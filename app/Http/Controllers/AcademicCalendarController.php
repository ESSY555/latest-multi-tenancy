<?php

namespace App\Http\Controllers;


use Illuminate\Validation\Rule;

use App\Models\AcademicYear;
use App\Models\AcademicSemester;
use App\Models\AcademicTerm;
use App\Models\AcademicEvent;
use App\Models\AcademicHoliday;
use App\Models\AcademicExam;
use App\Models\MockExam;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AcademicCalendarController extends Controller
{
    // Academic Year Management
    public function index()
    {
        $currentBranchId = session('current_branch_id');
        $academicYears = AcademicYear::where('branch_id', $currentBranchId)
            ->with(['terms', 'semesters', 'events', 'holidays'])
            ->orderBy('start_date', 'desc')
            ->get();

        $currentYear = $academicYears->where('is_active', true)->first();
        
        // Automatic closure logic: If 3rd term is past, end the session
        if ($currentYear && $currentYear->terms()->count() >= 3) {
            $lastTerm = $currentYear->getTermByNumber(3);
            if ($lastTerm && $lastTerm->isPast()) {
                $currentYear->endSession();
                // Refresh the list
                return redirect()->route('academic-calendar.index')
                    ->with('info', "Academic Section {$currentYear->name} has completed its third term and is now ended (inactive).");
            }
        }

        $upcomingEvents = collect();
        $upcomingHolidays = collect();

        if ($currentYear) {
            $upcomingEvents = $currentYear->getUpcomingEvents(5);
            $upcomingHolidays = $currentYear->getUpcomingHolidays(5);
        }

        return view('academic-calendar.index', compact(
            'academicYears',
            'currentYear',
            'upcomingEvents',
            'upcomingHolidays'
        ));
    }

    public function createYear()
    {
        $currentBranchId = session('current_branch_id');
        $branch = Branch::find($currentBranchId);
        
        return view('academic-calendar.years.create', compact('branch'));
    }

    public function storeYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $currentBranchId = session('current_branch_id');

        // If setting as active, deactivate other years
        if ($request->boolean('is_active')) {
            AcademicYear::where('branch_id', $currentBranchId)
                ->update(['is_active' => false]);
        }

        AcademicYear::create([
            'branch_id' => $currentBranchId,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Academic Section created successfully.');
    }

    public function editYear($id)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($id);

        return view('academic-calendar.years.edit', compact('academicYear'));
    }

    public function updateYear(Request $request, $id)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If setting as active, deactivate other years
        if ($request->boolean('is_active')) {
            AcademicYear::where('branch_id', $currentBranchId)
                ->where('id', '!=', $id)
                ->update(['is_active' => false]);
        }

        $academicYear->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Academic Section updated successfully.');
    }

    // Academic Semester Management
    public function createSemester($yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        return view('academic-calendar.semesters.create', compact('academicYear'));
    }

    public function storeSemester(Request $request, $yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date,
            'end_date' => 'required|date|before_or_equal:' . $academicYear->end_date . '|after:start_date',
            'description' => 'nullable|string',
            'semester_number' => 'required|integer|min:1|max:4',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        AcademicSemester::create([
            'academic_year_id' => $yearId,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'semester_number' => $request->semester_number,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Semester created successfully.');
    }

    // Academic Term Management
    public function createTerm($yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        return view('academic-calendar.terms.create', compact('academicYear'));
    }

    public function storeTerm(Request $request, $yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        // Enforce 3-term limit
        if ($academicYear->hasReachedTermLimit()) {
            return redirect()->back()
                ->with('error', 'Each section/session cannot have more than three terms.')
                ->withInput();
        }


        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                // Unique for this academic year
                Rule::unique('academic_terms')->where(function ($query) use ($yearId) {
                    return $query->where('academic_year_id', $yearId);
                })
            ],
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date,
            'end_date' => 'required|date|before_or_equal:' . $academicYear->end_date . '|after:start_date',
            'description' => 'nullable|string',
            'is_exam_term' => 'boolean',
            'is_break_term' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Determine the next term number
        $nextTermNumber = $academicYear->terms()->count() + 1;

        $signaturePath = null;
        if ($request->filled('principal_signature') && str_contains($request->principal_signature, 'data:image/png;base64,')) {
            $image_parts = explode(";base64,", $request->principal_signature);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'png';
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'signatures/term_' . uniqid() . '.' . $image_type;
            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
            $signaturePath = $fileName;
        }

        $term = AcademicTerm::create([
            'academic_year_id' => $yearId,
            'name' => $request->name,
            'term_number' => $nextTermNumber,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_exam_term' => $request->boolean('is_exam_term'),
            'is_break_term' => $request->boolean('is_break_term'),
            'principal_signature' => $signaturePath
        ]);

        $message = 'Term created successfully.';
        if ($nextTermNumber === 3) {
            $message .= ' This is the final term. The session will end upon completion.';
        }

        return redirect()->route('academic-calendar.index')
            ->with('success', $message);
    }

    public function editTerm($yearId, $termId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);
        $term = $academicYear->terms()->findOrFail($termId);

        return view('academic-calendar.terms.edit', compact('academicYear', 'term'));
    }

    public function updateTerm(Request $request, $yearId, $termId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);
        $term = $academicYear->terms()->findOrFail($termId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date,
            'end_date' => 'required|date|before_or_equal:' . $academicYear->end_date . '|after:start_date',
            'description' => 'nullable|string',
            'is_exam_term' => 'boolean',
            'is_break_term' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $signaturePath = $term->principal_signature;
        if ($request->filled('principal_signature') && str_contains($request->principal_signature, 'data:image/png;base64,')) {
            $image_parts = explode(";base64,", $request->principal_signature);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'png';
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'signatures/term_' . uniqid() . '.' . $image_type;
            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
            $signaturePath = $fileName;
        } elseif ($request->has('clear_signature') && $request->boolean('clear_signature')) {
            $signaturePath = null;
        }

        $term->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_exam_term' => $request->boolean('is_exam_term'),
            'is_break_term' => $request->boolean('is_break_term'),
            'principal_signature' => $signaturePath
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Term updated successfully.');
    }

    // Academic Event Management
    public function createEvent($yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);
        
        $classes = SchoolClass::where('branch_id', $currentBranchId)->get();
        $subjects = Subject::where('branch_id', $currentBranchId)->get();

        return view('academic-calendar.events.create', compact('academicYear', 'classes', 'subjects'));
    }

    public function storeEvent(Request $request, $yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date,
            'end_date' => 'required|date|before_or_equal:' . $academicYear->end_date . '|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'event_type' => 'required|in:exam,assignment,meeting,ceremony,sports,cultural,academic,administrative,other',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'boolean',
            'is_public' => 'boolean',
            'color' => 'nullable|string|max:7',
            'priority' => 'required|in:low,medium,high,urgent',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $event = AcademicEvent::create([
            'academic_year_id' => $yearId,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_type' => $request->event_type,
            'location' => $request->location,
            'is_all_day' => $request->boolean('is_all_day'),
            'is_public' => $request->boolean('is_public'),
            'color' => $request->color,
            'priority' => $request->priority
        ]);

        // Attach classes and subjects
        if ($request->has('class_ids')) {
            $event->classes()->attach($request->class_ids);
        }

        if ($request->has('subject_ids')) {
            $event->subjects()->attach($request->subject_ids);
        }

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Event created successfully.');
    }

    // Academic Holiday Management
    public function createHoliday($yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        return view('academic-calendar.holidays.create', compact('academicYear'));
    }

    public function storeHoliday(Request $request, $yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date,
            'end_date' => 'required|date|before_or_equal:' . $academicYear->end_date . '|after_or_equal:start_date',
            'holiday_type' => 'required|in:break,holiday,vacation,special,academic',
            'is_public_holiday' => 'boolean',
            'color' => 'nullable|string|max:7'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        AcademicHoliday::create([
            'academic_year_id' => $yearId,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'holiday_type' => $request->holiday_type,
            'is_public_holiday' => $request->boolean('is_public_holiday'),
            'color' => $request->color
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Holiday created successfully.');
    }

    // Academic Exam Management
    public function createExam($yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);
        
        $terms = $academicYear->terms;
        $semesters = $academicYear->semesters;
        $mockExams = MockExam::where('branch_id', $currentBranchId)
            ->where('academic_year_id', $academicYear->id)
            ->orderByDesc('id')
            ->get();
        $teachers = User::whereHas('branches', function ($query) use ($currentBranchId) {
            $query->where('branches.id', $currentBranchId)
                ->where('branch_user.role', 'teacher');
        })
            ->orderBy('name')
            ->get();
        $subjects = Subject::where('branch_id', $currentBranchId)->get();
        $classes = SchoolClass::where('branch_id', $currentBranchId)->get();

        return view('academic-calendar.exams.create', compact(
            'academicYear',
            'terms',
            'semesters',
            'mockExams',
            'teachers',
            'subjects',
            'classes'
        ));
    }

    public function storeExam(Request $request, $yearId)
    {
        $currentBranchId = session('current_branch_id');
        $academicYear = AcademicYear::where('branch_id', $currentBranchId)
            ->findOrFail($yearId);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_scope' => 'required|in:term,mock',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,project,presentation,practical,oral,written,other',
            'exam_date' => 'required|date|after_or_equal:' . $academicYear->start_date . '|before_or_equal:' . $academicYear->end_date,
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
            'subject_id' => 'nullable|exists:subjects,id',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'teacher_id' => 'required|exists:users,id',
            'academic_term_id' => 'required_if:exam_scope,term|nullable|exists:academic_terms,id',
            'mock_exam_id' => 'required_if:exam_scope,mock|nullable|exists:mock_exams,id',
            'academic_semester_id' => 'nullable|exists:academic_semesters,id',
            'is_published' => 'boolean',
            'is_online' => 'boolean',
            'location' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'color' => 'nullable|string|max:7'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->filled('academic_term_id')) {
            $termBelongsToYear = $academicYear->terms()
                ->where('id', $request->academic_term_id)
                ->exists();

            if (!$termBelongsToYear) {
                return redirect()->back()
                    ->with('error', 'Selected term does not belong to this academic section.')
                    ->withInput();
            }
        }

        if ($request->filled('mock_exam_id')) {
            $mockBelongsToContext = MockExam::where('id', $request->mock_exam_id)
                ->where('branch_id', $currentBranchId)
                ->where('academic_year_id', $academicYear->id)
                ->exists();

            if (!$mockBelongsToContext) {
                return redirect()->back()
                    ->with('error', 'Selected mock exam does not belong to this academic section.')
                    ->withInput();
            }
        }

        $teacherBelongsToBranch = User::where('id', $request->teacher_id)
            ->whereHas('branches', function ($query) use ($currentBranchId) {
                $query->where('branches.id', $currentBranchId)
                    ->where('branch_user.role', 'teacher');
            })
            ->exists();

        if (!$teacherBelongsToBranch) {
            return redirect()->back()
                ->with('error', 'Selected teacher does not belong to this branch.')
                ->withInput();
        }

        AcademicExam::create([
            'academic_year_id' => $yearId,
            'academic_term_id' => $request->exam_scope === 'term' ? $request->academic_term_id : null,
            'mock_exam_id' => $request->exam_scope === 'mock' ? $request->mock_exam_id : null,
            'exam_scope' => $request->exam_scope,
            'teacher_id' => $request->teacher_id,
            'academic_semester_id' => $request->academic_semester_id,
            'title' => $request->title,
            'description' => $request->description,
            'exam_type' => $request->exam_type,
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'subject_id' => $request->subject_id,
            'school_class_id' => $request->school_class_id,
            'is_published' => $request->boolean('is_published'),
            'is_online' => $request->boolean('is_online'),
            'location' => $request->location,
            'instructions' => $request->instructions,
            'color' => $request->color
        ]);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Exam created successfully.');
    }

    // Calendar View
    public function calendar(Request $request)
    {
        // Handle branch selection from GET request
        if ($request->has('branch_id') && $request->branch_id) {
            $currentBranchId = $request->branch_id;
            session(['current_branch_id' => $currentBranchId]);
        } else {
            $currentBranchId = session('current_branch_id');
        }
        
        // If no branch is selected, get the first branch with an active academic year
        if (!$currentBranchId) {
            $firstBranchWithYear = AcademicYear::where('is_active', true)
                ->with('branch')
                ->first();
            
            if ($firstBranchWithYear) {
                $currentBranchId = $firstBranchWithYear->branch_id;
                session(['current_branch_id' => $currentBranchId]);
            } else {
                // No active academic year found in any branch
                return view('academic-calendar.calendar', [
                    'currentYear' => null,
                    'calendarEvents' => collect(),
                    'branches' => Branch::all()
                ]);
            }
        }
        
        $currentYear = AcademicYear::where('branch_id', $currentBranchId)
            ->where('is_active', true)
            ->first();
            
        // Get all branches for selection
        $branches = Branch::all();

        $events = $currentYear->events()
            ->with(['classes', 'subjects'])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d'),
                    'end' => $event->end_date->format('Y-m-d'),
                    'color' => $event->color ?: $this->getEventColor($event->event_type),
                    'type' => 'event',
                    'data' => $event
                ];
            });

        $holidays = $currentYear->holidays()
            ->get()
            ->map(function ($holiday) {
                return [
                    'id' => $holiday->id,
                    'title' => $holiday->name,
                    'start' => $holiday->start_date->format('Y-m-d'),
                    'end' => $holiday->end_date->format('Y-m-d'),
                    'color' => $holiday->color ?: $this->getHolidayColor($holiday->holiday_type),
                    'type' => 'holiday',
                    'data' => $holiday
                ];
            });

        $exams = $currentYear->exams()
            ->get()
            ->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'start' => $exam->exam_date->format('Y-m-d'),
                    'end' => $exam->exam_date->format('Y-m-d'),
                    'color' => $exam->color ?: $this->getExamColor($exam->exam_type),
                    'type' => 'exam',
                    'data' => $exam
                ];
            });

        $calendarEvents = $events->concat($holidays)->concat($exams);

        return view('academic-calendar.calendar', compact('currentYear', 'calendarEvents', 'branches'));
    }

    private function getEventColor($type)
    {
        return match($type) {
            'exam' => '#dc2626',
            'assignment' => '#7c3aed',
            'meeting' => '#059669',
            'ceremony' => '#ea580c',
            'sports' => '#0891b2',
            'cultural' => '#be185d',
            'academic' => '#2563eb',
            'administrative' => '#6b7280',
            default => '#6b7280'
        };
    }

    private function getHolidayColor($type)
    {
        return match($type) {
            'break' => '#0891b2',
            'holiday' => '#dc2626',
            'vacation' => '#059669',
            'special' => '#7c3aed',
            'academic' => '#ea580c',
            default => '#6b7280'
        };
    }

    private function getExamColor($type)
    {
        return match($type) {
            'midterm' => '#2563eb',
            'final' => '#dc2626',
            'quiz' => '#059669',
            'assignment' => '#7c3aed',
            'project' => '#ea580c',
            'presentation' => '#7c3aed',
            'practical' => '#ec4899',
            'oral' => '#0891b2',
            'written' => '#6b7280',
            default => '#6b7280'
        };
    }
}
