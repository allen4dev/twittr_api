<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Resources\TweetResource;

use App\Http\Responses;

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

    public function update(Tweet $tweet)
    {
        $this->authorize('update', $tweet);

        $tweet->update([ 'body' => request('body') ]);

        return new TweetResource($tweet);
    }

    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);

        $tweet->delete();

        return response()->json()->setStatusCode(204);
    }
}
