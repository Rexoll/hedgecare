<?php

namespace App\Http\Controllers;

use App\Mail\requestAquot;
use App\Models\CustomOrder;
use App\Models\HousekeepingOrder;
use App\Models\jobBoardOrders;
use App\Models\MaintenanceOrder;
use App\Models\Provider;
use App\Models\rating;
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
                    rating::create([
                        'provider_id' => $housekeepingOrder->provider_id,
                        'ratings' => $rating,
                    ]);
                    $calculate = rating::where('provider_id', $housekeepingOrder->provider_id)->get();
                    $average = $calculate->average('ratings');
                    $sum = $calculate->count();
                    $housekeepingOrder->update([
                        'rating' => $rating,
                    ]);
                    Provider::where('id', $housekeepingOrder->provider_id)->update([
                        'rating' => $average,
                        'review' => $sum
                    ]);
                    break;

                case 'rentafriend':
                    $rentAFriendOrder = rentAfriendOrder::find($order_id);
                    if (is_null($rentAFriendOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    rating::create([
                        'provider_id' => $rentAFriendOrder->provider_id,
                        'ratings' => $rating,
                    ]);
                    $calculate = rating::where('provider_id', $rentAFriendOrder->provider_id)->get();
                    $average = $calculate->average('ratings');
                    $sum = $calculate->count();
                    $rentAFriendOrder->update([
                        'rating' => $rating,
                    ]);
                    Provider::where('id', $rentAFriendOrder->provider_id)->update([
                        'rating' => $average,
                        'review' => $sum
                    ]);
                    break;

                case 'maintenance':
                    $maintenanceOrder = MaintenanceOrder::find($order_id);
                    if (is_null($maintenanceOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    rating::create([
                        'provider_id' => $maintenanceOrder->provider_id,
                        'ratings' => $rating,
                    ]);
                    $calculate = rating::where('provider_id', $maintenanceOrder->provider_id)->get();
                    $average = $calculate->average('ratings');
                    $sum = $calculate->count();
                    $maintenanceOrder->update([
                        'rating' => $rating,
                    ]);
                    Provider::where('id', $maintenanceOrder->provider_id)->update([
                        'rating' => $average,
                        'review' => $sum
                    ]);
                    break;

                case 'other':
                    $customOrder = CustomOrder::find($order_id);
                    if (is_null($customOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    rating::create([
                        'provider_id' => $customOrder->provider_id,
                        'ratings' => $rating,
                    ]);
                    $calculate = rating::where('provider_id', $customOrder->provider_id)->get();
                    $average = $calculate->average('ratings');
                    $sum = $calculate->count();
                    $customOrder->update([
                        'rating' => $rating,
                    ]);
                    Provider::where('id', $customOrder->provider_id)->update([
                        'rating' => $average,
                        'review' => $sum
                    ]);
                    break;

                case 'job-board':
                    $jobBoard = jobBoardOrders::find($order_id);
                    if (is_null($jobBoard)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    rating::create([
                        'provider_id' => $jobBoard->provider_id,
                        'ratings' => $rating,
                    ]);
                    $calculate = rating::where('provider_id', $jobBoard->provider_id)->get();
                    $average = $calculate->average('ratings');
                    $sum = $calculate->count();
                    $jobBoard->update([
                        'rating' => $rating,
                    ]);
                    Provider::where('id', $jobBoard->provider_id)->update([
                        'rating' => $average,
                        'review' => $sum
                    ]);
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

    public function setAsDone(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'service' => 'required',
                'order_id' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            }
            //ambil data yang diperlukan dari body
            $service = $request->service;
            $order_id = $request->order_id;

            //berikan rating sesuai service yang dipilih
            switch ($service) {
                case 'housekeeping':
                    $housekeepingOrder = HousekeepingOrder::find($order_id);
                    if (is_null($housekeepingOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $housekeepingOrder->status = 'done';
                    $housekeepingOrder->save();
                    break;

                case 'rentafriend':
                    $rentAFriendOrder = rentAfriendOrder::find($order_id);
                    if (is_null($rentAFriendOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $rentAFriendOrder->status = 'done';
                    $rentAFriendOrder->save();
                    break;

                case 'maintenance':
                    $maintenanceOrder = MaintenanceOrder::find($order_id);
                    if (is_null($maintenanceOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $maintenanceOrder->status = 'done';
                    $maintenanceOrder->save();
                    break;

                case 'other':
                    $customOrder = CustomOrder::find($order_id);
                    if (is_null($customOrder)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $customOrder->status = 'done';
                    $customOrder->save();
                    break;

                case 'job-board':
                    $jobBoard = jobBoardOrders::find($order_id);
                    if (is_null($jobBoard)) {
                        return response()->json(['message' => 'order_id not found'], 404);
                    }
                    $jobBoard->status = 'done';
                    $jobBoard->save();
                    break;

                default:
                    return response()->json(['message' => 'Invalid service'], 400);
            }
            // Berikan response yang sesuai
            return response()->json(['message' => 'set status done successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function requestAquot(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'email',
                'message' => 'required',
                'phone_number' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }

            $validate = $validate->validate();

            Mail::to($validate["email"])->send(new requestAquot($validate));

            return response()->json([
                'message' => 'email sended',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
