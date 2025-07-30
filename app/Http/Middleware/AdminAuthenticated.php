<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check() || !Auth::guard('admin')->user()->status) {
            return to_route('admin.login')->with('error', 'البيانات غير صحيحة أو أن حسابك معطل');
        } 
        
        return $next($request);
    }
}
