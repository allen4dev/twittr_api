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
    public function a_user_receives_tweets_created_by_users_who_he_is_following_in_his_timeline()
    {
        $this->signin();

        $followedUser = create(User::class);

        DB::table('followers')->insert([
            'follower_id'  => auth()->id(),
            'following_id' => $followedUser->id,
        ]);

        $tweet = create(Tweet::class, [ 'user_id' => $followedUser->id ]);

        $this->json('GET', '/api/me/timeline')
            ->assertJson([
                'data' => [ [ 'type' => 'tweets', 'id' => '1'] ]
            ])
            ->assertStatus(200);
    }
}
