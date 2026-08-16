<?php

namespace App\Http\Controllers;

use App\Models\AnnualSummary;
use App\Models\User;
use App\Models\Result;
use App\Models\Result\Result as GradingResult;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnualResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of students for annual report selection.
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

        // If the user is a student, show them their session history
        if ($user->hasRole('student')) {
            $student = $user;
            $sessions = AcademicYear::whereHas('annualSummaries', function ($q) use ($student) {
                $q->where('student_id', $student->id)->where('is_approved', true);
            })->orderBy('start_date', 'desc')->get();

            return view('result.annual-index', compact('student', 'sessions', 'activeYear'));
        }

        // Start with students in the current branch
        $query = User::whereHas('branches', function ($q) use ($currentBranchId) {
            $q->where('branch_user.branch_id', $currentBranchId)
                ->where('branch_user.role', 'student');
        })
            ->whereHas('enrollments.schoolClass', function ($q) use ($currentBranchId) {
                $q->where('branch_id', $currentBranchId);
            })
            ->with(['enrollments.schoolClass', 'studentProfile']);

        // Filter by specific class if provided
        if ($request->filled('class_id')) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id);
            });
        }

        // For Form Teachers, show only students in their assigned classes
        if ($user->hasRole('form_teacher') && !$user->is_super_admin && !$user->hasRole('admin')) {
            $formTeacherClassIds = $user->formTeacherAssignments()
                ->where('is_active', true)
                ->pluck('school_class_id');

            $query->whereHas('enrollments', function ($q) use ($formTeacherClassIds) {
                $q->whereIn('school_class_id', $formTeacherClassIds);
            });
        }

        $students = $query->with([
            'annualSummaries' => function ($q) use ($selectedYearId) {
                if ($selectedYearId) {
                    $q->where('academic_year_id', $selectedYearId);
                }
            }
        ])->paginate(20)->withQueryString();

        $classes = SchoolClass::where('branch_id', $currentBranchId)->get();

        return view('result.annual-index', compact(
            'students',
            'classes',
            'activeYear',
            'allSessions',
            'selectedYearId',
            'selectedYear'
        ));
    }

    /**
     * Display the annual summary report for a student.
     */
    public function show($studentId, $yearId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        $student = User::with(['studentProfile', 'enrollments.schoolClass'])->findOrFail($studentId);
        $academicYear = AcademicYear::with('terms')->findOrFail($yearId);

        // Strict branch scoping for annual report access
        if (!$user->is_super_admin) {
            $studentInBranch = $student->branches()
                ->where('branches.id', $currentBranchId)
                ->where('branch_user.role', 'student')
                ->exists();
            $yearInBranch = $academicYear->branch_id == $currentBranchId;
            if (!$studentInBranch || !$yearInBranch) {
                abort(403, 'Unauthorized access.');
            }
        }

        // Fetch existing annual summary if any
        $annualSummary = AnnualSummary::where('student_id', $studentId)
            ->where('academic_year_id', $yearId)
            ->first();

        // Security: If student, they can only see their own approved result
        if ($user->hasRole('student')) {
            if ($user->id != $studentId) {
                abort(403, 'Unauthorized access.');
            }
            if (!$annualSummary || !$annualSummary->is_approved) {
                return redirect()->route('student.results.annual.index')->with('error', 'The annual report for ' . $academicYear->name . ' is not yet approved.');
            }
        }

        // Fetch all sessions the student has an annual report in
        $sessionsQuery = AcademicYear::whereHas('annualSummaries', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        });

        if ($user->hasRole('student')) {
            $sessionsQuery->whereHas('annualSummaries', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)->where('is_approved', true);
            });
        }

        $sessions = $sessionsQuery->orderBy('start_date', 'desc')->get();

        // Ensure the year has 3 terms for a summary
        $terms = $academicYear->terms()->orderBy('term_number')->get();

        // Get principal signature from the last term (usually 3rd term)
        $principalSignature = $terms->last()?->principal_signature;

        // Fetch all results for this student in this academic year
        $results = Result::where('student_id', $studentId)
            ->where('branch_id', $currentBranchId)
            ->whereHas('academicTerm', function ($q) use ($yearId) {
                $q->where('academic_year_id', $yearId);
            })
            ->with(['subject', 'academicTerm'])
            ->get();

        $classId = $results->first()?->school_class_id
            ?? $student->enrollments->sortByDesc('id')->first()?->school_class_id;
        $schoolClass = $classId ? SchoolClass::find($classId) : null;

        // Fetch all results for the whole class to calculate positions
        $allClassResults = Result::where('school_class_id', $classId)
            ->where('branch_id', $currentBranchId)
            ->whereHas('academicTerm', function ($q) use ($yearId) {
                $q->where('academic_year_id', $yearId);
            })
            ->get()
            ->groupBy('subject_id');

        $classStudentIds = User::whereHas('enrollments', function ($q) use ($classId) {
            $q->where('school_class_id', $classId);
        })->pluck('id')->toArray();

        $totalStudents = count($classStudentIds);

        // Resolve the grading system from the class name (SS: A1–F9, JS: A1/C/P/F, else A–F)
        // so the annual summary grades subjects the same way the term sheets do.
        $classType = GradingResult::resolveClassType($schoolClass?->name);
        $grader = new GradingResult();

        // Group by subject
        $subjectResults = $results->groupBy('subject_id')->map(function ($group, $subjectId) use ($terms, $allClassResults, $classStudentIds, $classType, $grader) {
            $firstTerm = $group->where('academicTerm.term_number', 1)->first();
            $secondTerm = $group->where('academicTerm.term_number', 2)->first();
            $thirdTerm = $group->where('academicTerm.term_number', 3)->first();

            $t1 = $firstTerm ? $firstTerm->total : 0;
            $t2 = $secondTerm ? $secondTerm->total : 0;
            $t3 = $thirdTerm ? $thirdTerm->total : 0;

            $availableTermsCount = 0;
            if ($firstTerm)
                $availableTermsCount++;
            if ($secondTerm)
                $availableTermsCount++;
            if ($thirdTerm)
                $availableTermsCount++;

            $average = $availableTermsCount > 0 ? ($t1 + $t2 + $t3) / $availableTermsCount : 0;

            // Calculate Position for this subject
            $subjectScores = [];
            $subjectGroup = $allClassResults->get($subjectId);

            if ($subjectGroup) {
                $studentSubjectResults = $subjectGroup->groupBy('student_id');
                foreach ($studentSubjectResults as $sid => $sResults) {
                    $sT1 = $sResults->where('academicTerm.term_number', 1)->first()?->total ?? 0;
                    $sT2 = $sResults->where('academicTerm.term_number', 2)->first()?->total ?? 0;
                    $sT3 = $sResults->where('academicTerm.term_number', 3)->first()?->total ?? 0;

                    $sTermsCount = 0;
                    if ($sResults->where('academicTerm.term_number', 1)->first())
                        $sTermsCount++;
                    if ($sResults->where('academicTerm.term_number', 2)->first())
                        $sTermsCount++;
                    if ($sResults->where('academicTerm.term_number', 3)->first())
                        $sTermsCount++;

                    $sAvg = $sTermsCount > 0 ? ($sT1 + $sT2 + $sT3) / $sTermsCount : 0;
                    $subjectScores[$sid] = $sAvg;
                }
            }

            arsort($subjectScores);
            $ranks = array_keys($subjectScores);
            $myRank = array_search($group->first()->student_id, $ranks);
            $position = ($myRank !== false) ? $this->formatPosition($myRank + 1) : '-';

            $signature = null;
            $editableResultId = null;
            $editableTermLabel = null;
            if ($thirdTerm && $thirdTerm->form_teacher_signature) {
                $signature = $thirdTerm->form_teacher_signature;
            } elseif ($secondTerm && $secondTerm->form_teacher_signature) {
                $signature = $secondTerm->form_teacher_signature;
            } elseif ($firstTerm && $firstTerm->form_teacher_signature) {
                $signature = $firstTerm->form_teacher_signature;
            }

            if ($thirdTerm) {
                $editableResultId = $thirdTerm->id;
                $editableTermLabel = '3rd Term';
            } elseif ($secondTerm) {
                $editableResultId = $secondTerm->id;
                $editableTermLabel = '2nd Term';
            } elseif ($firstTerm) {
                $editableResultId = $firstTerm->id;
                $editableTermLabel = '1st Term';
            }

            // Grade the annual (per-subject) average using the class-aware scale,
            // and derive the remark from that same grade so the two always agree.
            $grade = $grader->determineGrade($average, $classType);

            return [
                'subject' => $group->first()->subject->name,
                'term1' => $t1,
                'term2' => $t2,
                'term3' => $t3,
                'average' => round($average, 2),
                'grade' => $grade,
                'remark' => $grader->determineRemark($grade),
                'signature' => $signature,
                'position' => $position,
                'editable_result_id' => $editableResultId,
                'editable_term_label' => $editableTermLabel,
            ];
        });

        // Overall stats
        $totalAverage = $subjectResults->avg('average') ?: 0;
        $generatedSchoolHeadComment = $annualSummary?->school_head_comment
            ?: AnnualSummary::principalCommentFromScore($totalAverage);

        $mathematicsAverage = $subjectResults->first(function ($res) {
            return str_contains(strtolower($res['subject']), 'mathematics');
        })['average'] ?? 0;
        $englishAverage = $subjectResults->first(function ($res) {
            return str_contains(strtolower($res['subject']), 'english');
        })['average'] ?? 0;
        $computedOutcome = AnnualSummary::resolvePromotionOutcomeFromAverages(
            $totalAverage,
            [
                ['subject' => 'Mathematics', 'average' => $mathematicsAverage],
                ['subject' => 'English', 'average' => $englishAverage],
            ]
        );

        $displayOutcome = AnnualSummary::resolveDisplayOutcome($annualSummary, $computedOutcome);
        $promotionStatus = $displayOutcome['promotion_status'];
        $passStatus = $displayOutcome['pass_status'];

        // Calculate No of Passes and Fails
        // Based on the grading logic, 45 and above is a Pass (D and above)
        $noOfPasses = $subjectResults->filter(function($res) {
            return $res['average'] >= 45;
        })->count();
        
        $noOfFails = $subjectResults->filter(function($res) {
            return $res['average'] < 45;
        })->count();

        // Overall Position Calculation
        $allStudentAverages = [];
        $studentsInClassResults = Result::where('school_class_id', $classId)
            ->where('branch_id', $currentBranchId)
            ->whereHas('academicTerm', function ($q) use ($yearId) {
                $q->where('academic_year_id', $yearId);
            })
            ->get()
            ->groupBy('student_id');

        foreach ($studentsInClassResults as $sid => $sResults) {
            $sSubjectAverages = $sResults->groupBy('subject_id')->map(function ($sGroup) {
                $sT1 = $sGroup->where('academicTerm.term_number', 1)->first()?->total ?? 0;
                $sT2 = $sGroup->where('academicTerm.term_number', 2)->first()?->total ?? 0;
                $sT3 = $sGroup->where('academicTerm.term_number', 3)->first()?->total ?? 0;

                $sTermsCount = 0;
                if ($sGroup->where('academicTerm.term_number', 1)->first())
                    $sTermsCount++;
                if ($sGroup->where('academicTerm.term_number', 2)->first())
                    $sTermsCount++;
                if ($sGroup->where('academicTerm.term_number', 3)->first())
                    $sTermsCount++;

                return $sTermsCount > 0 ? ($sT1 + $sT2 + $sT3) / $sTermsCount : 0;
            });
            $allStudentAverages[$sid] = $sSubjectAverages->avg() ?: 0;
        }

        arsort($allStudentAverages);
        $overallRanks = array_keys($allStudentAverages);
        $myOverallRank = array_search($studentId, $overallRanks);
        $overallPosition = ($myOverallRank !== false) ? $this->formatPosition($myOverallRank + 1) : '-';

        return view('summary-of-annual', compact(
            'student',
            'academicYear',
            'subjectResults',
            'totalAverage',
            'terms',
            'annualSummary',
            'sessions',
            'schoolClass',
            'overallPosition',
            'totalStudents',
            'noOfPasses',
            'noOfFails',
            'generatedSchoolHeadComment',
            'promotionStatus',
            'passStatus',
            'principalSignature'
        ));
    }

    public function printAnnual($studentId, $yearId)
    {
        $showResponse = $this->show($studentId, $yearId);

        if ($showResponse instanceof \Illuminate\Http\RedirectResponse) {
            return $showResponse;
        }

        $data = $showResponse->getData();

        return view('summary-of-annual-print', $data);
    }

    /**
     * Update or create the annual summary assessment.
     */
    public function updateSummary(Request $request, $studentId, $yearId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Role check
        if (!$user->hasRole('form_teacher') && !$user->is_super_admin && !$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        // Strict branch scoping for annual summary mutation
        if (!$user->is_super_admin) {
            $studentInBranch = User::where('id', $studentId)
                ->whereHas('branches', function ($q) use ($currentBranchId) {
                    $q->where('branches.id', $currentBranchId)
                        ->where('branch_user.role', 'student');
                })
                ->exists();
            $yearInBranch = AcademicYear::where('id', $yearId)
                ->where('branch_id', $currentBranchId)
                ->exists();
            if (!$studentInBranch || !$yearInBranch) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
        }

        $existingSummary = AnnualSummary::where('student_id', $studentId)
            ->where('academic_year_id', $yearId)
            ->first();

        // Once approved, only super admin can edit the report.
        if ($existingSummary && $existingSummary->is_approved && !$user->is_super_admin) {
            return response()->json([
                'success' => false,
                'message' => 'This report has been approved. Only super admin can edit it.'
            ], 403);
        }

        $isAdminActor = $user->is_super_admin || $user->hasRole('admin');
        $rules = [
            'form_teacher_comment' => 'nullable|string',
            'form_teacher_signature' => 'nullable|string',
        ];

        if ($isAdminActor) {
            $rules = array_merge($rules, [
                'number_of_times_school_opened' => 'nullable|integer|min:0',
                'school_head_comment' => 'nullable|string',
                'school_head_signature' => 'nullable|string',
                'promotion_status' => 'nullable|in:promoted,not_promoted,resit,promoted_by_trial',
                'pass_status' => 'nullable|in:pass,fail,pending',
            ]);
        }

        if ($isAdminActor) {
            $request->merge([
                'promotion_status' => $request->input('promotion_status') === '' ? null : $request->input('promotion_status'),
                'pass_status' => $request->input('pass_status') === '' ? null : $request->input('pass_status'),
            ]);
        }

        $validated = $request->validate($rules);

        try {
            $payload = [
                'form_teacher_comment' => $validated['form_teacher_comment'] ?? null,
                'date' => now(),
            ];

            if ($isAdminActor) {
                $annualResultsAverage = Result::where('student_id', $studentId)
                    ->where('branch_id', $currentBranchId)
                    ->whereHas('academicTerm', function ($q) use ($yearId) {
                        $q->where('academic_year_id', $yearId);
                    })
                    ->avg('total');

                $subjectAverages = Result::where('student_id', $studentId)
                    ->where('branch_id', $currentBranchId)
                    ->whereHas('academicTerm', function ($q) use ($yearId) {
                        $q->where('academic_year_id', $yearId);
                    })
                    ->with('subject')
                    ->get()
                    ->groupBy('subject_id')
                    ->map(function ($group) {
                        return [
                            'subject' => $group->first()?->subject?->name ?? '',
                            'average' => (float) $group->avg('total'),
                        ];
                    })
                    ->values();

                $mathematicsAverage = $subjectAverages->first(function ($res) {
                    return str_contains(strtolower($res['subject']), 'mathematics');
                })['average'] ?? 0;
                $englishAverage = $subjectAverages->first(function ($res) {
                    return str_contains(strtolower($res['subject']), 'english');
                })['average'] ?? 0;
                $computedOutcome = AnnualSummary::determinePromotionOutcome(
                    $mathematicsAverage >= 45,
                    $englishAverage >= 45,
                    $annualResultsAverage
                );

                $payload['number_of_times_school_opened'] = $validated['number_of_times_school_opened'] ?? null;
                $payload['school_head_comment'] = ($validated['school_head_comment'] ?? null)
                    ?: AnnualSummary::principalCommentFromScore($annualResultsAverage);
                $payload['promotion_status'] = $validated['promotion_status'] ?? $computedOutcome['promotion_status'];
                $payload['pass_status'] = $validated['pass_status'] ?? $computedOutcome['pass_status'];
            }

            $summary = AnnualSummary::updateOrCreate(
                ['student_id' => $studentId, 'academic_year_id' => $yearId],
                $payload
            );

            // Save signatures if provided as base64
            if ($request->filled('form_teacher_signature') && str_contains($validated['form_teacher_signature'], 'data:image/png;base64,')) {
                $summary->form_teacher_signature = $this->saveSignature($validated['form_teacher_signature'], "ft_annual_{$summary->id}");
            }
            if ($isAdminActor && $request->filled('school_head_signature') && str_contains($validated['school_head_signature'], 'data:image/png;base64,')) {
                $globalPrincipalSignaturePath = $this->saveSignature($validated['school_head_signature'], "sh_annual_year_{$yearId}_branch_{$currentBranchId}");

                $studentIdsInBranch = User::whereHas('branches', function ($q) use ($currentBranchId) {
                    $q->where('branches.id', $currentBranchId)
                        ->where('branch_user.role', 'student');
                })->pluck('id');

                // One principal signature applies to all students in this branch + academic session.
                AnnualSummary::where('academic_year_id', $yearId)
                    ->whereIn('student_id', $studentIdsInBranch)
                    ->update(['school_head_signature' => $globalPrincipalSignaturePath]);

                $summary->school_head_signature = $globalPrincipalSignaturePath;
            }

            $summary->save();

            return response()->json(['success' => true, 'message' => 'Annual report updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Approve an annual summary report.
     */
    public function approve($studentId, $yearId)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Only admins can approve results
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        // Strict branch scoping for approval action
        if (!$user->is_super_admin) {
            $studentInBranch = User::where('id', $studentId)
                ->whereHas('branches', function ($q) use ($currentBranchId) {
                    $q->where('branches.id', $currentBranchId)
                        ->where('branch_user.role', 'student');
                })
                ->exists();
            $yearInBranch = AcademicYear::where('id', $yearId)
                ->where('branch_id', $currentBranchId)
                ->exists();
            if (!$studentInBranch || !$yearInBranch) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
        }

        try {
            // Compute promotion/pass status so it is stored on the record
            $currentBranchId = $currentBranchId ?? session('current_branch_id');
            $annualResultsAverage = Result::where('student_id', $studentId)
                ->where('branch_id', $currentBranchId)
                ->whereHas('academicTerm', function ($q) use ($yearId) {
                    $q->where('academic_year_id', $yearId);
                })
                ->avg('total') ?? 0;

            $subjectAverages = Result::where('student_id', $studentId)
                ->where('branch_id', $currentBranchId)
                ->whereHas('academicTerm', function ($q) use ($yearId) {
                    $q->where('academic_year_id', $yearId);
                })
                ->with('subject')
                ->get()
                ->groupBy('subject_id')
                ->map(fn($g) => [
                    'subject' => $g->first()?->subject?->name ?? '',
                    'average' => (float) $g->avg('total'),
                ]);

            $mathAvg = $subjectAverages->first(fn($r) => str_contains(strtolower($r['subject']), 'mathematics'))['average'] ?? 0;
            $engAvg  = $subjectAverages->first(fn($r) => str_contains(strtolower($r['subject']), 'english'))['average'] ?? 0;
            $outcome = AnnualSummary::determinePromotionOutcome($mathAvg >= 45, $engAvg >= 45, $annualResultsAverage);

            $summary = AnnualSummary::firstOrCreate(
                ['student_id' => $studentId, 'academic_year_id' => $yearId],
                [
                    'date'             => now(),
                    'promotion_status' => $outcome['promotion_status'],
                    'pass_status'      => $outcome['pass_status'],
                    'school_head_comment' => AnnualSummary::principalCommentFromScore($annualResultsAverage),
                ]
            );

            $summary->update([
                'is_approved' => true,
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Annual report approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve report: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper to save base64 signature as a file.
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
     * Preview the annual summary report (for development/debugging).
     */
    public function preview()
    {
        // Find a student with at least one result
        $result = Result::first();
        if (!$result) {
            return "No results found to preview.";
        }

        return $this->show($result->student_id, $result->academicTerm->academic_year_id);
    }

    private function formatPosition($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }

    /**
     * Bulk approve annual summary reports.
     */
    public function bulkApprove(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Only admins can approve results
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $sheets = $request->input('sheets');

        if (empty($sheets)) {
            return response()->json(['success' => false, 'message' => 'No reports provided.'], 400);
        }

        try {
            DB::beginTransaction();

            foreach ($sheets as $sheet) {
                $studentId = $sheet['student_id'];
                $yearId = $sheet['year_id'];

                // Strict branch scoping for approval action
                if (!$user->is_super_admin) {
                    $studentInBranch = User::where('id', $studentId)
                        ->whereHas('branches', function ($q) use ($currentBranchId) {
                            $q->where('branches.id', $currentBranchId)
                                ->where('branch_user.role', 'student');
                        })
                        ->exists();
                    $yearInBranch = AcademicYear::where('id', $yearId)
                        ->where('branch_id', $currentBranchId)
                        ->exists();
                    if (!$studentInBranch || !$yearInBranch) {
                        continue; // skip unauthorized records in bulk operations
                    }
                }

                $annualResultsAverage = Result::where('student_id', $studentId)
                    ->where('branch_id', $currentBranchId)
                    ->whereHas('academicTerm', function ($q) use ($yearId) {
                        $q->where('academic_year_id', $yearId);
                    })
                    ->avg('total') ?? 0;

                $subjectAverages = Result::where('student_id', $studentId)
                    ->where('branch_id', $currentBranchId)
                    ->whereHas('academicTerm', function ($q) use ($yearId) {
                        $q->where('academic_year_id', $yearId);
                    })
                    ->with('subject')
                    ->get()
                    ->groupBy('subject_id')
                    ->map(fn($g) => [
                        'subject' => $g->first()?->subject?->name ?? '',
                        'average' => (float) $g->avg('total'),
                    ]);

                $mathAvg = $subjectAverages->first(fn($r) => str_contains(strtolower($r['subject']), 'mathematics'))['average'] ?? 0;
                $engAvg  = $subjectAverages->first(fn($r) => str_contains(strtolower($r['subject']), 'english'))['average'] ?? 0;
                $outcome = AnnualSummary::determinePromotionOutcome($mathAvg >= 45, $engAvg >= 45, $annualResultsAverage);

                $summary = AnnualSummary::firstOrCreate(
                    ['student_id' => $studentId, 'academic_year_id' => $yearId],
                    [
                        'date'             => now(),
                        'promotion_status' => $outcome['promotion_status'],
                        'pass_status'      => $outcome['pass_status'],
                        'school_head_comment' => AnnualSummary::principalCommentFromScore($annualResultsAverage),
                    ]
                );

                $summary->update([
                    'is_approved' => true,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => count($sheets) . ' annual report(s) approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to bulk approve reports: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Bulk disapprove annual summary reports.
     */
    public function bulkDisapprove(Request $request)
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');

        // Only admins can disapprove results
        if (!$user->hasRole('admin') && !$user->is_super_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $sheets = $request->input('sheets');

        if (empty($sheets)) {
            return response()->json(['success' => false, 'message' => 'No reports provided.'], 400);
        }

        try {
            DB::beginTransaction();

            foreach ($sheets as $sheet) {
                $studentId = $sheet['student_id'];
                $yearId = $sheet['year_id'];

                // Strict branch scoping for disapproval action
                if (!$user->is_super_admin) {
                    $studentInBranch = User::where('id', $studentId)
                        ->whereHas('branches', function ($q) use ($currentBranchId) {
                            $q->where('branches.id', $currentBranchId)
                                ->where('branch_user.role', 'student');
                        })
                        ->exists();
                    $yearInBranch = AcademicYear::where('id', $yearId)
                        ->where('branch_id', $currentBranchId)
                        ->exists();
                    if (!$studentInBranch || !$yearInBranch) {
                        continue; // skip unauthorized records
                    }
                }

                AnnualSummary::where('student_id', $studentId)
                    ->where('academic_year_id', $yearId)
                    ->update([
                        'is_approved' => false,
                        'approved_by' => null,
                        'approved_at' => null,
                    ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => count($sheets) . ' annual report(s) disapproved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to bulk disapprove reports: ' . $e->getMessage()], 500);
        }
    }
}
