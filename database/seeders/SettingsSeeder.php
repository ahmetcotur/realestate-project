<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    /**
     * Site ayarlarını güncelle
     */
    public function run(): void
    {
        $settings = [
            // Genel Ayarlar
            'site_title' => 'Remax Pupa Emlak',
            'site_description' => 'Remax Pupa ile Türkiyenin en güzel evlerine sahip olun',
            'contact_email' => 'info@remaxpupa.com',
            'contact_phone' => '+90 242 836 30 30',
            'address' => 'Andifli Mah. Hasan Altan Cad. No:13/Z-1 Kaş / Antalya',
            
            // Sosyal Medya
            'facebook' => 'https://facebook.com/remaxpupa',
            'twitter' => 'https://twitter.com/remaxpupa',
            'instagram' => 'https://instagram.com/remaxpupa',
            'linkedin' => 'https://linkedin.com/company/remaxpupa',
            'youtube' => 'https://youtube.com/channel/remaxpupa',
            
            // Ana Sayfa Ayarları
            'home_hero_title' => 'Türkiye\'nin En Güzel Evleri',
            'home_hero_description' => 'Antalya, Kaş, Kalkan, Fethiye ve daha birçok bölgede hayalinizdeki mülke Remax Pupa Emlak ile ulaşın.',
            'home_features_title' => 'Neden Bizi Seçmelisiniz?',
            'home_featured_properties_title' => 'Öne Çıkan Emlak İlanları',
            'home_featured_agents_title' => 'Uzman Danışmanlarımız',
            'home_location_title' => 'Özel Lokasyonlar',
            'home_location_description' => 'Türkiye\'nin en güzel lokasyonlarında yatırım fırsatlarını keşfedin. Kaş, Kalkan, Fethiye ve daha birçok bölgede sizin için seçilmiş özel mülkler.',
            
            // Hakkımızda Sayfası Ayarları
            'about_title' => 'Remax Pupa Emlak Hakkında',
            'about_description' => 'Remax Pupa Emlak, 2010 yılından bu yana Türkiye\'nin en güzel bölgelerinde müşterilerine profesyonel emlak danışmanlığı hizmeti sunmaktadır. Kaş, Kalkan, Fethiye ve çevre bölgelerde uzmanlaşmış ekibimizle, sizin için en doğru gayrimenkul yatırımını bulmanıza yardımcı oluyoruz.\n\nRemax global ağının bir parçası olarak, uluslararası standartlarda ve güvenilir hizmet anlayışıyla çalışmaktayız. Müşteri memnuniyetini her zaman ön planda tutan firmamız, bölgenin en kapsamlı gayrimenkul portföyünü sizlere sunmaktan gurur duyar.',
            'about_mission_title' => 'Misyonumuz',
            'about_mission_description' => 'Sektörde lider konumumuzu koruyarak, müşterilerimize en doğru gayrimenkul yatırımlarını sunmak ve beklentilerinin ötesinde bir hizmet deneyimi yaşatmak.',
            'about_vision_title' => 'Vizyonumuz',
            'about_vision_description' => 'Türkiye\'nin en güzel bölgelerinde gayrimenkul danışmanlığı hizmetimizle fark yaratmak ve sektöre yön veren yenilikçi yaklaşımlar geliştirmek.',
            'about_team_title' => 'Profesyonel Ekibimiz',
            
            // Neden Bizi Seçmelisiniz Bölümü
            'about_why_us_title' => 'Neden Remax Pupa?',
            'about_why_us_item1_icon' => 'award',
            'about_why_us_item1_title' => 'Deneyim',
            'about_why_us_item1_description' => '10 yılı aşkın sektör deneyimimizle, bölgede lider konumdayız.',
            'about_why_us_item2_icon' => 'handshake',
            'about_why_us_item2_title' => 'Güven',
            'about_why_us_item2_description' => 'Şeffaf ve dürüst çalışma prensibiyle müşterilerimizle güvene dayalı ilişkiler kuruyoruz.',
            'about_why_us_item3_icon' => 'globe',
            'about_why_us_item3_title' => 'Global Ağ',
            'about_why_us_item3_description' => 'Remax global ağının bir parçası olarak, dünya standartlarında hizmet sunuyoruz.',
            
            // Emlak Sayfası Ayarları
            'properties_title' => 'Emlak İlanları',
            'properties_description' => 'Kaş, Kalkan, Fethiye ve daha birçok bölgede satılık ve kiralık gayrimenkul seçenekleri.',
            
            // Danışmanlar Sayfası Ayarları
            'agents_title' => 'Emlak Danışmanları',
            'agents_description' => 'Alanında uzman, profesyonel ve deneyimli emlak danışmanlarımız.',
            
            // İletişim Sayfası Ayarları
            'contact_title' => 'Bizimle İletişime Geçin',
            'contact_description' => 'Soru, görüş ve önerileriniz için bizimle iletişime geçebilirsiniz.',
            'contact_form_title' => 'Mesaj Gönderin',
            'contact_map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3192.2554178547594!2d29.975613815392!3d36.854617379935046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14c1b88e4cbf9607%3A0xf769ac57c6f1fdd7!2zS2HFnw!5e0!3m2!1str!2str!4v1647285523735!5m2!1str!2str" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            
            // Footer Ayarları
            'footer_about_text' => 'Remax Pupa Emlak olarak, Türkiye\'nin en güzel bölgelerinde profesyonel emlak danışmanlığı hizmeti sunuyoruz. Deneyimli ekibimizle müşterilerimize en doğru emlak seçimlerini yapmaları konusunda yardımcı oluyoruz.',
            'footer_address' => 'Andifli Mah. Hasan Altan Cad. No:13/Z-1 Kaş / Antalya',
            'footer_phone' => '+90 242 836 30 30',
            'footer_email' => 'info@remaxpupa.com',
            'footer_newsletter_text' => 'Yeni emlak ilanları ve fırsatlardan haberdar olmak için abone olun.',
            'footer_copyright_text' => '© 2024 Remax Pupa Emlak. Tüm hakları saklıdır.',
        ];

        // Ayarları kaydet
        $path = storage_path('app/public/settings');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        file_put_contents($path . '/site_settings.json', json_encode($settings, JSON_PRETTY_PRINT));

        $this->command->info('Site ayarları başarıyla güncellendi!');
    }
} 