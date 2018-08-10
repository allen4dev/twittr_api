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

        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->json('POST', $tweet->path() . '/favorite', $headers)
            ->assertJson([ 'data' => $tweet->toArray() ])
            ->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id'  => auth()->id(),
            'favorited_id' => $tweet->id,
        ]);
    }
}
