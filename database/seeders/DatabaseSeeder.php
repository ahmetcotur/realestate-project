<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur (eğer yoksa)
        if (!User::where('email', 'admin@remax-pupa.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@remax-pupa.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }
        
        // Seeder'ları çalıştır
        $this->call([
            PropertyTypeSeeder::class,
            DemoDatabaseSeeder::class,
            ContactSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
