<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Poster;
use Image;

class PosterImage extends Model
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


    public function poster()
    {
        return $this->belongsTo(Poster::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
