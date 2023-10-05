<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceAdditionalService;

class MaintenanceAdditionalServiceController extends Controller
{
    public function index()
    {
        try {
            $maintenance_additional_services = MaintenanceAdditionalService::with(["category", "skill"])->get();
            if ($maintenance_additional_services->isEmpty()) {
                return response()->json([
                    "message" => "maintenance addtional service is empty",
                ], 404);
            }
            return response()->json([
                "message" => "success get maintenance additional services",
                "data" => $maintenance_additional_services,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
