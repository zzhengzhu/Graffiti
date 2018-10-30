<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            // Add a Point spatial data field named location
            $table->point('location');
            $table->text('content')->nullable();
            $table->text('link')->nullable();
            $table->text('type')->nullable();
            $table->unsignedBigInteger('radius')->default('100');
            $table->unsignedBigInteger('pointto_id')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
