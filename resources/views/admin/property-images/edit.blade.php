@extends('admin.layouts.app')

@section('title', 'Emlak Görseli Düzenle')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    {{ $property->title_tr }} - Görsel Düzenle
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Emlak ilanına ait görselin bilgilerini güncelleyin
                </p>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('admin.properties.images.index', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Görsellere Dön
                </a>
            </div>
        </div>

        @if($errors->any())
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Aşağıdaki hataları düzeltin:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('admin.properties.images.update', [$property->id, $image->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <div class="aspect-w-3 aspect-h-2 mb-4">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full rounded-md object-cover shadow">
                            </div>
                            <div class="text-sm text-gray-500">
                                <p class="mb-1">Yüklenme tarihi: {{ $image->created_at->format('d.m.Y H:i') }}</p>
                                <p>Dosya yolu: {{ $image->image_path }}</p>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 space-y-6">
                            <div>
                                <label for="alt_text" class="block text-sm font-medium text-gray-700">Alternatif Metin</label>
                                <div class="mt-1">
                                    <input type="text" name="alt_text" id="alt_text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Görsel açıklaması (SEO için önemli)" value="{{ old('alt_text', $image->alt_text) }}">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Görsel için alternatif metin (SEO için önemli). Boş bırakılırsa emlak başlığı kullanılır.</p>
                            </div>
                            
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700">Sıralama</label>
                                <div class="mt-1">
                                    <input type="number" name="sort_order" id="sort_order" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" min="1" value="{{ old('sort_order', $image->sort_order) }}">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Görselin sıralama numarası. Küçük numara daha önce gösterilir.</p>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_featured" name="is_featured" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" value="1" {{ old('is_featured', $image->is_featured) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_featured" class="font-medium text-gray-700">Ana Görsel Olarak Ayarla</label>
                                    <p class="text-gray-500">Bu görseli emlak ilanının ana görseli olarak belirle. Daha önce ana görsel olarak ayarlanmış başka bir görsel varsa, o görselin ana görsel özelliği kaldırılacaktır.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
