<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtreleme ve arama parametrelerini al
        $search = $request->get('search');
        $status = $request->get('status');
        $propertyTypeId = $request->get('property_type_id');
        $agentId = $request->get('agent_id');
        
        // Emlak tiplerini ve danışmanları al (filtreleme için)
        $propertyTypes = PropertyType::all();
        $agents = Agent::where('is_active', true)->get();
        
        // Query builder başlat
        $query = Property::query()->with(['propertyType', 'agent']);
        
        // Eğer admin değilse, sadece kendi ilanlarını göster
        if (!auth()->user()->isAdmin()) {
            $query->where('agent_id', auth()->user()->agent_id);
        } else {
            // Admin ise tüm ilanları göster
            $query->withoutGlobalScope('agentProperties');
        }
        
        // Arama filtresi
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title_tr', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        // Durum filtresi (satılık/kiralık)
        if ($status) {
            $query->where('status', $status);
        }
        
        // Emlak tipi filtresi
        if ($propertyTypeId) {
            $query->where('property_type_id', $propertyTypeId);
        }
        
        // Danışman filtresi (sadece admin için)
        if (auth()->user()->isAdmin() && $agentId) {
            $query->where('agent_id', $agentId);
        }
        
        // Sonuçları getir
        $properties = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.properties.index', compact('properties', 'propertyTypes', 'agents', 'search', 'status', 'propertyTypeId', 'agentId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propertyTypes = PropertyType::all();
        $agents = Agent::where('is_active', true)->get();
        return view('admin.properties.create', compact('propertyTypes', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Çok basit geçici doğrulama
        $request->validate([
            'title_tr' => 'required|string|max:255',
            'description_tr' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'listing_type' => 'required|in:sale,rent',
        ]);

        // Kayıt oluştur
        $property = Property::create([
            'title_tr' => $request->title_tr,
            'title_en' => $request->title_en,
            'description_tr' => $request->description_tr,
            'description_en' => $request->description_en,
            'price' => $request->price,
            'currency' => $request->currency ?? 'TRY',
            'location' => $request->location,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'area' => $request->area,
            'property_type_id' => $request->property_type_id,
            'agent_id' => $request->agent_id,
            'features' => $request->features ? json_encode($request->features) : null,
            'slug' => Str::slug($request->title_tr),
            'is_featured' => $request->has('is_featured'),
            'status' => $request->listing_type,
        ]);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Emlak ilanı başarıyla oluşturuldu');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        // Admin rolündeki kullanıcı tüm ilanları görebilir, agent rolündeki kullanıcı sadece kendi ilanlarını görebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            $property = Property::withoutGlobalScope('agentProperties')->findOrFail($property->id);
        } else {
            // Danışmanlar için erişim kontrolü
            if ($property->agent_id !== auth()->user()->agent_id) {
                abort(403, 'Bu ilana erişim yetkiniz yok.');
            }
        }
        
        return view('admin.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        // Admin rolündeki kullanıcı tüm ilanları görebilir, agent rolündeki kullanıcı sadece kendi ilanlarını görebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            $property = Property::withoutGlobalScope('agentProperties')->findOrFail($property->id);
        } else {
            // Danışmanlar için erişim kontrolü
            if ($property->agent_id !== auth()->user()->agent_id) {
                abort(403, 'Bu ilana erişim yetkiniz yok.');
            }
        }
        
        $propertyTypes = PropertyType::all();
        $agents = Agent::where('is_active', true)->get();
        
        return view('admin.properties.edit', compact('property', 'propertyTypes', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        // Admin rolündeki kullanıcı tüm ilanları güncelleyebilir, agent rolündeki kullanıcı sadece kendi ilanlarını güncelleyebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            $property = Property::withoutGlobalScope('agentProperties')->findOrFail($property->id);
        } else {
            // Danışmanlar için erişim kontrolü
            if ($property->agent_id !== auth()->user()->agent_id) {
                abort(403, 'Bu ilana erişim yetkiniz yok.');
            }
        }
        
        // Çok basit geçici doğrulama
        $request->validate([
            'title_tr' => 'required|string|max:255',
            'description_tr' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'listing_type' => 'required|in:sale,rent',
        ]);

        // Emlak bilgilerini güncelle
        $property->update([
            'title_tr' => $request->title_tr,
            'title_en' => $request->title_en,
            'description_tr' => $request->description_tr,
            'description_en' => $request->description_en,
            'price' => $request->price,
            'currency' => $request->currency ?? 'TRY',
            'location' => $request->location,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'area' => $request->area,
            'property_type_id' => $request->property_type_id,
            'agent_id' => $request->agent_id,
            'features' => $request->features ? json_encode($request->features) : null,
            'slug' => Str::slug($request->title_tr),
            'is_featured' => $request->has('is_featured'),
            'status' => $request->listing_type,
        ]);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Emlak ilanı başarıyla güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        try {
            // İlana ait görselleri sil (varsa)
            foreach ($property->images as $image) {
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    unlink(public_path('storage/' . $image->image_path));
                }
                $image->delete();
            }
            
            $property->delete();
            return redirect()->route('admin.properties.index')->with('success', 'Emlak ilanı başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.properties.index')->with('error', 'Emlak ilanı silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the property status between 'sale' and 'rent'.
     */
    public function toggleStatus(Property $property)
    {
        try {
            $newStatus = $property->status === 'sale' ? 'rent' : 'sale';
            $property->update(['status' => $newStatus]);
            
            $statusText = $newStatus === 'sale' ? 'Satılık' : 'Kiralık';
            return redirect()->back()->with('success', "İlan durumu $statusText olarak değiştirildi.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'İlan durumu değiştirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the property visibility (active/inactive).
     */
    public function toggleVisibility(Property $property)
    {
        $property->is_active = !$property->is_active;
        $property->save();
        
        return back()->with('success', 'İlan durumu güncellendi.');
    }

    /**
     * Çoklu ilanları silme
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:properties,id'
        ]);
        
        // Admin veya emlak danışmanı kontrolü
        $query = Property::query();
        
        if (!auth()->user()->isAdmin()) {
            // Danışmanı sadece kendi ilanlarını silebilir
            $query->where('agent_id', auth()->user()->agent_id);
        } else {
            // Admin tüm ilanları silebilir
            $query->withoutGlobalScope('agentProperties');
        }
        
        // Seçilen ilanları sil
        $deletedCount = $query->whereIn('id', $request->ids)->delete();
        
        if ($deletedCount > 0) {
            return back()->with('success', $deletedCount . ' adet ilan başarıyla silindi.');
        } else {
            return back()->with('error', 'Hiçbir ilan silinemedi. Seçilen ilanlar üzerinde silme yetkiniz olmayabilir.');
        }
    }
}
