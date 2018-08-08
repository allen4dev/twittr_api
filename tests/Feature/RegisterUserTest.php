<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_register_to_the_app()
    {
        $this->withoutExceptionhandling();
        $user = [
            "username" => "Allen",
            "email"    => "alanaliagadev@example.test",
            "password" => "supersecret",
        ];

        $this->json('POST', '/api/register', $user)
            ->assertStatus(201);
    
        $this->assertDatabaseHas('users', [
            "username" => $user['username'],
            "email" => $user['email'],
        ]);
    }
}
