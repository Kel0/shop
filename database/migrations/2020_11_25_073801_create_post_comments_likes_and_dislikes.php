<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostCommentsLikesAndDislikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comments_likes_and_dislikes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("post_comment_id")->unsigned();
            $table->integer("likes");
            $table->integer("dislikes");
            $table->timestamps();
        });

        Schema::table('post_comments_likes_and_dislikes', function ($table) {
            $table->foreign("post_comment_id")
                    ->references("id")
                    ->on("post_comments")
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
        Schema::dropIfExists('post_comments_likes_and_dislikes');
    }
}
