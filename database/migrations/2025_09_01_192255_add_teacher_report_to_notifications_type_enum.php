<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'teacher_report' to the enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('assignment', 'result', 'attendance', 'announcement', 'system', 'lesson_plan', 'teacher_report')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'teacher_report' from the enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('assignment', 'result', 'attendance', 'announcement', 'system', 'lesson_plan')");
    }
};
