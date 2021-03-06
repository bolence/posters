<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlbumPoster extends Model
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


    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
