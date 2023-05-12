<?php

namespace App\Http\Controllers\Custom;

use App\Mail\InvoiceCustomOrder;
use App\Models\CustomOrder;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class CustomOrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "street_address" => "string|required",
                "detail_address" => "string|nullable",
                "service_hours" => "string|required",
                "detail_service" => "string|required",
                "provider_id" => "integer|required",
                "start_date" => "date|required",
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

            $custom_order = CustomOrder::create([...$validate, "sub_total" => $provider->price]);

            $custom_order = CustomOrder::where("id", $custom_order["id"])->with(["provider"])->first();

            return response()->json([
                "message" => "success create custom order",
                "data" => $custom_order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }

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

            $custom_order = CustomOrder::where("id", $order_id)->first();

            if ($custom_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($custom_order->pay_with_card != null && $custom_order->pay_with_paypal != null) {
                return response()->json(["message" => "this order already pay"], 409);
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
                "description" => "Pay Custom Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $custom_order->tax = 2.50;
            $custom_order->first_name = $validate["first_name"];
            $custom_order->last_name = $validate["last_name"];
            $custom_order->phone_number = $validate["phone_number"];
            $custom_order->email = $validate["email"];
            $custom_order->pay_with_card = $charge["id"];
            $custom_order->save();

            $custom_order = CustomOrder::where("id", $custom_order->id)->with(["provider"])->first();


            Mail::to($validate["email"])->send(new InvoiceCustomOrder($custom_order));

            return response()->json(["message" => "payment succeeded", "data" => $custom_order], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}