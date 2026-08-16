<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'category',
        'branch_id',
        'is_active',
        'display_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Get the branch that owns the gallery item.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scope to get only active gallery items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by branch.
     */
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute()
    {
        // Check if the image file exists, if not return a real image from Unsplash
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }
        
        // Return real images from Unsplash based on category and title
        $imageUrls = [
            'School Building' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=800&h=600&fit=crop&crop=center',
            'Science Lab' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&h=600&fit=crop&crop=center',
            'Sports Day' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center',
            'Art Exhibition' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop&crop=center',
            'Library' => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=800&h=600&fit=crop&crop=center',
            'Computer Lab' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=800&h=600&fit=crop&crop=center',
            'Graduation Ceremony' => 'https://images.unsplash.com/photo-1541339904-7e4e4b9b7e09?w=800&h=600&fit=crop&crop=center',
            'Student Council' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop&crop=center',
            'Teacher Workshop' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=600&fit=crop&crop=center',
            'Academic Excellence Award' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop&crop=center',
            'School Garden' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&h=600&fit=crop&crop=center',
            'Cultural Festival' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=600&fit=crop&crop=center',
        ];
        
        // Return the specific image if found, otherwise return a category-based image
        if (isset($imageUrls[$this->title])) {
            return $imageUrls[$this->title];
        }
        
        // Fallback to category-based images
        $categoryImages = [
            'facilities' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=800&h=600&fit=crop&crop=center',
            'events' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center',
            'activities' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop&crop=center',
            'students' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop&crop=center',
            'teachers' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=600&fit=crop&crop=center',
            'achievements' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop&crop=center',
            'general' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=800&h=600&fit=crop&crop=center',
        ];
        
        return $categoryImages[$this->category] ?? $categoryImages['general'];
    }
}
