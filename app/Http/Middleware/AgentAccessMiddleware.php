<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmış mı kontrol et
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        
        // Kullanıcı agent rolüne sahip mi kontrol et
        if (!Auth::user()->isAgent()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Bu alana sadece emlak danışmanları erişebilir.');
        }
        
        // Kullanıcı aktif mi kontrol et
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Hesabınız devre dışı bırakılmıştır. Lütfen yönetici ile iletişime geçin.');
        }
        
        return $next($request);
    }
} 