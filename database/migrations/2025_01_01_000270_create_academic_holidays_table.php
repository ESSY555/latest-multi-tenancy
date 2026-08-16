<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('holiday_type', [
                'break', 'holiday', 'vacation', 'special', 'academic'
            ])->default('holiday');
            $table->boolean('is_public_holiday')->default(false);
            $table->string('color')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['academic_year_id', 'holiday_type']);
            $table->index(['start_date', 'end_date']);
            $table->index('is_public_holiday');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_holidays');
    }
};
