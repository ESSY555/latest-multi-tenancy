<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AcademicEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'event_type',
        'location',
        'is_all_day',
        'is_public',
        'color',
        'priority'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_all_day' => 'boolean',
        'is_public' => 'boolean'
    ];

    // Event types
    const TYPE_EXAM = 'exam';
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_MEETING = 'meeting';
    const TYPE_CEREMONY = 'ceremony';
    const TYPE_SPORTS = 'sports';
    const TYPE_CULTURAL = 'cultural';
    const TYPE_ACADEMIC = 'academic';
    const TYPE_ADMINISTRATIVE = 'administrative';
    const TYPE_OTHER = 'other';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Relationships
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'academic_event_class');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'academic_event_subject');
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeToday($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    public function scopeThisWeek($query)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        return $query->where('start_date', '<=', $endOfWeek)
                    ->where('end_date', '>=', $startOfWeek);
    }

    public function scopeThisMonth($query)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        return $query->where('start_date', '<=', $endOfMonth)
                    ->where('end_date', '>=', $startOfMonth);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Helper methods
    public function isToday(): bool
    {
        $today = now()->toDateString();
        return $this->start_date->toDateString() <= $today && 
               $this->end_date->toDateString() >= $today;
    }

    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    public function isPast(): bool
    {
        return $this->end_date->isPast();
    }

    public function isOngoing(): bool
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getDurationInHours(): float
    {
        if ($this->is_all_day) {
            return $this->getDurationInDays() * 24;
        }

        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInHours($this->end_time);
        }

        return 0;
    }

    // Get event status
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

    // Get event status color for UI
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

    // Get priority color for UI
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }

    // Check if event conflicts with another event
    public function conflictsWith(AcademicEvent $otherEvent): bool
    {
        return $this->start_date <= $otherEvent->end_date && 
               $this->end_date >= $otherEvent->start_date;
    }

    // Get formatted time range
    public function getTimeRangeAttribute(): string
    {
        if ($this->is_all_day) {
            return 'All Day';
        }

        if ($this->start_time && $this->end_time) {
            return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
        }

        return 'No time specified';
    }

    // Get days until event
    public function getDaysUntilEvent(): int
    {
        if ($this->isPast() || $this->isToday()) {
            return 0;
        }

        return now()->diffInDays($this->start_date);
    }

    // Get event reminder text
    public function getReminderText(): string
    {
        $days = $this->getDaysUntilEvent();
        
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
}
