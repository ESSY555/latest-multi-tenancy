<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AdmissionApplication extends Model
{
    use BelongsToBranch;

    use HasFactory, Notifiable;

    protected $fillable = [
        'branch_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'nationality',
        'state_of_origin',
        'local_government_area',
        'religion',
        'church_denomination',
        'language_of_communication',
        'number_of_children_in_family',
        'position_in_family',
        'school_last_attended',
        'class_last_attended',
        'has_health_challenges',
        'health_challenges_details',
        'crisis_response',
        'current_grade',
        'primary_contact_name',
        'father_name',
        'mother_name',
        'father_residential_address',
        'mother_residential_address',
        'father_occupation',
        'mother_occupation',
        'father_office_address',
        'mother_office_address',
        'father_phone_number',
        'mother_phone_number',
        'relationship',
        'phone_number',
        'email',
        'address',
        'hear_about_school',
        'additional_info',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'has_health_challenges' => 'boolean',
    ];

    protected $appends = [
        'full_name',
        'age',
        'status_color',
    ];

    /**
     * Get the full name of the student
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the age of the student
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    /**
     * Scope for pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for reviewed applications
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope for approved applications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected applications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the branch that this application belongs to
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Check if application is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is reviewed
     */
    public function isReviewed()
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if application is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if application is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the application priority (for sorting)
     */
    public function getPriorityAttribute()
    {
        return match($this->status) {
            'pending' => 1,
            'reviewed' => 2,
            'approved' => 3,
            'rejected' => 4,
            default => 5,
        };
    }

    /**
     * Get the days since application was submitted
     */
    public function getDaysSinceSubmissionAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Check if application is overdue (pending for more than 7 days)
     */
    public function getIsOverdueAttribute()
    {
        return $this->isPending() && $this->days_since_submission > 7;
    }

    /**
     * Get the overdue status color
     */
    public function getOverdueColorAttribute()
    {
        if ($this->is_overdue) {
            return 'bg-red-100 text-red-800 border border-red-300';
        }
        return $this->status_color;
    }
}
