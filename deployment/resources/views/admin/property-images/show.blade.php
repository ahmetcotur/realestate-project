@extends('admin.layouts.app')

@section('title', 'Emlak Görseli Detayları')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    {{ $property->title_tr }} - Görsel Detayları
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Emlak ilanına ait görsel bilgileri
                </p>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('admin.properties.images.index', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Görsellere Dön
                </a>
                <a href="{{ route('admin.properties.images.edit', [$property->id, $image->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 py-6">
                    <div class="md:col-span-1">
                        <div class="aspect-w-3 aspect-h-2 mb-4">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full rounded-md object-cover shadow-lg">
                        </div>
                        
                        @if($image->is_featured)
                        <div class="flex items-center mt-2 text-green-700">
                            <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-sm font-medium">Ana Görsel</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="md:col-span-2">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Alternatif Metin</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $image->alt_text ?: 'Belirtilmemiş' }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Sıralama</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $image->sort_order }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Görsel Yolu</dt>
                                <dd class="mt-1 text-sm text-gray-900 truncate">{{ $image->image_path }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Dosya Boyutu</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @php
                                        $path = storage_path('app/public/' . $image->image_path);
                                        $size = file_exists($path) ? round(filesize($path) / 1024, 2) : 0;
                                    @endphp
                                    {{ $size }} KB
                                </dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Oluşturulma Tarihi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $image->created_at->format('d.m.Y H:i') }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Son Güncelleme</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $image->updated_at->format('d.m.Y H:i') }}</dd>
                            </div>
                            
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Meta Bilgiler</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if(!empty($image->meta_data))
                                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 px-4 py-3 bg-gray-50 rounded-md">
                                            @foreach($image->meta_data as $key => $value)
                                                <div class="sm:col-span-1">
                                                    <dt class="text-xs font-medium text-gray-500">{{ $key }}</dt>
                                                    <dd class="mt-0.5 text-xs text-gray-900">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    @else
                                        <span class="text-gray-500">Meta bilgisi yok</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-end space-x-2">
                    <button onclick="if(confirm('Bu görseli silmek istediğinizden emin misiniz?')) { document.getElementById('delete-form').submit(); }" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Görseli Sil
                    </button>
                </div>
                
                <form id="delete-form" action="{{ route('admin.properties.images.destroy', [$property->id, $image->id]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
