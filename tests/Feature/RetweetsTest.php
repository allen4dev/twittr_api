<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class RetweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_retweet_a_tweet()
    {
        $this->signin();

        $tweetToRetweet = create(Tweet::class);

        $this->json('POST', $tweetToRetweet->path() . '/retweet')
            ->assertJson([ 'data' => $tweetToRetweet->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('retweets', [
            'user_id'  => auth()->id(),
            'tweet_id' => $tweetToRetweet->id,
        ]);
    }
}
