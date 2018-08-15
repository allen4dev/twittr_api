<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

class FollowingsController extends Controller
{
    public function index()
    {
        $followings = auth()->user()->followings()->paginate();

        return UserResource::collection($followings);
    }
}
