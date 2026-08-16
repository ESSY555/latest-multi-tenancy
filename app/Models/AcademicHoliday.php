<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'holiday_type',
        'is_public_holiday',
        'color'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_public_holiday' => 'boolean'
    ];

    // Holiday types
    const TYPE_BREAK = 'break';
    const TYPE_HOLIDAY = 'holiday';
    const TYPE_VACATION = 'vacation';
    const TYPE_SPECIAL = 'special';
    const TYPE_ACADEMIC = 'academic';

    // Relationships
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Scopes
    public function scopePublicHolidays($query)
    {
        return $query->where('is_public_holiday', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('holiday_type', $type);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeThisMonth($query)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        return $query->where('start_date', '<=', $endOfMonth)
                    ->where('end_date', '>=', $startOfMonth);
    }

    public function scopeNextMonth($query)
    {
        $nextMonth = now()->addMonth();
        $startOfMonth = $nextMonth->startOfMonth();
        $endOfMonth = $nextMonth->endOfMonth();
        return $query->where('start_date', '<=', $endOfMonth)
                    ->where('end_date', '>=', $startOfMonth);
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

    public function isToday(): bool
    {
        $today = now()->toDateString();
        return $this->start_date->toDateString() <= $today && 
               $this->end_date->toDateString() >= $today;
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getRemainingDays(): int
    {
        if ($this->isPast()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_date));
    }

    // Get holiday status
    public function getStatusAttribute(): string
    {
        if ($this->isPast()) {
            return 'completed';
        } elseif ($this->isCurrent()) {
            return 'ongoing';
        } elseif ($this->isToday()) {
            return 'today';
        } else {
            return 'upcoming';
        }
    }

    // Get holiday status color for UI
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

    // Get holiday type color for UI
    public function getTypeColorAttribute(): string
    {
        return match($this->holiday_type) {
            'break' => 'blue',
            'holiday' => 'red',
            'vacation' => 'green',
            'special' => 'purple',
            'academic' => 'orange',
            default => 'gray'
        };
    }

    // Check if today is a holiday
    public static function isTodayHoliday($branchId = null): bool
    {
        $query = static::query();
        
        if ($branchId) {
            $query->whereHas('academicYear', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->current()->exists();
    }

    // Get next holiday
    public static function getNextHoliday($branchId = null)
    {
        $query = static::query();
        
        if ($branchId) {
            $query->whereHas('academicYear', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->upcoming()
                    ->orderBy('start_date', 'asc')
                    ->first();
    }

    // Get holidays in date range
    public static function getHolidaysInRange($startDate, $endDate, $branchId = null)
    {
        $query = static::query()
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate);
        
        if ($branchId) {
            $query->whereHas('academicYear', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->orderBy('start_date', 'asc')->get();
    }

    // Get working days between two dates (excluding holidays)
    public static function getWorkingDays($startDate, $endDate, $branchId = null): int
    {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $holidays = static::getHolidaysInRange($startDate, $endDate, $branchId);
        
        $holidayDays = 0;
        foreach ($holidays as $holiday) {
            $holidayDays += $holiday->getDurationInDays();
        }
        
        return max(0, $totalDays - $holidayDays);
    }

    // Get formatted date range
    public function getDateRangeAttribute(): string
    {
        if ($this->start_date->equalTo($this->end_date)) {
            return $this->start_date->format('M d, Y');
        }
        
        return $this->start_date->format('M d') . ' - ' . $this->end_date->format('M d, Y');
    }

    // Get days until holiday starts
    public function getDaysUntilStart(): int
    {
        if ($this->isPast() || $this->isCurrent()) {
            return 0;
        }

        return now()->diffInDays($this->start_date);
    }

    // Get holiday reminder text
    public function getReminderText(): string
    {
        $days = $this->getDaysUntilStart();
        
        if ($days === 0) {
            return 'Starts today';
        } elseif ($days === 1) {
            return 'Starts tomorrow';
        } elseif ($days <= 7) {
            return "Starts in {$days} days";
        } elseif ($days <= 30) {
            $weeks = ceil($days / 7);
            return "Starts in {$weeks} weeks";
        } else {
            $months = ceil($days / 30);
            return "Starts in {$months} months";
        }
    }
}
