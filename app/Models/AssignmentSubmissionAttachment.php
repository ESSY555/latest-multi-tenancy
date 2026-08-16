<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmissionAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }
}


