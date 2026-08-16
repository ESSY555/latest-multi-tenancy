<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('event_type', [
                'exam', 'assignment', 'meeting', 'ceremony', 'sports', 
                'cultural', 'academic', 'administrative', 'other'
            ])->default('other');
            $table->string('location')->nullable();
            $table->boolean('is_all_day')->default(true);
            $table->boolean('is_public')->default(true);
            $table->string('color')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['academic_year_id', 'event_type']);
            $table->index(['start_date', 'end_date']);
            $table->index(['is_public', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_events');
    }
};
