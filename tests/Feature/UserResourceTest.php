<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\UserIdentifierResource;

use App\User;
use App\Tweet;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
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
                        'avatar_url' => $user->avatar_url,
                        'profile_image' => $user->profile_image,
                        'contact_info' => $user->contact_info,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_included_object_with_the_user_tweets_as_tweet_resources_if_the_request_contains_a_include_query_parameter_with_value_tweets()
    {
        $user = create(User::class);

        $tweet1 = create(Tweet::class, [ 'user_id' => $user->id ]);
        $tweet2 = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->json('GET', $user->path() . '?include=tweets')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'tweets',
                        'id'   => (string) $tweet1->id,
                        'attributes' => [
                            'body' => $tweet1->body,
                        ]
                    ],
                    [
                        'type' => 'tweets',
                        'id'   => (string) $tweet2->id,
                        'attributes' => [
                            'body' => $tweet2->body,
                        ]
                    ],
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $user = create(User::class);

        $this->fetchUser($user)
            ->assertJson([
                'data' => [
                    'links' => [
                        'self' => route('users.show', ['user' => $user->id])
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_user_resources_under_a_data_object()
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
                'data' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $follower->id,
                        'attributes' => [
                            'username'      => $follower->username,
                            'email'         => $follower->email,
                            // more info
                        ]
                    ]
                ]
            ]);
    }

    public function fetchUser($user)
    {
        return $this->json('GET', $user->path());
    }
}
