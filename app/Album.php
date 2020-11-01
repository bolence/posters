<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Poster;

class Album extends Model
{

     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    public function posters()
    {
        return $this->hasMany(AlbumPoster::class);
    }


}
