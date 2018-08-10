<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class FollowUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_follow_other_users()
    {
        $token = $this->signin();

        $userToFollow = create(User::class);
        
        $this->followUser($userToFollow, $token)
            ->assertJson([ 'data' => $userToFollow->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('followings', [
            'user_id'      => auth()->id(),
            'following_id' => $userToFollow->id,
        ]);
    }

    /** @test */
    public function after_follow_a_user_a_record_also_should_be_added_in_the_followers_table()
    {
        $token = $this->signin();

        $userToFollow = create(User::class);

        $this->followUser($userToFollow, $token);

        $this->assertDatabaseHas('followers', [
            'user_id'     => $userToFollow->id,
            'follower_id' => auth()->id(),
        ]);
    }

    public function followUser($user, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $user->path() . '/follow', [] , $headers);
    }
}
