<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_his_information_if_sends_a_valid_token()
    {
        $token = $this->register();

        $this->json('GET', '/api/me', [], ['Authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => auth()->user()->toArray(),
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_tweets_if_sends_a_valid_token()
    {
        $token = $this->signin();

        $userTweets = create(Tweet::class, [ 'user_id' => auth()->id() ], 2);
        $otherTweet = create(Tweet::class);

        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->json('GET', '/api/me/tweets', [], $headers)
            ->assertJson([ 'data' => $userTweets->toArray()])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_favorites_if_sends_a_valid_token()
    {
        $token = $this->signin();
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $tweetsFavoritedByTheUser = create(Tweet::class, [], 2);
        $notFavoritedTweet = create(Tweet::class);

        $tweetsFavoritedByTheUser->each(function ($tweet) use ($headers) {
            $this->favoriteTweet($tweet, $headers);
        });
        
        $this->json('GET', '/api/me/favorites', [], $headers)
            ->assertJson([ 'data' => $tweetsFavoritedByTheUser->toArray() ])
            ->assertStatus(200);
    }

    public function register()
    {
        $response = $this->post('/api/auth/register', [
            'username' => 'Allen',
            'email'    => 'allen@example.test',
            'password' => 'secret',
        ]);

        return $response->original['data']['token'];
    }

    public function favoriteTweet($tweet, $headers)
    {
        return $this->json('POST', $tweet->path() . '/favorite', [], $headers);
    }
}
