<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SchoolRegistrationController extends Controller
{
    /**
     * Show the school registration form.
     * Pre-tenant-context operation — no BranchScope-protected model is queried here.
     */
    public function show()
    {
        // Redirect already-authenticated users with an established branch to their dashboard.
        if (Auth::check() && session()->has('current_branch_id')) {
            return redirect()->route('dashboard');
        }

        return view('auth.register-school');
    }

    /**
     * Handle the school registration form submission.
     *
     * Flow:
     *   1. Validate input
     *   2. Create School (Branch)                  ← global model, no BranchScope
     *   3. Create Admin User                        ← global model, no BranchScope
     *   4. Create branch_user relationship          ← raw pivot insert
     *   5. Commit transaction
     *   6. Log the admin in + establish session
     *   7. Redirect to dashboard
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_name'    => ['required', 'string', 'max:255'],
            'school_code'    => ['required', 'string', 'max:50', 'unique:branches,code'],
            'school_address' => ['nullable', 'string', 'max:500'],
            'school_city'    => ['nullable', 'string', 'max:100'],
            'school_state'   => ['nullable', 'string', 'max:100'],
            'school_country' => ['nullable', 'string', 'max:100'],
            'school_phone'   => ['nullable', 'string', 'max:30'],
            'admin_name'     => ['required', 'string', 'max:255'],
            'admin_email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::beginTransaction();

        try {
            // ── Step 1: Create the Branch (tenant) ─────────────────────────────────
            // Branch is a global model (no BelongsToBranch) — safe to create without context.
            $branch = Branch::create([
                'name'    => $request->school_name,
                'code'    => strtoupper($request->school_code),
                'address' => $request->school_address,
                'city'    => $request->school_city,
                'state'   => $request->school_state,
                'country' => $request->school_country ?? 'Nigeria',
                'phone'   => $request->school_phone,
            ]);

            // ── Step 2: Create the Admin User ───────────────────────────────────────
            // User is a global model (no BelongsToBranch) — safe to create without context.
            $admin = User::create([
                'name'           => $request->admin_name,
                'email'          => $request->admin_email,
                'password'       => Hash::make($request->password),
                'is_super_admin' => false,  // branch admin, not platform super-admin
            ]);

            // ── Step 3: Create branch_user relationship ─────────────────────────────
            // Raw pivot attach — no global scope involved.
            $admin->branches()->attach($branch->id, [
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ── Step 4: Commit ──────────────────────────────────────────────────────
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['general' => 'Registration failed: ' . $e->getMessage()]);
        }

        // ── Step 5: Log in + establish branch session ───────────────────────────────
        // Mirrors exactly what AuthController::login() does after a successful auth.
        Auth::login($admin, true);
        $request->session()->regenerate();
        session(['current_branch_id' => $branch->id]);
        session(['current_role'      => 'admin']);

        return redirect()->route('dashboard')
            ->with('success', "Welcome! Your school \"{$branch->name}\" has been registered successfully.");
    }
}
