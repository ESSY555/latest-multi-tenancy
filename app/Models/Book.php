<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'title',
        'author',
        'description',
        'category',
        'isbn',
        'publication_year',
        'publisher',
        'language',
        'file_path',
        'cover_image',
        'status',
        'branch_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the branch that owns the book.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the full URL to the PDF file.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get the full URL to the cover image.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    /**
     * Check if the book is available for reading/borrowing.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Scope to filter books by availability.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope to filter books by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
