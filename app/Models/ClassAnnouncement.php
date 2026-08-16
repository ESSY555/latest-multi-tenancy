<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAnnouncement extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'form_teacher_id',
        'school_class_id',
        'branch_id',
        'title',
        'content',
        'priority',
        'is_published',
        'published_at',
        'expiry_date'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    public function formTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'form_teacher_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
