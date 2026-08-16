<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_exams', function (Blueprint $table) {
            $table->enum('exam_scope', ['term', 'mock'])
                ->default('term')
                ->after('academic_term_id');
            $table->foreignId('mock_exam_id')
                ->nullable()
                ->after('exam_scope')
                ->constrained('mock_exams')
                ->nullOnDelete();
            $table->index(['academic_year_id', 'exam_scope']);
        });
    }

    public function down(): void
    {
        Schema::table('academic_exams', function (Blueprint $table) {
            $table->dropIndex(['academic_year_id', 'exam_scope']);
            $table->dropConstrainedForeignId('mock_exam_id');
            $table->dropColumn('exam_scope');
        });
    }
};
