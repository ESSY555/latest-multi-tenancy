<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('branches')->latest()->get();
        $branches = Branch::orderBy('name')->get();
        
        return view('users.index', compact('users', 'branches'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        try {
            $branches = Branch::orderBy('name')->get();
            $userBranches = $user->branches()->pluck('branch_id')->toArray();
            
            return response()->json([
                'success' => true,
                'user' => $user->load('branches'),
                'branches' => $branches,
                'userBranches' => $userBranches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading user data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:super_admin,admin,teacher,student,parent',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Update user basic info
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'is_super_admin' => $request->role === 'super_admin', // Handle super admin
            ]);

            // Update password if provided (use bcrypt like signup)
            if ($request->filled('password')) {
                $user->update([
                    'password' => bcrypt($request->password)
                ]);
            }

            // Update branch role relationship using the same method as signup
            $user->branches()->syncWithoutDetaching([
                (int) $request->branch_id => ['role' => $request->role],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully! User can still login with these credentials.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Remove all branch relationships
            $user->branches()->detach();
            
            // Remove other relationships
            $user->teachingClasses()->detach();
            $user->enrollments()->delete();
            $user->parents()->detach();
            $user->children()->detach();
            
            // Delete student profile if exists
            if ($user->studentProfile) {
                $user->studentProfile->delete();
            }

            // Delete the user
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:super_admin,admin,teacher,student,parent',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'required|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            // Create user following the same logic as signup
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password), // Use bcrypt like signup
                'is_super_admin' => $request->role === 'super_admin', // Handle super admin
            ]);

            // Attach role in selected branch using the same method as signup
            $user->branches()->syncWithoutDetaching([
                (int) $request->branch_id => ['role' => $request->role],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully! User can now login with these credentials.',
                'user' => $user->load('branches')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation error: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }
}
