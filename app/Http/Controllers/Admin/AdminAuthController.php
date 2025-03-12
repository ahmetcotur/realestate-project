<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminAuthController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(['auth', \App\Http\Middleware\AdminPanelAccess::class], only: ['profile', 'updateProfile', 'updatePassword']),
            new Middleware('guest', only: ['showLoginForm', 'login']),
        ];
    }
    
    /**
     * Giriş formunu göster
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }
    
    /**
     * Giriş işlemini gerçekleştir
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Kullanıcıyı bul
        $user = User::where('email', $credentials['email'])->first();
        
        // Kullanıcı yoksa veya aktif değilse
        if (!$user || !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Bu kullanıcı bulunamadı veya hesap aktif değil.'],
            ]);
        }
        
        // Kullanıcı admin veya agent değilse
        if (!in_array($user->role, ['admin', 'agent'])) {
            throw ValidationException::withMessages([
                'email' => ['Bu hesabın admin paneline erişim yetkisi bulunmamaktadır.'],
            ]);
        }
        
        // Giriş işlemi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Hoş geldiniz, ' . Auth::user()->name);
        }
        
        throw ValidationException::withMessages([
            'password' => ['Girilen şifre hatalı.'],
        ]);
    }
    
    /**
     * Çıkış işlemi
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'Başarıyla çıkış yaptınız.');
    }
    
    /**
     * Profil sayfasını göster
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('admin.auth.profile', compact('user'));
    }
    
    /**
     * Profil bilgilerini güncelle
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }
    
    /**
     * Şifre güncelleme
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('admin.profile')
            ->with('success', 'Şifreniz başarıyla güncellendi.');
    }
} 