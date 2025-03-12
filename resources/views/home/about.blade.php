@extends('layouts.app')

@section('title', 'Hakkımızda')
@section('meta_description', 'Remax Pupa Emlak hakkında bilgi edinin. Kaş ve Kalkan bölgesinde emlak deneyimimiz ile hizmetinizdeyiz.')

@section('content')
<div class="bg-blue-600 py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white text-center">{{ $settings['about_title'] ?? 'Hakkımızda' }}</h1>
        <p class="text-xl text-white text-center mt-4">{{ Str::limit($settings['about_description'] ?? '20 yılı aşkın tecrübemizle hizmetinizdeyiz', 100) }}</p>
    </div>
</div>

<!-- Şirket Bilgileri -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 md:pr-12 mb-8 md:mb-0">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $settings['about_company_title'] ?? 'Şirketimiz Hakkında' }}</h2>
                <div class="text-gray-600">
                    {!! nl2br(e($settings['about_description'] ?? 'Remax Pupa Emlak, 2003 yılından bu yana Kaş ve Kalkan bölgesinde gayrimenkul danışmanlığı hizmeti vermektedir. Profesyonel ekibimiz ve bölgedeki derin bilgimizle, müşterilerimize en doğru emlak yatırımlarını yapmaları konusunda rehberlik ediyoruz.')) !!}
                </div>
            </div>
            <div class="md:w-1/2">
                @if(isset($settings['about_image']) && $settings['about_image'])
                    <img src="{{ asset('storage/' . $settings['about_image']) }}" alt="{{ $settings['about_title'] ?? 'Remax Pupa Emlak' }}" class="rounded-lg shadow-lg w-full">
                @else
                    <img src="{{ asset('images/about-office.jpg') }}" alt="Remax Pupa Emlak Ofisi" class="rounded-lg shadow-lg w-full">
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Vizyon ve Misyon -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-eye fa-3x"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $settings['about_vision_title'] ?? 'Vizyonumuz' }}</h3>
                <p class="text-gray-600">
                    {{ $settings['about_vision_description'] ?? 'Kaş ve Kalkan bölgesinde gayrimenkul sektörünün lider markası olmak, yenilikçi ve müşteri odaklı yaklaşımımızla sektörde örnek gösterilen bir firma olmayı hedefliyoruz.' }}
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-bullseye fa-3x"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $settings['about_mission_title'] ?? 'Misyonumuz' }}</h3>
                <p class="text-gray-600">
                    {{ $settings['about_mission_description'] ?? 'Müşterilerimize doğru, güvenilir ve profesyonel gayrimenkul danışmanlığı hizmeti sunarak, hayallerindeki mülke ulaşmalarını sağlamak en önemli görevimizdir.' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Ekibimiz -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">{{ $settings['about_team_title'] ?? 'Profesyonel Ekibimiz' }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredAgents as $agent)
            <div class="bg-white rounded-lg shadow-md overflow-hidden text-center">
                <div class="h-64 overflow-hidden">
                    @if($agent->photo)
                        <img src="{{ asset('storage/agents/' . $agent->photo) }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400"><i class="fas fa-user fa-3x"></i></span>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $agent->name }}</h3>
                    <p class="text-blue-600 mb-3">{{ $agent->title }}</p>
                    <p class="text-gray-600 mb-4">{{ Str::limit($agent->bio, 100) }}</p>
                    <div class="flex justify-center space-x-4 mb-4">
                        @if($agent->phone)
                            <a href="tel:{{ $agent->phone }}" class="text-gray-600 hover:text-blue-600">
                                <i class="fas fa-phone"></i>
                            </a>
                        @endif
                        @if($agent->email)
                            <a href="mailto:{{ $agent->email }}" class="text-gray-600 hover:text-blue-600">
                                <i class="fas fa-envelope"></i>
                            </a>
                        @endif
                    </div>
                    <a href="{{ route('agents.show', $agent->slug) }}" class="inline-block text-blue-600 hover:text-blue-800">
                        Profili Gör <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Neden Bizi Seçmelisiniz -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">{{ $settings['about_why_us_title'] ?? 'Neden Bizi Seçmelisiniz?' }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-{{ $settings['about_why_us_item1_icon'] ?? 'award' }} fa-3x"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $settings['about_why_us_item1_title'] ?? 'Deneyim' }}</h3>
                <p class="text-gray-600">
                    {{ $settings['about_why_us_item1_description'] ?? '20 yılı aşkın sektör deneyimimizle, bölgenin en köklü emlak danışmanlık firmalarından biriyiz.' }}
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-{{ $settings['about_why_us_item2_icon'] ?? 'handshake' }} fa-3x"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $settings['about_why_us_item2_title'] ?? 'Güven' }}</h3>
                <p class="text-gray-600">
                    {{ $settings['about_why_us_item2_description'] ?? 'Şeffaf ve dürüst çalışma prensibiyle müşterilerimizle güvene dayalı ilişkiler kuruyoruz.' }}
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-{{ $settings['about_why_us_item3_icon'] ?? 'globe' }} fa-3x"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $settings['about_why_us_item3_title'] ?? 'Global Ağ' }}</h3>
                <p class="text-gray-600">
                    {{ $settings['about_why_us_item3_description'] ?? 'Remax global ağının bir parçası olarak, dünya standartlarında hizmet sunuyoruz.' }}
                </p>
            </div>
        </div>
    </div>
</section>
@endsection 