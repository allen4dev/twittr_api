<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_registered_user_can_retrieve_his_information_if_sends_a_valid_token()
    {
        $response = $this->post('/api/auth/register', [
            'username' => 'Allen',
            'email'    => 'allen@example.test',
            'password' => 'secret',
        ]);

        $token = $response->original['data']['token'];

        $this->json('GET', '/api/me', [], ['Authorization' => 'Bearer ' . $token])
        ->assertJson([
            'data' => auth()->user()->toArray(),
            ])
            ->assertStatus(200);
    }
}