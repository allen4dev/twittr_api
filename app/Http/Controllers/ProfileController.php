<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;

class ProfileController extends Controller
{
    public function show()
    {
        // $user = auth()->user();
        $token = request()->header('Authorization');

        // dd($token);
        dd(JWTAuth::toUser($token));

        return response()->json([
            'data' => [$user]
        ]);
    }
}
