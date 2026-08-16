<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result\Result;
use App\Models\Attendance;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        
        // Get children (students)
        $children = $user->children()
            ->whereHas('branches', function($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId);
            })
            ->with(['enrollments' => function($query) use ($currentBranchId) {
                $query->whereHas('schoolClass', function($subQuery) use ($currentBranchId) {
                    $subQuery->where('branch_id', $currentBranchId);
                });
            }, 'enrollments.schoolClass', 'enrollments.subjects'])
            ->get();

        if ($children->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'No children found in this branch.');
        }

        // Get overview data for all children
        $overviewData = [];
        foreach ($children as $child) {
            $overviewData[$child->id] = [
                'name' => $child->name,
                'class' => $child->enrollments->first()?->schoolClass?->name ?? 'Not Enrolled',
                'attendance' => $this->getChildAttendanceSummary($child->id, $currentBranchId),
                'recent_results' => $this->getChildRecentResults($child->id, $currentBranchId),
                'pending_assignments' => $this->getChildPendingAssignments($child->id, $currentBranchId),
                'gpa' => $this->calculateChildGPA($child->id, $currentBranchId)
            ];
        }

        return view('dashboard.roles.parent', compact('children', 'overviewData'));
    }

    private function getChildAttendanceSummary($childId, $branchId)
    {
        $totalDays = Attendance::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $childId)
            ->count();

        $presentDays = Attendance::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $childId)
            ->where('status', 'present')
            ->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'percentage' => $attendancePercentage
        ];
    }

    private function getChildRecentResults($childId, $branchId)
    {
        return Result::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $childId)
            ->with(['schoolClass'])
            ->latest()
            ->take(3)
            ->get();
    }

    private function getChildPendingAssignments($childId, $branchId)
    {
        return Assignment::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->whereIn('school_class_id', \App\Models\User::find($childId)->enrollments()->whereHas('schoolClass', function($subQuery) use ($branchId) {
                $subQuery->where('branch_id', $branchId);
            })->pluck('school_class_id'))
            ->where('due_date', '>=', now())
            ->with(['schoolClass'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
    }

    private function calculateChildGPA($childId, $branchId)
    {
        $results = Result::whereHas('schoolClass', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('student_id', $childId)
            ->get();

        if ($results->isEmpty()) {
            return 0.0;
        }

        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($results as $result) {
            $grade = strtoupper($result->grade ?? 'F');
            $points = $this->getGradePoints($grade);
            $credits = 1;

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

    public function childDetails($childId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        
        // Verify this is actually the user's child
        $child = $user->children()
            ->where('id', $childId)
            ->whereHas('branches', function($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId);
            })
            ->first();

        if (!$child) {
            return redirect()->route('parent.dashboard')->with('error', 'Child not found.');
        }

        // Get detailed information
        $enrollment = $child->enrollments()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass', 'subjects'])
            ->first();

        $results = $child->results()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->orderBy('created_at', 'desc')
            ->get();

        $attendance = $child->attendance()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->orderBy('date', 'desc')
            ->get();

        $assignments = Assignment::whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->whereIn('school_class_id', $child->enrollments()->whereHas('schoolClass', function($subQuery) use ($currentBranchId) {
                $subQuery->where('branch_id', $currentBranchId);
            })->pluck('school_class_id'))
            ->with(['schoolClass'])
            ->orderBy('due_date', 'asc')
            ->get();

        $gpa = $this->calculateChildGPA($child->id, $currentBranchId);
        $attendanceSummary = $this->getChildAttendanceSummary($child->id, $currentBranchId);

        return view('dashboard.parent.child-details', compact(
            'child',
            'enrollment',
            'results',
            'attendance',
            'assignments',
            'gpa',
            'attendanceSummary'
        ));
    }

    public function childGrades($childId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        
        $child = $user->children()
            ->where('id', $childId)
            ->whereHas('branches', function($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId);
            })
            ->first();

        if (!$child) {
            return redirect()->route('parent.dashboard')->with('error', 'Child not found.');
        }

        $results = $child->results()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->orderBy('created_at', 'desc')
            ->get();

        $gpa = $this->calculateChildGPA($child->id, $currentBranchId);

        return view('dashboard.parent.child-grades', compact('child', 'results', 'gpa'));
    }

    public function childAttendance($childId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        
        $child = $user->children()
            ->where('id', $childId)
            ->whereHas('branches', function($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId);
            })
            ->first();

        if (!$child) {
            return redirect()->route('parent.dashboard')->with('error', 'Child not found.');
        }

        $attendance = $child->attendance()
            ->whereHas('schoolClass', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->with(['schoolClass'])
            ->orderBy('date', 'desc')
            ->get();

        $attendanceSummary = $this->getChildAttendanceSummary($child->id, $currentBranchId);

        return view('dashboard.parent.child-attendance', compact('child', 'attendance', 'attendanceSummary'));
    }
}
