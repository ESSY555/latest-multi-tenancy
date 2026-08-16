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
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            
            // Student Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('current_grade')->nullable();
            
            // Parent/Guardian Information
            $table->string('primary_contact_name');
            $table->string('relationship');
            $table->string('phone_number');
            $table->string('email');
            $table->text('address');
            
            // Additional Information
            $table->string('hear_about_school')->nullable();
            $table->text('additional_info')->nullable();
            
            // Application Status
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};
