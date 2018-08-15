<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PhotoResource;
use App\Http\Resources\PhotoCollection;

use App\User;

class UserPhotosController extends Controller
{
    public function index()
    {
        $photos = auth()->user()->photos()->paginate();

        return new PhotoCollection($photos, auth()->user());
    }

    public function show(User $user)
    {
        return new PhotoCollection($user->photos()->paginate(), $user);
    }

    public function store(User $user)
    {
        request()->validate([
            'photos.*' => 'required|image',
        ]);

        array_map(function ($photo) use ( $user ) {
            $path = $photo->store('photos/' . $user->id, 'public');
            
            $user->photos()->create(compact('path'));
        }, request()->file('photos'));
    }
}
