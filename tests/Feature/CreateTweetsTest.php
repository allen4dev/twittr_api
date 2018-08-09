<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class CreateTweetsTest extends TestCase
{
    use RefreshDatabase;

    // ToDo: handle the failure token in the render
    /** @test */
    public function a_registered_user_can_create_tweets_if_a_valid_token_is_supplied()
    {
        $token = $this->signin();

        $tweet = raw(Tweet::class);
        
        $this->json('POST', '/api/tweets', $tweet, ['Authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => [ 'body' => $tweet['body'] ]
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('tweets', [
            'user_id' => auth()->id(),
            'body'    => $tweet['body'],
        ]);
    }
}
