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
                "choosedDate" => "date|nullable",
                "start_time_available" => "date_format:H:i:s|nullable",
                "end_time_available" => "date_format:H:i:s|nullable",
                "lowest_price" => "integer|nullable",
                "highest_price" => "integer|nullable",
            ]);

            if ($validator_query->fails()) {
                return response()->json([
                    "message" => "Bad request query url",
                    "errors" => $validator_query->errors(),
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
                    ["active_days", "LIKE", "%" . (($validate_query["choosedDate"] ?? null) == null ? "" : date('l', strtotime($validate_query["choosedDate"]))) . "%"],
                ])
                ->whereHas("services", function ($q) use ($validate_query) {
                    if ($validate_query["services"] ?? null != null) {
                        $i = 0;
                        foreach (explode(',', $validate_query["services"]) as $service) {
                            if ($i == 0) {
                                $q->Where("id", "=", $service);
                            } else {
                                $q->orWhere("id", "=", $service);
                            }
                            $i++;
                        }
                    }
                })
                ->whereTime('start_time_available', '>=', $validate_query["start_time_available"] ?? 0)
                ->whereTime('end_time_available', '<=', $validate_query["end_time_available"] ?? 24)

                ->whereBetween('price', [$validate_query["lowest_price"] ?? 1, $validate_query["highest_price"] ?? 150])

                // FILTRASI RADIUS
                // $lat = $request->get('lat');
                // $lng = $request->get('lng');
                // $radius = $request->get('radius');
                // ->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance")
                // ->where('status', 'active')
                // ->having('distance', '<=', $radius)
                // ->orderBy('distance')
                // ->setBindings([$lat, $lng, $lat])

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