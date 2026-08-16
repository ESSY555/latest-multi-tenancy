<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('auth_user_id')) {
            return redirect()->to('/login');
        }
        return $next($request);
    }
}


