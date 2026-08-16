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
            if (!Schema::hasColumn('student_profiles', 'grade_level')) {
                $table->string('grade_level')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'gender')) {
                $table->string('gender')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'parent_name')) {
                $table->string('parent_name')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'parent_phone')) {
                $table->string('parent_phone')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'parent_email')) {
                $table->string('parent_email')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'nationality')) {
                $table->string('nationality')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'state_of_origin')) {
                $table->string('state_of_origin')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'local_government_area')) {
                $table->string('local_government_area')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'religion')) {
                $table->string('religion')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'church_denomination')) {
                $table->string('church_denomination')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'residential_address')) {
                $table->text('residential_address')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'language_of_communication')) {
                $table->string('language_of_communication')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'number_of_children_in_family')) {
                $table->unsignedInteger('number_of_children_in_family')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'position_in_family')) {
                $table->unsignedInteger('position_in_family')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'school_last_attended')) {
                $table->string('school_last_attended')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'class_last_attended')) {
                $table->string('class_last_attended')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'has_health_challenges')) {
                $table->boolean('has_health_challenges')->default(false);
            }

            if (!Schema::hasColumn('student_profiles', 'health_challenges_details')) {
                $table->text('health_challenges_details')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'crisis_response')) {
                $table->string('crisis_response')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'father_name')) {
                $table->string('father_name')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'mother_name')) {
                $table->string('mother_name')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'father_residential_address')) {
                $table->text('father_residential_address')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'mother_residential_address')) {
                $table->text('mother_residential_address')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'father_occupation')) {
                $table->string('father_occupation')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'mother_occupation')) {
                $table->string('mother_occupation')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'father_office_address')) {
                $table->text('father_office_address')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'mother_office_address')) {
                $table->text('mother_office_address')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'father_phone_number')) {
                $table->string('father_phone_number')->nullable();
            }

            if (!Schema::hasColumn('student_profiles', 'mother_phone_number')) {
                $table->string('mother_phone_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally no-op to avoid dropping potentially in-use columns.
    }
};
