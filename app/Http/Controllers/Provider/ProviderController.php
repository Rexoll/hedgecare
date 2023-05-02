<?php

namespace App\Http\Controllers\Provider;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validator_query = Validator::make($request->query->all(), [
                "category" => "in:tutoring,housekeeping,rentafriend,other|nullable",
                "search" => "nullable",
            ]);


            if ($validator_query->fails()) {
                return response()->json([
                    "message" => "Bad request url query",
                    "errors" => $validator_query->errors()
                ], 400);
            }

            $validate_query = $validator_query->validate();

            $providers = Provider::
                whereHas(
                    "user",
                    function ($q) use ($validate_query) {
                        $q->where("first_name", "LIKE", "%" . ($validate_query["search"] ?? "") . "%");
                        $q->orWhere("last_name", "LIKE", "%" . ($validate_query["search"] ?? "") . "%");
                    }
                )
                ->where([
                    ["category", "LIKE", "%" . ($validate_query["category"] ?? "") . "%"],
                ])
                ->with(["user", "skills"])
                ->get();

            return response()->json([
                "message" => "success get providers",
                "data" => array_map(function (array $value) {
                    return [...$value, "thumbnail" => env("APP_URL") . $value["thumbnail"]];
                }, $providers->toArray()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }
}