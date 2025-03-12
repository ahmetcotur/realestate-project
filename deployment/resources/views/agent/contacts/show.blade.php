@extends('agent.layouts.app')

@section('title', 'Mesaj Detayı - Danışman Paneli')

@section('header', 'Mesaj Detayı')

@section('content')
<div class="mb-4">
    <a href="{{ route('agent.contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        Mesajlara Dön
    </a>
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

<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $contact->subject ?? 'Mesaj' }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ $contact->created_at->format('d.m.Y H:i') }}
            </p>
        </div>
        <div class="flex items-center">
            <button 
                type="button" 
                onclick="toggleReadStatus()"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md {{ $contact->is_read ? 'text-blue-700 bg-blue-100 hover:bg-blue-200' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }} focus:outline-none"
            >
                <span id="read-status-icon" class="mr-1.5">
                    @if($contact->is_read)
                        <i class="fas fa-envelope-open"></i>
                    @else
                        <i class="fas fa-envelope"></i>
                    @endif
                </span>
                <span id="read-status-text">{{ $contact->is_read ? 'Okundu Olarak İşaretle' : 'Okunmadı Olarak İşaretle' }}</span>
            </button>
            
            <form action="{{ route('agent.contacts.destroy', $contact) }}" method="POST" class="ml-2" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none">
                    <i class="fas fa-trash-alt mr-1.5"></i> Sil
                </button>
            </form>
        </div>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Gönderen
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $contact->name }}
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    E-posta
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">{{ $contact->email }}</a>
                </dd>
            </div>
            @if($contact->phone)
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Telefon
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a href="tel:{{ $contact->phone }}" class="text-blue-600 hover:text-blue-800">{{ $contact->phone }}</a>
                </dd>
            </div>
            @endif
            @if($contact->property)
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    İlgili İlan
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a href="{{ route('properties.show', $contact->property->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        {{ $contact->property->title_tr }} <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </a>
                </dd>
            </div>
            @endif
            <div class="{{ $contact->property ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">
                    Mesaj
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {!! nl2br(e($contact->message)) !!}
                </dd>
            </div>
        </dl>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Hızlı Yanıt
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Müşteriye doğrudan e-posta gönderin
        </p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <form action="mailto:{{ $contact->email }}" method="get">
            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Konu</label>
                <input type="text" name="subject" id="subject" value="RE: {{ $contact->subject ?? 'Mesajınıza Yanıt' }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Mesaj</label>
                <textarea id="body" name="body" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-paper-plane mr-2"></i> E-posta Gönder
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleReadStatus() {
        fetch('{{ route('agent.contacts.toggle-read', $contact) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = document.getElementById('read-status-icon');
                const text = document.getElementById('read-status-text');
                
                if (data.is_read) {
                    icon.innerHTML = '<i class="fas fa-envelope-open"></i>';
                    text.textContent = 'Okunmadı Olarak İşaretle';
                } else {
                    icon.innerHTML = '<i class="fas fa-envelope"></i>';
                    text.textContent = 'Okundu Olarak İşaretle';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush 