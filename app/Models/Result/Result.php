<?php

namespace App\Models\Result;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicTerm;
use App\Models\Branch;

class Result extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_class_id',
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
        'form_teacher_comment',
        'school_head_comment',
        'form_teacher_signature',
        'school_head_signature',
        'next_term_begins',
        'next_term_fees',
        'attendance_present',
        'attendance_absent',
        'attendance_late',
        'psychomotor',
        'affective',
    ];

    protected $casts = [
        'ca1' => 'decimal:2',
        'ca2' => 'decimal:2',
        'ca3' => 'decimal:2',
        'exam' => 'decimal:2',
        'total' => 'decimal:2',
        'class_highest' => 'decimal:2',
        'class_lowest' => 'decimal:2',
        'class_average' => 'decimal:2',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'attendance_present' => 'integer',
        'attendance_absent' => 'integer',
        'attendance_late' => 'integer',
        'psychomotor' => 'array',
        'affective' => 'array',
    ];

    /**
     * Get the student that owns the result.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the class for this result.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /**
     * Get the subject for this result.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the academic term for this result.
     */
    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    /**
     * Get the branch for this result.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who approved this result.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate total score from CA and Exam
     */
    public function calculateTotal()
    {
        return ($this->ca1 ?? 0) + ($this->ca2 ?? 0) + ($this->ca3 ?? 0) + ($this->exam ?? 0);
    }

    /**
     * Resolve the grading class type from a class name.
     * Returns 'ss', 'jss', or 'default'.
     */
    public static function resolveClassType(?string $className): string
    {
        if (!$className) return 'default';
        $name = trim($className);
        // Check JS before SS so 'JS1' doesn't accidentally match 'SS'
        if (stripos($name, 'JS') === 0) return 'js';
        if (stripos($name, 'SS') === 0) return 'ss';
        return 'default';
    }

    /**
     * Check if a class name belongs to Senior Secondary (SS).
     * Kept for backward-compatibility with existing code.
     */
    public static function isSeniorSecondaryClass(?string $className): bool
    {
        return self::resolveClassType($className) === 'ss';
    }

    /**
     * Check if a class name belongs to Junior Secondary (JS).
     */
    public static function isJuniorSecondaryClass(?string $className): bool
    {
        return self::resolveClassType($className) === 'js';
    }

    /**
     * Determine grade based on total score and class type.
     *
     * SS  (Senior Secondary): A1 B2 B3 C4 C5 C6 D7 E8 F9
     * JSS (Junior Secondary): A1 C  P  F
     * Default:                A  B  C  D  F
     */
    public function determineGrade($total, string $classType = 'default')
    {
        switch ($classType) {
            case 'ss':
                if ($total >= 80) return 'A1';
                if ($total >= 75) return 'B2';
                if ($total >= 70) return 'B3';
                if ($total >= 65) return 'C4';
                if ($total >= 60) return 'C5';
                if ($total >= 55) return 'C6';
                if ($total >= 50) return 'D7';
                if ($total >= 45) return 'E8';
                return 'F9';

            case 'js':
                if ($total >= 80) return 'A1';
                if ($total >= 55) return 'C';
                if ($total >= 45) return 'P';
                return 'F';

            default:
                if ($total >= 70) return 'A';
                if ($total >= 60) return 'B';
                if ($total >= 50) return 'C';
                if ($total >= 40) return 'D';
                return 'F';
        }
    }

    /**
     * Determine remark based on grade.
     * Handles all grading systems: default (A–F), SS (A1–F9), JSS (A1/C/P/F).
     */
    public function determineRemark($grade)
    {
        switch ($grade) {
            case 'A':
            case 'A1':
                return 'Excellent';
            case 'B':
            case 'B2':
                return 'Very Good';
            case 'B3':
                return 'Good';
            case 'C':
            case 'C4':
            case 'C5':
            case 'C6':
                return 'Credit';
            case 'D':
            case 'D7':
            case 'E8':
            case 'P':
                return 'Pass';
            case 'F':
            case 'F9':
                return 'Fail';
            default:
                return 'Unknown';
        }
    }

    /**
     * Boot method to automatically calculate totals and grades
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($result) {
            // Calculate total
            $result->total = $result->calculateTotal();

            // Resolve class type (ss / jss / default)
            $className = null;
            if ($result->school_class_id) {
                $className = \App\Models\SchoolClass::where('id', $result->school_class_id)
                    ->value('name');
            }
            $classType = self::resolveClassType($className);

            // Determine grade and remark
            $result->grade  = $result->determineGrade($result->total, $classType);
            $result->remark = $result->determineRemark($result->grade);
        });
    }

    /**
     * Scope for approved results
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending results
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope for results by term
     */
    public function scopeByTerm($query, $termId)
    {
        return $query->where('term_id', $termId);
    }

    /**
     * Scope for results by class
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }

    /**
     * Scope for results by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Get attendance percentage
     */
    public function getAttendancePercentageAttribute()
    {
        $total = $this->attendance_present + $this->attendance_absent + $this->attendance_late;
        if ($total == 0) return 0;
        
        return round(($this->attendance_present / $total) * 100, 2);
    }

    /**
     * Get overall performance status
     */
    public function getPerformanceStatusAttribute()
    {
        if ($this->total >= 70) {
            return 'Excellent';
        } elseif ($this->total >= 60) {
            return 'Good';
        } elseif ($this->total >= 50) {
            return 'Credit';
        } elseif ($this->total >= 40) {
            return 'Pass';
        } else {
            return 'Fail';
        }
    }
}
