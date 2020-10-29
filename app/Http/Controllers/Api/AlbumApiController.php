<?php

namespace App\Http\Controllers\Api;

use App\Album;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlbumApiController extends Controller
{
    /**
     * List all albums
     *
     * @return Response json
     */
    public function index()
    {
        return response()->json([
            'albums' => Album::all()
        ], 200);
    }

    /**
     * Update album resource
     *
     * @param Request $request
     * @param Integer $id
     * @return Response json
     */
    public function update(Request $request, $id)
    {
        $album = Album::find($id);
    }   

    /**
     * Delete album
     *
     * @param Integer $id
     * @return Response json
     */
    public function destroy($id)
    {

        $album = Album::find($id);

        if( !$album )
        {
            return;
        }

        $delete = $album->delete();

        if( $delete )
        {
            return response()->json(['message' => 'Album successfully deleted'], 200);
        }

        return response()->json(['message' => 'Unable to delete album'], 400);
        
    }
}
