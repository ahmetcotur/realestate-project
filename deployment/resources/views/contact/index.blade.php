@extends('layouts.app')

@section('title', $settings['contact_title'] ?? 'İletişim')
@section('meta_description', $settings['contact_description'] ?? 'Remax Pupa Emlak ile iletişime geçin. Kaş ve Kalkan bölgesindeki emlak seçenekleri için bizimle irtibata geçin.')

@section('content')
<div class="bg-blue-600 py-16" @if(isset($settings['contact_banner_image'])) style="background-image: url('{{ asset('storage/' . $settings['contact_banner_image']) }}'); background-size: cover; background-position: center;" @endif>
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white text-center">{{ $settings['contact_title'] ?? 'İletişim' }}</h1>
        <p class="text-xl text-white text-center mt-4">{{ $settings['contact_description'] ?? 'Sorularınız için bizimle irtibata geçin' }}</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col md:flex-row gap-12">
        <!-- İletişim Formu -->
        <div class="md:w-1/2">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $settings['contact_form_title'] ?? 'Bize Mesaj Gönderin' }}</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc pl-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-gray-700 mb-2">Adınız Soyadınız *</label>
                    <input type="text" name="name" id="name" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           value="{{ old('name') }}">
                </div>
                
                <div>
                    <label for="email" class="block text-gray-700 mb-2">E-posta Adresiniz *</label>
                    <input type="email" name="email" id="email" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           value="{{ old('email') }}">
                </div>
                
                <div>
                    <label for="phone" class="block text-gray-700 mb-2">Telefon Numaranız</label>
                    <input type="tel" name="phone" id="phone"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           value="{{ old('phone') }}">
                </div>
                
                <div>
                    <label for="subject" class="block text-gray-700 mb-2">Konu *</label>
                    <input type="text" name="subject" id="subject" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           value="{{ old('subject') }}">
                </div>
                
                <div>
                    <label for="message" class="block text-gray-700 mb-2">Mesajınız *</label>
                    <textarea name="message" id="message" rows="5" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('message') }}</textarea>
                </div>
                
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition duration-300">
                        Mesaj Gönder
                    </button>
                </div>
            </form>
        </div>
        
        <!-- İletişim Bilgileri -->
        <div class="md:w-1/2">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $settings['contact_info_title'] ?? 'İletişim Bilgilerimiz' }}</h2>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-start mb-6">
                    <div class="text-blue-600 mr-4">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ $settings['contact_address_title'] ?? 'Adres' }}</h3>
                        <p class="text-gray-600">
                            {!! nl2br(e($settings['contact_address_detail'] ?? 'Andifli Mah. Hasan Altan Cad. No:13/Z-1 Kaş / Antalya Türkiye')) !!}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start mb-6">
                    <div class="text-blue-600 mr-4">
                        <i class="fas fa-phone fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ $settings['contact_phone_title'] ?? 'Telefon' }}</h3>
                        <p class="text-gray-600">
                            @if(isset($settings['contact_phone1']) && !empty($settings['contact_phone1']))
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone1']) }}" class="hover:text-blue-600 transition duration-300">{{ $settings['contact_phone1'] }}</a><br>
                            @endif
                            
                            @if(isset($settings['contact_phone2']) && !empty($settings['contact_phone2']))
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone2']) }}" class="hover:text-blue-600 transition duration-300">{{ $settings['contact_phone2'] }}</a>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start mb-6">
                    <div class="text-blue-600 mr-4">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ $settings['contact_email_title'] ?? 'E-posta' }}</h3>
                        <p class="text-gray-600">
                            @if(isset($settings['contact_email1']) && !empty($settings['contact_email1']))
                                <a href="mailto:{{ $settings['contact_email1'] }}" class="hover:text-blue-600 transition duration-300">{{ $settings['contact_email1'] }}</a><br>
                            @endif
                            
                            @if(isset($settings['contact_email2']) && !empty($settings['contact_email2']))
                                <a href="mailto:{{ $settings['contact_email2'] }}" class="hover:text-blue-600 transition duration-300">{{ $settings['contact_email2'] }}</a>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="text-blue-600 mr-4">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ $settings['contact_hours_title'] ?? 'Çalışma Saatleri' }}</h3>
                        <p class="text-gray-600">
                            {!! nl2br(e($settings['contact_hours_detail'] ?? 'Pazartesi - Cuma: 09:00 - 18:00
Cumartesi: 10:00 - 15:00
Pazar: Kapalı')) !!}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Harita -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">{{ $settings['contact_location_title'] ?? 'Konum' }}</h3>
                <div class="h-80 w-full rounded-lg overflow-hidden">
                    @if(isset($settings['contact_map_embed']) && !empty($settings['contact_map_embed']))
                        {!! $settings['contact_map_embed'] !!}
                    @else
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3275.8066447431776!2d29.641516315257168!3d36.20026211171875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14c01f11e533a663%3A0x49c1c925d042eae5!2sKa%C5%9F%2C%20Antalya!5e0!3m2!1str!2str!4v1667814125471!5m2!1str!2str" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sosyal Medya Bölümü -->
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">{{ $settings['contact_social_title'] ?? 'Bizi Takip Edin' }}</h2>
        
        <div class="flex justify-center space-x-6">
            <a href="{{ $settings['facebook_url'] ?? 'https://facebook.com/remaxpupa' }}" target="_blank" class="bg-white p-4 rounded-full shadow-md text-blue-600 hover:text-blue-800 transition duration-300">
                <i class="fab fa-facebook-f fa-2x"></i>
            </a>
            <a href="{{ $settings['instagram_url'] ?? 'https://instagram.com/remaxpupa' }}" target="_blank" class="bg-white p-4 rounded-full shadow-md text-pink-600 hover:text-pink-800 transition duration-300">
                <i class="fab fa-instagram fa-2x"></i>
            </a>
            <a href="{{ $settings['twitter_url'] ?? 'https://twitter.com/remaxpupa' }}" target="_blank" class="bg-white p-4 rounded-full shadow-md text-blue-400 hover:text-blue-600 transition duration-300">
                <i class="fab fa-twitter fa-2x"></i>
            </a>
            <a href="{{ $settings['youtube_url'] ?? 'https://youtube.com/remaxpupa' }}" target="_blank" class="bg-white p-4 rounded-full shadow-md text-red-600 hover:text-red-800 transition duration-300">
                <i class="fab fa-youtube fa-2x"></i>
            </a>
        </div>
    </div>
</div>
@endsection 