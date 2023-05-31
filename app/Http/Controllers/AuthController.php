<?php

namespace App\Http\Controllers;

use App\Mail\RegisterProvider;
use App\Models\CustomOrder;
use App\Models\HousekeepingOrder;
use App\Models\MaintenanceOrder;
use App\Models\Provider;
use App\Models\rentAfriendOrder;
use App\Models\User;
use App\Notifications\UserVerifyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mail;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'email|required|min:6',
                'password' => 'required|confirmed|min:6',
                'phone_number' => 'regex:/^\+[1-9]{1}[0-9]{3,14}$/|required',
                'first_name' => 'string|required',
                'last_name' => 'string|required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            } else {
                $register = User::create([
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'role' => 'user',
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'password' => Hash::make($request->password),
                ]);

                $token = $register->createToken('register_token')->plainTextToken;

                $register->notify(new UserVerifyNotification($token));

                return response()->json([
                    'message' => 'register successfull',
                    'data' => [
                        'user' => $register,
                        'token_type' => 'bearer',
                        'token' => $token,
                    ],
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            }

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'unauthorized',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('login_token')->plainTextToken;
            if ($user->role == 'provider') {
                $provider = Provider::where('user_id', $user->id)->first();
                $user = [
                    ...$user->toArray(),
                    'provider' => $provider,
                ];
            }
            return response()->json([
                'data' =>
                [
                    'user' => $user,
                    'tokenType' => 'bearer',
                    'token' => $token,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function provider_register(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                'email' => 'email|required|min:6',
                'password' => 'required|confirmed|min:6',
                'phone_number' => 'regex:/^\+[1-9]{1}[0-9]{3,14}$/|required',
                'first_name' => 'string|required',
                'last_name' => 'string|required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            } else {
                $register = User::create([
                    'email' => $request->email,
                    'role' => 'provider',
                    'phone_number' => $request->phone_number,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'password' => Hash::make($request->password),
                ]);

                $register->markEmailAsVerified();

                Mail::send(new RegisterProvider($register));

                $token = $register->createToken('register_token')->plainTextToken;
                return response()->json([
                    'message' => 'register successfull',
                    'data' => [
                        'user' => $register,
                        'token_type' => 'bearer',
                        'token' => $token,
                    ],
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function currentUser()
    {
        return response()->json([
            "message" => "get current user success",
            "data" => Auth::user(),
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'email' => 'email|nullable|min:6',
            'phone_number' => 'regex:/^\+[1-9]{1}[0-9]{3,14}$/|nullable',
            'address' => 'string|nullable',
            'postal_code' => 'string|nullable',
            'thumbnail' => 'nullable|file|mimes:jpeg,jpg,png',
            'date_of_birth' => 'date|nullable',
            'gender' => 'in:Male,Female|nullable',
            'occupation' => 'string|nullable',
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
                'thumbnail-' . Auth::user()->id . '.png',
            );
            $validate['thumbnail'] = getenv('APP_URL') . '/storage/images' . '/thumbnail-' . Auth::user()->id . '.png';
        }

        $user = User::where('id', Auth::user()->id)->update($validate);
        if ($user < 1) {
            return response()->json([
                "message" => "data user profile nothing has changed",
            ], 400);
        }

        $user = User::where('id', Auth::user()->id)->first();

        return response()->json([
            "message" => "success update profile",
            "data" => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->where('id', Auth::user()->id)->delete();
        return response(['message' => 'success logout'], 200);
    }

    public function getActiveJobUser(Request $request)
    {
        $rent_a_friend_order = rentAfriendOrder::where(['user_id' => Auth()->user()->id, 'status' => 'active'])->with(['provider', 'services'])->get();
        $housekeeping_order = HousekeepingOrder::where(['user_id' => Auth()->user()->id, 'status' => 'active'])->with(['provider', 'services'])->get();
        $maintenance_order = MaintenanceOrder::where(['user_id' => Auth()->user()->id, 'status' => 'active'])->with(['provider', 'services'])->get();
        $custom_order = CustomOrder::where(['user_id' => Auth()->user()->id, 'status' => 'active'])->with(['provider'])->get();

        $orders = [
            ...$rent_a_friend_order->toArray(),
            ...$housekeeping_order->toArray(),
            ...$maintenance_order->toArray(),
            ...array_map(function (array $data) {
                return [
                    ...$data,
                    'services' => [],
                ];
            }, $custom_order->toArray()),
        ];

        if (!$orders) {
            return response([
                'message' => 'history jobs is empty',
            ], 404);
        }

        return response([
            'message' => 'success get active jobs',
            'data' => $orders,
        ], 200);
    }

    public function getHistoryJobUser(Request $request)
    {
        $rent_a_friend_order = rentAfriendOrder::where(['user_id' => Auth()->user()->id, 'status' => 'done'])->with(['provider', 'services'])->get();
        $housekeeping_order = HousekeepingOrder::where(['user_id' => Auth()->user()->id, 'status' => 'done'])->with(['provider', 'services'])->get();
        $maintenance_order = MaintenanceOrder::where(['user_id' => Auth()->user()->id, 'status' => 'done'])->with(['provider', 'services'])->get();
        $custom_order = CustomOrder::where(['user_id' => Auth()->user()->id, 'status' => 'done'])->with(['provider'])->get();

        $orders = [
            ...$rent_a_friend_order->toArray(),
            ...$housekeeping_order->toArray(),
            ...$maintenance_order->toArray(),
            ...array_map(function (array $data) {
                return [
                    ...$data,
                    'services' => [],
                ];
            }, $custom_order->toArray()),
        ];

        if (!$orders) {
            return response([
                'message' => 'history jobs is empty',
            ], 404);
        }

        return response([
            'message' => 'success get history jobs',
            'data' => $orders,
        ], 200);
    }
}
