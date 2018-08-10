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
    public function guests_cannot_follow_users()
    {
        $this->json('POST', '/api/users/1/follow')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_follow_other_users()
    {
        $token = $this->signin();

        $userToFollow = create(User::class);
        
        $this->followUser($userToFollow, $token)
            ->assertJson([ 'data' => $userToFollow->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('followers', [
            'follower_id' => auth()->id(),
            'following_id'   => $userToFollow->id,
        ]);
    }

    /** @test */
    public function a_user_cannot_follow_the_same_user_more_than_once()
    {
        $this->withoutExceptionHandling();
        
        $token = $this->signin();

        $userToFollow = create(User::class);

        try {
            $this->followUser($userToFollow, $token);
            $this->followUser($userToFollow, $token);
        } catch (Exception $e) {
            $this->fail('Did not expect to follow the same user more than once.');
        }

        $this->assertCount(1, $userToFollow->followers);
        $this->assertCount(1, auth()->user()->followings);
    }

    /** @test */
    public function a_user_can_unfollow_other_users()
    {
        $token = $this->signin();

        $user = create(User::class);

        $this->followUser($user, $token);

        $this->json('DELETE', $user->path() . '/unfollow')
            ->assertJson([ 'data' => $user->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('followers', [
            'follower_id' => auth()->id(),
            'following_id'   => $user->id,
        ]);
    }

    public function followUser($user, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $user->path() . '/follow', [] , $headers);
    }
}
