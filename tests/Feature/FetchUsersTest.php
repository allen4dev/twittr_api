<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class FetchUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $credentials = [
            'username' => 'Allen',
            'email'    => 'allen@example.test',
            'password' => 'secret',
        ];

        $response = $this->json('POST', '/api/register', $credentials);

        $this->credentials = $credentials;
        $this->token = $response->original['data']['token'];
    }

    /** @test */
    public function a_registered_user_can_retrieve_his_information()
    {
        $this->withoutExceptionHandling();

        $token = $this->token;

        $this->json('GET', '/api/me', [], ['HTTP_Authorization' => $token])
            ->assertJson([
                'data' => User::first()->toArray()
            ]);
    }
}
