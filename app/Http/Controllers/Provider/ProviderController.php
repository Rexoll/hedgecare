<?php

namespace App\Http\Controllers\Provider;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validator_query = Validator::make($request->query->all(), [
                "category" => "in:tutoring,housekeeping,rentafriend,maintenance,other|nullable",
                "search" => "nullable",
                "sortBy" => "in:name,rating,review,price|nullable",
                "services" => "regex:/^[\d,]+$/|nullable",
                "skills" => "regex:/^[\d,]+$/|nullable",
                "choosedDate" => "date|nullable",
                "start_time_available" => "date_format:H:i:s|required_with:end_time_available|nullable",
                "end_time_available" => "date_format:H:i:s|required_with:start_time_available|nullable",
                "lowest_price" => "integer|required_with:highest_price|nullable",
                "highest_price" => "integer|required_with:lowest_price|nullable",
                "latitude" => "numeric|required_with:longitude,radius|nullable",
                "longitude" => "numeric|required_with:latitude,radius|nullable",
                "radius" => "integer|nullable",
            ]);

            if ($validator_query->fails()) {
                return response()->json([
                    "message" => "Bad request query url",
                    "errors" => $validator_query->errors(),
                ], 400);
            }

            $validate_query = $validator_query->validate();

            $lat = $validate_query['latitude'] ?? null;
            $lng = $validate_query['longitude'] ?? null;
            $radius = $validate_query['radius'] ?? null;

            $providers = new Provider;
            if ($lat != null && $lng != null && $radius != null) {
                $providers = $providers->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance")
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance')
                    ->setBindings([$lat, $lng, $lat]);
            }
            $providers = $providers->whereHas("skills", function ($q) use ($validate_query) {
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
                ->whereTime('start_time_available', '>=', $validate_query["start_time_available"] ?? "00:00:00")
                ->whereTime('end_time_available', '<=', $validate_query["end_time_available"] ?? "23:00:00")

                ->whereBetween('price', [$validate_query["lowest_price"] ?? 1, $validate_query["highest_price"] ?? 150])

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

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'about' => 'string|nullable',
            'price' => 'numeric|nullable',
            'latitude' => 'numeric|nullable',
            'longitude' => 'numeric|nullable',
            'category' => 'in:tutoring,housekeeping,rentafriend,maintenance,other|nullable',
            'thumbnail' => 'nullable|file|mimes:jpeg,jpg,png',
            "skills.*" => "integer|nullable",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        $validate = Arr::where($validator->validate(), function ($value, $key) {
            return $value ?? null != null;
        });

        if ($validate['thumbnail'] ?? null != null) {
            $validate['thumbnail'] = Storage::putFileAs(
                'public/images',
                $validate['thumbnail'],
                'thumbnail-provider-' . $id . '.png',
            );
            $validate['thumbnail'] = getenv('APP_URL') . '/storage/images' . '/thumbnail-provider-' . $id . '.png';
        }

        $user = Provider::where('id', $id)->update($validate);
        if ($user < 1) {
            return response()->json([
                "message" => "data profile provider nothing has changed",
            ], 400);
        }

        $user = Provider::where('id', $id)->first();

        return response()->json([
            "message" => "success update provider profile",
            "data" => $user,
        ], 200);
    }
}
