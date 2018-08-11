<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;

use App\Reply;

class ReplyFavoritesController extends Controller
{
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
