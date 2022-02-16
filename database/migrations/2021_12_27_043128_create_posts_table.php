<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();

            $table->bigInteger('author_id');

            $table->string('slug', 256)->unique()->nullable();
            $table->longText('title');
            $table->longText('description');
            $table->json('keywords');
            $table->longText('content');
            $table->json('paragraph')->nullable();

            $table->string('language')->nullable()->default('id');

            $table->integer('views')->nullable()->default(0);
            $table->integer('shares')->nullable()->default(0);

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
