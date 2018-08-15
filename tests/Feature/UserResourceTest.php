<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Resources\UserIdentifierResource;

use App\User;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $user = create(User::class);

        $this->fetchUser($user)
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'id'   => (string)$user->id,
                    'attributes' => [
                        'username' => $user->username,
                        'email' => $user->email,
                        'fullname' => $user->fullname,
                        'avatar_url' => $user->avatar_url,
                        'profile_image' => $user->profile_image,
                        'contact_info' => $user->contact_info,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $user = create(User::class);

        $this->fetchUser($user)
            ->assertJson([
                'data' => [
                    'links' => [
                        'related' => route('users.show', ['user' => $user->id])
                    ]
                ]
            ]);
    }

    public function fetchUser($user)
    {
        return $this->json('GET', $user->path());
    }
}
