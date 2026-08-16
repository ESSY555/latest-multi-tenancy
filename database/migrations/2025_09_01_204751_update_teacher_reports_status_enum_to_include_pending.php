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
        // First, temporarily change the enum to allow both 'submitted' and 'pending'
        DB::statement("ALTER TABLE teacher_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'pending', 'approved', 'rejected')");
        
        // Update existing 'submitted' records to 'pending'
        DB::table('teacher_reports')
            ->where('status', 'submitted')
            ->update(['status' => 'pending']);
        
        // Now remove 'submitted' from the enum
        DB::statement("ALTER TABLE teacher_reports MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, add 'submitted' back to the enum
        DB::statement("ALTER TABLE teacher_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'pending', 'approved', 'rejected')");
        
        // Update 'pending' records back to 'submitted'
        DB::table('teacher_reports')
            ->where('status', 'pending')
            ->update(['status' => 'submitted']);
        
        // Remove 'pending' from the enum
        DB::statement("ALTER TABLE teacher_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved', 'rejected')");
    }
};
