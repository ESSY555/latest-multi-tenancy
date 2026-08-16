<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('branch.selected');
        
        // Only allow super_admin and admin to access teacher management
        $this->middleware(function ($request, $next) {
            $currentRole = session('current_role');
            if (!in_array($currentRole, ['super_admin', 'admin'])) {
                abort(403, 'Unauthorized access to teacher management.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of teachers.
     */
    public function index(Request $request)
    {
        $branchId = session('current_branch_id');
        $currentRole = session('current_role');
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        // Build query based on user role
        $query = User::teachers()->with(['branches']);

        if ($currentRole === 'super_admin') {
            // Super admin sees all teachers across branches
            if ($request->has('branch_id')) {
                $query->whereHas('branches', function($q) use ($request) {
                    $q->where('branches.id', $request->branch_id);
                });
            }
        } else {
            // Branch admin sees only teachers in their branch
            $query->whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId);
            });
        }

        // Apply status filter
        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->inactive();
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('name')->paginate(20)->appends($request->query());

        // Get branches for super admin
        $branches = null;
        if ($currentRole === 'super_admin') {
            $branches = Branch::orderBy('name')->get();
        }

        // Calculate statistics
        $stats = [
            'total' => User::teachers()->count(),
            'active' => User::teachers()->active()->count(),
            'inactive' => User::teachers()->inactive()->count(),
        ];

        return view('teacher-management.index', compact(
            'teachers', 
            'branches', 
            'status', 
            'search', 
            'stats',
            'currentRole'
        ));
    }

    /**
     * Activate a teacher.
     */
    public function activate(User $teacher)
    {
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        // Check if teacher belongs to the current branch (for branch admin)
        if ($currentRole === 'admin') {
            if (!$teacher->hasBranchRole($branchId, 'teacher')) {
                abort(403, 'You can only manage teachers in your branch.');
            }
        }

        $teacher->activate();

        return redirect()->route('teacher-management.index')
            ->with('success', "Teacher {$teacher->name} has been activated successfully.");
    }

    /**
     * Deactivate a teacher.
     */
    public function deactivate(User $teacher)
    {
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        // Check if teacher belongs to the current branch (for branch admin)
        if ($currentRole === 'admin') {
            if (!$teacher->hasBranchRole($branchId, 'teacher')) {
                abort(403, 'You can only manage teachers in your branch.');
            }
        }

        $teacher->deactivate();

        return redirect()->route('teacher-management.index')
            ->with('success', "Teacher {$teacher->name} has been deactivated successfully.");
    }

    /**
     * Delete a teacher.
     */
    public function destroy(User $teacher)
    {
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        // Check if teacher belongs to the current branch (for branch admin)
        if ($currentRole === 'admin') {
            if (!$teacher->hasBranchRole($branchId, 'teacher')) {
                abort(403, 'You can only manage teachers in your branch.');
            }
        }

        $teacherName = $teacher->name;

        // Use transaction to ensure data integrity
        DB::transaction(function () use ($teacher) {
            // Remove teacher from all branches
            $teacher->branches()->detach();
            
            // Remove teacher from all classes
            $teacher->teachingClasses()->detach();
            
            // Delete teacher
            $teacher->delete();
        });

        return redirect()->route('teacher-management.index')
            ->with('success', "Teacher {$teacherName} has been deleted successfully.");
    }

    /**
     * Show teacher details.
     */
    public function show(User $teacher)
    {
        $currentRole = session('current_role');
        $branchId = session('current_branch_id');

        // Check if teacher belongs to the current branch (for branch admin)
        if ($currentRole === 'admin') {
            if (!$teacher->hasBranchRole($branchId, 'teacher')) {
                abort(403, 'You can only view teachers in your branch.');
            }
        }

        // Load teacher relationships
        $teacher->load(['branches', 'teachingClasses', 'subjects']);

        return view('teacher-management.show', compact('teacher', 'currentRole'));
    }
}