<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'student_id',
        'subject_id',
        'term_id',
        'branch_id',
        'ca1',
        'ca2',
        'ca3',
        'exam',
        'total',
        'grade',
        'remark',
        'position',
        'class_highest',
        'class_lowest',
        'class_average',
        'is_approved',
        'approved_by',
        'approved_at',
        'attendance_present',
        'attendance_absent',
        'attendance_late',
        'form_teacher_comment',
        'school_head_comment',
        'psychomotor',
        'affective',
        'form_teacher_signature',
        'school_head_signature',
        'next_term_begins',
        'next_term_fees',
    ];

    protected $casts = [
        'psychomotor' => 'array',
        'affective' => 'array',
        'is_approved' => 'boolean',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Query scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // Get grade points for GPA calculation
    public function getGradePointsAttribute()
    {
        $grade = strtoupper($this->grade ?? 'F');
        return match($grade) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0
        };
    }
}


