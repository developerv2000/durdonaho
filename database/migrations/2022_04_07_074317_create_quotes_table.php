<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->integer('author_id')->nullable();
            $table->integer('user_id'); // publisher
            $table->integer('source_id');
            $table->string('source_image')->nullable();
            $table->integer('source_book_id')->nullable();
            $table->integer('source_movie_id')->nullable();
            $table->integer('source_song_id')->nullable();
            $table->boolean('popular')->default(0);
            $table->boolean('verified')->default(0); // by admin (проверено)
            $table->boolean('approved')->default(0); // approved or denied by admin (одобрено)
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
        Schema::dropIfExists('quotes');
    }
};
