<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Notifications\UserVerifyNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:App\Models\User,email",
            "phone_number" => "required|unique:App\Models\User,phone_number|regex:/^\+[1-9]{1}[0-9]{3,14}$/",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "please fill form completely",
                "errors" => $validator->errors()
            ], 400);
        }
        $user = User::create(
            [
                ...$validator->validate(),
                "role" => "user",
            ]
        );

        $user->notify(new UserVerifyNotification());

        return response()->json([
            "message" => "register for email " . $user->email . " successfully",
            "data" => $user,
        ], 201);
    }
}