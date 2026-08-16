<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $branchId = session('current_branch_id');
        $records = Attendance::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->with(['schoolClass', 'student'])
            ->latest('date')->paginate(20);
        return view('attendance.index', compact('records'));
    }

    public function create()
    {
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        
        // Get classes based on user role
        if ($currentRole === 'teacher') {
            // Teachers can only see classes they're assigned to
            $classes = auth()->user()->teachingClasses()
                ->where('branch_id', $branchId)
                ->orderBy('name')
                ->get();
        } else {
            // Admins and super admins can see all classes in the branch
            $classes = SchoolClass::where('branch_id', $branchId)->orderBy('name')->get();
        }
        
        return view('attendance.create', compact('classes'));
    }

    public function getStudents(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id'
        ]);

        $students = Enrollment::where('school_class_id', $request->school_class_id)
            ->with('student')
            ->get()
            ->pluck('student');

        return response()->json($students);
    }

    public function edit(Attendance $attendance)
    {
        // Check if user has permission to edit this attendance record
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        
        if ($attendance->schoolClass->branch_id !== $branchId) {
            abort(403, 'Unauthorized');
        }

        // Additional check for teachers: ensure they can only edit attendance for classes they're assigned to
        if ($currentRole === 'teacher') {
            $teacherClasses = auth()->user()->teachingClasses()
                ->where('branch_id', $branchId)
                ->pluck('school_classes.id')
                ->toArray();
            
            if (!in_array($attendance->school_class_id, $teacherClasses)) {
                abort(403, 'You can only edit attendance for classes you are assigned to.');
            }
        }

        // Get classes based on user role
        if ($currentRole === 'teacher') {
            // Teachers can only see classes they're assigned to
            $classes = auth()->user()->teachingClasses()
                ->where('branch_id', $branchId)
                ->orderBy('name')
                ->get();
        } else {
            // Admins and super admins can see all classes in the branch
            $classes = SchoolClass::where('branch_id', $branchId)->orderBy('name')->get();
        }

        return view('attendance.edit', compact('attendance', 'classes'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        // Check if user has permission to update this attendance record
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        
        if ($attendance->schoolClass->branch_id !== $branchId) {
            abort(403, 'Unauthorized');
        }

        // Additional check for teachers: ensure they can only update attendance for classes they're assigned to
        if ($currentRole === 'teacher') {
            $teacherClasses = auth()->user()->teachingClasses()
                ->where('branch_id', $branchId)
                ->pluck('school_classes.id')
                ->toArray();
            
            if (!in_array($attendance->school_class_id, $teacherClasses)) {
                abort(403, 'You can only update attendance for classes you are assigned to.');
            }
        }

        $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late'],
        ]);

        // Additional validation for teachers: ensure they can only assign to classes they're assigned to
        if ($currentRole === 'teacher') {
            if (!in_array($request->school_class_id, $teacherClasses)) {
                abort(403, 'You can only assign attendance to classes you are assigned to.');
            }
        }

        $attendance->update([
            'school_class_id' => $request->school_class_id,
            'date' => $request->date,
            'status' => $request->status,
        ]);

        return redirect()->route('attendance.index')->with('status', 'Attendance updated successfully');
    }

    public function destroy(Attendance $attendance)
    {
        // Check if user has permission to delete this attendance record
        $branchId = session('current_branch_id');
        if ($attendance->schoolClass->branch_id !== $branchId) {
            abort(403, 'Unauthorized');
        }

        $attendance->delete();

        return redirect()->route('attendance.index')->with('status', 'Attendance record deleted successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'date' => ['required', 'date'],
            'entries' => ['required', 'array'],
        ]);

        foreach ($request->entries as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'school_class_id' => $request->school_class_id,
                    'student_id' => $studentId,
                    'date' => $request->date,
                ],
                ['status' => $status]
            );
        }

        return redirect()->route('attendance.index')->with('status', 'Attendance saved');
    }
}


