<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->boolean('is_exam_term')->default(false);
            $table->boolean('is_break_term')->default(false);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['academic_year_id']);
            $table->index(['start_date', 'end_date']);
            $table->index(['is_exam_term', 'is_break_term']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
