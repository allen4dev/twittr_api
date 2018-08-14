<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            ->assertStatus(200);
    }

    /** @test */
    public function a_guest_can_fetch_all_users_who_favorited_a_tweet()
    {
        $this->withoutExceptionHandling();
        $token = $this->signin();
        
        $tweet = create(Tweet::class);
        
        $this->favoriteTweet($tweet, $token);
        
        auth()->logout();

        $this->json('GET', $tweet->path() . '/favorited')
            ->assertJson([ 'data' => [ User::first()->toArray() ]])
            ->assertStatus(200);
    }

    /** @test */
    public function a_single_user_response_should_contain_a_type_id_and_attributes_under_a_data_key()
    {
        $user = create(User::class);

        $this->fetchUser($user)
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'id'   => (string)$user->id,
                    'attributes' => [
                        'username' => $user->username,
                        'email' => $user->email,
                        'fullname' => $user->fullname,
                        'profile_image' => $user->profile_image,
                        'contact_info' => $user->contact_info,
                    ]
                ]
            ]);
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
