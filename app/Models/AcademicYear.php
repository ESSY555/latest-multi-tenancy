<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'start_date',
        'end_date',
        'is_active',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(AcademicTerm::class);
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(AcademicSemester::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(AcademicEvent::class);
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(AcademicHoliday::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(AcademicExam::class);
    }

    public function annualSummaries(): HasMany
    {
        return $this->hasMany(AnnualSummary::class);
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

    // Helper methods
    public function isCurrent(): bool
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
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

    // Get current term
    public function getCurrentTerm()
    {
        return $this->terms()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }

    // Get current semester
    public function getCurrentSemester()
    {
        return $this->semesters()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }

    // Get upcoming events
    public function getUpcomingEvents($limit = 10)
    {
        return $this->events()
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();
    }

    // Get upcoming holidays
    public function getUpcomingHolidays($limit = 10)
    {
        return $this->holidays()
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();
    }

    // Static method to get current academic year
    public static function getCurrentAcademicYear($branchId = null)
    {
        $query = self::where('is_active', true);
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        
        return $query->first();
    }

    // Static method to get current academic year name
    public static function getCurrentAcademicYearName($branchId = null)
    {
        $currentYear = self::getCurrentAcademicYear($branchId);
        return $currentYear ? $currentYear->name : null;
    }

    /**
     * Check if the academic year has reached the limit of 3 terms.
     */
    public function hasReachedTermLimit(): bool
    {
        return $this->terms()->count() >= 3;
    }

    /**
     * Mark this academic year (section) as ended.
     */
    public function endSession(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Get the term by its sequence number (1, 2, or 3).
     */
    public function getTermByNumber(int $number)
    {
        return $this->terms()->where('term_number', $number)->first();
    }
}
