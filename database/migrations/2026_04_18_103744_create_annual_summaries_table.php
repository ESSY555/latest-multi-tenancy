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
        Schema::create('annual_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->text('form_teacher_comment')->nullable();
            $table->text('school_head_comment')->nullable();
            $table->string('form_teacher_signature')->nullable();
            $table->string('school_head_signature')->nullable();
            $table->string('promotion_status')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_summaries');
    }
};
