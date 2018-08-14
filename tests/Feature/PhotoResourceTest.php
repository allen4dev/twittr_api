<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Photo;

class PhotoResourceTest extends TestCase
{
    use RefreshDatabase;

     /** @test */
     public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
     {
         $this->withoutExceptionHandling();

         $user = create(User::class);
         $photo = create(Photo::class, [ 'user_id' => $user->id ]);
 
         $this->fetchUserPhotos($user)
             ->assertJson([
                 'data' => [[
                     'type' => 'photos',
                     'id'   => (string) $photo->id,
                     'attributes' => [
                         'path' => $photo->path,
                     ]
                 ]]
             ]);
     }

     /** @test */
    public function it_should_contain_a_relationships_object_containing_a_user_resource_identifier_under_a_data_object()
    {
        $user  = create(User::class);
        $photo = create(Photo::class, [ 'user_id' => $user->id ]);

        $this->fetchUserPhotos($user)
            ->assertJson([
                'data' => [[
                    'relationships' => [
                        "user" => [
                            "links" => [
                                "related" => route('users.show', [ 'id' => $user->id ])
                            ],
                            "data" => [ "type" => "users", "id" => (string) $user->id ]
                        ]
                    ]
                ]]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_an_included_object_at_the_same_level_of_data_with_the_user_resource()
    {
        $this->withoutExceptionHandling();
        
        $user = create(User::class);

        $photo1 = create(Photo::class, [ 'user_id' => $user->id ]);
        $photo2 = create(Photo::class, [ 'user_id' => $user->id ]);

        $this->fetchUserPhotos($user)
            ->assertJson([
                'included' => [
                    'type' => 'users',
                    'id'   => (string) $user->id,
                    'attributes' => [
                        'username' => $user->username,
                        // more info
                    ],
                    'links' => [
                        'related' => route('users.show', ['user' => $user->id]),
                    ]
                ],
            ]);
    }

     public function fetchUserPhotos($user)
     {
        return $this->json('GET', $user->path() . '/photos');
     }
}
