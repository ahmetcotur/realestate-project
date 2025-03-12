<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public rotalar
Route::middleware('web')->group(function () {
    // Ana Sayfa
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Hakkımızda
    Route::get('/hakkimizda', [HomeController::class, 'about'])->name('about');

    // Emlak Rotaları
    Route::get('/emlaklar', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/emlaklar/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');
    Route::get('/one-cikan-emlaklar', [PropertyController::class, 'featured'])->name('properties.featured');
    Route::get('/emlak-ara', [PropertyController::class, 'search'])->name('properties.search');

    // Danışman Rotaları
    Route::get('/danismanlar', [AgentController::class, 'index'])->name('agents.index');
    Route::get('/danismanlar/{agent:slug}', [AgentController::class, 'show'])->name('agents.show');

    // İletişim
    Route::get('/iletisim', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');
    Route::post('/emlak-bilgi-talebi', [ContactController::class, 'propertyInquiry'])->name('contact.property-inquiry');
    Route::post('/danisman-iletisim/{agent}', [ContactController::class, 'agent'])->name('contact.agent');
    
    // Dil Değiştirme
    Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'changeLanguage'])->name('language');

    // Admin giriş işlemleri - giriş yapmamış kullanıcılar için
    Route::prefix('admin')->name('admin.')->group(function () {
        // Giriş sayfası ve işlemleri
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });
});

// Çıkış işlemi - sadece giriş yapmış kullanıcılar için
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware(['web', 'auth'])
    ->name('admin.logout');

// Admin panel route'ları - auth middleware ile korunuyor
Route::prefix('admin')->name('admin.')->middleware('web')->middleware('auth')->middleware(\App\Http\Middleware\AdminPanelAccess::class)->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('properties', AdminPropertyController::class);
    Route::patch('properties/{property}/toggle-status', [AdminPropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
    Route::patch('properties/{property}/toggle-visibility', [AdminPropertyController::class, 'toggleVisibility'])->name('properties.toggle-visibility');
    Route::post('properties/bulk-delete', [AdminPropertyController::class, 'bulkDelete'])->name('properties.bulk-delete');
    Route::resource('agents', AdminAgentController::class);
    
    // Kullanıcı yönetimi (Sadece admin rolü erişebilir)
    Route::middleware(\App\Http\Middleware\AdminRoleMiddleware::class)->group(function () {
        Route::resource('users', UserController::class);
    });
    
    // İletişim Mesajları Yönetimi
    Route::get('contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/unread', [\App\Http\Controllers\Admin\ContactController::class, 'unread'])->name('contacts.unread');
    Route::get('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::patch('contacts/{contact}/toggle-read', [\App\Http\Controllers\Admin\ContactController::class, 'toggleRead'])->name('contacts.toggle-read');
    Route::delete('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('contacts/bulk-delete', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');
    
    // Emlak Görselleri Yönetimi
    Route::prefix('properties/{property}')->name('properties.')->group(function () {
        Route::resource('images', \App\Http\Controllers\Admin\PropertyImageController::class);
        Route::post('images/reorder', [\App\Http\Controllers\Admin\PropertyImageController::class, 'reorder'])->name('images.reorder');
        Route::get('images/{image}/feature', [\App\Http\Controllers\Admin\PropertyImageController::class, 'setFeatured'])->name('images.feature');
        
        // Parçalı görsel yükleme için API endpoints
        Route::post('images/upload-chunk', [\App\Http\Controllers\Admin\PropertyImageController::class, 'uploadChunk'])->name('images.upload-chunk');
        Route::post('images/complete-upload', [\App\Http\Controllers\Admin\PropertyImageController::class, 'completeUpload'])->name('images.complete-upload');
    });
    
    // Profil ayarları
    Route::get('/profile', [AdminAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminAuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AdminAuthController::class, 'updatePassword'])->name('password.update');

    // Settings Routes
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::put('/settings/contact', [App\Http\Controllers\Admin\SettingsController::class, 'updateContact'])->name('settings.contact');
    Route::put('/settings/social', [App\Http\Controllers\Admin\SettingsController::class, 'updateSocial'])->name('settings.social');
    Route::put('/settings/home', [App\Http\Controllers\Admin\SettingsController::class, 'updateHome'])->name('settings.home');
    Route::put('/settings/about', [App\Http\Controllers\Admin\SettingsController::class, 'updateAbout'])->name('settings.about');
    Route::put('/settings/properties', [App\Http\Controllers\Admin\SettingsController::class, 'updateProperties'])->name('settings.properties');
    Route::put('/settings/agents', [App\Http\Controllers\Admin\SettingsController::class, 'updateAgents'])->name('settings.agents');
    Route::put('/settings/contact-page', [App\Http\Controllers\Admin\SettingsController::class, 'updateContactPage'])->name('settings.contact-page');
    Route::put('/settings/footer', [App\Http\Controllers\Admin\SettingsController::class, 'updateFooter'])->name('settings.footer');

    // SMTP Ayarları Rotaları
    Route::get('/smtp-settings', [App\Http\Controllers\Admin\SmtpSettingController::class, 'index'])->name('smtp.settings');
    Route::put('/smtp-settings', [App\Http\Controllers\Admin\SmtpSettingController::class, 'update'])->name('smtp.update');
    Route::post('/smtp-settings/test', [App\Http\Controllers\Admin\SmtpSettingController::class, 'test'])->name('smtp.test');
});

// Danışman paneli rotaları
Route::prefix('agent')->name('agent.')->middleware('web')->middleware('auth')->middleware(\App\Http\Middleware\AgentAccessMiddleware::class)->group(function () {
    Route::get('/', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
    
    // Danışmanın kendi emlak ilanları yönetimi
    Route::resource('properties', App\Http\Controllers\Agent\PropertyController::class);
    
    // Emlak Görselleri Yönetimi (İleriki aşamada eklenecek)
    // Route::prefix('properties/{property}')->name('properties.')->group(function () {
    //     Route::resource('images', App\Http\Controllers\Agent\PropertyImageController::class);
    // });
    
    // Danışmana gelen mesajlar
    Route::get('contacts', [App\Http\Controllers\Agent\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/unread', [App\Http\Controllers\Agent\ContactController::class, 'unread'])->name('contacts.unread');
    Route::get('contacts/{contact}', [App\Http\Controllers\Agent\ContactController::class, 'show'])->name('contacts.show');
    Route::patch('contacts/{contact}/toggle-read', [App\Http\Controllers\Agent\ContactController::class, 'toggleRead'])->name('contacts.toggle-read');
    Route::delete('contacts/{contact}', [App\Http\Controllers\Agent\ContactController::class, 'destroy'])->name('contacts.destroy');
    
    // Profil ayarları
    Route::get('/profile', [App\Http\Controllers\Admin\AdminAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\AdminAuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [App\Http\Controllers\Admin\AdminAuthController::class, 'updatePassword'])->name('password.update');
});

// Fallback - Laravel'in varsayılan "login" rotasını admin.login'e yönlendir
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
