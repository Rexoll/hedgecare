<?php

namespace App\Http\Controllers\housekeeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingCategory;
use Illuminate\Http\Request;

class HousekeepingCategoryController extends Controller
{
    public function index()
    {
        $housekeeping_categories = HousekeepingCategory::with("services")->get();
        if ($housekeeping_categories->isEmpty()) {
            return response()->json([
                "message" => "housekeeping categories is empty"
            ], 404);
        }
        return response()->json([
            "message" => "success get housekeeping categories",
            "data" => array_map(function (array $value) {
                return [...$value, "thumbnail" => env("APP_URL") . $value["thumbnail"]];
            }, $housekeeping_categories->toArray()),
        ], 200);
    }
}