<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicExam;
use App\Models\AcademicTerm;
use App\Models\AcademicYear;
use App\Models\MockExam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class ExamTimetableController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildTimetableListingData($request);
        $data['canManage'] = true;

        return view('admin.exam-timetables.index', $data);
    }

    public function browse(Request $request)
    {
        $data = $this->buildTimetableListingData($request);
        $data['canManage'] = false;

        return view('admin.exam-timetables.index', $data);
    }

    private function buildTimetableListingData(Request $request): array
    {
        $currentBranchId = session('current_branch_id');

        $academicYears = AcademicYear::where('branch_id', $currentBranchId)
            ->orderByDesc('start_date')
            ->get();

        $activeYear = $academicYears->firstWhere('is_active', true) ?: $academicYears->first();
        $selectedYearId = $request->integer('academic_year_id') ?: ($activeYear?->id);

        $timetables = AcademicExam::with(['academicYear', 'academicTerm', 'mockExam', 'schoolClass', 'subject', 'teacher'])
            ->where('academic_year_id', $selectedYearId)
            ->whereHas('academicYear', function ($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        $selectedYear = $academicYears->firstWhere('id', $selectedYearId);

        return compact('academicYears', 'selectedYear', 'timetables');
    }

    public function create()
    {
        $currentBranchId = session('current_branch_id');

        $activeYear = AcademicYear::where('branch_id', $currentBranchId)
            ->where('is_active', true)
            ->first();

        if (!$activeYear) {
            $activeYear = AcademicYear::where('branch_id', $currentBranchId)
                ->latest('start_date')
                ->first();
        }

        $terms = $activeYear
            ? $activeYear->terms()->orderBy('term_number')->get()
            : collect();

        $mockExams = $activeYear
            ? MockExam::where('branch_id', $currentBranchId)
                ->where('academic_year_id', $activeYear->id)
                ->orderByDesc('id')
                ->get()
            : collect();

        $teachers = User::whereHas('branches', function ($query) use ($currentBranchId) {
            $query->where('branches.id', $currentBranchId)
                ->where('branch_user.role', 'teacher');
        })->orderBy('name')->get();

        $classes = SchoolClass::where('branch_id', $currentBranchId)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('branch_id', $currentBranchId)
            ->orderBy('name')
            ->get();

        return view('admin.exam-timetables.create', compact(
            'activeYear',
            'terms',
            'mockExams',
            'teachers',
            'classes',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        return $this->saveTimetable($request);
    }

    public function edit($id)
    {
        $currentBranchId = session('current_branch_id');

        $timetable = AcademicExam::where('id', $id)
            ->whereHas('academicYear', function ($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->firstOrFail();

        $activeYear = $timetable->academicYear;

        $terms = $activeYear->terms()->orderBy('term_number')->get();

        $mockExams = MockExam::where('branch_id', $currentBranchId)
            ->where('academic_year_id', $activeYear->id)
            ->orderByDesc('id')
            ->get();

        $teachers = User::whereHas('branches', function ($query) use ($currentBranchId) {
            $query->where('branches.id', $currentBranchId)
                ->where('branch_user.role', 'teacher');
        })->orderBy('name')->get();

        $classes = SchoolClass::where('branch_id', $currentBranchId)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('branch_id', $currentBranchId)
            ->orderBy('name')
            ->get();

        return view('admin.exam-timetables.edit', compact(
            'timetable',
            'activeYear',
            'terms',
            'mockExams',
            'teachers',
            'classes',
            'subjects'
        ));
    }

    public function update(Request $request, $id)
    {
        $currentBranchId = session('current_branch_id');

        $timetable = AcademicExam::where('id', $id)
            ->whereHas('academicYear', function ($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->firstOrFail();

        return $this->saveTimetable($request, $timetable);
    }

    private function saveTimetable(Request $request, ?AcademicExam $timetable = null)
    {
        $currentBranchId = session('current_branch_id');

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'exam_scope' => 'required|in:term,mock',
            'term' => 'nullable|required_if:exam_scope,term|exists:academic_terms,id',
            'mock_exam_id' => 'nullable|required_if:exam_scope,mock|exists:mock_exams,id',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'class' => 'required|exists:school_classes,id',
            'subject' => 'nullable|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'venue' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $academicYear = AcademicYear::where('id', $validated['academic_year_id'])
            ->where('branch_id', $currentBranchId)
            ->firstOrFail();

        if ($validated['date'] < (string) $academicYear->start_date || $validated['date'] > (string) $academicYear->end_date) {
            return back()->withErrors(['date' => 'Exam date must be within the selected academic section dates.'])->withInput();
        }

        $classInBranch = SchoolClass::where('id', $validated['class'])
            ->where('branch_id', $currentBranchId)
            ->exists();
        if (!$classInBranch) {
            return back()->withErrors(['class' => 'Selected class does not belong to this branch.'])->withInput();
        }

        if (!empty($validated['subject'])) {
            $subjectInBranch = Subject::where('id', $validated['subject'])
                ->where('branch_id', $currentBranchId)
                ->exists();
            if (!$subjectInBranch) {
                return back()->withErrors(['subject' => 'Selected subject does not belong to this branch.'])->withInput();
            }
        }

        $teacherInBranch = User::where('id', $validated['teacher_id'])
            ->whereHas('branches', function ($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId)->where('branch_user.role', 'teacher');
            })
            ->exists();
        if (!$teacherInBranch) {
            return back()->withErrors(['teacher_id' => 'Selected teacher does not belong to this branch.'])->withInput();
        }

        if ($validated['exam_scope'] === 'term') {
            $termInYear = AcademicTerm::where('id', $validated['term'])
                ->where('academic_year_id', $academicYear->id)
                ->exists();
            if (!$termInYear) {
                return back()->withErrors(['term' => 'Selected term does not belong to this section.'])->withInput();
            }
        }

        if ($validated['exam_scope'] === 'mock') {
            $mockInYear = MockExam::where('id', $validated['mock_exam_id'])
                ->where('branch_id', $currentBranchId)
                ->where('academic_year_id', $academicYear->id)
                ->exists();
            if (!$mockInYear) {
                return back()->withErrors(['mock_exam_id' => 'Selected mock exam does not belong to this section.'])->withInput();
            }
        }

        $payload = [
            'academic_year_id' => $academicYear->id,
            'academic_term_id' => $validated['exam_scope'] === 'term' ? $validated['term'] : null,
            'mock_exam_id' => $validated['exam_scope'] === 'mock' ? $validated['mock_exam_id'] : null,
            'exam_scope' => $validated['exam_scope'],
            'title' => 'Exam Timetable',
            'description' => $validated['notes'] ?? null,
            'exam_type' => 'written',
            'exam_date' => $validated['date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'total_marks' => 100,
            'passing_marks' => 40,
            'subject_id' => $validated['subject'] ?? null,
            'school_class_id' => $validated['class'],
            'teacher_id' => $validated['teacher_id'],
            'location' => $validated['venue'] ?? null,
            'instructions' => $validated['notes'] ?? null,
            'is_published' => true,
            'is_online' => false,
        ];

        if ($timetable) {
            $timetable->update($payload);
            $message = 'Exam timetable updated successfully.';
        } else {
            $createdExam = AcademicExam::create($payload);

            $recipientIds = User::whereHas('branches', function ($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId)
                    ->whereIn('branch_user.role', ['admin', 'teacher', 'student']);
            })->pluck('id')->toArray();

            Notification::createExamTimetableNotification(
                $createdExam->load(['academicYear', 'academicTerm', 'mockExam', 'schoolClass', 'subject']),
                $recipientIds
            );
            $message = 'Exam timetable created successfully.';
        }

        return redirect()->route('admin.exam-timetables.index', ['academic_year_id' => $academicYear->id])
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $currentBranchId = session('current_branch_id');

        $exam = AcademicExam::where('id', $id)
            ->whereHas('academicYear', function ($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->firstOrFail();

        $exam->delete();

        return back()->with('success', 'Exam timetable deleted successfully.');
    }
}

