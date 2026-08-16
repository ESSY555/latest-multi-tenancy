<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LessonPlan;
use App\Models\TeacherReport;

class SidebarViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share pending teacher reports count with admin sidebars
        View::composer(['components.admin.branch-admin-sidebar', 'components.admin.super-admin-sidebar'], function ($view) {
            $branchId = session('current_branch_id');
            
            if ($branchId) {
                $pendingTeacherReports = TeacherReport::where('branch_id', $branchId)
                    ->where('status', 'pending')
                    ->count();
            } else {
                $pendingTeacherReports = TeacherReport::where('status', 'pending')->count();
            }
            
            $view->with('pendingTeacherReports', $pendingTeacherReports);
        });

        // Share pending lesson plans count with admin sidebars
        View::composer(['components.admin.branch-admin-sidebar', 'components.admin.super-admin-sidebar'], function ($view) {
            $branchId = session('current_branch_id');
            
            if ($branchId) {
                $pendingLessonPlans = LessonPlan::where('branch_id', $branchId)
                    ->where('status', 'submitted')
                    ->count();
            } else {
                $pendingLessonPlans = LessonPlan::where('status', 'submitted')->count();
            }
            
            $view->with('pendingLessonPlans', $pendingLessonPlans);
        });
    }
}
