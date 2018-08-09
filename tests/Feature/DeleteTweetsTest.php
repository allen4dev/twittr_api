<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

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
}
