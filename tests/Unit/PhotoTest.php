<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\Photo;

class PhotoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_photo_belongs_to_one_user()
    {
        $photo = create(Photo::class);

        $this->assertInstanceOf(User::class, $photo->user);
    }
}
