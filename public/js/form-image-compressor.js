/**
 * Remax Pupa Emlak - Form Görsel Sıkıştırma Entegrasyonu
 * Bu dosya, görsel sıkıştırma modülünü tüm formlara entegre eder
 */

document.addEventListener('DOMContentLoaded', function() {
    // Görsel yükleme formlarını otomatik olarak algıla ve işle
    const forms = document.querySelectorAll('form[enctype="multipart/form-data"]');
    
    forms.forEach(form => {
        // Formdaki tüm dosya input alanlarını bul
        const fileInputs = form.querySelectorAll('input[type="file"]');
        
        if (fileInputs.length === 0) return; // Dosya input yoksa atla
        
        // Her dosya inputu için ilerleme bildirimi oluştur
        fileInputs.forEach(fileInput => {
            // Eğer görsel kabul eden dosya girişi ise
            if (fileInput.accept && fileInput.accept.includes('image')) {
                // Benzersiz ID oluştur
                const uniqueId = 'progress-' + Math.random().toString(36).substring(2, 9);
                fileInput.dataset.progressId = uniqueId;
                
                // Dosya inputun hemen sonrasına ilerleme göstergesi ekle
                const progressContainer = document.createElement('div');
                progressContainer.id = uniqueId;
                progressContainer.classList.add('upload-progress-container', 'hidden', 'mt-2', 'bg-gray-100', 'rounded-md', 'p-2');
                progressContainer.innerHTML = `
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-600 progress-text">Görsel hazırlanıyor...</span>
                        <span class="text-xs text-gray-500 progress-percentage">0%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 progress-bar" style="width: 0%"></div>
                    </div>
                `;
                
                fileInput.parentNode.insertBefore(progressContainer, fileInput.nextSibling);
                
                // İlerleme göstergesini görmek için önizleme de ekle
                const previewContainer = document.createElement('div');
                previewContainer.classList.add('image-preview-container', 'hidden', 'mt-3');
                previewContainer.innerHTML = `
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Önizleme ve Optimize Bilgisi</h4>
                    <div class="image-preview-list grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2"></div>
                `;
                
                fileInput.parentNode.insertBefore(previewContainer, progressContainer.nextSibling);
                
                // Dosya değiştiğinde önizleme göster
                fileInput.addEventListener('change', function() {
                    const files = this.files;
                    const previewList = previewContainer.querySelector('.image-preview-list');
                    
                    // Önizlemeleri temizle
                    previewList.innerHTML = '';
                    
                    if (files.length > 0) {
                        previewContainer.classList.remove('hidden');
                        
                        Array.from(files).forEach(file => {
                            if (file.type.match('image.*')) {
                                const previewItem = document.createElement('div');
                                previewItem.classList.add('image-preview-item', 'relative', 'border', 'rounded', 'p-1');
                                previewItem.dataset.fileName = file.name;
                                
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    previewItem.innerHTML = `
                                        <div class="aspect-w-3 aspect-h-2 mb-1 bg-gray-100 rounded overflow-hidden">
                                            <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-xs text-gray-500 truncate" title="${file.name}">${file.name}</div>
                                        <div class="text-xs text-gray-400">${ImageCompressor.formatFileSize(file.size)}</div>
                                    `;
                                    previewList.appendChild(previewItem);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    } else {
                        previewContainer.classList.add('hidden');
                    }
                });
            }
        });
        
        // Form gönderildiğinde sıkıştırma işlemi için
        form.addEventListener('submit', async function(e) {
            // Dosyaları sıkıştırmadan önce gönderimi engelle
            e.preventDefault();
            
            // Formdaki dosya inputlarını bul ve sıkıştır
            const promises = Array.from(fileInputs)
                .filter(input => input.accept && input.accept.includes('image') && input.files.length > 0)
                .map(async (fileInput) => {
                    // İlerleme konteynerini ve bileşenlerini bul
                    const progressId = fileInput.dataset.progressId;
                    const progressContainer = document.getElementById(progressId);
                    const progressBar = progressContainer.querySelector('.progress-bar');
                    const progressText = progressContainer.querySelector('.progress-text');
                    const progressPercentage = progressContainer.querySelector('.progress-percentage');
                    
                    // İlerleme göstergesini göster
                    progressContainer.classList.remove('hidden');
                    
                    // İlerleme callback fonksiyonu
                    const updateProgress = (percentage, message) => {
                        if (percentage < 0) {
                            // Hata durumu
                            progressBar.style.width = '100%';
                            progressBar.classList.remove('bg-blue-500');
                            progressBar.classList.add('bg-red-500');
                            progressText.textContent = message;
                            progressPercentage.textContent = 'Hata';
                            return;
                        }
                        
                        progressBar.style.width = `${percentage}%`;
                        progressText.textContent = message || 'İşleniyor...';
                        progressPercentage.textContent = `${percentage}%`;
                        
                        // Tamamlandıysa yeşil göster
                        if (percentage >= 100) {
                            progressBar.classList.remove('bg-blue-500');
                            progressBar.classList.add('bg-green-500');
                            
                            // 2 saniye sonra gizle
                            setTimeout(() => {
                                progressContainer.classList.add('hidden');
                            }, 2000);
                        }
                    };
                    
                    try {
                        // Sıkıştırma işlemi
                        const compressor = new ImageCompressor();
                        const optimizedFiles = await compressor.optimizeImages(fileInput.files, updateProgress);
                        
                        // Dosyaları değiştir
                        const dataTransfer = new DataTransfer();
                        optimizedFiles.forEach(file => {
                            dataTransfer.items.add(file);
                            
                            // Önizlemede sıkıştırma oranını göster
                            if (file.optimized) {
                                const previewItem = document.querySelector(`.image-preview-item[data-file-name="${file.name}"]`);
                                if (previewItem) {
                                    const infoElement = previewItem.querySelector('.text-gray-400');
                                    if (infoElement) {
                                        infoElement.classList.remove('text-gray-400');
                                        infoElement.classList.add('text-green-500');
                                        infoElement.textContent = `${ImageCompressor.formatFileSize(file.size)} (${file.compressionRatio}% azaltıldı)`;
                                    }
                                }
                            }
                        });
                        
                        fileInput.files = dataTransfer.files;
                        
                        return Promise.resolve();
                    } catch (error) {
                        console.error('Sıkıştırma hatası:', error);
                        updateProgress(-1, `Hata: ${error.message}`);
                        return Promise.reject(error);
                    }
                });
            
            try {
                // Tüm sıkıştırma işlemlerinin tamamlanmasını bekle
                await Promise.all(promises);
                
                // Formu normal şekilde gönder
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            } catch (error) {
                console.error('Form gönderim hatası:', error);
                alert('Görsel optimizasyon hatası: ' + error.message);
                
                // Submit butonunu tekrar aktif et
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    });
}); 