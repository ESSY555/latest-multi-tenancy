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
        Schema::create('student_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('academic_year')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->timestamps();

            // Unique constraint to prevent duplicate assignments
            $table->unique(['student_id', 'subject_id', 'school_class_id', 'academic_year'], 'student_subject_unique');
            
            // Indexes for better performance
            $table->index(['student_id']);
            $table->index(['subject_id']);
            $table->index(['school_class_id']);
            $table->index(['academic_year']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subjects');
    }
};