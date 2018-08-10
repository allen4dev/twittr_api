<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_favorite_a_tweet()
    {
        $this->json('POST', '/api/tweets/1/favorite')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_favorite_a_tweet()
    {
        $token = $this->signin();

        $tweet = create(Tweet::class);

        $this->favoriteTweet($tweet, $token)
            ->assertJson([ 'data' => $tweet->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id'  => auth()->id(),
            'favorited_id' => $tweet->id,
        ]);
    }

    /** @test */
    public function a_user_cannot_favorite_the_same_tweet_more_than_once()
    {
        $token = $this->signin();

        $tweet = create(Tweet::class);

        try {
            $this->favoriteTweet($tweet, $token);
            $this->favoriteTweet($tweet, $token);
        } catch (Exception $e) {
            $this->fail('You cannot favorite a tweet more than once.');
        }

        $this->assertCount(1, $tweet->favorites);
    }

    /** @test */
    public function a_user_can_unfavorite_a_tweet()
    {
        $token = $this->signin();

        $tweet = create(Tweet::class);

        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->favoriteTweet($tweet, $token);

        $this->json('DELETE', $tweet->path() . '/unfavorite', [], $headers)
            ->assertJson([ 'data' => $tweet->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id'  => auth()->id(),
            'favorited_id' => $tweet->id,
        ]);
    }

    public function favoriteTweet($tweet, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $tweet->path() . '/favorite', [], $headers);
    }
}
