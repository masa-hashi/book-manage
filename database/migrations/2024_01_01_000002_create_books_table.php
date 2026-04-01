<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->integer('published_year')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->string('genre')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['未読', '読中', '読了'])->default('未読');
            $table->string('cover_image_url')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('rating')->nullable()->comment('1-5');
            $table->date('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
