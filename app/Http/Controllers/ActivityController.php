<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ActivityCollection;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = auth()->user()->activities()->paginate();

        return new ActivityCollection($activities);
    }
}
