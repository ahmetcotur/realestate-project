@extends('agent.layouts.app')

@section('title', 'Mesajlarım - Danışman Paneli')

@section('header', 'Mesajlarım')

@section('content')
<div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <a href="{{ route('agent.contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ request()->routeIs('agent.contacts.index') && !request()->has('filter') ? 'text-white bg-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white hover:bg-gray-50' }}">
            Tüm Mesajlar
        </a>
        <a href="{{ route('agent.contacts.unread') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ request()->routeIs('agent.contacts.unread') ? 'text-white bg-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white hover:bg-gray-50' }}">
            Okunmamış Mesajlar
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
        {{ session('error') }}
    </div>
@endif

@if($contacts->isEmpty())
    <div class="bg-white shadow overflow-hidden sm:rounded-md p-6 text-center">
        <p class="text-gray-500">Henüz mesajınız bulunmamaktadır.</p>
    </div>
@else
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($contacts as $contact)
                <li>
                    <a href="{{ route('agent.contacts.show', $contact) }}" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(!$contact->is_read)
                                        <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-blue-600 mr-2"></span>
                                    @endif
                                    <p class="text-sm font-medium text-blue-600 truncate">
                                        {{ $contact->subject ?? 'Mesaj' }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $contact->is_read ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $contact->is_read ? 'Okundu' : 'Okunmadı' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between">
                                    <p class="text-sm text-gray-900">
                                        {{ $contact->name }} <span class="text-gray-500">({{ $contact->email }})</span>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $contact->created_at->format('d.m.Y H:i') }}
                                    </p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                    {{ Str::limit($contact->message, 150) }}
                                </p>
                            </div>
                            @if($contact->property)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-home mr-1"></i> {{ $contact->property->title_tr ?? 'İlan' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
@endif
@endsection 