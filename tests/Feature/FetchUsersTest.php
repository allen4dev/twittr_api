<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class FetchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_fetch_the_information_of_a_user()
    {
        $user = create(User::class);

        $this->json('GET', "/api/users/{$user->id}")
            ->assertJson([ 'data' => $user->toArray() ])
            ->assertStatus(200);
    }
}
