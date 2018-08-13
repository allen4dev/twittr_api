<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

class AvatarController extends Controller
{
    public function store()
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);

        $path = request()->file('avatar')->store('avatars', 'public');

        auth()->user()->update([ 'avatar_url' => $path ]);

        return new UserResource(auth()->user());
    }
}
