@extends('layouts.app')

@section('title', isset($settings['agents_title']) ? $settings['agents_title'] : 'Danışmanlarımız')
@section('meta_description', isset($settings['agents_description']) ? $settings['agents_description'] : 'Kaş ve Kalkan bölgesindeki uzman emlak danışmanlarımız ile tanışın. Her biri bölgeyi yakından tanıyan, profesyonel hizmet sunan ekip üyelerimiz.')

@section('content')
<div class="container-fluid px-4 md:px-8 lg:px-12 py-8">
    <!-- Banner -->
    <div class="relative h-64 md:h-80 lg:h-96 mb-10 rounded-lg overflow-hidden">
        <div class="absolute inset-0 bg-blue-900 bg-opacity-50"></div>
        @if(isset($settings['agents_banner_image']) && $settings['agents_banner_image'])
            <img src="{{ asset('storage/' . $settings['agents_banner_image']) }}" alt="{{ $settings['agents_title'] ?? 'Danışmanlarımız' }}" class="w-full h-full object-cover">
        @else
            <img src="{{ asset('images/banners/agents-banner.jpg') }}" alt="Danışmanlarımız" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 flex items-center justify-center text-center">
            <div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">{{ $settings['agents_title'] ?? 'Danışmanlarımız' }}</h1>
                <p class="text-lg text-white max-w-2xl mx-auto">{{ $settings['agents_description'] ?? 'Uzman kadromuz ile Kaş ve Kalkan bölgesindeki emlak ihtiyaçlarınızda yanınızdayız.' }}</p>
            </div>
        </div>
    </div>

    <!-- Danışman Listesi -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($agents as $agent)
        <div class="bg-white rounded-lg overflow-hidden shadow-md transition duration-300 hover:shadow-lg">
            <div class="aspect-w-3 aspect-h-4 relative">
                @if($agent->photo && !str_starts_with($agent->photo, 'default'))
                    @if(str_starts_with($agent->photo, 'agents/'))
                    <img src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                    @else
                    <img src="{{ asset('storage/agents/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                    @endif
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>
            <div class="p-5">
                <h2 class="text-xl font-semibold mb-1">{{ $agent->name }}</h2>
                <p class="text-blue-600 mb-3">{{ $agent->title }}</p>
                
                <div class="space-y-2 mb-4">
                    @if($agent->phone)
                    <div class="flex items-center">
                        <i class="fas fa-phone-alt text-blue-600 mr-2 w-5 text-center"></i>
                        <a href="tel:{{ $agent->phone }}" class="text-gray-600 hover:text-blue-600">{{ $agent->phone }}</a>
                    </div>
                    @endif
                    
                    @if($agent->email)
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-600 mr-2 w-5 text-center"></i>
                        <a href="mailto:{{ $agent->email }}" class="text-gray-600 hover:text-blue-600">{{ $agent->email }}</a>
                    </div>
                    @endif

                    <div class="flex items-center">
                        <i class="fas fa-home text-blue-600 mr-2 w-5 text-center"></i>
                        <span class="text-gray-600">{{ $agent->properties->whereIn('status', ['sale', 'rent'])->count() }} İlan</span>
                    </div>
                </div>
                
                <a href="{{ route('agents.show', $agent->slug) }}" class="block text-center py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">Profili Görüntüle</a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center">
            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                <i class="fas fa-users text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-800 mb-2">Henüz Danışman Bulunamadı</h3>
            <p class="text-gray-600">Çok yakında uzman danışmanlarımız hizmetinizde olacak.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Ekibimize Katılın -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-8 mt-16 text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">{{ $settings['agents_join_title'] ?? 'Ekibimize Katılmak İster misiniz?' }}</h2>
            <p class="text-lg mb-6">{{ $settings['agents_join_description'] ?? 'Kaş ve Kalkan bölgesinde dinamik ve profesyonel ekibimizin bir parçası olmak için bizimle iletişime geçin.' }}</p>
            <a href="{{ route('contact.index') }}" class="inline-block py-3 px-6 bg-white text-blue-700 font-medium rounded-md hover:bg-gray-100 transition duration-300">İletişime Geçin</a>
        </div>
    </div>
</div>
@endsection 