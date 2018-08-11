<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
use App\Reply;

class DeleteTweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_tweets()
    {
        $this->json('DELETE', '/api/tweets/1')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_delete_his_tweets()
    {
        $token = $this->signin();

        $tweet = create(Tweet::class, [ 'user_id' => auth()->id() ]);

        $this->assertDatabaseHas('tweets', [ 'user_id'  => auth()->id() ]);

        $headers = [ 'Authorization' => 'Bearer ' .$token ];

        $this->json('DELETE', $tweet->path(), [], $headers)
            ->assertStatus(204);

        $this->assertDatabaseMissing('tweets', [
            'user_id'  => auth()->id(),
            'body'     => $tweet->body,
        ]);
    }

    /** @test */
    public function after_delete_a_thread_all_of_his_replies_should_also_be_deleted()
    {
        $this->signin();
        
        $tweet = create(Tweet::class, [ 'user_id'  => auth()->id() ]);
        $reply = create(Reply::class, [ 'user_id' => 999, 'tweet_id' => $tweet->id ]);

        $this->json('DELETE', $tweet->path());

        $this->assertDatabaseMissing('replies', [
            'user_id'  => 999,
            'tweet_id' => $tweet->id,
        ]);
    }
}
