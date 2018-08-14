<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\ReplyCollection;

use App\Tweet;
use App\Reply;

class ReplyController extends Controller
{
    public function index(Tweet $tweet)
    {
        return new ReplyCollection($tweet->replies);
    }

    public function show(Reply $reply)
    {
        return new ReplyResource($reply);
    }

    public function store(Tweet $tweet)
    {
        request()->validate([ 'body' => 'required' ]);

        $reply = $tweet->addReply(request('body'));

        return new ReplyResource($reply);
    }
}
