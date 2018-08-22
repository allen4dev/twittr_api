<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Response;
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

        return Response::format(
            [
                "id" => $user->id,
                "token" => $token
            ],
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
            return Response::format(null, 400, [ "message" => "Invalid credentials" ]);
        }
        
        return Response::format([
            'id' => auth()->id(),
            'token' => $token
        ], 200);
    }
}
