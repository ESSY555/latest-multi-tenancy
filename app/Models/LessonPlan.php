<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LessonPlan extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'teacher_name',
        'subject_topic',
        'class_grade_level',
        'lesson_date',
        'duration',
        'lesson_title',
        'objectives',
        'materials_resources',
        'lesson_introduction',
        'lesson_development',
        'assessment_evaluation',
        'conclusion',
        'reflection',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'teacher_id',
        'branch_id',
        'class_id',
        'school_name',
        'term_name',
        'week_name',
        'theme',
        'subtopic',
        'periods',
        'time_slot',
        'class_size',
        'average_age',
        'sex_label',
        'rationale',
        'previous_knowledge',
        'reference_books',
        'learning_aids',
        'lesson_note',
    ];

    protected $casts = [
        'lesson_date' => 'date',
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

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Attachments
    public function attachments()
    {
        return $this->hasMany(LessonPlanAttachment::class);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Draft</span>',
            'submitted' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Submitted</span>',
            'approved' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Approved</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rejected</span>',
            default => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unknown</span>'
        };
    }

    public function getFormattedLessonDateAttribute(): string
    {
        return $this->lesson_date->format('M d, Y');
    }

    public function getFormattedSubmittedAtAttribute(): string
    {
        return $this->submitted_at ? $this->submitted_at->format('M d, Y \a\t g:i A') : 'Not submitted';
    }

    public function getFormattedReviewedAtAttribute(): string
    {
        return $this->reviewed_at ? $this->reviewed_at->format('M d, Y \a\t g:i A') : 'Not reviewed';
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
        return $query->where('status', 'submitted');
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
        return $query->where('status', 'submitted');
    }

    // Methods
    public function submit(): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Create notification for branch admins
        \App\Models\Notification::createLessonPlanNotification($this);
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
            \App\Models\Notification::createLessonPlanApprovalNotification($this, $reviewer);
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
            \App\Models\Notification::createLessonPlanRejectionNotification($this, $reviewer, $reason);
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
        return $this->status === 'submitted';
    }

    public function isPendingReview(): bool
    {
        return $this->status === 'submitted';
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
}
