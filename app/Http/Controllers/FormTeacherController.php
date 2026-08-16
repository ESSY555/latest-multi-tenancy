<?php

namespace App\Http\Controllers;

use App\Models\FormTeacher;
use App\Models\SchoolClass;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Result\Result;
use App\Models\StudentRemark;
use App\Models\ClassAnnouncement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FormTeacherController extends Controller
{
    public function index()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass', 'branch'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $class = $formTeacher->schoolClass;
        $branch = $formTeacher->branch;
        $students = $class->enrollments()->with('student')->get();
        
        // Get today's attendance
        $todayAttendance = Attendance::where('school_class_id', $class->id)
            ->where('date', Carbon::today())
            ->get();

        // Get recent assignments
        $recentAssignments = Assignment::where('school_class_id', $class->id)
            ->with('submissions')
            ->latest()
            ->take(5)
            ->get();

        // Get recent remarks
        $recentRemarks = StudentRemark::where('school_class_id', $class->id)
            ->with('student')
            ->latest()
            ->take(5)
            ->get();

        return view('form-teacher.dashboard', compact(
            'formTeacher',
            'class',
            'branch',
            'students',
            'todayAttendance',
            'recentAssignments',
            'recentRemarks'
        ));
    }

    public function students()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $students = $formTeacher->schoolClass->enrollments()
            ->with(['student' => function($query) {
                $query->withCount('remarks');
            }])
            ->paginate(20)
            ->withQueryString();

        return view('form-teacher.students.index', compact('formTeacher', 'students'));
    }

    public function studentShow($studentId)
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $student = User::findOrFail($studentId);
        
        // Check if student is in form teacher's class
        $enrollment = $student->enrollments()
            ->where('school_class_id', $formTeacher->schoolClass->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('form-teacher.students')->with('error', 'Student not found in your class.');
        }

        // Get student's attendance
        $attendance = Attendance::where('student_id', $studentId)
            ->where('school_class_id', $formTeacher->school_class_id)
            ->orderBy('date', 'desc')
            ->get();

        // Get student's assignments
        $assignments = Assignment::where('school_class_id', $formTeacher->school_class_id)
            ->with(['submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->get();

        // Get student's results
        $results = Result::where('student_id', $studentId)
            ->where('school_class_id', $formTeacher->school_class_id)
            ->with(['subject', 'academicTerm', 'schoolClass'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student's remarks
        $remarks = StudentRemark::where('student_id', $studentId)
            ->where('school_class_id', $formTeacher->school_class_id)
            ->orderBy('date', 'desc')
            ->get();

        // Get student's recent activities (last 30 days)
        $recentActivities = collect();
        
        // Add attendance records
        $recentAttendance = $attendance->where('date', '>=', now()->subDays(30));
        $recentActivities = $recentActivities->merge($recentAttendance->map(function($item) {
            return [
                'type' => 'attendance',
                'date' => $item->date,
                'title' => 'Attendance: ' . ucfirst($item->status),
                'description' => $item->notes ?? 'No notes',
                'status' => $item->status
            ];
        }));

        // Add assignment submissions
        $recentSubmissions = collect();
        foreach($assignments as $assignment) {
            if($assignment->submissions->count() > 0) {
                $submission = $assignment->submissions->first();
                $recentSubmissions->push([
                    'type' => 'assignment',
                    'date' => $submission->created_at,
                    'title' => 'Assignment: ' . $assignment->title,
                    'description' => 'Submitted on ' . $submission->created_at->format('M d, Y'),
                    'status' => 'submitted'
                ]);
            }
        }
        $recentActivities = $recentActivities->merge($recentSubmissions);

        // Add remarks
        $recentRemarks = $remarks->where('date', '>=', now()->subDays(30))->map(function($item) {
            return [
                'type' => 'remark',
                'date' => $item->date,
                'title' => ucfirst($item->type) . ' Remark',
                'description' => $item->remark,
                'status' => $item->type
            ];
        });
        $recentActivities = $recentActivities->merge($recentRemarks);

        // Sort by date
        $recentActivities = $recentActivities->sortByDesc('date')->take(10);

        return view('form-teacher.students.show', compact(
            'formTeacher',
            'student',
            'attendance',
            'assignments',
            'results',
            'remarks',
            'recentActivities'
        ));
    }

    public function attendance()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $class = $formTeacher->schoolClass;
        // Build enrollments query for display (with optional student name filter)
        $enrollmentsQuery = $class->enrollments()->with('student');
        
        // Get search parameters
        $date = request('date', Carbon::today()->format('Y-m-d'));
        $studentName = request('student_name');
        $selectedDate = Carbon::parse($date);
        
        // Build attendance query based on search parameters
        $attendanceQuery = Attendance::where('school_class_id', $class->id)
            ->where('date', $date)
            ->with('student');
            
        // If student name is provided, filter by student name
        if ($studentName) {
            $attendanceQuery->whereHas('student', function($query) use ($studentName) {
                $query->where('name', 'like', '%' . $studentName . '%');
            });
            $enrollmentsQuery->whereHas('student', function($query) use ($studentName) {
                $query->where('name', 'like', '%' . $studentName . '%');
            });
        }
        
        $attendance = $attendanceQuery->get()->keyBy('student_id');

        // Check if attendance has been recorded for today
        $todayAttendanceRecorded = Attendance::where('school_class_id', $class->id)
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->exists();

        // Get recent attendance history (last 7 days)
        $recentAttendance = Attendance::where('school_class_id', $class->id)
            ->where('date', '>=', Carbon::today()->subDays(7))
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');

        // Paginate students for display
        $displayStudents = $enrollmentsQuery
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->appends(['date' => $date, 'student_name' => $studentName]);

        return view('form-teacher.attendance.index', compact(
            'formTeacher', 
            'class', 
            'students',
            'displayStudents',
            'attendance', 
            'date', 
            'studentName',
            'selectedDate',
            'todayAttendanceRecorded',
            'recentAttendance'
        ));
    }

    public function storeAttendance(Request $request)
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:users,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
        ]);

        $date = $request->date;
        $classId = $formTeacher->school_class_id;

        // Delete existing attendance for this date and class
        Attendance::where('school_class_id', $classId)
            ->where('date', $date)
            ->delete();

        // Create new attendance records
        foreach ($request->attendance as $record) {
            Attendance::create([
                'school_class_id' => $classId,
                'student_id' => $record['student_id'],
                'teacher_id' => Auth::id(),
                'date' => $date,
                'status' => $record['status'],
            ]);
        }

        return redirect()->route('form-teacher.attendance')
            ->with('success', 'Attendance recorded successfully for ' . $date);
    }

    public function assignments()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $assignments = Assignment::where('school_class_id', $formTeacher->school_class_id)
            ->with(['submissions', 'teacher'])
            ->latest()
            ->paginate(20);

        return view('form-teacher.assignments.index', compact('formTeacher', 'assignments'));
    }

    public function remarks()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $remarks = StudentRemark::where('school_class_id', $formTeacher->school_class_id)
            ->with(['student', 'formTeacher'])
            ->latest()
            ->paginate(20);

        return view('form-teacher.remarks.index', compact('formTeacher', 'remarks'));
    }

    public function createRemark()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $students = $formTeacher->schoolClass->enrollments()
            ->with('student')
            ->get();

        return view('form-teacher.remarks.create', compact('formTeacher', 'students'));
    }

    public function storeRemark(Request $request)
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'type' => 'required|in:academic,behavioral,general',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'severity' => 'required|in:positive,neutral,concern,serious',
            'is_private' => 'boolean',
            'date' => 'required|date',
        ]);

        // Verify student is in form teacher's class
        $enrollment = Enrollment::where('student_id', $request->student_id)
            ->where('school_class_id', $formTeacher->school_class_id)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'Student not found in your class.');
        }

        $remark = new StudentRemark();
        $remark->fill([
            'student_id' => $request->student_id,
            'form_teacher_id' => Auth::id(),
            'school_class_id' => $formTeacher->school_class_id,
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->input('content'),
            'severity' => $request->severity,
            'is_private' => $request->boolean('is_private'),
            'date' => $request->date,
        ]);
        $remark->save();

        return redirect()->route('form-teacher.remarks')
            ->with('success', 'Student remark added successfully.');
    }

    public function announcements()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $announcements = ClassAnnouncement::where('form_teacher_id', Auth::id())
            ->where('school_class_id', $formTeacher->school_class_id)
            ->latest()
            ->paginate(20);

        return view('form-teacher.announcements.index', compact('formTeacher', 'announcements'));
    }

    public function createAnnouncement()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        return view('form-teacher.announcements.create', compact('formTeacher'));
    }

    public function storeAnnouncement(Request $request)
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $announcement = new ClassAnnouncement();
        $announcement->fill([
            'form_teacher_id' => Auth::id(),
            'school_class_id' => $formTeacher->school_class_id,
            'branch_id' => $formTeacher->branch_id,
            'title' => $request->title,
            'content' => $request->input('content'),
            'priority' => $request->priority,
            'is_published' => true,
            'published_at' => now(),
            'expiry_date' => $request->expiry_date,
        ]);
        $announcement->save();

        return redirect()->route('form-teacher.announcements')
            ->with('success', 'Announcement created successfully.');
    }



    public function reports()
    {
        $formTeacher = FormTeacher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $class = $formTeacher->schoolClass;
        $students = $class->enrollments()->with('student')->get();

        // Calculate attendance statistics
        $attendanceStats = [];
        foreach ($students as $enrollment) {
            $totalDays = Attendance::where('student_id', $enrollment->student_id)
                ->where('school_class_id', $class->id)
                ->count();
            
            $presentDays = Attendance::where('student_id', $enrollment->student_id)
                ->where('school_class_id', $class->id)
                ->where('status', 'present')
                ->count();

            $attendanceStats[$enrollment->student_id] = [
                'total' => $totalDays,
                'present' => $presentDays,
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0
            ];
        }

        return view('form-teacher.reports.index', compact('formTeacher', 'class', 'students', 'attendanceStats'));
    }

    public function reportCards(Request $request)
    {
        $formTeacher = \App\Models\FormTeacher::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('is_active', true)
            ->with(['schoolClass', 'branch'])
            ->first();

        if (!$formTeacher) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned as a form teacher.');
        }

        $class = $formTeacher->schoolClass;
        $branch = $formTeacher->branch;
        
        // 1. Get all Academic Years for the branch
        $allYears = \App\Models\AcademicYear::where('branch_id', $branch->id)
            ->orderBy('start_date', 'desc')
            ->get();
            
        // 2. Determine Selected Year
        $selectedYearId = $request->query('year_id');
        $selectedYear = null;
        
        if ($selectedYearId) {
            $selectedYear = $allYears->find($selectedYearId);
        }
        
        if (!$selectedYear) {
            $selectedYear = $allYears->where('is_active', true)->first() ?: $allYears->first();
        }

        // 3. Get all Terms for the selected year
        $allTerms = $selectedYear ? $selectedYear->terms()->orderBy('start_date', 'asc')->get() : collect();
        
        // 4. Determine Selected Term
        $selectedTermId = $request->query('term_id');
        $currentTerm = null;
        
        if ($selectedTermId) {
            $currentTerm = $allTerms->find($selectedTermId);
        }
        
        if (!$currentTerm && $selectedYear) {
            // Priority: Date matching -> then first term
            $currentTerm = $selectedYear->getCurrentTerm() ?: $allTerms->first();
        }

        $enrollmentsQuery = $class->enrollments()
            ->with(['student' => function($q) use ($currentTerm) {
                if ($currentTerm) {
                    $q->withCount(['results' => function($sq) use ($currentTerm) {
                        $sq->where('term_id', $currentTerm->id);
                    }]);
                }
            }, 'student.studentProfile']);

        // When a term is selected, show only students that have records in that term.
        if ($currentTerm) {
            $enrollmentsQuery->whereHas('student.results', function ($q) use ($currentTerm) {
                $q->where('term_id', $currentTerm->id);
            });
        }

        $enrollments = $enrollmentsQuery->get();

        return view('form-teacher.report-cards.index', compact(
            'formTeacher',
            'class',
            'branch',
            'enrollments',
            'currentTerm',
            'allYears',
            'allTerms',
            'selectedYear'
        ));
    }
}
