<?php

namespace App\Http\Controllers;

use App\Mail\RegisterProvider;
use App\Models\User;
use App\Notifications\UserVerifyNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            } else {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $email = User::where('email', $request->email)->first();
                    $token = $email->createToken('login_token')->plainTextToken;
                    return response()->json([
                        'data' =>
                        [
                            'user' => $email,
                            'tokenType' => 'bearer',
                            'token' => $token,
                        ],
                    ], 200);
                }
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

    public function provider_login(Request $request)
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
            } else {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'provider'])) {
                    $email = User::where('email', $request->email)->first();
                    $token = $email->createToken('login_token')->plainTextToken;
                    return response()->json([
                        'data' =>
                        [
                            'user' => $email,
                            'tokenType' => 'bearer',
                            'token' => $token,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'unauthorized',
                    ], 401);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}