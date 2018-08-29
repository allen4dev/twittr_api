<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\FollowedUser;

use App\Http\Resources\UserResource;

use App\User;

class FollowUserController extends Controller
{
    public function store(User $user)
    {
        $user->follow();
        
        $user->notify(new FollowedUser);
    
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->unfollow();
        
        return new UserResource($user);
    }
}
