<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ActivityResource;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = auth()->user()->activities;

        return ActivityResource::collection($activities);
    }
}
