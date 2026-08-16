<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['assignment', 'result', 'attendance', 'announcement', 'system']);
            $table->boolean('is_read')->default(false);
            $table->json('data')->nullable(); // Additional data for the notification
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['branch_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
