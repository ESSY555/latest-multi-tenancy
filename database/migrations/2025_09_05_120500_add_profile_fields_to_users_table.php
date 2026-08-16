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
        Schema::table('users', function (Blueprint $table) {
            // Profile information (phone and address already exist)
            $table->text('bio')->nullable()->after('address');
            $table->string('profile_photo')->nullable()->after('bio');
            
            // Emergency contact information
            $table->string('emergency_contact_name')->nullable()->after('profile_photo');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            
            // Notification preferences
            $table->boolean('email_notifications')->default(true)->after('emergency_contact_relationship');
            $table->boolean('assignment_reminders')->default(true)->after('email_notifications');
            $table->boolean('grade_notifications')->default(true)->after('assignment_reminders');
            $table->boolean('announcement_notifications')->default(true)->after('grade_notifications');
            $table->boolean('attendance_alerts')->default(true)->after('announcement_notifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'profile_photo',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'email_notifications',
                'assignment_reminders',
                'grade_notifications',
                'announcement_notifications',
                'attendance_alerts'
            ]);
        });
    }
};
