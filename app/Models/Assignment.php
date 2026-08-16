<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['school_class_id', 'teacher_id', 'teacher_name', 'title', 'description', 'instructions', 'submission_format', 'due_date', 'is_published', 'allow_late'];

    protected $casts = [
        'due_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attachments()
    {
        return $this->hasMany(AssignmentAttachment::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}


