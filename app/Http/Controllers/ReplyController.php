<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;

use App\Tweet;
use App\Reply;

class ReplyController extends Controller
{
    public function index(Tweet $tweet)
    {
        // ! Use a ReplyCollection to customize the data
        return ReplyResource::collection($tweet->replies);
    }

    public function store(Tweet $tweet)
    {
        request()->validate([ 'body' => 'required' ]);

        $reply = $tweet->addReply(request('body'));

        return new ReplyResource($reply);
    }
}
