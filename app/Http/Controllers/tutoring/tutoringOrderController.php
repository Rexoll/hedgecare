<?php

namespace App\Http\Controllers\tutoring;

use App\Http\Controllers\Controller;
use App\Models\selected_course;
use App\Models\tutoringOrder;
use Illuminate\Http\Request;
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
                'session' => 'integer|required',
                'start_date' => 'date|required',
                'tutoring_hours' => 'string|required',
                'provider_id' => 'integer|required',
                'skills' => 'array|required',
                'skills.*' => 'integer|required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad send body data",
                    "errors" => $validator->errors(),
                ], 400);
            }
            ;

            $validate = $validator->validate();

            $tutoring_order = tutoringOrder::create($validate);
            selected_course::insert(
                array_map(function ($value) use ($tutoring_order) {
                    return [
                        "tutoring_order_id" => $tutoring_order["id"],
                        "skill_id" => $value,
                    ];
                }, $validate["skills"]),
            );

            $tutoring_order = tutoringOrder::where("id", $tutoring_order["id"])->with(['skills', 'provider'])->first();

            return response()->json([
                "message" => "success create tutoring order",
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

            $housekeeping_order = tutoringOrder::where("id", $order_id)->first();

            if ($housekeeping_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($housekeeping_order->pay_with_card != null && $housekeeping_order->pay_with_paypal != null) {
                return response()->json(["message" => "this order already pay"], 409);
            }

            $stripe = new StripeClient(env("STRIPE_SECRET"));
            $token = $stripe->tokens->create([
                "card" => [
                    "number" => $validate["card_number"],
                    "exp_month" => $validate["exp_month"],
                    "exp_year" => $validate["exp_year"],
                    "cvc" => $validate["cvc"],
                ],
            ]);

            if (!isset($token["id"])) {
                return response()->json(["message" => "card not found"], 404);
            }

            $charge = $stripe->charges->create([
                "card" => $token["id"],
                "currency" => "USD",
                "amount" => 20 * 100,
                "description" => "Pay Housekeeping Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $housekeeping_order->pay_with_card = $charge["id"];
            $housekeeping_order->save();

            return response()->json(["message" => "payment succeeded"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
