@extends('admin.layouts.app')

@section('title', 'Yeni Emlak İlanı Ekle')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    Yeni Emlak İlanı Ekle
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Listeye Dön
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
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
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Temel Bilgiler</h3>
                        </div>
                        
                        <!-- Türkçe Başlık -->
                        <div class="col-span-1">
                            <label for="title_tr" class="block text-sm font-medium text-gray-700">Başlık (Türkçe) *</label>
                            <div class="mt-1">
                                <input type="text" name="title_tr" id="title_tr" value="{{ old('title_tr') }}" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- İngilizce Başlık -->
                        <div class="col-span-1">
                            <label for="title_en" class="block text-sm font-medium text-gray-700">Başlık (İngilizce)</label>
                            <div class="mt-1">
                                <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- Türkçe Açıklama -->
                        <div class="col-span-1">
                            <label for="description_tr" class="block text-sm font-medium text-gray-700">Açıklama (Türkçe) *</label>
                            <div class="mt-1">
                                <textarea name="description_tr" id="description_tr" rows="4" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description_tr') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- İngilizce Açıklama -->
                        <div class="col-span-1">
                            <label for="description_en" class="block text-sm font-medium text-gray-700">Açıklama (İngilizce)</label>
                            <div class="mt-1">
                                <textarea name="description_en" id="description_en" rows="4" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description_en') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Fiyat ve Para Birimi -->
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Fiyat Bilgisi</h3>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="price" class="block text-sm font-medium text-gray-700">Fiyat *</label>
                            <div class="mt-1">
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                    min="0" step="1">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="currency" class="block text-sm font-medium text-gray-700">Para Birimi</label>
                            <div class="mt-1">
                                <select name="currency" id="currency" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="TRY" {{ old('currency') == 'TRY' ? 'selected' : '' }}>TL</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Konum Bilgileri -->
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Konum Bilgileri</h3>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="location" class="block text-sm font-medium text-gray-700">Konum (Bölge) *</label>
                            <div class="mt-1">
                                <input type="text" name="location" id="location" value="{{ old('location') }}" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Örn: Kaş, Kalkan, vb.</p>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="address" class="block text-sm font-medium text-gray-700">Adres</label>
                            <div class="mt-1">
                                <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="city" class="block text-sm font-medium text-gray-700">Şehir</label>
                            <div class="mt-1">
                                <input type="text" name="city" id="city" value="{{ old('city', 'Antalya') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="district" class="block text-sm font-medium text-gray-700">İlçe</label>
                            <div class="mt-1">
                                <input type="text" name="district" id="district" value="{{ old('district', 'Kaş') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- Özellikler -->
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Emlak Özellikleri</h3>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="property_type_id" class="block text-sm font-medium text-gray-700">Emlak Tipi *</label>
                            <div class="mt-1">
                                <select name="property_type_id" id="property_type_id" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Seçiniz</option>
                                    @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name_tr }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="agent_id" class="block text-sm font-medium text-gray-700">Danışman</label>
                            <div class="mt-1">
                                <select name="agent_id" id="agent_id" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Seçiniz</option>
                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} - {{ $agent->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Yatak Odası Sayısı</label>
                            <div class="mt-1">
                                <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                    min="0" step="1">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700">Banyo Sayısı</label>
                            <div class="mt-1">
                                <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                    min="0" step="1">
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="area" class="block text-sm font-medium text-gray-700">Alan (m²)</label>
                            <div class="mt-1">
                                <input type="number" name="area" id="area" value="{{ old('area') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                    min="0" step="1">
                            </div>
                        </div>
                        
                        <!-- Durum Bilgileri -->
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-4">Durum Bilgileri</h3>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="listing_type" class="block text-sm font-medium text-gray-700">İlan Tipi *</label>
                            <div class="mt-1">
                                <select name="listing_type" id="listing_type" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Seçiniz</option>
                                    <option value="sale" {{ old('listing_type') == 'sale' ? 'selected' : '' }}>Satılık</option>
                                    <option value="rent" {{ old('listing_type') == 'rent' ? 'selected' : '' }}>Kiralık</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
                            <div class="mt-1">
                                <select name="status" id="status" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Satıldı</option>
                                    <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Kiralandı</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <div class="mt-6">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="is_featured" id="is_featured" 
                                            {{ old('is_featured') ? 'checked' : '' }}
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_featured" class="font-medium text-gray-700">Öne Çıkan İlan</label>
                                        <p class="text-gray-500">Bu ilanı ana sayfada öne çıkar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        İlanı Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
