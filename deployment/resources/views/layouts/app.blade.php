<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', isset($settings['site_title']) ? $settings['site_title'] : 'Remax Pupa Emlak')</title>
    <meta name="description" content="@yield('meta_description', isset($settings['site_description']) ? $settings['site_description'] : 'Remax Pupa Emlak - Kaş, Kalkan ve çevresindeki en iyi emlak seçenekleri')">
    
    <!-- Favicon -->
    @if(isset($settings['favicon']) && $settings['favicon'])
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings['favicon']) }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    @endif
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts - Preconnect ve Preload kullanarak performans iyileştirmesi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Görsel Yükleme ve Geçiş Efektleri */
        .image-placeholder {
            z-index: 1;
            transition: opacity 0.3s ease;
        }
        
        img {
            transition: opacity 0.3s ease;
        }
        
        /* Lazy Loading Animasyonu */
        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 0.8; }
            100% { opacity: 0.4; }
        }
        
        .animate-pulse {
            animation: pulse 1.5s infinite;
        }
        
        .thumbnail-container {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            overflow: hidden;
        }
        
        .thumbnail-container.active {
            border-color: #2563eb;
        }
        
        .thumbnail-container:hover {
            opacity: 0.9;
        }
        
        /* Görsel Hata Mesajları */
        .error-container {
            position: relative;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background-color: #FEE2E2;
            border-left: 4px solid #EF4444;
            color: #B91C1C;
            border-radius: 0.25rem;
        }
        
        /* Full width stil tanımları */
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        
        /* İçerik kenar boşlukları için özel stil */
        .content-section {
            max-width: 100%;
            padding: 0;
        }
        
        /* Sayfa boşlukları */
        .section-padding {
            padding: 2rem 0;
        }
        
        /* Büyük ekranlarda maksimum genişlik */
        @media (min-width: 1600px) {
            .container-xl {
                max-width: 1540px;
                margin: 0 auto;
            }
        }
    </style>
    
    <!-- Görsel Sıkıştırma Modülü -->
    <script src="{{ asset('js/image-compressor.js') }}"></script>
    <script src="{{ asset('js/form-image-compressor.js') }}"></script>
    
    @yield('styles')
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <!-- Logo -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center">
                        @if(isset($settings['site_logo']) && $settings['site_logo'])
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ isset($settings['site_title']) ? $settings['site_title'] : 'Remax Pupa Emlak' }}" class="h-12">
                        @else
                            <span class="text-xl font-bold text-blue-600">{{ isset($settings['site_title']) ? $settings['site_title'] : 'Remax Pupa Emlak' }}</span>
                        @endif
                    </a>
                    
                    <!-- Mobil Menü Butonu -->
                    <div class="md:hidden">
                        <button type="button" id="mobile-menu-button" class="text-gray-600 hover:text-blue-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Ana Menü - Desktop -->
                <nav class="hidden md:flex items-center">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-500' }}">
                        Ana Sayfa
                    </a>
                    
                    <!-- Emlaklar Yerine Satılık ve Kiralık -->
                    <a href="{{ route('properties.index', ['status' => 'for_sale']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ (request()->routeIs('properties.*') && request()->input('status') == 'for_sale') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-500' }}">
                        Satılık
                    </a>
                    
                    <a href="{{ route('properties.index', ['status' => 'for_rent']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ (request()->routeIs('properties.*') && request()->input('status') == 'for_rent') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-500' }}">
                        Kiralık
                    </a>
                    
                    <a href="{{ route('agents.index') }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('agents.*') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-500' }}">
                        Danışmanlar
                    </a>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('about') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-500' }}">
                        Hakkımızda
                    </a>
                    <a href="{{ route('contact.index') }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contact.*') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-500' }}">
                        İletişim
                    </a>
                    
                    <!-- Dil Seçimi - Desktop -->
                    <div class="ml-4 flex items-center space-x-2">
                        <a href="#" class="text-sm text-gray-600 hover:text-blue-500">TR</a>
                        <span class="text-gray-300">|</span>
                        <a href="#" class="text-sm text-gray-600 hover:text-blue-500">EN</a>
                    </div>
                    
                    <!-- Admin Panel Linki (Eğer kullanıcı giriş yaptıysa) -->
                    @auth
                    <div class="ml-4">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-user-shield mr-2"></i> Admin Panel
                        </a>
                    </div>
                    @endauth
                </nav>
            </div>
            
            <!-- Mobil Menü - Kapalı Durumda Başlar -->
            <div id="mobile-menu" class="md:hidden mt-4 pb-2 hidden border-t border-gray-200 pt-4">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-500 hover:bg-blue-50' }}">
                        Ana Sayfa
                    </a>
                    
                    <!-- Emlaklar yerine Satılık ve Kiralık - Mobil -->
                    <a href="{{ route('properties.index', ['status' => 'for_sale']) }}" class="px-4 py-2 rounded-md text-base font-medium {{ (request()->routeIs('properties.*') && request()->input('status') == 'for_sale') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-500 hover:bg-blue-50' }}">
                        Satılık
                    </a>
                    
                    <a href="{{ route('properties.index', ['status' => 'for_rent']) }}" class="px-4 py-2 rounded-md text-base font-medium {{ (request()->routeIs('properties.*') && request()->input('status') == 'for_rent') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-500 hover:bg-blue-50' }}">
                        Kiralık
                    </a>
                    
                    <a href="{{ route('agents.index') }}" class="px-4 py-2 rounded-md text-base font-medium {{ request()->routeIs('agents.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-500 hover:bg-blue-50' }}">
                        Danışmanlar
                    </a>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-md text-base font-medium {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-500 hover:bg-blue-50' }}">
                        Hakkımızda
                    </a>
                    <a href="{{ route('contact.index') }}" class="px-4 py-2 rounded-md text-base font-medium {{ request()->routeIs('contact.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-500 hover:bg-blue-50' }}">
                        İletişim
                    </a>
                    
                    <!-- Dil Seçimi - Mobil -->
                    <div class="px-4 py-2 flex items-center space-x-2">
                        <a href="#" class="text-sm text-gray-600 hover:text-blue-500">TR</a>
                        <span class="text-gray-300">|</span>
                        <a href="#" class="text-sm text-gray-600 hover:text-blue-500">EN</a>
                    </div>
                    
                    <!-- Admin Panel Linki (Eğer kullanıcı giriş yaptıysa) -->
                    @auth
                    <div class="px-4 py-2">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-user-shield mr-2"></i> Admin Panel
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Banner Alanı (İsteğe Bağlı) -->
    @yield('banner')

    <!-- Ana İçerik -->
    <main class="content-section">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Hakkımızda -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Hakkımızda</h3>
                    <p class="text-gray-300 mb-4">{{ $settings['footer_about_text'] ?? 'Remax Pupa Emlak olarak, Kaş ve Kalkan bölgesinde profesyonel emlak danışmanlığı hizmeti sunuyoruz.' }}</p>
                    <div class="flex space-x-4">
                        @if(isset($settings['facebook']) && $settings['facebook'])
                            <a href="{{ $settings['facebook'] }}" target="_blank" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(isset($settings['twitter']) && $settings['twitter'])
                            <a href="{{ $settings['twitter'] }}" target="_blank" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(isset($settings['instagram']) && $settings['instagram'])
                            <a href="{{ $settings['instagram'] }}" target="_blank" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(isset($settings['linkedin']) && $settings['linkedin'])
                            <a href="{{ $settings['linkedin'] }}" target="_blank" class="text-gray-300 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                    </div>
                </div>
                
                <!-- Hızlı Linkler -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Hızlı Linkler</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Ana Sayfa</a></li>
                        <li><a href="{{ route('properties.index') }}" class="text-gray-300 hover:text-white">Tüm Emlaklar</a></li>
                        <li><a href="{{ route('properties.index', ['status' => 'for_sale']) }}" class="text-gray-300 hover:text-white">Satılık</a></li>
                        <li><a href="{{ route('properties.index', ['status' => 'for_rent']) }}" class="text-gray-300 hover:text-white">Kiralık</a></li>
                        <li><a href="{{ route('agents.index') }}" class="text-gray-300 hover:text-white">Danışmanlar</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white">Hakkımızda</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-gray-300 hover:text-white">İletişim</a></li>
                    </ul>
                </div>
                
                <!-- İletişim Bilgileri -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">İletişim</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                            <span>{{ $settings['footer_address'] ?? $settings['address'] ?? 'Andifli Mah., Kaş, Antalya, Türkiye' }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2"></i>
                            <span>{{ $settings['footer_phone'] ?? $settings['contact_phone'] ?? '+90 242 123 4567' }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>{{ $settings['footer_email'] ?? $settings['contact_email'] ?? 'info@pupakas.com' }}</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Haber Bülteni -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Haber Bülteni</h3>
                    <p class="text-gray-300 mb-4">{{ $settings['footer_newsletter_text'] ?? 'Yeni emlak ilanları ve fırsatlardan haberdar olmak için abone olun.' }}</p>
                    <form action="#" method="post" class="flex">
                        <input type="email" placeholder="E-posta Adresiniz" class="px-3 py-2 w-full rounded-l focus:outline-none text-gray-800">
                        <button type="submit" class="bg-blue-600 px-4 py-2 rounded-r hover:bg-blue-700 focus:outline-none">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p>{!! $settings['footer_copyright_text'] ?? '&copy; ' . date('Y') . ' Remax Pupa Emlak. Tüm hakları saklıdır.' !!}</p>
                <div class="mt-4 md:mt-0">
                    <a href="{{ $settings['footer_privacy_url'] ?? '#' }}" class="text-gray-300 hover:text-white mr-4">Gizlilik Politikası</a>
                    <a href="{{ $settings['footer_terms_url'] ?? '#' }}" class="text-gray-300 hover:text-white">Kullanım Şartları</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobil menü toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
