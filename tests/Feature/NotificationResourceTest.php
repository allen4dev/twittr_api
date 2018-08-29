<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_an_id_type_and_attributes_under_a_data_object()
    {
        $this->withoutExceptionHandling();
        // Given we have an authenticated user
        // followed by other user

        // When he fetch a single notification

        // Then 
    }
}
