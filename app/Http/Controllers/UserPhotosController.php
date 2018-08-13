<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PhotoResource;

use App\User;

class UserPhotosController extends Controller
{
    public function index()
    {
        $photos = auth()->user()->photos;

        return PhotoResource::collection($photos);
    }

    public function store(User $user)
    {
        request()->validate([ 'photo' => 'required|image' ]);

        $path = request()->file('photo')->store('photos/' . $user->id, 'public');

        $user->photos()->create(compact('path'));
    }
}
