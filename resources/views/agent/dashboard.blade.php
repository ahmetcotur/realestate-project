@extends('agent.layouts.app')

@section('title', 'Dashboard - Danışman Paneli')

@section('header', 'Dashboard')

@section('content')
<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Emlak İlanları Kartı -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $stats['properties'] }}</h2>
                    <p class="text-gray-600">Emlak İlanlarım</p>
                </div>
            </div>
        </div>

        <!-- İletişim Mesajları Kartı -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $stats['contacts'] }}</h2>
                    <p class="text-gray-600">İletişim Mesajları</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Son Eklenen Emlak İlanlarım</h2>
    
    @if($recentProperties->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($recentProperties as $property)
            <li>
                <a href="{{ route('agent.properties.show', $property) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-blue-600 truncate">{{ $property->title }}</p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $property->status === 'for_sale' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $property->status === 'for_sale' ? 'Satılık' : 'Kiralık' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    {{ $property->city }}
                                </p>
                                <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                    {{ number_format($property->price, 0, ',', '.') }} TL
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <p>
                                    {{ $property->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @else
    <p class="text-gray-500">Henüz emlak ilanınız bulunmamaktadır.</p>
    @endif
</div>

<div>
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Son Gelen Mesajlar</h2>
    
    @if($recentContacts->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($recentContacts as $contact)
            <li>
                <div class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $contact->name }} ({{ $contact->email }})
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $contact->is_read ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $contact->is_read ? 'Okundu' : 'Okunmadı' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 truncate">
                                {{ Str::limit($contact->message, 100) }}
                            </p>
                        </div>
                        <div class="mt-2 flex justify-between">
                            <p class="text-sm text-gray-500">
                                {{ $contact->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @else
    <p class="text-gray-500">Henüz mesajınız bulunmamaktadır.</p>
    @endif
</div>
@endsection 