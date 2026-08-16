<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AnnualSummary;
use App\Models\MockExam;
use App\Models\MockResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MockResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        $hasMockExamTable = Schema::hasTable('mock_exams');
        $hasMockResultTable = Schema::hasTable('mock_results');

        if (!$hasMockResultTable) {
            return view('result.mock-index', [
                'mockExams' => collect(),
                'selectedMockExamId' => null,
                'sheets' => collect(),
            ])->with('error', 'Mock results table is not available yet. Run migrations.');
        }

        $mockExams = $hasMockExamTable
            ? MockExam::where('branch_id', $currentBranchId)
                ->with('academicYear')
                ->orderByDesc('id')
                ->get()
            : collect();

        $selectedMockExamId = $request->filled('mock_exam_id')
            ? (int) $request->input('mock_exam_id')
            : null;

        // Student sees only their approved mock sheets.
        if ($user->hasRole('student')) {
            $sheetsQuery = MockResult::where('student_id', $user->id)
                ->where('branch_id', $currentBranchId)
                ->where('is_approved', true)
                ->when($selectedMockExamId, function ($q) use ($selectedMockExamId) {
                    $q->where('mock_exam_id', $selectedMockExamId);
                })
                ->select(
                    'student_id',
                    'school_class_id',
                    'mock_exam_id',
                    DB::raw('SUM(total) as total_score'),
                    DB::raw('COUNT(*) as total_subjects'),
                    DB::raw('AVG(total) as average_score')
                )
                ->groupBy('student_id', 'school_class_id', 'mock_exam_id');

            $sheetsQuery->with($hasMockExamTable ? ['student', 'schoolClass', 'mockExam'] : ['student', 'schoolClass']);
            $sheets = $sheetsQuery->paginate(20)->withQueryString();
        } else {
            $sheetsQuery = MockResult::where('branch_id', $currentBranchId)
                ->when($selectedMockExamId, function ($q) use ($selectedMockExamId) {
                    $q->where('mock_exam_id', $selectedMockExamId);
                })
                ->select(
                    'student_id',
                    'school_class_id',
                    'mock_exam_id',
                    DB::raw('SUM(total) as total_score'),
                    DB::raw('COUNT(*) as total_subjects'),
                    DB::raw('AVG(total) as average_score'),
                    DB::raw('MIN(is_approved) as is_approved')
                )->groupBy('student_id', 'school_class_id', 'mock_exam_id');

            $sheetsQuery->with($hasMockExamTable ? ['student', 'schoolClass', 'mockExam'] : ['student', 'schoolClass']);
            $sheets = $sheetsQuery->paginate(20)->withQueryString();
        }

        return view('result.mock-index', compact('mockExams', 'selectedMockExamId', 'sheets'));
    }

    public function exams()
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Unauthorized action.');
        }

        $currentBranchId = session('current_branch_id');
        $academicYears = AcademicYear::where('branch_id', $currentBranchId)
            ->orderByDesc('start_date')
            ->get();

        $mockExams = Schema::hasTable('mock_exams')
            ? MockExam::where('branch_id', $currentBranchId)
                ->with('academicYear')
                ->orderByDesc('id')
                ->paginate(20)
                ->withQueryString()
            : collect();

        return view('result.mock-exams', compact('academicYears', 'mockExams'))
            ->with(!Schema::hasTable('mock_exams') ? ['error' => 'Mock exams table is not available yet. Run migrations.'] : []);
    }

    public function storeExam(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (!Schema::hasTable('mock_exams')) {
            return back()->with('error', 'Mock exams table is not available yet. Run migrations.');
        }

        $currentBranchId = session('current_branch_id');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        if (!AcademicYear::where('id', $validated['academic_year_id'])->where('branch_id', $currentBranchId)->exists()) {
            return back()->with('error', 'Invalid academic year for this branch.');
        }

        if ($request->boolean('is_active')) {
            MockExam::where('branch_id', $currentBranchId)->update(['is_active' => false]);
        }

        MockExam::create([
            'name' => $validated['name'],
            'academic_year_id' => $validated['academic_year_id'],
            'branch_id' => $currentBranchId,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Mock exam created successfully.');
    }

    public function toggleExam(MockExam $mockExam)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (!Schema::hasTable('mock_exams')) {
            return back()->with('error', 'Mock exams table is not available yet. Run migrations.');
        }

        $currentBranchId = session('current_branch_id');
        if ((int) $mockExam->branch_id !== (int) $currentBranchId) {
            return back()->with('error', 'Cannot update mock exam outside current branch.');
        }

        $newStatus = !$mockExam->is_active;
        if ($newStatus) {
            MockExam::where('branch_id', $currentBranchId)->update(['is_active' => false]);
        }
        $mockExam->update(['is_active' => $newStatus]);

        return back()->with('success', $newStatus ? 'Mock exam activated.' : 'Mock exam deactivated.');
    }

    public function studentSheet($studentId, $mockExamId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!Schema::hasTable('mock_exams')) {
            abort(404, 'Mock exams table is not available yet.');
        }

        $student = User::with(['studentProfile', 'enrollments.schoolClass'])->findOrFail($studentId);
        $mockExam = MockExam::with('academicYear')->findOrFail($mockExamId);

        $resultsQuery = MockResult::where('student_id', $studentId)
            ->where('mock_exam_id', $mockExamId)
            ->where('branch_id', $currentBranchId)
            ->with(['subject', 'schoolClass', 'mockExam']);

        // Students can only view approved mock results.
        if (!$user->is_super_admin && !$user->hasRole('admin') && !$user->hasRole('form_teacher') && !$user->hasRole('teacher')) {
            if ($user->id !== (int) $studentId) {
                abort(403, 'Unauthorized access.');
            }
            $resultsQuery->where('is_approved', true);
        }

        $results = $resultsQuery->get();

        // Backfill subject positions for legacy/mock records where position was not persisted.
        // Position is per subject within class + mock exam.
        if ($results->contains(fn ($result) => is_null($result->position))) {
            $results
                ->groupBy(fn ($result) => $result->subject_id . ':' . $result->school_class_id . ':' . $result->mock_exam_id)
                ->each(function ($groupedResults) use ($currentBranchId) {
                    $first = $groupedResults->first();
                    if (!$first) {
                        return;
                    }

                    $ranked = MockResult::where('subject_id', $first->subject_id)
                        ->where('school_class_id', $first->school_class_id)
                        ->where('mock_exam_id', $first->mock_exam_id)
                        ->where('branch_id', $currentBranchId)
                        ->orderByDesc('total')
                        ->orderBy('student_id')
                        ->get();

                    $position = 1;
                    foreach ($ranked as $rankedResult) {
                        if ($rankedResult->position !== $position) {
                            MockResult::whereKey($rankedResult->id)->update(['position' => $position]);
                        }
                        $position++;
                    }
                });

            // Reload so the rendered sheet always uses the latest persisted positions.
            $results = $resultsQuery->get();
        }

        $averageScore = $results->avg('total') ?: 0;
        $totalScore = $results->sum('total');
        $totalSubjects = $results->count();
        $firstResult = $results->first();
        $generatedSchoolHeadComment = $firstResult?->school_head_comment
            ?: AnnualSummary::principalCommentFromScore($averageScore);

        $subjectStats = collect();
        if ($firstResult) {
            $classSubjectStats = MockResult::where('branch_id', $currentBranchId)
                ->where('mock_exam_id', $mockExamId)
                ->where('school_class_id', $firstResult->school_class_id)
                ->select(
                    'subject_id',
                    DB::raw('AVG(total) as class_average'),
                    DB::raw('MAX(total) as highest_in_class'),
                    DB::raw('MIN(total) as lowest_in_class')
                )
                ->groupBy('subject_id')
                ->get();

            $subjectStats = $classSubjectStats->keyBy('subject_id');
        }

        return view('result.mock-result', compact(
            'student',
            'mockExam',
            'results',
            'firstResult',
            'averageScore',
            'totalScore',
            'totalSubjects',
            'generatedSchoolHeadComment',
            'subjectStats'
        ));
    }

    public function approveSheet($studentId, $mockExamId)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return back()->with('error', 'Unauthorized action.');
        }

        MockResult::where('student_id', $studentId)
            ->where('mock_exam_id', $mockExamId)
            ->update([
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

        return back()->with('success', 'Mock result sheet approved successfully.');
    }

    public function disapproveSheet($studentId, $mockExamId)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return back()->with('error', 'Unauthorized action.');
        }

        MockResult::where('student_id', $studentId)
            ->where('mock_exam_id', $mockExamId)
            ->update([
                'is_approved' => false,
                'approved_by' => null,
                'approved_at' => null,
            ]);

        return back()->with('success', 'Mock result sheet disapproved successfully.');
    }

    public function bulkApproveSheets(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $sheets = $request->input('sheets');

        if (empty($sheets)) {
            return response()->json([
                'success' => false,
                'message' => 'No sheets provided'
            ], 400);
        }

        try {
            DB::beginTransaction();

            foreach ($sheets as $sheet) {
                MockResult::where('student_id', $sheet['student_id'] ?? null)
                    ->where('mock_exam_id', $sheet['mock_exam_id'] ?? null)
                    ->where('branch_id', $currentBranchId)
                    ->update([
                        'is_approved' => true,
                        'approved_by' => $user->id,
                        'approved_at' => now(),
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($sheets) . ' mock sheet(s) approved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk approve mock sheets: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDisapproveSheets(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $sheets = $request->input('sheets');

        if (empty($sheets)) {
            return response()->json([
                'success' => false,
                'message' => 'No sheets provided'
            ], 400);
        }

        try {
            DB::beginTransaction();

            foreach ($sheets as $sheet) {
                MockResult::where('student_id', $sheet['student_id'] ?? null)
                    ->where('mock_exam_id', $sheet['mock_exam_id'] ?? null)
                    ->where('branch_id', $currentBranchId)
                    ->update([
                        'is_approved' => false,
                        'approved_by' => null,
                        'approved_at' => null,
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($sheets) . ' mock sheet(s) disapproved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk disapprove mock sheets: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addComment(Request $request, MockResult $mockResult)
    {
        $user = Auth::user();
        $isAdminActor = $user->is_super_admin || $user->hasRole('admin');
        if (!$user->hasRole('form_teacher') && !$user->hasRole('admin') && !$user->is_super_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $validated = $request->validate([
            'form_teacher_comment' => 'nullable|string|max:1000',
            'school_head_comment' => 'nullable|string|max:1000',
            'form_teacher_signature' => 'nullable|string',
            'school_head_signature' => 'nullable|string',
            'psychomotor' => 'nullable|array',
            'affective' => 'nullable|array',
            'next_term_begins' => 'nullable|string|max:255',
            'next_term_fees' => 'nullable|string|max:255',
            'attendance_opened' => 'nullable|integer|min:0',
            'attendance_present' => 'nullable|integer|min:0',
        ]);

        try {
            $updateData = [];

            if ($request->has('form_teacher_comment')) {
                $updateData['form_teacher_comment'] = $validated['form_teacher_comment'];
            }
            if ($request->has('school_head_comment')) {
                $currentAverage = MockResult::where('student_id', $mockResult->student_id)
                    ->where('mock_exam_id', $mockResult->mock_exam_id)
                    ->avg('total');

                $updateData['school_head_comment'] = $validated['school_head_comment']
                    ?: AnnualSummary::principalCommentFromScore($currentAverage);
            }
            if ($request->has('psychomotor')) {
                $updateData['psychomotor'] = $validated['psychomotor'];
            }
            if ($request->has('affective')) {
                $updateData['affective'] = $validated['affective'];
            }
            if ($request->has('next_term_begins')) {
                $updateData['next_term_begins'] = $validated['next_term_begins'];
            }
            if ($request->has('next_term_fees')) {
                $updateData['next_term_fees'] = $validated['next_term_fees'];
            }
            if ($request->has('attendance_present')) {
                $updateData['attendance_present'] = $validated['attendance_present'] ?? 0;
            }
            if ($request->has('attendance_opened')) {
                $attendanceOpened = (int) ($validated['attendance_opened'] ?? 0);
                $attendancePresent = $request->has('attendance_present')
                    ? (int) ($validated['attendance_present'] ?? 0)
                    : (int) ($mockResult->attendance_present ?? 0);
                $attendanceLate = (int) ($mockResult->attendance_late ?? 0);

                // "Times school opened" is modeled as present + absent + late.
                $updateData['attendance_absent'] = max($attendanceOpened - $attendancePresent - $attendanceLate, 0);
            }

            if ($request->filled('form_teacher_signature') && str_contains($validated['form_teacher_signature'], 'data:image/png;base64,')) {
                $updateData['form_teacher_signature'] = $this->saveSignature($validated['form_teacher_signature'], 'mock_ft_' . $mockResult->id);
            }
            if ($isAdminActor && $request->filled('school_head_signature') && str_contains($validated['school_head_signature'], 'data:image/png;base64,')) {
                $globalPrincipalSignaturePath = $this->saveSignature($validated['school_head_signature'], 'mock_sh_exam_' . $mockResult->mock_exam_id . '_' . $mockResult->branch_id);

                // One principal signature applies to all students in this branch + mock exam.
                MockResult::where('branch_id', $mockResult->branch_id)
                    ->where('mock_exam_id', $mockResult->mock_exam_id)
                    ->update(['school_head_signature' => $globalPrincipalSignaturePath]);

                $updateData['school_head_signature'] = $globalPrincipalSignaturePath;
            }

            MockResult::where('student_id', $mockResult->student_id)
                ->where('mock_exam_id', $mockResult->mock_exam_id)
                ->update($updateData);

            return response()->json(['success' => true, 'message' => 'Mock result updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update record: ' . $e->getMessage()], 500);
        }
    }

    public function edit(MockResult $mockResult)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Only admin or super admin can edit mock results.');
        }

        if (!$user->is_super_admin && (int) $mockResult->branch_id !== (int) $currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        $mockResult->load(['student', 'subject', 'schoolClass', 'mockExam']);

        return view('result.mock-edit', compact('mockResult'));
    }

    public function update(Request $request, MockResult $mockResult)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Only admin or super admin can update mock results.');
        }

        if (!$user->is_super_admin && (int) $mockResult->branch_id !== (int) $currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'test_score' => 'nullable|numeric|min:0|max:10',
            'practical_score' => 'nullable|numeric|min:0|max:20',
            'exam' => 'nullable|numeric|min:0|max:90',
        ]);

        $mockResult->update([
            'ca1' => $validated['test_score'] ?? 0,
            'ca2' => $validated['practical_score'] ?? 0,
            'ca3' => 0,
            'exam' => $validated['exam'] ?? 0,
        ]);

        return redirect()
            ->route('result.mock-student-sheet', ['studentId' => $mockResult->student_id, 'mockExamId' => $mockResult->mock_exam_id])
            ->with('success', 'Mock subject result updated successfully.');
    }

    public function destroy(MockResult $mockResult)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Only admin or super admin can delete mock results.');
        }

        if (!$user->is_super_admin && (int) $mockResult->branch_id !== (int) $currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        $studentId = $mockResult->student_id;
        $mockExamId = $mockResult->mock_exam_id;
        $mockResult->delete();

        return redirect()
            ->route('result.mock-student-sheet', ['studentId' => $studentId, 'mockExamId' => $mockExamId])
            ->with('success', 'Mock subject result deleted successfully.');
    }

    private function saveSignature($base64Data, $prefix)
    {
        $image = str_replace('data:image/png;base64,', '', $base64Data);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signatures/' . $prefix . '_' . time() . '.png';

        \Illuminate\Support\Facades\Storage::disk('public')->put($imageName, base64_decode($image));

        return $imageName;
    }
}
