@extends('admin.layouts.app')

@section('title', 'Mesaj Detayı')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Mesaj Detayı</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">İletişim Mesajları</a></li>
        <li class="breadcrumb-item active">Mesaj Detayı</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-envelope-open-text me-1"></i>
                Mesaj Detayı
            </div>
            <div class="d-flex">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </a>
                
                <div class="btn-group me-2">
                    <form action="{{ route('admin.contacts.toggle-read', $contact->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm {{ $contact->is_read ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas {{ $contact->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                            {{ $contact->is_read ? 'Okunmadı olarak işaretle' : 'Okundu olarak işaretle' }}
                        </button>
                    </form>
                </div>
                
                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-user me-1"></i>
                            Gönderen Bilgileri
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Ad Soyad:</th>
                                    <td>{{ $contact->name }}</td>
                                </tr>
                                <tr>
                                    <th>E-posta:</th>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                    </td>
                                </tr>
                                @if($contact->phone)
                                <tr>
                                    <th>Telefon:</th>
                                    <td>
                                        <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Tarih:</th>
                                    <td>{{ $contact->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Durum:</th>
                                    <td>
                                        @if($contact->is_read)
                                            <span class="badge bg-secondary"><i class="fas fa-envelope-open me-1"></i> Okundu</span>
                                        @else
                                            <span class="badge bg-primary"><i class="fas fa-envelope me-1"></i> Okunmadı</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-1"></i>
                            İçerik Bilgileri
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Konu:</th>
                                    <td>{{ $contact->subject ?? 'Konu Belirtilmemiş' }}</td>
                                </tr>
                                @if($contact->property)
                                <tr>
                                    <th>İlgili İlan:</th>
                                    <td>
                                        <a href="{{ route('properties.show', $contact->property->slug) }}" target="_blank">
                                            {{ $contact->property->title_tr }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if($contact->agent_id)
                                <tr>
                                    <th>İlgili Danışman:</th>
                                    <td>
                                        @if($contact->agent)
                                            <a href="{{ route('agents.show', $contact->agent->slug) }}" target="_blank">
                                                {{ $contact->agent->name }}
                                            </a>
                                        @else
                                            Danışman Bilgisi Bulunamadı (#{{ $contact->agent_id }})
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comment-alt me-1"></i>
                    Mesaj İçeriği
                </div>
                <div class="card-body">
                    <div class="message-content p-3 bg-light rounded">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <h5 class="mb-3">Hızlı Yanıt</h5>
                <div class="d-flex gap-2">
                    <a href="mailto:{{ $contact->email }}?subject=RE: {{ $contact->subject ?? 'İletişim Formunuz Hakkında' }}" class="btn btn-primary">
                        <i class="fas fa-reply me-1"></i> E-posta ile Yanıtla
                    </a>
                    
                    @if($contact->phone)
                    <a href="tel:{{ $contact->phone }}" class="btn btn-success">
                        <i class="fas fa-phone-alt me-1"></i> Ara
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 