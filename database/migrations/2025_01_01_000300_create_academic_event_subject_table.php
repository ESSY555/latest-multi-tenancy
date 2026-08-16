<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_event_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Unique constraint to prevent duplicate relationships
            $table->unique(['academic_event_id', 'subject_id']);
            
            // Indexes for better performance
            $table->index(['academic_event_id']);
            $table->index(['subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_event_subject');
    }
};
