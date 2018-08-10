<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

use App\Tweet;

class TweetFavoritesController extends Controller
{
    public function show(Tweet $tweet)
    {
        $users = $tweet->favorites->map->user;

        return UserResource::collection($users);
    }
}
