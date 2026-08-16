<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StudyMaterial extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'title',
        'description',
        'subject',
        'class_level',
        'type',
        'file_path',
        'file_size',
        'duration', // For videos
        'views',
        'downloads',
        'uploaded_by',
        'branch_id',
        'is_active',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'duration' => 'integer', // Duration in seconds
        'views' => 'integer',
        'downloads' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who uploaded the material.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the branch that owns the material.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the full URL to the file.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if ($this->file_size < 1024) {
            return $this->file_size . ' B';
        } elseif ($this->file_size < 1024 * 1024) {
            return round($this->file_size / 1024, 1) . ' KB';
        } else {
            return round($this->file_size / (1024 * 1024), 1) . ' MB';
        }
    }

    /**
     * Get formatted duration for videos.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) return '';
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get time ago since upload.
     */
    public function getTimeAgoAttribute(): string
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Check if the material is a video.
     */
    public function isVideo(): bool
    {
        return in_array(strtolower($this->type), ['video', 'mp4', 'avi', 'mov']);
    }

    /**
     * Check if the material is a PDF.
     */
    public function isPdf(): bool
    {
        return strtolower($this->type) === 'pdf';
    }

    /**
     * Check if the material is a presentation.
     */
    public function isPresentation(): bool
    {
        return in_array(strtolower($this->type), ['presentation', 'ppt', 'pptx']);
    }

    /**
     * Check if the material is a worksheet.
     */
    public function isWorksheet(): bool
    {
        return strtolower($this->type) === 'worksheet';
    }

    /**
     * Increment view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Increment download count.
     */
    public function incrementDownloads(): void
    {
        $this->increment('downloads');
    }

    /**
     * Scope to filter by subject.
     */
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter active materials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
