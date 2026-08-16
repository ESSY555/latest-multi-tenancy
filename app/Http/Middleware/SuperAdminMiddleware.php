<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
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
        
        // Check if user is super admin
        if (!$user->is_super_admin) {
            abort(403, 'Unauthorized access. Super admin privileges required.');
        }
        
        return $next($request);
    }
}
