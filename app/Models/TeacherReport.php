<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TeacherReport extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'teacher_name',
        'report_date',
        'classes_taught',
        'subjects_taught',
        'topics_covered',
        'teaching_method',
        'objectives_achieved',
        'objectives_notes',
        'student_participation',
        'participation_notes',
        'homework_assigned',
        'homework_details',
        'class_activities',
        'challenges_faced',
        'materials_needed',
        'additional_notes',
        'teacher_id',
        'branch_id',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'report_date' => 'date',
        'classes_taught' => 'array',
        'subjects_taught' => 'array',
        'objectives_achieved' => 'boolean',
        'homework_assigned' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Draft</span>',
            'pending' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending Review</span>',
            'approved' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Approved</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rejected</span>',
            default => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unknown</span>'
        };
    }

    public function getFormattedReportDateAttribute(): string
    {
        return $this->report_date->format('M d, Y');
    }

    public function getFormattedSubmittedAtAttribute(): string
    {
        return $this->submitted_at ? $this->submitted_at->format('M d, Y \a\t g:i A') : 'Not submitted';
    }

    public function getFormattedReviewedAtAttribute(): string
    {
        return $this->reviewed_at ? $this->reviewed_at->format('M d, Y \a\t g:i A') : 'Not reviewed';
    }

    public function getTeachingMethodLabelAttribute(): string
    {
        return match($this->teaching_method) {
            'lecture' => 'Lecture',
            'group_work' => 'Group Work',
            'practical' => 'Practical',
            'discussion' => 'Discussion',
            'demonstration' => 'Demonstration',
            'project_based' => 'Project Based',
            'blended' => 'Blended Learning',
            'other' => 'Other',
            default => 'Not specified'
        };
    }

    public function getStudentParticipationLabelAttribute(): string
    {
        return match($this->student_participation) {
            'excellent' => 'Excellent',
            'good' => 'Good',
            'average' => 'Average',
            'poor' => 'Poor',
            'very_poor' => 'Very Poor',
            default => 'Not rated'
        };
    }

    public function getStudentParticipationColorAttribute(): string
    {
        return match($this->student_participation) {
            'excellent' => 'text-green-600 bg-green-100',
            'good' => 'text-blue-600 bg-blue-100',
            'average' => 'text-yellow-600 bg-yellow-100',
            'poor' => 'text-orange-600 bg-orange-100',
            'very_poor' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    // Scopes
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    // Methods
    public function submit(): void
    {
        $this->update([
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Create notification for branch admins
        \App\Models\Notification::createTeacherReportNotification($this);
    }

    public function approve(int $reviewerId): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'rejection_reason' => null,
        ]);

        // Create notification for teacher
        $reviewer = User::find($reviewerId);
        if ($reviewer) {
            \App\Models\Notification::createTeacherReportApprovalNotification($this, $reviewer);
        }
    }

    public function reject(int $reviewerId, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'rejection_reason' => $reason,
        ]);

        // Create notification for teacher
        $reviewer = User::find($reviewerId);
        if ($reviewer) {
            \App\Models\Notification::createTeacherReportRejectionNotification($this, $reviewer, $reason);
        }
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canBeSubmitted(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'pending';
    }

    public function isPendingReview(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // Static methods for teaching methods
    public static function getTeachingMethods(): array
    {
        return [
            'lecture' => 'Lecture',
            'group_work' => 'Group Work',
            'practical' => 'Practical',
            'discussion' => 'Discussion',
            'demonstration' => 'Demonstration',
            'project_based' => 'Project Based',
            'blended' => 'Blended Learning',
            'other' => 'Other',
        ];
    }

    public static function getStudentParticipationLevels(): array
    {
        return [
            'excellent' => 'Excellent',
            'good' => 'Good',
            'average' => 'Average',
            'poor' => 'Poor',
            'very_poor' => 'Very Poor',
        ];
    }
}
