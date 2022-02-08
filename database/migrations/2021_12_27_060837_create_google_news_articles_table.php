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
            $table->string('guid', 200)->unique()->primary();
            $table->string('title', 200)->unique();
            $table->string('link', 200)->unique();
            $table->string('status')->nullable()->default('pending');

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
