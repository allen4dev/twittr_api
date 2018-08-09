<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Tweet;

class UpdateTweetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_update_his_tweet()
    {
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
}
