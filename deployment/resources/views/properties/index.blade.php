@extends('layouts.app')

@section('title', 'Emlak İlanları')
@section('meta_description', 'Kaş ve Kalkan bölgesindeki satılık ve kiralık emlak seçenekleri. Villalar, apartmanlar ve arsalar.')

@section('banner')
<div class="relative">
    <div class="w-full h-64 md:h-80 bg-cover bg-center" style="background-image: url('{{ asset('images/property-banner.jpg') }}')">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="w-full px-4 md:px-8 lg:px-12 h-full flex flex-col justify-center relative z-10">
            <div class="lg:ml-12 xl:ml-24">
                <h1 class="text-4xl font-bold text-white mb-4">Emlak İlanları</h1>
                <p class="text-xl text-white">Kaş ve Kalkan'daki en iyi gayrimenkul seçeneklerini keşfedin</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<style>
    .custom-gradient {
        background: linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.8) 50%, rgba(0,0,0,0.5) 75%, rgba(0,0,0,0.3) 100%);
        height: 6rem;
    }
    .title-background {
        background-color: rgba(0, 0, 0, 0.8);
        height: 6rem;
    }
</style>

<div class="container-fluid px-4 md:px-8 lg:px-12 py-8">
    <!-- Filtreleme -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4">Emlak Ara</h2>
        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <!-- Emlak Tipi -->
            <div>
                <label for="type" class="block text-gray-700 mb-2">Emlak Tipi</label>
                <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Tümü</option>
                    @foreach($propertyTypes as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name_tr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Satılık/Kiralık -->
            <div>
                <label for="status" class="block text-gray-700 mb-2">İlan Tipi</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Tümü</option>
                    <option value="for_sale" {{ request('status') == 'for_sale' ? 'selected' : '' }}>Satılık</option>
                    <option value="for_rent" {{ request('status') == 'for_rent' ? 'selected' : '' }}>Kiralık</option>
                </select>
            </div>

            <!-- Fiyat Aralığı -->
            <div>
                <label for="min_price" class="block text-gray-700 mb-2">Min. Fiyat</label>
                <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="Min. Fiyat" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="max_price" class="block text-gray-700 mb-2">Max. Fiyat</label>
                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="Max. Fiyat" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Lokasyon -->
            <div>
                <label for="location" class="block text-gray-700 mb-2">Lokasyon</label>
                <input type="text" name="location" id="location" value="{{ request('location') }}" placeholder="Ör: Kaş, Kalkan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Arama Butonu -->
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full">
                    <i class="fas fa-search mr-2"></i> Ara
                </button>
            </div>
        </form>
    </div>

    <!-- Sonuçlar -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">{{ $properties->total() }} Emlak İlanı Bulundu</h2>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Sırala:</span>
                <select class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option>En Yeniler</option>
                    <option>Fiyat (Artan)</option>
                    <option>Fiyat (Azalan)</option>
                </select>
            </div>
        </div>

        @if($properties->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($properties as $property)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 property-card">
                <div class="relative">
                    @php
                        $propertyImages = $property->images ?? collect([]);
                        $imageToShow = null;
                        
                        if (count($propertyImages) > 0) {
                            // Önce featured olanı ara
                            foreach ($propertyImages as $img) {
                                if ($img->is_featured == 1) {
                                    $imageToShow = $img;
                                    break;
                                }
                            }
                            
                            // Featured yoksa ilk görseli al
                            if (!$imageToShow && isset($propertyImages[0])) {
                                $imageToShow = $propertyImages[0];
                            }
                        }
                    @endphp
                
                    <a href="{{ route('properties.show', $property->slug) }}">
                        @if($imageToShow)
                            <div class="w-full h-64 bg-gray-100 flex items-center justify-center image-placeholder relative">
                                <span class="text-gray-400 animate-pulse"><i class="fas fa-spinner fa-spin"></i></span>
                                <img src="{{ asset('storage/' . $imageToShow->image_path) }}" alt="{{ $property->title_tr }}" class="w-full h-64 object-cover absolute inset-0" loading="lazy" onload="this.previousElementSibling.style.display = 'none'">
                            </div>
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500"><i class="fas fa-image fa-2x"></i></span>
                            </div>
                        @endif
                        <div class="absolute top-0 right-0 bg-blue-600 text-white py-1 px-3 m-2 text-sm font-medium rounded">
                            {{ $property->propertyType->name_tr ?? 'Emlak' }}
                        </div>
                        @if($property->status === 'sale')
                        <div class="absolute top-0 left-0 bg-blue-600 text-white py-1 px-3 m-2 text-sm font-medium rounded-full">
                            Satılık
                        </div>
                        @elseif($property->status === 'rent')
                        <div class="absolute top-0 left-0 bg-green-600 text-white py-1 px-3 m-2 text-sm font-medium rounded-full">
                            Kiralık
                        </div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 title-background p-4">
                            <h3 class="text-xl font-semibold text-white truncate drop-shadow-md">{{ $property->title_tr }}</h3>
                            <p class="text-white text-lg drop-shadow-md">{{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}</p>
                        </div>
                    </a>
                </div>
                <div class="p-4">
                    <div class="flex flex-col space-y-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2 hover:text-blue-600">{{ $property->title_tr }}</h3>
                        <p class="text-gray-600 truncate">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i> {{ $property->location }}
                        </p>
                        <div class="flex justify-between">
                            @if($property->bedrooms)
                            <span class="text-gray-600">
                                <i class="fas fa-bed text-blue-600 mr-1"></i> {{ $property->bedrooms }} Yatak Odası
                            </span>
                            @endif
                            @if($property->bathrooms)
                            <span class="text-gray-600">
                                <i class="fas fa-bath text-blue-600 mr-1"></i> {{ $property->bathrooms }} Banyo
                            </span>
                            @endif
                            @if($property->area)
                            <span class="text-gray-600">
                                <i class="fas fa-ruler-combined text-blue-600 mr-1"></i> {{ $property->area }} m²
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center">
                            @if($property->agent && $property->agent->photo)
                            <img src="{{ asset('storage/agents/' . $property->agent->photo) }}" alt="{{ $property->agent->name }}" class="w-8 h-8 rounded-full mr-2">
                            @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            @endif
                            <span class="text-sm text-gray-600">{{ $property->agent->name ?? 'Danışman' }}</span>
                        </div>
                        <a href="{{ route('properties.show', $property->slug) }}" class="text-blue-600 hover:text-blue-800">
                            Detaylar <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Sayfalama -->
        <div class="mt-10">
            {{ $properties->withQueryString()->links() }}
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Üzgünüz, aramanıza uygun emlak bulunamadı.</h3>
            <p class="text-gray-600 mb-4">Lütfen farklı filtreler ile tekrar deneyin ya da tüm emlakları görüntüleyin.</p>
            <a href="{{ route('properties.index') }}" class="inline-block bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition duration-300">
                Tüm Emlaklar
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
