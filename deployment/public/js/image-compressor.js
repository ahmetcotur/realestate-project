/**
 * Remax Pupa Emlak - Görsel Sıkıştırma Modülü
 * Bu modül, dosya yükleme sınırlarını aşmamak için görsel dosyalarını sıkıştırır
 */

class ImageCompressor {
    constructor(options = {}) {
        this.maxFileSize = options.maxFileSize || 2 * 1024 * 1024; // 2MB varsayılan
        this.maxWidth = options.maxWidth || 1920; // Maksimum genişlik
        this.initialQuality = options.initialQuality || 0.9; // Başlangıç kalitesi
        this.minQuality = options.minQuality || 0.5; // Minimum kalite sınırı
        this.qualityStep = options.qualityStep || 0.05; // Kalite düşürme adımı
    }

    /**
     * Görsel dosyasını optimize eder
     * @param {File} file - Optimize edilecek görsel dosyası
     * @param {Function} progressCallback - İlerleme durumunu bildiren callback
     * @returns {Promise<File>} - Optimize edilmiş dosya
     */
    async optimizeImage(file, progressCallback = null) {
        // Dosya bir görsel değilse veya zaten yeterince küçükse değişiklik yapma
        if (!file.type.match('image.*') || file.size <= this.maxFileSize) {
            return file;
        }

        if (progressCallback) {
            progressCallback(0, 'Görsel işleniyor...');
        }

        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = async (e) => {
                try {
                    const img = new Image();
                    img.onload = async () => {
                        // Canvas oluştur
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        
                        let width = img.width;
                        let height = img.height;
                        
                        // Eğer genişlik maksimum genişlikten büyükse, orantılı olarak küçült
                        if (width > this.maxWidth) {
                            const ratio = this.maxWidth / width;
                            width = this.maxWidth;
                            height = height * ratio;
                        }
                        
                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);
                        
                        // Sıkıştırma işlemi - kaliteyi kademeli olarak düşür
                        let quality = this.initialQuality;
                        let fileBlob;
                        let fileSize;
                        
                        if (progressCallback) {
                            progressCallback(30, 'Görsel sıkıştırılıyor...');
                        }
                        
                        do {
                            fileBlob = await new Promise(resolve => {
                                canvas.toBlob(resolve, file.type, quality);
                            });
                            fileSize = fileBlob.size;
                            quality -= this.qualityStep; // Her adımda kaliteyi düşür
                            
                            if (quality < this.minQuality) break; // Minimum kalite sınırı
                        } while (fileSize > this.maxFileSize);
                        
                        if (progressCallback) {
                            progressCallback(90, 'Görsel hazırlanıyor...');
                        }
                        
                        // Yeni dosya oluştur
                        const newFile = new File([fileBlob], file.name, {
                            type: file.type,
                            lastModified: new Date().getTime()
                        });
                        
                        // Optimize bilgisini ekle
                        newFile.optimized = true;
                        newFile.originalSize = file.size;
                        newFile.compressionRatio = Math.round((file.size - newFile.size) / file.size * 100);
                        
                        if (progressCallback) {
                            progressCallback(100, 'Tamamlandı!');
                        }
                        
                        resolve(newFile);
                    };
                    
                    img.onerror = () => {
                        reject(new Error(`Görsel yüklenirken hata oluştu: ${file.name}`));
                    };
                    
                    img.src = e.target.result;
                } catch (error) {
                    reject(error);
                }
            };
            reader.onerror = () => reject(new Error(`Dosya okunamadı: ${file.name}`));
            reader.readAsDataURL(file);
        });
    }

    /**
     * Birden fazla görseli optimize eder
     * @param {FileList|Array} files - Optimize edilecek görsel dosyaları
     * @param {Function} progressCallback - İlerleme durumunu bildiren callback
     * @returns {Promise<Array<File>>} - Optimize edilmiş dosyalar
     */
    async optimizeImages(files, progressCallback = null) {
        const optimizedFiles = [];
        const totalFiles = files.length;
        
        for (let i = 0; i < totalFiles; i++) {
            const file = files[i];
            const fileProgress = (progress, message) => {
                if (progressCallback) {
                    // Toplam ilerleme = (tamamlanan dosyalar / toplam dosya sayısı) + (mevcut dosyanın ilerlemesi / toplam dosya sayısı)
                    const totalProgress = Math.round((i / totalFiles * 100) + (progress / totalFiles));
                    progressCallback(totalProgress, `${file.name}: ${message} (${i+1}/${totalFiles})`);
                }
            };
            
            const optimizedFile = await this.optimizeImage(file, fileProgress);
            optimizedFiles.push(optimizedFile);
        }
        
        return optimizedFiles;
    }

    /**
     * Dosya boyutunu formatlar
     * @param {Number} size - Bayt cinsinden dosya boyutu
     * @returns {String} - Formatlanmış dosya boyutu
     */
    static formatFileSize(size) {
        if (size < 1024) return size + ' B';
        if (size < 1024 * 1024) return (size / 1024).toFixed(1) + ' KB';
        return (size / (1024 * 1024)).toFixed(1) + ' MB';
    }

    /**
     * Form gönderimi öncesinde dosyaları optimize etmek için kullanılır
     * @param {HTMLFormElement} form - Dosya yükleme formunun elementi
     * @param {String} fileInputSelector - Dosya input elementinin seçicisi
     * @param {Function} progressCallback - İlerleme durumunu bildiren callback
     * @returns {Promise<void>}
     */
    static async handleFormSubmit(form, fileInputSelector, progressCallback = null) {
        // Form gönderimi engelle
        form.addEventListener('submit', async function(e) {
            const fileInput = form.querySelector(fileInputSelector);
            
            // Dosya yoksa normal devam et
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                return true;
            }
            
            // Dosya varsa gönderimi engelle ve işlemlere başla
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> İşleniyor...';
            }
            
            try {
                // Yeni bir compressor oluştur
                const compressor = new ImageCompressor();
                
                // Dosyaları optimize et
                const optimizedFiles = await compressor.optimizeImages(fileInput.files, progressCallback);
                
                // Orijinal input'u değiştirmek için bir DataTransfer nesnesi oluştur
                const dataTransfer = new DataTransfer();
                
                // Optimize edilmiş dosyaları ekle
                optimizedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                
                // FileList'i değiştir
                fileInput.files = dataTransfer.files;
                
                // İşlem tamamlandı, formu gönder
                if (progressCallback) {
                    progressCallback(100, 'Yükleme başlatılıyor...');
                }
                
                // Formu gönder
                form.submit();
                
            } catch (error) {
                console.error('Optimizasyon hatası:', error);
                
                if (progressCallback) {
                    progressCallback(-1, `Hata: ${error.message}`);
                }
                
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
                
                alert('Görsel optimizasyon hatası: ' + error.message);
            }
        });
    }
}

// Global olarak erişilebilir yap
window.ImageCompressor = ImageCompressor; 