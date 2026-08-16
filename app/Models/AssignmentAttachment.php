<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}


