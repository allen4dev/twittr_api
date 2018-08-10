<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

use App\User;

class UserTweetsController extends Controller
{
    public function index(User $user)
    {
        $tweets = $user->tweets;

        return TweetResource::collection($tweets);
    }
}
