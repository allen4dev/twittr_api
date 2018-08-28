<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
use App\Tweet;
use App\User;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_reply_knows_his_path()
    {
        $reply = create(Reply::class);

        $this->assertEquals("/api/replies/{$reply->id}", $reply->path());
    }

    /** @test */
    public function a_reply_belongs_to_a_tweet()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(Tweet::class, $reply->tweet);
    }

    /** @test */
    public function a_reply_belongs_to_a_user()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(User::class, $reply->user);
    }
}
