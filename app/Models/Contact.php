<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'branch_id',
        'status',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'unread' => 'bg-red-100 text-red-800',
            'read' => 'bg-yellow-100 text-yellow-800',
            'replied' => 'bg-green-100 text-green-800'
        ];
        
        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }
}
