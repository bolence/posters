<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostersApiTest extends TestCase
{

    use DatabaseMigrations;

    /** @var $endpoint */
    protected $endpoint = '/api/posters';

    /**
     * User can see all posters
     *
     * @return void
     */
    public function testCanSeeAllPosters()
    {
        $response = $this->json('GET', $this->endpoint);
        $response->assertStatus(200);
    }

    /**
     * User can create new poster
     *
     * @return void
     */
    public function testCanCreatePoster()
    {
        $image = \App\Image::create([
            'image_name' => time() . '_bosko.jpg'
        ]);

        $payload = [
            'title' => 'Moj novi poster',
            'side_bck_color' => 'black',
            'description' => 'Neki opis postera',
            'images[]' => $image->id
        ];

        $response = $this->json('POST', $this->endpoint, $payload);
        $response->assertStatus(200);

    }

    /**
     * User can update poster
     *
     * @return void
     */
    public function testCanUpdatePoster()
    {
        $poster = \App\Poster::create([
            'title' => 'Novi poster',
            'side_bck_color' => 'red',
            'description' => ''
        ]);

        $payload = [
            'title' => 'Ne tako novi poster',
            'side_bck_color' => 'green',
            'description' => 'Opis postera'
        ];

        $response = $this->json('PUT', $this->endpoint . '/' . $poster->id, $payload);
        $response->assertStatus(200);
    }

    /**
     * User can delete poster
     *
     * @return void
     */
    public function testCanDeleteAlbum()
    {
        $poster = \App\Poster::create([
            'title' => 'Novi poster',
            'side_bck_color' => 'red',
            'description' => ''
        ]);

        $response = $this->json('DELETE', $this->endpoint . '/' . $poster->id);
        $response->assertStatus(200);
    }


}
