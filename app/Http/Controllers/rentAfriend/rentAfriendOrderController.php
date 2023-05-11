<?php

namespace App\Http\Controllers\rentAfriend;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceRentAfriendOrder;
use App\Models\Provider;
use App\Models\rentAfriendOrder;
use App\Models\rentAfriendOrderAdditionalService;
use App\Models\rentAfriendSocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class rentAfriendOrderController extends Controller
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
                "category_id" => "integer|required",
                "service_hours" => "string|required",
                "detail_service" => "string|required",
                "provider_id" => "integer|required",
                "start_date" => "date|required",
                "socialmedia_contact" => "array|required",
                "socialmedia_contact.*.platform" => "string|required",
                "socialmedia_contact.*.username" => "string|required",
                "services" => "array|required",
                "services.*" => "integer|required",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad send body data",
                    "errors" => $validator->errors(),
                ], 400);
            };

            $validate = $validator->validate();
            $provider = Provider::where("id", $validate["provider_id"])->first();

            $rentAfriend_order = rentAfriendOrder::create([...$validate, "sub_total" => $provider->price]);
            rentAfriendOrderAdditionalService::insert(
                array_map(function ($value) use ($rentAfriend_order) {
                    return [
                        "order_id" => $rentAfriend_order["id"],
                        "service_id" => $value,
                    ];
                }, $validate["services"]),
            );

            rentAfriendSocialMedia::insert(
                array_map(function ($value) use ($rentAfriend_order) {
                    return [
                        "order_id" => $rentAfriend_order["id"],
                        "platform" => $value["platform"],
                        "username" => $value["username"],
                    ];
                }, $validate["socialmedia_contact"]),
            );

            $rentAfriend_order = rentAfriendOrder::where("id", $rentAfriend_order["id"])->with(["services", "category", "provider","socialmedia"])->first();

            return response()->json([
                "message" => "success create rent a friend order",
                "data" => $rentAfriend_order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\rentAfriendOrder  $rentAfriendOrder
     * @return \Illuminate\Http\Response
     */
    public function show(rentAfriendOrder $rentAfriendOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\rentAfriendOrder  $rentAfriendOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rentAfriendOrder $rentAfriendOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\rentAfriendOrder  $rentAfriendOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(rentAfriendOrder $rentAfriendOrder)
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

            $rentAfriend_order = rentAfriendOrder::where("id", $order_id)->first();

            if ($rentAfriend_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($rentAfriend_order->pay_with_card != null && $rentAfriend_order->pay_with_paypal != null) {
                return response()->json(["message" => "this order already paid"], 409);
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
                "description" => "Pay rent a friend Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $rentAfriend_order->tax = 2.50;
            $rentAfriend_order->first_name = $validate["first_name"];
            $rentAfriend_order->last_name = $validate["last_name"];
            $rentAfriend_order->phone_number = $validate["phone_number"];
            $rentAfriend_order->email = $validate["email"];
            $rentAfriend_order->pay_with_card = $charge["id"];
            $rentAfriend_order->save();

            $rentAfriend_order = rentAfriendOrder::where("id", $rentAfriend_order->id)->with(["category", "provider"])->first();

            Mail::to($validate["email"])->send(new InvoiceRentAfriendOrder($rentAfriend_order));

            return response()->json(["message" => "payment succeeded"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
