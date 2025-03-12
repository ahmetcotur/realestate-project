@extends('admin.layouts.app')

@section('title', 'Yeni Kullanıcı Ekle')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Yeni Kullanıcı Ekle</h1>
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Kullanıcılar</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span>Yeni Kullanıcı</span>
            </div>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Listeye Dön
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">Yeni Kullanıcı Bilgileri</span>
            </div>
        </div>
        
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
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ad Soyad -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                           required>
                </div>
                
                <!-- E-posta -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                           required>
                </div>
                
                <!-- Şifre -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                    <input type="password" name="password" id="password" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                           required>
                </div>
                
                <!-- Şifre Tekrar -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Şifre Tekrar</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                           required>
                </div>
                
                <!-- Rol -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Rolü</label>
                    <select name="role" id="role" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            required x-data="{ role: '{{ old('role', 'agent') }}' }" x-model="role">
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="agent" {{ old('role', 'agent') === 'agent' ? 'selected' : '' }}>Danışman</option>
                    </select>
                </div>
            </div>
            
            <!-- Danışman Bilgileri (role=agent seçilirse görünür) -->
            <div x-data="{ role: '{{ old('role', 'agent') }}' }" x-show="role === 'agent'" class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Danışman Bilgileri</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Danışman Ünvanı -->
                    <div>
                        <label for="agent_title" class="block text-sm font-medium text-gray-700 mb-1">Danışman Ünvanı</label>
                        <input type="text" name="agent_title" id="agent_title" 
                               value="{{ old('agent_title', 'Emlak Danışmanı') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Telefon -->
                    <div>
                        <label for="agent_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                        <input type="text" name="agent_phone" id="agent_phone" 
                               value="{{ old('agent_phone') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Fotoğraf -->
                    <div class="col-span-full">
                        <label for="agent_photo_file" class="block text-sm font-medium text-gray-700 mb-1">Profil Fotoğrafı</label>
                        
                        <input type="file" name="agent_photo_file" id="agent_photo_file" accept="image/*"
                               class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">
                            JPG, PNG veya WebP. Maksimum 2MB.
                        </p>
                    </div>
                    
                    <!-- Biyografi -->
                    <div class="col-span-full">
                        <label for="agent_bio" class="block text-sm font-medium text-gray-700 mb-1">Biyografi</label>
                        <textarea name="agent_bio" id="agent_bio" rows="5" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('agent_bio') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Durum -->
            <div class="mt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Kullanıcı Aktif</label>
                </div>
            </div>
            
            <!-- Butonlar -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                    İptal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Oluştur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 