<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosterImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poster_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poster_id');
            $table->unsignedBigInteger('image_id');
            $table->foreign('poster_id')->references('id')->on('posters');
            $table->foreign('image_id')->references('id')->on('images');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poster_image');
    }
}
