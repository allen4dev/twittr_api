<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;

use App\Tweet;
use App\User;

class FetchTimelineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_his_timeline()
    {
        $this->signin();

        $followedUser = create(User::class);

        DB::table('followers')->insert([
            'follower_id'  => auth()->id(),
            'following_id' => $followedUser->id,
        ]);

        $tweet = create(Tweet::class, [ 'user_id' => $followedUser->id ]);

        $this->json('GET', '/api/me/timeline')
            ->assertJson([ 'data' => [ $tweet->toArray() ]])
            ->assertStatus(200);
    }

    public function publishTweet()
    {
        $tweet = [ 'body' => 'hey' ];

        return $this->json('POST', '/api/tweets', $tweet);
    }
}
