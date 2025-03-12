<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SmtpSettingController extends Controller
{
    /**
     * SMTP ayarları formunu göster
     */
    public function index()
    {
        // .env dosyasından mevcut SMTP ayarlarını al
        $smtpSettings = [
            'MAIL_MAILER' => env('MAIL_MAILER', 'smtp'),
            'MAIL_HOST' => env('MAIL_HOST', 'smtp.gmail.com'),
            'MAIL_PORT' => env('MAIL_PORT', '587'),
            'MAIL_USERNAME' => env('MAIL_USERNAME', ''),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD', ''),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION', 'tls'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS', 'info@remaxpupa.com'),
            'MAIL_FROM_NAME' => env('MAIL_FROM_NAME', env('APP_NAME')),
            'ADMIN_EMAIL' => env('ADMIN_EMAIL', 'admin@pupaemlak.com'),
        ];
        
        return view('admin.settings.smtp', compact('smtpSettings'));
    }
    
    /**
     * SMTP ayarlarını güncelle
     */
    public function update(Request $request)
    {
        // Form doğrulama
        $validated = $request->validate([
            'MAIL_MAILER' => 'required|string',
            'MAIL_HOST' => 'required|string',
            'MAIL_PORT' => 'required|string',
            'MAIL_USERNAME' => 'required|string',
            'MAIL_PASSWORD' => 'required|string',
            'MAIL_ENCRYPTION' => 'nullable|string',
            'MAIL_FROM_ADDRESS' => 'required|email',
            'MAIL_FROM_NAME' => 'required|string',
            'ADMIN_EMAIL' => 'required|email',
        ]);
        
        // .env dosyasını oku
        $envFile = base_path('.env');
        $envContents = File::get($envFile);
        
        // Her bir SMTP ayarını .env dosyasında güncelle
        foreach ($validated as $key => $value) {
            // Özel karakterler için tırnak içine al
            if (strpos($value, ' ') !== false) {
                $value = '"' . $value . '"';
            }
            
            // .env içinde ayarın var olup olmadığını kontrol et
            if (strpos($envContents, $key . '=') !== false) {
                $envContents = preg_replace("/$key=(.*)/", "$key=$value", $envContents);
            } else {
                $envContents .= "\n$key=$value";
            }
        }
        
        // .env dosyasını güncelle
        File::put($envFile, $envContents);
        
        // Yapılandırma önbelleğini temizle
        Artisan::call('config:clear');
        
        return redirect()->route('admin.smtp.settings')
            ->with('success', 'SMTP ayarları başarıyla güncellendi.');
    }
    
    /**
     * SMTP bağlantısını test et
     */
    public function test(Request $request)
    {
        try {
            // Test maili gönder
            \Mail::raw('Bu bir SMTP bağlantı test mailidir. Bu mail, SMTP ayarlarının doğru çalıştığını gösterir.', function($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('SMTP Bağlantı Testi');
            });
            
            return redirect()->route('admin.smtp.settings')
                ->with('success', 'SMTP test maili başarıyla gönderildi. Lütfen gelen kutunuzu kontrol edin.');
        } catch (\Exception $e) {
            return redirect()->route('admin.smtp.settings')
                ->with('error', 'SMTP test maili gönderilemedi: ' . $e->getMessage());
        }
    }
} 