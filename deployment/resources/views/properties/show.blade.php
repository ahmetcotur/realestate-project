@extends('layouts.app')

@section('title', $property->title_tr)
@section('meta_description', Str::limit(strip_tags($property->description_tr), 160))

@section('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<style>
.swiper-container {
    width: 100%;
    height: 500px;
}

.swiper-slide {
    text-align: center;
    background: #f8f8f8;
}

.swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    cursor: pointer;
}

.thumbnail-container {
    width: 100%;
    height: 80px;
    cursor: pointer;
    overflow: hidden;
    position: relative;
}

.thumbnail:hover,
.thumbnail-container.active {
    opacity: 1;
    border-color: #3182ce;
}

.property-feature-list li {
    margin-bottom: 0.5rem;
    display: flex;
}

.property-feature-list li i {
    color: #3182ce;
    margin-right: 0.5rem;
    min-width: 16px;
}

#lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

#lightbox.hidden {
    display: none;
}

#lightbox img {
    max-width: 90%;
    max-height: 90%;
    margin: auto;
    transition: all 0.3s ease;
}

#close-lightbox {
    position: absolute;
    top: 20px;
    right: 20px;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    z-index: 1001;
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    font-size: 3rem;
    cursor: pointer;
    background: rgba(0, 0, 0, 0.5);
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    z-index: 1001;
    transition: all 0.3s ease;
}

.lightbox-nav:hover {
    background: rgba(255, 255, 255, 0.2);
}

.lightbox-prev {
    left: 20px;
}

.lightbox-next {
    right: 20px;
}

@media (max-width: 768px) {
    .lightbox-nav {
        font-size: 2rem;
        width: 40px;
        height: 40px;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-4 md:px-8 lg:px-12 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-600 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <a href="{{ route('properties.index') }}" class="hover:text-blue-600">Emlaklar</a>
        <span class="mx-2">/</span>
        <span class="text-gray-400">{{ $property->title_tr }}</span>
    </div>

    <!-- Emlak Başlık -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $property->title_tr }}</h1>
                <p class="text-gray-600 flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                    {{ $property->location }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center justify-end mb-1">
                    @if($property->status === 'sale')
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Satılık</span>
                    @elseif($property->status === 'rent')
                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Kiralık</span>
                    @endif
                </div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($property->price, 0, ',', '.') }} {{ $property->currency }}</div>
                <div class="text-gray-600">{{ $property->propertyType->name_tr ?? 'Emlak' }}</div>
            </div>
        </div>
    </div>

    <!-- Emlak Görselleri -->
    <div class="mb-10">
        @php
            $propertyImages = $property->images ?? collect([]);
        @endphp

        @if(count($propertyImages) > 0)
        <div class="swiper-container mb-4">
            <div class="swiper-wrapper">
                @foreach($propertyImages as $key => $image)
                <div class="swiper-slide">
                    <div class="bg-gray-100 w-full h-full flex items-center justify-center image-placeholder">
                        <span class="text-gray-400 animate-pulse"><i class="fas fa-spinner fa-spin"></i></span>
                    </div>
                    <!-- Ana görseller için ana görseli hemen, diğerlerini lazy olarak yükle -->
                    <img 
                        src="{{ asset('storage/' . $image->image_path) }}" 
                        alt="{{ $property->title_tr }} - Görsel {{ $loop->iteration }}"
                        class="w-full h-full object-contain"
                        {{ $key === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }}
                        onload="this.previousElementSibling.style.display = 'none'"
                    >
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
            @foreach($propertyImages as $key => $image)
            <div class="relative thumbnail-container {{ $key === 0 ? 'active' : '' }}" data-index="{{ $key }}">
                <div class="bg-gray-100 w-full h-16 flex items-center justify-center image-placeholder">
                    <span class="text-gray-400 animate-pulse"><i class="fas fa-spinner fa-2xs fa-spin"></i></span>
                </div>
                <img 
                    src="{{ asset('storage/' . $image->image_path) }}" 
                    alt="{{ $property->title_tr }} - Küçük Görsel {{ $loop->iteration }}"
                    class="thumbnail w-full h-16 object-cover absolute inset-0"
                    loading="lazy"
                    onload="this.previousElementSibling.style.display = 'none'"
                >
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-100 h-80 flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-image text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Bu emlak için herhangi bir görsel bulunmamaktadır.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Ana İçerik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sol Kolon - Emlak Detayları -->
        <div class="lg:col-span-2 order-2 lg:order-1">
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h2 class="text-2xl font-semibold mb-6">Emlak Bilgileri</h2>
                
                <!-- Açıklama -->
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-4">Açıklama</h3>
                    <div class="text-gray-600 prose max-w-none">
                        {!! nl2br(e($property->description_tr)) !!}
                    </div>
                </div>
                
                <!-- Özellikler -->
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-4">Özellikler</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-bed text-blue-600 mr-2"></i>
                            <span>{{ $property->bedrooms }} Yatak Odası</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bath text-blue-600 mr-2"></i>
                            <span>{{ $property->bathrooms }} Banyo</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-ruler-combined text-blue-600 mr-2"></i>
                            <span>{{ $property->area }} m²</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                            <span>{{ $property->created_at->format('d.m.Y') }} tarihinde eklendi</span>
                        </div>
                    </div>
                </div>
                
                <!-- Detaylı Özellikler -->
                @php
                    $propertyFeatures = [];
                    if ($property->features) {
                        $propertyFeatures = is_array($property->features) ? $property->features : json_decode($property->features, true) ?? [];
                    }
                @endphp
                
                @if(count($propertyFeatures) > 0)
                <div>
                    <h3 class="text-xl font-semibold mb-4">Detaylı Özellikler</h3>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 property-feature-list">
                        @foreach($propertyFeatures as $feature)
                        <li>
                            <i class="fas fa-check"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            
            <!-- Konum -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h3 class="text-xl font-semibold mb-4">Konum</h3>
                
                @if($property->latitude && $property->longitude)
                <div class="h-80 bg-gray-200 rounded-lg mb-4">
                    <!-- Burada harita entegrasyonu olacak -->
                    <div id="property-map" class="w-full h-full rounded-lg"></div>
                </div>
                @else
                <div class="text-gray-600 mb-4">
                    <p>{{ $property->location }} bölgesinde yer almaktadır.</p>
                </div>
                @endif
            </div>
            
            <!-- Benzer İlanlar -->
            @if($relatedProperties->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-6">Benzer İlanlar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedProperties as $similar)
                    <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm transition duration-300 hover:shadow-md">
                        <div class="h-40 overflow-hidden relative">
                            @php
                                $similarImage = null;
                                
                                if (isset($similar->images) && count($similar->images) > 0) {
                                    // Önce featured olanı ara
                                    foreach ($similar->images as $img) {
                                        if ($img->is_featured) {
                                            $similarImage = $img;
                                            break;
                                        }
                                    }
                                    
                                    // Featured yoksa ilk görseli al
                                    if (!$similarImage && isset($similar->images[0])) {
                                        $similarImage = $similar->images[0];
                                    }
                                }
                            @endphp
                            
                            @if($similarImage)
                                <img src="{{ asset('storage/' . $similarImage->image_path) }}" alt="{{ $similar->title_tr }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400"><i class="fas fa-image fa-2x"></i></span>
                                </div>
                            @endif
                            <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs px-2 py-1 m-2 rounded">
                                {{ number_format($similar->price, 0, ',', '.') }} {{ $similar->currency }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-1 truncate">{{ $similar->title_tr }}</h4>
                            <p class="text-xs text-gray-600 mb-2 truncate">{{ $similar->location }}</p>
                            <a href="{{ route('properties.show', $similar->slug) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                Detaylar <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sağ Kolon - İletişim, Fiyat vb. -->
        <div class="lg:col-span-1 order-1 lg:order-2">
            <!-- Danışman Bilgisi -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Emlak Danışmanı</h3>
                
                <div class="flex items-center mb-4">
                    @if($property->agent && $property->agent->photo)
                    <img src="{{ asset('storage/agents/' . $property->agent->photo) }}" alt="{{ $property->agent->name }}" class="w-16 h-16 rounded-full object-cover mr-4">
                    @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                    @endif
                    
                    <div>
                        <h4 class="font-semibold">{{ $property->agent->name ?? 'Danışman' }}</h4>
                        <p class="text-blue-600 text-sm">{{ $property->agent->title ?? 'Emlak Danışmanı' }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @if($property->agent && $property->agent->phone)
                    <a href="tel:{{ $property->agent->phone }}" class="flex items-center text-gray-600 hover:text-blue-600">
                        <i class="fas fa-phone-alt mr-2 text-blue-600"></i>
                        <span>{{ $property->agent->phone }}</span>
                    </a>
                    @endif
                    
                    @if($property->agent && $property->agent->email)
                    <a href="mailto:{{ $property->agent->email }}" class="flex items-center text-gray-600 hover:text-blue-600">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                        <span>{{ $property->agent->email }}</span>
                    </a>
                    @endif
                </div>
                
                @if($property->agent)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('agents.show', $property->agent->slug) }}" class="text-blue-600 hover:text-blue-800">
                        Danışman Profilini Gör <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
            </div>
            
            <!-- İletişim Formu -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Bilgi İsteyin</h3>
                
                <form action="{{ route('contact.property-inquiry') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-1">Adınız</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-1">E-posta</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 mb-1">Telefon</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="block text-gray-700 mb-1">Mesajınız</label>
                        <textarea id="message" name="message" rows="4" required class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-md transition duration-300">
                        Bilgi İsteyin
                    </button>
                </form>
            </div>
            
            <!-- Kredi Hesaplayıcı -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold">İpotekli Alım Hesaplayıcı</h3>
                    <button id="mortgage-calculator-button" class="text-blue-600 hover:text-blue-800 focus:outline-none">
                        <i class="fas fa-calculator"></i>
                        <span class="sr-only">Hesaplayıcıyı Göster/Gizle</span>
                    </button>
                </div>
                
                <div id="mortgage-calculator" class="mt-4">
                    <form id="mortgage-form" class="space-y-4">
                        <div>
                            <label for="loan-amount" class="block text-gray-700 mb-1">Emlak Fiyatı (TL)</label>
                            <input type="number" id="loan-amount" value="{{ $property->price }}" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="down-payment" class="block text-gray-700 mb-1">Peşinat (TL)</label>
                            <input type="number" id="down-payment" value="{{ round($property->price * 0.2) }}" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="interest-rate" class="block text-gray-700 mb-1">Faiz Oranı (%)</label>
                            <input type="number" id="interest-rate" value="1.79" step="0.01" min="0.1" max="100" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="loan-term" class="block text-gray-700 mb-1">Kredi Vadesi (Yıl)</label>
                            <input type="number" id="loan-term" value="10" min="1" max="30" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300">
                            Hesapla
                        </button>
                    </form>
                    
                    <div id="mortgage-results" class="mt-6 pt-6 border-t border-gray-200 hidden">
                        <h4 class="font-semibold mb-4">Hesaplama Sonuçları</h4>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Aylık Ödeme:</span>
                                <span id="monthly-payment" class="font-semibold">-</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Toplam Ödeme:</span>
                                <span id="total-payment" class="font-semibold">-</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Toplam Faiz:</span>
                                <span id="total-interest" class="font-semibold">-</span>
                            </div>
                        </div>
                        
                        <p class="mt-4 text-xs text-gray-500">* Bu hesaplamalar yaklaşık değerlerdir. Kesin bilgi için lütfen bankalarla iletişime geçiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" class="hidden">
    <span id="close-lightbox">&times;</span>
    <div id="lightbox-prev" class="lightbox-nav lightbox-prev">&lt;</div>
    <div id="lightbox-next" class="lightbox-nav lightbox-next">&gt;</div>
    <img id="lightbox-image" src="" alt="Büyük Görsel" data-index="0">
</div>
@endsection

@section('scripts')
<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Emlak bilgileri açılıp kapanabilir
    const propertyDetailButtons = document.querySelectorAll('.property-detail-button');
    const propertyDetailContents = document.querySelectorAll('.property-detail-content');
    
    propertyDetailButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            propertyDetailContents[index].classList.toggle('hidden');
            
            const icon = button.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });
    
    // İpotekli alım hesaplayıcı
    const mortgageCalculatorButton = document.getElementById('mortgage-calculator-button');
    const mortgageCalculator = document.getElementById('mortgage-calculator');
    
    if (mortgageCalculatorButton && mortgageCalculator) {
        mortgageCalculatorButton.addEventListener('click', () => {
            mortgageCalculator.classList.toggle('hidden');
            
            const icon = mortgageCalculatorButton.querySelector('i');
            if (icon.classList.contains('fa-calculator')) {
                icon.classList.remove('fa-calculator');
                icon.classList.add('fa-times');
                mortgageCalculatorButton.querySelector('span').textContent = 'Hesaplayıcıyı Kapat';
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-calculator');
                mortgageCalculatorButton.querySelector('span').textContent = 'İpotekli Alım Hesaplayıcı';
            }
        });
    }
    
    // Lightbox işlevselliği
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const closeLightbox = document.getElementById('close-lightbox');
    const lightboxPrev = document.getElementById('lightbox-prev');
    const lightboxNext = document.getElementById('lightbox-next');
    const swiperSlideImages = document.querySelectorAll('.swiper-slide img');
    
    // Görsel dizisini hazırla
    let imageUrls = [];
    let currentIndex = 0;
    
    // Mevcut tüm görselleri topla
    @php
        $imageUrls = [];
        if(isset($property->images) && count($property->images) > 0) {
            foreach($property->images as $image) {
                $imageUrls[] = asset('storage/' . $image->image_path);
            }
        }
    @endphp
    
    imageUrls = @json($imageUrls);
    
    // Lightbox'ın gizli olduğundan emin ol
    if(lightbox) {
        lightbox.classList.add('hidden');
    }
    
    // Görseller arasında gezinmeyi sağlayan fonksiyon
    const navigateImages = (direction) => {
        if (imageUrls.length <= 1) return;
        
        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % imageUrls.length;
        } else {
            currentIndex = (currentIndex - 1 + imageUrls.length) % imageUrls.length;
        }
        
        if (lightboxImage) {
            // Resim değiştiğinde hafif bir geçiş efekti
            lightboxImage.style.opacity = '0.3';
            
            setTimeout(() => {
                lightboxImage.src = imageUrls[currentIndex];
                lightboxImage.dataset.index = currentIndex;
                lightboxImage.style.opacity = '1';
                
                // Slider ile senkronize et
                swiper.slideTo(currentIndex);
            }, 200);
        }
    };
    
    // Klavye ile kontrol
    document.addEventListener('keydown', function(e) {
        if (lightbox && !lightbox.classList.contains('hidden')) {
            // Sağ ok tuşu
            if (e.key === 'ArrowRight') {
                e.preventDefault(); // Tarayıcının varsayılan davranışını engelle
                navigateImages('next');
            }
            // Sol ok tuşu
            else if (e.key === 'ArrowLeft') {
                e.preventDefault(); // Tarayıcının varsayılan davranışını engelle
                navigateImages('prev');
            }
            // Escape tuşu
            else if (e.key === 'Escape') {
                e.preventDefault(); // Tarayıcının varsayılan davranışını engelle
                lightbox.classList.add('hidden');
            }
        }
    });
    
    // Resimlere tıklama olayı
    swiperSlideImages.forEach((img, index) => {
        img.addEventListener('click', function() {
            if(lightbox && lightboxImage) {
                currentIndex = index;
                lightboxImage.src = this.src;
                lightboxImage.dataset.index = currentIndex;
                lightbox.classList.remove('hidden');
            }
        });
    });
    
    // Navigasyon butonları
    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', function() {
            navigateImages('prev');
        });
    }
    
    if (lightboxNext) {
        lightboxNext.addEventListener('click', function() {
            navigateImages('next');
        });
    }
    
    // Lightbox kapatma (X düğmesi)
    if(closeLightbox) {
        closeLightbox.addEventListener('click', function() {
            lightbox.classList.add('hidden');
        });
    }
    
    // Lightbox dışına tıklanarak kapatma
    if(lightbox) {
        lightbox.addEventListener('click', function(e) {
            if(e.target === lightbox) {
                lightbox.classList.add('hidden');
            }
        });
    }
    
    // Görselleri önbelleğe al
    const preloadPropertyImages = () => {
        if(typeof preloadImages === 'function' && imageUrls.length > 0) {
            preloadImages(imageUrls);
        }
    };
    
    // Swiper Slider
    const swiper = new Swiper('.swiper-container', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        lazy: true,
        preloadImages: false,
    });
    
    // Thumbnail tıklama işlemleri
    const thumbnailContainers = document.querySelectorAll('.thumbnail-container');
    
    thumbnailContainers.forEach(container => {
        container.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            swiper.slideTo(index);
            
            // Aktif thumbnail'i değiştir
            thumbnailContainers.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Swiper slayt değiştiğinde thumbnail'i güncelle
    swiper.on('slideChange', function() {
        const realIndex = swiper.realIndex;
        thumbnailContainers.forEach(t => t.classList.remove('active'));
        const activeThumb = document.querySelector(`.thumbnail-container[data-index="${realIndex}"]`);
        if (activeThumb) {
            activeThumb.classList.add('active');
        }
    });
    
    // İpotekli alım hesaplayıcı
    const mortgageForm = document.getElementById('mortgage-form');
    const loanAmount = document.getElementById('loan-amount');
    const downPayment = document.getElementById('down-payment');
    const interestRate = document.getElementById('interest-rate');
    const loanTerm = document.getElementById('loan-term');
    const monthlyPayment = document.getElementById('monthly-payment');
    const totalPayment = document.getElementById('total-payment');
    const totalInterest = document.getElementById('total-interest');
    
    if (mortgageForm) {
        mortgageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Değerleri al
            const principal = parseFloat(loanAmount.value) - parseFloat(downPayment.value);
            const monthlyRate = parseFloat(interestRate.value) / 100 / 12;
            const numberOfPayments = parseFloat(loanTerm.value) * 12;
            
            // Aylık ödeme hesapla
            const x = Math.pow(1 + monthlyRate, numberOfPayments);
            const monthly = (principal * x * monthlyRate) / (x - 1);
            
            if (isFinite(monthly)) {
                monthlyPayment.innerHTML = monthly.toLocaleString('tr-TR', { maximumFractionDigits: 2 }) + ' TL';
                totalPayment.innerHTML = (monthly * numberOfPayments).toLocaleString('tr-TR', { maximumFractionDigits: 2 }) + ' TL';
                totalInterest.innerHTML = ((monthly * numberOfPayments) - principal).toLocaleString('tr-TR', { maximumFractionDigits: 2 }) + ' TL';
                
                document.getElementById('mortgage-results').classList.remove('hidden');
            } else {
                alert('Lütfen geçerli değerler giriniz!');
            }
        });
    }
    
    // Sayfa yüklendiğinde resimleri önbelleğe al
    preloadPropertyImages();
});
</script>
@endsection
