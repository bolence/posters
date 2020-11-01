<?php

namespace App\Http\Controllers\Api;

use App\Album;
use Exception;
use App\AlbumPoster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AlbumApiController extends Controller
{
    /**
     * List all albums
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'albums' => Album::with('posters')->get()
        ], 200);
    }

    /**
     * Save new album
     *
     * @param Request $request
     * @return Response json
     */
    public function store(Request $request)
    {

        $album = new Album;
        $album->album_name = $request->input('album_name');
        $saved = $album->save();


        if( ! $saved ){

            Log::error('Unable to craete a new album with');

            return response()->json([
                'message' => 'Unable to create a new album ' . $album->album_name
            ], 400);
        }

        $posters = $request->input('posters');

        // we can add more posters at once to one album
        if( isset($posters) && count($posters) > 0 )
        {
            foreach ($posters as $poster_id)
            {
                $album_poster = new AlbumPoster();
                $album_poster->poster_id = $poster_id;
                $album_poster->album_id = $album->id;
                $album_poster->save();
            }
        }


        Log::error('New album created ' . print_r($album, true));

        return response()->json([
            'message' => 'New album created',
            'album' => $album->with('posters')->get()
        ], 200);

    }

    /**
     * Update album resource
     *
     * @param  Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $album = Album::find($id);
        $album->album_name = $request->input('album_name');

        $posters = $request->input('posters');

        try
        {
            $album->save();
        }
        catch( Exception $e )
        {

            Log::error('Unable to update album with error ' . $e->getMessage() . ' on line ' . $e->getLine());

            return response()->json([
                'error'   => $e->getMessage(),
                'message' => 'Unable to update album ' . $album->album_name
            ], 400);

        }

        if(isset($posters) && count($posters) > 0 )
        {
            foreach($posters as $id => $poster_id)
            {
                AlbumPoster::updateOrCreate([
                    'album_id' => $id
                ],
                [
                    'poster_id' => $poster_id
                ]
            );
            }
        }

        return response()->json([
            'message' => 'Album successfully updated',
            'album'   => $album->with('posters')->get()
        ], 200);
    }

    /**
     * Delete album with related posters
     *
     * @param Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $album = Album::find($id);

        if( ! $album )
        {
            return response()->json([
                'message' => 'Unable to find album with ID: ' . $id
            ], 400);
        }

        try
        {
            $album->posters()->delete();
            $album->delete();

        }
        catch( Exception $e )
        {
            Log::error('Unable to delete album with message ' . $e->getMessage() . ' in file ' . $e->getFile());

            return response()->json([
                'message' => 'Unable to delete album or related messages',
                'error' => $e->getMessage(),
            ], 400);
        }

        Log::info("Album deleted");

        return response()->json([
            'message' => "Album with ID: $id successfully deleted"
        ], 200);

    }
}
