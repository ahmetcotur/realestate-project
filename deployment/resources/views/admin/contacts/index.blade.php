@extends('admin.layouts.app')

@section('title', isset($unreadOnly) ? 'Okunmamış Mesajlar' : 'Tüm İletişim Mesajları')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4 mb-0">{{ isset($unreadOnly) ? 'Okunmamış Mesajlar' : 'İletişim Mesajları' }}</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ isset($unreadOnly) ? 'Okunmamış Mesajlar' : 'İletişim Mesajları' }}</li>
            </ol>
        </div>
        <div class="d-flex">
            @if(isset($unreadOnly))
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-list"></i> Tüm Mesajlar
                </a>
            @else
                <a href="{{ route('admin.contacts.unread') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-envelope"></i> Okunmamışlar
                    @if($unreadCount ?? 0 > 0)
                        <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                    @endif
                </a>
            @endif
            
            <button type="button" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                <i class="fas fa-trash"></i> <span id="selectedCountText">Seçilenleri Sil</span>
            </button>
        </div>
    </div>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <div>
                <i class="fas fa-filter me-1"></i> Filtreleme Seçenekleri
            </div>
            <button class="btn btn-sm btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="filterCollapse">
            <div class="card-body border-bottom">
                <form action="{{ route('admin.contacts.index') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Durum</label>
                            <select class="form-select" name="is_read">
                                <option value="">Tümü</option>
                                <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>Okunmamış</option>
                                <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>Okunmuş</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tarih Aralığı</label>
                            <select class="form-select" name="date_range">
                                <option value="">Tümü</option>
                                <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Bugün</option>
                                <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>Son 7 Gün</option>
                                <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>Son 30 Gün</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Arama</label>
                            <input type="text" class="form-control" name="search" placeholder="İsim, e-posta, konu..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Filtrele
                            </button>
                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i> Sıfırla
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form action="{{ route('admin.contacts.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="contactsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="40">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th width="60">Durum</th>
                                <th>Gönderen</th>
                                <th>Konu</th>
                                <th>İlgili İlan</th>
                                <th width="120">Tarih</th>
                                <th width="140">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr class="{{ $contact->is_read ? '' : 'bg-light fw-bold' }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input contact-checkbox" type="checkbox" name="ids[]" value="{{ $contact->id }}">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($contact->is_read)
                                            <span class="badge bg-secondary rounded-pill" title="Okundu"><i class="fas fa-envelope-open"></i></span>
                                        @else
                                            <span class="badge bg-primary rounded-pill" title="Okunmadı"><i class="fas fa-envelope"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $contact->name }}</div>
                                        <div class="small text-muted">
                                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a>
                                        </div>
                                        @if($contact->phone)
                                            <div class="small text-muted">
                                                <a href="tel:{{ $contact->phone }}" class="text-decoration-none">{{ $contact->phone }}</a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $contact->subject ?? 'Konu Belirtilmemiş' }}</td>
                                    <td>
                                        @if($contact->property)
                                            <a href="{{ route('properties.show', $contact->property->slug) }}" target="_blank" class="text-decoration-none">
                                                {{ Str::limit($contact->property->title_tr, 40) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div title="{{ $contact->created_at }}">{{ $contact->created_at->format('d.m.Y') }}</div>
                                        <div class="small text-muted">{{ $contact->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-primary me-1" title="Mesajı Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.contacts.toggle-read', $contact->id) }}" method="POST" class="me-1">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $contact->is_read ? 'btn-warning' : 'btn-success' }}" title="{{ $contact->is_read ? 'Okunmadı olarak işaretle' : 'Okundu olarak işaretle' }}">
                                                    <i class="fas {{ $contact->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Mesajı Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                            <h5>Henüz mesaj bulunmuyor</h5>
                                            <p class="text-muted">{{ isset($unreadOnly) ? 'Okunmamış mesaj bulunmuyor.' : 'Henüz iletişim formu üzerinden bir mesaj gönderilmemiş.' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
            
            <div class="d-flex justify-content-between align-items-center border-top p-3">
                <div>
                    @if($contacts->total() > 0)
                        <span class="text-muted">Toplam {{ $contacts->total() }} mesaj</span>
                    @endif
                </div>
                <div>
                    {{ $contacts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        const selectedCountText = document.getElementById('selectedCountText');
        
        // Tümünü seç/kaldır fonksiyonu
        selectAllCheckbox.addEventListener('change', function() {
            contactCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkDeleteButton();
        });
        
        // Her checkbox değişikliğinde butonu güncelle
        contactCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButton();
                
                // Tüm checkboxlar seçili mi kontrol et
                const allChecked = Array.from(contactCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(contactCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = anyChecked && !allChecked;
            });
        });
        
        // Toplu silme butonu durumunu güncelle
        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.contact-checkbox:checked').length;
            bulkDeleteBtn.disabled = checkedCount === 0;
            
            if (checkedCount > 0) {
                selectedCountText.textContent = `${checkedCount} Mesajı Sil`;
                bulkDeleteBtn.classList.remove('btn-danger');
                bulkDeleteBtn.classList.add('btn-danger');
            } else {
                selectedCountText.textContent = 'Seçilenleri Sil';
                bulkDeleteBtn.classList.remove('btn-danger');
                bulkDeleteBtn.classList.add('btn-danger');
            }
        }
        
        // Toplu silme butonu tıklandığında
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedCount = document.querySelectorAll('.contact-checkbox:checked').length;
            
            if (checkedCount > 0 && confirm(`${checkedCount} mesajı silmek istediğinizden emin misiniz?`)) {
                bulkDeleteForm.submit();
            }
        });
        
        // Filtreleme alanı için toggle davranışı
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.toString()) {
            document.getElementById('filterCollapse').classList.add('show');
        }
    });
</script>
@endsection 