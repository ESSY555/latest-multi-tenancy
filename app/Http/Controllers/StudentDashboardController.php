<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result\Result;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\ClassAnnouncement;
use App\Services\DashboardChartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    protected $chartService;
    
    public function __construct(DashboardChartService $chartService)
    {
        $this->chartService = $chartService;
    }
    
    public function index()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        
        // Check if user has student role in current branch
        $studentRole = \DB::table('branch_user')
            ->where('user_id', $user->id)
            ->where('branch_id', $currentBranchId)
            ->where('role', 'student')
            ->first();
        
        if (!$studentRole) {
            return redirect()->route('dashboard')->with('error', 'You do not have student access in this branch.');
        }

        // Get current class enrollment
        $currentEnrollment = $user->enrollments()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->latest()
            ->first();

        // Get recent results
        $recentResults = $user->results()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // Get attendance summary
        $attendanceSummary = $this->getAttendanceSummary($user->id, $currentBranchId);

        // Get recent published assignments for student's enrolled classes
        $recentAssignments = Assignment::whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->whereIn('school_class_id', $user->enrollments()->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })->pluck('school_class_id'))
            ->where('is_published', true)
            ->with(['schoolClass'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent announcements for student's class
        $recentAnnouncements = collect();
        if ($currentEnrollment) {
            $recentAnnouncements = ClassAnnouncement::where('school_class_id', $currentEnrollment->school_class_id)
                ->where('is_published', true)
                ->where(function($query) {
                    $query->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>=', now());
                })
                ->with(['formTeacher'])
                ->latest()
                ->take(3)
                ->get();
        }

        // Calculate GPA
        $gpa = $this->calculateGPA($user->id, $currentBranchId);

        // Get charts for student
        $charts = $this->chartService->getStudentCharts($currentBranchId, $user->id);

        return view('dashboard.roles.student', compact(
            'currentEnrollment',
            'recentResults',
            'attendanceSummary',
            'recentAssignments',
            'recentAnnouncements',
            'gpa',
            'charts'
        ));
    }

    private function getAttendanceSummary($studentId, $branchId)
    {
        $totalDays = Attendance::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $studentId)
            ->count();

        $presentDays = Attendance::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $studentId)
            ->where('status', 'present')
            ->count();

        $absentDays = Attendance::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $studentId)
            ->where('status', 'absent')
            ->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'percentage' => $attendancePercentage
        ];
    }

    private function calculateGPA($studentId, $branchId)
    {
        $results = Result::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $studentId)
            ->get();

        if ($results->isEmpty()) {
            return 0.0;
        }

        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($results as $result) {
            // Simple GPA calculation (A=4, B=3, C=2, D=1, F=0)
            $grade = strtoupper($result->grade ?? 'F');
            $points = $this->getGradePoints($grade);
            $credits = 1; // Assuming 1 credit per subject for now

            $totalPoints += ($points * $credits);
            $totalCredits += $credits;
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }

    private function getGradePoints($grade)
    {
        return match($grade) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0
        };
    }

    public function grades()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        $results = $user->results()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        $gpa = $this->calculateGPA($user->id, $currentBranchId);

        return view('dashboard.student.grades', compact('results', 'gpa'));
    }

    public function attendance()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        $attendance = $user->attendance()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->orderBy('date', 'desc')
            ->get();

        $attendanceSummary = $this->getAttendanceSummary($user->id, $currentBranchId);

        return view('dashboard.student.attendance', compact('attendance', 'attendanceSummary'));
    }

    public function assignments()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        $studentClassIds = $user->enrollments()->whereHas('schoolClass', function($subQuery) use ($currentBranchId) {
            $subQuery->where('branch_id', $currentBranchId);
        })->pluck('school_class_id');

        $assignments = Assignment::whereIn('school_class_id', $studentClassIds)
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->where('is_published', true)
            ->with(['schoolClass', 'submissions' => function($q) use ($user) {
                $q->where('student_id', $user->id)->latest();
            }])
            ->orderBy('due_date', 'asc')
            ->get();

        // Categorize: overdue (due_date < today), today, upcoming (due_date > today)
        $today = now()->startOfDay();
        $overdueAssignments = $assignments->filter(function($a) use ($today) {
            return $a->due_date && $a->due_date->lt($today);
        });
        $todayAssignments = $assignments->filter(function($a) use ($today) {
            return $a->due_date && $a->due_date->isSameDay($today);
        });
        $upcomingAssignments = $assignments->filter(function($a) use ($today) {
            return !$a->due_date || $a->due_date->gt($today);
        });

        return view('dashboard.student.assignments', compact('assignments', 'overdueAssignments', 'todayAssignments', 'upcomingAssignments'));
    }

    public function announcements()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Get current class enrollment
        $currentEnrollment = $user->enrollments()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->latest()
            ->first();

        if (!$currentEnrollment) {
            return redirect()->route('student.dashboard')->with('error', 'You are not enrolled in any class in this branch.');
        }

        // Get announcements for the student's current class
        $announcements = ClassAnnouncement::where('school_class_id', $currentEnrollment->school_class_id)
            ->where('is_published', true)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->with(['formTeacher', 'schoolClass'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.student.announcements', compact('announcements', 'currentEnrollment'));
    }
}
