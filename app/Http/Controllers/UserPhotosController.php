<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserPhotosController extends Controller
{
    public function store(User $user)
    {
        request()->validate([ 'photo' => 'required|image' ]);

        $path = request()->file('photo')->store('photos/' . $user->id, 'public');

        $user->photos()->create(compact('path'));
    }
}
