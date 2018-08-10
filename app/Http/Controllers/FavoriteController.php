<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

use App\Tweet;

class FavoriteController extends Controller
{
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
