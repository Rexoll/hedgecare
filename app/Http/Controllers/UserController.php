<?php

namespace App\Http\Controllers;

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
            "phone_number" => "required|unique:App\Models\User,phone_number",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 400);
        }
        $user = User::create(
            [
                ...$validator->validate(),
                "role" => "user",
            ]
        );
        return response()->json([
            "data" => $user,
        ], 201);
    }
}