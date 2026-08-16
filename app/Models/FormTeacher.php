<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormTeacher extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_class_id',
        'branch_id',
        'is_active',
        'start_date',
        'end_date',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function students()
    {
        return $this->schoolClass->enrollments()->with('student');
    }

    public function remarks()
    {
        return $this->hasMany(StudentRemark::class, 'form_teacher_id', 'user_id');
    }

    public function announcements()
    {
        return $this->hasMany(ClassAnnouncement::class, 'form_teacher_id', 'user_id');
    }
}
