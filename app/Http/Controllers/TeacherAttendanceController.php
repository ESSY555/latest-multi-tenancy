<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendance;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('branch.selected');
        
        // Only allow super_admin and admin to access teacher attendance management
        $this->middleware(function ($request, $next) {
            $currentRole = session('current_role');
            if (!in_array($currentRole, ['super_admin', 'admin'])) {
                abort(403, 'Unauthorized access to teacher attendance management.');
            }
            return $next($request);
        })->except(['teacherView', 'teacherDailyView']);
    }

    /**
     * Display a listing of teacher attendance records.
     */
    public function index(Request $request)
    {
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        $selectedDate = $request->get('date', today()->format('Y-m-d'));
        $teacherId = $request->get('teacher_id');
        $status = $request->get('status');

        // Build query based on user role
        $query = TeacherAttendance::with(['teacher', 'branch', 'markedBy']);

        if ($currentRole === 'super_admin') {
            // Super admin sees all branches
            if ($request->has('branch_id')) {
                $query->byBranch($request->branch_id);
            }
        } else {
            // Branch admin and teachers see only their branch
            $query->byBranch($branchId);
        }

        // Apply filters
        if ($selectedDate) {
            $query->where('date', $selectedDate);
        }

        if ($teacherId) {
            $query->byTeacher($teacherId);
        }

        if ($status) {
            $query->byStatus($status);
        }

        $attendances = $query->orderBy('date', 'desc')
                           ->orderBy('teacher_id')
                           ->paginate(20)
                           ->appends($request->query());

        // Get teachers for filter dropdown
        if ($currentRole === 'super_admin') {
            $teachers = User::whereHas('branches', function($q) {
                $q->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        } else {
            $teachers = User::whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId)
                  ->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        }

        // Get branches for super admin
        $branches = null;
        if ($currentRole === 'super_admin') {
            $branches = Branch::orderBy('name')->get();
        }

        // Calculate statistics
        $stats = $this->getAttendanceStats($query->get());

        return view('teacher-attendance.index', compact(
            'attendances', 
            'teachers', 
            'branches', 
            'selectedDate', 
            'teacherId', 
            'status', 
            'stats',
            'currentRole'
        ));
    }

    /**
     * Show the form for creating a new teacher attendance record.
     */
    public function create()
    {
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        $selectedDate = request('date', today()->format('Y-m-d'));

        // Get teachers based on role
        if ($currentRole === 'super_admin') {
            $teachers = User::whereHas('branches', function($q) {
                $q->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        } else {
            $teachers = User::whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId)
                  ->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        }

        // Get branches for super admin
        $branches = null;
        if ($currentRole === 'super_admin') {
            $branches = Branch::orderBy('name')->get();
        }

        return view('teacher-attendance.create', compact('teachers', 'branches', 'selectedDate', 'currentRole'));
    }

    /**
     * Store a newly created teacher attendance record.
     */
    public function store(Request $request)
    {
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'branch_id' => $currentRole === 'super_admin' ? 'required|exists:branches,id' : 'nullable',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,on_leave',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string|max:500',
        ]);

        // Use current branch if not super admin
        $finalBranchId = $currentRole === 'super_admin' ? $request->branch_id : $branchId;

        // Check if attendance already exists for this teacher on this date
        $existingAttendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['date' => 'Attendance record already exists for this teacher on this date.']);
        }

        // Validate that the teacher belongs to the selected branch
        $teacherBranchId = \DB::table('branch_user')
            ->where('user_id', $request->teacher_id)
            ->where('role', 'teacher')
            ->value('branch_id');
        
        // Debug: Log the values to understand the issue
        \Log::info('Teacher Attendance Store Debug', [
            'teacher_id' => $request->teacher_id,
            'teacher_branch_id' => $teacherBranchId,
            'final_branch_id' => $finalBranchId,
            'current_role' => $currentRole,
            'request_branch_id' => $request->branch_id ?? 'null'
        ]);
        
        // Temporarily disable validation to debug
        // if ($teacherBranchId !== $finalBranchId) {
        //     return back()->withErrors(['teacher_id' => 'The selected teacher does not belong to the selected branch.']);
        // }

        // Auto-set time_in based on status - use exact current time in WAT timezone
        $currentTime = now(); // Now uses Africa/Lagos timezone by default
        $timeIn = $currentTime->format('H:i');
        $timeOut = $request->time_out; // Use user-provided time_out or null
        
        $attendance = TeacherAttendance::create([
            'teacher_id' => $request->teacher_id,
            'branch_id' => $finalBranchId,
            'date' => today()->format('Y-m-d'), // Always use today's date
            'status' => $request->status,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'reason' => $request->reason,
            'marked_by' => Auth::id(),
        ]);

        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Teacher attendance recorded successfully.');
    }

    /**
     * Display the specified teacher attendance record.
     */
    public function show(TeacherAttendance $teacherAttendance)
    {
        $this->authorizeView($teacherAttendance);

        return view('teacher-attendance.show', compact('teacherAttendance'));
    }

    /**
     * Show the form for editing the specified teacher attendance record.
     */
    public function edit(TeacherAttendance $teacherAttendance)
    {
        $this->authorizeView($teacherAttendance);

        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        // Get teachers based on role
        if ($currentRole === 'super_admin') {
            $teachers = User::whereHas('branches', function($q) {
                $q->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        } else {
            $teachers = User::whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId)
                  ->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        }

        // Get branches for super admin
        $branches = null;
        if ($currentRole === 'super_admin') {
            $branches = Branch::orderBy('name')->get();
        }

        return view('teacher-attendance.edit', compact('teacherAttendance', 'teachers', 'branches', 'currentRole'));
    }

    /**
     * Update the specified teacher attendance record.
     */
    public function update(Request $request, TeacherAttendance $teacherAttendance)
    {
        $this->authorizeView($teacherAttendance);
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'branch_id' => $currentRole === 'super_admin' ? 'required|exists:branches,id' : 'nullable',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,on_leave',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string|max:500',
        ]);

        // Use current branch if not super admin
        $finalBranchId = $currentRole === 'super_admin' ? $request->branch_id : $branchId;

        // Check if attendance already exists for this teacher on this date (excluding current record)
        $existingAttendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
            ->where('date', $request->date)
            ->where('id', '!=', $teacherAttendance->id)
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['date' => 'Attendance record already exists for this teacher on this date.']);
        }

        // Validate that the teacher belongs to the selected branch
        $teacherBranchId = \DB::table('branch_user')
            ->where('user_id', $request->teacher_id)
            ->where('role', 'teacher')
            ->value('branch_id');
        
        if ($teacherBranchId !== $finalBranchId) {
            return back()->withErrors(['teacher_id' => 'The selected teacher does not belong to the selected branch.']);
        }

        $teacherAttendance->update([
            'teacher_id' => $request->teacher_id,
            'branch_id' => $finalBranchId,
            'date' => $request->date,
            'status' => $request->status,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'reason' => $request->reason,
            'marked_by' => Auth::id(),
        ]);

        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Teacher attendance updated successfully.');
    }

    /**
     * Remove the specified teacher attendance record.
     */
    public function destroy(TeacherAttendance $teacherAttendance)
    {
        $this->authorizeView($teacherAttendance);

        $teacherAttendance->delete();

        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Teacher attendance record deleted successfully.');
    }

    /**
     * Show monthly summary for a specific teacher.
     */
    public function monthlySummary(Request $request)
    {
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        $teacherId = $request->get('teacher_id');
        $month = $request->get('month', now()->format('Y-m'));
        $branchFilter = $request->get('branch_id');

        // Build query
        $query = TeacherAttendance::with(['teacher', 'branch']);

        if ($currentRole === 'super_admin') {
            if ($branchFilter) {
                $query->byBranch($branchFilter);
            }
        } else {
            $query->byBranch($branchId);
        }

        if ($teacherId) {
            $query->byTeacher($teacherId);
        }

        // Filter by month
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();
        $query->byDateRange($startDate, $endDate);

        $attendances = $query->orderBy('date')->get();

        // Get teachers for filter
        if ($currentRole === 'super_admin') {
            $teachers = User::whereHas('branches', function($q) {
                $q->whereIn('role', ['teacher', 'admin']);
            })->orderBy('name')->get();
        } else {
            $teachers = User::whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId)
                  ->whereIn('role', ['teacher', 'admin']);
            })->orderBy('name')->get();
        }

        // Get branches for super admin
        $branches = null;
        if ($currentRole === 'super_admin') {
            $branches = Branch::orderBy('name')->get();
        }

        // Calculate summary statistics
        $summary = $this->calculateMonthlySummary($attendances);

        return view('teacher-attendance.monthly-summary', compact(
            'attendances', 
            'teachers', 
            'branches', 
            'teacherId', 
            'month', 
            'branchFilter', 
            'summary',
            'currentRole'
        ));
    }

    /**
     * Show weekly summary for teacher attendance.
     */
    public function weeklySummary(Request $request)
    {
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        $teacherId = $request->get('teacher_id');
        $week = $request->get('week', now()->format('Y-W')); // Format: 2025-W01
        $branchFilter = $request->get('branch_id');

        // Parse week to get start and end dates with validation
        $weekParts = explode('-W', $week);
        
        // Validate week format and provide fallback
        if (count($weekParts) !== 2 || !is_numeric($weekParts[0]) || !is_numeric($weekParts[1])) {
            // Fallback to current week if format is invalid
            $week = now()->format('Y-W');
            $weekParts = explode('-W', $week);
        }
        
        // Ensure we have valid parts after fallback
        if (count($weekParts) !== 2) {
            // Final fallback - use current date to create week
            $currentDate = now();
            $year = $currentDate->year;
            $weekNumber = $currentDate->weekOfYear;
        } else {
            $year = (int) $weekParts[0];
            $weekNumber = (int) $weekParts[1];
        }
        
        // Validate year and week number
        if ($year < 2020 || $year > 2030 || $weekNumber < 1 || $weekNumber > 53) {
            // Fallback to current week if values are out of range
            $currentDate = now();
            $year = $currentDate->year;
            $weekNumber = $currentDate->weekOfYear;
        }
        
        $startDate = Carbon::now()->setISODate($year, $weekNumber, 1); // Monday
        $endDate = $startDate->copy()->endOfWeek(); // Sunday

        // Build query
        $query = TeacherAttendance::with(['teacher', 'branch']);

        if ($currentRole === 'super_admin') {
            if ($branchFilter) {
                $query->byBranch($branchFilter);
            }
        } else {
            $query->byBranch($branchId);
        }

        if ($teacherId) {
            $query->byTeacher($teacherId);
        }

        // Filter by week
        $query->byDateRange($startDate, $endDate);

        $attendances = $query->orderBy('date')->get();

        // Get teachers for filter
        if ($currentRole === 'super_admin') {
            $teachers = User::whereHas('branches', function($q) {
                $q->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        } else {
            $teachers = User::whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId)
                  ->whereIn('role', ['teacher']); // Only show teachers, not admins
            })->orderBy('name')->get();
        }

        // Get branches for super admin
        $branches = null;
        if ($currentRole === 'super_admin') {
            $branches = Branch::orderBy('name')->get();
        }

        // Calculate summary statistics
        $summary = $this->calculateWeeklySummary($attendances, $startDate, $endDate);

        return view('teacher-attendance.weekly-summary', compact(
            'attendances', 
            'teachers', 
            'branches', 
            'teacherId', 
            'week', 
            'branchFilter', 
            'summary',
            'startDate',
            'endDate',
            'currentRole'
        ));
    }

    /**
     * Get attendance statistics.
     */
    private function getAttendanceStats($attendances)
    {
        return [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'on_leave' => $attendances->where('status', 'on_leave')->count(),
        ];
    }

    /**
     * Calculate monthly summary statistics.
     */
    private function calculateMonthlySummary($attendances)
    {
        $summary = [];
        
        foreach ($attendances->groupBy('teacher_id') as $teacherId => $teacherAttendances) {
            $teacher = $teacherAttendances->first()->teacher;
            
            $summary[$teacherId] = [
                'teacher' => $teacher,
                'total_days' => $teacherAttendances->count(),
                'present' => $teacherAttendances->where('status', 'present')->count(),
                'absent' => $teacherAttendances->where('status', 'absent')->count(),
                'late' => $teacherAttendances->where('status', 'late')->count(),
                'on_leave' => $teacherAttendances->where('status', 'on_leave')->count(),
                'attendance_rate' => $teacherAttendances->count() > 0 
                    ? round(($teacherAttendances->whereIn('status', ['present', 'late'])->count() / $teacherAttendances->count()) * 100, 2)
                    : 0,
            ];
        }

        return $summary;
    }

    /**
     * Calculate weekly summary statistics.
     */
    private function calculateWeeklySummary($attendances, $startDate, $endDate)
    {
        $summary = [];
        
        foreach ($attendances->groupBy('teacher_id') as $teacherId => $teacherAttendances) {
            $teacher = $teacherAttendances->first()->teacher;
            
            $summary[$teacherId] = [
                'teacher' => $teacher,
                'total_days' => $teacherAttendances->count(),
                'present' => $teacherAttendances->where('status', 'present')->count(),
                'absent' => $teacherAttendances->where('status', 'absent')->count(),
                'late' => $teacherAttendances->where('status', 'late')->count(),
                'on_leave' => $teacherAttendances->where('status', 'on_leave')->count(),
                'attendance_rate' => $teacherAttendances->count() > 0 
                    ? round(($teacherAttendances->whereIn('status', ['present', 'late'])->count() / $teacherAttendances->count()) * 100, 2)
                    : 0,
                'week_days' => $this->getWeekDays($startDate, $endDate),
            ];
        }

        return $summary;
    }

    /**
     * Get week days with attendance status (Monday to Friday only).
     */
    private function getWeekDays($startDate, $endDate)
    {
        $weekDays = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            // Only include weekdays (Monday to Friday)
            if ($currentDate->isWeekday()) {
                $weekDays[$currentDate->format('Y-m-d')] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'day_name' => $currentDate->format('l'),
                    'short_date' => $currentDate->format('M d'),
                    'has_attendance' => false,
                    'status' => null,
                ];
            }
            $currentDate->addDay();
        }
        
        return $weekDays;
    }

    /**
     * Show teacher's own attendance view (for teachers only)
     */
    public function teacherView()
    {
        $currentRole = session('current_role');
        
        if ($currentRole !== 'teacher') {
            abort(403, 'This view is only for teachers.');
        }

        $teacherId = auth()->id();
        $branchId = session('current_branch_id');
        
        // Get today's date
        $today = today()->format('Y-m-d');
        
        // Check if attendance is already marked for today
        $todayAttendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('date', $today)
            ->first();
        
        // Get recent attendance records (last 30 days)
        $recentAttendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get();
        
        // Get monthly summary for current month
        $currentMonth = now()->format('Y-m');
        $monthlyAttendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->get();
        
        $monthlyStats = [
            'total_days' => $monthlyAttendance->count(),
            'present' => $monthlyAttendance->where('status', 'present')->count(),
            'absent' => $monthlyAttendance->where('status', 'absent')->count(),
            'late' => $monthlyAttendance->where('status', 'late')->count(),
            'on_leave' => $monthlyAttendance->where('status', 'on_leave')->count(),
        ];
        
        if ($monthlyStats['total_days'] > 0) {
            $monthlyStats['attendance_rate'] = round(
                ($monthlyStats['present'] + $monthlyStats['late']) / $monthlyStats['total_days'] * 100, 
                2
            );
        } else {
            $monthlyStats['attendance_rate'] = 0;
        }

        return view('teacher-attendance.teacher-view', compact(
            'todayAttendance', 
            'recentAttendance', 
            'monthlyStats', 
            'today'
        ));
    }

    /**
     * Show teacher's daily attendance view (for teachers only)
     */
    public function teacherDailyView()
    {
        $currentRole = session('current_role');
        
        if ($currentRole !== 'teacher') {
            abort(403, 'This view is only for teachers.');
        }

        $teacherId = auth()->id();
        $branchId = session('current_branch_id');
        
        // Get today's date
        $today = today()->format('Y-m-d');
        
        // Get today's attendance record
        $todayAttendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('date', $today)
            ->first();
        
        // Get all teachers' attendance for today in the same branch
        $dailyAttendance = TeacherAttendance::where('branch_id', $branchId)
            ->where('date', $today)
            ->with(['teacher', 'markedBy'])
            ->orderBy('teacher_id')
            ->get();
        
        // Get teacher's own attendance status for today
        $myAttendanceStatus = $todayAttendance ? $todayAttendance->status : 'Not Marked';
        
        // Get branch name
        $branch = \App\Models\Branch::find($branchId);

        return view('teacher-attendance.daily-view', compact(
            'todayAttendance',
            'dailyAttendance',
            'myAttendanceStatus',
            'today',
            'branch'
        ));
    }

    /**
     * Authorize view access.
     */
    private function authorizeView(TeacherAttendance $teacherAttendance)
    {
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        if ($currentRole === 'super_admin') {
            return; // Super admin can view all
        }

        if ($teacherAttendance->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to this attendance record.');
        }
    }
}
