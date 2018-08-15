<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetCollection;

use App\User;

class UserTweetsController extends Controller
{
    public function index(User $user)
    {
        $tweets = $user->tweets()->paginate();

        return new TweetCollection($tweets);
    }
}
