<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_authors', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('book_id');
            $table->ulid('author_id');
            $table->timestamps();

            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('author_id')->references('author_id')->on('authors')->onDelete('cascade');
            
            $table->unique(['book_id', 'author_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_authors');
    }
};