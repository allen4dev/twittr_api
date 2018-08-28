<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserCollection;

use App\User;

class UserFollowersController extends Controller
{
    public function index(User $user)
    {
        return new UserCollection($user->followers->paginate());
    }
}
