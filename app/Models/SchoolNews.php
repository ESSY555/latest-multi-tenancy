<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolNews extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'image_path',
        'is_published',
        'published_at',
        'author_id',
        'branch_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Accessors
    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : 'Draft';
    }

    public function getStatusAttribute()
    {
        if (!$this->is_published) {
            return 'Draft';
        }
        
        if ($this->published_at && $this->published_at->isFuture()) {
            return 'Scheduled';
        }
        
        return 'Published';
    }
}
