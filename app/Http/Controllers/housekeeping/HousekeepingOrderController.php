<?php

namespace App\Http\Controllers\housekeeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingOrder;
use App\Models\HousekeepingOrderAdditionalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HousekeepingOrderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                "category_id" => "integer|required",
                "order_type" => "in:individual,business|required",
                "street_address_id" => "integer|required",
                "detail_address" => "string|nullable",
                "service_hours" => "string|required",
                "detail_service" => "string|required",
                "provider_id" => "integer|required",
                "start_date" => "date|required",
                "services" => "array|required",
                "services.*" => "integer|required"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad send body data",
                    "errors" => $validator->errors()
                ], 400);
            }
            ;

            $validate = $validator->validate();

            $housekeeping_order = HousekeepingOrder::create($validate);
            HousekeepingOrderAdditionalService::insert(
                array_map(function ($value) use ($housekeeping_order) {
                    return [
                        "order_id" => $housekeeping_order["id"],
                        "service_id" => $value,
                    ];
                }, $validate["services"]),
            );

            $housekeeping_order = HousekeepingOrder::where("id", $housekeeping_order["id"])->with(["services", "category", "address", "provider",])->first();

            DB::commit();

            return response()->json([
                "message" => "success create housekeeping order",
                "data" => $housekeeping_order,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }
}