<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormTeacher;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormTeacherAssignmentController extends Controller
{
    /**
     * Display a listing of form teacher assignments
     */
    public function index()
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get branches for the form
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();
        
        // Get form teacher assignments
        $formTeacherAssignments = FormTeacher::with(['user', 'schoolClass', 'branch'])
            ->when(!$user->is_super_admin, function($query) use ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get available teachers for assignment
        $teachers = User::whereHas('branches', function($query) use ($currentBranchId, $user) {
            $query->where('role', 'teacher');
            if (!$user->is_super_admin) {
                $query->where('branch_id', $currentBranchId);
            }
        })
        ->orderBy('name')
        ->get();
        
        // Get available classes for assignment
        $classes = SchoolClass::when(!$user->is_super_admin, function($query) use ($currentBranchId) {
            $query->where('branch_id', $currentBranchId);
        })
        ->orderBy('name')
        ->get();
        
        return view('admin.form-teacher-assignments.index', compact(
            'formTeacherAssignments',
            'branches',
            'teachers',
            'classes'
        ));
    }

    /**
     * Show the form for creating a new form teacher assignment
     */
    public function create()
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get branches for the form
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();
        
        // Get available teachers
        $teachers = User::whereHas('branches', function($query) use ($currentBranchId, $user) {
            $query->where('role', 'teacher');
            if (!$user->is_super_admin) {
                $query->where('branch_id', $currentBranchId);
            }
        })
        ->orderBy('name')
        ->get();
        
        // Get available classes
        $classes = SchoolClass::when(!$user->is_super_admin, function($query) use ($currentBranchId) {
            $query->where('branch_id', $currentBranchId);
        })
        ->orderBy('name')
        ->get();
        
        return view('admin.form-teacher-assignments.create', compact('branches', 'teachers', 'classes'));
    }

    /**
     * Store a newly created form teacher assignment
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }
        
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:school_classes,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $branchId = $request->branch_id;
            
            // Check if user has access to this branch
            if (!$user->is_super_admin && $branchId != $currentBranchId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to this branch.'], 403);
            }
            
            // Verify the class belongs to the selected branch
            $class = SchoolClass::where('id', $request->class_id)
                ->where('branch_id', $branchId)
                ->first();
            
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Class does not exist in the selected branch.'], 400);
            }
            
            // Check if teacher is assigned to this branch
            $teacherBranch = DB::table('branch_user')
                ->where('user_id', $request->teacher_id)
                ->where('branch_id', $branchId)
                ->where('role', 'teacher')
                ->first();
                
            if (!$teacherBranch) {
                return response()->json(['success' => false, 'message' => 'Teacher is not assigned to this branch.'], 400);
            }
            
            // Check if class already has an active form teacher
            $existingFormTeacher = FormTeacher::where('school_class_id', $request->class_id)
                ->where('is_active', true)
                ->first();
                
            if ($existingFormTeacher) {
                return response()->json(['success' => false, 'message' => 'This class already has an active form teacher.'], 400);
            }
            
            // Create form teacher assignment
            $formTeacher = FormTeacher::create([
                'user_id' => $request->teacher_id,
                'school_class_id' => $request->class_id,
                'branch_id' => $branchId,
                'is_active' => true,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'notes' => $request->notes,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Form teacher assigned successfully',
                'formTeacher' => $formTeacher->load(['user', 'schoolClass', 'branch'])
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign form teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified form teacher assignment
     */
    public function edit(FormTeacher $formTeacherAssignment)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        // Check if user has access to this assignment
        if (!$user->is_super_admin && $formTeacherAssignment->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this assignment.');
        }
        
        // Get branches for display
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();
        
        // Get available teachers
        $teachers = User::whereHas('branches', function($query) use ($currentBranchId, $user) {
            $query->where('role', 'teacher');
            if (!$user->is_super_admin) {
                $query->where('branch_id', $currentBranchId);
            }
        })
        ->orderBy('name')
        ->get();
        
        // Get available classes
        $classes = SchoolClass::when(!$user->is_super_admin, function($query) use ($currentBranchId) {
            $query->where('branch_id', $currentBranchId);
        })
        ->orderBy('name')
        ->get();
        
        return view('admin.form-teacher-assignments.edit', compact('formTeacherAssignment', 'branches', 'teachers', 'classes'));
    }

    /**
     * Update the specified form teacher assignment
     */
    public function update(Request $request, FormTeacher $formTeacherAssignment)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }
        
        // Check if user has access to this assignment
        if (!$user->is_super_admin && $formTeacherAssignment->branch_id != $currentBranchId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this assignment.'], 403);
        }
        
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:school_classes,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Get the class to determine branch
            $class = SchoolClass::findOrFail($request->class_id);
            $branchId = $class->branch_id;
            
            // Check if user has access to this branch
            if (!$user->is_super_admin && $branchId != $currentBranchId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to this branch.'], 403);
            }
            
            // Check if teacher is assigned to this branch
            $teacherBranch = DB::table('branch_user')
                ->where('user_id', $request->teacher_id)
                ->where('branch_id', $branchId)
                ->where('role', 'teacher')
                ->first();
                
            if (!$teacherBranch) {
                return response()->json(['success' => false, 'message' => 'Teacher is not assigned to this branch.'], 400);
            }
            
            // Check if class already has an active form teacher (excluding current assignment)
            $existingFormTeacher = FormTeacher::where('school_class_id', $request->class_id)
                ->where('is_active', true)
                ->where('id', '!=', $formTeacherAssignment->id)
                ->first();
                
            if ($existingFormTeacher && $request->boolean('is_active')) {
                return response()->json(['success' => false, 'message' => 'This class already has an active form teacher.'], 400);
            }
            
            // Update form teacher assignment
            $formTeacherAssignment->update([
                'user_id' => $request->teacher_id,
                'school_class_id' => $request->class_id,
                'branch_id' => $branchId,
                'is_active' => $request->boolean('is_active'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'notes' => $request->notes,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Form teacher assignment updated successfully',
                'formTeacher' => $formTeacherAssignment->load(['user', 'schoolClass', 'branch'])
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form teacher assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified form teacher assignment
     */
    public function destroy(FormTeacher $formTeacherAssignment)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }
        
        // Check if user has access to this assignment
        if (!$user->is_super_admin && $formTeacherAssignment->branch_id != $currentBranchId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this assignment.'], 403);
        }
        
        try {
            $formTeacherAssignment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Form teacher assignment removed successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove form teacher assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of a form teacher assignment
     */
    public function toggleStatus(FormTeacher $formTeacherAssignment)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check authorization
        if (!$user->is_super_admin && !$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }
        
        // Check if user has access to this assignment
        if (!$user->is_super_admin && $formTeacherAssignment->branch_id != $currentBranchId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this assignment.'], 403);
        }
        
        try {
            // If activating, check if class already has an active form teacher
            if (!$formTeacherAssignment->is_active) {
                $existingFormTeacher = FormTeacher::where('school_class_id', $formTeacherAssignment->school_class_id)
                    ->where('is_active', true)
                    ->where('id', '!=', $formTeacherAssignment->id)
                    ->first();
                    
                if ($existingFormTeacher) {
                    return response()->json(['success' => false, 'message' => 'This class already has an active form teacher.'], 400);
                }
            }
            
            $formTeacherAssignment->update([
                'is_active' => !$formTeacherAssignment->is_active
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Form teacher status updated successfully',
                'is_active' => $formTeacherAssignment->is_active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form teacher status: ' . $e->getMessage()
            ], 500);
        }
    }
}
