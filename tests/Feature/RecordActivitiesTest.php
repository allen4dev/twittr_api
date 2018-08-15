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
        $this->withoutExceptionHandling();
        $this->signin();

        $tweet = raw(Tweet::class, [ 'user_id' => auth()->id() ]);

        $this->json('POST', '/api/tweets', $tweet);

        $this->assertDatabaseHas('activities', [
            'user_id'      => auth()->id(),
            'action'       => 'created_tweet',
            'subject_id'   => 1,
            'subject_type' => 'App\Tweet',
        ]);
    }

    /** @test */
    public function an_activity_is_recorder_after_the_user_replies_a_tweet()
    {
        $this->signin();

        $tweet = create(Tweet::class);

        $data = [ 'body' => 'A tweet reply' ];

        $this->json('POST', $tweet->path() . '/replies', $data);

        $this->assertDatabaseHas('activities', [
            'user_id'      => auth()->id(),
            'action'       => 'created_reply',
            'subject_id'   => 1,
            'subject_type' => 'App\Reply',
        ]);
    }
}
