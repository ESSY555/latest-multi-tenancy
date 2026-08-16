<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MockExam;

class AcademicExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'academic_term_id',
        'mock_exam_id',
        'exam_scope',
        'teacher_id',
        'academic_semester_id',
        'title',
        'description',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'subject_id',
        'school_class_id',
        'is_published',
        'is_online',
        'location',
        'instructions',
        'color'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
        'exam_scope' => 'string',
        'is_published' => 'boolean',
        'is_online' => 'boolean'
    ];

    // Exam types
    const TYPE_MIDTERM = 'midterm';
    const TYPE_FINAL = 'final';
    const TYPE_QUIZ = 'quiz';
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_PROJECT = 'project';
    const TYPE_PRESENTATION = 'presentation';
    const TYPE_PRACTICAL = 'practical';
    const TYPE_ORAL = 'oral';
    const TYPE_WRITTEN = 'written';
    const TYPE_OTHER = 'other';

    // Relationships
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function mockExam(): BelongsTo
    {
        return $this->belongsTo(MockExam::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicSemester(): BelongsTo
    {
        return $this->belongsTo(AcademicSemester::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'academic_exam_student', 'exam_id', 'student_id')
                    ->withPivot('score', 'grade', 'status', 'submitted_at')
                    ->withTimestamps();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('exam_type', $type);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now());
    }

    public function scopeToday($query)
    {
        $today = now()->toDateString();
        return $query->where('exam_date', $today);
    }

    public function scopeThisWeek($query)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        return $query->whereBetween('exam_date', [$startOfWeek, $endOfWeek]);
    }

    public function scopeThisMonth($query)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        return $query->whereBetween('exam_date', [$startOfMonth, $endOfMonth]);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }

    // Helper methods
    public function isToday(): bool
    {
        return $this->exam_date->isToday();
    }

    public function isUpcoming(): bool
    {
        return $this->exam_date->isFuture();
    }

    public function isPast(): bool
    {
        return $this->exam_date->isPast();
    }

    public function isOngoing(): bool
    {
        if (!$this->start_time || !$this->end_time) {
            return false;
        }

        $now = now();
        return $now->between($this->start_time, $this->end_time);
    }

    public function getDurationInHours(): float
    {
        return $this->duration_minutes / 60;
    }

    public function getRemainingDays(): int
    {
        if ($this->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->exam_date);
    }

    // Get exam status
    public function getStatusAttribute(): string
    {
        if ($this->isPast()) {
            return 'completed';
        } elseif ($this->isOngoing()) {
            return 'ongoing';
        } elseif ($this->isToday()) {
            return 'today';
        } else {
            return 'upcoming';
        }
    }

    // Get exam status color for UI
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'gray',
            'ongoing' => 'green',
            'today' => 'blue',
            'upcoming' => 'yellow',
            default => 'gray'
        };
    }

    // Get exam type color for UI
    public function getTypeColorAttribute(): string
    {
        return match($this->exam_type) {
            'midterm' => 'blue',
            'final' => 'red',
            'quiz' => 'green',
            'assignment' => 'purple',
            'project' => 'orange',
            'presentation' => 'indigo',
            'practical' => 'pink',
            'oral' => 'teal',
            'written' => 'gray',
            default => 'gray'
        };
    }

    // Check if exam is published
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    // Check if exam is online
    public function isOnline(): bool
    {
        return $this->is_online;
    }

    // Get formatted time range
    public function getTimeRangeAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return 'Time not specified';
        }

        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    // Get formatted duration
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_minutes < 60) {
            return $this->duration_minutes . ' minutes';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($minutes === 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        }

        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    }

    // Get days until exam
    public function getDaysUntilExam(): int
    {
        if ($this->isPast() || $this->isToday()) {
            return 0;
        }

        return now()->diffInDays($this->exam_date);
    }

    // Get exam reminder text
    public function getReminderText(): string
    {
        $days = $this->getDaysUntilExam();
        
        if ($days === 0) {
            return 'Today';
        } elseif ($days === 1) {
            return 'Tomorrow';
        } elseif ($days <= 7) {
            return "In {$days} days";
        } elseif ($days <= 30) {
            $weeks = ceil($days / 7);
            return "In {$weeks} weeks";
        } else {
            $months = ceil($days / 30);
            return "In {$months} months";
        }
    }

    // Get student count
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }

    // Get average score
    public function getAverageScoreAttribute(): float
    {
        $scores = $this->students()->pluck('pivot.score')->filter();
        
        if ($scores->isEmpty()) {
            return 0.0;
        }

        return round($scores->avg(), 2);
    }

    // Get pass rate
    public function getPassRateAttribute(): float
    {
        $totalStudents = $this->students()->count();
        
        if ($totalStudents === 0) {
            return 0.0;
        }

        $passingStudents = $this->students()
            ->wherePivot('score', '>=', $this->passing_marks)
            ->count();

        return round(($passingStudents / $totalStudents) * 100, 2);
    }

    // Check if student is registered for this exam
    public function isStudentRegistered($studentId): bool
    {
        return $this->students()->where('id', $studentId)->exists();
    }

    // Get student's exam result
    public function getStudentResult($studentId)
    {
        return $this->students()
            ->where('id', $studentId)
            ->first();
    }
}
