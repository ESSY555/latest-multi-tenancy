<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'description',
        'semester_number',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'semester_number' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(AcademicTerm::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(AcademicExam::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeByNumber($query, $number)
    {
        return $query->where('semester_number', $number);
    }

    // Helper methods
    public function isCurrent(): bool
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    public function isPast(): bool
    {
        return $this->end_date->isPast();
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getProgressPercentage(): float
    {
        if (!$this->isCurrent()) {
            return 0.0;
        }

        $totalDays = $this->getDurationInDays();
        $elapsedDays = $this->start_date->diffInDays(now());
        
        return min(100.0, max(0.0, round(($elapsedDays / $totalDays) * 100, 2)));
    }

    public function getRemainingDays(): int
    {
        if ($this->isPast()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_date));
    }

    // Get semester status
    public function getStatusAttribute(): string
    {
        if ($this->isPast()) {
            return 'completed';
        } elseif ($this->isCurrent()) {
            return 'active';
        } else {
            return 'upcoming';
        }
    }

    // Get semester status color for UI
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'gray',
            'active' => 'green',
            'upcoming' => 'blue',
            default => 'gray'
        };
    }

    // Get current term in this semester
    public function getCurrentTerm()
    {
        return $this->terms()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }

    // Get upcoming terms in this semester
    public function getUpcomingTerms($limit = 5)
    {
        return $this->terms()
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();
    }

    // Get completed terms in this semester
    public function getCompletedTerms()
    {
        return $this->terms()
            ->where('end_date', '<', now())
            ->orderBy('end_date', 'desc')
            ->get();
    }

    // Check if semester has exams
    public function hasExams(): bool
    {
        return $this->exams()->exists();
    }

    // Get upcoming exams in this semester
    public function getUpcomingExams($limit = 5)
    {
        return $this->exams()
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date', 'asc')
            ->limit($limit)
            ->get();
    }

    // Get semester GPA for a student
    public function getStudentGPA($studentId): float
    {
        $results = $this->results()
            ->where('student_id', $studentId)
            ->get();

        if ($results->isEmpty()) {
            return 0.0;
        }

        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($results as $result) {
            $grade = strtoupper($result->grade ?? 'F');
            $points = $this->getGradePoints($grade);
            $credits = 1; // Assuming 1 credit per subject

            $totalPoints += ($points * $credits);
            $totalCredits += $credits;
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }

    private function getGradePoints($grade): float
    {
        return match($grade) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0
        };
    }
}
