<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        $branches = Branch::orderBy('name')->get();
        return view('auth.login', compact('branches'));
    }

    public function showRegister()
    {
        $branches = Branch::orderBy('name')->get();
        return view('auth.register', compact('branches'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'credential' => ['required', 'string'],
            'password' => ['required'],
            'role' => ['required', 'in:super_admin,admin,teacher,student,parent'],
        ]);

        $credential = trim((string) $request->credential);
        $loginField = filter_var($credential, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $normalizedCredential = $loginField === 'username' ? strtolower($credential) : $credential;

        $user = User::where($loginField, $normalizedCredential)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['credential' => 'Invalid credentials'])->withInput();
        }

        // Check if user is active (for teachers)
        if ($user->status === 'inactive') {
            return back()->withErrors(['credential' => 'Your account has been deactivated. Please contact your admin to activate your account.'])->withInput();
        }

        // Validate that the selected role matches the user's actual role
        $selectedRole = $request->role;
        $actualRole = null;
        
        if ($user->is_super_admin) {
            $actualRole = 'super_admin';
        } else {
            // Get user's role in the selected branch
            $branchId = (int) $request->branch_id;
            $branchRole = $user->branches()->where('branches.id', $branchId)->value('role');
            
            if (!$branchRole) {
                return back()->withErrors(['branch_id' => 'You are not assigned to this branch'])->withInput();
            }
            
            $actualRole = $branchRole;
        }

        // Check if selected role matches actual role
        if ($selectedRole !== $actualRole) {
            return back()->withErrors(['role' => 'Invalid role selected. Your actual role is: ' . ucfirst($actualRole)])->withInput();
        }

        // Ensure user belongs to branch unless super admin
        $branchId = (int) $request->branch_id;
        if (! $user->is_super_admin) {
            $belongs = $user->branches()->where('branches.id', $branchId)->exists();
            if (! $belongs) {
                return back()->withErrors(['branch_id' => 'You are not assigned to this branch'])->withInput();
            }
        }

        if (! Auth::attempt([$loginField => $normalizedCredential, 'password' => $request->password], true)) {
            return back()->withErrors(['credential' => 'Invalid credentials'])->withInput();
        }

        $request->session()->regenerate();
        session(['current_branch_id' => $branchId]);
        session(['current_role' => $actualRole]);
        
        // Redirect to dashboard after successful login
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['current_branch_id', 'current_role']);
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'branch_id' => ['required', 'exists:branches,id'],
            'role' => ['required', 'in:super_admin,admin,teacher,student,parent'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_super_admin' => $request->role === 'super_admin',
        ]);

        // Attach role in selected branch
        $user->branches()->syncWithoutDetaching([
            (int) $request->branch_id => ['role' => $request->role],
        ]);

        Auth::login($user);
        session(['current_branch_id' => (int) $request->branch_id]);
        session(['current_role' => $request->role]);
        return redirect()->route('dashboard');
    }
}


