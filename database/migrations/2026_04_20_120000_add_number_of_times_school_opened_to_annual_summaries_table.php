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
        Schema::table('annual_summaries', function (Blueprint $table) {
            $table->unsignedInteger('number_of_times_school_opened')->nullable()->after('academic_year_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annual_summaries', function (Blueprint $table) {
            $table->dropColumn('number_of_times_school_opened');
        });
    }
};
