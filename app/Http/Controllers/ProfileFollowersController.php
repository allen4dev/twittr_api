<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserCollection;

class ProfileFollowersController extends Controller
{
    public function index()
    {
        $followers = auth()->user()->followers()->paginate();

        return new UserCollection($followers);
    }
}
