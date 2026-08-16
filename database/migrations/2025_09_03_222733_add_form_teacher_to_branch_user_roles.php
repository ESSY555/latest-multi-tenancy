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
        // Add 'form_teacher' to the role enum
        DB::statement("ALTER TABLE branch_user MODIFY COLUMN role ENUM('admin', 'teacher', 'student', 'parent', 'form_teacher')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'form_teacher' from the role enum
        DB::statement("ALTER TABLE branch_user MODIFY COLUMN role ENUM('admin', 'teacher', 'student', 'parent')");
    }
};
