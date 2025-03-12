@extends('admin.layouts.app')

@section('title', 'Yeni Danışman Ekle')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    Yeni Danışman Ekle
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.agents.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Listeye Dön
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
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
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- Ünvan -->
                        <div class="col-span-1">
                            <label for="title" class="block text-sm font-medium text-gray-700">Ünvan *</label>
                            <div class="mt-1">
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required 
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
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- Telefon -->
                        <div class="col-span-1">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Telefon *</label>
                            <div class="mt-1">
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- Biyografi -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700">Biyografi</label>
                            <div class="mt-1">
                                <textarea name="bio" id="bio" rows="4" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('bio') }}</textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Danışman hakkında kısa bir biyografi.</p>
                        </div>
                        
                        <!-- Fotoğraf -->
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Fotoğraf & Sosyal Medya</h3>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="photo" class="block text-sm font-medium text-gray-700">Fotoğraf</label>
                            <div class="mt-1">
                                <input type="text" name="photo" id="photo" value="{{ old('photo', 'default-agent.jpg') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Şimdilik fotoğraf yükleme desteği yok. Sadece dosya adı girin.</p>
                        </div>
                        
                        <!-- Sosyal Medya - Basitleştirilmiş -->
                        <div class="col-span-1">
                            <label for="social_media_facebook" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                            <div class="mt-1">
                                <input type="text" name="social_media[facebook]" id="social_media_facebook" value="{{ old('social_media.facebook') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="social_media_twitter" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                            <div class="mt-1">
                                <input type="text" name="social_media[twitter]" id="social_media_twitter" value="{{ old('social_media.twitter') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="social_media_instagram" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                            <div class="mt-1">
                                <input type="text" name="social_media[instagram]" id="social_media_instagram" value="{{ old('social_media.instagram') }}" 
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
                                        {{ old('is_active', true) ? 'checked' : '' }}
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
                        Danışman Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
