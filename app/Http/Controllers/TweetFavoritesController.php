<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;
use App\Http\Resources\UserResource;

use App\Tweet;

class TweetFavoritesController extends Controller
{
    // ! Code duplication if still adding new resources to favorite
    public function index()
    {
        $tweets = auth()->user()->favorites()->whereType('tweet')->get()->map->favorited;

        return TweetResource::collection($tweets);
    }

    public function show(Tweet $tweet)
    {
        $users = $tweet->favorites->map->user;

        return UserResource::collection($users);
    }

    public function store(Tweet $tweet)
    {
        $tweet->favorite();

        return new TweetResource($tweet);
    }

    public function destroy(Tweet $tweet)
    {
        $tweet->unfavorite();

        return new TweetResource($tweet);
    }
}
