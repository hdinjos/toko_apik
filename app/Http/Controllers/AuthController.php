<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validate())) {
            return response()->json(
                [
                    'error' => true,
                    "message" => "invalid credential"
                ],
                400
            );
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            "email" => "required|email|string|email|max:50|unique:users",
            'password' => 'required|confirmed|string|min:6',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create(array_merge($validator->validate(), ["password" => Hash::make($request->get("password"))]));
        return response()->json(
            [
                "success" => true,
                "message" => "user register succesfull",
                "user" => $user
            ]
        );
    }

    public function userProfile(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(["message" => "Logout success"]);
    }

    public function refreshToken(Request $request)
    {
        return $this->createNewToken(auth()->refresh());
    }
    protected function createNewToken($token)
    {
        return response()->json([
            "access_token" => $token,
            "token_type" => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                "email" => auth()->user()->email,
                "role_id" => auth()->user()->role_id
            ]
        ]);
    }
}
