<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
use App\User;

class FetchTweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_a_single_tweet()
    {
        $tweet = create(Tweet::class);

        $this->json('GET', $tweet->path())
            ->assertJson([ 'data' => $tweet->toArray() ])
            ->assertStatus(200);

    }

    /** @test */
    public function guests_can_fetch_tweets_from_a_user()
    {
        $user   = create(User::class);
        $tweets = create(Tweet::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/tweets')
            ->assertJson([ 'data' => $tweets->toArray() ])
            ->assertStatus(200);
        
    }
}
