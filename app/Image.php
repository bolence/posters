<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Image extends Model
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


    public function posterimages()
    {
        return $this->belongsTo(PosterImage::class);
    }
}
