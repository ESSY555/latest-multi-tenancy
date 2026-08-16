<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'status',
        'grade',
        'remarks',
        'can_resubmit',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function attachments()
    {
        return $this->hasMany(AssignmentSubmissionAttachment::class, 'submission_id');
    }

    public function isFinalized(): bool
    {
        return in_array($this->status, ['approved', 'graded']) && !$this->can_resubmit;
    }
}


