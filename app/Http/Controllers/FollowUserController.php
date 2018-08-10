<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\UserResource;

use App\User;

class FollowUserController extends Controller
{
    public function store(User $user)
    {
        DB::table('followings')
            ->insert([
                'user_id'      => auth()->id(),
                'following_id' => $user->id,
            ]);
    
        return new UserResource($user);
    }
}
