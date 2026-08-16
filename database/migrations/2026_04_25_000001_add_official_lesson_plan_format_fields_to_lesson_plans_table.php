<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('lesson_plans', 'school_name')) {
                $table->string('school_name')->nullable()->after('class_id');
            }
            if (!Schema::hasColumn('lesson_plans', 'term_name')) {
                $table->string('term_name')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'week_name')) {
                $table->string('week_name')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'theme')) {
                $table->string('theme')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'subtopic')) {
                $table->string('subtopic')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'periods')) {
                $table->string('periods')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'time_slot')) {
                $table->string('time_slot')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'class_size')) {
                $table->string('class_size')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'average_age')) {
                $table->string('average_age')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'sex_label')) {
                $table->string('sex_label')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'rationale')) {
                $table->text('rationale')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'previous_knowledge')) {
                $table->text('previous_knowledge')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'reference_books')) {
                $table->text('reference_books')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'learning_aids')) {
                $table->text('learning_aids')->nullable();
            }
            if (!Schema::hasColumn('lesson_plans', 'lesson_note')) {
                $table->longText('lesson_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            $cols = [
                'school_name', 'term_name', 'week_name', 'theme', 'subtopic', 'periods', 'time_slot',
                'class_size', 'average_age', 'sex_label', 'rationale', 'previous_knowledge',
                'reference_books', 'learning_aids', 'lesson_note',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('lesson_plans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
