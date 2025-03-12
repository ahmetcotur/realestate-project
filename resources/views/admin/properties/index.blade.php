@extends('admin.layouts.app')

@section('title', 'Emlak İlanları')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Emlak İlanları</h1>
            <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Yeni İlan Ekle
            </a>
        </div>

        @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Filtreleme ve arama formu - ileride eklenebilir -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form action="{{ route('admin.properties.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                    <!-- Arama Kutusu -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">İlan Ara</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}" 
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md"
                                placeholder="İlan adı, konum veya ID">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Durum Filtresi -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                        <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Tümü</option>
                            <option value="sale" {{ isset($status) && $status == 'sale' ? 'selected' : '' }}>Satılık</option>
                            <option value="rent" {{ isset($status) && $status == 'rent' ? 'selected' : '' }}>Kiralık</option>
                        </select>
                    </div>
                    
                    <!-- Emlak Tipi Filtresi -->
                    <div>
                        <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">Emlak Tipi</label>
                        <select id="property_type_id" name="property_type_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Tüm Tipler</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type->id }}" {{ isset($propertyTypeId) && $propertyTypeId == $type->id ? 'selected' : '' }}>{{ $type->name_tr }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Danışman Filtresi (Sadece admin görebilir) -->
                    @if(auth()->user()->isAdmin())
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Danışman</label>
                        <select id="agent_id" name="agent_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Tüm Danışmanlar</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ isset($agentId) && $agentId == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-between">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrele
                    </button>
                    
                    @if($search || $status || $propertyTypeId || (auth()->user()->isAdmin() && $agentId))
                    <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Filtreleri Temizle
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
            @if($properties->count() > 0)
            <form action="{{ route('admin.properties.bulk-delete') }}" method="POST" id="bulk-action-form">
                @csrf
                <div class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="select-all" class="ml-2 block text-sm text-gray-900">Tümünü Seç</label>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50" id="bulk-delete-btn" disabled>
                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Seçilenleri Sil
                    </button>
                </div>
                
                <ul class="divide-y divide-gray-200">
                    @foreach($properties as $property)
                    <li class="hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center px-4 py-5 sm:px-6">
                            <!-- Seçim Checkbox'ı -->
                            <div class="flex-shrink-0 mr-4">
                                <input type="checkbox" id="property-{{ $property->id }}" name="ids[]" value="{{ $property->id }}" class="property-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            
                            <div class="min-w-0 flex-1 flex items-center">
                                <!-- Emlak resmi - öne çıkan resim veya varsayılan -->
                                <div class="flex-shrink-0 h-24 w-32 rounded-lg overflow-hidden relative shadow-md">
                                    @php
                                        $imagesCount = count($property->images ?? []);
                                        $imageToShow = null;
                                        
                                        if ($imagesCount > 0) {
                                            // Önce featured olanı ara
                                            foreach ($property->images as $img) {
                                                if ($img->is_featured) {
                                                    $imageToShow = $img;
                                                    break;
                                                }
                                            }
                                            
                                            // Featured yoksa ilk görseli al
                                            if (!$imageToShow && isset($property->images[0])) {
                                                $imageToShow = $property->images[0];
                                            }
                                        }
                                    @endphp
                                    
                                    @if($imageToShow)
                                        <img class="h-24 w-32 object-cover transition-all duration-300 hover:scale-105" src="{{ asset('storage/' . $imageToShow->image_path) }}" alt="{{ $property->title_tr }}">
                                    @else
                                        <div class="h-24 w-32 bg-gray-200 flex items-center justify-center">
                                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-0 left-0 bg-black bg-opacity-60 text-white text-xs px-2 py-1 w-full flex justify-between">
                                        <span>ID: {{ $property->id }}</span>
                                        <span>{{ $imagesCount }} görsel</span>
                                    </div>
                                </div>
                                
                                <!-- Emlak bilgileri -->
                                <div class="min-w-0 flex-1 px-4 py-1">
                                    <div>
                                        <div class="flex items-center flex-wrap">
                                            <h3 class="text-md font-medium text-gray-900 truncate pr-2">{{ $property->title_tr }}</h3>
                                            
                                            @if($property->is_featured)
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                Öne Çıkan
                                            </span>
                                            @endif
                                            
                                            <div class="ml-2 flex items-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($property->status === 'sale') bg-blue-100 text-blue-800 
                                                    @elseif($property->status === 'rent') bg-green-100 text-green-800 
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $property->status === 'sale' ? 'Satılık' : ($property->status === 'rent' ? 'Kiralık' : $property->status) }}
                                                </span>
                                                
                                                <form action="{{ route('admin.properties.toggle-status', $property->id) }}" method="POST" class="ml-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs text-gray-600 hover:text-blue-600" title="Satılık/Kiralık Değiştir">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <div class="ml-2 flex items-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($property->is_active) bg-green-100 text-green-800 
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $property->is_active ? 'Aktif' : 'Pasif' }}
                                                </span>
                                                
                                                <form action="{{ route('admin.properties.toggle-visibility', $property->id) }}" method="POST" class="ml-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs text-gray-600 hover:text-blue-600" title="Aktif/Pasif Değiştir">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 flex items-center text-sm text-gray-700">
                                            <span class="font-bold text-blue-600 mr-2">{{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}</span>
                                            <span class="px-1.5 py-0.5 bg-gray-100 rounded">{{ $property->location }}</span>
                                            
                                            <div class="flex items-center ml-3 space-x-3">
                                                @if($property->bedrooms)
                                                <span class="flex items-center text-gray-600" title="Oda Sayısı">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                    </svg>
                                                    {{ $property->bedrooms }}
                                                </span>
                                                @endif
                                                
                                                @if($property->bathrooms)
                                                <span class="flex items-center text-gray-600" title="Banyo Sayısı">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                    </svg>
                                                    {{ $property->bathrooms }}
                                                </span>
                                                @endif
                                                
                                                @if($property->area)
                                                <span class="flex items-center text-gray-600" title="Alan">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                                    </svg>
                                                    {{ $property->area }} m²
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 text-xs text-gray-500 flex items-center">
                                            <span class="inline-flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $property->agent->name ?? 'Atanmamış' }}
                                            </span>
                                            <span class="ml-4 inline-flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $property->propertyType->name_tr ?? 'Belirtilmemiş' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- İşlem butonları -->
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.properties.show', $property->id) }}" class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.properties.edit', $property->id) }}" class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-green-600 bg-green-50 hover:bg-green-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.properties.images.index', $property->id) }}" class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu emlak ilanını silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center p-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $properties->links() }}
                </div>
            </form>
            @else
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz İlan Yok</h3>
                <p class="mt-1 text-sm text-gray-500">İlk ilanınızı oluşturmak için "Yeni İlan Ekle" butonunu kullanın.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const propertyCheckboxes = document.querySelectorAll('.property-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkActionForm = document.getElementById('bulk-action-form');
        
        // Tümünü seç/kaldır
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            propertyCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateDeleteButtonState();
        });
        
        // Her bir checkbox değişikliğini izle
        propertyCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateDeleteButtonState();
                
                // Tüm checkboxlar seçili mi kontrol et
                const allChecked = Array.from(propertyCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
        
        // Toplu silme butonunu güncelle
        function updateDeleteButtonState() {
            const anyChecked = Array.from(propertyCheckboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
        }
        
        // Silme işlemi öncesi onay
        bulkActionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkedCount = Array.from(propertyCheckboxes).filter(cb => cb.checked).length;
            
            if (checkedCount > 0) {
                const confirmMessage = `${checkedCount} adet ilanı silmek istediğinize emin misiniz? Bu işlem geri alınamaz.`;
                
                if (confirm(confirmMessage)) {
                    this.submit();
                }
            }
        });
    });
</script>
@endpush
