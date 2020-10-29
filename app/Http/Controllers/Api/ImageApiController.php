<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Image;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageApiController extends Controller
{

    /**
     * List all image which is not posters
     *
     * @return void
     */
    public function index()
    {

        $images = DB::table('images')->whereNotIn('id', function($q) {
            $q->select('image_id')->from('poster_images');
        })->get();

        return response()->json(['images' => $images], 200);
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
        $image->save();

        Storage::disk('s3')->store('uploads', $filename );

        return response()->json(['message' => "Image $filename successfully uploaded"], 200);

    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        $image = Image::find($id);
        
        if( !$image )
        {
            return;
        }

        $delete = $image->delete();

        if( $delete )
        {
            return response()->json(['message' => 'Successfully deleted'], 200);
        }

        return response()->json(['message' => 'Unable to delete requested image'], 400);

        
    }
}
