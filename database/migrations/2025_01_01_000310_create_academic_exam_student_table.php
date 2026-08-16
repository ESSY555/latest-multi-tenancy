<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_exam_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->integer('score')->nullable();
            $table->string('grade')->nullable();
            $table->enum('status', ['registered', 'attended', 'absent', 'completed'])->default('registered');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate registrations
            $table->unique(['academic_exam_id', 'student_id']);
            
            // Indexes for better performance
            $table->index(['academic_exam_id']);
            $table->index(['student_id']);
            $table->index(['status', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_exam_student');
    }
};
