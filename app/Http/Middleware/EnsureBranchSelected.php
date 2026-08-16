<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBranchSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('current_branch_id')) {
            return redirect()->to('/select-branch');
        }
        return $next($request);
    }
}



