<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_reply_knows_his_path()
    {
        $reply = create(Reply::class);

        $this->assertEquals("/api/replies/{$reply->id}", $reply->path());
    }
}