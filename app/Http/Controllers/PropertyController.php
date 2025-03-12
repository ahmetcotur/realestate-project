<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
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
     * Tüm emlakları listeler
     */
    public function index(Request $request)
    {
        // Site ayarlarını al
        $settings = $this->getSettings();
    
        // Query oluştur
        $query = Property::with(['propertyType', 'agent', 'images'])
            ->where('is_active', true);
        
        // Filtreleme yap
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        if ($request->filled('type')) {
            $query->where('property_type_id', $request->type);
        }
        
        // Satılık/Kiralık filtresi
        if ($request->filled('status')) {
            // URL'deki "for_sale" ve "for_rent" parametrelerini DB'deki "sale" ve "rent" değerlerine dönüştür
            $status = $request->status;
            
            if ($status === 'for_sale') {
                $status = 'sale';
            } else if ($status === 'for_rent') {
                $status = 'rent';
            }
            
            $query->where('status', $status);
        } else {
            // Varsayılan olarak tüm aktif ilanları göster
            $query->whereIn('status', ['sale', 'rent']);
        }
        
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }
        
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title_tr', 'like', '%' . $request->search . '%')
                  ->orWhere('description_tr', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sıralama yap
        $sortField = 'created_at';
        $sortDirection = 'desc';
        
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $sortField = 'price';
                    $sortDirection = 'asc';
                    break;
                case 'price_desc':
                    $sortField = 'price';
                    $sortDirection = 'desc';
                    break;
                case 'date_asc':
                    $sortField = 'created_at';
                    $sortDirection = 'asc';
                    break;
                case 'date_desc':
                    $sortField = 'created_at';
                    $sortDirection = 'desc';
                    break;
            }
        }
        
        $properties = $query->orderBy($sortField, $sortDirection)->paginate(9);
        
        // Emlak tipleri
        $propertyTypes = PropertyType::all();
        
        return view('properties.index', [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'request' => $request,
            'settings' => $settings
        ]);
    }

    /**
     * Emlak detayını gösterir
     */
    public function show(Property $property): View
    {
        $property->load(['propertyType', 'agent', 'images']);
        
        // Benzer ilanları getir
        $relatedProperties = Property::with(['propertyType', 'agent', 'images'])
            ->where('property_type_id', $property->property_type_id)
            ->where('id', '!=', $property->id)
            ->where('is_active', true)
            ->take(3)
            ->get();
            
        // Property ve ilişkili emlaklar için zaten images ilişkisi yüklendiği için
        // manuel olarak property_images verilerini yüklemeye gerek yok
            
        return view('properties.show', [
            'property' => $property,
            'relatedProperties' => $relatedProperties
        ]);
    }

    /**
     * Emlak arama işlemi
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        // Eski sorguyu kaldırıyorum ve yalnızca doğru olanı bırakıyorum
        $properties = Property::where(function($q) use ($query) {
                $q->where('title_tr', 'like', '%' . $query . '%')
                  ->orWhere('description_tr', 'like', '%' . $query . '%')
                  ->orWhere('location', 'like', '%' . $query . '%');
            })
            ->where('is_active', true)
            ->whereIn('status', ['sale', 'rent'])
            ->paginate(9);
            
        return view('properties.search', [
            'properties' => $properties,
            'query' => $query
        ]);
    }
}
