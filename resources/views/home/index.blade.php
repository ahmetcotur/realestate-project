@extends('layouts.app')

@section('title', 'Ana Sayfa')
@section('meta_description', 'Remax Pupa Emlak - Kaş ve Kalkan bölgesindeki en iyi emlak seçenekleri, villa ve daireler')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-blue-600 text-white">
        <div class="absolute inset-0 overflow-hidden">
            @if(isset($settings['home_hero_image']) && $settings['home_hero_image'])
                <img src="{{ asset('storage/' . $settings['home_hero_image']) }}" alt="Kaş ve Kalkan Manzarası" class="w-full h-full object-cover opacity-50" fetchpriority="high">
            @else
                <img src="{{ asset('images/hero-bg.jpg') }}" alt="Kaş ve Kalkan Manzarası" class="w-full h-full object-cover opacity-50" fetchpriority="high">
            @endif
        </div>
        <div class="w-full px-4 md:px-8 lg:px-12 py-24 relative z-10">
            <div class="max-w-3xl lg:ml-12 xl:ml-24">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $settings['home_hero_title'] ?? 'Kaş ve Kalkan\'da Hayalinizdeki Mülkü Keşfedin' }}</h1>
                <p class="text-xl mb-8">{{ $settings['home_hero_description'] ?? 'Türkiye\'nin en güzel kıyılarında sizin için özel seçilmiş villa ve daireler.' }}</p>
                
                <!-- Arama Formu -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="location" class="block text-gray-700 mb-1">Konum</label>
                            <select name="location" id="location" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">Tüm Konumlar</option>
                                <option value="Kaş">Kaş</option>
                                <option value="Kalkan">Kalkan</option>
                                <option value="Kaş Merkez">Kaş Merkez</option>
                                <option value="Çukurbağ">Çukurbağ</option>
                            </select>
                        </div>
                        <div>
                            <label for="type" class="block text-gray-700 mb-1">Emlak Tipi</label>
                            <select name="type" id="type" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">Tüm Tipler</option>
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_tr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1">&nbsp;</label>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300">
                                <i class="fas fa-search mr-2"></i> Emlak Ara
                            </button>
                        </div>
                    </form>
                    <!-- Tüm Emlakları Görüntüle Butonu -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-th-list mr-2"></i> Tüm Emlakları Görüntüle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Öne Çıkan Emlaklar -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">{{ $settings['home_featured_properties_title'] ?? 'Öne Çıkan Emlaklar' }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredProperties as $property)
                <div class="bg-white rounded-lg shadow-md overflow-hidden property-card transition duration-300">
                    <div class="relative h-64">
                        @if(isset($property->images) && $property->images->isNotEmpty())
                            @php
                                $imageToShow = null;
                                
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
                            @endphp
                            
                            @if($imageToShow)
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center image-placeholder">
                                    <span class="text-gray-400 animate-pulse"><i class="fas fa-spinner fa-spin"></i></span>
                                </div>
                                <img src="{{ asset('storage/' . $imageToShow->image_path) }}" alt="{{ $property->title_tr }}" class="w-full h-full object-cover absolute inset-0" loading="lazy" onload="this.parentElement.querySelector('.image-placeholder').style.display = 'none';">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400"><i class="fas fa-image fa-2x"></i></span>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400"><i class="fas fa-image fa-2x"></i></span>
                            </div>
                        @endif
                        <div class="absolute top-0 right-0 bg-blue-600 text-white px-3 py-1 m-2 rounded-md">
                            {{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}
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
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title_tr }}</h3>
                        <p class="text-gray-600 mb-4"><i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>{{ $property->location }}</p>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span><i class="fas fa-bed mr-1"></i> {{ $property->bedrooms }} Yatak</span>
                            <span><i class="fas fa-bath mr-1"></i> {{ $property->bathrooms }} Banyo</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> {{ $property->area }} m²</span>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('properties.show', $property->slug) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300 w-full text-center">
                                Detayları Gör
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Neden Biz Section -->
    <section class="py-16 bg-white">
        <div class="container-fluid px-4 md:px-8 lg:px-12">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">{{ $settings['about_why_us_title'] ?? 'Neden Remax Pupa Emlak?' }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 hover:shadow-lg rounded-lg transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-{{ $settings['about_why_us_item1_icon'] ?? 'gem' }} fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">{{ $settings['about_why_us_item1_title'] ?? 'Özel Seçilmiş Emlaklar' }}</h3>
                    <p class="text-gray-600">{{ $settings['about_why_us_item1_description'] ?? 'En kaliteli ve özel mülkleri sizin için titizlikle seçiyor ve sunuyoruz.' }}</p>
                </div>
                
                <div class="text-center p-6 hover:shadow-lg rounded-lg transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-{{ $settings['about_why_us_item2_icon'] ?? 'user-tie' }} fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">{{ $settings['about_why_us_item2_title'] ?? 'Uzman Danışmanlar' }}</h3>
                    <p class="text-gray-600">{{ $settings['about_why_us_item2_description'] ?? 'Alanında uzman danışmanlarımız emlak sürecinin her adımında yanınızda.' }}</p>
                </div>
                
                <div class="text-center p-6 hover:shadow-lg rounded-lg transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-{{ $settings['about_why_us_item3_icon'] ?? 'handshake' }} fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">{{ $settings['about_why_us_item3_title'] ?? 'Güvenilir Hizmet' }}</h3>
                    <p class="text-gray-600">{{ $settings['about_why_us_item3_description'] ?? '20 yılı aşkın tecrübemizle güvenilir ve şeffaf emlak hizmeti sunuyoruz.' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Emlak Danışmanları -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">{{ $settings['home_featured_agents_title'] ?? 'Emlak Danışmanlarımız' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredAgents as $agent)
                <div class="bg-white rounded-lg shadow-md overflow-hidden property-card transition duration-300">
                    <div class="relative h-64">
                        @if($agent->photo && !str_starts_with($agent->photo, 'default'))
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center image-placeholder">
                                <span class="text-gray-400 animate-pulse"><i class="fas fa-spinner fa-spin"></i></span>
                            </div>
                            @if(str_starts_with($agent->photo, 'agents/'))
                                <img src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover absolute inset-0" loading="lazy" onload="this.parentElement.querySelector('.image-placeholder').style.display = 'none';">
                            @else
                                <img src="{{ asset('storage/agents/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover absolute inset-0" loading="lazy" onload="this.parentElement.querySelector('.image-placeholder').style.display = 'none';">
                            @endif
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400"><i class="fas fa-user fa-2x"></i></span>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $agent->name }}</h3>
                        <p class="text-blue-600 mb-4">{{ $agent->title }}</p>
                        
                        <div class="space-y-2 mb-4">
                            @if($agent->phone)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone-alt text-blue-600 mr-2 w-4 text-center"></i>
                                <span>{{ $agent->phone }}</span>
                            </div>
                            @endif
                            
                            @if($agent->email)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope text-blue-600 mr-2 w-4 text-center"></i>
                                <span>{{ $agent->email }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('agents.show', $agent->slug) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300 w-full text-center">
                                Profili Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Kaş ve Kalkan Hakkında -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center lg:space-x-12">
                <div class="lg:w-1/2 mb-10 lg:mb-0">
                    @if(isset($settings['home_location_image']) && $settings['home_location_image'])
                        <img src="{{ asset('storage/' . $settings['home_location_image']) }}" alt="Kaş ve Kalkan" class="rounded-lg shadow-md w-full">
                    @else
                        <img src="{{ asset('images/kas-kalkan-info.jpg') }}" alt="Kaş ve Kalkan" class="rounded-lg shadow-md w-full">
                    @endif
                </div>
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-bold mb-6">{{ $settings['home_location_title'] ?? 'Kaş ve Kalkan Hakkında' }}</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        {{ $settings['home_location_description'] ?? 'Kaş ve Kalkan, Türkiye\'nin en güzel kıyı bölgelerinden biridir. Berrak denizi, muhteşem manzarası ve zengin kültürü ile her yıl binlerce turisti kendine çekmektedir.' }}
                    </p>
                    <a href="{{ route('about') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                        Daha Fazla Bilgi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- İletişim Section -->
    <section class="py-16 bg-gray-50">
        <div class="container-fluid px-4 md:px-8 lg:px-12">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Bizimle İletişime Geçin</h2>
                    <p class="text-gray-600 mb-6">Sorularınız mı var? Size yardımcı olmaktan memnuniyet duyarız. İletişim formunu doldurun, en kısa sürede size dönüş yapalım.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="text-blue-600 mr-3">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Adres</h4>
                                <p class="text-gray-600">Andifli Mah., Kaş, Antalya, Türkiye</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="text-blue-600 mr-3">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Telefon</h4>
                                <p class="text-gray-600">+90 242 123 4567</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="text-blue-600 mr-3">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">E-posta</h4>
                                <p class="text-gray-600">info@pupakas.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/2">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-gray-700 mb-1">Adınız</label>
                                    <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="email" class="block text-gray-700 mb-1">E-posta</label>
                                    <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="subject" class="block text-gray-700 mb-1">Konu</label>
                                <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="block text-gray-700 mb-1">Mesajınız</label>
                                <textarea id="message" name="message" rows="4" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300">
                                Mesaj Gönder
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 