<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Agent;
use App\Models\Property;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Önce mevcut mesajları temizle
        Contact::query()->delete();

        // Aktif ajanlar ve emlak ilanlarını al
        $agents = Agent::where('is_active', true)->get();
        $properties = Property::inRandomOrder()->limit(10)->get();

        // İletişim mesajları
        $contacts = [
            [
                'name' => 'Ali Yılmaz',
                'email' => 'ali.yilmaz@example.com',
                'phone' => '05301234567',
                'subject' => 'İlan hakkında bilgi',
                'message' => 'Merhaba, Levent\'teki rezidans daire hakkında daha detaylı bilgi almak istiyorum. Uygun bir zamanda beni arayabilir misiniz?',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => false,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name' => 'Zehra Kaya',
                'email' => 'zehra.kaya@example.com',
                'phone' => '05312345678',
                'subject' => 'Randevu talebi',
                'message' => 'Bodrum\'daki villa için randevu almak istiyorum. Hafta sonu müsait olacağım. Sizin için uygun bir zaman var mı?',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name' => 'Mehmet Demir',
                'email' => 'mehmet.demir@example.com',
                'phone' => '05322345678',
                'subject' => 'Fiyat bilgisi',
                'message' => 'Kadıköy\'deki dairenin fiyatı hakkında pazarlık payı var mı? Ciddi bir alıcıyım ve hemen alım yapabilirim.',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => false,
                'created_at' => now()->subHours(12),
                'updated_at' => now()->subHours(12),
            ],
            [
                'name' => 'Ayşe Öztürk',
                'email' => 'ayse.ozturk@example.com',
                'phone' => '05332345678',
                'subject' => 'Kiralama şartları',
                'message' => 'Nişantaşı\'ndaki daire için kiralama şartlarınız nelerdir? Kontrat süresi minimum ne kadar? Depozito miktarı nedir?',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => true,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(6),
            ],
            [
                'name' => 'Mustafa Çelik',
                'email' => 'mustafa.celik@example.com',
                'phone' => '05342345678',
                'subject' => 'Yatırım amaçlı',
                'message' => 'Yatırım amaçlı bir gayrimenkul arıyorum. Portföyünüzde yatırım getirisi yüksek olan emlaklar hangileri? Bir görüşme yapabilir miyiz?',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Selin Aksoy',
                'email' => 'selin.aksoy@example.com',
                'phone' => '05352345678',
                'subject' => 'Ofis kiralama',
                'message' => 'Leventteki ofis için kurumsal olarak kiralamak istiyoruz. Uzun dönemli anlaşma yapabiliriz. Mümkünse ofisi görmek istiyoruz.',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => true,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name' => 'Can Yıldız',
                'email' => 'can.yildiz@example.com',
                'phone' => '05362345678',
                'subject' => 'Arsa hakkında',
                'message' => 'Çeşme\'deki arsa hakkında imar durumunu daha detaylı öğrenebilir miyim? İnşaat izni var mı ve ne kadar bir alan üzerine inşaat yapabilirim?',
                'property_id' => null, // Sonra atanacak
                'agent_id' => null, // Sonra atanacak
                'is_read' => false, 
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'name' => 'Deniz Avcı',
                'email' => 'deniz.avci@example.com',
                'phone' => '05372345678',
                'subject' => 'Genel bilgi',
                'message' => 'Merhaba, Kaş bölgesindeki gayrimenkul fiyatları hakkında genel bir bilgi almak istiyorum. Bölgede yatırım yapmayı düşünüyorum.',
                'property_id' => null,
                'agent_id' => $agents->random()->id,
                'is_read' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(9),
            ],
            [
                'name' => 'Ebru Yılmaz',
                'email' => 'ebru.yilmaz@example.com',
                'phone' => '05382345678',
                'subject' => 'Acil daire arayışı',
                'message' => 'Acil olarak 3+1 bir daireye ihtiyacım var. Beşiktaş veya Şişli civarında olursa iyi olur. 15 gün içinde taşınmak istiyorum.',
                'property_id' => null,
                'agent_id' => $agents->random()->id,
                'is_read' => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Burak Şahin',
                'email' => 'burak.sahin@example.com',
                'phone' => '05392345678',
                'subject' => 'Villa projeleri',
                'message' => 'Bodrum bölgesinde yeni villa projeleri var mı? 5+1 veya daha büyük, havuzlu villa arıyorum. Bütçe sınırım 30 milyon TL.',
                'property_id' => null,
                'agent_id' => $agents->random()->id,
                'is_read' => true,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(7),
            ],
        ];

        // Mesajları ekleme
        foreach ($contacts as $index => $contactData) {
            // İlk 7 mesaja rastgele bir property ata
            if ($index < 7 && count($properties) > 0) {
                $property = $properties->random();
                $contactData['property_id'] = $property->id;
                $contactData['agent_id'] = $property->agent_id;
            }
            
            Contact::create($contactData);
        }

        $this->command->info('Demo mesajlar başarıyla eklendi!');
    }
} 