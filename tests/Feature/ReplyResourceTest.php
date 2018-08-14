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
    // public function a_collection_should_contain_a_list_of_reply_resources_under_a_data_object()
    // {
    //     $this->withoutExceptionHandling();

    //     $tweet = create(Tweet::class);

    //     $reply1 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);
    //     $reply2 = create(Reply::class, [ 'tweet_id' => $tweet->id ]);

    //     $this->fetchTweetReplies($tweet)
    //         ->assertJson([
    //             'data' => [
    //                 [
    //                     'type' => 'replies',
    //                     'id'   => (string) $reply1->id,
    //                     'attributes' => [
    //                         'body' => $reply1->body,
    //                     ]
    //                 ],
    //                 [
    //                     'type' => 'replies',
    //                     'id'   => (string) $reply2->id,
    //                     'attributes' => [
    //                         'body' => $reply2->body,
    //                     ]
    //                 ],
    //             ]
    //         ]);
    // }

    public function fetchTweetReplies($tweet)
    {
        return $this->json('GET', $tweet->path() . '/replies');
    }
}
