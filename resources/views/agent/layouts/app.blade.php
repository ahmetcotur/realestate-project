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
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-700 text-white fixed inset-y-0 z-10 transition-all duration-300 transform lg:translate-x-0" x-data="{ open: false }" :class="open ? 'translate-x-0 ease-out' : '-translate-x-full ease-in lg:translate-x-0'">
            <div class="flex items-center justify-between px-4 py-3 bg-blue-800">
                <div class="flex items-center">
                    <span class="text-xl font-semibold">Danışman Paneli</span>
                </div>
                <button @click="open = false" class="lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="mt-5 px-2">
                <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') ? 'bg-blue-800' : 'hover:bg-blue-800' }} flex items-center px-4 py-2 text-white rounded-md transition-colors">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- Emlak İlanları Menüsü -->
                <a href="{{ route('agent.properties.index') }}" class="{{ request()->routeIs('agent.properties.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} flex items-center px-4 py-2 mt-2 text-white rounded-md transition-colors">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    <span>Emlak İlanlarım</span>
                </a>
                
                <!-- İletişim Mesajları Menüsü -->
                <a href="{{ route('agent.contacts.index') }}" class="{{ request()->routeIs('agent.contacts.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} flex items-center px-4 py-2 mt-2 text-white rounded-md transition-colors">
                    <i class="fas fa-envelope w-5 h-5 mr-3"></i>
                    <span>Mesajlarım</span>
                    @php
                        // Okunmamış mesaj sayısını al
                        $unreadCount = Auth::user()->agent->contacts()->where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
                
                <!-- Profil Ayarları Menüsü -->
                <a href="{{ route('agent.profile') }}" class="{{ request()->routeIs('agent.profile') ? 'bg-blue-800' : 'hover:bg-blue-800' }} flex items-center px-4 py-2 mt-2 text-white rounded-md transition-colors">
                    <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                    <span>Profil Ayarları</span>
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <button @click="open = true" class="lg:hidden mr-2">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center">
                    <a href="{{ route('home') }}" target="_blank" class="text-gray-500 hover:text-blue-600 mr-4" title="Siteyi Görüntüle">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 focus:outline-none">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="{{ route('agent.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Ayarları</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Çıkış Yap</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html> 