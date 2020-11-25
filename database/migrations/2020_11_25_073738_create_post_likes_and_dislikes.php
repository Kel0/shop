<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostLikesAndDislikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_likes_and_dislikes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("post_id")->unsigned();
            $table->integer("likes");
            $table->integer("dislikes");
            $table->timestamps();
        });

        Schema::table('post_likes_and_dislikes', function ($table) {
            $table->foreign("post_id")
                    ->references("id")
                    ->on("post")
                    ->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_likes_and_dislikes');
    }
}
