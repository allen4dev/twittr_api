<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_an_avatar_to_his_profile()
    {
        Storage::fake('public');

        $this->signin();

        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $this->json('POST', 'api/me/avatar', [ 'avatar' => $file ]);
        
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
        
        $this->assertNotNull(auth()->user()->fresh()->avatar_url);
    }
}
