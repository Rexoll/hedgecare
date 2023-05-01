<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingAdditionalService;
use Illuminate\Http\Request;

class HousekeepingAdditionalServiceController extends Controller
{
    public function index()
    {
        $housekeeping_additional_services = HousekeepingAdditionalService::all();
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