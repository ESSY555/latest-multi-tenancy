<?php

namespace App\Http\Controllers;

use App\Models\LessonPlan;
use App\Models\LessonPlanAttachment;
use App\Models\Branch;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LessonPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('branch.selected');
    }

    /**
     * Display a listing of lesson plans for teachers
     */
    public function index()
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');

        $currentRole = session('current_role');
        
        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before viewing lesson plans.');
        }
        
        if ($currentRole === 'teacher') {
            // Teachers see their own lesson plans
            $lessonPlans = LessonPlan::byTeacher($user->id)
                ->byBranch($branchId)
                ->with(['branch', 'schoolClass'])
                ->latest()
                ->paginate(10);

            $stats = [
                'total' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->count(),
                'draft' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('draft')->count(),
                'submitted' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('submitted')->count(),
                'approved' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('approved')->count(),
                'rejected' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('rejected')->count(),
            ];

            return view('lesson-plans.teacher.index', compact('lessonPlans', 'stats'));
        } else {
            // Admins see all lesson plans in their branch
            $lessonPlans = LessonPlan::byBranch($branchId)
                ->with(['teacher', 'branch', 'schoolClass'])
                ->latest()
                ->paginate(15);

            $stats = [
                'total' => LessonPlan::byBranch($branchId)->count(),
                'pending' => LessonPlan::byBranch($branchId)->pendingReview()->count(),
                'approved' => LessonPlan::byBranch($branchId)->approved()->count(),
                'rejected' => LessonPlan::byBranch($branchId)->rejected()->count(),
            ];

            return view('lesson-plans.admin.index', compact('lessonPlans', 'stats'));
        }
    }

    /**
     * Show the form for creating a new lesson plan
     */
    public function create()
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can create lesson plans.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before creating lesson plans.');
        }

        $classes = $user->teachingClasses()
            ->where('school_classes.branch_id', $branchId)
            ->orderBy('school_classes.name')
            ->get();
        $subjects = $user->subjects()->orderBy('name')->get();
        $branches = Branch::where('id', $branchId)->get();

        return view('lesson-plans.create', compact('classes', 'branches', 'subjects'));
    }

    /**
     * Store a newly created lesson plan
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        $saveAction = $request->input('save_action', 'submit');
        $submitNow = $saveAction === 'submit';

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can create lesson plans.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before creating lesson plans.');
        }

        $teacherSubjects = $user->subjects()->pluck('subjects.id')->toArray();
        $teacherClasses = $user->teachingClasses()
            ->where('school_classes.branch_id', $branchId)
            ->pluck('school_classes.id')
            ->toArray();

        $validated = $request->validate([
            'teacher_name' => 'required|string|max:255',
            'subject_id' => ['required', Rule::in($teacherSubjects)],
            'topic' => 'required|string|max:255',
            'lesson_date' => 'required|date',
            'duration' => 'required|string|max:100',
            'lesson_title' => 'required|string|max:255',
            'objectives' => 'required|string',
            'materials_resources' => 'required|string',
            'lesson_introduction' => 'required|string',
            'lesson_development' => 'required|string',
            'assessment_evaluation' => 'required|string',
            'conclusion' => 'required|string',
            'reflection' => 'nullable|string',
            'class_id' => ['nullable', Rule::in($teacherClasses)],
            'school_name' => 'nullable|string|max:255',
            'term_name' => 'nullable|string|max:255',
            'week_name' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'subtopic' => 'nullable|string|max:255',
            'periods' => 'nullable|string|max:255',
            'time_slot' => 'nullable|string|max:255',
            'class_size' => 'nullable|string|max:255',
            'average_age' => 'nullable|string|max:255',
            'sex_label' => 'nullable|string|max:255',
            'rationale' => 'nullable|string',
            'previous_knowledge' => 'nullable|string',
            'reference_books' => 'nullable|string',
            'learning_aids' => 'nullable|string',
            'lesson_note' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $subjectName = $user->subjects()
            ->where('subjects.id', $validated['subject_id'])
            ->value('subjects.name');

        $selectedClass = null;
        if (!empty($validated['class_id'])) {
            $selectedClass = $user->teachingClasses()
                ->where('school_classes.branch_id', $branchId)
                ->where('school_classes.id', $validated['class_id'])
                ->first();
        }

        $classGradeLevel = $selectedClass
            ? trim($selectedClass->name . ($selectedClass->grade_level ? ' - ' . $selectedClass->grade_level : ''))
            : 'Not specified';

        $learningAids = $validated['learning_aids'] ?? null;
        if (!$learningAids) {
            $learningAids = $validated['materials_resources'];
        }

        $lessonPlan = DB::transaction(function () use ($request, $validated, $user, $branchId, $subjectName, $classGradeLevel, $learningAids, $submitNow) {
            $plan = LessonPlan::create([
                'teacher_name' => $validated['teacher_name'],
                'subject_topic' => trim(($subjectName ?? 'Unknown Subject').' - '.$validated['topic']),
                'class_grade_level' => $classGradeLevel,
                'lesson_date' => $validated['lesson_date'],
                'duration' => $validated['duration'],
                'lesson_title' => $validated['lesson_title'],
                'objectives' => $validated['objectives'],
                'materials_resources' => $validated['materials_resources'],
                'lesson_introduction' => $validated['lesson_introduction'],
                'lesson_development' => $validated['lesson_development'],
                'assessment_evaluation' => $validated['assessment_evaluation'],
                'conclusion' => $validated['conclusion'],
                'reflection' => $validated['reflection'] ?? null,
                'class_id' => $validated['class_id'] ?? null,
                'teacher_id' => $user->id,
                'branch_id' => $branchId,
                'status' => 'draft',
                'school_name' => $validated['school_name'] ?? null,
                'term_name' => $validated['term_name'] ?? null,
                'week_name' => $validated['week_name'] ?? null,
                'theme' => $validated['theme'] ?? null,
                'subtopic' => $validated['subtopic'] ?? null,
                'periods' => $validated['periods'] ?? null,
                'time_slot' => $validated['time_slot'] ?? null,
                'class_size' => $validated['class_size'] ?? null,
                'average_age' => $validated['average_age'] ?? null,
                'sex_label' => $validated['sex_label'] ?? null,
                'rationale' => $validated['rationale'] ?? null,
                'previous_knowledge' => $validated['previous_knowledge'] ?? null,
                'reference_books' => $validated['reference_books'] ?? null,
                'learning_aids' => $learningAids,
                'lesson_note' => $validated['lesson_note'] ?? null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if (! $file) {
                        continue;
                    }
                    $path = $file->store('lesson-plans', 'public');
                    $plan->attachments()->create([
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            if ($submitNow) {
                $plan->load('teacher');
                $plan->submit();
            }

            return $plan;
        });

        if ($submitNow) {
            return redirect()->route('lesson-plans.show', $lessonPlan)
                ->with('success', 'Lesson plan sent to branch admins for review. You will be notified when it is approved or rejected.');
        }

        return redirect()->route('lesson-plans.show', $lessonPlan)
            ->with('success', 'Lesson plan saved as draft. You can submit it for review later.');
    }

    /**
     * Display the specified lesson plan
     */
    public function show(LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');

        $currentRole = session('current_role');
        
        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before viewing lesson plans.');
        }
        // Check if user can view this lesson plan
        if ($currentRole === 'teacher' && $lessonPlan->teacher_id !== $user->id) {
            abort(403, 'You can only view your own lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only view lesson plans from your selected branch.');
        }

        $lessonPlan->load(['teacher', 'branch', 'schoolClass', 'reviewer', 'attachments']);

        return view('lesson-plans.show', compact('lessonPlan'));
    }

    /**
     * Show the form for editing the specified lesson plan
     */
    public function edit(LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can edit lesson plans.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before editing lesson plans.');
        }

        if ($lessonPlan->teacher_id !== $user->id) {
            abort(403, 'You can only edit your own lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only edit lesson plans from your selected branch.');
        }

        if (!$lessonPlan->canBeEdited()) {
            abort(403, 'This lesson plan cannot be edited.');
        }

        $classes = $user->teachingClasses()
            ->where('school_classes.branch_id', $branchId)
            ->orderBy('school_classes.name')
            ->get();
        $branches = Branch::where('id', $branchId)->get();
        $lessonPlan->loadMissing('attachments');

        return view('lesson-plans.edit', compact('lessonPlan', 'classes', 'branches'));
    }

    /**
     * Update the specified lesson plan
     */
    public function update(Request $request, LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can update lesson plans.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before updating lesson plans.');
        }

        if ($lessonPlan->teacher_id !== $user->id) {
            abort(403, 'You can only update your own lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only update lesson plans from your selected branch.');
        }

        if (!$lessonPlan->canBeEdited()) {
            abort(403, 'This lesson plan cannot be edited.');
        }

        $teacherClasses = $user->teachingClasses()
            ->where('school_classes.branch_id', $branchId)
            ->pluck('school_classes.id')
            ->toArray();

        $validated = $request->validate([
            'teacher_name' => 'required|string|max:255',
            'subject_topic' => 'required|string|max:255',
            'class_grade_level' => 'required|string|max:255',
            'lesson_date' => 'required|date',
            'duration' => 'required|string|max:100',
            'lesson_title' => 'required|string|max:255',
            'objectives' => 'required|string',
            'materials_resources' => 'required|string',
            'lesson_introduction' => 'required|string',
            'lesson_development' => 'required|string',
            'assessment_evaluation' => 'required|string',
            'conclusion' => 'required|string',
            'reflection' => 'nullable|string',
            'class_id' => ['nullable', Rule::in($teacherClasses)],
            'school_name' => 'nullable|string|max:255',
            'term_name' => 'nullable|string|max:255',
            'week_name' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'subtopic' => 'nullable|string|max:255',
            'periods' => 'nullable|string|max:255',
            'time_slot' => 'nullable|string|max:255',
            'class_size' => 'nullable|string|max:255',
            'average_age' => 'nullable|string|max:255',
            'sex_label' => 'nullable|string|max:255',
            'rationale' => 'nullable|string',
            'previous_knowledge' => 'nullable|string',
            'reference_books' => 'nullable|string',
            'learning_aids' => 'nullable|string',
            'lesson_note' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if (empty($validated['learning_aids'])) {
            $validated['learning_aids'] = $validated['materials_resources'];
        }

        $lessonPlan->update($validated);

        // Handle new attachments (only when editable)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file) continue;
                $path = $file->store('lesson-plans', 'public');
                $lessonPlan->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('lesson-plans.show', $lessonPlan)
            ->with('success', 'Lesson plan updated successfully!');
    }

    /**
     * Delete a single attachment (teacher, draft only)
     */
    public function destroyAttachment(LessonPlan $lessonPlan, LessonPlanAttachment $attachment)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher' || $lessonPlan->teacher_id !== $user->id) {
            abort(403);
        }

        if ($lessonPlan->branch_id !== $branchId || !$lessonPlan->isDraft()) {
            abort(403);
        }

        if ($attachment->lesson_plan_id !== $lessonPlan->id) {
            abort(404);
        }

        try {
            \Storage::disk('public')->delete($attachment->path);
        } catch (\Throwable $e) {}

        $attachment->delete();

        return redirect()->route('lesson-plans.show', $lessonPlan)
            ->with('success', 'Attachment removed.');
    }

    /**
     * Submit lesson plan for review
     */
    public function submit(LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can submit lesson plans.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before submitting lesson plans.');
        }

        if ($lessonPlan->teacher_id !== $user->id) {
            abort(403, 'You can only submit your own lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only submit lesson plans from your selected branch.');
        }

        if (!$lessonPlan->canBeSubmitted()) {
            abort(403, 'This lesson plan cannot be submitted.');
        }

        $lessonPlan->submit();

        return redirect()->route('lesson-plans.show', $lessonPlan)
            ->with('success', 'Lesson plan submitted successfully! It is now pending admin review.');
    }

    /**
     * Approve lesson plan (Admin only)
     */
    public function approve(LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if (!in_array($currentRole, ['admin', 'super_admin'])) {
            abort(403, 'Only admins can approve lesson plans.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before approving lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only approve lesson plans from your selected branch.');
        }

        if (!$lessonPlan->canBeReviewed()) {
            abort(403, 'This lesson plan cannot be reviewed.');
        }

        $lessonPlan->approve($user->id);

        return redirect()->route('lesson-plans.show', $lessonPlan)
            ->with('success', 'Lesson plan approved successfully!');
    }

    /**
     * Reject lesson plan (Admin only)
     */
    public function reject(Request $request, LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if (!in_array($currentRole, ['admin', 'super_admin'])) {
            abort(403, 'Only admins can reject lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only reject lesson plans from your selected branch.');
        }

        if (!$lessonPlan->canBeReviewed()) {
            abort(403, 'This lesson plan cannot be reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $lessonPlan->reject($user->id, $validated['rejection_reason']);

        return redirect()->route('lesson-plans.show', $lessonPlan)
            ->with('success', 'Lesson plan rejected. The teacher will be notified with your feedback.');
    }

    /**
     * Remove the specified lesson plan
     */
    public function destroy(LessonPlan $lessonPlan)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can delete lesson plans.');
        }

        if ($lessonPlan->teacher_id !== $user->id) {
            abort(403, 'You can only delete your own lesson plans.');
        }

        if ($lessonPlan->branch_id !== $branchId) {
            abort(403, 'You can only delete lesson plans from your selected branch.');
        }

        if ($lessonPlan->status !== 'draft') {
            abort(403, 'You can only delete draft lesson plans.');
        }

        $lessonPlan->delete();

        return redirect()->route('lesson-plans.index')
            ->with('success', 'Lesson plan deleted successfully!');
    }

    /**
     * Show lesson plans by status for teachers
     */
    public function byStatus($status)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can view lesson plans by status.');
        }

        $validStatuses = ['draft', 'submitted', 'approved', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            abort(404);
        }

        $lessonPlans = LessonPlan::byTeacher($user->id)
            ->byBranch($branchId)
            ->byStatus($status)
            ->with(['branch', 'schoolClass'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->count(),
            'draft' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('draft')->count(),
            'submitted' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('submitted')->count(),
            'approved' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('approved')->count(),
            'rejected' => LessonPlan::byTeacher($user->id)->byBranch($branchId)->byStatus('rejected')->count(),
        ];

        return view('lesson-plans.teacher.by-status', compact('lessonPlans', 'stats', 'status'));
    }
}
