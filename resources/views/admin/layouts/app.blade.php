<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Remax Pupa Emlak</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Style -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    
    <!-- Görsel Sıkıştırma Modülü -->
    <script src="{{ asset('js/image-compressor.js') }}"></script>
    <script src="{{ asset('js/form-image-compressor.js') }}"></script>
    
    <!-- Tiny MCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    @php
        $unreadCount = \App\Models\Contact::where('is_read', false)->count();
    @endphp
    
    <div class="min-h-screen flex flex-col md:flex-row" x-data="{ mobileMenuOpen: false }">
        <!-- Mobil Menü Düğmesi -->
        <div class="md:hidden bg-blue-800 text-white p-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Remax Pupa Emlak</h2>
                <p class="text-blue-200 text-sm">Yönetim Paneli</p>
            </div>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
        
        <!-- Mobil Menü -->
        <div class="md:hidden" x-show="mobileMenuOpen" x-cloak>
            <div class="bg-blue-800 text-white w-full px-4 py-4">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-tachometer-alt mr-3"></i>
                                    Dashboard
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.properties.index') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.properties.*') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-building mr-3"></i>
                                    Emlak İlanları
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.contacts.index') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-envelope mr-3"></i>
                                    İletişim Mesajları
                                    @if($unreadCount > 0)
                                        <span class="ml-2 bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-users mr-3"></i>
                                    Kullanıcı ve Danışman Yönetimi
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.settings') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-cog mr-3"></i>
                                    Site Ayarları
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.smtp.settings') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.smtp.*') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-envelope mr-3"></i>
                                    SMTP Ayarları
                                </span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('admin.profile') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.profile') ? 'bg-blue-700' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-user-circle mr-3"></i>
                                    Profil
                                </span>
                            </a>
                        </li>
                        <li class="border-t border-blue-700 pt-2 mt-2">
                            <a href="/" class="block p-2 rounded hover:bg-blue-700">
                                <span class="flex items-center">
                                    <i class="fas fa-home mr-3"></i>
                                    Siteye Dön
                                </span>
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left p-2 rounded hover:bg-blue-700">
                                    <span class="flex items-center">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Çıkış Yap
                                    </span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white w-64 py-4 px-2 hidden md:block">
            <div class="mb-8 px-4">
                <h2 class="text-xl font-bold">Remax Pupa Emlak</h2>
                <p class="text-blue-200 text-sm">Yönetim Paneli</p>
            </div>
            
            <nav>
                <ul class="space-y-1 px-2">
                    <li class="mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.properties.index') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.properties.*') ? 'bg-blue-700' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-building mr-3"></i>
                                Emlak İlanları
                            </span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.contacts.index') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.contacts.index') ? 'bg-blue-700' : '' }}">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center">
                                    <i class="fas fa-envelope mr-3"></i>
                                    İletişim Mesajları
                                </span>
                                @if($unreadCount > 0)
                                    <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('admin.contacts.unread') }}" class="block p-2 pl-10 rounded hover:bg-blue-700 {{ request()->routeIs('admin.contacts.unread') ? 'bg-blue-700' : '' }} text-sm">
                            <span class="flex items-center">
                                <i class="fas fa-circle text-xs mr-2 text-red-500"></i>
                                Okunmamış Mesajlar
                                @if($unreadCount > 0)
                                    <span class="ml-2 bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    @if(Auth::user()->isAdmin())
                    <li class="mb-2">
                        <a href="{{ route('admin.users.index') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-users mr-3"></i>
                                Kullanıcı ve Danışman Yönetimi
                            </span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.settings') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.settings') ? 'bg-blue-700' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-cog mr-3"></i>
                                Site Ayarları
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.smtp.settings') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.smtp.*') ? 'bg-blue-700' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-3"></i>
                                SMTP Ayarları
                            </span>
                        </a>
                    </li>
                    @endif
                    
                    <!-- Profil Yönetimi -->
                    <li class="mb-2">
                        <a href="{{ route('admin.profile') }}" class="block p-2 rounded hover:bg-blue-700 {{ request()->routeIs('admin.profile') ? 'bg-blue-700' : '' }}">
                            <span class="flex items-center">
                                <i class="fas fa-user-circle mr-3"></i>
                                Profil
                            </span>
                        </a>
                    </li>
                    
                    <li class="mt-8">
                        <a href="/" class="block p-2 rounded hover:bg-blue-700">
                            <span class="flex items-center">
                                <i class="fas fa-home mr-3"></i>
                                Siteye Dön
                            </span>
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left p-2 rounded hover:bg-blue-700">
                                <span class="flex items-center">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Çıkış Yap
                                </span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-3">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                    <div class="flex items-center" x-data="{ profileMenu: false }">
                        <div class="relative">
                            <button @click="profileMenu = !profileMenu" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                                <div class="flex items-center">
                                    @if(Auth::user()->isAgent() && Auth::user()->agent && Auth::user()->agent->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->agent->photo) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover mr-2">
                                    @else
                                        <span class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center mr-2">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    @endif
                                    <span>{{ Auth::user()->name }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="profileMenu" 
                                @click.away="profileMenu = false" 
                                x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                <div class="py-1">
                                    <span class="block px-4 py-2 text-sm text-gray-700 border-b">
                                        Rol: <span class="font-semibold">{{ Auth::user()->isAdmin() ? 'Admin' : 'Emlak Danışmanı' }}</span>
                                    </span>
                                    <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-circle mr-2"></i> Profil
                                    </a>
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Çıkış Yap
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Alert Messages -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
