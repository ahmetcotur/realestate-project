@extends('admin.layouts.app')

@section('title', 'Danışman Detayı')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    Danışman Detayı
                </h2>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('admin.agents.edit', $agent->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
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
            <div class="px-4 py-5 sm:p-6">
                <!-- Danışman Profil Bantı -->
                <div class="rounded-md bg-gray-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @if($agent->photo && !str_starts_with($agent->photo, 'default'))
                                @if(str_starts_with($agent->photo, 'agents/'))
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->name }}">
                                @else
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('storage/agents/' . $agent->photo) }}" alt="{{ $agent->name }}">
                                @endif
                            @else
                            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="ml-6 flex-1">
                            <div class="flex items-center">
                                <h3 class="text-xl font-medium text-gray-900">{{ $agent->name }}</h3>
                                <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($agent->is_active) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                    {{ $agent->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </div>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $agent->title }}</p>
                            <div class="mt-2 max-w-2xl text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $agent->phone }}
                                </div>
                                <div class="flex items-center mt-1">
                                    <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $agent->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danışman Detay İçeriği -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sol Kolon: Temel Bilgiler -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">Danışman Bilgileri</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Ad Soyad</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->name }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Unvan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->title ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">E-posta</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->email }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Telefon</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->phone }}</dd>
                                </div>
                                
                                @if($agent->bio_tr)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Biyografi (Türkçe)</dt>
                                    <dd class="mt-1 text-sm text-gray-900 prose max-w-none">{!! nl2br(e($agent->bio_tr)) !!}</dd>
                                </div>
                                @endif
                                
                                @if($agent->bio_en)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Biyografi (İngilizce)</dt>
                                    <dd class="mt-1 text-sm text-gray-900 prose max-w-none">{!! nl2br(e($agent->bio_en)) !!}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">İletişim ve Sosyal Medya</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">İkincil Telefon</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->phone_secondary ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Ofis Telefonu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->office_phone ?? 'Belirtilmemiş' }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">LinkedIn</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($agent->linkedin)
                                        <a href="{{ $agent->linkedin }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $agent->linkedin }}</a>
                                        @else
                                        Belirtilmemiş
                                        @endif
                                    </dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Facebook</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($agent->facebook)
                                        <a href="{{ $agent->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $agent->facebook }}</a>
                                        @else
                                        Belirtilmemiş
                                        @endif
                                    </dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Instagram</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($agent->instagram)
                                        <a href="{{ $agent->instagram }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $agent->instagram }}</a>
                                        @else
                                        Belirtilmemiş
                                        @endif
                                    </dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Twitter</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($agent->twitter)
                                        <a href="{{ $agent->twitter }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $agent->twitter }}</a>
                                        @else
                                        Belirtilmemiş
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Sağ Kolon: İstatistikler ve Sistem Bilgileri -->
                    <div class="col-span-1">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">İlan İstatistikleri</h3>
                            
                            <dl class="grid grid-cols-1 gap-y-4">
                                <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Aktif İlanlar</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-blue-600">{{ $agent->properties()->where('status', 'active')->count() }}</dd>
                                </div>
                                
                                <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Satılan/Kiralanan İlanlar</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-green-600">{{ $agent->properties()->where('status', 'sold')->count() }}</dd>
                                </div>
                                
                                <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Toplam İlanlar</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $agent->properties()->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-2">Sistem Bilgileri</h3>
                            
                            <dl class="grid grid-cols-1 gap-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->slug }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Danışman ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->id }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Oluşturulma Tarihi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->created_at->format('d.m.Y H:i') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Son Güncelleme</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->updated_at->format('d.m.Y H:i') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Profil URL</dt>
                                    <dd class="mt-1 text-sm text-gray-900 truncate">
                                        <a href="{{ route('agents.show', $agent->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ route('agents.show', $agent->slug) }}
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
                <form action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu danışmanı silmek istediğinize emin misiniz? Bu işlemle ilişkili tüm ilanlar sistemde kalacak ancak danışman atanmamış olacaktır.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Danışmanı Sil
                    </button>
                </form>
                
                <div class="flex space-x-2">
                    <a href="{{ route('agents.show', $agent->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Sitede Görüntüle
                    </a>
                    
                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
