@extends('admin.layouts.app')

@section('title', 'Emlak İlanı Detayı')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    Emlak İlanı Detayı
                </h2>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('admin.properties.images.index', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Görselleri Yönet
                </a>
                <a href="{{ route('admin.properties.edit', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Listeye Dön
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <!-- İlan Durum Bantı -->
                <div class="rounded-md bg-gray-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <h3 class="text-lg font-medium text-gray-900">{{ $property->title_tr }}</h3>
                                <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($property->status === 'active') bg-green-100 text-green-800 
                                    @elseif($property->status === 'sold') bg-red-100 text-red-800 
                                    @elseif($property->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $property->status === 'active' ? 'Aktif' : ($property->status === 'sold' ? 'Satıldı' : ($property->status === 'pending' ? 'Beklemede' : 'Pasif')) }}
                                </span>
                                @if($property->is_featured)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Öne Çıkan</span>
                                @endif
                            </div>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Oluşturulma: {{ $property->created_at->format('d.m.Y H:i') }} | 
                                Son Güncelleme: {{ $property->updated_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <span class="text-xl font-bold text-blue-600">{{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}</span>
                        </div>
                    </div>
                </div>

                <!-- İlan Detay İçeriği -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sol Kolon: Temel Bilgiler -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">İlan Bilgileri</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Başlık (Türkçe)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->title_tr }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Başlık (İngilizce)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->title_en ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Açıklama (Türkçe)</dt>
                                    <dd class="mt-1 text-sm text-gray-900 prose max-w-none">{!! nl2br(e($property->description_tr)) !!}</dd>
                                </div>
                                
                                @if($property->description_en)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Açıklama (İngilizce)</dt>
                                    <dd class="mt-1 text-sm text-gray-900 prose max-w-none">{!! nl2br(e($property->description_en)) !!}</dd>
                                </div>
                                @endif
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Fiyat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Emlak Tipi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->propertyType->name_tr ?? 'Belirtilmemiş' }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">Konum Bilgileri</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Konum</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->location }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Adres</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->address ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Şehir</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->city ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">İlçe</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->district ?? 'Belirtilmemiş' }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">Özellikler</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Yatak Odası</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->bedrooms ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Banyo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->bathrooms ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Alan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->area ? $property->area . ' m²' : 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                @if($property->features && is_array($property->features))
                                <div class="sm:col-span-3">
                                    <dt class="text-sm font-medium text-gray-500">Diğer Özellikler</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <ul class="grid grid-cols-2 gap-2">
                                            @foreach($property->features as $feature)
                                            <li class="flex items-center">
                                                <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ $feature }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Sağ Kolon: Görseller ve Yan Bilgiler -->
                    <div class="col-span-1">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">Danışman Bilgisi</h3>
                            
                            @if($property->agent)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $property->agent->photo) }}" alt="{{ $property->agent->name }}">
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $property->agent->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $property->agent->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $property->agent->phone }}</p>
                                </div>
                            </div>
                            @else
                            <p class="text-sm text-gray-500">Danışman atanmamış</p>
                            @endif
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">İlan Görselleri</h3>
                            
                            @if($property->images && $property->images->count() > 0)
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($property->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $property->title_tr }}" class="h-24 w-full object-cover rounded">
                                    @if($image->is_featured)
                                    <span class="absolute top-0 right-0 bg-yellow-400 text-xs font-semibold px-1 py-0.5 rounded-bl">Ana</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Toplam {{ $property->images->count() }} görsel</p>
                            @else
                            <p class="text-sm text-gray-500">Henüz yüklenen görsel yok</p>
                            @endif
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">SEO & Sistem Bilgileri</h3>
                            
                            <dl class="grid grid-cols-1 gap-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->slug }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">İlan ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->id }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">İlan URL</dt>
                                    <dd class="mt-1 text-sm text-gray-900 truncate">
                                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ route('properties.show', $property->slug) }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- İşlem Butonları -->
            <div class="px-4 py-3 bg-gray-50 sm:px-6 flex justify-between">
                <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu emlak ilanını silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        İlanı Sil
                    </button>
                </form>
                
                <div class="flex space-x-2">
                    <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Sitede Görüntüle
                    </a>
                    
                    <a href="{{ route('admin.properties.edit', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Düzenle
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
