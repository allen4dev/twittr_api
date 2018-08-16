<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ErrorResponsesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function try_to_get_create_update_or_delete_a_protected_resource_without_a_valid_token_should_return_a_401_unauthenticated_error()
    {
        $this->json('POST', '/api/tweets', [])
            ->assertExactJson([
                'errors' => [
                    'status' => '401',
                    'title'  => 'Unauthenticated',
                    'detail' => 'This action is only allowed to authenticated members'
                ]
            ])->assertStatus(401);
    }
}
