<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'branch_id', 'name', 'grade_level', 'academic_year',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_teacher', 'school_class_id', 'teacher_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_subject');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function formTeacher()
    {
        return $this->hasOne(FormTeacher::class)->where('is_active', true);
    }

    public function formTeachers()
    {
        return $this->hasMany(FormTeacher::class);
    }
}


