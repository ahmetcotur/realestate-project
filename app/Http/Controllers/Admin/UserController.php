<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    /**
     * Kullanıcı listesini göster
     */
    public function index()
    {
        // Tüm kullanıcıları listeleme - admin ve agent (danışman) dahil
        $users = User::with('agent')->orderBy('role')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Yeni kullanıcı oluşturma formunu göster
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Yeni kullanıcı oluştur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'agent'])],
            'is_active' => 'boolean',
            
            // Danışman bilgileri (agent rolü için)
            'agent_title' => 'nullable|string|max:255',
            'agent_phone' => 'nullable|string|max:20',
            'agent_bio' => 'nullable|string',
            'agent_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['password'] = Hash::make($validated['password']);
        
        // Önce kullanıcıyı oluştur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => $validated['is_active'],
        ]);
        
        // Eğer agent rolü seçildiyse, danışman da oluştur
        if ($validated['role'] === 'agent') {
            $agent = Agent::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'title' => $validated['agent_title'] ?? 'Emlak Danışmanı',
                'phone' => $validated['agent_phone'] ?? '',
                'bio' => $validated['agent_bio'] ?? '',
                'photo' => 'default-agent.jpg', // Varsayılan resim
                'slug' => Str::slug($validated['name']),
                'is_active' => $validated['is_active'],
            ]);
            
            // Kullanıcıyı bu agent ile ilişkilendir
            $user->agent_id = $agent->id;
            $user->save();
            
            // Fotoğraf işlemleri
            if ($request->hasFile('agent_photo_file')) {
                $file = $request->file('agent_photo_file');
                
                // Benzersiz dosya adı oluştur
                $fileExtension = $file->getClientOriginalExtension();
                $photoName = 'agent_' . $agent->id . '_' . uniqid() . '.' . $fileExtension;
                
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
                
                $agent->photo = $photoName;
                $agent->save();
            }
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }
    
    /**
     * Kullanıcı düzenleme formunu göster
     */
    public function edit(User $user)
    {
        $agents = Agent::all(); // Tüm danışmanlar
        
        // Eğer kullanıcı bir danışmansa, danışman verilerini de al
        $agentData = null;
        if ($user->isAgent() && $user->agent) {
            $agentData = $user->agent;
        }
        
        return view('admin.users.edit', compact('user', 'agents', 'agentData'));
    }
    
    /**
     * Kullanıcı bilgilerini güncelle
     */
    public function update(Request $request, User $user)
    {
        // Debug - Form içeriğini incele
        \Log::info('Form verileri:', $request->all());
        
        // Fotoğraf yükleme kontrolü
        if ($request->hasFile('agent_photo_file')) {
            \Log::info('Agent fotoğraf dosyası yüklendi: ' . $request->file('agent_photo_file')->getClientOriginalName());
        } else {
            \Log::info('Agent fotoğraf dosyası bulunamadı');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => ['required', Rule::in(['admin', 'agent'])],
            'is_active' => 'boolean',
            
            // Danışman bilgileri (agent rolü için)
            'agent_title' => 'nullable|string|max:255',
            'agent_phone' => 'nullable|string|max:20',
            'agent_bio' => 'nullable|string',
            'agent_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Şifre değişikliği varsa
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            
            $validated['password'] = Hash::make($request->password);
        }
        
        // Kullanıcı rolüne göre işlemler
        if ($validated['role'] === 'admin') {
            // Admin için agent_id null olmalı
            $validated['agent_id'] = null;
            
            // Eğer kullanıcı danışmandan admine dönüştürülüyorsa, agent ilişkisini kaldır
            if ($user->isAgent() && $user->agent) {
                // İlişkiyi kaldır
                $user->agent_id = null;
            }
        } else if ($validated['role'] === 'agent') {
            // Danışman için işlemler
            
            // Eğer kullanıcı zaten danışmansa ve bir agent_id varsa
            if ($user->agent_id && $user->agent) {
                // Mevcut danışmanı güncelle
                $agent = $user->agent;
                
                $agent->name = $validated['name']; // Kullanıcı adını danışman adı ile senkronize et
                $agent->email = $validated['email']; // Email adresini senkronize et
                
                if (isset($validated['agent_title'])) {
                    $agent->title = $validated['agent_title'];
                }
                
                if (isset($validated['agent_phone'])) {
                    $agent->phone = $validated['agent_phone'];
                }
                
                if (isset($validated['agent_bio'])) {
                    $agent->bio = $validated['agent_bio'];
                }
                
                $agent->is_active = $validated['is_active'];
                
                // Fotoğraf yükleme işlemi
                if ($request->hasFile('agent_photo_file')) {
                    try {
                        $file = $request->file('agent_photo_file');
                        
                        // Eski fotoğrafı sil (varsayılan dışında)
                        if ($agent->photo && !str_starts_with($agent->photo, 'default')) {
                            Storage::disk('public')->delete('agents/' . $agent->photo);
                        }
                        
                        // Benzersiz dosya adı oluştur
                        $fileExtension = $file->getClientOriginalExtension();
                        $photoName = 'agent_' . $agent->id . '_' . uniqid() . '.' . $fileExtension;
                        
                        \Log::info('Fotoğraf işleme başlıyor: ' . $photoName);
                        
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
                        
                        $agent->photo = $photoName;
                        
                        \Log::info('Fotoğraf başarıyla yüklendi: ' . $path);
                    } catch (\Exception $e) {
                        \Log::error('Fotoğraf yükleme hatası: ' . $e->getMessage());
                        return back()->with('error', 'Fotoğraf yüklenirken bir hata oluştu: ' . $e->getMessage());
                    }
                }
                
                $agent->save();
                
            } else {
                // Eğer admin ise ve agent rolüne dönüştürülüyorsa, yeni bir danışman kaydı oluştur
                
                // Benzersiz slug oluştur
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $counter = 1;
                
                // Slug zaten varsa sayı ekleyerek benzersiz yap
                while (Agent::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $agent = Agent::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'title' => $validated['agent_title'] ?? 'Emlak Danışmanı',
                    'phone' => $validated['agent_phone'] ?? '',
                    'bio' => $validated['agent_bio'] ?? '',
                    'photo' => 'default-agent.jpg', // Varsayılan resim
                    'slug' => $slug, // Benzersiz slug kullan
                    'is_active' => $validated['is_active'],
                ]);
                
                // Kullanıcıyı bu agent ile ilişkilendir
                $validated['agent_id'] = $agent->id;
                
                // Fotoğraf işlemleri
                if ($request->hasFile('agent_photo_file')) {
                    try {
                        $file = $request->file('agent_photo_file');
                        
                        // Benzersiz dosya adı oluştur
                        $fileExtension = $file->getClientOriginalExtension();
                        $photoName = 'agent_' . $agent->id . '_' . uniqid() . '.' . $fileExtension;
                        
                        \Log::info('Yeni oluşturulan agent için fotoğraf işleme başlıyor: ' . $photoName);
                        
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
                        
                        $agent->photo = $photoName;
                        $agent->save();
                        
                        \Log::info('Yeni agent için fotoğraf başarıyla yüklendi: ' . $path);
                    } catch (\Exception $e) {
                        \Log::error('Yeni agent için fotoğraf yükleme hatası: ' . $e->getMessage());
                        return back()->with('error', 'Fotoğraf yüklenirken bir hata oluştu: ' . $e->getMessage());
                    }
                }
            }
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı bilgileri başarıyla güncellendi.');
    }
    
    /**
     * Kullanıcıyı sil
     */
    public function destroy(User $user)
    {
        // Kendini silmeye çalışıyorsa engelle
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendi hesabınızı silemezsiniz.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }
} 