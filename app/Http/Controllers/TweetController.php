<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

use App\Tweet;

class TweetController extends Controller
{
    public function show(Tweet $tweet)
    {
        return new TweetResource($tweet);
    }

    public function store(Request $request)
    {
        $request->validate([ 'body' => 'required' ]);

        $tweet = auth()->user()->publishTweet($request);

        return new TweetResource($tweet);
    }
}
