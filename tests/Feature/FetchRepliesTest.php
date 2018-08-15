<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
use App\Reply;

class FetchRepliesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function guests_can_fetch_replies_for_a_tweet()
    {
        $this->withoutExceptionHandling();
        $tweet = create(Tweet::class);
        
        create(Reply::class, [ 'tweet_id' => $tweet->id ], 2);
        create(Reply::class);

        $this->json('GET', $tweet->path() . '/replies')
            ->assertJson([
                'data' => [
                    [ 'type' => 'replies', 'id' => '1' ],
                    [ 'type' => 'replies', 'id' => '2' ],
                ],
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function guests_can_fetch_a_single_reply()
    {
        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson([
                'data' => [ 'type' => 'replies', 'id' => $reply->id ]
            ])
            ->assertStatus(200);
    }
}
