<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make the assessment score columns nullable so that a field left empty
     * during entry is stored as NULL (not entered yet) instead of 0. This lets
     * teachers return later to fill in the still-empty fields, while any field
     * that already holds a value stays locked.
     */
    public function up(): void
    {
        if (Schema::hasTable('results')) {
            Schema::table('results', function (Blueprint $table) {
                $table->decimal('ca1', 5, 2)->nullable()->default(null)->change();
                $table->decimal('ca2', 5, 2)->nullable()->default(null)->change();
                $table->decimal('ca3', 5, 2)->nullable()->default(null)->change();
                $table->decimal('exam', 5, 2)->nullable()->default(null)->change();
            });
        }

        if (Schema::hasTable('mock_results')) {
            Schema::table('mock_results', function (Blueprint $table) {
                $table->decimal('ca1', 5, 2)->nullable()->default(null)->change();
                $table->decimal('ca2', 5, 2)->nullable()->default(null)->change();
                $table->decimal('ca3', 5, 2)->nullable()->default(null)->change();
                $table->decimal('exam', 5, 2)->nullable()->default(null)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('results')) {
            Schema::table('results', function (Blueprint $table) {
                $table->decimal('ca1', 5, 2)->default(0)->change();
                $table->decimal('ca2', 5, 2)->default(0)->change();
                $table->decimal('ca3', 5, 2)->default(0)->change();
                $table->decimal('exam', 5, 2)->default(0)->change();
            });
        }

        if (Schema::hasTable('mock_results')) {
            Schema::table('mock_results', function (Blueprint $table) {
                $table->decimal('ca1', 5, 2)->default(0)->change();
                $table->decimal('ca2', 5, 2)->default(0)->change();
                $table->decimal('ca3', 5, 2)->default(0)->change();
                $table->decimal('exam', 5, 2)->default(0)->change();
            });
        }
    }
};
