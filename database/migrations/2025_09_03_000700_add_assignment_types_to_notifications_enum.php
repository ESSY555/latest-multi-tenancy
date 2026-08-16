<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend notifications.type enum to include assignment_publish/submission/review
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('assignment', 'result', 'attendance', 'announcement', 'system', 'lesson_plan', 'teacher_report', 'assignment_publish', 'assignment_submission', 'assignment_review')");
    }

    public function down(): void
    {
        // Revert to previous set without the three new assignment types
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('assignment', 'result', 'attendance', 'announcement', 'system', 'lesson_plan', 'teacher_report')");
    }
};


