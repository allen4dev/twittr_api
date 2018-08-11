<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
use App\Reply;

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

        $this->favoriteResource($tweet, $token)
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
            $this->favoriteResource($tweet, $token);
            $this->favoriteResource($tweet, $token);
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

        $this->favoriteResource($tweet, $token);

        $this->json('DELETE', $tweet->path() . '/unfavorite', [], $headers)
            ->assertJson([ 'data' => $tweet->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id'  => auth()->id(),
            'favorited_id' => $tweet->id,
        ]);
    }

    /** @test */
    public function a_user_can_favorite_a_reply()
    {
        $token = $this->signin();

        $reply = create(Reply::class);

        $this->favoriteResource($reply, $token)
            ->assertJson([ 'data' => $reply->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id'        => auth()->id(),
            'favorited_id'   => $reply->id,
            'favorited_type' => Reply::class
        ]);
    }

    public function favoriteResource($resource, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $resource->path() . '/favorite', [], $headers);
    }

    /** @test */
    public function a_user_can_unfavorite_a_reply()
    {
        $token = $this->signin();

        $reply = create(Reply::class);

        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->favoriteResource($reply, $token);

        $this->json('DELETE', $reply->path() . '/unfavorite', [], $headers)
            ->assertJson([ 'data' => $reply->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id'  => auth()->id(),
            'favorited_id' => $reply->id,
            'favorited_type' => Reply::class,
        ]);
    }
}
