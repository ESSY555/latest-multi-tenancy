<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('publisher')->nullable();
            $table->string('language')->nullable();
            $table->string('file_path'); // Path to the PDF file
            $table->string('cover_image')->nullable(); // Optional cover image
            $table->enum('status', ['available', 'borrowed', 'reserved'])->default('available');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
