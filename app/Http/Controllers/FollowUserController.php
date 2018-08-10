<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\UserFollowed;

use App\Http\Resources\UserResource;

use App\User;

class FollowUserController extends Controller
{
    public function store(User $user)
    {
        event(new UserFollowed( auth()->user(), $user ));
    
        return new UserResource($user);
    }
}
