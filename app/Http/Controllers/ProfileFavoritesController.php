<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

class ProfileFavoritesController extends Controller
{
    public function index()
    {
        $tweets = auth()->user()->favorites->map->favorited;

        return TweetResource::collection($tweets);
    }
}
