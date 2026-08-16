<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_event_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Unique constraint to prevent duplicate relationships
            $table->unique(['academic_event_id', 'school_class_id']);
            
            // Indexes for better performance
            $table->index(['academic_event_id']);
            $table->index(['school_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_event_class');
    }
};
