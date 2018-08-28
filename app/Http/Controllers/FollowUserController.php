<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\UserFollowed;

use App\Http\Resources\UserResource;

use App\User;

class FollowUserController extends Controller
{
    public function store(User $user)
    {
        $user->follow();
        
        $user->notify(new UserFollowed);
    
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->unfollow();
        
        return new UserResource($user);
    }
}
