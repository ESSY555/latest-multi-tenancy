<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Result\Result;
use App\Models\MockExam;
use App\Models\MockResult;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        
        // Super admin can see all teachers across all branches
        if ($user->is_super_admin) {
            $teachers = User::whereHas('branches', function ($q) {
                $q->where('branch_user.role', 'teacher');
            })->with('branches')->orderBy('name')->paginate(20);
        } else {
            // Branch admin can only see teachers in their branch
            $teachers = User::whereHas('branches', function ($q) use ($branchId) {
                $q->where('branches.id', $branchId)->where('branch_user.role', 'teacher');
            })->orderBy('name')->paginate(20);
        }

        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Super admin can assign teachers to any branch
        if ($user->is_super_admin) {
            $branches = Branch::orderBy('name')->get();
        } else {
            // Branch admin can only assign teachers to their own branch
            $branchId = session('current_branch_id');
            $branches = Branch::where('id', $branchId)->get();
        }
        
        return view('teachers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        
        // Validate branch access
        if (!$user->is_super_admin) {
            // Branch admin can only assign teachers to their own branch
            $request->merge(['branch_id' => $branchId]);
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Attach teacher to the selected branch
        $user->branches()->syncWithoutDetaching([$validated['branch_id'] => ['role' => 'teacher']]);

        return redirect()->route('teachers.index')->with('status', 'Teacher created successfully and can now login with the provided credentials.');
    }

    public function scoreSheet(Request $request)
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        $resultType = $request->query('result_type', 'termly');
        
        // 1. Get Teacher's Context
        $subject = null;
        $class = null;
        
        // Try to get class/subject from request or default to teacher's first assignment
        $classId = $request->query('class_id');
        $subjectId = $request->query('subject_id');
        
        if ($classId) {
            $class = \App\Models\SchoolClass::find($classId);
        }
        
        if (!$class) {
            $class = $user->teachingClasses()->first();
        }
        
        if ($subjectId) {
            $subject = \App\Models\Subject::find($subjectId);
        }
        
        if (!$subject) {
            $subject = $user->subjects()->first();
        }
        
        // 2. Get Academic Term
        $currentTerm = \App\Models\AcademicTerm::whereHas('academicYear', function($q) use ($branchId) {
            $q->where('branch_id', $branchId)->where('is_active', true);
        })->current()->first();
        
        if (!$currentTerm) {
            $currentTerm = \App\Models\AcademicTerm::whereHas('academicYear', function($q) use ($branchId) {
                $q->where('branch_id', $branchId)->where('is_active', true);
            })->latest('start_date')->first();
        }
        
        if (!$currentTerm) {
            $currentTerm = \App\Models\AcademicTerm::latest()->first();
        }

        // 3. Get active mock exams for this branch
        $mockExams = MockExam::where('branch_id', $branchId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        // 4. Get students for this class
        $students = [];
        if ($class) {
            $students = StudentProfile::with('user')
                ->whereHas('user.enrollments', function($q) use ($class) {
                    $q->where('school_class_id', $class->id);
                })->get();
        }

        return view('teacher.score-sheet', compact('students', 'subject', 'class', 'currentTerm', 'resultType', 'mockExams'));
    }

    public function saveScores(Request $request)
    {
        $resultType = $request->input('result_type', 'termly');

        $request->validate([
            'student_id' => 'required|array',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:school_classes,id',
            'result_type' => 'required|in:termly,mock',
            'term_id' => 'nullable|exists:academic_terms,id',
            'mock_exam_id' => 'nullable|exists:mock_exams,id',
        ]);

        $user = auth()->user();
        $branchId = session('current_branch_id') ?: 1;
        $classId = $request->class_id;
        $subjectId = $request->subject_id;
        $termId = $request->term_id;
        $mockExamId = $request->mock_exam_id;

        if ($resultType === 'termly' && !$termId) {
            return back()->with('error', 'Academic term is required for termly entry.');
        }

        if ($resultType === 'mock' && !$mockExamId) {
            return back()->with('error', 'Mock exam selection is required for mock entry.');
        }

        // 1. Authorization Check
        $isAuthorizedToEdit = $user->is_super_admin || 
                              $user->hasRole('admin') || 
                              $user->isFormTeacherOfClass($classId);

        $savedCount = 0;
        $lockedStudents = [];

        foreach ($request->student_id as $index => $studentId) {
            // 2. Check for existing record if NOT authorized to edit
            if (!$isAuthorizedToEdit) {
                if ($resultType === 'mock') {
                    $exists = MockResult::where([
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'mock_exam_id' => $mockExamId,
                        'school_class_id' => $classId,
                    ])->exists();
                } else {
                    $exists = Result::where([
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'term_id' => $termId,
                        'school_class_id' => $classId,
                    ])->exists();
                }

                if ($exists) {
                    $student = User::find($studentId);
                    $lockedStudents[] = $student ? $student->name : "ID: $studentId";
                    continue;
                }
            }

            // 3. Save Score
            $ca1 = $request->cat1[$index] ?? 0;
            $ca2 = $request->npw[$index] ?? 0;
            $ca3 = $request->cat2[$index] ?? 0;
            $exam = $request->exam[$index] ?? 0;

            if ($resultType === 'mock') {
                $mockExam = MockExam::find($mockExamId);
                if (!$mockExam) {
                    continue;
                }

                MockResult::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'mock_exam_id' => $mockExamId,
                        'school_class_id' => $classId,
                        'branch_id' => $branchId,
                    ],
                    [
                        'academic_year_id' => $mockExam->academic_year_id,
                        'ca1' => $ca1,
                        'ca2' => $ca2,
                        'ca3' => $ca3,
                        'exam' => $exam,
                        'is_approved' => false,
                    ]
                );
            } else {
                Result::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'term_id' => $termId,
                        'school_class_id' => $classId,
                        'branch_id' => $branchId,
                    ],
                    [
                        'ca1' => $ca1,
                        'ca2' => $ca2,
                        'ca3' => $ca3,
                        'exam' => $exam,
                        'is_approved' => false,
                    ]
                );
            }
            $savedCount++;
        }

        // 4. Return with appropriate feedback
        if (count($lockedStudents) > 0) {
            $msg = "Saved $savedCount scores. Re-entry was blocked for: " . implode(', ', $lockedStudents) . ". Only Form Teachers or Admins can edit existing records.";
            return back()->with('warning', $msg);
        }

        $label = $resultType === 'mock' ? 'mock scores' : 'scores';
        return back()->with('success', "Successfully saved $savedCount {$label}! They are now pending approval.");
    }

    private function calculateGrade($score)
    {
        if ($score >= 80)
            return 'A';
        if ($score >= 70)
            return 'B';
        if ($score >= 60)
            return 'C';
        if ($score >= 50)
            return 'D';
        return 'F';
    }
}


