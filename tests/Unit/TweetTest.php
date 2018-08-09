<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tweet_knows_his_path()
    {
        $tweet = create(Tweet::class);

        $this->assertEquals("/api/tweets/{$tweet->id}", $tweet->path());
    }

    /** @test */
    public function a_tweet_has_many_replies()
    {
        $tweet = create(Tweet::class);        

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $tweet->replies
        );
    }
}
