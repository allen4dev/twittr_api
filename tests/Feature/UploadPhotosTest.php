<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadPhotosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_upload_a_single_photo()
    {
        Storage::fake('public');

        $this->signin();
        
        $file = UploadedFile::fake()->image('user_photo.jpg');

        $data = [ 'photos' => [ $file ] ];

        $this->json('POST', auth()->user()->path() . '/photos', $data);
        
        tap(auth()->id(), function ($uid) use ( $file ) {
            $path = "photos/{$uid}/{$file->hashName()}";

            Storage::disk('public')->assertExists($path);
    
            $this->assertDatabaseHas('photos', [
                'user_id' => auth()->id(),
                'path'    => $path,
            ]);
        });
    }

    /** @test */
    public function a_user_can_upload_multiple_photos()
    {
        $this->signin();

        $data = [ 'photos' => [
            $photo1 = UploadedFile::fake()->image('user_photo.jpg'),            
            $photo2 = UploadedFile::fake()->image('user_photo.jpg'),            
        ]];

        $this->json('POST', auth()->user()->path() . '/photos', $data);

        foreach ($data['photos'] as $photo) {
            $uid = auth()->id();
            $path = "photos/{$uid}/{$photo->hashName()}";

            $this->assertDatabaseHas('photos', [
                'user_id' => $uid,
                'path' => $path,
            ]);
        }
    }
}
