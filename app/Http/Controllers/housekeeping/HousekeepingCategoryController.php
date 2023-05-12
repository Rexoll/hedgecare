<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingCategory;
use Illuminate\Http\Request;

class HousekeepingCategoryController extends Controller
{
    public function index()
    {
        $housekeeping_categories = HousekeepingCategory::with(["services"])->get();
        if ($housekeeping_categories->isEmpty()) {
            return response()->json([
                "message" => "housekeeping categories is empty"
            ], 404);
        }
        return response()->json([
            "message" => "success get housekeeping categories",
            "data" => $housekeeping_categories,
        ], 200);
    }
}