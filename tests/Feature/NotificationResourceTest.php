<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Notifications\FollowedUser;

use App\User;

class NotificationResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_an_id_type_and_attributes_under_a_data_object()
    {
        $this->signin();

        $user2 = create(User::class);

        $this->followUser($user2);

        auth()->logout();
        $this->signin($user2);

        $notification = $user2->notifications()->first();

        $this->json('GET', "/api/me/notifications/{$notification->id}")
            ->assertJson([
                'data' => [
                    'type' => 'notifications',
                    'id'   => $notification->id,
                    'attributes' => [
                        'message' => $notification->data['message'],
                        'additional_information' => $notification->data['additional'],
                        'subject' => 'FollowedUser',
                        'created_at' => (string) $notification->created_at,
                        'updated_at' => (string) $notification->updated_at,
                        'read_for_humans' => $notification->created_at->diffForHumans(),
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_containing_a_user_resource_identifier_under_the_data_object()
    {
        $this->signin();

        $user2 = create(User::class);

        $this->followUser($user2);

        auth()->logout();
        $this->signin($user2);

        $notification = $user2->notifications()->first();

        $this->json('GET', "/api/me/notifications/{$notification->id}")
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'type' => 'users',
                        'id'   => $notification->notifiable_id,
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_notification_resources_under_a_data_object()
    {
        $this->signin();

        $user2 = create(User::class);

        $this->followUser($user2);

        auth()->logout();
        $this->signin($user2);

        $notification = $user2->notifications()->first();

        $this->json('GET', "/api/me/notifications/unread")
            ->assertJson([
                'data' => [[
                    'type' => 'notifications',
                    'id'   => $notification->id,
                    'attributes' => [
                        'message' => $notification->data['message'],
                        'additional_information' => $notification->data['additional'],
                        'subject' => 'FollowedUser',
                        'created_at' => (string) $notification->created_at,
                        'updated_at' => (string) $notification->updated_at,
                        'read_for_humans' => $notification->created_at->diffForHumans(),
                    ]
                ]]
            ]);
    }

    public function followUser($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }
}
