<?php

namespace App\Http\Controllers;

use App\Mail\requestAquot;
use App\Models\CustomOrder;
use App\Models\HousekeepingOrder;
use App\Models\jobBoardOrders;
use App\Models\MaintenanceOrder;
use App\Models\rentAfriendOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class orderController extends Controller
{
    public function rating(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'service' => 'required',
                'order_id' => 'required',
                'rating' => 'required|min:1|max:5',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            }
            //ambil data yang diperlukan dari body
            $service = $request->service;
            $order_id = $request->order_id;
            $rating = $request->rating;

            //berikan rating sesuai service yang dipilih
            switch ($service) {
                case 'housekeeping':
                    $housekeepingOrder = HousekeepingOrder::find($order_id);
                    if (is_null($housekeepingOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $housekeepingOrder->rating = $rating;
                    $housekeepingOrder->save();
                    break;

                case 'rentafriend':
                    $rentAFriendOrder = rentAfriendOrder::find($order_id);
                    if (is_null($rentAFriendOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $rentAFriendOrder->rating = $rating;
                    $rentAFriendOrder->save();
                    break;

                case 'maintenance':
                    $maintenanceOrder = MaintenanceOrder::find($order_id);
                    if (is_null($maintenanceOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $maintenanceOrder->rating = $rating;
                    $maintenanceOrder->save();
                    break;

                case 'other':
                    $customOrder = CustomOrder::find($order_id);
                    if (is_null($customOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $customOrder->rating = $rating;
                    $customOrder->save();
                    break;

                default:
                    return response()->json(['message' => 'Invalid service'], 400);
            }
            // Berikan response yang sesuai
            return response()->json(['message' => 'Rating has been given successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'service' => 'required',
                'order_id' => 'required',
                'detail_service' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            }
            //ambil data yang diperlukan dari body
            $service = $request->service;
            $order_id = $request->order_id;
            $detail_service = $request->detail_service;

            //berikan detail_service sesuai service yang dipilih
            switch ($service) {
                case 'housekeeping':
                    $housekeepingOrder = HousekeepingOrder::find($order_id);
                    if (is_null($housekeepingOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $housekeepingOrder->detail_service = $detail_service;
                    $housekeepingOrder->save();
                    break;

                case 'rentafriend':
                    $rentAFriendOrder = rentAfriendOrder::find($order_id);
                    if (is_null($rentAFriendOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $rentAFriendOrder->detail_service = $detail_service;
                    $rentAFriendOrder->save();
                    break;

                case 'maintenance':
                    $maintenanceOrder = MaintenanceOrder::find($order_id);
                    if (is_null($maintenanceOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $maintenanceOrder->detail_service = $detail_service;
                    $maintenanceOrder->save();
                    break;

                case 'other':
                    $customOrder = CustomOrder::find($order_id);
                    if (is_null($customOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $customOrder->detail_service = $detail_service;
                    $customOrder->save();
                    break;

                case 'job-board':
                    $jobBoard = jobBoardOrders::find($order_id);
                    if (is_null($jobBoard)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $jobBoard->detail_service = $detail_service;
                    $jobBoard->save();
                    break;

                default:
                    return response()->json(['message' => 'Invalid service'], 400);
                    break;
            }
            // Berikan response yang sesuai
            return response()->json(['message' => 'detail service has been updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function requestAquot(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'email',
            'message' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }

        $validate = $validate->validate();

        Mail::to($validate["email"])->send(new requestAquot($validate));

        return response()->json([
            'message' => 'email sended'
        ], 200);
    }
}
