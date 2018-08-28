<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Activity;
use App\Reply;
use App\Tweet;
use App\User;

class ActivityResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_collection_should_contain_a_list_of_activity_resources_under_a_data_object()
    {
        $this->withoutExceptionHandling();

        $this->signin();

        create(Tweet::class, [ 'user_id' => auth()->id() ]);
        create(Tweet::class, [ 'user_id' => auth()->id() ]);

        $this->fetchUserActivities()
            ->assertJson([
                'data' => [
                    [
                        'type' => 'activities',
                        'id'   => '1',
                        'attributes' => [
                            'action' => Activity::first()->action,
                        ]
                    ],
                    [
                        'type' => 'activities',
                        'id'   => '2',
                        'attributes' => [
                            'action' => Activity::find(2)->action,
                        ]
                    ],
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $this->withoutExceptionhandling();

        $this->signin();

        create(Tweet::class, [ 'user_id' => auth()->id() ]);
        create(Tweet::class, [ 'user_id' => auth()->id() ]);

        $this->fetchUserActivities()
            ->assertJson([
                'links' => [ 'self' => route('activities') ]
            ]);
    }

    /** @test */
    public function a_collection_should_also_contain_the_activities_author_under_a_includes_object_at_the_same_level_of_the_data_object()
    {
        $this->signin();

        create(Tweet::class, [ 'user_id' => auth()->id() ]);
        create(Reply::class, [ 'user_id' => auth()->id() ]);

        $this->fetchUserActivities()
            ->assertJson([
                'included' => [[
                    'type' => 'users',
                    'id'   => (string) auth()->id(),
                    'attributes' => [
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                        // more user data
                    ]
                ]]
            ]);
    }

    public function fetchUserActivities()
    {
        return $this->json('GET', '/api/me/activities');
    }
}
