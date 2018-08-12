<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;
use App\User;

class UpdateTweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function guests_cannot_update_tweets()
    {
        $this->json('PATCH', '/api/tweets/1', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_update_his_tweet()
    {
        // $this->withoutExceptionHandling();
        $token = $this->signin();

        $previousTweet = create(Tweet::class, [ 'user_id' => auth()->id() ]);

        $newFields = [ 'body' => 'A updated body for my Tweet' ];
        $headers   = [ 'Authorization' => 'Bearer ' . $token ];

        $this->json('PATCH', $previousTweet->path(), $newFields, $headers)
            ->assertJson([ 'data' => [ 'body' => $newFields['body'] ]])
            ->assertStatus(200);

        $this->assertDatabaseMissing('tweets', [
            'user_id' => auth()->id(),
            'body'    => $previousTweet->body,
        ]);
        
        $this->assertDatabaseHas('tweets', [
            'user_id' => auth()->id(),
            'body'    => $newFields['body'],
        ]);
    }

    /** @test */
    public function just_authorized_users_can_update_a_tweet()
    {
        $this->signin();

        $user  = create(User::class);
        $tweet = create(Tweet::class, [ 'user_id' => $user->id ]);

        $this->json('PATCH', $tweet->path(), ['body' => 'Updating other user tweet'])
            ->assertStatus(403);
    }
}
