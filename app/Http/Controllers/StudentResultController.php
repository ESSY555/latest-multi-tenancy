<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result;
use App\Models\StudentProfile;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use PDF;

class StudentResultController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can view results
        $this->middleware('auth');
    }

    public function viewResults(Request $request)
    {
        $user = auth()->user();

        // Get the student profile for the authenticated user
        $student = StudentProfile::where('user_id', $user->id)->first();

        if (!$student) {
            abort(403, 'Access denied. You are not registered as a student.');
        }

        // Get all results for this student to find academic years and terms
        $allResults = Result::where('student_id', $user->id)
            ->with(['academicTerm.academicYear', 'subject'])
            ->get();

        // Get distinct academic years from student's results
        $academicYears = $allResults
            ->map(function ($result) {
                return $result->academicTerm->academicYear;
            })
            ->unique(function ($year) {
                return $year->id;
            })
            ->sortByDesc('created_at')
            ->values();

        // If no academic years found for student, get all academic years from system
        if ($academicYears->isEmpty()) {
            $academicYears = AcademicYear::where('branch_id', session('current_branch_id'))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Get selected academic year (default to most recent)
        $selectedAcademicYearId = $request->input('academic_year_id')
            ? (int) $request->input('academic_year_id')
            : $academicYears->first()?->id;

        $selectedAcademicYear = null;
        $terms = collect();

        if ($selectedAcademicYearId) {
            $selectedAcademicYear = AcademicYear::find($selectedAcademicYearId);

            // Get terms for this academic year from student's results
            $terms = $allResults
                ->filter(function ($result) use ($selectedAcademicYearId) {
                    return $result->academicTerm->academic_year_id == $selectedAcademicYearId;
                })
                ->map(function ($result) {
                    return $result->academicTerm;
                })
                ->unique(function ($term) {
                    return $term->id;
                })
                ->values();

            // If no terms found for student in this year, get all terms from year
            if ($terms->isEmpty() && $selectedAcademicYear) {
                $terms = $selectedAcademicYear->terms()->get();
            }
        }

        // Get selected term (default to most recent)
        $selectedTermId = $request->input('term_id')
            ? (int) $request->input('term_id')
            : $terms->first()?->id;

        // Get only APPROVED results for this authenticated student
        $query = Result::where('student_id', $user->id)->approved();

        if ($selectedAcademicYearId) {
            $query->whereHas('academicTerm', function ($q) use ($selectedAcademicYearId) {
                $q->where('academic_year_id', $selectedAcademicYearId);
            });
        }

        if ($selectedTermId) {
            $query->where('term_id', $selectedTermId);
        }

        $results = $query->with(['schoolClass', 'subject', 'academicTerm.academicYear'])->orderBy('subject_id')->get();

        // Calculate overall statistics for the result sheet template
        $totalScore = $results->sum('total');
        $averageScore = $results->avg('total') ?? 0;
        $totalSubjects = $results->count();

        // Resolve class type for grade interpretation display (ss / js / default)
        $className = $results->first()?->schoolClass?->name
            ?? StudentProfile::where('user_id', $user->id)->first()?->schoolClass?->name;
        $classType = \App\Models\Result\Result::resolveClassType($className);
        
        // Get the selected term model
        $term = null;
        if ($selectedTermId) {
            $term = \App\Models\AcademicTerm::with('academicYear')->find($selectedTermId);
        } elseif ($selectedAcademicYear) {
            // Fallback to first term if none selected but year is
            $term = $terms->first();
        }

        // Check if there are any pending (unapproved) results
        $hasPendingResults = Result::where('student_id', $user->id)
            ->pending()
            ->exists();

        // We pass the fresh User model as $student so profile_photo and details are always up-to-date
        $student = User::find($user->id) ?? $user;

        return view('student.results', compact(
            'student', 
            'results', 
            'user', 
            'hasPendingResults', 
            'academicYears', 
            'selectedAcademicYear', 
            'terms', 
            'selectedTermId',
            'term',
            'totalScore',
            'averageScore',
            'totalSubjects',
            'classType'
        ));
    }

    public function printResults(Request $request)
    {
        $user = auth()->user();

        $studentProfile = StudentProfile::where('user_id', $user->id)->first();
        if (!$studentProfile) {
            abort(403, 'Access denied. You are not registered as a student.');
        }

        $query = Result::where('student_id', $user->id)->approved();

        if ($request->filled('academic_year_id')) {
            $query->whereHas('academicTerm', function ($q) use ($request) {
                $q->where('academic_year_id', $request->input('academic_year_id'));
            });
        }

        if ($request->filled('term_id')) {
            $query->where('term_id', $request->input('term_id'));
        }

        $results = $query->with(['schoolClass', 'subject', 'academicTerm.academicYear'])->orderBy('subject_id')->get();

        if ($results->isEmpty()) {
            return redirect()->route('student.results')
                ->with('error', 'No approved results available to print.');
        }

        $totalScore = $results->sum('total');
        $averageScore = $results->avg('total') ?? 0;
        $totalSubjects = $results->count();

        $className = $results->first()?->schoolClass?->name
            ?? StudentProfile::where('user_id', $user->id)->first()?->schoolClass?->name;
        $classType = \App\Models\Result\Result::resolveClassType($className);

        $termId = $request->input('term_id');
        if ($termId) {
            $term = \App\Models\AcademicTerm::with('academicYear')->find($termId);
        } else {
            $term = $results->first()?->academicTerm;
        }

        $student = User::find($user->id) ?? $user;

        return view('student.results-print', compact(
            'student',
            'results',
            'user',
            'term',
            'totalScore',
            'averageScore',
            'totalSubjects',
            'classType'
        ));
    }

    public function viewResultBySubject($subjectId)
    {
        $user = auth()->user();

        // Get the student profile for the authenticated user
        $student = StudentProfile::where('user_id', $user->id)->first();

        if (!$student) {
            abort(403, 'Access denied. You are not registered as a student.');
        }

        // Get APPROVED result for this specific student and subject
        $result = Result::where('student_id', $user->id)
            ->where('subject_id', $subjectId)
            ->approved()
            ->first();

        if (!$result) {
            abort(404, 'Result not found for this subject. It may not have been approved yet.');
        }

        // Verify the result belongs to the authenticated student
        if ($result->student_id !== $user->id) {
            abort(403, 'You do not have permission to access this result.');
        }

        return view('student.result-detail', compact('student', 'result', 'user'));
    }

    public function exportResultsPDF(Request $request)
    {
        $user = auth()->user();

        // Get only APPROVED results for this student
        $query = Result::where('student_id', $user->id)->approved();

        // Filter by academic year if provided
        if ($request->filled('academic_year_id')) {
            $query->whereHas('academicTerm', function ($q) use ($request) {
                $q->where('academic_year_id', $request->input('academic_year_id'));
            });
        }

        // Filter by term if provided
        if ($request->filled('term_id')) {
            $query->where('term_id', $request->input('term_id'));
        }

        $results = $query->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->orderBy('subjects.name')
            ->select('results.*')
            ->with(['subject', 'academicTerm.academicYear', 'schoolClass'])
            ->get();

        if ($results->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No approved results available to export.');
        }

        // Calculate overall statistics for the result sheet template
        $totalScore = $results->sum('total');
        $averageScore = $results->avg('total') ?? 0;
        $totalSubjects = $results->count();
        
        // Get the selected term model
        $termId = $request->input('term_id');
        $term = null;
        if ($termId) {
            $term = \App\Models\AcademicTerm::with('academicYear')->find($termId);
        } else {
            $term = $results->first()->academicTerm;
        }

        // Resolve class type for grade interpretation display (ss / js / default)
        $className = $results->first()?->schoolClass?->name
            ?? StudentProfile::where('user_id', $user->id)->first()?->schoolClass?->name;
        $classType = \App\Models\Result\Result::resolveClassType($className);

        // We pass the fresh User model as $student so profile_photo and details are always up-to-date
        $student = User::find($user->id) ?? $user;

        // Generate PDF
        $pdf = PDF::loadView('student.results-pdf', compact(
            'student', 
            'results', 
            'user', 
            'term', 
            'totalScore', 
            'averageScore', 
            'totalSubjects',
            'classType'
        ));

        return $pdf->download('Results_' . $user->name . '_' . date('Y-m-d') . '.pdf');
    }

    public function exportResultsExcel(Request $request)
    {
        $user = auth()->user();

        // Get the student profile for the authenticated user
        $student = StudentProfile::where('user_id', $user->id)->first();

        if (!$student) {
            abort(403, 'Access denied. You are not registered as a student.');
        }

        // Get only APPROVED results for this student
        $query = Result::where('student_id', $user->id)->approved();

        // Filter by academic year if provided
        if ($request->filled('academic_year_id')) {
            $query->whereHas('academicTerm', function ($q) use ($request) {
                $q->where('academic_year_id', $request->input('academic_year_id'));
            });
        }

        // Filter by term if provided
        if ($request->filled('term_id')) {
            $query->where('term_id', $request->input('term_id'));
        }

        $results = $query->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->orderBy('subjects.name')
            ->select('results.*')
            ->with(['subject', 'academicTerm.academicYear'])
            ->get();

        if ($results->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No approved results available to export.');
        }

        // Create Excel export
        return \Excel::download(
            new \App\Exports\StudentResultsExport($student, $results, $user),
            'Results_' . $user->name . '_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportSubjectPDF($subjectId)
    {
        $user = auth()->user();

        // Get the student profile for the authenticated user
        $student = StudentProfile::where('user_id', $user->id)->first();

        if (!$student) {
            abort(403, 'Access denied. You are not registered as a student.');
        }

        // Get APPROVED result for this specific student and subject
        $result = Result::where('student_id', $user->id)
            ->where('subject_id', $subjectId)
            ->approved()
            ->first();

        if (!$result) {
            abort(404, 'Approved result not found for this subject.');
        }

        // Verify the result belongs to the authenticated student
        if ($result->student_id !== $user->id) {
            abort(403, 'You do not have permission to access this result.');
        }

        // Generate PDF
        $pdf = PDF::loadView('student.result-detail-pdf', compact('student', 'result', 'user'));

        return $pdf->download('Result_' . ($result->subject->name ?? 'Subject') . '_' . $user->name . '_' . date('Y-m-d') . '.pdf');
    }


}
