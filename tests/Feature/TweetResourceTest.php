<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
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
                        'self' => route('tweets.show', [ 'id' => $tweet->id ])
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
    public function it_should_contain_a_relationships_object_containing_a_replies_resource_identifier_under_a_data_object()
    {
        $tweet = create(Tweet::class);
        $reply1 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);
        $reply2 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->json('GET', $tweet->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'replies' => [
                            'data' => [
                                [
                                    'type' => 'replies',
                                    'id'   => (string) $reply1->id,
                                ],
                                [
                                    'type' => 'replies',
                                    'id'   => (string) $reply2->id,
                                ],
                            ]
                        ]
                    ]
                ]
            ]);
    }

    // /** @test */
    // public function it_should_contain_a_user_resouce_and_a_replies_collection_under_a_included_object_at_the_same_level_of_the_data_object()
    // {
    //     $this->withoutExceptionHandling();

    //     $user  = create(User::class);
    //     $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

    //     $reply1 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);
    //     $reply2 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

    //     $this->json('GET', $tweet->path() . '?include=user,replies')
    //         ->assertJson([
    //             'included' => [
    //                 [
    //                     'type' => 'users',
    //                     'id'   => (string) $user->id,
    //                     'attributes' => [
    //                         'username' => $user->username,
    //                         'email' => $user->email,
    //                         // more user fields
    //                     ]
    //                 ],
    //                 [
    //                     'type' => 'replies',
    //                     'id'   => (string) $reply1->id,
    //                     'attributes' => [
    //                         'body' => $reply1->body,
    //                         // more fields
    //                     ]
    //                 ],
    //                 [
    //                     'type' => 'replies',
    //                     'id'   => (string) $reply2->id,
    //                     'attributes' => [
    //                         'body' => $reply2->body,
    //                         // more fields
    //                     ]
    //                 ],
    //             ]  
    //         ]);
    // }



    /** @test */
    public function it_should_also_contain_the_author_if_the_request_sends_a_include_query_parameter_with_value_user()
    {
        $user  = create(User::class);
        $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->json('GET', $tweet->path() . '?include=user')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $user->id,
                        'attributes' => [
                            'username' => $user->username,
                            'email' => $user->email,
                            // more user fields
                        ]
                    ],
                ]  
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_tweet_replies_if_the_request_sends_a_include_query_parameter_with_value_replies()
    {
        $this->withoutExceptionHandling();

        $tweet = create(Tweet::class);

        $reply1 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);
        $reply2 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->json('GET', $tweet->path() . '?include=replies')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'replies',
                        'id'   => (string) $reply1->id,
                        'attributes' => [
                            'body' => $reply1->body,
                            // more user fields
                        ]
                    ],
                    [
                        'type' => 'replies',
                        'id'   => (string) $reply2->id,
                        'attributes' => [
                            'body' => $reply2->body,
                            // more user fields
                        ]
                    ],
                ]  
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_user_and_the_tweet_replies_if_the_request_sends_a_include_query_parameter_with_a_comma_separeted_value_of_user_and_replies()
    {
        $this->withoutExceptionHandling();

        $user = create(User::class);

        $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

        $reply1 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);
        $reply2 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->json('GET', $tweet->path() . '?include=user,replies')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $user->id,
                        'attributes' => [
                            'username' => $user->username,
                            'email'    => $user->email,
                            // more user fields
                        ]
                    ],
                    [
                        'type' => 'replies',
                        'id'   => (string) $reply1->id,
                        'attributes' => [
                            'body' => $reply1->body,
                            // more user fields
                        ]
                    ],
                    [
                        'type' => 'replies',
                        'id'   => (string) $reply2->id,
                        'attributes' => [
                            'body' => $reply2->body,
                            // more user fields
                        ]
                    ],
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

    /** @test */
    public function a_collection_should_also_contain_the_authors_of_the_tweets_under_a_included_object_at_the_same_level_of_data()
    {
        $user1 = create(User::class, []);
        $user2 = create(User::class, []);
        $tweet1 = create(Tweet::class, [ 'user_id' => $user1->id]);
        $tweet2 = create(Tweet::class, [ 'user_id' => $user2->id]);

        $this->json('GET', '/api/tweets')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $user1->id,
                        'attributes' => [
                            'username' => $user1->username,
                            'email'    => $user1->email,
                            // more fields
                        ]
                    ],
                    [
                        'type' => 'users',
                        'id'   => (string) $user2->id,
                        'attributes' => [
                            'username' => $user2->username,
                            'email'    => $user2->email,
                            // more fields
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
