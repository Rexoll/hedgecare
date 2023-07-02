<?php

namespace App\Http\Controllers\jobBoard;

use App\Http\Controllers\Controller;
use App\Mail\invoiceJobBoardOrders;
use App\Models\jobBoardOrderAdditionalService;
use App\Models\jobBoardOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class jobBoardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "street_address" => "string|required",
                "detail_address" => "string|nullable",
                "detail_service" => "string|required",
                "service_name" => "string|required",
                "start_date" => "date|required",
                "from_hour" => "integer|lt:to_hour|min:0|max:23|required",
                "to_hour" => "integer|gt:from_hour|min:1|max:24|required",
                "sub_total" => "integer|required",
                "services.*" => "integer|required",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad send body data",
                    "errors" => $validator->errors(),
                ], 400);
            };

            $validate = $validator->validate();

            $jobBoard_order = jobBoardOrders::create([
                "street_address" => $validate['street_address'],
                "detail_address" => $validate['detail_address'] ?? null,
                "service_name" => $validate['service_name'],
                "detail_service" => $validate['detail_service'],
                "start_date" => $validate['start_date'],
                "from_hour" => $validate['from_hour'],
                "to_hour" => $validate['to_hour'],
                "sub_total" => $validate['sub_total'],
                "status" => 'not_paid',
                "user_id" => Auth::user()->id,
            ]);

            $get_jobBoard = jobBoardOrders::where('id', $jobBoard_order->id)->first();
            $value = $validate['service_name'];
            switch ($value) {
                case 'housekeeping':
                    foreach ($validate['services'] as $data) {
                        jobBoardOrderAdditionalService::create([
                            'order_id' => $get_jobBoard['id'],
                            'housekeeping_id' => $data,
                        ]);
                    }
                    break;
                case 'maintenance':
                    foreach ($validate['services'] as $data) {
                        jobBoardOrderAdditionalService::create([
                            'order_id' => $get_jobBoard['id'],
                            'maintenance_id' => $data,
                        ]);
                    }
                    break;
                case 'rentafriend':
                    foreach ($validate['services'] as $data) {
                        jobBoardOrderAdditionalService::create([
                            'order_id' => $get_jobBoard['id'],
                            'rentafriend_id' => $data,
                        ]);
                    }
                    break;
                default:
                    return response()->json([
                        'data' => 'Service not found, please insert a right service'
                    ], 404);
                    break;
            }

            $jobBoard_order = jobBoardOrders::where("id", $jobBoard_order["id"])->with('user')->first();

            //search for service_name
            switch ($value) {
                case 'housekeeping':
                    $find = jobBoardOrderAdditionalService::where('order_id', $jobBoard_order->id)->with('housekeeping')->get();
                    break;
                case 'maintenance':
                    $find = jobBoardOrderAdditionalService::where('order_id', $jobBoard_order->id)->with('maintenance')->get();
                    break;
                case 'rentafriend':
                    $find = jobBoardOrderAdditionalService::where('order_id', $jobBoard_order->id)->with('rentafriend')->get();
                    break;
                default:
                    return response()->json(['message' => 'notfound'], 404);
                    break;
            }

            return response()->json([
                "message" => "success create Job Board order",
                "data" => [
                    $jobBoard_order,
                    $find,
                ],
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

            $jobBoard_order = jobBoardOrders::where("id", $order_id)->first();

            if ($jobBoard_order == null) {
                return response()->json(["message" => "order not found"], 404);
            }

            if ($jobBoard_order->pay_with_card != null && $jobBoard_order->pay_with_paypal != null) {
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
                "amount" => ($jobBoard_order->sub_total + $jobBoard_order->tax) * 100,
                "description" => "Pay Job Board Order",
            ]);

            if ($charge["status"] == "failed") {
                return response()->json(["message" => $charge["failure_message"]], 406);
            } else if ($charge["status"] == "pending") {
                return response()->json(["message" => "payment pending"], 202);
            }

            $jobBoard_order->tax = 2.50;
            $jobBoard_order->first_name = $validate["first_name"];
            $jobBoard_order->last_name = $validate["last_name"];
            $jobBoard_order->phone_number = $validate["phone_number"];
            $jobBoard_order->email = $validate["email"];
            $jobBoard_order->pay_with_card = $charge["id"];
            $jobBoard_order->status = "active";
            $jobBoard_order->save();

            $jobBoard_order = jobBoardOrders::where("id", $jobBoard_order->id)->with(["category", "provider"])->first();

            Mail::to($validate["email"])->send(new invoiceJobBoardOrders($jobBoard_order, substr($validate["card_number"], -4)));

            return response()->json(["message" => "payment succeeded", "data" => $jobBoard_order], 200);
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
            $findOrder = jobBoardOrders::findOrFail($id);
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
            $findOrder = jobBoardOrders::findOrFail($id);
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

    public function get(Request $request)
    {
        try {
            $get = jobBoardOrders::with(['user', 'order.maintenance', 'order.housekeeping', 'order.rentafriend'])->paginate(10);
            return response()->json([
                'data' => $get
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
