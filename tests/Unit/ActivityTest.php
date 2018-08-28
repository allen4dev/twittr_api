<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Activity;
use App\User;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_belongs_to_a_user()
    {
        $activity = create(Activity::class);

        $this->assertInstanceOf(User::class, $activity->user);
    }
}
