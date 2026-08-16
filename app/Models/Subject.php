<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = ['branch_id', 'name', 'code'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teacher_subjects', 'subject_id', 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_subjects', 'subject_id', 'student_id')
            ->withPivot('school_class_id', 'academic_year', 'status')
            ->withTimestamps();
    }
}


