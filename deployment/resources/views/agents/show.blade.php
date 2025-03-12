@extends('layouts.app')

@section('title', $agent->name . ' - Emlak Danışmanı')
@section('meta_description', 'Profesyonel emlak danışmanımız ' . $agent->name . ' ile tanışın. ' . Str::limit(strip_tags($agent->bio), 120))

@section('content')
<div class="container-fluid px-4 md:px-8 lg:px-12 py-8">

    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-600 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <a href="{{ route('agents.index') }}" class="hover:text-blue-600">Danışmanlarımız</a>
        <span class="mx-2">/</span>
        <span class="text-gray-400">{{ $agent->name }}</span>
    </div>

    <!-- Danışman Bilgileri -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
        <!-- Sol Kolon: Profil & İletişim -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <!-- Profil Resmi -->
                <div class="aspect-w-1 aspect-h-1 relative">
                    @if($agent->photo && !str_starts_with($agent->photo, 'default'))
                        @if(str_starts_with($agent->photo, 'agents/'))
                        <img src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                        @else
                        <img src="{{ asset('storage/agents/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                        @endif
                    @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-6xl text-gray-400"></i>
                    </div>
                    @endif
                </div>
                
                <!-- Kişisel Bilgiler -->
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-1">{{ $agent->name }}</h1>
                    <p class="text-blue-600 text-lg mb-4">{{ $agent->title }}</p>
                    
                    <div class="space-y-3 border-t border-gray-100 pt-4">
                        @if($agent->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone-alt text-blue-600 mr-3 w-5 text-center"></i>
                            <a href="tel:{{ $agent->phone }}" class="text-gray-600 hover:text-blue-600">{{ $agent->phone }}</a>
                        </div>
                        @endif
                        
                        @if($agent->email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3 w-5 text-center"></i>
                            <a href="mailto:{{ $agent->email }}" class="text-gray-600 hover:text-blue-600">{{ $agent->email }}</a>
                        </div>
                        @endif
                        
                        @if(isset($agent->languages) && count($agent->languages) > 0)
                        <div class="flex items-start">
                            <i class="fas fa-language text-blue-600 mr-3 w-5 text-center mt-1"></i>
                            <div>
                                <span class="block text-gray-800 font-medium">Konuştuğu Diller</span>
                                <p class="text-gray-600">{{ implode(', ', $agent->languages) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- İletişim Formu -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">{{ $agent->name }} ile İletişime Geçin</h2>
                    
                    <form action="{{ route('contact.agent', $agent->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-gray-700 mb-1">Adınız</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 mb-1">E-posta</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-gray-700 mb-1">Telefon</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-gray-700 mb-1">Mesajınız</label>
                            <textarea id="message" name="message" rows="4" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-md transition duration-300">
                            Mesaj Gönder
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sağ Kolon: Biyografi & Emlaklar -->
        <div class="lg:col-span-3">
            <!-- Biyografi -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8 p-6">
                <h2 class="text-2xl font-semibold mb-4">Hakkında</h2>
                
                <div class="prose max-w-none text-gray-700">
                    @if($agent->bio)
                        {!! nl2br(e($agent->bio)) !!}
                    @else
                        <p>{{ $agent->name }}, Kaş ve Kalkan bölgesinde uzun yıllara dayanan deneyimiyle, müşterilerine en iyi hizmeti sunmak için çalışmaktadır. Bölgedeki emlak piyasasına hakim olan danışmanımız, sizin ihtiyaçlarınıza en uygun mülkü bulmanızda yardımcı olacaktır.</p>
                    @endif
                </div>
            </div>
            
            <!-- Danışmanın İlanları -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-6">{{ $agent->name }}'in İlanları</h2>
                
                @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($properties as $property)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition duration-300">
                        <div class="relative h-52">
                            @php
                                $propertyImage = null;
                                
                                if (isset($property->images) && count($property->images) > 0) {
                                    // Önce featured olanı ara
                                    foreach ($property->images as $img) {
                                        if ($img->is_featured) {
                                            $propertyImage = $img;
                                            break;
                                        }
                                    }
                                    
                                    // Featured yoksa ilk görseli al
                                    if (!$propertyImage && isset($property->images[0])) {
                                        $propertyImage = $property->images[0];
                                    }
                                }
                            @endphp
                            
                            <div class="bg-gray-100 w-full h-full flex items-center justify-center image-placeholder">
                                <span class="text-gray-400 animate-pulse"><i class="fas fa-spinner fa-spin"></i></span>
                            </div>
                            
                            @if($propertyImage)
                            <img 
                                src="{{ asset('storage/' . $propertyImage->image_path) }}" 
                                alt="{{ $property->title_tr }}" 
                                class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                                onload="this.previousElementSibling.style.display = 'none'"
                            >
                            @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-home text-4xl text-gray-400"></i>
                            </div>
                            @endif
                            
                            <div class="absolute top-0 right-0 bg-blue-600 text-white text-sm px-3 py-1 m-2 rounded-md">
                                {{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}
                            </div>
                            
                            <!-- İlanın satılık/kiralık durumunu gösteren rozet -->
                            <div class="absolute top-0 left-0 {{ $property->status === 'sale' ? 'bg-green-600' : 'bg-purple-600' }} text-white text-sm px-3 py-1 m-2 rounded-md">
                                {{ $property->status === 'sale' ? 'Satılık' : 'Kiralık' }}
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 truncate">{{ $property->title_tr }}</h3>
                            
                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                <span class="truncate">{{ $property->location }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm mb-3">
                                @if($property->bedrooms)
                                <div class="flex items-center">
                                    <i class="fas fa-bed text-blue-600 mr-1"></i>
                                    <span>{{ $property->bedrooms }}</span>
                                </div>
                                @endif
                                
                                @if($property->bathrooms)
                                <div class="flex items-center">
                                    <i class="fas fa-bath text-blue-600 mr-1"></i>
                                    <span>{{ $property->bathrooms }}</span>
                                </div>
                                @endif
                                
                                @if($property->area)
                                <div class="flex items-center">
                                    <i class="fas fa-ruler-combined text-blue-600 mr-1"></i>
                                    <span>{{ $property->area }} m²</span>
                                </div>
                                @endif
                            </div>
                            
                            <a href="{{ route('properties.show', $property->slug) }}" class="block text-center py-2 px-4 bg-white border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition duration-300">
                                Detayları Gör
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $properties->links() }}
                </div>
                @else
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-home text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Henüz İlan Bulunamadı</h3>
                    <p class="text-gray-600">{{ $agent->name }}'in şu anda aktif ilanı bulunmamaktadır.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Diğer Danışmanlar -->
    @if(isset($otherAgents) && $otherAgents->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-6">Diğer Danışmanlarımız</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($otherAgents as $otherAgent)
            <div class="bg-white rounded-lg overflow-hidden shadow-md transition duration-300 hover:shadow-lg">
                <div class="aspect-w-3 aspect-h-2 relative">
                    @if($otherAgent->photo && !str_starts_with($otherAgent->photo, 'default'))
                        @if(str_starts_with($otherAgent->photo, 'agents/'))
                            <img src="{{ asset('storage/' . $otherAgent->photo) }}" alt="{{ $otherAgent->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/agents/' . $otherAgent->photo) }}" alt="{{ $otherAgent->name }}" class="w-full h-full object-cover">
                        @endif
                    @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-gray-400"></i>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-1">{{ $otherAgent->name }}</h3>
                    <p class="text-blue-600 mb-3 text-sm">{{ $otherAgent->title }}</p>
                    
                    <a href="{{ route('agents.show', $otherAgent->slug) }}" class="block text-center py-2 px-4 bg-white border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition duration-300">
                        Profili Görüntüle
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection 