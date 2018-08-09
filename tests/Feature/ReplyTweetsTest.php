<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class ReplyTweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_reply_a_tweet()
    {
        $this->json('POST', '/api/tweets/1/replies', [])
            ->assertStatus(401);
    }
    
    /** @test */
    public function a_user_can_reply_a_tweet_if_a_valid_token_is_supplied()
    {
        $token = $this->signin();

        $tweet = create(Tweet::class);

        $data = [ 'body' => 'A Reply for a Tweet' ];
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->json('POST', $tweet->path() . '/replies', $data, $headers)
            ->assertJson(['data' => [
                'body'    => $data['body'],
                'user_id' => auth()->id(),
            ]])
            ->assertStatus(201);

        $this->assertDatabaseHas('replies', [
            'user_id'  => auth()->id(),
            'tweet_id' => $tweet->id,
            'body'     => $data['body'],
        ]);
    }
}
