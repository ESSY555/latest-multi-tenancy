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
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('subject');
            $table->string('class_level')->nullable();
            $table->string('type'); // PDF, Video, Presentation, Worksheet, etc.
            $table->string('file_path'); // Path to the uploaded file
            $table->bigInteger('file_size')->default(0); // File size in bytes
            $table->integer('duration')->nullable(); // Duration in seconds (for videos)
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->unsignedBigInteger('uploaded_by'); // User ID who uploaded
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['subject', 'type']);
            $table->index(['is_active', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_materials');
    }
};
