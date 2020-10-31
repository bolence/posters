<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageApiController extends Controller
{

    /**
     * List all image which is not a posters
     *
     * @return void
     */
    public function index()
    {

        $images = DB::table('images')->whereNotIn('id', function($q) {
            $q->select('image_id')->from('poster_images');
        })->get();

        return response()->json([
            'images' => $images
        ], 200);
    }

    /**
     * Save image and store to server
     *
     * @return void
     */
    public function store(Request $request)
    {

        Storage::fake('s3');

        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);

        $image = new Image;
        $filename = time() . "_" . $request->file->getClientOriginalName();
        $image->image_name = $filename;

        try
        {
            $image->save();
        }
        catch( Exception $e )
        {
            Log::error('Unable to upload new image with message ' . $e->getMessage() . ' in file ' . $e->getFile());

            return response()->json([
                'message' => 'Unable to upload new image',
                'error'   => $e->getMessage()
            ], 400);
        }

        Storage::disk('s3')->store('uploads', $filename );

        Log::info('New image uploaded ' . $filename);

        return response()->json(['message' => "Image $filename successfully uploaded"], 200);

    }


    /**
     * Delete image
     *
     * @param integer $id
     * @return Response json
     */
    public function destroy($id)
    {
        $image = Image::find($id);

        if( ! $image )
        {
            return response()->json([
                'message' => 'Unable to find image with ID: ' . $id
            ], 400);
        }

        try
        {
            $image->delete();
        }
        catch(Exception $e)
        {
            Log::error('Unable to delete image with message ' . $e->getMessage() . ' in file ' . $e->getFile());
            return response()->json([
                'message' => 'Unable to delete requested image'
            ], 400);
        }

        return response()->json([
            'message' => 'Image successfully deleted'
            ], 200);

    }
}
