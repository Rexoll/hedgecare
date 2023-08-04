<?php

namespace App\Http\Controllers\Provider;

use App\Models\Provider;
use App\Models\Skill;
use Hamcrest\Core\IsTypeOf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
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

            $providers =  Provider::query();
            if ($lat != null && $lng != null && $radius != null) {
                $providers->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance")
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance')
                    ->setBindings([$lat, $lng, $lat]);
            }

            if ($validate_query["skills"] ?? null) {
                $providers->whereHas("skills", function ($q) use ($validate_query) {
                    $i = 0;
                    foreach (explode(',', $validate_query["skills"]) as $skill) {
                        if ($i == 0) {
                            $q->Where("skills.id", "=", $skill);
                        } else {
                            $q->orWhere("skills.id", "=", $skill);
                        }
                        $i++;
                    }
                });
            }

            if ($validate_query['search'] ?? null) {
                $providers->whereHas(
                    "user",
                    function ($q) use ($validate_query) {
                        $q->where("first_name", "LIKE", "%" . ($validate_query["search"]) . "%")
                            ->orWhere("last_name", "LIKE", "%" . ($validate_query["search"]) . "%");
                    }
                );
            }

            if ($validate_query['services'] ?? null) {
                $providers->whereHas("services", function ($q) use ($validate_query) {
                    $i = 0;
                    foreach (explode(',', $validate_query["services"]) as $service) {
                        if ($i == 0) {
                            $q->Where("id", "=", $service);
                        } else {
                            $q->orWhere("id", "=", $service);
                        }
                        $i++;
                    }
                });
            }

            if ($validate_query['start_time_available'] ?? null && $validate_query["end_time_available"] ?? null) {
                $providers->whereTime('start_time_available', '>=', $validate_query["start_time_available"])
                    ->whereTime('end_time_available', '<=', $validate_query["end_time_available"]);
            }

            if ($validate_query['lowest_price'] ?? null && $validate_query["highest_price"] ?? null) {
                $providers->whereBetween('price', [$validate_query["lowest_price"], $validate_query["highest_price"]]);
            }

            if ($validate_query['active_days'] ?? null) {
                $providers->where("active_days", "LIKE", "%" . date('l', strtotime($validate_query["choosedDate"])) . "%");
            }
            if ($validate_query['active_days'] ?? null) {
                $providers->where("category", "LIKE", "%" . $validate_query["category"] . "%");
            }

            $providers = collect($providers->with(["user", "skills", "services"])->get());
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
            'address' => 'string|nullable',
            'price' => 'numeric|nullable',
            'latitude' => 'numeric|nullable',
            'longitude' => 'numeric|nullable',
            'category' => 'in:tutoring,housekeeping,rentafriend,maintenance,other|nullable',
            'thumbnail' => 'nullable|file|mimes:jpeg,jpg,png',
            "skills" => "nullable",
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


        if ($validate['skills'] ?? null != null) {
            if (gettype($validate['skills']) == 'string') {
                $validate['skills'] = json_decode($validate['skills']);
            }
            $provider = Provider::where('id', $id)->first();
            $provider->skills()->sync($validate['skills']);
            unset($validate['skills']);
        }

        $provider = Provider::where('id', $id)->update($validate);
        $provider = Provider::where('id', $id)->with('skills')->first();
        $provider->markEmailAsVerified();

        return response()->json([
            "message" => "success update provider profile",
            "data" => $provider,
        ], 200);
    }

    public function destroy($id)
    {
        try {
            Provider::findOrFail($id)->delete();
            return response()->json(['message' => 'Successfull deleted'], 200);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Data provider not found or deleted'], 404);
        }
    }
}
