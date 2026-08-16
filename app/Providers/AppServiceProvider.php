<?php

namespace App\Providers;

use App\Tenancy\BranchContext;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share current branch with all views if set
        view()->composer('*', function ($view) {
            $view->with('currentBranch', app()->bound('currentBranch') ? app('currentBranch') : null);
            $view->with('currentUser', auth()->user());
            $view->with('currentRole', (function () {
                $user = auth()->user();
                if (! $user) return null;
                
                // First try to get role from session
                $role = session('current_role');
                
                // If not in session, determine from database
                if (!$role) {
                    if ($user->is_super_admin) {
                        $role = 'super_admin';
                    } else {
                        $branch = app()->bound('currentBranch') ? app('currentBranch') : null;
                        if ($branch) {
                            $role = \DB::table('branch_user')->where('user_id', $user->id)->where('branch_id', $branch->id)->value('role');
                            
                            // Elevate 'teacher' role to 'form_teacher' if they have an active classroom assignment
                            if ($role === 'teacher' && $user->isFormTeacherInBranch($branch->id)) {
                                $role = 'form_teacher';
                            }
                        }
                    }
                    
                    // Set the role in session for future use
                    if ($role) {
                        session(['current_role' => $role]);
                    }
                }
                
                return $role ?: null;
            })());
            
            // Share current academic year with all views — only when a branch context exists.
            // This MUST be guarded: AcademicYear uses BelongsToBranch / BranchScope, which
            // throws TenantContextMissingException when no branch is in session (e.g. /login).
            $view->with('currentAcademicYear', (function () {
                // Safe check — never touches the DB when context is missing.
                if (! BranchContext::hasBranch()) {
                    return null;
                }

                // Prefer the container-bound instance (set by SetCurrentBranch middleware),
                // fall back to the raw session value.
                $branch   = app()->bound('currentBranch') ? app('currentBranch') : null;
                $branchId = $branch ? $branch->id : session('current_branch_id');

                // $branchId is guaranteed non-null here because hasBranch() returned true.
                return \App\Models\AcademicYear::getCurrentAcademicYear($branchId);
            })());

            // Share pending lesson plans count for admins
            $user = auth()->user();
            if ($user && in_array($view->getData('currentRole'), ['admin', 'super_admin'])) {
                $branchId = session('current_branch_id');
                if ($branchId) {
                    $pendingLessonPlans = \App\Models\LessonPlan::where('branch_id', $branchId)
                        ->where('status', 'submitted')
                        ->count();
                    $view->with('pendingLessonPlans', $pendingLessonPlans);
                }
            }
        });

        // Simple gate for super admin
        \Illuminate\Support\Facades\Gate::define('super_admin', function ($user) {
            return (bool) ($user->is_super_admin ?? false);
        });
    }
}
