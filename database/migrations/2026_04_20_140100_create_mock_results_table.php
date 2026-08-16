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
        Schema::create('mock_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('mock_exam_id')->constrained('mock_exams')->onDelete('cascade');

            $table->decimal('ca1', 5, 2)->default(0);
            $table->decimal('ca2', 5, 2)->default(0);
            $table->decimal('ca3', 5, 2)->default(0);
            $table->decimal('exam', 5, 2)->default(0);
            $table->decimal('total', 5, 2)->default(0);
            $table->string('grade', 2)->nullable();
            $table->string('remark')->nullable();
            $table->integer('position')->nullable();

            $table->text('form_teacher_comment')->nullable();
            $table->text('school_head_comment')->nullable();
            $table->string('form_teacher_signature')->nullable();
            $table->string('school_head_signature')->nullable();
            $table->json('psychomotor')->nullable();
            $table->json('affective')->nullable();
            $table->string('next_term_begins')->nullable();
            $table->string('next_term_fees')->nullable();
            $table->integer('attendance_present')->default(0);
            $table->integer('attendance_absent')->default(0);
            $table->integer('attendance_late')->default(0);

            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'mock_exam_id', 'branch_id'], 'unique_mock_result_subject');
            $table->index(['student_id', 'mock_exam_id']);
            $table->index(['school_class_id', 'mock_exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mock_results');
    }
};
