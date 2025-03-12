<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı admin rolüne sahip mi kontrol et
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bu alana sadece admin rolündeki kullanıcılar erişebilir.');
        }
        
        return $next($request);
    }
} 