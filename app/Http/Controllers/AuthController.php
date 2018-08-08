<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;

use App\User;

class AuthController extends Controller
{
    public function store()
    {
        request()->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'username' => request('username'),
            'email'    => request('email'),
            'password' => bcrypt(request('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            "data" => [ "token" => $token ],
            "meta" => [
                "status" => [ "code" => 201 ],
                "error"  => null
            ],
        ], 201);
    }
}
