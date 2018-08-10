<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Tweet;

class FetchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_fetch_the_information_of_a_user()
    {
        $user = create(User::class);

        $this->json('GET', $user->path())
            ->assertJson([ 'data' => $user->toArray() ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_guest_can_fetch_all_users_who_favorited_a_tweet()
    {
        $token = $this->signin();
        
        $tweet = create(Tweet::class);
        
        $this->favoriteTweet($tweet, $token);
        
        auth()->logout();

        $this->json('GET', $tweet->path() . '/favorited')
            ->assertJson([ 'data' => [ User::first()->toArray() ]])
            ->assertStatus(200);
    }

    public function favoriteTweet($tweet, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $tweet->path() . '/favorite', [], $headers);
    }
}
