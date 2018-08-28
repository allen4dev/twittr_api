<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_is_notified_after_other_user_follows_him()
    {
        $this->signin();
        
        $followedUser = create(User::class);
        
        $this->followUser($followedUser);

        $this->assertCount(1, $followedUser->unreadNotifications);
    }

    public function followUser($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }
}
