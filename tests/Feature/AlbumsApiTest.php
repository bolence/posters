<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AlbumsApiTest extends TestCase
{

    use DatabaseMigrations;

    /** @var $endpoint */
    protected $endpoint = '/api/albums';

    /**
     *
     * User can see all albums
     *
     * @return void
     */
    public function testGetAllAlbums()
    {
        $response = $this->json('GET', $this->endpoint);
        $response->assertStatus(200);
    }

    /**
     * Test if user can create album
     *
     * @return void
     */
    public function testCanCreateAlbum()
    {

        $payload = ['album_name' => 'Moj prvi album'];

        $response = $this->json('POST', $this->endpoint, $payload);

        $response->assertStatus(200);
    }

    /**
     * Test if user can update album
     *
     * @return void
     */
    public function testCanUpdateAlbum()
    {
        $album = \App\Album::create([
            'album_name' => 'Prvi album',
        ]);

        $posters[$album->id] = ['1'];

        $payload = ['album_name' => 'Drugi album', 'posters[]' => $posters];

        $response = $this->json('PUT', $this->endpoint . '/' . $album->id, $payload);
        $response->assertStatus(200);
    }

    /**
     * Test if user can delete album
     *
     * @return void
     */
    public function testCanDeleteAlbum()
    {
        $album = \App\Album::create([
            'album_name' => 'Album za brisanje'
        ]);

        $response = $this->json('DELETE', $this->endpoint . '/' . $album->id);
        $response->assertStatus(200);
    }
}
