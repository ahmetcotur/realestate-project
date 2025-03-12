<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Site ayarlarını getirir
     */
    private function getSettings()
    {
        $path = storage_path('app/public/settings/site_settings.json');
        
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        
        return [];
    }

    /**
     * Ana sayfa
     */
    public function index()
    {
        // Site ayarlarını al
        $settings = $this->getSettings();
        
        // Öne çıkan emlaklar
        $featuredProperties = Property::with(['propertyType', 'agent', 'images'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->whereIn('status', ['sale', 'rent'])
            ->take(6)
            ->get();
        
        // Öne çıkan danışmanlar - sadece aktif olanları getir
        $featuredAgents = Agent::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        // Emlak tipleri
        $propertyTypes = PropertyType::all();
        
        return view('home.index', [
            'featuredProperties' => $featuredProperties,
            'featuredAgents' => $featuredAgents,
            'propertyTypes' => $propertyTypes, // Emlak tiplerini view'a aktarıyoruz
            'settings' => $settings // Site ayarlarını view'a aktarıyoruz
        ]);
    }
    
    /**
     * Hakkımızda sayfası
     */
    public function about()
    {
        // Site ayarlarını al
        $settings = $this->getSettings();
        
        // Öne çıkan danışmanlar - sadece aktif olanları getir
        $featuredAgents = Agent::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('home.about', [
            'featuredAgents' => $featuredAgents,
            'settings' => $settings
        ]);
    }
    
    /**
     * Gizlilik politikası
     */
    public function privacy()
    {
        return view('home.privacy');
    }
    
    /**
     * Kullanım koşulları
     */
    public function terms()
    {
        return view('home.terms');
    }
}
