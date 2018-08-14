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
        $tweet = create(Tweet::class);
        
        $tweetReplies = create(Reply::class, [ 'tweet_id' => $tweet->id ], 2);
        $otherReply = create(Reply::class);

        $this->json('GET', $tweet->path() . '/replies')
            ->assertJson([
                'data' => $tweetReplies->toArray(),
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function guests_can_fetch_a_single_reply()
    {
        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson([
                'data' => $reply->toArray()
            ])
            ->assertStatus(200);
    }
}
