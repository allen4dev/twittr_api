<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class TweetResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_key()
    {
        $tweet = create(Tweet::class);

        $this->fetchTweet($tweet)
            ->assertJson([
                'data' => [
                    'type' => 'tweets',
                    'id'   => (string) $tweet->id,
                    'attributes' => [
                        'body' => $tweet->body,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object(Type $var = null)
    {
        $tweet = create(Tweet::class);

        $this->fetchTweet($tweet)
            ->assertJson([
                'data' => [
                    'links' => [
                        'self' => route('tweets.show', [ 'id' => $tweet->id ])
                    ]
                ]
            ]);
    }

    public function fetchTweet($tweet)
    {
        return $this->json('GET', $tweet->path());
    }
}
