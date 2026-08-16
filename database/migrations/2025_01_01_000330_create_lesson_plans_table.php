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
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();
            
            // General Information
            $table->string('teacher_name');
            $table->string('subject_topic');
            $table->string('class_grade_level');
            $table->date('lesson_date');
            $table->string('duration'); // e.g., "40 minutes", "1 hour"
            
            // Lesson Details
            $table->string('lesson_title');
            $table->text('objectives'); // Learning outcomes
            $table->text('materials_resources');
            $table->text('lesson_introduction'); // Set induction / motivation
            $table->text('lesson_development'); // Teaching & learning activities
            $table->text('assessment_evaluation');
            $table->text('conclusion');
            $table->text('reflection')->nullable(); // Optional reflection
            
            // Workflow Status
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Admin who reviewed
            
            // Relationships
            $table->unsignedBigInteger('teacher_id'); // User ID of the teacher
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('class_id')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['teacher_id', 'status']);
            $table->index(['branch_id', 'status']);
            $table->index(['status', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_plans');
    }
};
