<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use App\Photo;
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

        $this->json('GET', '/api/me', [], [ 'Authorization' => 'Bearer ' . $token ])
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                    ]
                ],
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_tweets()
    {
        $this->withoutExceptionHandling();
        $token = $this->signin();

        create(Tweet::class, [ 'user_id' => auth()->id() ], 2);
        create(Tweet::class);

        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $this->json('GET', '/api/me/tweets', [], $headers)
            ->assertJson([
                'data' => [
                    [ 'type' => 'tweets', 'id' => '1' ],
                    [ 'type' => 'tweets', 'id' => '2' ],
                ]
            ])
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
                [ 'type' => 'tweets', 'id' => (string) $userTweet->id  ],
                [ 'type' => 'tweets', 'id' => (string) $retweetedTweet->id  ],
            ] ]);
    }

    /** @test */
    public function a_user_can_fetch_his_favorited_tweets()
    {
        $token = $this->signin();
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $tweetsFavoritedByTheUser = create(Tweet::class, [], 2);
        create(Tweet::class);

        $tweetsFavoritedByTheUser->each(function ($tweet) use ($headers) {
            $this->favoriteResource($tweet, $headers);
        });
        
        $this->json('GET', '/api/me/favorites/tweets', [], $headers)
            
            ->assertJson([
                'data' => [
                    [ 'type' => 'tweets', 'id' => '1' ],
                    [ 'type' => 'tweets', 'id' => '2' ],
                ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_favorited_replies()
    {
        $this->withoutExceptionHandling();
        $token = $this->signin();
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        $repliesFavoritedByTheUser = create(Reply::class, [], 2);
        create(Reply::class);

        $repliesFavoritedByTheUser->each(function ($reply) use ($headers) {
            $this->favoriteResource($reply, $headers);
        });
        
        $this->json('GET', '/api/me/favorites/replies', [], $headers)
            ->assertJson([
                'data' => [
                    [ 'type' => 'replies', 'id' => '1' ],
                    [ 'type' => 'replies', 'id' => '2' ],
                ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_followers()
    {
        $this->signin();

        $follower = create(User::class);

        DB::table('followers')
            ->insert([
                'follower_id'  => $follower->id,
                'following_id' => auth()->id(),
            ]);
        
        $this->json('GET', '/api/me/followers')
            ->assertJson([
                'data' => [[
                    'type' => 'users',
                    'id'   => (string) $follower->id,
                ]]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_the_users_who_he_is_following()
    {
        $token = $this->signin();

        $followedUser = create(User::class);

        $this->followUser($followedUser, $token);

        $this->json('GET', '/api/me/followings')
            ->assertJson([ 'data' => [
                [ 'type' => 'users', 'id' => (string) $followedUser->id ]
            ]])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_list_of_activities()
    {
        $this->signin();

        $tweet = create(Tweet::class, [ 'user_id' => auth()->id() ]);
        create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->json('GET', '/api/me/activities')
            ->assertJson([
                'data' => [
                    [ 'type' => 'activities', 'id' => '1' ],
                    [ 'type' => 'activities', 'id' => '2' ],
                ]
            ])->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_his_photos()
    {
        $this->signin();

        create(Photo::class, [ 'user_id' => auth()->id() ], 2);

        $this->json('GET', '/api/me/photos')
            ->assertJson([
                'data' => [
                    [ 'type' => 'photos', 'id' => '1' ],
                    [ 'type' => 'photos', 'id' => '2' ],
                ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_fetch_a_single_notification()
    {
        $token = $this->signin();

        $user2 = create(User::class);
        $this->followUser($user2, $token);

        auth()->logout();

        $this->signin($user2);

        $notificationId = $user2->notifications()->first()->id;

        $this->json('GET', "/api/me/notifications/{$notificationId}")
            ->assertJson([
                'data' => [
                    'type' => 'notifications',
                    'id'   => $notificationId,
                ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    // public function a_user_can_fetch_all_of_his_notifications()
    // {
    //     $this->withoutExceptionHandling();
    //     // Given we have an authenticated user1
    //     $token = $this->signin();
    //     // and a user2 who is followed by the user1
    //     $user2 = create(User::class);

    //     $this->followUser($user2, $token);

    //     auth()->logout();

    //     $this->signin($user2);

    //     // When the user2 makes a GET request to /me/notifications
    //     $this->json('GET', '/api/me/notifications')
    //     // Then he should receive a notification resouce
    //     // with the type notifications
    //     // and the id of 1 under a data object
    //         ->assertJson([[
    //             'type' => 'notifications',
    //             'id'   => '1',
    //         ]]);
    // }

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
