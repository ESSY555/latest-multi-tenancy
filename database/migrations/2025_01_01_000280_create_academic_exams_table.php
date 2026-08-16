<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_term_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_semester_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('exam_type', [
                'midterm', 'final', 'quiz', 'assignment', 'project', 
                'presentation', 'practical', 'oral', 'written', 'other'
            ])->default('other');
            $table->date('exam_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('total_marks');
            $table->integer('passing_marks');
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_online')->default(false);
            $table->string('location')->nullable();
            $table->text('instructions')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['academic_year_id', 'exam_type']);
            $table->index(['exam_date']);
            $table->index(['subject_id', 'school_class_id']);
            $table->index(['is_published', 'is_online']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_exams');
    }
};
