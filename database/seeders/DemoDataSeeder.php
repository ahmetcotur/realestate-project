<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyImage;
use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Demo verilerini yükler
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'remember_token' => Str::random(10),
                'is_admin' => true,
            ]
        );

        // Emlak tipleri oluştur
        $propertyTypes = [
            ['name_tr' => 'Villa', 'name_en' => 'Villa'],
            ['name_tr' => 'Daire', 'name_en' => 'Apartment'],
            ['name_tr' => 'Müstakil Ev', 'name_en' => 'Detached House'],
            ['name_tr' => 'Arsa', 'name_en' => 'Land'],
            ['name_tr' => 'İş Yeri', 'name_en' => 'Commercial']
        ];

        foreach ($propertyTypes as $type) {
            PropertyType::firstOrCreate(
                ['name_tr' => $type['name_tr']],
                ['name_en' => $type['name_en']]
            );
        }

        // Danışmanlar oluştur
        $agents = [
            [
                'name' => 'Ahmet Yılmaz',
                'title_tr' => 'Kıdemli Emlak Danışmanı',
                'title_en' => 'Senior Real Estate Consultant',
                'description_tr' => 'Ahmet Bey 10 yıllık tecrübesiyle Kaş ve Kalkan bölgesinde uzmanlaşmış bir emlak danışmanıdır.',
                'description_en' => 'Mr. Ahmet has specialized in Kas and Kalkan region with 10 years of experience.',
                'email' => 'ahmet@example.com',
                'phone' => '+90 555 123 4567',
                'slug' => 'ahmet-yilmaz',
                'is_active' => true,
            ],
            [
                'name' => 'Ayşe Demir',
                'title_tr' => 'Emlak Danışmanı',
                'title_en' => 'Real Estate Consultant',
                'description_tr' => 'Ayşe Hanım lüks villalar konusunda uzmanlaşmış bir danışmandır.',
                'description_en' => 'Mrs. Ayşe is a consultant specialized in luxury villas.',
                'email' => 'ayse@example.com',
                'phone' => '+90 555 987 6543',
                'slug' => 'ayse-demir',
                'is_active' => true,
            ],
        ];

        foreach ($agents as $agentData) {
            Agent::firstOrCreate(
                ['email' => $agentData['email']],
                $agentData
            );
        }

        // Örnek emlak ilanları oluştur
        $demoProperties = [
            [
                'title_tr' => 'Kalkanda Deniz Manzaralı Villa',
                'title_en' => 'Sea View Villa in Kalkan',
                'description_tr' => 'Kalkanda muhteşem deniz manzaralı lüks villa. 4 yatak odası, özel havuz.',
                'description_en' => 'Luxury villa with amazing sea view in Kalkan. 4 bedrooms, private pool.',
                'price' => 850000,
                'currency' => 'EUR',
                'location' => 'Kalkan, Antalya',
                'area' => 220,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'status' => 'sale',
                'is_featured' => true,
                'is_active' => true,
                'property_type_id' => 1, // Villa
                'agent_id' => 1,
                'slug' => 'kalkanda-deniz-manzarali-villa',
            ],
            [
                'title_tr' => 'Kaşta Kiralık Daire',
                'title_en' => 'Apartment for Rent in Kas',
                'description_tr' => 'Kaş merkezde 2+1 kiralık daire. Denize yürüme mesafesinde.',
                'description_en' => 'Rental apartment (2+1) in Kas center. Walking distance to the sea.',
                'price' => 800,
                'currency' => 'EUR',
                'location' => 'Kaş, Antalya',
                'area' => 85,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'status' => 'rent',
                'is_featured' => true,
                'is_active' => true,
                'property_type_id' => 2, // Daire
                'agent_id' => 2,
                'slug' => 'kasta-kiralik-daire',
            ],
            [
                'title_tr' => 'Kalkan Merkezde Satılık Villa',
                'title_en' => 'Villa for Sale in Kalkan Center',
                'description_tr' => 'Kalkan merkezde satılık lüks villa. Havuzlu, manzaralı.',
                'description_en' => 'Luxury villa for sale in Kalkan center. With pool and view.',
                'price' => 520000,
                'currency' => 'EUR',
                'location' => 'Kalkan, Antalya',
                'area' => 180,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'status' => 'sale',
                'is_featured' => false,
                'is_active' => true,
                'property_type_id' => 1, // Villa
                'agent_id' => 1,
                'slug' => 'kalkan-merkezde-satilik-villa',
            ],
        ];

        foreach ($demoProperties as $propertyData) {
            $property = Property::firstOrCreate(
                ['slug' => $propertyData['slug']],
                $propertyData
            );
            
            // Dummy görseller için PropertyImage eklenebilir
            // Not: Gerçek görseller için storage/app/public/properties/{property_id} altında dosyalar olmalı
        }

        // Site ayarları oluştur
        $settings = [
            'site_title' => 'Remax Pupa Emlak',
            'site_description' => 'Kaş ve Kalkan bölgesinde emlak danışmanlığı hizmetleri',
            'contact_email' => 'info@example.com',
            'contact_phone' => '+90 555 123 4567',
            'contact_address' => 'Kalkan Mahallesi, Kaş/Antalya',
            'footer_about_text' => 'Remax Pupa Emlak, Kaş ve Kalkan bölgesinde 10 yılı aşkın tecrübesiyle hizmet vermektedir.',
            'footer_copyright_text' => '© ' . date('Y') . ' Remax Pupa Emlak. Tüm hakları saklıdır.',
            'social_facebook' => 'https://facebook.com/',
            'social_instagram' => 'https://instagram.com/',
            'social_twitter' => 'https://twitter.com/',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
} 