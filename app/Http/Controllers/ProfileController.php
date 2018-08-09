<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use App\Http\Resources\TweetResource;

class ProfileController extends Controller
{
    public function index()
    {
        $tweets = auth()->user()->tweets;

        return TweetResource::collection($tweets);
    }

    public function show()
    {
        $user = auth()->user();

        return new UserResource($user);
    }
}
