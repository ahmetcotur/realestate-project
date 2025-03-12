<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmış mı kontrol et
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        
        // Kullanıcı admin veya agent rolüne sahip mi kontrol et
        if (!in_array(Auth::user()->role, ['admin', 'agent'])) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Bu alana erişim yetkiniz bulunmamaktadır.');
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
