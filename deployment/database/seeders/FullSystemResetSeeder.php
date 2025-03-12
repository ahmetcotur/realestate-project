<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Agent;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyImage;
use App\Models\Contact;

class FullSystemResetSeeder extends Seeder
{
    /**
     * Tüm sistemi sıfırlar ve dummy data ekler
     */
    public function run(): void
    {
        // Veritabanı tablolarını sıfırla
        $this->cleanupDatabase();
        
        // Admin kullanıcısının ID'sini al ve sakla
        $adminUser = User::where('role', 'admin')->first();
        $adminId = $adminUser ? $adminUser->id : null;
        
        // Dummy verileri ekle - Sıralama önemli
        $this->call([
            PropertyTypeSeeder::class,
            DemoDatabaseSeeder::class,
            ContactSeeder::class,
            SettingsSeeder::class,
        ]);
        
        $this->command->info('Tüm sistem başarıyla sıfırlandı ve dummy veriler eklendi!');
    }
    
    /**
     * Veritabanını temizle ve tabloları sıfırla
     */
    private function cleanupDatabase(): void
    {
        // Foreign key kontrollerini devre dışı bırak
        Schema::disableForeignKeyConstraints();
        
        // Tabloları temizle
        PropertyImage::query()->delete();
        Property::query()->delete();
        Contact::query()->delete();
        
        // Admin dışındaki kullanıcıları temizle
        User::where('role', '!=', 'admin')->delete();
        
        // Diğer tabloları temizle
        Agent::query()->delete();
        PropertyType::query()->delete();
        
        // Foreign key kontrollerini tekrar etkinleştir
        Schema::enableForeignKeyConstraints();
        
        $this->command->info('Veritabanı temizlendi!');
    }
} 