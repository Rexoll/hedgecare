<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMaintenanceOrder;
use App\Mail\MaintenanceOrderNotification;
use App\Models\MaintenanceOrder;
use App\Models\MaintenanceOrderAdditionalService;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Stripe;
use Stripe\StripeClient;

class MaintenanceOrderController extends Controller
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

            $sub_total = ($provider->price * $request->expected_hour);
            $maintenance_order = MaintenanceOrder::create([...$validate, 'status' => 'not_paid', 'user_id' => Auth::user()->id, "sub_total" => $sub_total + 4.99, 'tax' => $sub_total * 0.13]);
            $maintenance_order->save();
            if ($validate["services"] ?? null != null) {
                MaintenanceOrderAdditionalService::insert(
                    array_map(function ($value) use ($maintenance_order) {
                        return [
                            "order_id" => $maintenance_order["id"],
                            "service_id" => $value,
                        ];
                    }, $validate["services"]),
                );
            }

            //stripe site
            Stripe::setApiKey(env("STRIPE_SECRET"));
            try {
                $productPrice = Price::create([
                    'unit_amount' => (int) (($maintenance_order->sub_total + $maintenance_order->tax) * 100), // Harga dalam sen, misalnya $10 dalam sen
                    'currency' => 'cad',
                    'product_data' => [
                        'name' => 'Maintenance',
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
                'customer_email' => Auth::user()->email,
                'mode' => 'payment',
                'redirect_on_completion' => 'always',
                'return_url' => 'https://hedgecare.ca/order/success?session_id={CHECKOUT_SESSION_ID}',
                'metadata' => [
                    'product_name' => 'Maintenance', // Nama produk atau informasi lain yang sesuai
                ],
            ]);

            //end of stripe

            //save session id to DB
            MaintenanceOrder::where('id', $maintenance_order->id)->update([
                'session_id' => $checkout_session->id,
                'first_name' => Auth::user()->first_name,
                'last_name' => Auth::user()->last_name,
                'phone_number' => Auth::user()->phone_number,
                'email' => Auth::user()->email,
            ]);

            $maintenance_order = MaintenanceOrder::where("id", $maintenance_order["id"])->with(["services", "category", "provider"])->first();
            $mail = User::where('id', $provider->user_id)->first();
            Mail::to($mail->email)->send(new MaintenanceOrderNotification($maintenance_order));

            return response()->json([
                "message" => "success create maintenance order",
                "data" => $maintenance_order,
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
                "card_number" => "string|digits:16|required",
                "exp_month" => "string|digits:2|required",
                "exp_year" => "string|min:2|max:4|required",
                "cvc" => "string|digits:3|required",
            ]);

            if ($validator->fails()) {
                return response()->json(["message" => $validator->errors()], 400);
            }

            $validate = $validator->validate();

            $maintenance_order = MaintenanceOrder::where("id", $order_id)->first();

            if ($maintenance_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($maintenance_order->pay_with_card != null && $maintenance_order->pay_with_paypal != null) {
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
                "amount" => (int) (($maintenance_order->sub_total + $maintenance_order->tax + 499) * 100),
                "description" => "Pay Maintenance Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $maintenance_order->first_name = $validate["first_name"];
            $maintenance_order->last_name = $validate["last_name"];
            $maintenance_order->phone_number = $validate["phone_number"];
            $maintenance_order->email = $validate["email"];
            $maintenance_order->pay_with_card = $charge["id"];
            $maintenance_order->status = "active";
            $maintenance_order->save();

            $maintenance_order = MaintenanceOrder::where("id", $maintenance_order->id)->with(["category", "provider"])->first();

            Mail::to($validate["email"])->send(new InvoiceMaintenanceOrder($maintenance_order, substr($validate["card_number"], -4)));

            return response()->json(["message" => "payment succeeded", "data" => $maintenance_order], 200);
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
            $findOrder = MaintenanceOrder::findOrFail($id);
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
            $findOrder = MaintenanceOrder::findOrFail($id);
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
