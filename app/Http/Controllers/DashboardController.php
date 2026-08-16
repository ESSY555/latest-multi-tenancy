<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AcademicTerm;
use App\Models\AnnualSummary;
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
use App\Models\FormTeacher;
use App\Services\DashboardChartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $chartService;
    
    public function __construct(DashboardChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    private function buildPromotionAndPerformanceSummary(?int $branchId, ?AcademicYear $academicYear, ?AcademicTerm $academicTerm): array
    {
        $summary = [
            'promoted_count' => 0,
            'not_promoted_count' => 0,
            'failed_count' => 0,
            'resit_count' => 0,
            'pending_count' => 0,
            'best_per_class' => collect(),
            'session_name' => $academicYear?->name ?? 'N/A',
            'term_name' => $academicTerm?->name ?? ($academicTerm ? 'Term ' . $academicTerm->term_number : 'N/A'),
        ];

        if (!$academicYear) {
            return $summary;
        }

        $annualSummaries = AnnualSummary::query()
            ->where('academic_year_id', $academicYear->id)
            ->whereNotNull('promotion_status')
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('student.branches', function ($branchQuery) use ($branchId) {
                    $branchQuery->where('branches.id', $branchId)
                        ->where('branch_user.role', 'student');
                });
            })
            ->select('student_id', 'promotion_status')
            ->get();

        $annualSummariesByStudent = $annualSummaries->keyBy('student_id');

        $studentPerformanceQuery = Result::query()
            ->select(
                'results.student_id',
                'results.school_class_id',
                'users.name as student_name',
                'school_classes.name as class_name',
                DB::raw('AVG(results.total) as average_score')
            )
            ->join('users', 'users.id', '=', 'results.student_id')
            ->join('school_classes', 'school_classes.id', '=', 'results.school_class_id')
            ->whereHas('academicTerm', function ($query) use ($academicYear) {
                $query->where('academic_year_id', $academicYear->id);
            })
            ->whereNotNull('results.school_class_id')
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('results.branch_id', $branchId);
            })
            ->when($academicTerm, function ($query) use ($academicTerm) {
                $query->where('results.term_id', $academicTerm->id);
            })
            ->groupBy('results.student_id', 'results.school_class_id', 'users.name', 'school_classes.name');

        $studentPerformance = $studentPerformanceQuery->get()->map(function ($row) use ($annualSummariesByStudent) {
            $summaryRecord = $annualSummariesByStudent->get($row->student_id);
            $promotionStatus = 'not_promoted';

            if ($summaryRecord && !empty($summaryRecord->promotion_status)) {
                $promotionStatus = $summaryRecord->promotion_status;
            } elseif ((float) $row->average_score < 45) {
                $promotionStatus = 'failed';
            }

            return [
                'student_id' => (int) $row->student_id,
                'promotion_status' => $promotionStatus,
                'average_score' => round((float) $row->average_score, 2),
                'student_name' => $row->student_name ?? 'Unknown student',
                'class_name' => $row->class_name ?? 'Unknown class',
            ];
        });

        $summary['promoted_count'] = $studentPerformance->filter(fn ($row) => $row['promotion_status'] === 'promoted')->count();
        $summary['promoted_by_trial_count'] = $studentPerformance->filter(fn ($row) => $row['promotion_status'] === 'promoted_by_trial')->count();
        $summary['not_promoted_count'] = $studentPerformance->where('promotion_status', 'not_promoted')->count();
        $summary['failed_count'] = $studentPerformance->where('promotion_status', 'failed')->count();
        $summary['resit_count'] = $studentPerformance->where('promotion_status', 'resit')->count();
        $summary['pending_count'] = $studentPerformance->where('promotion_status', 'pending')->count();

        $bestPerClass = collect();
        foreach ($studentPerformance->groupBy('class_name') as $className => $classStudents) {
            $best = $classStudents->sortByDesc('average_score')->first();
            if ($best) {
                $bestPerClass->push([
                    'student_name' => $best['student_name'],
                    'class_name' => $className,
                    'average_score' => $best['average_score'],
                    'position' => 1,
                ]);
            }
        }

        $summary['best_per_class'] = $bestPerClass->sortBy('class_name')->values();

        return $summary;
    }
    
    public function index()
    {
        $user = Auth::user();
        if (request()->has('global') && Auth::user()->is_super_admin) {
            session()->forget('current_branch_id');
        }
        $branchId = session('current_branch_id');
        
        // Debug logging
        \Log::info('Dashboard access attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_super_admin' => $user->is_super_admin,
            'session_branch_id' => $branchId,
            'session_data' => session()->all()
        ]);
        
        // If no branch is selected and user is not super admin, redirect to branch selection
        if (! $user->is_super_admin && ! $branchId) {
            \Log::info('Redirecting to branch selection - no branch selected');
            return redirect('/select-branch');
        }
        
        // Get branch instance
        $branch = null;
        if ($branchId) {
            $branch = Branch::find($branchId);
            if ($branch) {
                app()->instance('currentBranch', $branch);
                \Log::info('Branch found and set', ['branch_id' => $branch->id, 'branch_name' => $branch->name]);
            } else {
                \Log::warning('Branch not found in database', ['branch_id' => $branchId]);
            }
        }

        $isSuper = (bool) $user->is_super_admin;
        $role = session('current_role');
        $academicYears = \App\Models\AcademicYear::when($branchId, function($query) use ($branchId) {
            return $query->where('branch_id', $branchId);
        })->orderBy('start_date', 'desc')->get();

        $selectedSessionId = request('session_id');
        if ($selectedSessionId) {
            $activeAcademicYear = \App\Models\AcademicYear::find($selectedSessionId);
            $activeAcademicTerm = $activeAcademicYear?->getCurrentTerm();
        } else {
            $activeAcademicYear = \App\Models\AcademicYear::getCurrentAcademicYear($branchId);
            $activeAcademicTerm = $activeAcademicYear?->getCurrentTerm();
        }
        
        $promotionSummary = $this->buildPromotionAndPerformanceSummary($branchId, $activeAcademicYear, $activeAcademicTerm);
        
        // If session role is not set, determine from database
        if (!$role) {
            $role = $isSuper ? 'super_admin' : (\DB::table('branch_user')->where('user_id', $user->id)->where('branch_id', $branchId)->value('role') ?? '');
            if ($role) {
                session(['current_role' => $role]);
            }
        }
        
        \Log::info('User role determined', [
            'user_id' => $user->id,
            'role' => $role,
            'branch_id' => $branchId
        ]);

        if ($role === 'super_admin') {
            // Super Admin Dashboard - can be global or branch-specific
            if (!$branch) {
                // Global overview
                $stats = [
                    'branches' => Branch::count(),
                    'classes' => SchoolClass::count(),
                    'teachers' => \DB::table('branch_user')->where('role', 'teacher')->distinct('user_id')->count('user_id'),
                    'students' => \DB::table('branch_user')->where('role', 'student')->distinct('user_id')->count('user_id'),
                    'parents' => \DB::table('branch_user')->where('role', 'parent')->distinct('user_id')->count('user_id'),
                    'administrators' => \App\Models\User::where('is_super_admin', true)->orWhereHas('branches', fn($q) => $q->where('role', 'admin'))->count(),
                    'results' => Result::count(),
                    'assignments' => Assignment::count(),
                    'attendance' => Attendance::count(),
                    'admissions' => AdmissionApplication::count(),
                    'gallery_items' => Gallery::count(),
                ];
                
                $recentBranches = Branch::latest()->take(6)->get();
                $recentClasses = SchoolClass::latest()->with('branch')->take(6)->get();
                $recentUsers = User::with('branches')->latest()->take(10)->get();
                $recentAssignments = Assignment::with(['schoolClass.branch', 'teacher'])->latest()->take(6)->get();
                $recentResults = Result::with(['student', 'schoolClass.branch'])->latest()->take(6)->get();
                $recentAdmissions = AdmissionApplication::with('branch')->latest()->take(5)->get();
                
                // Additional data for enhanced monitoring
                $recentAttendance = Attendance::with(['student', 'schoolClass.branch'])->latest()->take(10)->get();
                $recentAcademicEvents = \App\Models\AcademicEvent::with(['academicYear.branch'])->latest()->take(10)->get();
                $userActivitySummary = [
                    'admins' => \DB::table('branch_user')->where('role', 'admin')->distinct('user_id')->count('user_id'),
                    'teachers' => \DB::table('branch_user')->where('role', 'teacher')->distinct('user_id')->count('user_id'),
                    'students' => \DB::table('branch_user')->where('role', 'student')->distinct('user_id')->count('user_id'),
                    'parents' => \DB::table('branch_user')->where('role', 'parent')->distinct('user_id')->count('user_id'),
                ];
                
                // Get charts for super admin
                $charts = $this->chartService->getSuperAdminCharts();
                
                return view('dashboard.roles.super', compact(
                    'stats', 
                    'recentBranches', 
                    'recentClasses', 
                    'recentUsers', 
                    'recentAssignments', 
                    'recentResults',
                    'recentAttendance',
                    'recentAcademicEvents',
                    'userActivitySummary',
                    'recentAdmissions',
                    'charts',
                    'promotionSummary',
                    'activeAcademicYear',
                    'activeAcademicTerm',
                    'academicYears',
                    'selectedSessionId'
                ));
            } else {
                // Branch-specific overview for super admin
                $stats = [
                    'classes' => SchoolClass::where('branch_id', $branchId)->count(),
                    'subjects' => Subject::where('branch_id', $branchId)->count(),
                    'teachers' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'teacher')->distinct('user_id')->count('user_id'),
                    'students' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'student')->distinct('user_id')->count('user_id'),
                    'parents' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'parent')->distinct('user_id')->count('user_id'),
                    'administrators' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'admin')->distinct('user_id')->count('user_id'),
                    'results' => Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->count(),
                    'assignments' => Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->count(),
                    'attendance' => Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->count(),
                    'pending_lesson_plans' => LessonPlan::where('branch_id', $branchId)->where('status', 'submitted')->count(),
                    'admissions' => AdmissionApplication::where('branch_id', $branchId)->count(),
                    'gallery_items' => Gallery::where('branch_id', $branchId)->orWhereNull('branch_id')->count(),
                ];
                
                $recentClasses = SchoolClass::where('branch_id', $branchId)->latest()->take(6)->get();
                $recentAssignments = Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->latest()->take(6)->with(['schoolClass', 'teacher'])->get();
                $recentResults = Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->latest()->take(6)->with(['student', 'schoolClass'])->get();
                $branchUsers = \DB::table('branch_user')
                    ->where('branch_id', $branchId)
                    ->join('users', 'branch_user.user_id', '=', 'users.id')
                    ->select('users.*', 'branch_user.role')
                    ->latest('users.created_at')
                    ->take(10)
                    ->get();
                
                // Get charts for super admin with branch
                $charts = $this->chartService->getSuperAdminCharts($branchId);
                
                return view('dashboard.roles.super', compact('branch', 'stats', 'recentClasses', 'recentAssignments', 'recentResults', 'branchUsers', 'charts', 'promotionSummary', 'activeAcademicYear', 'activeAcademicTerm', 'academicYears', 'selectedSessionId'));
            }
        }

        // Branch scoped dashboards
        switch ($role) {
            case 'admin':
                $stats = [
                    'classes' => SchoolClass::where('branch_id', $branchId)->count(),
                    'subjects' => Subject::where('branch_id', $branchId)->count(),
                    'teachers' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'teacher')->distinct('user_id')->count('user_id'),
                    'students' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'student')->distinct('user_id')->count('user_id'),
                    'pending_lesson_plans' => \App\Models\LessonPlan::where('branch_id', $branchId)->where('status', 'submitted')->count(),
                    'admissions' => AdmissionApplication::where('branch_id', $branchId)->count(),
                    'gallery_items' => \App\Models\Gallery::where('branch_id', $branchId)->orWhereNull('branch_id')->count(),
                ];
                $recentAssignments = Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->latest()->take(6)->with('schoolClass')->get();
                $recentResults = Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->latest()->take(6)->with(['student','schoolClass'])->get();
                $recentAdmissions = AdmissionApplication::where('branch_id', $branchId)->latest()->take(5)->get();
                
                // Get charts for admin
                $charts = $this->chartService->getAdminCharts($branchId);
                
                return view('dashboard.roles.admin', compact('branch', 'stats', 'recentAssignments', 'recentResults', 'recentAdmissions', 'charts'));

            case 'teacher':
                \Log::info('Rendering teacher dashboard', ['user_id' => $user->id, 'branch_id' => $branchId]);
                $classes = $user->teachingClasses()->withCount('enrollments')->get();
                $myAssignments = Assignment::where('teacher_id', $user->id)->latest()->take(6)->with('schoolClass')->get();
                $attendanceCount = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->count();
                
                // Check if user is assigned as a form teacher
                $formTeacher = \App\Models\FormTeacher::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->with(['schoolClass', 'branch'])
                    ->first();
                
                // Get charts for teacher
                $charts = $this->chartService->getTeacherCharts($branchId, $user->id);
                
                return view('dashboard.roles.teacher', compact('branch', 'classes', 'myAssignments', 'attendanceCount', 'charts', 'formTeacher'));

            case 'form_teacher':
                // Form teachers get access to both teacher and form teacher features
                // Check if user is assigned as a form teacher
                $formTeacher = \App\Models\FormTeacher::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->with(['schoolClass', 'branch'])
                    ->first();
                
                // Always show teacher dashboard, but include form teacher info if available
                $classes = $user->teachingClasses()->withCount('enrollments')->get();
                $myAssignments = Assignment::where('teacher_id', $user->id)->latest()->take(6)->with('schoolClass')->get();
                $attendanceCount = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->count();
                
                // Get charts for teacher
                $charts = $this->chartService->getTeacherCharts($branchId, $user->id);
                
                return view('dashboard.roles.teacher', compact('branch', 'classes', 'myAssignments', 'attendanceCount', 'charts', 'formTeacher'));

            case 'student':
                // Redirect to student dashboard controller
                return redirect()->route('student.dashboard');

            case 'parent':
                // Redirect to parent dashboard controller
                return redirect()->route('parent.dashboard');

            default:
                \Log::warning('No role found, falling back to default dashboard', ['user_id' => $user->id, 'role' => $role]);
                // Fallback to branch overview (previous index)
                $stats = [
                    'branches' => 1,
                    'classes' => SchoolClass::where('branch_id', $branchId)->count(),
                    'teachers' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'teacher')->distinct('user_id')->count('user_id'),
                    'students' => \DB::table('branch_user')->where('branch_id', $branchId)->where('role', 'student')->distinct('user_id')->count('user_id'),
                    'results' => Result::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))->count(),
                    'pending_lesson_plans' => LessonPlan::where('branch_id', $branchId)->where('status', 'submitted')->count(),
                    'admissions' => AdmissionApplication::where('branch_id', $branchId)->count(),
                    'gallery_items' => Gallery::where('branch_id', $branchId)->orWhereNull('branch_id')->count(),
                ];
                $recentClasses = SchoolClass::where('branch_id', $branchId)->latest()->with('branch')->take(5)->with('branch')->get();
                $teacherClasses = $user->teachingClasses()->withCount('enrollments')->get();
                $teacherSummary = [
                    'classCount' => $teacherClasses->count(),
                    'studentTotal' => $teacherClasses->sum('enrollments_count'),
                    'classes' => $teacherClasses,
                ];
                return view('dashboard.index', compact('stats','recentClasses','teacherSummary','isSuper','branch'));
        }
    }
}


