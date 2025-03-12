@extends('admin.layouts.app')

@section('title', 'Emlak Görseli Ekle')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    {{ $property->title_tr }} - Görsel Ekle
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Emlak ilanına yeni görseller yükleyin
                </p>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('admin.properties.images.index', $property->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Görsellere Dön
                </a>
            </div>
        </div>

        @if($errors->any())
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Aşağıdaki hataları düzeltin:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('admin.properties.images.store', $property->id) }}" method="POST" enctype="multipart/form-data" id="image-upload-form">
                @csrf
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Görsel Yükleme</h3>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Çoklu yükleme desteklenir</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Maksimum dosya boyutu: 2MB. İzin verilen uzantılar: jpeg, jpg, png, webp. Büyük dosyalar otomatik olarak optimize edilecektir.</p>
                            
                            <div class="flex flex-col items-center justify-center py-8 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-gray-100 transition-colors" id="dropzone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Dosya Seç</span>
                                            <input id="file-upload" name="images[]" type="file" class="sr-only" multiple accept="image/jpeg,image/png,image/jpg,image/webp">
                                        </label>
                                        <p class="pl-1">veya buraya sürükleyip bırakın</p>
                                    </div>
                                    <p class="text-xs text-gray-500">Daha kaliteli görüntü için yüksek çözünürlüklü görseller kullanın</p>
                                </div>
                                
                                <div class="mt-4 w-full px-4" id="image-preview-container">
                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="preview">
                                        <!-- Önizlemeler burada gösterilecek -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="alt_text" class="block text-sm font-medium text-gray-700">Alternatif Metin</label>
                            <div class="mt-1">
                                <input type="text" name="alt_text" id="alt_text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Görsel açıklaması (SEO için önemli)" value="{{ old('alt_text') }}">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Görsel için alternatif metin (SEO için önemli). Boş bırakılırsa emlak başlığı kullanılır.</p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_featured" name="is_featured" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_featured" class="font-medium text-gray-700">Ana Görsel Olarak Ayarla</label>
                                <p class="text-gray-500">Bu görseli emlak ilanının ana görseli olarak belirle. Daha önce ana görsel olarak ayarlanmış başka bir görsel varsa, o görselin ana görsel özelliği kaldırılacaktır.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Görselleri Yükle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-upload');
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('image-preview-container');
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        const MAX_WIDTH = 1920;
        const QUALITY = 0.9;
        const CHUNK_SIZE = 1024 * 1024; // 1MB parça boyutu
        
        // Form gönderimini engelleyip işlenmiş görüntüleri göndermek için 
        const form = document.querySelector('form');
        const processedFiles = new Map(); // İşlenmiş dosyaları saklamak için
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> İşleniyor...';
            
            const originalInput = document.getElementById('file-upload');
            const files = originalInput.files;
            
            if (files.length === 0) {
                form.submit();
                return;
            }
            
            try {
                // Her dosya için ayrı yükleme işlemi başlat
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    await uploadLargeFile(file, i, submitBtn, files.length);
                }
                
                // Tüm dosyalar yüklendikten sonra ana sayfaya yönlendir
                window.location.href = form.action.replace('/create', '');
                
            } catch (error) {
                console.error('Yükleme hatası:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg> Görselleri Yükle';
                alert('Görsel yükleme hatası: ' + error.message);
            }
        });
        
        // Büyük dosyayı parçalayarak yükleyen fonksiyon
        async function uploadLargeFile(file, fileIndex, submitBtn, totalFiles) {
            // Önce dosyayı optimize et
            const optimizedFile = await optimizeImage(file, fileIndex);
            
            // Yükleme için benzersiz ID oluştur
            const fileId = generateUniqueId();
            const totalChunks = Math.ceil(optimizedFile.size / CHUNK_SIZE);
            
            // Dosyayı parçalara ayır ve her parçayı yükle
            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, optimizedFile.size);
                const chunk = optimizedFile.slice(start, end);
                
                // Dosya parçasını Base64'e dönüştür
                const base64Chunk = await readFileChunkAsBase64(chunk);
                
                // Yükleme durumunu güncelle
                const progressText = `${fileIndex + 1}/${totalFiles} dosya - Parça ${chunkIndex + 1}/${totalChunks}`;
                submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${progressText}`;
                
                // Parçayı yükle
                await uploadChunk({
                    chunk: base64Chunk,
                    index: chunkIndex,
                    totalChunks: totalChunks,
                    filename: file.name,
                    fileId: fileId
                });
            }
            
            // Tüm parçalar yüklendikten sonra, birleştirme isteği gönder
            return completeUpload({
                fileId: fileId,
                filename: file.name,
                totalChunks: totalChunks,
                alt_text: document.getElementById('alt_text').value,
                is_featured: document.getElementById('is_featured').checked ? 1 : 0
            });
        }
        
        // Dosya parçasını Base64 olarak oku
        function readFileChunkAsBase64(chunk) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = e => resolve(e.target.result.split(',')[1]); // Base64 içeriğini al
                reader.onerror = reject;
                reader.readAsDataURL(chunk);
            });
        }
        
        // Parça yükleme isteği gönder
        async function uploadChunk(params) {
            const propertyId = window.location.pathname.match(/properties\/(\d+)\/images/)[1];
            const url = `/admin/properties/${propertyId}/images/upload-chunk`;
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(params)
            });
            
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Parça yükleme hatası');
            }
            
            return await response.json();
        }
        
        // Birleştirme isteği gönder
        async function completeUpload(params) {
            const propertyId = window.location.pathname.match(/properties\/(\d+)\/images/)[1];
            const url = `/admin/properties/${propertyId}/images/complete-upload`;
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(params)
            });
            
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Dosya birleştirme hatası');
            }
            
            return await response.json();
        }
        
        // Benzersiz ID oluştur
        function generateUniqueId() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
        
        // Görsel optimize et
        async function optimizeImage(file, fileIndex) {
            // Dosya zaten küçükse optimize etme
            if (file.size <= MAX_FILE_SIZE && !file.type.match('image.*')) {
                return file;
            }
            
            // Görsel dosyasını işle
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const img = new Image();
                        img.onload = async function() {
                            let canvas = document.createElement('canvas');
                            let ctx = canvas.getContext('2d');
                            let width = img.width;
                            let height = img.height;
                            
                            // Eğer genişlik maksimum genişlikten büyükse, orantılı olarak küçült
                            if (width > MAX_WIDTH) {
                                const ratio = MAX_WIDTH / width;
                                width = MAX_WIDTH;
                                height = height * ratio;
                            }
                            
                            canvas.width = width;
                            canvas.height = height;
                            ctx.drawImage(img, 0, 0, width, height);
                            
                            // Sıkıştırma işlemi - kaliteyi kademeli olarak düşür
                            let quality = QUALITY;
                            let fileBlob;
                            let fileSize;
                            
                            do {
                                fileBlob = await new Promise(resolve => {
                                    canvas.toBlob(resolve, file.type, quality);
                                });
                                fileSize = fileBlob.size;
                                quality -= 0.05; // Her adımda kaliteyi düşür
                                
                                if (quality < 0.5) break; // Minimum kalite sınırı
                            } while (fileSize > MAX_FILE_SIZE);
                            
                            // Yeni dosya oluştur
                            const newFile = new File([fileBlob], file.name, {
                                type: file.type,
                                lastModified: new Date().getTime()
                            });
                            
                            // Dosya boyutunu güncelleme bilgisi
                            updatePreviewFileSize(fileBlob.size, file.name);
                            
                            resolve(newFile);
                        };
                        img.src = e.target.result;
                    } catch (error) {
                        reject(error);
                    }
                };
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }
        
        // Önizlemede dosya boyutunu güncelle
        function updatePreviewFileSize(newSize, fileName) {
            const previewItems = preview.querySelectorAll('[data-file="' + fileName + '"]');
            if (previewItems.length > 0) {
                const fileInfo = previewItems[0].querySelector('.text-gray-500');
                if (fileInfo) {
                    fileInfo.textContent = `${fileName} (${formatFileSize(newSize)}) - Optimize edildi`;
                    fileInfo.classList.add('text-green-500');
                }
            }
        }
        
        // Dosya seçme
        fileInput.addEventListener('change', handleFiles);
        
        // Drag & Drop olayları
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropzone.classList.add('border-blue-500');
            dropzone.classList.add('bg-blue-50');
        }
        
        function unhighlight() {
            dropzone.classList.remove('border-blue-500');
            dropzone.classList.remove('bg-blue-50');
        }
        
        dropzone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFiles();
        }
        
        function handleFiles() {
            const files = fileInput.files;
            preview.innerHTML = '';
            processedFiles.clear();
            
            if (files.length > 0) {
                previewContainer.classList.remove('hidden');
                Array.from(files).forEach((file, index) => previewFile(file, index));
            } else {
                previewContainer.classList.add('hidden');
            }
        }
        
        function previewFile(file, index) {
            // Sadece resim dosyalarını kabul et
            if (!file.type.match('image.*')) {
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.dataset.index = index;
                div.dataset.file = file.name;
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-24 object-cover rounded-md';
                img.alt = file.name;
                
                const fileInfo = document.createElement('div');
                fileInfo.className = 'mt-1 text-xs text-gray-500 truncate';
                fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
                
                // Size uyarısı
                if (file.size > MAX_FILE_SIZE) {
                    const sizeWarning = document.createElement('div');
                    sizeWarning.className = 'mt-1 text-xs text-orange-500';
                    sizeWarning.textContent = 'Yüklenmeden önce otomatik optimize edilecek';
                    div.appendChild(sizeWarning);
                }
                
                div.appendChild(img);
                div.appendChild(fileInfo);
                preview.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });
</script>
@endsection
