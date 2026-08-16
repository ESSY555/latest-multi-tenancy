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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('parent_email');
            $table->string('state_of_origin')->nullable()->after('nationality');
            $table->string('local_government_area')->nullable()->after('state_of_origin');
            $table->string('religion')->nullable()->after('local_government_area');
            $table->string('church_denomination')->nullable()->after('religion');
            $table->text('residential_address')->nullable()->after('church_denomination');
            $table->string('language_of_communication')->nullable()->after('residential_address');
            $table->unsignedInteger('number_of_children_in_family')->nullable()->after('language_of_communication');
            $table->unsignedInteger('position_in_family')->nullable()->after('number_of_children_in_family');
            $table->string('school_last_attended')->nullable()->after('position_in_family');
            $table->string('class_last_attended')->nullable()->after('school_last_attended');
            $table->boolean('has_health_challenges')->default(false)->after('class_last_attended');
            $table->text('health_challenges_details')->nullable()->after('has_health_challenges');
            $table->string('crisis_response')->nullable()->after('health_challenges_details');
            $table->string('father_name')->nullable()->after('crisis_response');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->text('father_residential_address')->nullable()->after('mother_name');
            $table->text('mother_residential_address')->nullable()->after('father_residential_address');
            $table->string('father_occupation')->nullable()->after('mother_residential_address');
            $table->string('mother_occupation')->nullable()->after('father_occupation');
            $table->text('father_office_address')->nullable()->after('mother_occupation');
            $table->text('mother_office_address')->nullable()->after('father_office_address');
            $table->string('father_phone_number')->nullable()->after('mother_office_address');
            $table->string('mother_phone_number')->nullable()->after('father_phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nationality',
                'state_of_origin',
                'local_government_area',
                'religion',
                'church_denomination',
                'residential_address',
                'language_of_communication',
                'number_of_children_in_family',
                'position_in_family',
                'school_last_attended',
                'class_last_attended',
                'has_health_challenges',
                'health_challenges_details',
                'crisis_response',
                'father_name',
                'mother_name',
                'father_residential_address',
                'mother_residential_address',
                'father_occupation',
                'mother_occupation',
                'father_office_address',
                'mother_office_address',
                'father_phone_number',
                'mother_phone_number',
            ]);
        });
    }
};
