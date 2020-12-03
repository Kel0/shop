<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("post_id")->unsigned();
            $table->integer("like")->default(0);
            $table->integer("dislike")->default(0);
            $table->timestamps();
        });

        Schema::table('users_meta', function ($table) {
            $table->foreign("post_id")
                    ->references("id")
                    ->on("post")
                    ->onDelete("CASCADE");

            $table->foreign("user_id")
                    ->references("id")
                    ->on("users")
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
        Schema::dropIfExists('users_meta');
    }
}
