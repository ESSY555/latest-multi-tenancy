<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('assignment_submissions', 'can_resubmit')) {
                $table->boolean('can_resubmit')->default(false)->after('remarks');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('assignment_submissions', 'can_resubmit')) {
                $table->dropColumn('can_resubmit');
            }
        });
    }
};


