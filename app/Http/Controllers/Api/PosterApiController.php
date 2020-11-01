<?php

namespace App\Http\Controllers\Api;

use App\Poster;
use App\PosterImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PosterApiController extends Controller
{

    /**
     * Get all posters with related images
     *
     * @return void
     */
    public function index()
    {
        return response()->json([
            'posters' => Poster::with('poster_images')->get()
        ], 200);
    }

    /**
     * Save new poster
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $images = $request->input('images');

        $poster = new Poster();
        $poster->side_bck_color = $request->input('side_bck_color');
        $poster->title = $request->input('title');
        $poster->description = $request->input('description');
        $saved = $poster->save();

        if( ! $saved )
        {
            return response()->json([
                'message' => 'Unable to save a new poster'
            ], 400);
        }

        if(isset($images) && count($images) > 0)
        {
            foreach($images as $image_id)
            {
                $poster_image = new PosterImage;
                $poster_image->poster_id = $poster->id;
                $poster_image->image_id  = $image_id;
                $poster_image->save();
            }
        }


        return response()->json([
            'message' => 'Successfully saved new poster',
            'poster' => $poster->with('poster_images')->get()
        ], 200);
    }

    /**
     * Update poster
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $poster = Poster::find($id);

        $images = $request->input('images');

        if( ! $poster )
        {
            return response()->json([
                'message' => 'Unable to find poster with ID: ' . $id
            ], 400);
        }

        $poster->fill($request->except('images'));

        // update all images from poster
        if(isset($images) && count($images) > 0 )
        {
            foreach($images as $id => $image_id)
            {
                PosterImage::updateOrCreate(['poster_id' => $id], ['image_id' => $image_id]);
            }
        }

        $poster->save();

        return response()->json(['message' => 'Poster "' . $poster->title . '" successfully updated'], 200);
    }

    /**
     * Remove poster resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $poster = Poster::find($id);

        if( ! $poster )
        {
            return response()->json([
                'message' => 'Unable to find poster with ID: ' . $id
            ], 400);
        }

        try
        {
            $poster->poster_images()->delete();
            $poster->delete();
        }
        catch(Exception $e)
        {
            Log::error('Unable to delete poster or poster image with message ' . $e->getMessage() . ' in file ' . $e->getFile());
            return response()->json([
                'message' => 'Unable to delete poster',
                'error'   => $e->getMessage()
            ], 400);
        }

        Log::info('Poster deleted');

        return response()->json([
            'message' => 'Poster successfully deleted'
        ], 200);

    }
}
