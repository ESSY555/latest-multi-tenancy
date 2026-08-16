<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        
        // Super admin can see all students across all branches
        if ($user->is_super_admin) {
            $students = User::whereHas('branches', function ($q) {
                $q->where('branch_user.role', 'student');
            })->with('branches')->orderBy('name')->paginate(20);
        } else {
            // Branch admin can only see students in their branch
            $students = User::whereHas('branches', function ($q) use ($branchId) {
                $q->where('branches.id', $branchId)->where('branch_user.role', 'student');
            })->orderBy('name')->paginate(20);
        }

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Super admin can assign students to any branch
        if ($user->is_super_admin) {
            $branches = Branch::orderBy('name')->get();
        } else {
            // Branch admin can only assign students to their own branch
            $branchId = session('current_branch_id');
            $branches = Branch::where('id', $branchId)->get();
        }
        
        return view('students.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        
        // Validate branch access
        if (!$user->is_super_admin) {
            // Branch admin can only assign students to their own branch
            $request->merge(['branch_id' => $branchId]);
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Attach student to the selected branch
        $user->branches()->syncWithoutDetaching([$validated['branch_id'] => ['role' => 'student']]);

        return redirect()->route('students.index')->with('status', 'Student created successfully and can now login with the provided credentials.');
    }
}


