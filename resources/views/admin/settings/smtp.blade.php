@extends('admin.layouts.app')

@section('title', 'SMTP Ayarları')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold">SMTP Ayarları</h1>
    <p class="text-gray-600">Mail gönderimi için SMTP sunucu ayarlarını yapılandırın.</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('admin.smtp.update') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Mail Driver -->
            <div>
                <label for="MAIL_MAILER" class="block text-sm font-medium text-gray-700 mb-1">Mail Driver</label>
                <select id="MAIL_MAILER" name="MAIL_MAILER" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="smtp" {{ $smtpSettings['MAIL_MAILER'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="sendmail" {{ $smtpSettings['MAIL_MAILER'] == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    <option value="mailgun" {{ $smtpSettings['MAIL_MAILER'] == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                    <option value="ses" {{ $smtpSettings['MAIL_MAILER'] == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                </select>
                @error('MAIL_MAILER')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail Host -->
            <div>
                <label for="MAIL_HOST" class="block text-sm font-medium text-gray-700 mb-1">Mail Host</label>
                <input type="text" name="MAIL_HOST" id="MAIL_HOST" value="{{ $smtpSettings['MAIL_HOST'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('MAIL_HOST')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail Port -->
            <div>
                <label for="MAIL_PORT" class="block text-sm font-medium text-gray-700 mb-1">Mail Port</label>
                <input type="text" name="MAIL_PORT" id="MAIL_PORT" value="{{ $smtpSettings['MAIL_PORT'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('MAIL_PORT')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail Encryption -->
            <div>
                <label for="MAIL_ENCRYPTION" class="block text-sm font-medium text-gray-700 mb-1">Mail Şifreleme</label>
                <select id="MAIL_ENCRYPTION" name="MAIL_ENCRYPTION" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="tls" {{ $smtpSettings['MAIL_ENCRYPTION'] == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ $smtpSettings['MAIL_ENCRYPTION'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="" {{ $smtpSettings['MAIL_ENCRYPTION'] == '' ? 'selected' : '' }}>Hiçbiri</option>
                </select>
                @error('MAIL_ENCRYPTION')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail Username -->
            <div>
                <label for="MAIL_USERNAME" class="block text-sm font-medium text-gray-700 mb-1">Mail Kullanıcı Adı</label>
                <input type="text" name="MAIL_USERNAME" id="MAIL_USERNAME" value="{{ $smtpSettings['MAIL_USERNAME'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('MAIL_USERNAME')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail Password -->
            <div>
                <label for="MAIL_PASSWORD" class="block text-sm font-medium text-gray-700 mb-1">Mail Şifresi</label>
                <input type="password" name="MAIL_PASSWORD" id="MAIL_PASSWORD" value="{{ $smtpSettings['MAIL_PASSWORD'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('MAIL_PASSWORD')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail From Address -->
            <div>
                <label for="MAIL_FROM_ADDRESS" class="block text-sm font-medium text-gray-700 mb-1">Gönderen E-posta Adresi</label>
                <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS" value="{{ $smtpSettings['MAIL_FROM_ADDRESS'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('MAIL_FROM_ADDRESS')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mail From Name -->
            <div>
                <label for="MAIL_FROM_NAME" class="block text-sm font-medium text-gray-700 mb-1">Gönderen Adı</label>
                <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" value="{{ $smtpSettings['MAIL_FROM_NAME'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('MAIL_FROM_NAME')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin Email -->
            <div>
                <label for="ADMIN_EMAIL" class="block text-sm font-medium text-gray-700 mb-1">Admin E-posta Adresi</label>
                <input type="email" name="ADMIN_EMAIL" id="ADMIN_EMAIL" value="{{ $smtpSettings['ADMIN_EMAIL'] }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <p class="text-xs text-gray-500 mt-1">İletişim formlarından gelen mesajlar bu adrese gönderilecektir.</p>
                @error('ADMIN_EMAIL')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                Ayarları Kaydet
            </button>
        </div>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-200">
        <h2 class="text-xl font-semibold mb-4">SMTP Bağlantı Testi</h2>
        <form method="POST" action="{{ route('admin.smtp.test') }}" class="flex items-end gap-4">
            @csrf
            <div class="flex-grow">
                <label for="test_email" class="block text-sm font-medium text-gray-700 mb-1">Test E-posta Adresi</label>
                <input type="email" name="test_email" id="test_email" required placeholder="ornek@domain.com" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg">
                    Test Maili Gönder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 