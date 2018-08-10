<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TweetResource;

class ProfileFavoritesController extends Controller
{
    public function index()
    {
        $tweets = auth()->user()->favorites->each->favorited;

        dd($tweets->toArray());

        return TweetResource::collection($tweets);
    }
}
