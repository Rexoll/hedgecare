<?php

namespace App\Http\Controllers\API\house_keeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingAdditionalService;
use Illuminate\Http\Request;

class HousekeepingAdditionalServiceController extends Controller
{
    public function index()
    {
        $housekeeping_additional_services = HousekeepingAdditionalService::with("category")->get();
        if ($housekeeping_additional_services->isEmpty()) {
            return response()->json([
                "message" => "housekeeping addtional service is empty"
            ], 404);
        }
        return response()->json([
            "message" => "success get housekeeping additional services",
            "data" => $housekeeping_additional_services,
        ], 200);
    }
}
