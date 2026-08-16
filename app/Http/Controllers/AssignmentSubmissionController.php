<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentSubmissionAttachment;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    public function create(Assignment $assignment)
    {
        // Only students can submit; ensure user is student in branch and enrolled in class
        $this->authorizeStudentForAssignment($assignment);
        return view('assignments.submit', compact('assignment'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $this->authorizeStudentForAssignment($assignment);

        $request->validate([
            'content' => ['nullable', 'string'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:15360'],
        ]);

        // Prevent duplicate active submission if not allowed to resubmit
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->latest()->first();
        if ($existing && $existing->isFinalized()) {
            return back()->with('error', 'This submission is finalized and cannot be resubmitted.');
        }

        // Enforce due date if not allowed late
        if ($assignment->due_date && !$assignment->allow_late && now()->greaterThan($assignment->due_date)) {
            return back()->with('error', 'The due date has passed. Late submissions are not allowed.');
        }

        $submission = new AssignmentSubmission();
        $submission->fill([
            'assignment_id' => $assignment->id,
            'student_id' => auth()->id(),
            'content' => $request->input('content'),
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $submission->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('assignment-submissions', 'public');
                $submission->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Notify teacher of new submission
        \App\Models\Notification::createAssignmentSubmissionNotification($submission);

        return redirect()->route('student.assignments')->with('status', 'Assignment submitted');
    }

    public function review(Assignment $assignment)
    {
        $this->authorizeTeacherForAssignment($assignment);
        
        // Get search parameter
        $studentName = request('student_name');
        
        // Build query for submissions
        $submissionsQuery = AssignmentSubmission::where('assignment_id', $assignment->id)->with('student');
        
        // Filter by student name if search is provided
        if ($studentName) {
            $submissionsQuery->whereHas('student', function($query) use ($studentName) {
                $query->where('name', 'like', '%' . $studentName . '%');
            });
        }
        
        $submissions = $submissionsQuery->get();
        
        // Load attachments for the assignment
        $assignment->load('attachments');
        
        return view('assignments.review', compact('assignment', 'submissions', 'studentName'));
    }

    public function grade(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorizeTeacherForAssignment($assignment);

        if ((int) $submission->getAttribute('assignment_id') !== (int) $assignment->id) {
            abort(404, 'Submission does not belong to this assignment.');
        }

        $request->validate([
            'status' => ['required', 'in:approved,returned,graded'],
            'grade' => ['nullable', 'string', 'max:10'],
            'remarks' => ['nullable', 'string'],
            'allow_resubmit' => ['nullable', 'boolean'],
        ]);

        $submission->update([
            'status' => $request->status,
            'grade' => $request->grade,
            'remarks' => $request->remarks,
            'can_resubmit' => (bool) $request->boolean('allow_resubmit'),
            'graded_at' => now(),
        ]);

        // Notify student of review outcome
        \App\Models\Notification::createAssignmentReviewNotification($submission);

        return back()->with('status', 'Submission updated');
    }

    private function authorizeStudentForAssignment(Assignment $assignment): void
    {
        $branchId = session('current_branch_id');
        if (session('current_role') !== 'student') {
            abort(403);
        }
        $enrolled = \App\Models\Enrollment::where('student_id', auth()->id())
            ->where('school_class_id', $assignment->school_class_id)
            ->whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->exists();
        if (!$enrolled) {
            abort(403);
        }
    }

    private function authorizeTeacherForAssignment(Assignment $assignment): void
    {
        $branchId = session('current_branch_id');
        $role = session('current_role');
        if (!in_array($role, ['teacher', 'admin', 'super_admin'])) {
            abort(403);
        }
        if ($role === 'teacher' && $assignment->teacher_id !== auth()->id()) {
            abort(403);
        }
        if ($assignment->schoolClass?->branch_id !== $branchId) {
            abort(403);
        }
    }
}


