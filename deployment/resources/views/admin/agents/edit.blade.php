@extends('admin.layouts.app')

@section('title', 'Danışman Düzenle')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    Danışman Düzenle: {{ $agent->name }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                <a href="{{ route('admin.agents.show', $agent->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Danışman Detayı
                </a>
                <a href="{{ route('admin.agents.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Listeye Dön
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="mt-10 md:mt-0">
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="px-4 py-5 sm:p-6">
                            <!-- Hata mesajları -->
                            @if ($errors->any())
                            <div class="rounded-md bg-red-50 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Lütfen aşağıdaki hataları düzeltin:
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Form içeriği -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Kişisel Bilgiler</h3>
                                </div>
                                
                                <!-- İsim -->
                                <div class="col-span-1">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Ad Soyad *</label>
                                    <div class="mt-1">
                                        <input type="text" name="name" id="name" value="{{ old('name', $agent->name) }}" required 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Ünvan -->
                                <div class="col-span-1">
                                    <label for="title" class="block text-sm font-medium text-gray-700">Ünvan *</label>
                                    <div class="mt-1">
                                        <input type="text" name="title" id="title" value="{{ old('title', $agent->title) }}" required 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Örn: Emlak Danışmanı, Broker, vb.</p>
                                </div>
                                
                                <!-- İletişim Bilgileri -->
                                <div class="col-span-1 md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">İletişim Bilgileri</h3>
                                </div>
                                
                                <!-- Email -->
                                <div class="col-span-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700">E-posta *</label>
                                    <div class="mt-1">
                                        <input type="email" name="email" id="email" value="{{ old('email', $agent->email) }}" required 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Telefon -->
                                <div class="col-span-1">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefon *</label>
                                    <div class="mt-1">
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $agent->phone) }}" required 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Biyografi -->
                                <div class="col-span-1 md:col-span-2">
                                    <label for="bio" class="block text-sm font-medium text-gray-700">Biyografi</label>
                                    <div class="mt-1">
                                        <textarea name="bio" id="bio" rows="4" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('bio', $agent->bio) }}</textarea>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Danışman hakkında kısa bir biyografi.</p>
                                </div>
                                
                                <!-- Fotoğraf -->
                                <div class="col-span-1 md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Fotoğraf & Sosyal Medya</h3>
                                </div>
                                
                                <div class="col-span-1">
                                    <label for="photo" class="block text-sm font-medium text-gray-700">Fotoğraf</label>
                                    <div class="mt-1 flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($agent->photo && !str_starts_with($agent->photo, 'default'))
                                                @if(str_starts_with($agent->photo, 'agents/'))
                                                    <img src="{{ asset('storage/' . $agent->photo) }}" 
                                                        alt="{{ $agent->name }}" 
                                                        class="h-24 w-24 object-cover rounded-md border border-gray-200">
                                                @else
                                                    <img src="{{ asset('storage/agents/' . $agent->photo) }}" 
                                                        alt="{{ $agent->name }}" 
                                                        class="h-24 w-24 object-cover rounded-md border border-gray-200">
                                                @endif
                                            @else
                                                <div class="h-24 w-24 rounded-md bg-gray-200 flex items-center justify-center text-gray-400">
                                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <label for="photo-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Fotoğraf Yükle</span>
                                                <input id="photo-upload" name="photo_file" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp">
                                            </label>
                                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP veya JPEG. En fazla 2MB.</p>
                                            <p class="mt-1 text-xs text-gray-400" id="file-name">
                                                @if($agent->photo && !str_starts_with($agent->photo, 'default'))
                                                    Mevcut: {{ $agent->photo }}
                                                @else
                                                    Henüz fotoğraf yüklenmedi
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                @php
                                    $socialMedia = old('social_media', json_decode($agent->social_media, true) ?? []);
                                @endphp
                                
                                <!-- Sosyal Medya - Basitleştirilmiş -->
                                <div class="col-span-1">
                                    <label for="social_media_facebook" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                                    <div class="mt-1">
                                        <input type="text" name="social_media[facebook]" id="social_media_facebook" 
                                            value="{{ $socialMedia['facebook'] ?? '' }}" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <div class="col-span-1">
                                    <label for="social_media_twitter" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                                    <div class="mt-1">
                                        <input type="text" name="social_media[twitter]" id="social_media_twitter" 
                                            value="{{ $socialMedia['twitter'] ?? '' }}" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <div class="col-span-1">
                                    <label for="social_media_instagram" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                                    <div class="mt-1">
                                        <input type="text" name="social_media[instagram]" id="social_media_instagram" 
                                            value="{{ $socialMedia['instagram'] ?? '' }}" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Durum -->
                                <div class="col-span-1 md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Durum</h3>
                                </div>
                                
                                <div class="col-span-1">
                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="is_active" id="is_active" 
                                                {{ old('is_active', $agent->is_active) ? 'checked' : '' }}
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active" class="font-medium text-gray-700">Aktif</label>
                                            <p class="text-gray-500">Danışman web sitesinde görünür olacak</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dosya yükleme input'unu dinle
        const photoInput = document.getElementById('photo-upload');
        const fileNameDisplay = document.getElementById('file-name');
        
        photoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                // Dosya boyutunu kontrol et (2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                if (file.size > maxSize) {
                    alert('Dosya boyutu 2MB\'dan küçük olmalıdır. Yüklediğiniz dosya: ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB');
                    this.value = ''; // Input değerini temizle
                    return;
                }
                
                // Dosya adını göster
                fileNameDisplay.textContent = 'Seçilen: ' + file.name;
                
                // Ön izleme göster
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.flex-shrink-0 img, .flex-shrink-0 div');
                    
                    // Eğer div varsa (varsayılan resim gösterimi), onu bir img ile değiştir
                    if (preview.tagName === 'DIV') {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Agent Photo Preview';
                        img.className = 'h-24 w-24 object-cover rounded-md border border-gray-200';
                        preview.parentNode.replaceChild(img, preview);
                    } else {
                        // Zaten bir img varsa sadece src'yi güncelle
                        preview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
