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
        // Update existing teacher reports from 'submitted' to 'pending' status
        DB::table('teacher_reports')
            ->where('status', 'submitted')
            ->update(['status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back from 'pending' to 'submitted' status
        DB::table('teacher_reports')
            ->where('status', 'pending')
            ->update(['status' => 'submitted']);
    }
};
