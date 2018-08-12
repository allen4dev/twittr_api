<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

class TimelineController extends Controller
{
    public function index()
    {
        $tweets = auth()->user()
            ->followings()
            ->with('tweets')
            ->get()
            ->flatMap->tweets;

        return TweetResource::collection($tweets);
    }
}
