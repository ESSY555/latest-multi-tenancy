<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\SchoolClass;
use App\Models\Enrollment;
use App\Models\Result\Result;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\User;
use App\Models\LessonPlan;
use App\Models\AdmissionApplication;
use App\Models\Gallery;
use App\Models\TeacherReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardChartService
{
    public function getSuperAdminCharts($branchId = null)
    {
        $charts = [];
        
        if (!$branchId) {
            // Global charts
            $charts['userDistribution'] = $this->getUserDistributionChart();
            $charts['branchActivity'] = $this->getBranchActivityChart();
            $charts['monthlyGrowth'] = $this->getMonthlyGrowthChart();
            $charts['recentActivity'] = $this->getRecentActivityChart();
        } else {
            // Branch-specific charts
            $charts['classEnrollment'] = $this->getClassEnrollmentChart($branchId);
            $charts['attendanceTrends'] = $this->getAttendanceTrendsChart($branchId);
            $charts['academicPerformance'] = $this->getAcademicPerformanceChart($branchId);
            $charts['teacherWorkload'] = $this->getTeacherWorkloadChart($branchId);
        }
        
        return $charts;
    }
    
    public function getAdminCharts($branchId)
    {
        return [
            'classEnrollment' => $this->getClassEnrollmentChart($branchId),
            'attendanceTrends' => $this->getAttendanceTrendsChart($branchId),
            'academicPerformance' => $this->getAcademicPerformanceChart($branchId),
            'admissionTrends' => $this->getAdmissionTrendsChart($branchId),
            'teacherPerformance' => $this->getTeacherPerformanceChart($branchId),
            'monthlyStats' => $this->getMonthlyStatsChart($branchId),
        ];
    }
    
    public function getTeacherCharts($branchId, $teacherId)
    {
        return [
            'classAttendance' => $this->getClassAttendanceChart($branchId, $teacherId),
            'assignmentSubmission' => $this->getAssignmentSubmissionChart($branchId, $teacherId),
            'studentPerformance' => $this->getStudentPerformanceChart($branchId, $teacherId),
            'attendanceTrends' => $this->getTeacherAttendanceTrendsChart($branchId, $teacherId),
            'monthlyActivity' => $this->getTeacherMonthlyActivityChart($branchId, $teacherId),
        ];
    }
    
    public function getStudentCharts($branchId, $studentId)
    {
        return [
            'attendanceHistory' => $this->getStudentAttendanceHistoryChart($branchId, $studentId),
            'academicProgress' => $this->getStudentAcademicProgressChart($branchId, $studentId),
            'subjectPerformance' => $this->getStudentSubjectPerformanceChart($branchId, $studentId),
            'monthlyAttendance' => $this->getStudentMonthlyAttendanceChart($branchId, $studentId),
        ];
    }
    
    private function getUserDistributionChart()
    {
        $userCounts = DB::table('branch_user')
            ->select('role', DB::raw('count(distinct user_id) as count'))
            ->groupBy('role')
            ->get();
            
        return [
            'type' => 'pie',
            'title' => 'User Distribution',
            'data' => [
                'labels' => $userCounts->pluck('role')->map(fn($role) => ucfirst($role))->toArray(),
                'series' => $userCounts->pluck('count')->toArray(),
            ]
        ];
    }
    
    private function getBranchActivityChart()
    {
        $branchStats = Branch::withCount(['schoolClasses', 'users'])->get();
        
        return [
            'type' => 'bar',
            'title' => 'Branch Activity',
            'data' => [
                'labels' => $branchStats->pluck('name')->toArray(),
                'series' => [
                    [
                        'name' => 'Classes',
                        'data' => $branchStats->pluck('school_classes_count')->toArray(),
                    ],
                    [
                        'name' => 'Users',
                        'data' => $branchStats->pluck('users_count')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getMonthlyGrowthChart()
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }
        
        $userGrowth = [];
        $classGrowth = [];
        
        foreach ($months as $month) {
            $date = Carbon::createFromFormat('M Y', $month);
            $userGrowth[] = User::where('created_at', '<=', $date->endOfMonth())->count();
            $classGrowth[] = SchoolClass::where('created_at', '<=', $date->endOfMonth())->count();
        }
        
        return [
            'type' => 'line',
            'title' => 'Monthly Growth',
            'data' => [
                'labels' => $months->toArray(),
                'series' => [
                    [
                        'name' => 'Users',
                        'data' => $userGrowth,
                    ],
                    [
                        'name' => 'Classes',
                        'data' => $classGrowth,
                    ]
                ]
            ]
        ];
    }
    
    private function getClassEnrollmentChart($branchId)
    {
        $classes = SchoolClass::where('branch_id', $branchId)
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Class Enrollment',
            'data' => [
                'labels' => $classes->pluck('name')->toArray(),
                'series' => [
                    [
                        'name' => 'Students',
                        'data' => $classes->pluck('enrollments_count')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getAttendanceTrendsChart($branchId)
    {
        $attendanceData = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        return [
            'type' => 'donut',
            'title' => 'Attendance Overview',
            'data' => [
                'labels' => $attendanceData->pluck('status')->map(fn($status) => ucfirst($status))->toArray(),
                'series' => $attendanceData->pluck('count')->toArray(),
            ]
        ];
    }
    
    private function getAcademicPerformanceChart($branchId)
    {
        $results = Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->select('subject_id', DB::raw('avg(total) as average_score'))
            ->with('subject')
            ->groupBy('subject_id')
            ->orderBy('average_score', 'desc')
            ->take(8)
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Subject Performance',
            'data' => [
                'labels' => $results->pluck('subject.name')->toArray(),
                'series' => [
                    [
                        'name' => 'Average Score',
                        'data' => $results->pluck('average_score')->map(fn($score) => round($score, 1))->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getTeacherWorkloadChart($branchId)
    {
        $teacherWorkload = DB::table('branch_user')
            ->join('class_teacher', 'branch_user.user_id', '=', 'class_teacher.teacher_id')
            ->join('school_classes', 'class_teacher.school_class_id', '=', 'school_classes.id')
            ->where('branch_user.branch_id', $branchId)
            ->where('branch_user.role', 'teacher')
            ->select('users.name', DB::raw('count(school_classes.id) as class_count'))
            ->join('users', 'branch_user.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->orderBy('class_count', 'desc')
            ->take(8)
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Teacher Workload',
            'data' => [
                'labels' => $teacherWorkload->pluck('name')->toArray(),
                'series' => [
                    [
                        'name' => 'Classes',
                        'data' => $teacherWorkload->pluck('class_count')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getAdmissionTrendsChart($branchId)
    {
        $admissions = AdmissionApplication::where('branch_id', $branchId)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return [
            'type' => 'line',
            'title' => 'Admission Trends',
            'data' => [
                'labels' => $admissions->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
                'series' => [
                    [
                        'name' => 'Applications',
                        'data' => $admissions->pluck('count')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getTeacherPerformanceChart($branchId)
    {
        $teacherPerformance = DB::table('branch_user')
            ->join('users', 'branch_user.user_id', '=', 'users.id')
            ->where('branch_user.branch_id', $branchId)
            ->where('branch_user.role', 'teacher')
            ->select('users.id', 'users.name')
            ->get()
            ->map(function($teacher) {
                $lessonPlans = LessonPlan::where('teacher_id', $teacher->id)->count();
                $assignments = Assignment::where('teacher_id', $teacher->id)->count();
                return [
                    'name' => $teacher->name,
                    'lesson_plans' => $lessonPlans,
                    'assignments' => $assignments,
                ];
            })
            ->take(8);
            
        return [
            'type' => 'bar',
            'title' => 'Teacher Performance',
            'data' => [
                'labels' => $teacherPerformance->pluck('name')->toArray(),
                'series' => [
                    [
                        'name' => 'Lesson Plans',
                        'data' => $teacherPerformance->pluck('lesson_plans')->toArray(),
                    ],
                    [
                        'name' => 'Assignments',
                        'data' => $teacherPerformance->pluck('assignments')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getMonthlyStatsChart($branchId)
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }
        
        $stats = [];
        foreach ($months as $month) {
            $date = Carbon::createFromFormat('M Y', $month);
            $stats[] = [
                'month' => $month,
                'attendance' => Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
                    ->whereBetween('date', [$date->startOfMonth(), $date->endOfMonth()])
                    ->count(),
                'assignments' => Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
                    ->whereBetween('created_at', [$date->startOfMonth(), $date->endOfMonth()])
                    ->count(),
                'results' => Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
                    ->whereBetween('created_at', [$date->startOfMonth(), $date->endOfMonth()])
                    ->count(),
            ];
        }
        
        return [
            'type' => 'line',
            'title' => 'Monthly Statistics',
            'data' => [
                'labels' => $months->toArray(),
                'series' => [
                    [
                        'name' => 'Attendance Records',
                        'data' => collect($stats)->pluck('attendance')->toArray(),
                    ],
                    [
                        'name' => 'Assignments',
                        'data' => collect($stats)->pluck('assignments')->toArray(),
                    ],
                    [
                        'name' => 'Results',
                        'data' => collect($stats)->pluck('results')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getClassAttendanceChart($branchId, $teacherId)
    {
        $classes = SchoolClass::where('branch_id', $branchId)
            ->whereHas('teachers', fn($q) => $q->where('teacher_id', $teacherId))
            ->withCount(['enrollments', 'attendances'])
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Class Overview',
            'data' => [
                'labels' => $classes->pluck('name')->toArray(),
                'series' => [
                    [
                        'name' => 'Students',
                        'data' => $classes->pluck('enrollments_count')->toArray(),
                    ],
                    [
                        'name' => 'Attendance Records',
                        'data' => $classes->pluck('attendances_count')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getAssignmentSubmissionChart($branchId, $teacherId)
    {
        $assignments = Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->where('teacher_id', $teacherId)
            ->withCount('submissions')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Assignment Submissions',
            'data' => [
                'labels' => $assignments->pluck('title')->map(fn($title) => substr($title, 0, 20) . '...')->toArray(),
                'series' => [
                    [
                        'name' => 'Submissions',
                        'data' => $assignments->pluck('submissions_count')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getStudentPerformanceChart($branchId, $teacherId)
    {
        $results = Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->whereHas('subject', fn($q) => $q->whereHas('teachers', fn($subQ) => $subQ->where('teacher_id', $teacherId)))
            ->select('subject_id', DB::raw('avg(total) as average_score'))
            ->with('subject')
            ->groupBy('subject_id')
            ->orderBy('average_score', 'desc')
            ->take(6)
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Student Performance by Subject',
            'data' => [
                'labels' => $results->pluck('subject.name')->toArray(),
                'series' => [
                    [
                        'name' => 'Average Score',
                        'data' => $results->pluck('average_score')->map(fn($score) => round($score, 1))->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getTeacherAttendanceTrendsChart($branchId, $teacherId)
    {
        $attendanceData = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->whereHas('schoolClass.teachers', fn($q) => $q->where('teacher_id', $teacherId))
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        return [
            'type' => 'pie',
            'title' => 'Attendance Distribution',
            'data' => [
                'labels' => $attendanceData->pluck('status')->map(fn($status) => ucfirst($status))->toArray(),
                'series' => $attendanceData->pluck('count')->toArray(),
            ]
        ];
    }
    
    private function getTeacherMonthlyActivityChart($branchId, $teacherId)
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }
        
        $activity = [];
        foreach ($months as $month) {
            $date = Carbon::createFromFormat('M Y', $month);
            $activity[] = [
                'month' => $month,
                'assignments' => Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
                    ->where('teacher_id', $teacherId)
                    ->whereBetween('created_at', [$date->startOfMonth(), $date->endOfMonth()])
                    ->count(),
                'lesson_plans' => LessonPlan::where('branch_id', $branchId)
                    ->where('teacher_id', $teacherId)
                    ->whereBetween('created_at', [$date->startOfMonth(), $date->endOfMonth()])
                    ->count(),
            ];
        }
        
        return [
            'type' => 'line',
            'title' => 'Monthly Activity',
            'data' => [
                'labels' => $months->toArray(),
                'series' => [
                    [
                        'name' => 'Assignments',
                        'data' => collect($activity)->pluck('assignments')->toArray(),
                    ],
                    [
                        'name' => 'Lesson Plans',
                        'data' => collect($activity)->pluck('lesson_plans')->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getStudentAttendanceHistoryChart($branchId, $studentId)
    {
        $attendanceData = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->where('student_id', $studentId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        return [
            'type' => 'donut',
            'title' => 'Attendance Overview',
            'data' => [
                'labels' => $attendanceData->pluck('status')->map(fn($status) => ucfirst($status))->toArray(),
                'series' => $attendanceData->pluck('count')->toArray(),
            ]
        ];
    }
    
    private function getStudentAcademicProgressChart($branchId, $studentId)
    {
        $results = Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->where('student_id', $studentId)
            ->select('subject_id', DB::raw('avg(total) as average_score'))
            ->with('subject')
            ->groupBy('subject_id')
            ->orderBy('average_score', 'desc')
            ->take(6)
            ->get();
            
        return [
            'type' => 'bar',
            'title' => 'Subject Performance',
            'data' => [
                'labels' => $results->pluck('subject.name')->toArray(),
                'series' => [
                    [
                        'name' => 'Average Score',
                        'data' => $results->pluck('average_score')->map(fn($score) => round($score, 1))->toArray(),
                    ]
                ]
            ]
        ];
    }
    
    private function getStudentSubjectPerformanceChart($branchId, $studentId)
    {
        $results = Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->where('student_id', $studentId)
            ->select('subject_id', 'total', 'created_at')
            ->with('subject')
            ->orderBy('created_at')
            ->get()
            ->groupBy('subject.name');
            
        $chartData = [];
        $labels = [];
        
        foreach ($results as $subject => $subjectResults) {
            $chartData[] = [
                'name' => $subject,
                'data' => $subjectResults->pluck('total')->toArray(),
            ];
            $labels = $subjectResults->pluck('created_at')->map(fn($date) => $date->format('M d'))->toArray();
        }
        
        return [
            'type' => 'line',
            'title' => 'Performance Over Time',
            'data' => [
                'labels' => $labels,
                'series' => $chartData,
            ]
        ];
    }
    
    private function getStudentMonthlyAttendanceChart($branchId, $studentId)
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }
        
        $attendance = [];
        foreach ($months as $month) {
            $date = Carbon::createFromFormat('M Y', $month);
            $total = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
                ->where('student_id', $studentId)
                ->whereBetween('date', [$date->startOfMonth(), $date->endOfMonth()])
                ->count();
            $present = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
                ->where('student_id', $studentId)
                ->where('status', 'present')
                ->whereBetween('date', [$date->startOfMonth(), $date->endOfMonth()])
                ->count();
            $attendance[] = $total > 0 ? round(($present / $total) * 100, 1) : 0;
        }
        
        return [
            'type' => 'line',
            'title' => 'Monthly Attendance Rate',
            'data' => [
                'labels' => $months->toArray(),
                'series' => [
                    [
                        'name' => 'Attendance Rate (%)',
                        'data' => $attendance,
                    ]
                ]
            ]
        ];
    }
    
    private function getRecentActivityChart()
    {
        $activities = collect();
        
        // Recent users
        $recentUsers = User::latest()->take(5)->get();
        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'User Registration',
                'description' => $user->name . ' registered',
                'date' => $user->created_at,
            ]);
        }
        
        // Recent assignments
        $recentAssignments = Assignment::latest()->take(5)->get();
        foreach ($recentAssignments as $assignment) {
            $activities->push([
                'type' => 'Assignment Created',
                'description' => $assignment->title . ' by ' . ($assignment->teacher_name ?? $assignment->teacher->name),
                'date' => $assignment->created_at,
            ]);
        }
        
        // Recent results
        $recentResults = Result::with(['student', 'schoolClass', 'subject'])->latest()->take(5)->get();
        foreach ($recentResults as $result) {
            $activities->push([
                'type' => 'Result Recorded',
                'description' => $result->subject->name . ' for ' . $result->student->name,
                'date' => $result->created_at,
            ]);
        }
        
        $activities = $activities->sortByDesc('date')->take(10);
        
        return [
            'type' => 'timeline',
            'title' => 'Recent Activity',
            'data' => $activities->toArray(),
        ];
    }
}
