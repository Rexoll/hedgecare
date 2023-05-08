<?php

namespace App\Http\Controllers\Provider;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validator_query = Validator::make($request->query->all(), [
                "category" => "in:tutoring,housekeeping,rentafriend,other|nullable",
                "search" => "nullable",
                "sortBy" => "in:name,rating,review,price|nullable",
                "services" => "regex:/^[\d,]+$/|nullable",
                "skills" => "regex:/^[\d,]+$/|nullable",
            ]);

            if ($validator_query->fails()) {
                return response()->json([
                    "message" => "Bad request query url",
                    "errors" => $validator_query->errors()
                ], 400);
            }

            $validate_query = $validator_query->validate();

            $providers = Provider::
                whereHas("skills", function ($q) use ($validate_query) {
                    if ($validate_query["skills"] ?? null != null) {
                        $i = 0;
                        foreach (explode(',', $validate_query["skills"]) as $skill) {
                            if ($i == 0) {
                                $q->Where("skills.id", "=", $skill);
                            } else {
                                $q->orWhere("skills.id", "=", $skill);
                            }
                            $i++;
                        }
                    }
                })
                ->whereHas(
                    "user",
                    function ($q) use ($validate_query) {
                        if ($validate_query["search"] ?? null != null) {
                            $q->where("first_name", "LIKE", "%" . ($validate_query["search"]) . "%")
                                ->orWhere("last_name", "LIKE", "%" . ($validate_query["search"]) . "%");
                        }
                    }
                )
                ->where([
                    ["category", "LIKE", "%" . ($validate_query["category"] ?? "") . "%"],
                ])
                ->whereHas("services", function ($q) use ($validate_query) {
                    if ($validate_query["services"] ?? null != null) {
                        $i = 0;
                        foreach (explode(',', $validate_query["services"]) as $service) {
                            $q->whereHas("service", function ($q) use ($service, $i) {
                                if ($i == 0) {
                                    $q->Where("id", "=", $service);
                                } else {
                                    $q->orWhere("id", "=", $service);
                                }
                            });
                            $i++;
                        }
                    }
                })
                ->with(["user", "skills", "services"])
                ->get();

            $providers = collect($providers);
            if (($validate_query["sortBy"] ?? null) == "name") {
                $providers = $providers->sortBy([
                    function ($a, $b) {
                        return strcmp(strtolower($a->user->first_name), strtolower($b->user->first_name));
                    },
                    function ($a, $b) {
                        return strcmp(strtolower($a->user->last_name), strtolower($b->user->last_name));
                    },
                ]);
            } else if (($validate_query["sortBy"] ?? null) != null) {
                $providers = $providers->sortByDesc($validate_query["sortBy"]);
            }

            return response()->json([
                "message" => "success get providers",
                "data" => $providers->values(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }
}