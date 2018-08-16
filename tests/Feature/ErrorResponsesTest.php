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

    /** @test */
    public function try_to_get_a_non_existing_resource_should_return_a_model_not_found_error_with_a_404_status_code()
    {
        $this->json('GET', '/api/tweets/999')
            ->assertExactJson([
                'errors' => [
                    'status' => '404',
                    'title'  => 'Model not found',
                    'detail' => "Tweet with that id does not exist",
                ]
            ])->assertStatus(404);
                    
        $this->json('GET', '/api/replies/999')
            ->assertExactJson([
                'errors' => [
                    'status' => '404',
                    'title'  => 'Model not found',
                    'detail' => "Reply with that id does not exist",
                ]
            ])->assertStatus(404);
    }
}
