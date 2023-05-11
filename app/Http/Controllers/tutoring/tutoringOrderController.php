<?php

namespace App\Http\Controllers\tutoring;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceTutoringOrder;
use App\Models\Provider;
use App\Models\selected_course;
use App\Models\tutoringOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class tutoringOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_type' => 'in:individual,business|required',
                'environment' => 'in:individual,group_lessons|required',
                'session' => 'string|required',
                'start_date' => 'date|required',
                'tutoring_hours' => 'string|required',
                'provider_id' => 'integer|required',
                'skills' => 'array|required',
                'skills.*' => 'integer|required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad send body data",
                    "errors" => $validator->errors()
                ], 400);
            }
            ;

            $validate = $validator->validate();

            $provider = Provider::where("id", $validate["provider_id"])->first();

            $tutoring_order = tutoringOrder::create([...$validate, "sub_total" => $provider->price]);
            selected_course::insert(
                array_map(function ($value) use ($tutoring_order) {
                    return [
                        "tutoring_order_id" => $tutoring_order["id"],
                        "skill_id" => $value,
                    ];
                }, $validate["skills"]),
            );

            $tutoring_order = tutoringOrder::where("id", $tutoring_order["id"])->with(["skills", "provider"])->first();

            return response()->json([
                "message" => "success create housekeeping order",
                "data" => $tutoring_order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tutoringOrder  $tutoringOrder
     * @return \Illuminate\Http\Response
     */
    public function show(tutoringOrder $tutoringOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tutoringOrder  $tutoringOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tutoringOrder $tutoringOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tutoringOrder  $tutoringOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(tutoringOrder $tutoringOrder)
    {
        //
    }

    public function payWithCard(Request $request, int $order_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                "first_name" => "string|required",
                "last_name" => "string|required",
                "phone_number" => "regex:/^\+[1-9]{1}[0-9]{3,14}$/|required",
                "email" => "email|required",
                "card_number" => "string|digits:16|required",
                "exp_month" => "string|digits:2|required",
                "exp_year" => "string |digits:2|required",
                "cvc" => "string|digits:3|required",
            ]);

            if ($validator->fails()) {
                return response()->json(["message" => $validator->errors()], 400);
            }

            $validate = $validator->validate();

            $tutoring_order = tutoringOrder::where("id", $order_id)->first();

            if ($tutoring_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($tutoring_order->pay_with_card != null && $tutoring_order->pay_with_paypal != null) {
                return response()->json(["message" => "this order already paid"], 409);
            }

            $stripe = new StripeClient(env("STRIPE_SECRET"));
            $token = $stripe->tokens->create([
                "card" => [
                    "number" => $validate["card_number"],
                    "exp_month" => $validate["exp_month"],
                    "exp_year" => $validate["exp_year"],
                    "cvc" => $validate["cvc"],
                ]
            ]);

            if (!isset($token["id"])) {
                return response()->json(["message" => "card not found"], 404);
            }

            $charge = $stripe->charges->create([
                "card" => $token["id"],
                "currency" => "USD",
                "amount" => 20 * 100,
                "description" => "Pay tutoring Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $tutoring_order->tax = 2.50;
            $tutoring_order->first_name = $validate["first_name"];
            $tutoring_order->last_name = $validate["last_name"];
            $tutoring_order->phone_number = $validate["phone_number"];
            $tutoring_order->email = $validate["email"];
            $tutoring_order->pay_with_card = $charge["id"];
            $tutoring_order->save();

            $tutoring_order = tutoringOrder::where("id", $tutoring_order->id)->with("provider")->first();


            Mail::to($validate["email"])->send(new InvoiceTutoringOrder($tutoring_order));

            return response()->json(["message" => "payment succeeded"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
