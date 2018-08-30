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
        $this->withoutExceptionHandling();

        $user1 = create(User::class);
        $this->signin($user1);

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
                        'additional' => [
                            'content' => $notification->data['additional']['content'],
                            'sender_avatar'   => $user1->avatar_url,
                            'sender_username' => $user1->username,
                        ],
                        'action' => 'FollowedUser',
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
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
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
                    'links' => [
                        'self' => route('notifications.show', [ 'notification' => $notification->id])
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_notification_resources_under_a_data_object()
    {
        $user1 = create(User::class);
        $this->signin($user1);

        $user2 = create(User::class);

        $this->followUser($user2);

        auth()->logout();
        $this->signin($user2);

        $notification = $user2->notifications()->first();

        $this->json('GET', "/api/me/notifications")
            ->assertJson([
                'data' => [[
                    'type' => 'notifications',
                    'id'   => $notification->id,
                    'attributes' => [
                        'message' => $notification->data['message'],
                        'additional' => [
                            'content' => $notification->data['additional']['content'],
                            'sender_avatar'   => $user1->avatar_url,
                            'sender_username' => $user1->username,
                        ],
                        'action' => 'FollowedUser',
                        'created_at' => (string) $notification->created_at,
                        'updated_at' => (string) $notification->updated_at,
                        'read_for_humans' => $notification->created_at->diffForHumans(),
                    ]
                ]]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_details_about_the_pagination_under_a_links_object_at_the_same_level_of_the_data_object()
    {
        $this->signin();

        $this->json('GET', "/api/me/notifications")
            ->assertJson([
                'links' => [ 'self' => route('notifications.unread') ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_user_resource_under_a_included_object_at_the_same_level_of_the_data_object()
    {
        $this->signin();

        $user2 = create(User::class);

        $this->followUser($user2);

        auth()->logout();
        $this->signin($user2);

        $this->json('GET', "/api/me/notifications")
            ->assertJson([
                'included' => [[
                    'type' => 'users',
                    'id'   => (string) $user2->id,
                    'attributes' => [
                        'username' => $user2->username,
                        'email' => $user2->email,
                        // more user fields
                    ]
                ]]
            ]);
    }

    public function followUser($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }
}
