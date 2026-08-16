<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_exams', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                ->nullable()
                ->after('school_class_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('academic_exams', function (Blueprint $table) {
            $table->dropIndex(['teacher_id']);
            $table->dropConstrainedForeignId('teacher_id');
        });
    }
};
