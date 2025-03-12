<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Önce mevcut admin kullanıcısını kontrol et
$existingAdmin = User::where('email', 'admin@example.com')->first();

if ($existingAdmin) {
    echo "Admin kullanıcısı zaten mevcut.\n";
    exit;
}

// Yeni admin kullanıcısı oluştur
$user = new User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = Hash::make('password');
$user->role = 'admin';
$user->is_active = true;
$user->save();

echo "Admin kullanıcısı başarıyla oluşturuldu.\n";
echo "E-posta: admin@example.com\n";
echo "Şifre: password\n"; 