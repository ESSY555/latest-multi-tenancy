<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TeacherAttendance extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'branch_id',
        'date',
        'status',
        'time_in',
        'time_out',
        'reason',
        'marked_by'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime:H:i',
        'time_out' => 'datetime:H:i',
    ];

    /**
     * Get the teacher that owns the attendance record.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the branch that owns the attendance record.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who marked the attendance.
     */
    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'present' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Present</span>',
            'absent' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Absent</span>',
            'late' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Late</span>',
            'on_leave' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">On Leave</span>',
        ];

        return $badges[$this->status] ?? $badges['present'];
    }

    /**
     * Get the formatted time in.
     */
    public function getFormattedTimeInAttribute(): string
    {
        return $this->time_in ? Carbon::parse($this->time_in)->format('H:i') : '-';
    }

    /**
     * Get the formatted time out.
     */
    public function getFormattedTimeOutAttribute(): string
    {
        return $this->time_out ? Carbon::parse($this->time_out)->format('H:i') : '-';
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M d, Y');
    }

    /**
     * Scope to filter by branch.
     */
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope to filter by teacher.
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get today's attendance.
     */
    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    /**
     * Scope to get this month's attendance.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('date', now()->year)
                    ->whereMonth('date', now()->month);
    }
}
