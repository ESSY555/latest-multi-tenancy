<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Check if user is authenticated
        if (!$user) {
            abort(403, 'Unauthorized access. Please log in.');
        }
        
        // Super admin has access to everything
        if ($user->is_super_admin) {
            return $next($request);
        }
        
        // Check if user has branch admin role
        if (!$this->hasBranchAdminRole($user)) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
        
        // For branch admins, ensure they have a branch selected
        $currentBranchId = session('current_branch_id');
        if (!$currentBranchId) {
            return redirect('/select-branch')->with('error', 'Please select a branch to continue.');
        }
        
        // Store the current branch ID in the request for controllers to use
        $request->attributes->set('current_branch_id', $currentBranchId);
        
        return $next($request);
    }
    
    /**
     * Check if user has branch admin role
     */
    private function hasBranchAdminRole($user): bool
    {
        $currentBranchId = session('current_branch_id');
        if (!$currentBranchId) {
            return false;
        }
        
        $role = \DB::table('branch_user')
            ->where('user_id', $user->id)
            ->where('branch_id', $currentBranchId)
            ->value('role');
            
        return $role === 'admin';
    }
}
