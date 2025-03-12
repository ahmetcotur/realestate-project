<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [
            [
                'title_tr' => 'Deniz Manzaralı Lüks Villa',
                'title_en' => 'Luxury Villa with Sea View',
                'description_tr' => 'Kaş\'ta enfes deniz manzarasına sahip, özel havuzlu 4+1 lüks villa. Modern tasarım, geniş teras ve yüksek kalite iç mekan detayları ile mükemmel bir yaşam alanı.',
                'description_en' => 'Luxurious 4+1 villa with a private pool and breathtaking sea view in Kas. Perfect living space with modern design, spacious terrace, and high-quality interior details.',
                'price' => 750000,
                'currency' => 'EUR',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 220,
                'location' => 'Kaş, Antalya',
                'address' => 'Gökseki Mah. Deniz Yolu Cad. No:15, Kaş/Antalya',
                'city' => 'Antalya',
                'district' => 'Kaş',
                'features' => json_encode(['Havuz', 'Özel Bahçe', 'Deniz Manzarası', 'Klima', 'Güvenlik Sistemi']),
                'status' => 'active',
                'property_type_id' => 1, // Villa
                'agent_id' => 1, // Ahmet Yılmaz
                'slug' => 'deniz-manzarali-luks-villa',
                'is_featured' => true,
            ],
            [
                'title_tr' => 'Kalkan Merkezde Modern Apartman Dairesi',
                'title_en' => 'Modern Apartment in Kalkan Center',
                'description_tr' => 'Kalkan merkezde, plaja yürüme mesafesinde, 2+1 modern daire. Balkondan deniz manzarası, site içerisinde ortak havuz ve çocuk oyun alanı bulunmaktadır.',
                'description_en' => 'Modern 2+1 apartment within walking distance to the beach in Kalkan center. Features sea view from the balcony, shared pool, and children\'s playground within the complex.',
                'price' => 195000,
                'currency' => 'EUR',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area' => 120,
                'location' => 'Kalkan, Antalya',
                'address' => 'Merkez Mah. Akdeniz Cad. Safir Sitesi No:7, Kalkan/Antalya',
                'city' => 'Antalya',
                'district' => 'Kalkan',
                'features' => json_encode(['Ortak Havuz', 'Site İçi', 'Deniz Manzarası', 'Klima', 'Güvenlik']),
                'status' => 'active',
                'property_type_id' => 2, // Apartman Dairesi
                'agent_id' => 2, // Ayşe Kaya
                'slug' => 'kalkan-merkezde-modern-apartman-dairesi',
                'is_featured' => true,
            ],
            [
                'title_tr' => 'Ticari İşyeri - Kaş Çarşı Merkezinde',
                'title_en' => 'Commercial Property - In Kas Town Center',
                'description_tr' => 'Kaş çarşı merkezinde 150m² ticari işyeri. Ana cadde üzerinde, yüksek cirolu bölgede konumlanmıştır. Restoran, kafe veya mağaza için idealdir.',
                'description_en' => 'Commercial property of 150m² in the center of Kas town. Located on the main street in a high-turnover area. Ideal for restaurant, cafe, or retail store.',
                'price' => 420000,
                'currency' => 'EUR',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'area' => 150,
                'location' => 'Kaş Merkez, Antalya',
                'address' => 'Andifli Mah. Cumhuriyet Cad. No:48, Kaş/Antalya',
                'city' => 'Antalya',
                'district' => 'Kaş',
                'features' => json_encode(['Ana Cadde', 'Merkezi Konum', 'Vitrin Cepheli', 'Altyapı Hazır']),
                'status' => 'active',
                'property_type_id' => 5, // Ticari Gayrimenkul
                'agent_id' => 3, // Mehmet Çelik
                'slug' => 'ticari-isyeri-kas-carsi-merkezinde',
                'is_featured' => false,
            ],
        ];

        // Önce emlak kayıtlarını oluştur
        foreach ($properties as $property) {
            Property::create($property);
        }

        // Emlak görsellerini ekle
        $images = [
            [
                'property_id' => 1,
                'image_path' => 'properties/1/property_villa1.jpg',
                'alt_text' => 'Villa dış görünüm',
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'property_id' => 1,
                'image_path' => 'properties/1/property_villa1_salon.jpg',
                'alt_text' => 'Villa salon',
                'sort_order' => 2,
                'is_featured' => false,
            ],
            [
                'property_id' => 1,
                'image_path' => 'properties/1/property_villa1_havuz.jpg',
                'alt_text' => 'Villa havuz',
                'sort_order' => 3,
                'is_featured' => false,
            ],
            [
                'property_id' => 2,
                'image_path' => 'properties/2/property_apartment1.jpg',
                'alt_text' => 'Apartman dış görünüm',
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'property_id' => 2,
                'image_path' => 'properties/2/property_apartment1_salon.jpg',
                'alt_text' => 'Apartman salon',
                'sort_order' => 2,
                'is_featured' => false,
            ],
            [
                'property_id' => 3,
                'image_path' => 'properties/3/property_commercial1.jpg',
                'alt_text' => 'Ticari işyeri dış görünüm',
                'sort_order' => 1,
                'is_featured' => true,
            ],
        ];

        foreach ($images as $image) {
            PropertyImage::create($image);
        }
    }
}
