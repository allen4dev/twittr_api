<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Responses;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        // ! Refactor to API resources
        return Responses::format(compact('user'));
    }
}
