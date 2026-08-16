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
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'psychomotor')) {
                $table->json('psychomotor')->nullable();
            }
            if (!Schema::hasColumn('results', 'affective')) {
                $table->json('affective')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $columns = array_filter(['psychomotor', 'affective'], fn ($column) => Schema::hasColumn('results', $column));
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
