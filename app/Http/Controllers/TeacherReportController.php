<?php

namespace App\Http\Controllers;

use App\Models\TeacherReport;
use App\Models\Branch;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('branch.selected');
    }

    /**
     * Display a listing of teacher reports
     */
    public function index()
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        
        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before viewing teacher reports.');
        }
        
        if ($currentRole === 'teacher') {
            // Teachers see their own reports
            $teacherReports = TeacherReport::byTeacher($user->id)
                ->byBranch($branchId)
                ->with(['branch'])
                ->latest('report_date')
                ->paginate(10);

            $stats = [
                'total' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->count(),
                'draft' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('draft')->count(),
                'submitted' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('submitted')->count(),
                'approved' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('approved')->count(),
                'rejected' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('rejected')->count(),
            ];

            return view('teacher-reports.teacher.index', compact('teacherReports', 'stats'));
        } else {
            // Admins see only review-stage reports (not drafts) in their branch
            $teacherReports = TeacherReport::byBranch($branchId)
                ->whereIn('status', ['pending', 'approved', 'rejected'])
                ->with(['teacher', 'branch'])
                ->latest('report_date')
                ->paginate(15);

            $stats = [
                'total' => TeacherReport::byBranch($branchId)->whereIn('status', ['pending', 'approved', 'rejected'])->count(),
                'pending' => TeacherReport::byBranch($branchId)->pendingReview()->count(),
                'approved' => TeacherReport::byBranch($branchId)->approved()->count(),
                'rejected' => TeacherReport::byBranch($branchId)->rejected()->count(),
            ];

            return view('teacher-reports.admin.index', compact('teacherReports', 'stats'));
        }
    }

    /**
     * Show the form for creating a new teacher report
     */
    public function create()
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can create teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before creating teacher reports.');
        }

        $classes = SchoolClass::where('branch_id', $branchId)->get();
        $subjects = Subject::where('branch_id', $branchId)->get();
        $teachingMethods = TeacherReport::getTeachingMethods();
        $participationLevels = TeacherReport::getStudentParticipationLevels();

        return view('teacher-reports.create', compact('classes', 'subjects', 'teachingMethods', 'participationLevels'));
    }

    /**
     * Store a newly created teacher report
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can create teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before creating teacher reports.');
        }

        $validated = $request->validate([
            'teacher_name' => 'required|string|max:255',
            'report_date' => 'required|date',
            'classes_taught' => 'required|array|min:1',
            'classes_taught.*' => 'exists:school_classes,id',
            'subjects_taught' => 'required|array|min:1',
            'subjects_taught.*' => 'exists:subjects,id',
            'topics_covered' => 'required|string',
            'teaching_method' => 'nullable|string|in:' . implode(',', array_keys(TeacherReport::getTeachingMethods())),
            'objectives_achieved' => 'boolean',
            'objectives_notes' => 'nullable|string',
            'student_participation' => 'nullable|string|in:' . implode(',', array_keys(TeacherReport::getStudentParticipationLevels())),
            'participation_notes' => 'nullable|string',
            'homework_assigned' => 'boolean',
            'homework_details' => 'nullable|string',
            'class_activities' => 'nullable|string',
            'challenges_faced' => 'nullable|string',
            'materials_needed' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $teacherReport = TeacherReport::create([
            ...$validated,
            'teacher_id' => $user->id,
            'branch_id' => $branchId,
            'status' => 'draft',
        ]);

        return redirect()->route('teacher-reports.show', $teacherReport)
            ->with('success', 'Teacher report created successfully! You can now submit it for review.');
    }

    /**
     * Display the specified teacher report
     */
    public function show(TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        
        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before viewing teacher reports.');
        }

        // Check if user can view this report
        if ($currentRole === 'teacher' && $teacherReport->teacher_id !== $user->id) {
            abort(403, 'You can only view your own teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only view teacher reports from your selected branch.');
        }

        $teacherReport->load(['teacher', 'branch', 'reviewer']);

        return view('teacher-reports.show', compact('teacherReport'));
    }

    /**
     * Show the form for editing the specified teacher report
     */
    public function edit(TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can edit teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before editing teacher reports.');
        }

        if ($teacherReport->teacher_id !== $user->id) {
            abort(403, 'You can only edit your own teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only edit teacher reports from your selected branch.');
        }

        if (!$teacherReport->canBeEdited()) {
            abort(403, 'This teacher report cannot be edited.');
        }

        $classes = SchoolClass::where('branch_id', $branchId)->get();
        $subjects = Subject::where('branch_id', $branchId)->get();
        $teachingMethods = TeacherReport::getTeachingMethods();
        $participationLevels = TeacherReport::getStudentParticipationLevels();

        return view('teacher-reports.edit', compact('teacherReport', 'classes', 'subjects', 'teachingMethods', 'participationLevels'));
    }

    /**
     * Update the specified teacher report
     */
    public function update(Request $request, TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can update teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before updating teacher reports.');
        }

        if ($teacherReport->teacher_id !== $user->id) {
            abort(403, 'You can only update your own teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only update teacher reports from your selected branch.');
        }

        if (!$teacherReport->canBeEdited()) {
            abort(403, 'This teacher report cannot be edited.');
        }

        $validated = $request->validate([
            'teacher_name' => 'required|string|max:255',
            'report_date' => 'required|date',
            'classes_taught' => 'required|array|min:1',
            'classes_taught.*' => 'exists:school_classes,id',
            'subjects_taught' => 'required|array|min:1',
            'subjects_taught.*' => 'exists:subjects,id',
            'topics_covered' => 'required|string',
            'teaching_method' => 'nullable|string|in:' . implode(',', array_keys(TeacherReport::getTeachingMethods())),
            'objectives_achieved' => 'boolean',
            'objectives_notes' => 'nullable|string',
            'student_participation' => 'nullable|string|in:' . implode(',', array_keys(TeacherReport::getStudentParticipationLevels())),
            'participation_notes' => 'nullable|string',
            'homework_assigned' => 'boolean',
            'homework_details' => 'nullable|string',
            'class_activities' => 'nullable|string',
            'challenges_faced' => 'nullable|string',
            'materials_needed' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $teacherReport->update($validated);

        return redirect()->route('teacher-reports.show', $teacherReport)
            ->with('success', 'Teacher report updated successfully!');
    }

    /**
     * Submit teacher report for review
     */
    public function submit(TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can submit teacher reports.');
        }

        if ($teacherReport->teacher_id !== $user->id) {
            abort(403, 'You can only submit your own teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before submitting teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only submit teacher reports from your selected branch.');
        }

        if (!$teacherReport->canBeSubmitted()) {
            abort(403, 'This teacher report cannot be submitted.');
        }

        $teacherReport->submit();

        return redirect()->route('teacher-reports.show', $teacherReport)
            ->with('success', 'Teacher report submitted successfully! It is now pending admin review.');
    }

    /**
     * Approve teacher report (Admin only)
     */
    public function approve(TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if (!in_array($currentRole, ['admin', 'super_admin'])) {
            abort(403, 'Only admins can approve teacher reports.');
        }

        if ($teacherReport->teacher_id === $user->id) {
            abort(403, 'You cannot approve your own teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before approving teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only approve teacher reports from your selected branch.');
        }

        if (!$teacherReport->canBeReviewed()) {
            abort(403, 'This teacher report cannot be reviewed.');
        }

        $teacherReport->approve($user->id);

        return redirect()->route('teacher-reports.show', $teacherReport)
            ->with('success', 'Teacher report approved successfully!');
    }

    /**
     * Reject teacher report (Admin only)
     */
    public function reject(Request $request, TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if (!in_array($currentRole, ['admin', 'super_admin'])) {
            abort(403, 'Only admins can reject teacher reports.');
        }

        if ($teacherReport->teacher_id === $user->id) {
            abort(403, 'You cannot reject your own teacher reports.');
        }

        if (!$branchId) {
            return redirect()->route('dashboard.select-branch')
                ->with('error', 'Please select a branch before rejecting teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only reject teacher reports from your selected branch.');
        }

        if (!$teacherReport->canBeReviewed()) {
            abort(403, 'This teacher report cannot be reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $teacherReport->reject($user->id, $validated['rejection_reason']);

        return redirect()->route('teacher-reports.show', $teacherReport)
            ->with('success', 'Teacher report rejected. The teacher will be notified with your feedback.');
    }

    /**
     * Remove the specified teacher report
     */
    public function destroy(TeacherReport $teacherReport)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        if ($currentRole !== 'teacher') {
            abort(403, 'Only teachers can delete teacher reports.');
        }

        if ($teacherReport->teacher_id !== $user->id) {
            abort(403, 'You can only delete your own teacher reports.');
        }

        if ($teacherReport->branch_id !== $branchId) {
            abort(403, 'You can only delete teacher reports from your selected branch.');
        }

        if ($teacherReport->status !== 'draft') {
            abort(403, 'You can only delete draft teacher reports.');
        }

        $teacherReport->delete();

        return redirect()->route('teacher-reports.index')
            ->with('success', 'Teacher report deleted successfully!');
    }

    /**
     * Show teacher reports by status for teachers
     */
    public function byStatus($status)
    {
        $user = Auth::user();
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');

        // Teachers: keep existing behavior (including 'draft')
        if ($currentRole === 'teacher') {
            $validTeacherStatuses = ['draft', 'submitted', 'approved', 'rejected'];
            if (!in_array($status, $validTeacherStatuses)) {
                abort(404);
            }

            $teacherReports = TeacherReport::byTeacher($user->id)
                ->byBranch($branchId)
                ->byStatus($status)
                ->with(['branch'])
                ->latest('report_date')
                ->paginate(10);

            $stats = [
                'total' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->count(),
                'draft' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('draft')->count(),
                'submitted' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('submitted')->count(),
                'approved' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('approved')->count(),
                'rejected' => TeacherReport::byTeacher($user->id)->byBranch($branchId)->byStatus('rejected')->count(),
            ];

            return view('teacher-reports.teacher.by-status', compact('teacherReports', 'stats', 'status'));
        }

        // Admins: allow filtering by review statuses. Map 'submitted' to DB 'pending'
        if (in_array($currentRole, ['admin', 'super_admin'])) {
            $validAdminStatuses = ['submitted', 'approved', 'rejected'];
            if (!in_array($status, $validAdminStatuses)) {
                abort(404);
            }

            $dbStatus = $status === 'submitted' ? 'pending' : $status;

            $teacherReports = TeacherReport::byBranch($branchId)
                ->where('status', $dbStatus)
                ->with(['teacher', 'branch'])
                ->latest('report_date')
                ->paginate(15);

            $stats = [
                'total' => TeacherReport::byBranch($branchId)->whereIn('status', ['pending', 'approved', 'rejected'])->count(),
                'pending' => TeacherReport::byBranch($branchId)->pendingReview()->count(),
                'approved' => TeacherReport::byBranch($branchId)->approved()->count(),
                'rejected' => TeacherReport::byBranch($branchId)->rejected()->count(),
            ];

            return view('teacher-reports.admin.index', compact('teacherReports', 'stats'));
        }

        abort(403);
    }
}
