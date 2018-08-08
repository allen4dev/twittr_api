<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;


use App\Http\Responses;
use App\Http\Requests\RegisterRequest;

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
}
