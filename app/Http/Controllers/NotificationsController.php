<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Transformers\IncludeTransformer;

use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;

        return new NotificationCollection($notifications->paginate());
    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        return new NotificationResource($notification);
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json()->setStatusCode(204);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications()->update([ 'read_at' => now() ]);

        return response()->json()->setStatusCode(204);
    }
}
