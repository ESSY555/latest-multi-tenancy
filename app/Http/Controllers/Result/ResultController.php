<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Models\Result\Result;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicTerm;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\MockExam;
use App\Models\MockResult;
use App\Models\AnnualSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Role-based middleware
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            
            // Allow access based on role
            if ($user->is_super_admin || $user->hasRole('admin') || $user->hasRole('teacher') || $user->hasRole('form_teacher')) {
                return $next($request);
            }
            
            abort(403, 'Unauthorized access to result management.');
        });
    }

    /**
     * Display a listing of results
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Get all academic years for this branch for filtering
        $allSessions = AcademicYear::where('branch_id', $currentBranchId)
            ->orderBy('start_date', 'desc')
            ->get();

        // Determine which academic year we are viewing
        $activeYear = $allSessions->where('is_active', true)->first();
        $selectedYearId = $request->input('academic_year_id', $activeYear?->id);
        $selectedYear = $allSessions->where('id', $selectedYearId)->first() ?: $activeYear;

        if ($user->hasRole('admin') || $user->is_super_admin) {
            $query = Result::with(['student', 'schoolClass', 'academicTerm'])
                ->where('branch_id', $currentBranchId)
                ->select(
                    'student_id',
                    'school_class_id',
                    'term_id',
                    DB::raw('SUM(total) as total_score'),
                    DB::raw('COUNT(*) as total_subjects'),
                    DB::raw('MIN(is_approved) as is_approved'),
                    DB::raw('AVG(total) as average_score'),
                    DB::raw('MAX(updated_at) as last_activity')
                )
                ->groupBy('student_id', 'school_class_id', 'term_id');

            // Apply Academic Year filter
            if ($selectedYearId) {
                $query->whereHas('academicTerm', function($q) use ($selectedYearId) {
                    $q->where('academic_year_id', $selectedYearId);
                });
            }

            if ($request->filled('class_id')) {
                $query->where('school_class_id', $request->class_id);
            }

            if ($request->filled('term_id')) {
                $query->where('term_id', $request->term_id);
            }

            if ($request->filled('status')) {
                if ($request->status === 'approved') {
                    $query->having(DB::raw('MIN(is_approved)'), '=', 1);
                } elseif ($request->status === 'pending') {
                    $query->having(DB::raw('MIN(is_approved)'), '=', 0);
                }
            }

            $results = $query->orderByDesc('last_activity')->paginate(20)->withQueryString();
        } else {
            $query = Result::with(['student', 'schoolClass', 'subject', 'academicTerm'])
                ->where('branch_id', $currentBranchId);

            // Apply Academic Year filter
            if ($selectedYearId) {
                $query->whereHas('academicTerm', function($q) use ($selectedYearId) {
                    $q->where('academic_year_id', $selectedYearId);
                });
            }

            if ($request->filled('class_id')) {
                $query->where('school_class_id', $request->class_id);
            }

            if ($request->filled('term_id')) {
                $query->where('term_id', $request->term_id);
            }

            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }

            if ($request->filled('status')) {
                if ($request->status === 'approved') {
                    $query->approved();
                } elseif ($request->status === 'pending') {
                    $query->pending();
                }
            }

            $teacherSubjects = $user->subjects()->pluck('subjects.id');
            // Class teachers can enter results for any subject in their class (see store()/bulkStore()),
            // so the listing must include those classes too, not just the teacher's own assigned subjects.
            $classTeacherClassIds = SchoolClass::whereHas('teachers', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->pluck('id');

            $query->where(function($q) use ($teacherSubjects, $classTeacherClassIds) {
                $q->whereIn('subject_id', $teacherSubjects);
                if ($classTeacherClassIds->isNotEmpty()) {
                    $q->orWhereIn('school_class_id', $classTeacherClassIds);
                }
            });

            $results = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        }

        // Get filter options
        $classes = SchoolClass::where('branch_id', $currentBranchId)->get();
        $subjects = Subject::where('branch_id', $currentBranchId)->get();
        
        // Get terms for the selected session
        if ($selectedYear) {
            $terms = $selectedYear->terms()->get();
        } else {
            $terms = AcademicTerm::whereHas('academicYear', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })->get();
        }

        // Return different views based on user role
        $viewData = [
            'results' => $results,
            'classes' => $classes,
            'subjects' => $subjects,
            'terms' => $terms,
            'currentAcademicYear' => $selectedYear,
            'allSessions' => $allSessions,
            'selectedYearId' => $selectedYearId
        ];

        if ($user->hasRole('form_teacher')) {
            return view('result.form-teacher-index', $viewData);
        } elseif ($user->hasRole('teacher')) {
            return view('result.teacher-index', $viewData);
        }

        return view('result.index', $viewData);
    }

    /**
     * Show the form for creating a new result
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Get students for the selected class
        $students = collect();
        $subjects = collect();
        $class = null;

        if ($request->filled('class_id')) {
            $class = SchoolClass::findOrFail($request->class_id);
            
            // Get students enrolled in this class
            $studentsQuery = User::whereHas('enrollments', function($query) use ($request) {
                $query->where('school_class_id', $request->class_id)
                      ->where('status', 'active');
            });

            // Get subjects for this class
            $subjectsQuery = $class->subjects();

            if ($user->hasRole('teacher') || $user->hasRole('form_teacher')) {
                $teacherSubjectIds = $user->subjects()->pluck('subjects.id');
                
                // Limit subjects to those taught by the teacher
                $subjectsQuery->whereIn('subjects.id', $teacherSubjectIds);

                // If not a Class Teacher, limit students to those taking their subjects
                $isClassTeacher = $class->teachers()->where('users.id', $user->id)->exists();
                if (!$isClassTeacher) {
                    $studentsQuery->whereHas('studentSubjects', function($q) use ($teacherSubjectIds, $request) {
                        $q->whereIn('subject_id', $teacherSubjectIds)
                          ->where('student_subjects.school_class_id', $request->class_id);
                    });
                }
            }

            $students = $studentsQuery->get();
            $subjects = $subjectsQuery->get();
        }

        // Filter classes for teachers
        $classesQuery = SchoolClass::where('branch_id', $currentBranchId);
        if ($user->hasRole('teacher') || $user->hasRole('form_teacher')) {
            $classesQuery->where(function($query) use ($user) {
                $query->whereHas('teachers', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })->orWhereHas('subjects', function($q) use ($user) {
                    $q->whereHas('teachers', function($sq) use ($user) {
                        $sq->where('users.id', $user->id);
                    });
                });
            });
        }
        $classes = $classesQuery->get();
        
        // Get current academic year and its terms
        $currentAcademicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->where('is_active', true)
            ->first();
        
        if ($currentAcademicYear) {
            $terms = $currentAcademicYear->terms()->get();
        } else {
            $terms = AcademicTerm::whereHas('academicYear', function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })->get();
        }

        $mockExams = MockExam::where('branch_id', $currentBranchId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return view('result.create', compact('students', 'subjects', 'classes', 'terms', 'class', 'currentAcademicYear', 'mockExams'));
    }

    public function getBulkEntryData(Request $request)
    {
        try {
            $request->validate([
                'class_id' => 'required|exists:school_classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'result_type' => 'required|in:termly,mock',
                'term_id' => 'required_if:result_type,termly|nullable|exists:academic_terms,id',
                'mock_exam_id' => 'required_if:result_type,mock|nullable|exists:mock_exams,id',
            ]);

            $currentBranchId = session('current_branch_id');
            $user = Auth::user();
            $class = SchoolClass::findOrFail($request->class_id);

            // Get students enrolled in this class
            $studentsQuery = User::whereHas('enrollments', function($query) use ($request) {
                $query->where('school_class_id', $request->class_id)
                      ->where('status', 'active');
            });

            if ($user->hasRole('teacher') || $user->hasRole('form_teacher')) {
                $teacherSubjectIds = $user->subjects()->pluck('subjects.id');
                // Check if assigned to subject
                if (!$teacherSubjectIds->contains($request->subject_id)) {
                    return response()->json(['success' => false, 'message' => 'Not assigned to this subject.']);
                }
                
                // Limit students to those taking this subject
                $isClassTeacher = $class->teachers()->where('users.id', $user->id)->exists();
                if (!$isClassTeacher) {
                    $studentsQuery->whereHas('studentSubjects', function($q) use ($request) {
                        $q->where('subject_id', $request->subject_id)
                          ->where('student_subjects.school_class_id', $request->class_id);
                    });
                }
            }

            $students = $studentsQuery->select('id', 'name')->orderBy('name')->paginate(20)->withQueryString();

            // Fetch existing results. Looked up by student+subject+term/mock_exam+branch
            // only — matching the DB's actual unique constraint — not school_class_id,
            // since a student's class can change after a result was recorded and the
            // result should still be found (and remain editable-status-aware) either way.
            $studentIds = $students->getCollection()->pluck('id');
            $existingResults = collect();
            if ($request->result_type === 'mock') {
                $existingResults = MockResult::whereIn('student_id', $studentIds)
                    ->where('subject_id', $request->subject_id)
                    ->where('mock_exam_id', $request->mock_exam_id)
                    ->where('branch_id', $currentBranchId)
                    ->get()->keyBy('student_id');
            } else {
                $existingResults = Result::whereIn('student_id', $studentIds)
                    ->where('subject_id', $request->subject_id)
                    ->where('term_id', $request->term_id)
                    ->where('branch_id', $currentBranchId)
                    ->get()->keyBy('student_id');
            }
            
            // Generate data structure
            $data = $students->getCollection()->map(function($student) use ($existingResults, $request, $class) {
                // Fetch student profile admission number
                $profile = \App\Models\StudentProfile::where('user_id', $student->id)->first();
                $admissionNumber = $profile ? $profile->admission_number : 'N/A';
                
                $result = $existingResults->get($student->id);
                return [
                    'student_id' => $student->id,
                    'name' => $student->name,
                    'admission_number' => $admissionNumber,
                    'class_name' => $class->name,
                    'has_result' => $result ? true : false,
                    'result_id' => $result ? $result->id : null,
                    'ca1' => $result ? $result->ca1 : null,
                    'ca2' => $result ? $result->ca2 : null,
                    'ca3' => $result ? $result->ca3 : null,
                    'exam' => $result ? $result->exam : null,
                    'is_approved' => $result ? $result->is_approved : false,
                ];
            });

            $students->setCollection($data);

            return response()->json([
                'success' => true,
                'data' => $students,
                'isAdmin' => ($user->hasRole('admin') || $user->is_super_admin)
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bulk data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkStore(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        $wantsJson = $request->expectsJson();

        try {
            $scoreRules = $request->input('result_type') === 'mock'
                ? [
                    'results.*.ca1' => 'nullable|numeric|min:0|max:10',
                    'results.*.ca2' => 'nullable|numeric|min:0|max:20',
                    'results.*.exam' => 'nullable|numeric|min:0|max:90',
                ]
                : [
                    'results.*.ca1' => 'nullable|numeric|min:0|max:10',
                    'results.*.ca2' => 'nullable|numeric|min:0|max:10',
                    'results.*.ca3' => 'nullable|numeric|min:0|max:10',
                    'results.*.exam' => 'nullable|numeric|min:0|max:70',
                ];

            $validated = $request->validate([
                'class_id' => 'required|exists:school_classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'result_type' => 'required|in:termly,mock',
                'term_id' => 'nullable|exists:academic_terms,id',
                'mock_exam_id' => 'nullable|exists:mock_exams,id',
                'results' => 'required|array',
                'results.*.student_id' => 'required|exists:users,id',
                ...$scoreRules,
                'form_teacher_signature' => 'nullable|string',
            ]);

            // Additional validation for teachers
            if ($user->hasRole('teacher') || $user->hasRole('form_teacher')) {
                $class = SchoolClass::findOrFail($validated['class_id']);
                
                // 1. Check if teacher is assigned to the subject
                $isAssignedToSubject = $user->subjects()->where('subjects.id', $validated['subject_id'])->exists();
                if (!$isAssignedToSubject) {
                    $message = 'You are not assigned to teach this subject.';
                    return $wantsJson
                        ? response()->json(['success' => false, 'message' => $message], 403)
                        : redirect()->back()->withErrors(['error' => $message])->withInput();
                }

                // 2. Check if the subject is part of the class
                $isSubjectInClass = $class->subjects()->where('subjects.id', $validated['subject_id'])->exists();
                if (!$isSubjectInClass) {
                    $message = 'This subject is not offered in the selected class.';
                    return $wantsJson
                        ? response()->json(['success' => false, 'message' => $message], 422)
                        : redirect()->back()->withErrors(['error' => $message])->withInput();
                }
            }

            DB::beginTransaction();

            $isAdmin = $user->hasRole('admin') || $user->is_super_admin;
            $signaturePath = null;
            if ($request->filled('form_teacher_signature') && str_contains($request->form_teacher_signature, 'data:image/png;base64,')) {
                $signaturePath = $this->saveSignature($request->form_teacher_signature, 'ft_bulk_' . time());
            }

            $processedAny = false;
            $lastResult = null;
            $createdCount = 0;
            $updatedCount = 0;
            $lockedCount = 0;

            $scoreFields = ['ca1', 'ca2', 'ca3', 'exam'];

            foreach ($validated['results'] as $studentId => $scores) {
                // Normalize submitted scores. An omitted or blank field means
                // "not entered yet", which we store as null so the teacher can
                // return later and fill it in. A field that already holds a value
                // stays locked (see resolveScoreUpdate()).
                $provided = [];
                foreach ($scoreFields as $field) {
                    $value = $scores[$field] ?? null;
                    $provided[$field] = ($value === null || $value === '') ? null : $value;
                }

                // Mock entry does not use CA3.
                if ($validated['result_type'] === 'mock') {
                    $provided['ca3'] = null;
                }

                // Skip students where nothing at all was entered.
                if ($provided['ca1'] === null && $provided['ca2'] === null && $provided['ca3'] === null && $provided['exam'] === null) {
                    continue;
                }

                if ($validated['result_type'] === 'mock') {
                    $mockExam = MockExam::findOrFail($validated['mock_exam_id']);

                    // Looked up by student+subject+mock_exam+branch (matches the DB's
                    // unique constraint) — not school_class_id, since a student's class
                    // can change after a result was recorded and it should still resolve
                    // to the same row rather than colliding on a duplicate insert.
                    $existing = MockResult::where('student_id', $studentId)
                        ->where('subject_id', $validated['subject_id'])
                        ->where('mock_exam_id', $validated['mock_exam_id'])
                        ->where('branch_id', $currentBranchId)
                        ->first();

                    if ($existing) {
                        $updateData = $this->resolveScoreUpdate($existing, $provided, ['ca1', 'ca2', 'exam'], $isAdmin);
                        if (!empty($updateData)) {
                            $existing->update($updateData);
                            $lastResult = $existing;
                            $processedAny = true;
                            $updatedCount++;
                        } else {
                            $lockedCount++;
                        }
                    } else {
                        $lastResult = MockResult::create([
                            'student_id' => $studentId,
                            'school_class_id' => $validated['class_id'],
                            'subject_id' => $validated['subject_id'],
                            'academic_year_id' => $mockExam->academic_year_id,
                            'mock_exam_id' => $mockExam->id,
                            'branch_id' => $currentBranchId,
                            'ca1' => $provided['ca1'], 'ca2' => $provided['ca2'], 'ca3' => 0, 'exam' => $provided['exam'],
                            'is_approved' => false,
                        ]);
                        $processedAny = true;
                        $createdCount++;
                    }
                } else {
                    // Looked up by student+subject+term+branch (matches the DB's unique
                    // constraint) — not school_class_id, for the same reason as above.
                    $existing = Result::where('student_id', $studentId)
                        ->where('subject_id', $validated['subject_id'])
                        ->where('term_id', $validated['term_id'])
                        ->where('branch_id', $currentBranchId)
                        ->first();

                    if ($existing) {
                        $updateData = $this->resolveScoreUpdate($existing, $provided, ['ca1', 'ca2', 'ca3', 'exam'], $isAdmin);
                        if (!empty($updateData)) {
                            $existing->update($updateData);
                            $lastResult = $existing;
                            $processedAny = true;
                            $updatedCount++;
                        } else {
                            $lockedCount++;
                        }
                    } else {
                        $lastResult = Result::create([
                            'student_id' => $studentId,
                            'school_class_id' => $validated['class_id'],
                            'subject_id' => $validated['subject_id'],
                            'term_id' => $validated['term_id'],
                            'branch_id' => $currentBranchId,
                            'ca1' => $provided['ca1'], 'ca2' => $provided['ca2'], 'ca3' => $provided['ca3'], 'exam' => $provided['exam'],
                            'is_approved' => false,
                        ]);
                        $processedAny = true;
                        $createdCount++;

                        if ($signaturePath) {
                            $lastResult->update(['form_teacher_signature' => $signaturePath]);
                        }
                    }
                }
            }

            if ($processedAny && $lastResult) {
                if ($validated['result_type'] === 'termly') {
                    $this->calculateClassStatistics($lastResult);
                } else {
                    $this->calculateMockPositions($lastResult->subject_id, $lastResult->school_class_id, $lastResult->mock_exam_id, $lastResult->branch_id);
                }
            }

            DB::commit();

            $savedCount = $createdCount + $updatedCount;
            $message = $savedCount > 0
                ? "{$savedCount} student result(s) saved ({$createdCount} new, {$updatedCount} updated)."
                : 'No new scores were saved.';

            if ($lockedCount > 0) {
                $message .= " {$lockedCount} student(s) already had every submitted field recorded — those values are locked and were not changed. An admin can edit locked fields.";
            }

            if ($savedCount === 0 && $lockedCount === 0) {
                $message = 'Nothing to save — no scores were entered.';
            }

            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'created' => $createdCount,
                    'updated' => $updatedCount,
                    'locked' => $lockedCount,
                ]);
            }

            return redirect()->route('result.index')
                ->with($lockedCount > 0 && $savedCount > 0 ? 'warning' : 'success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($wantsJson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BULK_RESULT_STORE_EXCEPTION', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($wantsJson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save bulk results: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Failed to save bulk results: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Decide which score fields may be written when updating an existing result.
     *
     * Teachers may only fill fields that are still empty (null); once a field
     * holds a value it stays locked. Admins may overwrite any field they
     * supplied a value for. Fields left blank in the request are never touched.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $existing
     * @param  array<string, mixed>  $provided  Normalized scores (null = not submitted)
     * @param  array<int, string>  $fields  Score fields eligible for this result type
     */
    private function resolveScoreUpdate($existing, array $provided, array $fields, bool $isAdmin): array
    {
        $updateData = [];
        foreach ($fields as $field) {
            if ($provided[$field] === null) {
                continue; // nothing submitted for this field
            }

            // Teachers can only populate an empty field; admins may overwrite.
            if ($isAdmin || $existing->{$field} === null) {
                $updateData[$field] = $provided[$field];
            }
        }

        return $updateData;
    }

    /**
     * Store a newly created result
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        Log::info('Result creation attempt started.', [
            'user_id' => $user->id,
            'branch_id' => $currentBranchId,
            'request_data' => $request->except(['form_teacher_signature'])
        ]);

        try {
            $scoreRules = $request->input('result_type') === 'mock'
                ? [
                    'ca1' => 'nullable|numeric|min:0|max:10',
                    'ca2' => 'nullable|numeric|min:0|max:20',
                    'ca3' => 'nullable|numeric|min:0|max:0',
                    'exam' => 'nullable|numeric|min:0|max:90',
                ]
                : [
                    'ca1' => 'nullable|numeric|min:0|max:10',
                    'ca2' => 'nullable|numeric|min:0|max:10',
                    'ca3' => 'nullable|numeric|min:0|max:10',
                    'exam' => 'nullable|numeric|min:0|max:70',
                ];

            $validated = $request->validate([
                'student_id' => 'required|exists:users,id',
                'class_id' => 'required|exists:school_classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'result_type' => 'required|in:termly,mock',
                'term_id' => 'nullable|exists:academic_terms,id',
                'mock_exam_id' => 'nullable|exists:mock_exams,id',
                ...$scoreRules,
                'attendance_present' => 'nullable|integer|min:0',
                'attendance_absent' => 'nullable|integer|min:0',
                'attendance_late' => 'nullable|integer|min:0',
                'form_teacher_signature' => 'nullable|string',
            ]);

            Log::info('Validation successful.', ['validated_data' => $validated]);

            if ($validated['result_type'] === 'termly' && empty($validated['term_id'])) {
                return redirect()->back()->withErrors(['error' => 'Academic term is required for termly result.'])->withInput();
            }
            if ($validated['result_type'] === 'mock' && empty($validated['mock_exam_id'])) {
                return redirect()->back()->withErrors(['error' => 'Mock exam is required for mock result.'])->withInput();
            }

            if ($validated['result_type'] === 'mock') {
                // Mock entry uses TEST (ca1), PRACTICAL (ca2), EXAM, with ca3 intentionally unused.
                $validated['ca3'] = 0;
            }

            // Additional validation for teachers
            if ($user->hasRole('teacher') || $user->hasRole('form_teacher')) {
                $class = SchoolClass::findOrFail($validated['class_id']);
                
                // 1. Check if teacher is assigned to the subject
                $isAssignedToSubject = $user->subjects()->where('subjects.id', $validated['subject_id'])->exists();
                if (!$isAssignedToSubject) {
                    Log::warning('Teacher not assigned to subject.', ['user' => $user->id, 'subject' => $validated['subject_id']]);
                    return redirect()->back()->withErrors(['error' => 'You are not assigned to teach this subject.'])->withInput();
                }

                // 2. Check if the subject is part of the class
                $isSubjectInClass = $class->subjects()->where('subjects.id', $validated['subject_id'])->exists();
                if (!$isSubjectInClass) {
                    Log::warning('Subject not in class.', ['class' => $class->id, 'subject' => $validated['subject_id']]);
                    return redirect()->back()->withErrors(['error' => 'This subject is not offered in the selected class.'])->withInput();
                }

                // 3. Check if teacher is assigned to the class or the student takes their subject in this class
                $isClassTeacher = $class->teachers()->where('users.id', $user->id)->exists();
                if (!$isClassTeacher) {
                    $studentTakesSubject = DB::table('student_subjects')
                        ->where('student_id', $validated['student_id'])
                        ->where('subject_id', $validated['subject_id'])
                        ->where('school_class_id', $validated['class_id'])
                        ->exists();
                    if (!$studentTakesSubject) {
                        Log::warning('Student not taking subject in this class.', ['student' => $validated['student_id'], 'subject' => $validated['subject_id']]);
                        return redirect()->back()->withErrors(['error' => 'The selected student is not enrolled in your subject for this class.'])->withInput();
                    }
                }
            }

            // Check if result already exists based on result type.
            if ($validated['result_type'] === 'mock') {
                $existingResult = MockResult::where('student_id', $validated['student_id'])
                    ->where('subject_id', $validated['subject_id'])
                    ->where('mock_exam_id', $validated['mock_exam_id'])
                    ->where('branch_id', $currentBranchId)
                    ->first();
            } else {
                $existingResult = Result::where('student_id', $validated['student_id'])
                    ->where('subject_id', $validated['subject_id'])
                    ->where('term_id', $validated['term_id'])
                    ->where('branch_id', $currentBranchId)
                    ->first();
            }

            if ($existingResult) {
                Log::info('DUPLICATE_RESULT_FOUND', ['student' => $validated['student_id'], 'subject' => $validated['subject_id']]);
                return redirect()->back()
                    ->withErrors(['error' => 'Result already exists for this student, subject, and term.'])
                    ->withInput();
            }

            DB::beginTransaction();

            if ($validated['result_type'] === 'mock') {
                $mockExam = MockExam::findOrFail($validated['mock_exam_id']);
                $result = MockResult::create([
                    'student_id' => $validated['student_id'],
                    'school_class_id' => $validated['class_id'],
                    'subject_id' => $validated['subject_id'],
                    'academic_year_id' => $mockExam->academic_year_id,
                    'mock_exam_id' => $mockExam->id,
                    'branch_id' => $currentBranchId,
                    'ca1' => $validated['ca1'] ?? null,
                    'ca2' => $validated['ca2'] ?? null,
                    'ca3' => $validated['ca3'] ?? null,
                    'exam' => $validated['exam'] ?? null,
                    'attendance_present' => $validated['attendance_present'] ?? 0,
                    'attendance_absent' => $validated['attendance_absent'] ?? 0,
                    'attendance_late' => $validated['attendance_late'] ?? 0,
                    'is_approved' => false,
                ]);

                // Keep subject positions updated for mock results.
                $this->calculateMockPositions(
                    $result->subject_id,
                    $result->school_class_id,
                    $result->mock_exam_id,
                    $result->branch_id
                );
            } else {
                // NOTE: Using school_class_id to match DB column name
                $result = Result::create([
                    'student_id' => $validated['student_id'],
                    'school_class_id' => $validated['class_id'],
                    'subject_id' => $validated['subject_id'],
                    'term_id' => $validated['term_id'],
                    'branch_id' => $currentBranchId,
                    'ca1' => $validated['ca1'] ?? null,
                    'ca2' => $validated['ca2'] ?? null,
                    'ca3' => $validated['ca3'] ?? null,
                    'exam' => $validated['exam'] ?? null,
                    'attendance_present' => $validated['attendance_present'] ?? 0,
                    'attendance_absent' => $validated['attendance_absent'] ?? 0,
                    'attendance_late' => $validated['attendance_late'] ?? 0,
                    'is_approved' => false,
                ]);
            }

            Log::info('Result record created.', ['id' => $result->id, 'data' => $result->toArray()]);

            // Save Form Teacher Signature if provided
            if ($request->filled('form_teacher_signature') && str_contains($request->form_teacher_signature, 'data:image/png;base64,')) {
                $signaturePath = $this->saveSignature($request->form_teacher_signature, 'ft_' . $result->id);
                $result->update(['form_teacher_signature' => $signaturePath]);
                Log::info('Signature saved.', ['path' => $signaturePath]);
            }

            // Calculate class statistics for termly results only.
            if ($validated['result_type'] === 'termly') {
                Log::info('Calculating statistics...');
                $this->calculateClassStatistics($result);
                Log::info('Statistics calculation complete.');
            }

            DB::commit();

            Log::info('TRANSACTION_COMMITTED', ['result_id' => $result->id]);

            return redirect()->route('result.index')
                ->with('success', 'Result created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('VALIDATION_FAILED', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RESULT_STORE_EXCEPTION', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create result: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified result
     */
    public function show(Result $result)
    {
        $result->load(['student', 'schoolClass', 'subject', 'academicTerm', 'approver']);
        return view('result.show', compact('result'));
    }

    /**
     * Show the form for editing the specified result
     */
    public function edit(Result $result)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Only admin or super admin can edit subject results.');
        }

        // Check if user can edit this result
        if (!$user->is_super_admin && $result->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        $students = User::whereHas('enrollments', function($query) use ($result) {
            $query->where('school_class_id', $result->school_class_id)
                  ->where('status', 'active');
        })->get();

        $subjects = Subject::where('branch_id', $currentBranchId)->get();
        $classes = SchoolClass::where('branch_id', $currentBranchId)->get();
        $terms = AcademicTerm::whereHas('academicYear', function($query) use ($currentBranchId) {
            $query->where('branch_id', $currentBranchId);
        })->get();

        return view('result.edit', compact('result', 'students', 'subjects', 'classes', 'terms'));
    }

    /**
     * Update the specified result
     */
    public function update(Request $request, Result $result)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Only admin or super admin can update subject results.');
        }

        // Check if user can update this result
        if (!$user->is_super_admin && $result->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'ca1' => 'nullable|numeric|min:0|max:10',
            'ca2' => 'nullable|numeric|min:0|max:10',
            'ca3' => 'nullable|numeric|min:0|max:10',
            'exam' => 'nullable|numeric|min:0|max:70',
            'attendance_present' => 'nullable|integer|min:0',
            'attendance_absent' => 'nullable|integer|min:0',
            'attendance_late' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $result->update($validated);

            // Recalculate class statistics
            $this->calculateClassStatistics($result);

            DB::commit();

            return redirect()->route('result.index')
                ->with('success', 'Result updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update result: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified result
     */
    public function destroy(Result $result)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Only admin or super admin can delete subject results.');
        }

        // Check if user can delete this result
        if (!$user->is_super_admin && $result->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $result->delete();
            return redirect()->route('result.index')
                ->with('success', 'Result deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete result: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve an entire student result sheet
     */
    public function approveSheet(Request $request, $studentId, $termId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Only admins can approve results
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $updated = Result::where('student_id', $studentId)
                ->where('term_id', $termId)
                ->where('branch_id', $currentBranchId)
                ->update([
                    'is_approved' => true,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);

            if ($updated === 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'No matching result sheet found in your current branch.']);
            }

            return redirect()->back()
                ->with('success', 'Student result sheet approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to approve result sheet: ' . $e->getMessage()]);
        }
    }

    /**
     * Disapprove an entire student result sheet
     */
    public function disapproveSheet(Request $request, $studentId, $termId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Only admins can disapprove results
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $updated = Result::where('student_id', $studentId)
                ->where('term_id', $termId)
                ->where('branch_id', $currentBranchId)
                ->update([
                    'is_approved' => false,
                    'approved_by' => null,
                    'approved_at' => null,
                ]);

            if ($updated === 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'No matching result sheet found in your current branch.']);
            }

            return redirect()->back()
                ->with('success', 'Student result sheet disapproved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to disapprove result sheet: ' . $e->getMessage()]);
        }
    }

    /**
     * Get students for a specific class (API)
     */
    public function getClassStudents(SchoolClass $schoolClass)
    {
        try {
            $user = Auth::user();
            
            // Basic branch check
            if (!$user->is_super_admin && $schoolClass->branch_id != session('current_branch_id')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized branch access.'], 403);
            }

            // Get students enrolled in this class
            $students = User::whereHas('enrollments', function($query) use ($schoolClass) {
                $query->where('school_class_id', $schoolClass->id)
                      ->where('status', 'active');
            })->orderBy('name')->get(['id', 'name', 'email']);

            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subjects for a specific class (API)
     */
    public function getClassSubjects(SchoolClass $schoolClass)
    {
        try {
            $user = Auth::user();
            
            // Basic branch check
            if (!$user->is_super_admin && $schoolClass->branch_id != session('current_branch_id')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized branch access.'], 403);
            }

            $subjectsQuery = $schoolClass->subjects();

            // If teacher, only show subjects they are assigned to
            if ($user->hasRole('teacher') || $user->hasRole('form_teacher')) {
                $teacherSubjectIds = $user->subjects()->pluck('subjects.id');
                $subjectsQuery->whereIn('subjects.id', $teacherSubjectIds);
            }

            $subjects = $subjectsQuery->orderBy('name')->get(['subjects.id', 'subjects.name']);

            // Map id to subjects.id for consistency
            $subjects = $subjects->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name
                ];
            });

            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate class statistics for a result
     */
    private function calculateClassStatistics(Result $result)
    {
        // Get all results for the same subject, class, and term
        $classResults = Result::where('subject_id', $result->subject_id)
            ->where('school_class_id', $result->school_class_id)
            ->where('term_id', $result->term_id)
            ->where('branch_id', $result->branch_id)
            ->get();

        Log::info('Stats calculation data fetched.', ['count' => $classResults->count()]);

        if ($classResults->count() > 0) {
            $highest = $classResults->max('total');
            $lowest = $classResults->min('total');
            $average = $classResults->avg('total');

            // Update all results with class statistics
            Result::where('subject_id', $result->subject_id)
                ->where('school_class_id', $result->school_class_id)
                ->where('term_id', $result->term_id)
                ->where('branch_id', $result->branch_id)
                ->update([
                    'class_highest' => $highest,
                    'class_lowest' => $lowest,
                    'class_average' => round($average, 2),
                ]);

            // Calculate and update positions
            $this->calculatePositions($result->subject_id, $result->school_class_id, $result->term_id, $result->branch_id);
        }
    }

    /**
     * Calculate positions for all students in a subject
     */
    private function calculatePositions($subjectId, $classId, $termId, $branchId)
    {
        $results = Result::where('subject_id', $subjectId)
            ->where('school_class_id', $classId)
            ->where('term_id', $termId)
            ->where('branch_id', $branchId)
            ->orderBy('total', 'desc')
            ->get();

        $position = 1;
        foreach ($results as $result) {
            $result->update(['position' => $position]);
            $position++;
        }
    }

    /**
     * Calculate positions for mock results per subject/class/exam.
     */
    private function calculateMockPositions($subjectId, $classId, $mockExamId, $branchId)
    {
        $results = MockResult::where('subject_id', $subjectId)
            ->where('school_class_id', $classId)
            ->where('mock_exam_id', $mockExamId)
            ->where('branch_id', $branchId)
            ->orderBy('total', 'desc')
            ->orderBy('student_id')
            ->get();

        $position = 1;
        foreach ($results as $result) {
            $result->update(['position' => $position]);
            $position++;
        }
    }

    /**
     * Get student result sheet
     */
    public function studentResultSheet(Request $request, $studentId, $termId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        $student = User::findOrFail($studentId);
        
        // Check if user can view this student's results
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            // Check if user is the form teacher for this student's class
            $isFormTeacher = \App\Models\FormTeacher::where('user_id', $user->id)
                ->where('is_active', true)
                ->whereHas('schoolClass', function($q) use ($studentId) {
                    $q->whereHas('enrollments', function($eq) use ($studentId) {
                        $eq->where('student_id', $studentId);
                    });
                })->exists();

            if (!$isFormTeacher && $user->id != $studentId) {
                abort(403, 'Unauthorized access.');
            }
        }

        $resultsQuery = Result::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('branch_id', $currentBranchId)
            ->with(['subject', 'academicTerm', 'schoolClass']);

        // Only hide unapproved results from students (Staff need to see them to comment/approve)
        if (!$user->is_super_admin && !$user->hasRole('admin') && !$user->hasRole('form_teacher')) {
            $resultsQuery->where('is_approved', true);
        }

        $results = $resultsQuery->get();

        // If results are empty, we still want to show the sheet (header info) for authorized staff
        if ($results->isEmpty()) {
            $term = AcademicTerm::with('academicYear')->findOrFail($termId);
            $totalScore = 0;
            $averageScore = 0;
            $totalSubjects = 0;
        } else {
            $term = $results->first()->academicTerm;
            // Calculate overall statistics
            $totalScore = $results->sum('total');
            $averageScore = $results->avg('total');
            $totalSubjects = $results->count();
        }

        $generatedSchoolHeadComment = $results->first()?->school_head_comment
            ?: AnnualSummary::principalCommentFromScore($averageScore);

        // Resolve class type for grade interpretation display (ss / jss / default)
        $className = $results->first()?->schoolClass?->name
            ?? $student->enrollments->first()?->schoolClass?->name;
        $classType = \App\Models\Result\Result::resolveClassType($className);
        $isSS = ($classType === 'ss'); // backward-compat for any existing view references

        return view('result.student-sheet', compact(
            'student',
            'results',
            'term',
            'totalScore',
            'averageScore',
            'totalSubjects',
            'generatedSchoolHeadComment',
            'isSS',
            'classType'
        ));
    }

    /**
     * Add form teacher comment to result
     */
    public function addComment(Request $request, Result $result)
    {
        $user = Auth::user();
        $isAdminActor = $user->is_super_admin || $user->hasRole('admin');
        
        // Allowed roles: form_teacher, admin, super_admin
        if (!$user->hasRole('form_teacher') && !$user->is_super_admin && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
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
            $isFormTeacherOnly = $user->hasRole('form_teacher') && !$isAdminActor;
            
            if ($request->has('form_teacher_comment')) {
                $updateData['form_teacher_comment'] = $validated['form_teacher_comment'];
            }

            if ($request->has('next_term_begins')) {
                $updateData['next_term_begins'] = $validated['next_term_begins'];
            }

            // Guard against environments where this migration has not been run yet.
            if (Schema::hasColumn('results', 'next_term_fees') && $request->has('next_term_fees')) {
                $updateData['next_term_fees'] = $validated['next_term_fees'];
            }

            if ($request->has('attendance_present')) {
                $updateData['attendance_present'] = $validated['attendance_present'] ?? 0;
            }

            if ($request->has('attendance_opened')) {
                $attendanceOpened = (int) ($validated['attendance_opened'] ?? 0);
                $attendancePresent = $request->has('attendance_present')
                    ? (int) ($validated['attendance_present'] ?? 0)
                    : (int) ($result->attendance_present ?? 0);
                $attendanceLate = (int) ($result->attendance_late ?? 0);

                // "Times school opened" is modeled as present + absent + late.
                $updateData['attendance_absent'] = max($attendanceOpened - $attendancePresent - $attendanceLate, 0);
            }

            if (!$isFormTeacherOnly) {
                if ($request->has('school_head_comment')) {
                    $updateData['school_head_comment'] = $validated['school_head_comment']
                        ?: AnnualSummary::principalCommentFromScore($result->class_average ?? $result->total ?? 0);
                }

                if ($request->has('psychomotor')) {
                    $updateData['psychomotor'] = $validated['psychomotor'];
                }

                if ($request->has('affective')) {
                    $updateData['affective'] = $validated['affective'];
                }
            }

            // Process Form Teacher Signature
            if ($request->filled('form_teacher_signature') && str_contains($validated['form_teacher_signature'], 'data:image/png;base64,')) {
                $updateData['form_teacher_signature'] = $this->saveSignature($validated['form_teacher_signature'], 'ft_' . $result->id);
            }

            // Process School Head Signature
            if ($isAdminActor && $request->filled('school_head_signature') && str_contains($validated['school_head_signature'], 'data:image/png;base64,')) {
                $globalPrincipalSignaturePath = $this->saveSignature($validated['school_head_signature'], 'sh_term_' . $result->term_id . '_' . $result->branch_id);

                // One principal signature applies to all students in this branch + term.
                Result::where('branch_id', $result->branch_id)
                    ->where('term_id', $result->term_id)
                    ->update(['school_head_signature' => $globalPrincipalSignaturePath]);

                $updateData['school_head_signature'] = $globalPrincipalSignaturePath;
            }
            // Also update other results for the same student and term so the sheet is synced
            Result::where('student_id', $result->student_id)
                ->where('term_id', $result->term_id)
                ->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully.',
                'data' => $updateData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to save base64 signature as a file
     */
    private function saveSignature($base64Data, $prefix)
    {
        $image = str_replace('data:image/png;base64,', '', $base64Data);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signatures/' . $prefix . '_' . time() . '.png';
        
        \Illuminate\Support\Facades\Storage::disk('public')->put($imageName, base64_decode($image));
        
        return $imageName;
    }

    /**
     * Bulk approve student sheets
     */
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
                Result::where('student_id', $sheet['student_id'])
                    ->where('term_id', $sheet['term_id'])
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
                'message' => count($sheets) . ' student sheet(s) approved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk approve sheets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk disapprove student sheets
     */
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
                Result::where('student_id', $sheet['student_id'])
                    ->where('term_id', $sheet['term_id'])
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
                'message' => count($sheets) . ' student sheet(s) disapproved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk disapprove sheets: ' . $e->getMessage()
            ], 500);
        }
    }
}
