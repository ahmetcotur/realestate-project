<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = Agent::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.agents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Basit doğrulama
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email',
            'phone' => 'required|string|max:20',
            'title' => 'required|string|max:255',
        ]);

        // Kayıt oluştur
        $agent = Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'bio' => $request->bio,
            'photo' => $request->photo ?? 'default-agent.jpg',
            'slug' => Str::slug($request->name),
            'social_media' => $request->social_media ? json_encode($request->social_media) : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Danışman başarıyla oluşturuldu');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        $agent->load(['properties']);
        return view('admin.agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $agent = Agent::findOrFail($id);
        return view('admin.agents.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Basit doğrulama
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $id,
            'phone' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Danışman bilgilerini al
        $agent = Agent::findOrFail($id);
        
        // Fotoğraf yükleme işlemi
        $photoName = $agent->photo;
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            
            // Eski fotoğrafı sil (varsayılan dışında)
            if ($photoName && !str_starts_with($photoName, 'default')) {
                Storage::disk('public')->delete('agents/' . $photoName);
            }
            
            // Benzersiz dosya adı oluştur
            $fileExtension = $file->getClientOriginalExtension();
            $photoName = 'agent_' . $id . '_' . uniqid() . '.' . $fileExtension;
            
            // Fotoğrafı optimize et
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            
            // Büyük görselleri yeniden boyutlandır
            if ($image->width() > 800) {
                $image->scale(width: 800);
            }
            
            // Fotoğrafı kaydet
            $path = 'agents/' . $photoName;
            Storage::disk('public')->put($path, $image->toJpeg(85));
        }

        // Danışman bilgilerini güncelle
        $agent->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'bio' => $request->bio,
            'photo' => $photoName,
            'social_media' => $request->social_media ? json_encode($request->social_media) : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Danışman bilgileri başarıyla güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $agent = Agent::findOrFail($id);
        
        // Danışmanın ilanlarını kontrol et (gerçek uygulamada burada önlem alınmalı)
        if ($agent->properties()->count() > 0) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Danışmanın ilanları bulunduğu için silinemez');
        }
        
        $agent->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Danışman başarıyla silindi');
    }
}
