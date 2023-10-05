<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingAdditionalService;

class HousekeepingAdditionalServiceController extends Controller
{
    public function index()
    {
        $housekeeping_additional_services = HousekeepingAdditionalService::with(["category", "skill"])->get();
        if ($housekeeping_additional_services->isEmpty()) {
            return response()->json([
                "message" => "housekeeping addtional service is empty",
            ], 404);
        }
        return response()->json([
            "message" => "success get housekeeping additional services",
            "data" => $housekeeping_additional_services,
        ], 200);
    }
}
