<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockResult extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_class_id',
        'subject_id',
        'branch_id',
        'academic_year_id',
        'mock_exam_id',
        'ca1',
        'ca2',
        'ca3',
        'exam',
        'total',
        'grade',
        'remark',
        'position',
        'form_teacher_comment',
        'school_head_comment',
        'form_teacher_signature',
        'school_head_signature',
        'psychomotor',
        'affective',
        'next_term_begins',
        'next_term_fees',
        'attendance_present',
        'attendance_absent',
        'attendance_late',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'ca1' => 'decimal:2',
        'ca2' => 'decimal:2',
        'ca3' => 'decimal:2',
        'exam' => 'decimal:2',
        'total' => 'decimal:2',
        'psychomotor' => 'array',
        'affective' => 'array',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function mockExam(): BelongsTo
    {
        return $this->belongsTo(MockExam::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($result) {
            $result->total = ($result->ca1 ?? 0) + ($result->ca2 ?? 0) + ($result->ca3 ?? 0) + ($result->exam ?? 0);
            $result->grade = self::determineGrade($result->total);
            $result->remark = self::determineRemark($result->grade);
        });
    }

    private static function determineGrade(float $total): string
    {
        if ($total >= 70) {
            return 'A';
        }
        if ($total >= 60) {
            return 'B';
        }
        if ($total >= 50) {
            return 'C';
        }
        if ($total >= 45) {
            return 'D';
        }

        return 'F';
    }

    private static function determineRemark(string $grade): string
    {
        return match ($grade) {
            'A' => 'Excellent',
            'B' => 'Very Good',
            'C' => 'Good',
            'D' => 'Pass',
            default => 'Fail',
        };
    }
}
