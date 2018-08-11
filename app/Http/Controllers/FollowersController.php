<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

class FollowersController extends Controller
{
    public function index()
    {
        $followers = auth()->user()->followers;

        return UserResource::collection($followers);
    }
}
