<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterFollower
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserFollowed  $event
     * @return void
     */
    public function handle(UserFollowed $event)
    {
        $event->followed->followers()->create([
            'follower_id' => $event->following->id,
        ]);
    }
}
