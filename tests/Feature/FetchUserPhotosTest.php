<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Photo;

class FetchUserPhotosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_all_photos_of_a_user()
    {
        $user = create(User::class);

        create(Photo::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/photos')
            ->assertJson([
                'data' => [
                    [ 'type' => 'photos', 'id' => '1' ],
                    [ 'type' => 'photos', 'id' => '2' ],
                ]
            ])
            ->assertStatus(200);
    }
}
