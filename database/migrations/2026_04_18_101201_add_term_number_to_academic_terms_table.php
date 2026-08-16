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
        Schema::table('academic_terms', function (Blueprint $table) {
            $table->integer('term_number')->nullable()->after('name')->comment('Term order: 1 for First Term, 2 for Second Term, 3 for Third Term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_terms', function (Blueprint $table) {
            $table->dropColumn('term_number');
        });
    }
};
