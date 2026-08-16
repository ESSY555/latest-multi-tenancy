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
        Schema::create('teacher_reports', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('teacher_name');
            $table->date('report_date');
            
            // Class & Subject Details
            $table->json('classes_taught'); // Multi-select classes
            $table->json('subjects_taught'); // Multi-select subjects
            $table->text('topics_covered');
            
            // Lesson & Progress
            $table->enum('teaching_method', [
                'lecture', 
                'group_work', 
                'practical', 
                'discussion', 
                'demonstration', 
                'project_based', 
                'blended', 
                'other'
            ])->nullable();
            $table->boolean('objectives_achieved')->default(false);
            $table->text('objectives_notes')->nullable();
            $table->enum('student_participation', [
                'excellent', 
                'good', 
                'average', 
                'poor', 
                'very_poor'
            ])->nullable();
            $table->text('participation_notes')->nullable();
            
            // Assignments & Activities
            $table->boolean('homework_assigned')->default(false);
            $table->text('homework_details')->nullable();
            $table->text('class_activities')->nullable();
            
            // Challenges & Needs
            $table->text('challenges_faced')->nullable();
            $table->text('materials_needed')->nullable();
            
            // Additional Notes
            $table->text('additional_notes')->nullable();
            
            // Relationships
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('branch_id');
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['teacher_id', 'report_date']);
            $table->index(['branch_id', 'status']);
            $table->index(['status', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_reports');
    }
};
