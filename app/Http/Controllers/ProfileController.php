<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use App\Http\Resources\TweetCollection;

class ProfileController extends Controller
{
    public function index()
    {
        // ToDo: Try a raw sql or eager loading
        $userTweets      = auth()->user()->tweets;
        $retweetedTweets = auth()->user()->retweets->map->tweet;

        $tweets = $userTweets->merge($retweetedTweets);

        return new TweetCollection($tweets);
    }

    public function show()
    {
        $user = auth()->user();

        return new UserResource($user);
    }
}
