<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Album;

class Poster extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];



    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function poster_images()
    {
        return $this->hasMany(PosterImage::class);
    }
}
