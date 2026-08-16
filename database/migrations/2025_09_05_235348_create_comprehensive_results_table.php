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
        // Drop the existing results table (if any) and recreate it with the new structure
        Schema::dropIfExists('results');
        
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('academic_terms')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            
            // Assessment scores
            $table->decimal('ca1', 5, 2)->default(0)->comment('Continuous Assessment 1 (0-10)');
            $table->decimal('ca2', 5, 2)->default(0)->comment('Continuous Assessment 2 (0-10)');
            $table->decimal('ca3', 5, 2)->default(0)->comment('Continuous Assessment 3 (0-10)');
            $table->decimal('exam', 5, 2)->default(0)->comment('Examination Score (0-60)');
            
            // Calculated fields
            $table->decimal('total', 5, 2)->default(0)->comment('Total Score (CA1+CA2+CA3+Exam)');
            $table->string('grade', 2)->nullable()->comment('Grade (A, B, C, D, F)');
            $table->string('remark')->nullable()->comment('Remark (Excellent, Good, Fair, Poor, Fail)');
            
            // Class statistics
            $table->integer('position')->nullable()->comment('Position in class for this subject');
            $table->decimal('class_highest', 5, 2)->nullable()->comment('Highest score in class for this subject');
            $table->decimal('class_lowest', 5, 2)->nullable()->comment('Lowest score in class for this subject');
            $table->decimal('class_average', 5, 2)->nullable()->comment('Average score in class for this subject');
            
            // Approval system
            $table->boolean('is_approved')->default(false)->comment('Whether result is approved by admin');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Comments
            $table->text('form_teacher_comment')->nullable()->comment('Form teacher comment');
            $table->text('school_head_comment')->nullable()->comment('School head/principal comment');
            
            // Attendance
            $table->integer('attendance_present')->default(0)->comment('Number of days present');
            $table->integer('attendance_absent')->default(0)->comment('Number of days absent');
            $table->integer('attendance_late')->default(0)->comment('Number of days late');
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['student_id', 'subject_id', 'term_id']);
            $table->index(['school_class_id', 'subject_id', 'term_id']);
            $table->index(['branch_id', 'term_id']);
            $table->index('is_approved');
            
            // Unique constraint to prevent duplicate results
            $table->unique(['student_id', 'subject_id', 'term_id', 'branch_id'], 'unique_student_subject_term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
