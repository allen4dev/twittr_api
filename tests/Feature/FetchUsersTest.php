<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Tweet;

class FetchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_fetch_the_information_of_a_user()
    {
        $user = create(User::class);

        $this->fetchUser($user)
            ->assertJson([
                'data' => [ 'type' => 'users', 'id' => (string) $user->id ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_guest_can_fetch_all_users_who_favorited_a_tweet()
    {
        $token = $this->signin();
        
        $tweet = create(Tweet::class);
        
        $this->favoriteTweet($tweet, $token);
        
        auth()->logout();

        $this->json('GET', $tweet->path() . '/favorited')
            ->assertJson([
                'data' => [ [ 'type' => 'users', 'id' => '1' ] ]
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function a_guest_can_fetch_all_followers_from_a_user()
    {
        $followedUser = create(User::class);

        $user1 = create(User::class);
        $user2 = create(User::class);

        $values = [
            [ 'follower_id' => $user1->id, 'following_id' => $followedUser->id ],
            [ 'follower_id' => $user2->id, 'following_id' => $followedUser->id ],
        ];

        DB::table('followers')->insert($values);

        $this->json('GET', $followedUser->path() . '/followers')
            ->assertJson([
                'data' => [
                    [ 'type' => 'users', 'id' => (string) $user1->id ],
                    [ 'type' => 'users', 'id' => (string) $user2->id ],
                ],
            ])
            ->assertStatus(200);
    }

    public function fetchUser($user)
    {
        return $this->json('GET', $user->path());
    }

    public function favoriteTweet($tweet, $token)
    {
        $headers = [ 'Authorization' => 'Bearer ' . $token ];

        return $this->json('POST', $tweet->path() . '/favorite', [], $headers);
    }
}
