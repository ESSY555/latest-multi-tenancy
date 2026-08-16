<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LessonPlan;
use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Attendance;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTeacherController extends Controller
{
    /**
     * Display a listing of teachers
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $currentBranchId = session('current_branch_id');
            
            if (!$user->is_super_admin && !$currentBranchId) {
                abort(403, 'Unauthorized access.');
            }
            
            // Get teachers for the current branch
            $teachers = User::whereHas('branches', function($query) use ($currentBranchId, $user) {
                $query->where('role', 'teacher');
                if (!$user->is_super_admin) {
                    $query->where('branch_id', $currentBranchId);
                }
            })
            ->with(['branches', 'teachingClasses', 'subjects'])
            ->orderBy('name')
            ->paginate(20);
            
            // Get branches for the form
            $branches = $user->is_super_admin 
                ? \App\Models\Branch::orderBy('name')->get()
                : \App\Models\Branch::where('id', $currentBranchId)->get();
            
            // Get subjects for the current branch (or all if super admin)
            if ($user->is_super_admin) {
                $subjects = \App\Models\Subject::orderBy('name')->get();
            } else {
                $subjects = $currentBranchId ? \App\Models\Subject::where('branch_id', $currentBranchId)
                    ->orderBy('name')
                    ->get() : collect();
            }
            
            // Get classes for the current branch (or all if super admin)
            if ($user->is_super_admin) {
                $classes = SchoolClass::orderBy('name')->get();
            } else {
                $classes = $currentBranchId ? SchoolClass::where('branch_id', $currentBranchId)
                    ->orderBy('name')
                    ->get() : collect();
            }
            
            // Get current academic year for prefilling forms
            $currentAcademicYear = \App\Models\AcademicYear::getCurrentAcademicYearName($currentBranchId);
            
            return view('dashboard.admin-manage-teachers', compact('teachers', 'branches', 'subjects', 'classes', 'currentAcademicYear'));
        } catch (\Throwable $e) {
            \Log::error('Error in AdminTeacherController@index: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'branch_id' => session('current_branch_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'An error occurred while fetching teacher index: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create()
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        if (!$user->is_super_admin && !$currentBranchId) {
            abort(403, 'Unauthorized access.');
        }
        
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();
        
        // Get subjects and classes for the current branch
        $subjects = $currentBranchId ? \App\Models\Subject::where('branch_id', $currentBranchId)
            ->orderBy('name')
            ->get() : collect();
        
        $classes = $currentBranchId ? SchoolClass::where('branch_id', $currentBranchId)
            ->orderBy('name')
            ->get() : collect();
        
        return view('admin.teachers.create', compact('branches', 'subjects', 'classes'));
    }

    /**
     * Store a newly created teacher
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        if (!$user->is_super_admin && !$currentBranchId) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $targetBranchId = $user->is_super_admin ? (int) $validated['branch_id'] : (int) $currentBranchId;
        if (!$user->is_super_admin && $targetBranchId !== (int) $validated['branch_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized branch selection.'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
            
            // Create user
            $teacher = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'password' => bcrypt($validated['password']),
            ]);
            
            // Assign teacher role to current branch
            DB::table('branch_user')->insert([
                'user_id' => $teacher->id,
                'branch_id' => $targetBranchId,
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Assign subjects if provided
            if (isset($validated['subject_ids']) && !empty($validated['subject_ids'])) {
                $teacher->subjects()->attach($validated['subject_ids']);
            }
            
            // Assign classes if provided
            if (isset($validated['class_ids']) && !empty($validated['class_ids'])) {
                $teacher->teachingClasses()->attach($validated['class_ids']);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully'
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified teacher's detail page.
     */
    public function show(User $teacher)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');

        $teacherBranch = $this->resolveTeacherBranch($teacher, $user, $currentBranchId);

        if (!$teacherBranch) {
            abort(404, 'Teacher not found.');
        }

        if (!$user->is_super_admin && $teacherBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this teacher.');
        }

        $branch = \App\Models\Branch::find($teacherBranch->branch_id);

        $teacher->load(['subjects', 'teachingClasses']);

        $stats = [
            'total_classes' => $teacher->teachingClasses->count(),
            'total_subjects' => $teacher->subjects->count(),
            'total_lesson_plans' => $teacher->lessonPlans()->count(),
            'total_assignments' => $teacher->teacherAssignments()->count(),
        ];

        return view('admin.teachers.show', compact('teacher', 'branch', 'stats'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(User $teacher)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');

        $teacherBranch = $this->resolveTeacherBranch($teacher, $user, $currentBranchId);

        if (!$teacherBranch) {
            abort(404, 'Teacher not found.');
        }

        if (!$user->is_super_admin && $teacherBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this teacher.');
        }

        $branchId = $teacherBranch->branch_id;

        $branches = $user->is_super_admin
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();

        $subjects = \App\Models\Subject::where('branch_id', $branchId)->orderBy('name')->get();
        $classes = SchoolClass::where('branch_id', $branchId)->orderBy('name')->get();

        $teacher->load(['subjects', 'teachingClasses']);
        $assignedSubjectIds = $teacher->subjects->pluck('id')->toArray();
        $assignedClassIds = $teacher->teachingClasses->pluck('id')->toArray();

        return view('admin.teachers.edit', compact(
            'teacher',
            'branches',
            'teacherBranch',
            'subjects',
            'classes',
            'assignedSubjectIds',
            'assignedClassIds'
        ));
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, User $teacher)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');

        $teacherBranch = $this->resolveTeacherBranch($teacher, $user, $currentBranchId);

        if (!$teacherBranch) {
            abort(404, 'Teacher not found.');
        }

        if (!$user->is_super_admin && $teacherBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this teacher.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'branch_id' => 'nullable|exists:branches,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'hire_date' => $validated['hire_date'] ?? null,
            ];

            // Password is optional; only change it when a new one is supplied.
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($validated['password']);
            }

            $teacher->update($userData);

            // Only the super admin may move a teacher between branches.
            if ($user->is_super_admin && $request->filled('branch_id') && $validated['branch_id'] != $teacherBranch->branch_id) {
                DB::table('branch_user')
                    ->where('user_id', $teacher->id)
                    ->where('role', 'teacher')
                    ->update(['branch_id' => $validated['branch_id']]);
            }

            // Sync assignments. The edit form always renders both sections, so an
            // absent key means "no items checked" and should clear the assignment.
            $teacher->subjects()->sync($validated['subject_ids'] ?? []);
            $teacher->teachingClasses()->sync($validated['class_ids'] ?? []);

            DB::commit();

            return redirect()->route('admin.teachers.show', $teacher)
                ->with('success', 'Teacher updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update teacher: ' . $e->getMessage()]);
        }
    }

    /**
     * Resolve the teacher's branch_user pivot row, scoped to the current branch
     * for branch admins and unrestricted for the super admin.
     */
    private function resolveTeacherBranch(User $teacher, User $user, $currentBranchId)
    {
        return DB::table('branch_user')
            ->where('user_id', $teacher->id)
            ->where('role', 'teacher')
            ->when(!$user->is_super_admin && $currentBranchId, function ($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->first();
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(User $teacher)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check if teacher belongs to current branch
        $teacherBranch = DB::table('branch_user')
            ->where('user_id', $teacher->id)
            ->where('role', 'teacher')
            ->first();
            
        if (!$teacherBranch) {
            return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
        }
        
        if (!$user->is_super_admin && $teacherBranch->branch_id != $currentBranchId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this teacher.'], 403);
        }
        
        try {
            DB::beginTransaction();
            
            // Remove branch assignment
            DB::table('branch_user')
                ->where('user_id', $teacher->id)
                ->where('role', 'teacher')
                ->delete();
            
            // Delete teacher (this will cascade to related records)
            $teacher->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
                
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete teacher: ' . $e->getMessage()
            ], 500);
        }
    }
}
