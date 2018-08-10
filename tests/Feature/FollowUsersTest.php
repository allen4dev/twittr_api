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
        
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->json('POST', $userToFollow->path() . '/follow', [] , $headers)
            ->assertJson([ 'data' => $userToFollow->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('followings', [
            'user_id'      => auth()->id(),
            'following_id' => $userToFollow->id,
        ]);
    }
}
