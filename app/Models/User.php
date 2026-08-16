<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'address',
        'bio',
        'profile_photo',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'email_notifications',
        'assignment_reminders',
        'grade_notifications',
        'announcement_notifications',
        'attendance_alerts',
        'password',
        'is_super_admin',
        'last_login_at',
        'qualification',
        'specialization',
        'hire_date',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'hire_date' => 'date',
        ];
    }

    /**
     * Normalize usernames for case-insensitive authentication.
     */
    public function setUsernameAttribute($value): void
    {
        $this->attributes['username'] = $value !== null ? strtolower(trim((string) $value)) : null;
    }

    // Relationships
    public function branches()
    {
        return $this->belongsToMany(Branch::class)->withPivot('role')->withTimestamps();
    }

    public function teachingClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_teacher', 'teacher_id', 'school_class_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    // Role helpers within current branch
    public function hasBranchRole(int $branchId, string $role): bool
    {
        return $this->branches()->where('branches.id', $branchId)->where('branch_user.role', $role)->exists();
    }

    // Check if user has a specific role in the current branch
    public function hasRole(string $role): bool
    {
        if ($role === 'super_admin') {
            return (bool) $this->is_super_admin;
        }
        
        // For branch-specific roles, check current branch
        $currentBranchId = session('current_branch_id');
        if ($currentBranchId) {
            // Check branch_user pivot table first
            if ($this->hasBranchRole($currentBranchId, $role)) {
                return true;
            }

            // Also check dynamic assignments for form_teacher role
            if ($role === 'form_teacher') {
                return $this->isFormTeacherInBranch($currentBranchId);
            }
        }
        
        return false;
    }

    public function isFormTeacherInBranch(int $branchId): bool
    {
        return $this->formTeacherAssignments()
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->exists();
    }

    public function isFormTeacherOfClass(int $classId): bool
    {
        return $this->formTeacherAssignments()
            ->where('school_class_id', $classId)
            ->where('is_active', true)
            ->exists();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id');
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    public function annualSummaries()
    {
        return $this->hasMany(AnnualSummary::class, 'student_id');
    }

    public function mockResults()
    {
        return $this->hasMany(MockResult::class, 'student_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'student_id');
    }

    public function teacherAssignments()
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    public function lessonPlans()
    {
        return $this->hasMany(LessonPlan::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id');
    }

    public function studentSubjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects', 'student_id', 'subject_id')
            ->withPivot('school_class_id', 'academic_year', 'status')
            ->withTimestamps();
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function formTeacherAssignments()
    {
        return $this->hasMany(FormTeacher::class, 'user_id');
    }

    public function activeFormTeacherAssignment()
    {
        return $this->hasOne(FormTeacher::class, 'user_id')->where('is_active', true);
    }

    public function remarks()
    {
        return $this->hasMany(StudentRemark::class, 'student_id');
    }

    // Status-related methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate(): void
    {
        $this->update(['status' => 'inactive']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeTeachers($query)
    {
        return $query->whereHas('branches', function($q) {
            $q->where('role', 'teacher');
        });
    }
}
