<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Mail\HouseKeepingOrderNotification;
use App\Models\HousekeepingOrder;
use App\Models\HousekeepingOrderAdditionalService;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Stripe;

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
                "from_hour" => "integer|min:0|max:23",
                "expected_hour" => "integer|min:1|max:24",
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

            $sub_total = $provider->price * $request->expected_hour;
            $housekeeping_order = HousekeepingOrder::create([...$validate, 'status' => 'not_paid', 'user_id' => Auth::user()->id, "sub_total" => $sub_total, 'tax' => $sub_total * 0.13]);
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

            //stripe site
            Stripe::setApiKey(env("STRIPE_SECRET"));
            try {
                $productPrice = Price::create([
                    'unit_amount' => (int) (($housekeeping_order->sub_total + $housekeeping_order->tax) * 100), // Harga dalam sen, misalnya $10 dalam sen
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Housekeeping',
                    ],
                ]);
            } catch (\Throwable $th) {
                return response()->json(["message" => $th->getMessage()], 400);
            }

            $checkout_session = Session::create([
                'ui_mode' => 'embedded',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $productPrice->id, // Ganti dengan ID harga produk Anda
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'redirect_on_completion' => 'never',
                // 'success_url' => 'https://hedgecare.ca',
                // 'cancel_url' => 'https://hedgecare.ca',
            ]);

            //end of stripe

            $housekeeping_order = HousekeepingOrder::where("id", $housekeeping_order["id"])->with(["services", "category", "provider"])->first();
            $mail = User::where('id', $provider->user_id)->first();
            Mail::to($mail->email)->send(new HousekeepingOrderNotification($housekeeping_order));

            return response()->json([
                "message" => "success create housekeeping order",
                "data" => $housekeeping_order,
                "client_secret" => $checkout_session['client_secret'],
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
                "card_number" => "string|digits:16|nullable",
                "exp_month" => "string|digits:2|nullable",
                "exp_year" => "string|min:2|max:4|nullable",
                "cvc" => "string|digits:3|nullable",
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

            $housekeeping_order->first_name = $validate["first_name"];
            $housekeeping_order->last_name = $validate["last_name"];
            $housekeeping_order->phone_number = $validate["phone_number"];
            $housekeeping_order->email = $validate["email"];
            $housekeeping_order->status = "active";
            $housekeeping_order->save();

            $housekeeping_order = HousekeepingOrder::where("id", $housekeeping_order->id)->with(["category", "provider"])->first();

            return response()->json([
                "message" => "payment registered",
                "data" => $housekeeping_order,
            ], 200);
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
                'status' => 'done',
            ]);
            return response()->json([
                'message' => 'successfully submited review',
                'review' => $findOrder->review,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateOrder(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'detail_service' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
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
