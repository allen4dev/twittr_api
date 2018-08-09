<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_register_to_the_app()
    {
        $this->withoutExceptionHandling();
        $user = [
            "username" => "Allen",
            "email"    => "allen@example.test",
            "password" => "supersecret",
        ];

        $response = $this->json('POST', '/api/auth/register', $user)->assertStatus(201);
    
        $this->assertTrue(array_key_exists('token', $response->original['data']));

        $this->assertDatabaseHas('users', [
            "username" => $user['username'],
            "email" => $user['email'],
        ]);
    }

    /** @test */
    public function a_registered_user_can_login_with_his_credentials()
    {
        $credentials = [
            'username' => 'Allen',
            'email'    => 'allen@example.test',
            'password' => 'secret',
        ];

        $this->json('POST', '/api/auth/register', $credentials);

        $response = $this->json('POST', '/api/auth/login', [
            "email" => $credentials['email'],
            "password" => $credentials['password'],
        ])->assertStatus(200);

        $this->assertTrue(array_key_exists('token', $response->original['data']));
    }
}
