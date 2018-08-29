<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
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

    /** @test */
    public function a_user_is_notified_after_other_user_retweet_his_tweet()
    {
        $this->signin();

        $user = create(User::class);
        $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->json('POST', $tweet->path() . '/retweet');

        $this->assertCount(1, $user->unreadNotifications); 
    }

    public function followUser($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }
}
