<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_summaries', function (Blueprint $table) {
            if (!Schema::hasColumn('annual_summaries', 'pass_status')) {
                $table->string('pass_status')->nullable()->after('promotion_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('annual_summaries', function (Blueprint $table) {
            if (Schema::hasColumn('annual_summaries', 'pass_status')) {
                $table->dropColumn('pass_status');
            }
        });
    }
};
