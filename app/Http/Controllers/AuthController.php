<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Responses;
use App\Http\Requests\RegisterRequest;

use JWTAuth;

use App\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->registerUser();

        $token = JWTAuth::fromUser($user);

        $statusCode = 201;

        return Responses::format(
            [ "token" => $token ],
            $statusCode
        );
    }

    public function login()
    {
        request()->validate([
            "email"    => "required|email",
            "password" => "required",
        ]);

        $credentials = request()->only([ 'email', 'password' ]);

        if (! $token = auth()->attempt($credentials)) {
            return Responses::format(null, 400, [ "message" => "Invalid credentials" ]);
        }

        return Responses::format(compact('token'), 200);
    }
}
