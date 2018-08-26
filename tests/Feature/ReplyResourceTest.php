<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
use App\Tweet;

class ReplyResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $this->withoutExceptionHandling();

        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson([
                'data' => [
                    'type' => 'replies',
                    'id'   => (string) $reply->id,
                    'attributes' => [
                        'body' => $reply->body,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson([
                'data' => [
                    'links' => [
                        'related' => route('replies.show', [ 'reply' => $reply->id ])
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_containing_a_tweet_resource_identifier_under_a_data_object()
    {
        $tweet  = create(Tweet::class);
        $reply = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->fetchReply($reply)
            ->assertJson([
                'data' => [
                    'relationships' => [
                        "tweet" => [
                            "links" => [
                                "related" => route('tweets.show', [ 'tweet' => $tweet->id ])
                            ],
                            "data" => [ "type" => "tweets", "id" => (string) $tweet->id ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_reply_resources_under_a_data_object()
    {
        $this->withoutExceptionHandling();

        $tweet = create(Tweet::class);

        $reply1 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);
        $reply2 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

        $this->fetchTweetReplies($tweet)
            ->assertJson([
                'data' => [
                    [
                        'type' => 'replies',
                        'id'   => (string) $reply1->id,
                        'attributes' => [
                            'body' => $reply1->body,
                        ]
                    ],
                    [
                        'type' => 'replies',
                        'id'   => (string) $reply2->id,
                        'attributes' => [
                            'body' => $reply2->body,
                        ]
                    ],
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $this->withoutExceptionhandling();

        $tweet   = create(Tweet::class);
        $replies = create(Reply::class, [ 'tweet_id' => $tweet->id ], 2);

        $this->fetchTweetReplies($tweet)
            ->assertJson([
                'links' => [ 'self' => route('tweets.replies', [ 'tweet' => $tweet->id ])]
            ]);
    }

    public function fetchReply($reply)
    {
        return $this->json('GET', $reply->path());
    }

    public function fetchTweetReplies($tweet)
    {
        return $this->json('GET', $tweet->path() . '/replies');
    }
}
