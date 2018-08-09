<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class FetchTweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_a_single_tweet()
    {
        $tweet = create(Tweet::class);

        $this->json('GET', $tweet->path())
            ->assertJson([ 'data' => $tweet->toArray() ])
            ->assertStatus(200);

    }
}
