<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

use App\Tweet;

class RetweetController extends Controller
{
    public function store(Tweet $tweet)
    {
        auth()->user()->retweets()->create([ 'tweet_id' => $tweet->id ]);

        return new TweetResource($tweet);
    }
}
