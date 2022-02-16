<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleNewsArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_news_articles', function (Blueprint $table) {
            $table->id();
            $table->longText('guid')->nullable();
            $table->longText('title')->nullable();
            $table->longText('link')->nullable();
            $table->longText('status')->nullable('pending');

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
        Schema::dropIfExists('google_news_articles');
    }
}
