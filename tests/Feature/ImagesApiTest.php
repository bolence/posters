<?php

namespace Tests\Feature;

use App\Image;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ImagesApiTest extends TestCase
{

    use DatabaseMigrations;

    /** @var $endpoint */
    protected $endpoint = '/api/images';

    /**
     * User can see all uploaded images
     *
     * @return void
     */
    public function testCanGetAllImages()
    {
        $response = $this->json('GET', $this->endpoint);

        $response->assertStatus(200);
    }

    /**
     * User can upload images
     *
     * @return void
     */
    public function testCanUploadImages()
    {
        Storage::fake('s3');

        $image = UploadedFile::fake()->create('test.png', 0);
        $response = $this->json('POST', $this->endpoint, [
            'file' => $image
        ]);

        $response->assertStatus(200);
    }

    /**
     * User can delete image
     *
     * @return void
     */
    public function testCanDeleteImage()
    {

        $image = Image::create([
            'image_name' => time() . '_bosko.jpg'
        ]);

        $response = $this->json('DELETE', $this->endpoint . '/' . $image->id);

        $response->assertStatus(200);
    }
}
