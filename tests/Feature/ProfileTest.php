<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use App\Reply;
use App\Tweet;
use App\User;


class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_his_information()
    {
        $token = $this->register();

        $this->json('GET', '/api/me', [], ['Authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => auth()->user()->toArray(),
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_tweets()
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
    public function a_user_also_receives_retweeted_tweets_when_fetch_his_tweets()
    {
        $this->signin();
        
        $userTweet = create(Tweet::class, [ 'user_id' => auth()->id() ]);

        $retweetedTweet = create(Tweet::class);

        DB::table('retweets')->insert([
            'user_id'  => auth()->id(),
            'tweet_id' => $retweetedTweet->id,
        ]);

        $this->json('GET', 'api/me/tweets')
            ->assertJson([ 'data' => [
                $userTweet->toArray(),
                $retweetedTweet->toArray(),
            ] ]);
    }

    /** @test */
    public function a_user_can_fetch_his_favorited_tweets()
    {
        $token = $this->signin();
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $tweetsFavoritedByTheUser = create(Tweet::class, [], 2);
        $notFavoritedTweet = create(Tweet::class);

        $tweetsFavoritedByTheUser->each(function ($tweet) use ($headers) {
            $this->favoriteResource($tweet, $headers);
        });
        
        $this->json('GET', '/api/me/favorites/tweets', [], $headers)
            ->assertJson([ 'data' => $tweetsFavoritedByTheUser->toArray() ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_favorited_replies()
    {
        $token = $this->signin();
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $repliesFavoritedByTheUser = create(Reply::class, [], 2);
        $notFavoritedReply = create(Reply::class);

        $repliesFavoritedByTheUser->each(function ($reply) use ($headers) {
            $this->favoriteResource($reply, $headers);
        });
        
        $this->json('GET', '/api/me/favorites/replies', [], $headers)
            ->assertJson([ 'data' => $repliesFavoritedByTheUser->toArray() ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_followers()
    {
        $userOne = create(User::class);

        $token = $this->signin($userOne);
        
        $userTwo = create(User::class);

        $this->followUser($userTwo, $token);
        
        auth()->logout();

        $this->signin($userTwo);
        
        $this->json('GET', '/api/me/followers')
            ->assertJson([ 'data' => [ $userOne->toArray() ] ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_the_users_who_he_is_following()
    {
        $token = $this->signin();

        $followedUser = create(User::class);

        $this->followUser($followedUser, $token);

        $this->json('GET', '/api/me/followings')
            ->assertJson([ 'data' => [ $followedUser->toArray() ] ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_list_of_activities()
    {
        $this->signin();

        $tweet = create(Tweet::class, [ 'user_id' => auth()->id() ]);
        $reply = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->json('GET', '/api/me/activities')
            ->assertJson([
                'data' => [
                    [
                        'user_id' => auth()->id(),
                        'subject_id' => $tweet->id,
                        'type' => 'created_tweet'
                    ],
                    [
                        'user_id' => auth()->id(),
                        'subject_id' => $reply->id,
                        'type' => 'created_reply'
                    ],
                ]
            ])->assertStatus(200);
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

    public function favoriteResource($resource, $headers)
    {
        return $this->json('POST', $resource->path() . '/favorite', [], $headers);
    }

    public function followUser($user, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $user->path() . '/follow', [] , $headers);
    }
}
