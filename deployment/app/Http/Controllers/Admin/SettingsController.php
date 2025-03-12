<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Ayarlar sayfasını göster
     */
    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Ayarları güncelle - Tüm ayarlar (Eski Metot)
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Genel Site Ayarları
            'site_title' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'address' => 'required|string|max:1000',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            
            // Ana Sayfa Ayarları
            'home_hero_title' => 'required|string|max:255',
            'home_hero_description' => 'required|string|max:1000',
            'home_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'home_features_title' => 'required|string|max:255',
            'home_featured_properties_title' => 'required|string|max:255',
            'home_featured_agents_title' => 'required|string|max:255',
            'home_location_title' => 'required|string|max:255',
            'home_location_description' => 'required|string|max:2000',
            'home_location_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            
            // Hakkımızda Sayfası Ayarları
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string|max:5000',
            'about_mission_title' => 'required|string|max:255',
            'about_mission_description' => 'required|string|max:1000',
            'about_vision_title' => 'required|string|max:255', 
            'about_vision_description' => 'required|string|max:1000',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'about_team_title' => 'required|string|max:255',
            
            // Neden Bizi Seçmelisiniz Bölümü
            'about_why_us_title' => 'Neden Bizi Seçmelisiniz?',
            'about_why_us_item1_icon' => 'award',
            'about_why_us_item1_title' => 'Deneyim',
            'about_why_us_item1_description' => '20 yılı aşkın sektör deneyimimizle, bölgenin en köklü emlak danışmanlık firmalarından biriyiz.',
            'about_why_us_item2_icon' => 'handshake',
            'about_why_us_item2_title' => 'Güven',
            'about_why_us_item2_description' => 'Şeffaf ve dürüst çalışma prensibiyle müşterilerimizle güvene dayalı ilişkiler kuruyoruz.',
            'about_why_us_item3_icon' => 'globe',
            'about_why_us_item3_title' => 'Global Ağ',
            'about_why_us_item3_description' => 'Remax global ağının bir parçası olarak, dünya standartlarında hizmet sunuyoruz.',
            
            // Emlak Sayfası Ayarları
            'properties_title' => 'required|string|max:255',
            'properties_description' => 'required|string|max:1000',
            'properties_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            
            // Danışmanlar Sayfası Ayarları
            'agents_title' => 'required|string|max:255',
            'agents_description' => 'required|string|max:1000',
            'agents_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            
            // İletişim Sayfası Ayarları
            'contact_title' => 'required|string|max:255',
            'contact_description' => 'required|string|max:1000',
            'contact_form_title' => 'required|string|max:255',
            'contact_map_embed' => 'nullable|string|max:2000',
            'contact_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar - Genel Site Ayarları
        $settings['site_title'] = $request->site_title;
        $settings['site_description'] = $request->site_description;
        $settings['contact_email'] = $request->contact_email;
        $settings['contact_phone'] = $request->contact_phone;
        $settings['address'] = $request->address;
        $settings['facebook'] = $request->facebook;
        $settings['twitter'] = $request->twitter;
        $settings['instagram'] = $request->instagram;
        $settings['linkedin'] = $request->linkedin;
        $settings['youtube'] = $request->youtube;
        
        // Ana Sayfa Ayarları
        $settings['home_hero_title'] = $request->home_hero_title;
        $settings['home_hero_description'] = $request->home_hero_description;
        $settings['home_features_title'] = $request->home_features_title;
        $settings['home_featured_properties_title'] = $request->home_featured_properties_title;
        $settings['home_featured_agents_title'] = $request->home_featured_agents_title;
        $settings['home_location_title'] = $request->home_location_title;
        $settings['home_location_description'] = $request->home_location_description;
        
        // Hakkımızda Sayfası Ayarları
        $settings['about_title'] = $request->about_title;
        $settings['about_description'] = $request->about_description;
        $settings['about_mission_title'] = $request->about_mission_title;
        $settings['about_mission_description'] = $request->about_mission_description;
        $settings['about_vision_title'] = $request->about_vision_title;
        $settings['about_vision_description'] = $request->about_vision_description;
        $settings['about_team_title'] = $request->about_team_title;
        
        // Neden Bizi Seçmelisiniz Bölümü
        $settings['about_why_us_title'] = $request->about_why_us_title;
        $settings['about_why_us_item1_icon'] = $request->about_why_us_item1_icon;
        $settings['about_why_us_item1_title'] = $request->about_why_us_item1_title;
        $settings['about_why_us_item1_description'] = $request->about_why_us_item1_description;
        $settings['about_why_us_item2_icon'] = $request->about_why_us_item2_icon;
        $settings['about_why_us_item2_title'] = $request->about_why_us_item2_title;
        $settings['about_why_us_item2_description'] = $request->about_why_us_item2_description;
        $settings['about_why_us_item3_icon'] = $request->about_why_us_item3_icon;
        $settings['about_why_us_item3_title'] = $request->about_why_us_item3_title;
        $settings['about_why_us_item3_description'] = $request->about_why_us_item3_description;
        
        // Emlak Sayfası Ayarları
        $settings['properties_title'] = $request->properties_title;
        $settings['properties_description'] = $request->properties_description;
        
        // Danışmanlar Sayfası Ayarları
        $settings['agents_title'] = $request->agents_title;
        $settings['agents_description'] = $request->agents_description;
        
        // İletişim Sayfası Ayarları
        $settings['contact_title'] = $request->contact_title;
        $settings['contact_description'] = $request->contact_description;
        $settings['contact_form_title'] = $request->contact_form_title;
        $settings['contact_map_embed'] = $request->contact_map_embed;

        // Logo işleme
        if ($request->hasFile('site_logo')) {
            // Eski logo varsa sil
            if (isset($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo'])) {
                Storage::disk('public')->delete($settings['site_logo']);
            }
            
            // Yeni logoyu kaydet
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $settings['site_logo'] = $logoPath;
        }

        // Favicon işleme
        if ($request->hasFile('favicon')) {
            // Eski favicon varsa sil
            if (isset($settings['favicon']) && Storage::disk('public')->exists($settings['favicon'])) {
                Storage::disk('public')->delete($settings['favicon']);
            }
            
            // Yeni favicon'u kaydet
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $settings['favicon'] = $faviconPath;
        }
        
        // Ana Sayfa Hero Image
        if ($request->hasFile('home_hero_image')) {
            // Eski görsel varsa sil
            if (isset($settings['home_hero_image']) && Storage::disk('public')->exists($settings['home_hero_image'])) {
                Storage::disk('public')->delete($settings['home_hero_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('home_hero_image')->store('settings/home', 'public');
            $settings['home_hero_image'] = $imagePath;
        }
        
        // Hakkımızda Görseli
        if ($request->hasFile('about_image')) {
            // Eski görsel varsa sil
            if (isset($settings['about_image']) && Storage::disk('public')->exists($settings['about_image'])) {
                Storage::disk('public')->delete($settings['about_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('about_image')->store('settings/about', 'public');
            $settings['about_image'] = $imagePath;
        }
        
        // Emlak Banner Görseli
        if ($request->hasFile('properties_banner_image')) {
            // Eski görsel varsa sil
            if (isset($settings['properties_banner_image']) && Storage::disk('public')->exists($settings['properties_banner_image'])) {
                Storage::disk('public')->delete($settings['properties_banner_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('properties_banner_image')->store('settings/properties', 'public');
            $settings['properties_banner_image'] = $imagePath;
        }
        
        // Danışmanlar Banner Görseli
        if ($request->hasFile('agents_banner_image')) {
            // Eski görsel varsa sil
            if (isset($settings['agents_banner_image']) && Storage::disk('public')->exists($settings['agents_banner_image'])) {
                Storage::disk('public')->delete($settings['agents_banner_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('agents_banner_image')->store('settings/agents', 'public');
            $settings['agents_banner_image'] = $imagePath;
        }
        
        // İletişim Banner Görseli
        if ($request->hasFile('contact_banner_image')) {
            // Eski görsel varsa sil
            if (isset($settings['contact_banner_image']) && Storage::disk('public')->exists($settings['contact_banner_image'])) {
                Storage::disk('public')->delete($settings['contact_banner_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('contact_banner_image')->store('settings/contact', 'public');
            $settings['contact_banner_image'] = $imagePath;
        }
        
        // Ana Sayfa Bölge Bilgisi Görseli
        if ($request->hasFile('home_location_image')) {
            // Eski görsel varsa sil
            if (isset($settings['home_location_image']) && Storage::disk('public')->exists($settings['home_location_image'])) {
                Storage::disk('public')->delete($settings['home_location_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('home_location_image')->store('settings/home', 'public');
            $settings['home_location_image'] = $imagePath;
        }

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Ayarlar başarıyla güncellendi.');
    }

    /**
     * Genel Ayarları güncelle
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_title' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['site_title'] = $request->site_title;
        $settings['site_description'] = $request->site_description;

        // Logo işleme
        if ($request->hasFile('site_logo')) {
            // Eski logo varsa sil
            if (isset($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo'])) {
                Storage::disk('public')->delete($settings['site_logo']);
            }
            
            // Yeni logoyu kaydet
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $settings['site_logo'] = $logoPath;
        }

        // Favicon işleme
        if ($request->hasFile('favicon')) {
            // Eski favicon varsa sil
            if (isset($settings['favicon']) && Storage::disk('public')->exists($settings['favicon'])) {
                Storage::disk('public')->delete($settings['favicon']);
            }
            
            // Yeni favicon'u kaydet
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $settings['favicon'] = $faviconPath;
        }

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Genel ayarlar başarıyla güncellendi.');
    }
    
    /**
     * İletişim bilgilerini güncelle
     */
    public function updateContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'address' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['contact_email'] = $request->contact_email;
        $settings['contact_phone'] = $request->contact_phone;
        $settings['address'] = $request->address;

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'İletişim bilgileri başarıyla güncellendi.');
    }
    
    /**
     * Sosyal medya bilgilerini güncelle
     */
    public function updateSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['facebook'] = $request->facebook;
        $settings['twitter'] = $request->twitter;
        $settings['instagram'] = $request->instagram;
        $settings['linkedin'] = $request->linkedin;
        $settings['youtube'] = $request->youtube;

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Sosyal medya bilgileri başarıyla güncellendi.');
    }
    
    /**
     * Ana sayfa ayarlarını güncelle
     */
    public function updateHome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'home_hero_title' => 'required|string|max:255',
            'home_hero_description' => 'required|string|max:1000',
            'home_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'home_features_title' => 'required|string|max:255',
            'home_featured_properties_title' => 'required|string|max:255',
            'home_featured_agents_title' => 'required|string|max:255',
            'home_location_title' => 'required|string|max:255',
            'home_location_description' => 'required|string|max:2000',
            'home_location_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['home_hero_title'] = $request->home_hero_title;
        $settings['home_hero_description'] = $request->home_hero_description;
        $settings['home_features_title'] = $request->home_features_title;
        $settings['home_featured_properties_title'] = $request->home_featured_properties_title;
        $settings['home_featured_agents_title'] = $request->home_featured_agents_title;
        $settings['home_location_title'] = $request->home_location_title;
        $settings['home_location_description'] = $request->home_location_description;

        // Ana Sayfa Hero Image
        if ($request->hasFile('home_hero_image')) {
            // Eski görsel varsa sil
            if (isset($settings['home_hero_image']) && Storage::disk('public')->exists($settings['home_hero_image'])) {
                Storage::disk('public')->delete($settings['home_hero_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('home_hero_image')->store('settings/home', 'public');
            $settings['home_hero_image'] = $imagePath;
        }
        
        // Ana Sayfa Bölge Bilgisi Görseli
        if ($request->hasFile('home_location_image')) {
            // Eski görsel varsa sil
            if (isset($settings['home_location_image']) && Storage::disk('public')->exists($settings['home_location_image'])) {
                Storage::disk('public')->delete($settings['home_location_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('home_location_image')->store('settings/home', 'public');
            $settings['home_location_image'] = $imagePath;
        }

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Ana sayfa ayarları başarıyla güncellendi.');
    }
    
    /**
     * Hakkımızda sayfası ayarlarını güncelle
     */
    public function updateAbout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about_title' => 'required|string|max:255',
            'about_company_title' => 'required|string|max:255',
            'about_description' => 'required|string|max:5000',
            'about_mission_title' => 'required|string|max:255',
            'about_mission_description' => 'required|string|max:1000',
            'about_vision_title' => 'required|string|max:255', 
            'about_vision_description' => 'required|string|max:1000',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'about_team_title' => 'required|string|max:255',
            // Neden Bizi Seçmelisiniz? bölümü için validasyon kuralları
            'about_why_us_title' => 'required|string|max:255',
            'about_why_us_item1_icon' => 'required|string|max:50',
            'about_why_us_item1_title' => 'required|string|max:100',
            'about_why_us_item1_description' => 'required|string|max:500',
            'about_why_us_item2_icon' => 'required|string|max:50',
            'about_why_us_item2_title' => 'required|string|max:100',
            'about_why_us_item2_description' => 'required|string|max:500',
            'about_why_us_item3_icon' => 'required|string|max:50',
            'about_why_us_item3_title' => 'required|string|max:100',
            'about_why_us_item3_description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['about_title'] = $request->about_title;
        $settings['about_company_title'] = $request->about_company_title;
        $settings['about_description'] = $request->about_description;
        $settings['about_mission_title'] = $request->about_mission_title;
        $settings['about_mission_description'] = $request->about_mission_description;
        $settings['about_vision_title'] = $request->about_vision_title;
        $settings['about_vision_description'] = $request->about_vision_description;
        $settings['about_team_title'] = $request->about_team_title;

        // Neden Bizi Seçmelisiniz? bölümü verilerini aktar
        $settings['about_why_us_title'] = $request->about_why_us_title;
        $settings['about_why_us_item1_icon'] = $request->about_why_us_item1_icon;
        $settings['about_why_us_item1_title'] = $request->about_why_us_item1_title;
        $settings['about_why_us_item1_description'] = $request->about_why_us_item1_description;
        $settings['about_why_us_item2_icon'] = $request->about_why_us_item2_icon;
        $settings['about_why_us_item2_title'] = $request->about_why_us_item2_title;
        $settings['about_why_us_item2_description'] = $request->about_why_us_item2_description;
        $settings['about_why_us_item3_icon'] = $request->about_why_us_item3_icon;
        $settings['about_why_us_item3_title'] = $request->about_why_us_item3_title;
        $settings['about_why_us_item3_description'] = $request->about_why_us_item3_description;

        // Hakkımızda Görseli
        if ($request->hasFile('about_image')) {
            // Eski görsel varsa sil
            if (isset($settings['about_image']) && Storage::disk('public')->exists($settings['about_image'])) {
                Storage::disk('public')->delete($settings['about_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('about_image')->store('settings/about', 'public');
            $settings['about_image'] = $imagePath;
        }

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Hakkımızda sayfası ayarları başarıyla güncellendi.');
    }
    
    /**
     * Emlaklar sayfası ayarlarını güncelle
     */
    public function updateProperties(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'properties_title' => 'required|string|max:255',
            'properties_description' => 'required|string|max:1000',
            'properties_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['properties_title'] = $request->properties_title;
        $settings['properties_description'] = $request->properties_description;

        // Emlak Banner Görseli
        if ($request->hasFile('properties_banner_image')) {
            // Eski görsel varsa sil
            if (isset($settings['properties_banner_image']) && Storage::disk('public')->exists($settings['properties_banner_image'])) {
                Storage::disk('public')->delete($settings['properties_banner_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('properties_banner_image')->store('settings/properties', 'public');
            $settings['properties_banner_image'] = $imagePath;
        }

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Emlaklar sayfası ayarları başarıyla güncellendi.');
    }
    
    /**
     * Danışmanlar sayfası ayarlarını güncelle
     */
    public function updateAgents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agents_title' => 'required|string|max:255',
            'agents_description' => 'required|string|max:1000',
            'agents_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'agents_join_title' => 'required|string|max:255',
            'agents_join_description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['agents_title'] = $request->agents_title;
        $settings['agents_description'] = $request->agents_description;
        $settings['agents_join_title'] = $request->agents_join_title;
        $settings['agents_join_description'] = $request->agents_join_description;

        // Danışmanlar Banner Görseli
        if ($request->hasFile('agents_banner_image')) {
            // Eski görsel varsa sil
            if (isset($settings['agents_banner_image']) && Storage::disk('public')->exists($settings['agents_banner_image'])) {
                Storage::disk('public')->delete($settings['agents_banner_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('agents_banner_image')->store('settings/agents', 'public');
            $settings['agents_banner_image'] = $imagePath;
        }

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Danışmanlar sayfası ayarları başarıyla güncellendi.');
    }
    
    /**
     * İletişim sayfası ayarlarını güncelle
     */
    public function updateContactPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_title' => 'required|string|max:255',
            'contact_description' => 'required|string|max:1000',
            'contact_form_title' => 'required|string|max:255',
            'contact_info_title' => 'required|string|max:255',
            'contact_address_title' => 'required|string|max:255',
            'contact_address_detail' => 'required|string|max:1000',
            'contact_phone_title' => 'required|string|max:255',
            'contact_phone1' => 'required|string|max:50',
            'contact_phone2' => 'nullable|string|max:50',
            'contact_email_title' => 'required|string|max:255',
            'contact_email1' => 'required|email|max:255',
            'contact_email2' => 'nullable|email|max:255',
            'contact_hours_title' => 'required|string|max:255',
            'contact_hours_detail' => 'required|string|max:1000',
            'contact_location_title' => 'required|string|max:255',
            'contact_map_embed' => 'nullable|string|max:2000',
            'contact_social_title' => 'required|string|max:255',
            'contact_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['contact_title'] = $request->contact_title;
        $settings['contact_description'] = $request->contact_description;
        $settings['contact_form_title'] = $request->contact_form_title;
        $settings['contact_info_title'] = $request->contact_info_title;
        
        // Detaylı iletişim bilgileri
        $settings['contact_address_title'] = $request->contact_address_title;
        $settings['contact_address_detail'] = $request->contact_address_detail;
        $settings['contact_phone_title'] = $request->contact_phone_title;
        $settings['contact_phone1'] = $request->contact_phone1;
        $settings['contact_phone2'] = $request->contact_phone2;
        $settings['contact_email_title'] = $request->contact_email_title;
        $settings['contact_email1'] = $request->contact_email1;
        $settings['contact_email2'] = $request->contact_email2;
        $settings['contact_hours_title'] = $request->contact_hours_title;
        $settings['contact_hours_detail'] = $request->contact_hours_detail;
        $settings['contact_location_title'] = $request->contact_location_title;
        $settings['contact_map_embed'] = $request->contact_map_embed;
        $settings['contact_social_title'] = $request->contact_social_title;
        
        // Ayrıca genel iletişim bilgilerini de güncelle (diğer sayfalarda kullanılabilir)
        $settings['contact_email'] = $request->contact_email1;
        $settings['contact_phone'] = $request->contact_phone1;
        $settings['address'] = $request->contact_address_detail;
        
        // İletişim Banner Görseli
        if ($request->hasFile('contact_banner_image')) {
            // Eski görsel varsa sil
            if (isset($settings['contact_banner_image']) && Storage::disk('public')->exists($settings['contact_banner_image'])) {
                Storage::disk('public')->delete($settings['contact_banner_image']);
            }
            
            // Yeni görseli kaydet
            $imagePath = $request->file('contact_banner_image')->store('settings/contact', 'public');
            $settings['contact_banner_image'] = $imagePath;
        }
        
        // Ayarları JSON dosyasına kaydet
        $this->saveSettings($settings);
        
        return redirect()->route('admin.settings')->with('success', 'İletişim sayfası ayarları başarıyla güncellendi.');
    }

    /**
     * Footer ayarlarını güncelle
     */
    public function updateFooter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'footer_about_text' => 'required|string|max:1000',
            'footer_address' => 'required|string|max:255',
            'footer_phone' => 'required|string|max:50',
            'footer_email' => 'required|email|max:255',
            'footer_newsletter_text' => 'required|string|max:255',
            'footer_copyright_text' => 'required|string|max:255',
            'footer_privacy_url' => 'required|string|max:255',
            'footer_terms_url' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mevcut ayarları al
        $settings = $this->getSettings();

        // Form verilerini ayarlar dizisine aktar
        $settings['footer_about_text'] = $request->footer_about_text;
        $settings['footer_address'] = $request->footer_address;
        $settings['footer_phone'] = $request->footer_phone;
        $settings['footer_email'] = $request->footer_email;
        $settings['footer_newsletter_text'] = $request->footer_newsletter_text;
        $settings['footer_copyright_text'] = $request->footer_copyright_text;
        $settings['footer_privacy_url'] = $request->footer_privacy_url;
        $settings['footer_terms_url'] = $request->footer_terms_url;

        // Ayarları kaydet
        $this->saveSettings($settings);

        return redirect()->route('admin.settings')->with('success', 'Footer ayarları başarıyla güncellendi.');
    }

    /**
     * Ayarları dosyadan okur
     */
    private function getSettings()
    {
        $path = storage_path('app/public/settings/site_settings.json');
        
        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true);
        } else {
            // Varsayılan ayarlar
            $settings = [
                // Genel Ayarlar
                'site_title' => 'Remax Pupa Emlak',
                'site_description' => 'Kaş ve Kalkan\'da Emlak. En İyi Seçenekler İçin Remax Pupa Emlak',
                'contact_email' => 'info@remaxpupa.com',
                'contact_phone' => '+90 242 836 12 34',
                'address' => 'Andifli Mah. Hasan Altan Cad. No:13/Z-1 Kaş / Antalya',
                
                // Sosyal Medya
                'facebook_url' => 'https://facebook.com/remaxpupa',
                'instagram_url' => 'https://instagram.com/remaxpupa',
                'twitter_url' => 'https://twitter.com/remaxpupa',
                'youtube_url' => 'https://youtube.com/remaxpupa',
                
                // Ana Sayfa
                'home_title' => 'Hayalinizdeki Mülke Kavuşun',
                'home_subtitle' => 'Kaş ve Kalkan\'da en geniş emlak portföyü',
                'home_featured_title' => 'Öne Çıkan Gayrimenkuller',
                'home_featured_subtitle' => 'Sizin için özenle seçtiğimiz mülklerimiz',
                'home_search_title' => 'Aradığınız Mülkü Bulun',
                'home_search_subtitle' => 'Kriterlerinize göre arama yaparak size en uygun emlakları keşfedin',
                
                // Hakkımızda
                'about_title' => 'Remax Pupa Emlak Hakkında',
                'about_company_title' => 'Şirketimiz Hakkında',
                'about_description' => 'Remax Pupa Emlak, 2003 yılından bu yana Kaş ve Kalkan bölgesinde gayrimenkul danışmanlığı hizmeti vermektedir. Profesyonel ekibimiz ve bölgedeki derin bilgimizle, müşterilerimize en doğru emlak yatırımlarını yapmaları konusunda rehberlik ediyoruz.',
                'about_mission_title' => 'Misyonumuz',
                'about_mission_description' => 'Müşterilerimize en kaliteli hizmeti sunarak, beklentilerinin ötesinde bir emlak deneyimi yaşatmak.',
                'about_vision_title' => 'Vizyonumuz',
                'about_vision_description' => 'Kaş ve Kalkan bölgesinde gayrimenkul sektörünün lider firması olmak ve Türkiye çapında marka değerimizi artırmak.',
                'about_team_title' => 'Profesyonel Ekibimiz',
                
                // Neden Bizi Seçmelisiniz Bölümü
                'about_why_us_title' => 'Neden Bizi Seçmelisiniz?',
                'about_why_us_item1_icon' => 'award',
                'about_why_us_item1_title' => 'Deneyim',
                'about_why_us_item1_description' => '20 yılı aşkın sektör deneyimimizle, bölgenin en köklü emlak danışmanlık firmalarından biriyiz.',
                'about_why_us_item2_icon' => 'handshake',
                'about_why_us_item2_title' => 'Güven',
                'about_why_us_item2_description' => 'Şeffaf ve dürüst çalışma prensibiyle müşterilerimizle güvene dayalı ilişkiler kuruyoruz.',
                'about_why_us_item3_icon' => 'globe',
                'about_why_us_item3_title' => 'Global Ağ',
                'about_why_us_item3_description' => 'Remax global ağının bir parçası olarak, dünya standartlarında hizmet sunuyoruz.',
                
                // Emlak Sayfası Ayarları
                'properties_title' => 'Emlak İlanları',
                'properties_description' => 'Kaş ve Kalkan\'daki en iyi gayrimenkul seçeneklerini keşfedin',
                'properties_banner_image' => null,
                
                // Danışmanlar Sayfası Ayarları
                'agents_title' => 'Emlak Danışmanlarımız',
                'agents_description' => 'Uzman ve profesyonel danışmanlarımızla tanışın',
                'agents_join_title' => 'Ekibimize Katılmak İster misiniz?',
                'agents_join_description' => 'Kaş ve Kalkan bölgesinde dinamik ve profesyonel ekibimizin bir parçası olmak için bizimle iletişime geçin.',
                'agents_banner_image' => null,
                
                // İletişim Sayfası Ayarları
                'contact_title' => 'Bizimle İletişime Geçin',
                'contact_description' => 'Soru ve talepleriniz için bizimle iletişime geçmekten çekinmeyin.',
                'contact_form_title' => 'Mesaj Gönderin',
                'contact_map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3192.2554178547594!2d29.975613815392!3d36.854617379935046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14c1b88e4cbf9607%3A0xf769ac57c6f1fdd7!2zS2HFnw!5e0!3m2!1str!2str!4v1647285523735!5m2!1str!2str" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'contact_banner_image' => null,
                
                // Footer Ayarları
                'footer_about_text' => 'Remax Pupa Emlak olarak, Kaş ve Kalkan bölgesinde profesyonel emlak danışmanlığı hizmeti sunuyoruz. 20 yılı aşkın deneyimimizle müşterilerimize en doğru emlak seçimlerini yapmaları konusunda yardımcı oluyoruz.',
                'footer_address' => 'Andifli Mah., Kaş, Antalya, Türkiye',
                'footer_phone' => '+90 242 123 4567',
                'footer_email' => 'info@pupakas.com',
                'footer_newsletter_text' => 'Yeni emlak ilanları ve fırsatlardan haberdar olmak için abone olun.',
                'footer_copyright_text' => '© 2024 Remax Pupa Emlak. Tüm hakları saklıdır.',
                'footer_privacy_url' => '#',
                'footer_terms_url' => '#',
            ];
        }
        
        return $settings;
    }

    /**
     * Ayarları dosyaya kaydeder
     */
    private function saveSettings($settings)
    {
        $path = storage_path('app/public/settings');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        file_put_contents($path . '/site_settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    }
} 