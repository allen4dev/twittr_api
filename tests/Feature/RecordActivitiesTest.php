<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class RecordActivitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_activity_is_recorder_after_create_a_tweet()
    {
        $this->signin();

        $tweet = raw(Tweet::class, [ 'user_id' => auth()->id() ]);

        $this->json('POST', '/api/tweets', $tweet);

        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'tweet_id' => 1,
        ]);
    }
}
