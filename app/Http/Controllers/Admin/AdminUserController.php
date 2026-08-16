<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'role' => 'required|in:teacher,student,parent,admin',
                'branch_id' => 'required|exists:branches,id',
                'password' => 'required|string|min:6|confirmed',
            ]);

            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role to branch
            $user->branches()->attach($request->branch_id, [
                'role' => $request->role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user->load('branches')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::with('branches')->findOrFail($id);
            
            // Get the user's role in the current branch
            $currentBranchId = session('current_branch_id');
            $userRole = $user->branches()
                ->where('branch_id', $currentBranchId)
                ->first()
                ->pivot->role ?? '';

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $userRole
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($id),
                ],
                'role' => 'required|in:teacher,student,parent,admin',
            ]);

            DB::beginTransaction();

            $user = User::findOrFail($id);
            $currentBranchId = session('current_branch_id');

            // Update user basic info
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update role in the current branch
            $user->branches()->updateExistingPivot($currentBranchId, [
                'role' => $request->role,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user->load('branches')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $currentBranchId = session('current_branch_id');

            // Remove user from current branch only
            $user->branches()->detach($currentBranchId);

            // If user has no more branches, delete the user
            if ($user->branches()->count() === 0) {
                $user->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User removed from branch successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error removing user: ' . $e->getMessage()
            ], 500);
        }
    }
}
