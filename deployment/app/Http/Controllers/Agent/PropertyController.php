<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    /**
     * Danışmanın emlak ilanlarını listeler
     */
    public function index()
    {
        $agentId = Auth::user()->agent_id;
        $properties = Property::where('agent_id', $agentId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('agent.properties.index', compact('properties'));
    }

    /**
     * Yeni emlak ilanı oluşturma formunu gösterir
     */
    public function create()
    {
        $propertyTypes = PropertyType::all();
        return view('agent.properties.create', compact('propertyTypes'));
    }

    /**
     * Yeni emlak ilanını kaydeder
     */
    public function store(Request $request)
    {
        $agentId = Auth::user()->agent_id;
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'price' => 'required|numeric',
            'status' => ['required', Rule::in(['sale', 'rent', 'sold', 'rented'])],
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'features' => 'nullable|string',
        ]);
        
        $validated['agent_id'] = $agentId;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['is_featured'] = $request->has('is_featured');
        
        $property = Property::create($validated);
        
        return redirect()->route('agent.properties.index')
            ->with('success', 'Emlak ilanı başarıyla oluşturuldu.');
    }

    /**
     * Emlak ilanı detaylarını gösterir
     */
    public function show(Property $property)
    {
        // Danışmanın sadece kendi ilanlarını görmesini sağla
        $this->checkPropertyOwnership($property);
        
        return view('agent.properties.show', compact('property'));
    }

    /**
     * Emlak ilanı düzenleme formunu gösterir
     */
    public function edit(Property $property)
    {
        // Danışmanın sadece kendi ilanlarını düzenlemesini sağla
        $this->checkPropertyOwnership($property);
        
        $propertyTypes = PropertyType::all();
        return view('agent.properties.edit', compact('property', 'propertyTypes'));
    }

    /**
     * Emlak ilanını günceller
     */
    public function update(Request $request, Property $property)
    {
        // Danışmanın sadece kendi ilanlarını güncellemesini sağla
        $this->checkPropertyOwnership($property);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'price' => 'required|numeric',
            'status' => ['required', Rule::in(['sale', 'rent', 'sold', 'rented'])],
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'features' => 'nullable|string',
        ]);
        
        $validated['is_featured'] = $request->has('is_featured');
        
        $property->update($validated);
        
        return redirect()->route('agent.properties.index')
            ->with('success', 'Emlak ilanı başarıyla güncellendi.');
    }

    /**
     * Emlak ilanını siler
     */
    public function destroy(Property $property)
    {
        // Danışmanın sadece kendi ilanlarını silmesini sağla
        $this->checkPropertyOwnership($property);
        
        $property->delete();
        
        return redirect()->route('agent.properties.index')
            ->with('success', 'Emlak ilanı başarıyla silindi.');
    }
    
    /**
     * Mülkün danışmana ait olup olmadığını kontrol eder
     */
    private function checkPropertyOwnership(Property $property)
    {
        $agentId = Auth::user()->agent_id;
        
        if ($property->agent_id !== $agentId) {
            abort(403, 'Bu emlak ilanına erişim izniniz yok.');
        }
    }
} 