<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
use App\User;

class TweetResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
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
                        'related' => route('tweets.show', [ 'id' => $tweet->id ])
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_containing_a_user_resource_identifier_under_a_data_object()
    {
        $user  = create(User::class);
        $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->fetchTweet($tweet)
            ->assertJson([
                'data' => [
                    'relationships' => [
                        "user" => [
                            "links" => [
                                "related" => route('users.show', [ 'id' => $user->id ])
                            ],
                            "data" => [ "type" => "users", "id" => (string) $user->id ]
                        ]
                    ]
                ]
            ]);
    }

     /** @test */
    public function it_should_contain_a_user_resource_under_a_included_object_at_the_same_level_of_the_data_object()
    {
        $user = create(User::class);
        $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->json('GET', $tweet->path())
            ->assertJson([
                'included' => [
                    'type' => 'users',
                    'id'   => (string) $user->id,
                    'attributes' => [
                        'username' => $user->username,
                        'email' => $user->email,
                        // more user fields
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_tweet_resources_under_a_data_object()
    {
        $user = create(User::class);

        $tweet1 = create(Tweet::class, [ 'user_id' => $user->id ]);
        $tweet2 = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->json('GET', $user->path() . '/tweets')
            ->assertJson([
                'data' => [
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

    public function fetchTweet($tweet)
    {
        return $this->json('GET', $tweet->path());
    }
}
