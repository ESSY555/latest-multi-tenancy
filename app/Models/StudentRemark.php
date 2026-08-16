<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRemark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'form_teacher_id',
        'school_class_id',
        'type',
        'title',
        'content',
        'severity',
        'is_private',
        'date'
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function formTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'form_teacher_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
