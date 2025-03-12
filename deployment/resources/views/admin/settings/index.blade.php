@extends('admin.layouts.app')

@section('title', 'Site Ayarları')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sekme değiştirme fonksiyonu
        function changeTab(tabId) {
            // Tüm sekme içeriklerini gizle
            document.querySelectorAll('.settings-content').forEach(function(tab) {
                tab.classList.add('hidden');
            });
            
            // Tüm sekme başlıklarını pasif yap
            document.querySelectorAll('.settings-tab').forEach(function(tab) {
                tab.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                tab.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            });
            
            // Seçilen sekmeyi aktif yap
            document.getElementById('tab-' + tabId).classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
            document.getElementById('tab-' + tabId).classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            
            // Seçilen içeriği göster
            document.getElementById('content-' + tabId).classList.remove('hidden');
        }
        
        // Sekme değiştirme butonlarına tıklama olayları ekle
        document.querySelectorAll('.settings-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                changeTab(this.getAttribute('data-tab'));
            });
        });
        
        // Sayfa yüklendiğinde ilk sekmeyi göster
        changeTab('general');
    });
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Site Ayarları</h1>
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span>Site Ayarları</span>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">Site Ayarlarını Düzenle</span>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Lütfen aşağıdaki hataları düzeltin:</p>
                        <ul class="mt-1 ml-4 text-sm list-disc">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Sekmeler -->
        <div class="border-b border-gray-200">
            <div class="flex overflow-x-auto">
                <button id="tab-general" data-tab="general" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-blue-600 text-white border-blue-600">
                    Genel Ayarlar
                </button>
                <button id="tab-contact" data-tab="contact" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    İletişim Bilgileri
                </button>
                <button id="tab-social" data-tab="social" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    Sosyal Medya
                </button>
                <button id="tab-home" data-tab="home" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    Ana Sayfa
                </button>
                <button id="tab-about" data-tab="about" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    Hakkımızda
                </button>
                <button id="tab-properties" data-tab="properties" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    Emlaklar
                </button>
                <button id="tab-agents" data-tab="agents" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    Danışmanlar
                </button>
                <button id="tab-contact-page" data-tab="contact-page" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    İletişim Sayfası
                </button>
                <button id="tab-footer" data-tab="footer" class="settings-tab px-6 py-3 border-b-2 text-sm font-medium bg-white text-gray-700 border-gray-300">
                    Footer
                </button>
            </div>
        </div>
        
        <!-- Sekme İçerikleri -->
        <!-- Genel Ayarlar Sekmesi -->
        <div id="content-general" class="settings-content p-6">
            <form action="{{ route('admin.settings.update.general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Genel Ayarlar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Başlık -->
                        <div>
                            <label for="site_title" class="block text-sm font-medium text-gray-700 mb-1">Site Başlığı</label>
                            <input type="text" name="site_title" id="site_title" value="{{ old('site_title', $settings['site_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Site Açıklama -->
                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Açıklaması</label>
                            <textarea name="site_description" id="site_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Logo ve Favicon -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-1">Site Logo</label>
                                
                                @if(isset($settings['site_logo']) && $settings['site_logo'])
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                                            alt="Site Logo" class="h-16 object-contain">
                                        <input type="hidden" name="old_logo" value="{{ $settings['site_logo'] }}">
                                    </div>
                                @endif
                                
                                <input type="file" name="site_logo" id="site_logo" accept="image/*"
                                    class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG veya SVG. Maksimum 2MB.</p>
                            </div>
                            
                            <div>
                                <label for="favicon" class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                                
                                @if(isset($settings['favicon']) && $settings['favicon'])
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $settings['favicon']) }}" 
                                            alt="Favicon" class="h-10 object-contain">
                                        <input type="hidden" name="old_favicon" value="{{ $settings['favicon'] }}">
                                    </div>
                                @endif
                                
                                <input type="file" name="favicon" id="favicon" accept="image/x-icon,image/png,image/jpeg,image/svg+xml"
                                    class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                                <p class="mt-1 text-xs text-gray-500">ICO, PNG, JPG veya SVG. Maksimum 1MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Genel Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- İletişim Bilgileri Sekmesi -->
        <div id="content-contact" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.contact') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">İletişim Bilgileri</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- İletişim Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">İletişim E-posta</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- İletişim Telefon -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">İletişim Telefon</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Adres -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('address', $settings['address'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        İletişim Bilgilerini Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Sosyal Medya Sekmesi -->
        <div id="content-social" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.social') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Sosyal Medya</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Facebook -->
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fab fa-facebook-f"></i>
                                </span>
                                <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $settings['facebook'] ?? '') }}" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- Twitter -->
                        <div>
                            <label for="twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fab fa-twitter"></i>
                                </span>
                                <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $settings['twitter'] ?? '') }}" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fab fa-instagram"></i>
                                </span>
                                <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- LinkedIn -->
                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fab fa-linkedin-in"></i>
                                </span>
                                <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- YouTube -->
                        <div>
                            <label for="youtube" class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fab fa-youtube"></i>
                                </span>
                                <input type="url" name="youtube" id="youtube" value="{{ old('youtube', $settings['youtube'] ?? '') }}" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Sosyal Medya Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Ana Sayfa İçerik Ayarları -->
        <div id="content-home" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.home') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Ana Sayfa İçerik Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ana Sayfa Hero Başlık -->
                        <div>
                            <label for="home_hero_title" class="block text-sm font-medium text-gray-700 mb-1">Hero Başlık</label>
                            <input type="text" name="home_hero_title" id="home_hero_title" value="{{ old('home_hero_title', $settings['home_hero_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Ana Sayfa Hero Açıklama -->
                        <div>
                            <label for="home_hero_description" class="block text-sm font-medium text-gray-700 mb-1">Hero Açıklama</label>
                            <textarea name="home_hero_description" id="home_hero_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('home_hero_description', $settings['home_hero_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Ana Sayfa Hero Görseli -->
                        <div class="md:col-span-2">
                            <label for="home_hero_image" class="block text-sm font-medium text-gray-700 mb-1">Hero Arkaplan Görseli</label>
                            
                            @if(isset($settings['home_hero_image']) && $settings['home_hero_image'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['home_hero_image']) }}" 
                                        alt="Hero Görseli" class="h-32 object-cover rounded">
                                    <input type="hidden" name="old_home_hero_image" value="{{ $settings['home_hero_image'] }}">
                                </div>
                            @endif
                            
                            <input type="file" name="home_hero_image" id="home_hero_image" accept="image/*"
                                class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG veya GIF. Maksimum 5MB. Önerilen boyut: 1920x1080px</p>
                        </div>
                        
                        <!-- Ana Sayfa Özellikler Başlık -->
                        <div>
                            <label for="home_features_title" class="block text-sm font-medium text-gray-700 mb-1">Özellikler Başlık</label>
                            <input type="text" name="home_features_title" id="home_features_title" value="{{ old('home_features_title', $settings['home_features_title'] ?? 'Neden Bizi Tercih Etmelisiniz') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Ana Sayfa Öne Çıkan Emlaklar Başlık -->
                        <div>
                            <label for="home_featured_properties_title" class="block text-sm font-medium text-gray-700 mb-1">Öne Çıkan Emlaklar Başlık</label>
                            <input type="text" name="home_featured_properties_title" id="home_featured_properties_title" value="{{ old('home_featured_properties_title', $settings['home_featured_properties_title'] ?? 'Öne Çıkan Emlaklar') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Ana Sayfa Öne Çıkan Danışmanlar Başlık -->
                        <div>
                            <label for="home_featured_agents_title" class="block text-sm font-medium text-gray-700 mb-1">Öne Çıkan Danışmanlar Başlık</label>
                            <input type="text" name="home_featured_agents_title" id="home_featured_agents_title" value="{{ old('home_featured_agents_title', $settings['home_featured_agents_title'] ?? 'Emlak Danışmanlarımız') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Ana Sayfa Bölge Bilgisi Başlık -->
                        <div>
                            <label for="home_location_title" class="block text-sm font-medium text-gray-700 mb-1">Bölge Bilgisi Başlık</label>
                            <input type="text" name="home_location_title" id="home_location_title" value="{{ old('home_location_title', $settings['home_location_title'] ?? 'Kaş ve Kalkan Hakkında') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Ana Sayfa Bölge Bilgisi İçerik -->
                        <div class="md:col-span-2">
                            <label for="home_location_description" class="block text-sm font-medium text-gray-700 mb-1">Bölge Bilgisi İçerik</label>
                            <textarea name="home_location_description" id="home_location_description" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('home_location_description', $settings['home_location_description'] ?? 'Kaş ve Kalkan, Türkiye\'nin en güzel kıyı bölgelerinden biridir. Berrak denizi, muhteşem manzarası ve zengin kültürü ile her yıl binlerce turisti kendine çekmektedir.') }}</textarea>
                        </div>
                        
                        <!-- Ana Sayfa Bölge Bilgisi Görseli -->
                        <div class="md:col-span-2">
                            <label for="home_location_image" class="block text-sm font-medium text-gray-700 mb-1">Bölge Bilgisi Görseli</label>
                            
                            @if(isset($settings['home_location_image']) && $settings['home_location_image'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['home_location_image']) }}" 
                                        alt="Bölge Görseli" class="h-32 object-cover rounded">
                                    <input type="hidden" name="old_home_location_image" value="{{ $settings['home_location_image'] }}">
                                </div>
                            @endif
                            
                            <input type="file" name="home_location_image" id="home_location_image" accept="image/*"
                                class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG veya GIF. Maksimum 5MB.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Ana Sayfa Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Hakkımızda Sayfası Ayarları -->
        <div id="content-about" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.about') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Hakkımızda Sayfası Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hakkımızda Başlık -->
                        <div>
                            <label for="about_title" class="block text-sm font-medium text-gray-700 mb-1">Hakkımızda Başlık</label>
                            <input type="text" name="about_title" id="about_title" value="{{ old('about_title', $settings['about_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Ana sayfa üst kısmında görünen başlık</p>
                        </div>
                        
                        <!-- Şirketimiz Hakkında Başlık -->
                        <div>
                            <label for="about_company_title" class="block text-sm font-medium text-gray-700 mb-1">Şirketimiz Hakkında Başlık</label>
                            <input type="text" name="about_company_title" id="about_company_title" value="{{ old('about_company_title', $settings['about_company_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Sayfa içeriğindeki şirket bilgileri bölüm başlığı</p>
                        </div>
                        
                        <!-- Hakkımızda Açıklama -->
                        <div class="md:col-span-2">
                            <label for="about_description" class="block text-sm font-medium text-gray-700 mb-1">Hakkımızda Açıklama</label>
                            <textarea name="about_description" id="about_description" rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('about_description', $settings['about_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Hakkımızda Görsel -->
                        <div class="md:col-span-2">
                            <label for="about_image" class="block text-sm font-medium text-gray-700 mb-1">Hakkımızda Görseli</label>
                            
                            @if(isset($settings['about_image']) && $settings['about_image'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['about_image']) }}" 
                                        alt="Hakkımızda Görseli" class="h-32 object-cover rounded">
                                    <input type="hidden" name="old_about_image" value="{{ $settings['about_image'] }}">
                                </div>
                            @endif
                            
                            <input type="file" name="about_image" id="about_image" accept="image/*"
                                class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG veya GIF. Maksimum 5MB.</p>
                        </div>
                        
                        <!-- Misyon Başlık -->
                        <div>
                            <label for="about_mission_title" class="block text-sm font-medium text-gray-700 mb-1">Misyon Başlık</label>
                            <input type="text" name="about_mission_title" id="about_mission_title" value="{{ old('about_mission_title', $settings['about_mission_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Misyon Açıklama -->
                        <div>
                            <label for="about_mission_description" class="block text-sm font-medium text-gray-700 mb-1">Misyon Açıklama</label>
                            <textarea name="about_mission_description" id="about_mission_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('about_mission_description', $settings['about_mission_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Vizyon Başlık -->
                        <div>
                            <label for="about_vision_title" class="block text-sm font-medium text-gray-700 mb-1">Vizyon Başlık</label>
                            <input type="text" name="about_vision_title" id="about_vision_title" value="{{ old('about_vision_title', $settings['about_vision_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Vizyon Açıklama -->
                        <div>
                            <label for="about_vision_description" class="block text-sm font-medium text-gray-700 mb-1">Vizyon Açıklama</label>
                            <textarea name="about_vision_description" id="about_vision_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('about_vision_description', $settings['about_vision_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Ekip Başlık -->
                        <div>
                            <label for="about_team_title" class="block text-sm font-medium text-gray-700 mb-1">Ekip Başlık</label>
                            <input type="text" name="about_team_title" id="about_team_title" value="{{ old('about_team_title', $settings['about_team_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Neden Bizi Seçmelisiniz Bölümü -->
                        <div class="mb-10">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Neden Bizi Seçmelisiniz? Bölümü</h3>
                            <p class="mb-4 text-sm text-gray-500">Bu ayarlar hem "Hakkımızda" sayfasındaki "Neden Bizi Seçmelisiniz?" bölümünü hem de Ana sayfadaki "Neden Remax Pupa Emlak?" bölümünü kontrol eder.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bölüm Başlığı -->
                                <div class="md:col-span-2">
                                    <label for="about_why_us_title" class="block text-sm font-medium text-gray-700 mb-1">Bölüm Başlığı</label>
                                    <input type="text" name="about_why_us_title" id="about_why_us_title" value="{{ old('about_why_us_title', $settings['about_why_us_title'] ?? 'Neden Bizi Seçmelisiniz?') }}" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                        required>
                                </div>
                                
                                <!-- Madde 1 -->
                                <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">1. Madde</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="about_why_us_item1_icon" class="block text-sm font-medium text-gray-700 mb-1">İkon</label>
                                            <input type="text" name="about_why_us_item1_icon" id="about_why_us_item1_icon" value="{{ old('about_why_us_item1_icon', $settings['about_why_us_item1_icon'] ?? 'award') }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>
                                            <p class="mt-1 text-xs text-gray-500">Font Awesome ikon ismi giriniz (örn: award, star, check, trophy)</p>
                                        </div>
                                        <div>
                                            <label for="about_why_us_item1_title" class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                                            <input type="text" name="about_why_us_item1_title" id="about_why_us_item1_title" value="{{ old('about_why_us_item1_title', $settings['about_why_us_item1_title'] ?? 'Deneyim') }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="about_why_us_item1_description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                                            <textarea name="about_why_us_item1_description" id="about_why_us_item1_description" rows="2"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>{{ old('about_why_us_item1_description', $settings['about_why_us_item1_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Madde 2 -->
                                <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">2. Madde</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="about_why_us_item2_icon" class="block text-sm font-medium text-gray-700 mb-1">İkon</label>
                                            <input type="text" name="about_why_us_item2_icon" id="about_why_us_item2_icon" value="{{ old('about_why_us_item2_icon', $settings['about_why_us_item2_icon'] ?? 'handshake') }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>
                                            <p class="mt-1 text-xs text-gray-500">Font Awesome ikon ismi giriniz (örn: award, star, check, trophy)</p>
                                        </div>
                                        <div>
                                            <label for="about_why_us_item2_title" class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                                            <input type="text" name="about_why_us_item2_title" id="about_why_us_item2_title" value="{{ old('about_why_us_item2_title', $settings['about_why_us_item2_title'] ?? 'Güven') }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="about_why_us_item2_description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                                            <textarea name="about_why_us_item2_description" id="about_why_us_item2_description" rows="2"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>{{ old('about_why_us_item2_description', $settings['about_why_us_item2_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Madde 3 -->
                                <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">3. Madde</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="about_why_us_item3_icon" class="block text-sm font-medium text-gray-700 mb-1">İkon</label>
                                            <input type="text" name="about_why_us_item3_icon" id="about_why_us_item3_icon" value="{{ old('about_why_us_item3_icon', $settings['about_why_us_item3_icon'] ?? 'globe') }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>
                                            <p class="mt-1 text-xs text-gray-500">Font Awesome ikon ismi giriniz (örn: award, star, check, trophy)</p>
                                        </div>
                                        <div>
                                            <label for="about_why_us_item3_title" class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                                            <input type="text" name="about_why_us_item3_title" id="about_why_us_item3_title" value="{{ old('about_why_us_item3_title', $settings['about_why_us_item3_title'] ?? 'Global Ağ') }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="about_why_us_item3_description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                                            <textarea name="about_why_us_item3_description" id="about_why_us_item3_description" rows="2"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                                required>{{ old('about_why_us_item3_description', $settings['about_why_us_item3_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Hakkımızda Sayfası Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Emlaklar Sekmesi -->
        <div id="content-properties" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.properties') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Emlaklar Sayfası Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Emlaklar Başlık -->
                        <div>
                            <label for="properties_title" class="block text-sm font-medium text-gray-700 mb-1">Emlaklar Başlık</label>
                            <input type="text" name="properties_title" id="properties_title" value="{{ old('properties_title', $settings['properties_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Emlaklar Açıklama -->
                        <div>
                            <label for="properties_description" class="block text-sm font-medium text-gray-700 mb-1">Emlaklar Açıklama</label>
                            <textarea name="properties_description" id="properties_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('properties_description', $settings['properties_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Emlaklar Banner Görsel -->
                        <div class="md:col-span-2">
                            <label for="properties_banner_image" class="block text-sm font-medium text-gray-700 mb-1">Emlaklar Banner Görsel</label>
                            
                            @if(isset($settings['properties_banner_image']) && $settings['properties_banner_image'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['properties_banner_image']) }}" 
                                        alt="Emlaklar Banner" class="h-32 object-cover rounded">
                                    <input type="hidden" name="old_properties_banner_image" value="{{ $settings['properties_banner_image'] }}">
                                </div>
                            @endif
                            
                            <input type="file" name="properties_banner_image" id="properties_banner_image" accept="image/*"
                                class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG veya GIF. Maksimum 5MB. Önerilen boyut: 1920x400px</p>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Emlaklar Sayfası Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Danışmanlar Sekmesi -->
        <div id="content-agents" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.agents') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Danışmanlar Sayfası Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Danışmanlar Başlık -->
                        <div>
                            <label for="agents_title" class="block text-sm font-medium text-gray-700 mb-1">Danışmanlar Başlık</label>
                            <input type="text" name="agents_title" id="agents_title" value="{{ old('agents_title', $settings['agents_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Danışmanlar Açıklama -->
                        <div>
                            <label for="agents_description" class="block text-sm font-medium text-gray-700 mb-1">Danışmanlar Açıklama</label>
                            <textarea name="agents_description" id="agents_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('agents_description', $settings['agents_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Danışmanlar Banner Görsel -->
                        <div class="md:col-span-2">
                            <label for="agents_banner_image" class="block text-sm font-medium text-gray-700 mb-1">Danışmanlar Banner Görsel</label>
                            
                            @if(isset($settings['agents_banner_image']) && $settings['agents_banner_image'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['agents_banner_image']) }}" 
                                        alt="Danışmanlar Banner" class="h-32 object-cover rounded">
                                    <input type="hidden" name="old_agents_banner_image" value="{{ $settings['agents_banner_image'] }}">
                                </div>
                            @endif
                            
                            <input type="file" name="agents_banner_image" id="agents_banner_image" accept="image/*"
                                class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG veya GIF. Maksimum 5MB. Önerilen boyut: 1920x400px</p>
                        </div>
                        
                        <!-- Ekibe Katılın Başlık -->
                        <div>
                            <label for="agents_join_title" class="block text-sm font-medium text-gray-700 mb-1">Ekibe Katılın Başlık</label>
                            <input type="text" name="agents_join_title" id="agents_join_title" value="{{ old('agents_join_title', $settings['agents_join_title'] ?? 'Ekibimize Katılmak İster misiniz?') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Ekibe Katılın Açıklama -->
                        <div>
                            <label for="agents_join_description" class="block text-sm font-medium text-gray-700 mb-1">Ekibe Katılın Açıklama</label>
                            <textarea name="agents_join_description" id="agents_join_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('agents_join_description', $settings['agents_join_description'] ?? 'Kaş ve Kalkan bölgesinde dinamik ve profesyonel ekibimizin bir parçası olmak için bizimle iletişime geçin.') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Danışmanlar Sayfası Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- İletişim Sayfası Sekmesi -->
        <div id="content-contact-page" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.contact-page') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">İletişim Sayfası Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- İletişim Başlık -->
                        <div>
                            <label for="contact_title" class="block text-sm font-medium text-gray-700 mb-1">İletişim Başlık</label>
                            <input type="text" name="contact_title" id="contact_title" value="{{ old('contact_title', $settings['contact_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- İletişim Açıklama -->
                        <div>
                            <label for="contact_description" class="block text-sm font-medium text-gray-700 mb-1">İletişim Açıklama</label>
                            <textarea name="contact_description" id="contact_description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('contact_description', $settings['contact_description'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- İletişim Form Başlık -->
                        <div>
                            <label for="contact_form_title" class="block text-sm font-medium text-gray-700 mb-1">Form Başlık</label>
                            <input type="text" name="contact_form_title" id="contact_form_title" value="{{ old('contact_form_title', $settings['contact_form_title'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- İletişim Bilgileri Başlık -->
                        <div>
                            <label for="contact_info_title" class="block text-sm font-medium text-gray-700 mb-1">İletişim Bilgileri Başlık</label>
                            <input type="text" name="contact_info_title" id="contact_info_title" value="{{ old('contact_info_title', $settings['contact_info_title'] ?? 'İletişim Bilgilerimiz') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Detaylı İletişim Bilgileri</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Adres Başlığı -->
                        <div>
                            <label for="contact_address_title" class="block text-sm font-medium text-gray-700 mb-1">Adres Başlığı</label>
                            <input type="text" name="contact_address_title" id="contact_address_title" value="{{ old('contact_address_title', $settings['contact_address_title'] ?? 'Adres') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Detaylı Adres -->
                        <div class="md:col-span-2">
                            <label for="contact_address_detail" class="block text-sm font-medium text-gray-700 mb-1">Detaylı Adres</label>
                            <textarea name="contact_address_detail" id="contact_address_detail" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('contact_address_detail', $settings['contact_address_detail'] ?? "Andifli Mah. Hasan Altan Cad.\nNo:13/Z-1 Kaş / Antalya\nTürkiye") }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Her satır için yeni bir satır kullanabilirsiniz.</p>
                        </div>
                        
                        <!-- Telefon Başlığı -->
                        <div>
                            <label for="contact_phone_title" class="block text-sm font-medium text-gray-700 mb-1">Telefon Başlığı</label>
                            <input type="text" name="contact_phone_title" id="contact_phone_title" value="{{ old('contact_phone_title', $settings['contact_phone_title'] ?? 'Telefon') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Telefon 1 -->
                        <div>
                            <label for="contact_phone1" class="block text-sm font-medium text-gray-700 mb-1">Telefon 1</label>
                            <input type="text" name="contact_phone1" id="contact_phone1" value="{{ old('contact_phone1', $settings['contact_phone1'] ?? '+90 242 836 12 34') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Telefon 2 -->
                        <div>
                            <label for="contact_phone2" class="block text-sm font-medium text-gray-700 mb-1">Telefon 2 (opsiyonel)</label>
                            <input type="text" name="contact_phone2" id="contact_phone2" value="{{ old('contact_phone2', $settings['contact_phone2'] ?? '+90 530 456 12 34') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- E-posta Başlığı -->
                        <div>
                            <label for="contact_email_title" class="block text-sm font-medium text-gray-700 mb-1">E-posta Başlığı</label>
                            <input type="text" name="contact_email_title" id="contact_email_title" value="{{ old('contact_email_title', $settings['contact_email_title'] ?? 'E-posta') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- E-posta 1 -->
                        <div>
                            <label for="contact_email1" class="block text-sm font-medium text-gray-700 mb-1">E-posta 1</label>
                            <input type="email" name="contact_email1" id="contact_email1" value="{{ old('contact_email1', $settings['contact_email1'] ?? 'info@remaxpupa.com') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- E-posta 2 -->
                        <div>
                            <label for="contact_email2" class="block text-sm font-medium text-gray-700 mb-1">E-posta 2 (opsiyonel)</label>
                            <input type="email" name="contact_email2" id="contact_email2" value="{{ old('contact_email2', $settings['contact_email2'] ?? 'satis@remaxpupa.com') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Çalışma Saatleri Başlığı -->
                        <div>
                            <label for="contact_hours_title" class="block text-sm font-medium text-gray-700 mb-1">Çalışma Saatleri Başlığı</label>
                            <input type="text" name="contact_hours_title" id="contact_hours_title" value="{{ old('contact_hours_title', $settings['contact_hours_title'] ?? 'Çalışma Saatleri') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Çalışma Saatleri -->
                        <div class="md:col-span-2">
                            <label for="contact_hours_detail" class="block text-sm font-medium text-gray-700 mb-1">Çalışma Saatleri Detayı</label>
                            <textarea name="contact_hours_detail" id="contact_hours_detail" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('contact_hours_detail', $settings['contact_hours_detail'] ?? "Pazartesi - Cuma: 09:00 - 18:00\nCumartesi: 10:00 - 15:00\nPazar: Kapalı") }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Her satır için yeni bir satır kullanabilirsiniz.</p>
                        </div>
                        
                        <!-- Konum Başlığı -->
                        <div>
                            <label for="contact_location_title" class="block text-sm font-medium text-gray-700 mb-1">Konum Başlığı</label>
                            <input type="text" name="contact_location_title" id="contact_location_title" value="{{ old('contact_location_title', $settings['contact_location_title'] ?? 'Konum') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Google Harita Embed Kodu -->
                        <div class="md:col-span-2">
                            <label for="contact_map_embed" class="block text-sm font-medium text-gray-700 mb-1">Google Harita Embed Kodu</label>
                            <textarea name="contact_map_embed" id="contact_map_embed" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('contact_map_embed', $settings['contact_map_embed'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Google Maps'ten alınan iframe kodunu buraya yapıştırın.</p>
                        </div>
                        
                        <!-- İletişim Banner Görsel -->
                        <div class="md:col-span-2">
                            <label for="contact_banner_image" class="block text-sm font-medium text-gray-700 mb-1">İletişim Banner Görsel</label>
                            
                            @if(isset($settings['contact_banner_image']) && $settings['contact_banner_image'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['contact_banner_image']) }}" 
                                        alt="İletişim Banner" class="h-32 object-cover rounded">
                                    <input type="hidden" name="old_contact_banner_image" value="{{ $settings['contact_banner_image'] }}">
                                </div>
                            @endif
                            
                            <input type="file" name="contact_banner_image" id="contact_banner_image" accept="image/*"
                                class="w-full border border-gray-300 px-3 py-2 rounded-md text-sm">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG veya GIF. Maksimum 5MB.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Sosyal Medya Bölümü</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sosyal Medya Başlık -->
                        <div>
                            <label for="contact_social_title" class="block text-sm font-medium text-gray-700 mb-1">Sosyal Medya Başlığı</label>
                            <input type="text" name="contact_social_title" id="contact_social_title" value="{{ old('contact_social_title', $settings['contact_social_title'] ?? 'Bizi Takip Edin') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        İletişim Sayfası Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer Sekmesi -->
        <div id="content-footer" class="settings-content p-6 hidden">
            <form action="{{ route('admin.settings.update.footer') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Footer Ayarları</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hakkımızda Metni -->
                        <div class="md:col-span-2">
                            <label for="footer_about_text" class="block text-sm font-medium text-gray-700 mb-1">Hakkımızda Metni</label>
                            <textarea name="footer_about_text" id="footer_about_text" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('footer_about_text', $settings['footer_about_text'] ?? '') }}</textarea>
                        </div>
                        
                        <!-- Footer İletişim Bilgileri -->
                        <div>
                            <label for="footer_address" class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                            <textarea name="footer_address" id="footer_address" rows="2"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>{{ old('footer_address', $settings['footer_address'] ?? '') }}</textarea>
                        </div>
                        
                        <div>
                            <label for="footer_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                            <input type="text" name="footer_phone" id="footer_phone" value="{{ old('footer_phone', $settings['footer_phone'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <div>
                            <label for="footer_email" class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                            <input type="email" name="footer_email" id="footer_email" value="{{ old('footer_email', $settings['footer_email'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Haber Bülteni Metni -->
                        <div class="md:col-span-2">
                            <label for="footer_newsletter_text" class="block text-sm font-medium text-gray-700 mb-1">Haber Bülteni Metni</label>
                            <input type="text" name="footer_newsletter_text" id="footer_newsletter_text" value="{{ old('footer_newsletter_text', $settings['footer_newsletter_text'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <!-- Telif Hakkı Metni -->
                        <div class="md:col-span-2">
                            <label for="footer_copyright_text" class="block text-sm font-medium text-gray-700 mb-1">Telif Hakkı Metni</label>
                            <input type="text" name="footer_copyright_text" id="footer_copyright_text" value="{{ old('footer_copyright_text', $settings['footer_copyright_text'] ?? '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                            <p class="mt-1 text-xs text-gray-500">HTML etiketleri kullanabilirsiniz (örn: &amp;copy; 2024)</p>
                        </div>
                        
                        <!-- Yasal Bağlantılar -->
                        <div>
                            <label for="footer_privacy_url" class="block text-sm font-medium text-gray-700 mb-1">Gizlilik Politikası URL</label>
                            <input type="text" name="footer_privacy_url" id="footer_privacy_url" value="{{ old('footer_privacy_url', $settings['footer_privacy_url'] ?? '#') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                        
                        <div>
                            <label for="footer_terms_url" class="block text-sm font-medium text-gray-700 mb-1">Kullanım Şartları URL</label>
                            <input type="text" name="footer_terms_url" id="footer_terms_url" value="{{ old('footer_terms_url', $settings['footer_terms_url'] ?? '#') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                        </div>
                    </div>
                </div>
                
                <!-- Butonlar -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Sıfırla
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Footer Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Butonlar -->
        <div class="flex justify-end space-x-3">
            <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                Sıfırla
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Kaydet
            </button>
        </div>
    </div>
</div>
@endsection 