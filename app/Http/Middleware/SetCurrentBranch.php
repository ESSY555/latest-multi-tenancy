<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentBranch
{
    public function handle(Request $request, Closure $next): Response
    {
        $branchId = session('current_branch_id');
        if ($branchId) {
            $branch = Branch::find($branchId);
            if ($branch) {
                app()->instance('currentBranch', $branch);
            }
        }
        return $next($request);
    }
}


