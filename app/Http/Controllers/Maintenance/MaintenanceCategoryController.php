<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceCategory;

class MaintenanceCategoryController extends Controller
{
    public function index()
    {
        try {
            $maintenance_categories = MaintenanceCategory::with(["services"])->get();
            if ($maintenance_categories->isEmpty()) {
                return response()->json([
                    "message" => "maintenance categories is empty",
                ], 404);
            }
            return response()->json([
                "message" => "success get maintenance categories",
                "data" => $maintenance_categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
