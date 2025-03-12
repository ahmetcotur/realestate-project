@extends('admin.layouts.app')

@section('title', 'Emlak Görselleri')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    {{ $property->title_tr }} - Görseller
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Emlak ilanı için yüklenen görselleri düzenleyin, sıralayın veya silin
                </p>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('admin.properties.images.create', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Görsel Ekle
                </a>
                <a href="{{ route('admin.properties.show', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    İlan Detayına Dön
                </a>
            </div>
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

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            @if($images->count() > 0)
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Yüklenen Görseller ({{ $images->count() }})</h3>
                
                <div id="images-container" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($images as $image)
                    <div class="relative group border rounded-lg overflow-hidden" data-id="{{ $image->id }}">
                        <div class="relative aspect-w-3 aspect-h-2">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full h-48 object-cover">
                            <!-- Sadece görsel alanında hover efekti -->                          
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="drag-handle text-white p-2 cursor-move">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-2">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Sıra: {{ $image->sort_order }}</span>
                                @if($image->is_featured)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Ana Görsel</span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-gray-500 truncate" title="{{ $image->alt_text }}">{{ $image->alt_text }}</p>
                            
                            <div class="flex space-x-2 mt-2 z-10 relative">
                                <a href="{{ route('admin.properties.images.edit', [$property->id, $image->id]) }}" class="inline-flex items-center p-1 border border-transparent rounded text-xs text-blue-600 hover:text-blue-800">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                @if(!$image->is_featured)
                                <a href="{{ route('admin.properties.images.feature', [$property->id, $image->id]) }}" class="inline-flex items-center p-1 border border-transparent rounded text-xs text-yellow-600 hover:text-yellow-800">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </a>
                                @endif
                                
                                <form action="{{ route('admin.properties.images.destroy', [$property->id, $image->id]) }}" method="POST" class="delete-form inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center p-1 border border-transparent rounded text-xs text-red-600 hover:text-red-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="px-4 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz görsel yok</h3>
                <p class="mt-1 text-sm text-gray-500">Bu ilan için görseller eklemek için başlayın.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.properties.images.create', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Yeni Görsel Ekle
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($images->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sıralama için Sortable JS
        const container = document.getElementById('images-container');
        
        const sortable = new Sortable(container, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function (evt) {
                const images = Array.from(container.querySelectorAll('[data-id]')).map(el => el.dataset.id);
                
                // CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // AJAX ile sıralama güncelleme
                fetch('{{ route("admin.properties.images.reorder", $property->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ images })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sıra numaralarını güncelle
                        const imageElements = container.querySelectorAll('[data-id]');
                        imageElements.forEach((el, index) => {
                            const orderText = el.querySelector('span.text-sm.text-gray-500');
                            if (orderText) orderText.textContent = `Sıra: ${index + 1}`;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
        
        // Silme işlemini yönet
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Bu görseli silmek istediğinize emin misiniz?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endif

@endsection
