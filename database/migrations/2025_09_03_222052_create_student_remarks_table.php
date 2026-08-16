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
        Schema::create('student_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('form_teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['academic', 'behavioral', 'general']);
            $table->string('title');
            $table->text('content');
            $table->enum('severity', ['positive', 'neutral', 'concern', 'serious'])->default('neutral');
            $table->boolean('is_private')->default(false);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_remarks');
    }
};
