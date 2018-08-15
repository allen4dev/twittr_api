<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\ReplyCollection;

use App\Reply;

class ReplyFavoritesController extends Controller
{
    public function index()
    {
        $replies = auth()->user()->favorites()->whereType('reply')->get()->map->favorited;

        return new ReplyCollection($replies->paginate(), $replies->first()->tweet);
    }

    public function store(Reply $reply)
    {
        $reply->favorite();

        return new ReplyResource($reply);
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        return new ReplyResource($reply);
    }
}
