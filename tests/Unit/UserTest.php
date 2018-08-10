<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_knows_his_path()
    {
        $user = create(User::class);

        $this->assertEquals("/api/users/{$user->id}", $user->path());
    }

    /** @test */
    public function a_user_has_many_tweets()
    {
        $user = create(User::class);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $user->tweets
        );
    }

    /** @test */
    public function a_user_has_many_favorites()
    {
        $user = create(User::class);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $user->favorites
        );
    }
}
