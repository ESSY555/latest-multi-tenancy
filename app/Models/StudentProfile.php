<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'academic_year_id',
        'admission_number',
        'grade_level',
        'gender',
        'date_of_birth',
        'guardian_name',
        'guardian_phone',
        'parent_name',
        'parent_phone',
        'parent_email',
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
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'has_health_challenges' => 'boolean',
    ];

    /**
     * Get the user that owns the student profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the age of the student.
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Get the formatted date of birth.
     */
    public function getFormattedDateOfBirthAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->format('M j, Y') : null;
    }

    /**
     * Get the academic year that the student was admitted in.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
