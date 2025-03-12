<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Property;

class AgentController extends Controller
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
     * Tüm danışmanları listeler
     */
    public function index(): View
    {
        // Site ayarlarını al
        $settings = $this->getSettings();
        
        // Sadece aktif danışmanları listele
        $agents = Agent::with(['properties' => function($query) {
            $query->whereIn('status', ['sale', 'rent']);
        }])
        ->where('is_active', true)
        ->get();
        
        return view('agents.index', [
            'agents' => $agents,
            'settings' => $settings
        ]);
    }

    /**
     * Danışman detayını gösterir
     */
    public function show(Agent $agent): View
    {
        // Danışmanın ilanlarını getir
        $properties = $agent->properties()
            ->with(['propertyType', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(6);
            
        // Diğer danışmanları getir (en fazla 4)
        $otherAgents = Agent::where('id', '!=', $agent->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('agents.show', [
            'agent' => $agent,
            'properties' => $properties,
            'otherAgents' => $otherAgents
        ]);
    }

    /**
     * Öne Çıkan Danışmanları (Anasayfa için)
     */
    public function featured()
    {
        $featuredAgents = Agent::where('is_active', true)
            ->take(4)
            ->get();
            
        return view('home.featured_agents', [
            'featuredAgents' => $featuredAgents
        ]);
    }
}
