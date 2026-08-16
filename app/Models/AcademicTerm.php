<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'name',
        'principal_signature',
        'term_number',
        'start_date',
        'end_date',
        'description',
        'is_exam_term',
        'is_break_term'
    ];

    protected $casts = [
        'term_number' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_exam_term' => 'boolean',
        'is_break_term' => 'boolean'
    ];

    // Relationships
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
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
        return $this->hasMany(\App\Models\Result\Result::class);
    }

    // Scopes
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

    public function scopeExamTerms($query)
    {
        return $query->where('is_exam_term', true);
    }

    public function scopeBreakTerms($query)
    {
        return $query->where('is_break_term', true);
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

    // Get term status
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

    // Get term status color for UI
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'gray',
            'active' => 'green',
            'upcoming' => 'blue',
            default => 'gray'
        };
    }

    // Check if term has exams
    public function hasExams(): bool
    {
        return $this->exams()->exists();
    }

    // Get upcoming exams in this term
    public function getUpcomingExams($limit = 5)
    {
        return $this->exams()
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if this is the third (last) term of the academic year.
     */
    public function isLastTerm(): bool
    {
        return $this->term_number === 3;
    }

    /**
     * Check if this term should trigger an annual report.
     */
    public function shouldHaveAnnualReport(): bool
    {
        return $this->isLastTerm();
    }
}
