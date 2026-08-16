<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\AssignmentAttachment;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $branchId = session('current_branch_id');
        $assignments = Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId))
            ->with(['schoolClass'])
            ->latest()->paginate(10);
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $classes = SchoolClass::where('branch_id', session('current_branch_id'))->orderBy('name')->get();
        return view('assignments.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $role = session('current_role');
        if (!in_array($role, ['teacher', 'admin', 'super_admin'])) {
            abort(403);
        }

        $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'submission_format' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'resources' => ['nullable', 'array'],
            'resources.*' => ['file', 'max:10240'],
            'publish' => ['nullable', 'boolean'],
            'allow_late' => ['nullable', 'boolean'],
        ]);

        $schoolClass = $this->resolveAccessibleClassOrFail((int) $request->school_class_id);

        $assignment = Assignment::create([
            'school_class_id' => $schoolClass->id,
            'teacher_id' => auth()->id(),
            'teacher_name' => auth()->user()->name,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'submission_format' => $request->submission_format,
            'due_date' => $request->due_date,
            'is_published' => (bool) $request->boolean('publish'),
            'allow_late' => (bool) $request->boolean('allow_late'),
        ]);

        if ($request->hasFile('resources')) {
            foreach ($request->file('resources') as $file) {
                $path = $file->store('assignments', 'public');
                $assignment->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Notify students in class on publish
        if ($assignment->is_published) {
            $studentIds = Enrollment::where('school_class_id', $assignment->school_class_id)
                ->pluck('student_id')->toArray();
            if (!empty($studentIds)) {
                \App\Models\Notification::createAssignmentPublishNotification($assignment, $studentIds);
            }
        }

        return redirect()->route('assignments.index')->with('status', 'Assignment created');
    }

    public function show(Assignment $assignment)
    {
        $branchId = session('current_branch_id');
        $role = session('current_role');

        if ($assignment->schoolClass?->branch_id !== $branchId) {
            abort(403);
        }

        if (in_array($role, ['teacher', 'admin', 'super_admin'])) {
            $this->authorizeTeacher($assignment);
            $assignment->load(['schoolClass', 'attachments', 'submissions.student']);
        } elseif ($role === 'student') {
            $enrolled = Enrollment::where('student_id', auth()->id())
                ->where('school_class_id', $assignment->school_class_id)
                ->where('status', 'active')
                ->exists();

            if (!$enrolled || !$assignment->is_published) {
                abort(403);
            }

            $assignment->load(['schoolClass', 'attachments']);
            $assignment->setRelation('submissions', $assignment->submissions()->where('student_id', auth()->id())->get());
        } else {
            abort(403);
        }

        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        $this->authorizeTeacher($assignment);
        $classes = SchoolClass::where('branch_id', session('current_branch_id'))->orderBy('name')->get();
        return view('assignments.edit', compact('assignment', 'classes'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $this->authorizeTeacher($assignment);

        $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'submission_format' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'allow_late' => ['nullable', 'boolean'],
        ]);

        $schoolClass = $this->resolveAccessibleClassOrFail((int) $request->school_class_id);

        $assignment->update([
            'school_class_id' => $schoolClass->id,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'submission_format' => $request->submission_format,
            'due_date' => $request->due_date,
            'allow_late' => (bool) $request->boolean('allow_late'),
        ]);

        return redirect()->route('assignments.show', $assignment)->with('status', 'Assignment updated');
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorizeTeacher($assignment);
        $assignment->delete();
        return redirect()->route('assignments.index')->with('status', 'Assignment deleted');
    }

    public function stats()
    {
        $branchId = session('current_branch_id');
        $role = session('current_role');

        $query = Assignment::whereHas('schoolClass', fn($q) => $q->where('branch_id', $branchId));
        if ($role === 'teacher') {
            $query->where('teacher_id', auth()->id());
        }

        $assignments = $query->with(['schoolClass', 'submissions'])->get();

        $totals = [
            'assignments' => $assignments->count(),
            'published' => $assignments->where('is_published', true)->count(),
            'unpublished' => $assignments->where('is_published', false)->count(),
            'submissions' => 0,
            'graded' => 0,
            'pending_review' => 0,
        ];

        foreach ($assignments as $a) {
            $totals['submissions'] += $a->submissions->count();
            $totals['graded'] += $a->submissions->whereIn('status', ['graded','approved'])->count();
            $totals['pending_review'] += $a->submissions->where('status', 'submitted')->count();
        }

        // Per-class breakdown
        $byClass = $assignments->groupBy(fn($a) => $a->schoolClass->name ?? 'Unknown')
            ->map(function($items) {
                $published = $items->where('is_published', true)->count();
                $subs = $items->flatMap->submissions;
                return [
                    'assignments' => $items->count(),
                    'published' => $published,
                    'submission_rate' => $items->count() > 0 ? round(($subs->count() / $items->count()), 2) : 0,
                    'graded' => $subs->whereIn('status', ['graded','approved'])->count(),
                    'pending_review' => $subs->where('status', 'submitted')->count(),
                ];
            })->sortKeys();

        return view('assignments.stats', compact('totals', 'byClass'));
    }

    public function publish(Assignment $assignment)
    {
        $this->authorizeTeacher($assignment);
        $assignment->update(['is_published' => true]);

        // notify students when publishing now
        $studentIds = Enrollment::where('school_class_id', $assignment->school_class_id)
            ->pluck('student_id')->toArray();
        if (!empty($studentIds)) {
            \App\Models\Notification::createAssignmentPublishNotification($assignment, $studentIds);
        }

        return back()->with('status', 'Assignment published');
    }

    public function unpublish(Assignment $assignment)
    {
        $this->authorizeTeacher($assignment);
        $assignment->update(['is_published' => false]);
        return back()->with('status', 'Assignment unpublished');
    }

    private function authorizeTeacher(Assignment $assignment): void
    {
        $role = session('current_role');
        $branchId = session('current_branch_id');
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

    private function resolveAccessibleClassOrFail(int $classId): SchoolClass
    {
        $branchId = session('current_branch_id');
        $role = session('current_role');

        $class = SchoolClass::where('id', $classId)
            ->where('branch_id', $branchId)
            ->first();

        if (!$class) {
            abort(403, 'Invalid class for current branch.');
        }

        if ($role === 'teacher') {
            $isAssigned = $class->teachers()->where('users.id', auth()->id())->exists();
            if (!$isAssigned) {
                abort(403, 'You are not assigned to this class.');
            }
        }

        return $class;
    }
}


