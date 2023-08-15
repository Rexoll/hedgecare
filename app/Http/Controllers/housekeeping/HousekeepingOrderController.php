<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceHousekeepingOrder;
use App\Models\HousekeepingOrder;
use App\Models\HousekeepingOrderAdditionalService;
use App\Models\Provider;
use Database\Seeders\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class HousekeepingOrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "category_id" => "integer|required",
                "order_type" => "in:individual,business|required",
                "street_address" => "string|required",
                "detail_address" => "string|nullable",
                "detail_service" => "string|required",
                "start_date" => "date|required",
                "provider_id" => "integer|required",
                "from_hour" => "integer|lt:to_hour|min:0|max:23",
                "to_hour" => "integer|gt:from_hour|min:1|max:24",
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

            $housekeeping_order = HousekeepingOrder::create([...$validate, 'user_id' => Auth()->user()->id, 'status' => 'not_paid', "sub_total" => ($provider->price * (($validate["to_hour"] ?? 2) - ($validate["from_hour"] ?? 1)))]);
            // $housekeeping_order->status = "not_paid";
            // $housekeeping_order->user_id = Auth()->user()->id;
            $housekeeping_order->save();
            if ($validate["services"] ?? null != null) {
                HousekeepingOrderAdditionalService::insert(
                    array_map(function ($value) use ($housekeeping_order) {
                        return [
                            "order_id" => $housekeeping_order["id"],
                            "service_id" => $value,
                        ];
                    }, $validate["services"]),
                );
            }

            $housekeeping_order = HousekeepingOrder::where("id", $housekeeping_order["id"])->with(["services", "category", "provider"])->first();

            return response()->json([
                "message" => "success create housekeeping order",
                "data" => $housekeeping_order,
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
                "exp_year" => "string|min:2|max:4|required",
                "cvc" => "string|digits:3|required",
            ]);

            if ($validator->fails()) {
                return response()->json(["message" => $validator->errors()], 400);
            }

            $validate = $validator->validate();

            $housekeeping_order = HousekeepingOrder::where("id", $order_id)->first();

            if ($housekeeping_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($housekeeping_order->pay_with_card != null && $housekeeping_order->pay_with_paypal != null) {
                return response()->json(["message" => "this order already pay"], 409);
            }

            $stripe = new StripeClient(env("STRIPE_SECRET"));
            try {
                $token = $stripe->tokens->create([
                    "card" => [
                        "number" => $validate["card_number"],
                        "exp_month" => $validate["exp_month"],
                        "exp_year" => $validate["exp_year"],
                        "cvc" => $validate["cvc"],
                    ],
                ]);
            } catch (\Throwable $th) {
                return response()->json(["message" => $th->getMessage()], 400);
            }

            $charge = $stripe->charges->create([
                "card" => $token["id"],
                "currency" => "USD",
                "amount" => ($housekeeping_order->sub_total + $housekeeping_order->tax) * 100,
                "description" => "Pay Housekeeping Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $housekeeping_order->tax = 2.50;
            $housekeeping_order->first_name = $validate["first_name"];
            $housekeeping_order->last_name = $validate["last_name"];
            $housekeeping_order->phone_number = $validate["phone_number"];
            $housekeeping_order->email = $validate["email"];
            $housekeeping_order->pay_with_card = $charge["id"];
            $housekeeping_order->status = "active";
            $housekeeping_order->save();

            $housekeeping_order = HousekeepingOrder::where("id", $housekeeping_order->id)->with(["category", "provider"])->first();

            Mail::to($validate["email"])->send(new InvoiceHousekeepingOrder($housekeeping_order, substr($validate["card_number"], -4)));
            Mail::to('cs@hedgecare.ca')->send(new InvoiceHousekeepingOrder($housekeeping_order, substr($validate["card_number"], -4)));

            return response()->json(["message" => "payment succeeded", "data" => $housekeeping_order], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function review(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'review' => 'required|min:1|max:5',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            }
            $findOrder = HousekeepingOrder::findOrFail($id);
            $findOrder->update([
                'review' => $request->review,
                'status' => 'done'
            ]);
            return response()->json([
                'message' => 'successfully submited review',
                'review' => $findOrder->review
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateOrder(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'detail_service' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors()
                ], 400);
            }
            $findOrder = HousekeepingOrder::findOrFail($id);
            $findOrder->update([
                'detail_service' => $request->detail_service,
            ]);
            return response()->json([
                'message' => 'successfully submited details',
                'detail_service' => $findOrder->detail_service,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
