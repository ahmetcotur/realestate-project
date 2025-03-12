// Remax Pupa Emlak - Ana JavaScript Dosyası

// DOM yüklendiğinde çalıştır
document.addEventListener('DOMContentLoaded', function() {
    // Mobil Menü Davranışı
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Sayfa dışına tıklandığında mobil menüyü kapat
        document.addEventListener('click', (event) => {
            const isClickInsideMenu = mobileMenu.contains(event.target);
            const isClickOnButton = mobileMenuButton.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnButton && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Emlak filtreleme fonksiyonu
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            filterProperties();
        });
    }

    // Animasyonlu elementleri etkinleştir
    animateOnScroll();
    
    // Form gönderim işlemi
    setupForms();
    
    // Görsel galerisi için basit lightbox
    setupLightbox();
    
    // Ana sayfa emlak slaytlarını başlat
    initPropertySlider();
    
    // Aktif navigasyon linkini işaretle
    highlightActiveNavLink();
    
    // Görseller için lazy loading uygula
    setupLazyLoading();
    
    // Hata mesajı gösterimi
    showErrorMessages();
});

// Emlakları filtrele
function filterProperties() {
    const propertyType = document.getElementById('property-type')?.value;
    const location = document.getElementById('location')?.value;
    const priceRange = document.getElementById('price-range')?.value;
    const rooms = document.getElementById('rooms')?.value;
    
    console.log('Filtreleme:', { propertyType, location, priceRange, rooms });
    
    // Gerçek uygulamada bu değerlerle AJAX isteği gönderilir veya mevcut DOM elementleri filtrelenir
    // Şimdilik sadece konsola yazdırıyoruz
    
    // Kullanıcıya işlem bildirimi
    alert('Filtreleme işlemi başarılı! Sonuçlar yükleniyor...');
    
    // Gerçek uygulamada burada AJAX çağrısı olur
    // fetchFilteredProperties(propertyType, location, priceRange, rooms);
}

// Scroll'a bağlı animasyonlar
function animateOnScroll() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animatedElements.length === 0) return;
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Form işlemleri
function setupForms() {
    // Tüm formları seç
    const forms = document.querySelectorAll('form');
    
    // Her bir form için gönderim olayını dinle
    forms.forEach(form => {
        if (form.id === 'filter-form') return; // Filtreleme formu için farklı işlem var
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this);
        });
        
        // Input alanlarını iyileştir
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            // Odaklama efekti
            input.addEventListener('focus', () => {
                input.classList.add('border-blue-600');
                input.classList.add('ring-2');
                input.classList.add('ring-blue-100');
            });
            
            input.addEventListener('blur', () => {
                input.classList.remove('border-blue-600');
                input.classList.remove('ring-2');
                input.classList.remove('ring-blue-100');
            });
        });
    });
}

// Form gönderimi
function submitForm(form) {
    // Form verilerini al
    const formData = new FormData(form);
    const formValues = {};
    
    for (let [key, value] of formData.entries()) {
        formValues[key] = value;
    }
    
    console.log('Form Verileri:', formValues);
    
    // Gerçek uygulamada burada AJAX ile form gönderimi yapılır
    
    // Başarı mesajını göster
    const formContainer = form.parentElement;
    const successMessage = document.createElement('div');
    successMessage.className = 'bg-green-50 text-green-800 p-4 rounded my-4';
    successMessage.innerHTML = `
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p>Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.</p>
        </div>
    `;
    
    // Formu gizle ve başarı mesajını göster
    form.style.display = 'none';
    formContainer.appendChild(successMessage);
    
    // 3 saniye sonra formu sıfırla ve yeniden göster
    setTimeout(() => {
        form.reset();
        form.style.display = 'block';
        formContainer.removeChild(successMessage);
    }, 3000);
}

// Emlak detay sayfasındaki görsel galerisi için lightbox
function setupLightbox() {
    const galleryImages = document.querySelectorAll('.gallery-image');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const closeLightbox = document.getElementById('close-lightbox');
    
    if (!galleryImages.length || !lightbox || !lightboxImage || !closeLightbox) return;
    
    galleryImages.forEach(image => {
        image.addEventListener('click', () => {
            const imgSrc = image.getAttribute('src');
            lightboxImage.setAttribute('src', imgSrc);
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });
    
    closeLightbox.addEventListener('click', () => {
        lightbox.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });
    
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
}

// Emlak fiyat hesaplayıcı (örnek)
function calculateMortgage(price, downPayment, years, interestRate) {
    const principal = price - downPayment;
    const monthlyRate = interestRate / 100 / 12;
    const numberOfPayments = years * 12;
    
    const monthlyPayment = principal * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / 
                          (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
    
    return monthlyPayment.toFixed(2);
}

// Ana sayfadaki emlak slaytları için basit slider
function initPropertySlider() {
    const slider = document.querySelector('.property-slider');
    const slides = document.querySelectorAll('.property-slide');
    const prevButton = document.querySelector('.slider-prev');
    const nextButton = document.querySelector('.slider-next');
    
    if (!slider || !slides.length || !prevButton || !nextButton) return;
    
    let currentSlide = 0;
    const slideCount = slides.length;
    
    // Slaytları güncelle
    function updateSlides() {
        slides.forEach((slide, index) => {
            slide.style.transform = `translateX(${100 * (index - currentSlide)}%)`;
            
            // Aktif olmayan slaytları biraz saydam yap
            if (index === currentSlide) {
                slide.style.opacity = 1;
                slide.style.scale = 1;
            } else {
                slide.style.opacity = 0.7;
                slide.style.scale = 0.95;
            }
        });
    }
    
    // İlk durumu ayarla
    updateSlides();
    
    // Önceki slayta git
    prevButton.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + slideCount) % slideCount;
        updateSlides();
    });
    
    // Sonraki slayta git
    nextButton.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % slideCount;
        updateSlides();
    });
    
    // Otomatik geçiş
    let autoSlideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slideCount;
        updateSlides();
    }, 5000);
    
    // Mouse üzerindeyken otomatik geçişi durdur
    slider.addEventListener('mouseenter', () => {
        clearInterval(autoSlideInterval);
    });
    
    // Mouse ayrıldığında otomatik geçişi tekrar başlat
    slider.addEventListener('mouseleave', () => {
        autoSlideInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % slideCount;
            updateSlides();
        }, 5000);
    });
    
    // Dokunma hareketleri için destek (mobil cihazlar)
    let touchStartX = 0;
    let touchEndX = 0;
    
    slider.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, {passive: true});
    
    slider.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, {passive: true});
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) < swipeThreshold) return;
        
        if (diff > 0) {
            // Sola kaydırma - sonraki slayt
            currentSlide = (currentSlide + 1) % slideCount;
        } else {
            // Sağa kaydırma - önceki slayt
            currentSlide = (currentSlide - 1 + slideCount) % slideCount;
        }
        
        updateSlides();
    }
}

// Aktif sayfanın navigasyon linkini işaretle
function highlightActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    
    // Desktop ve mobil menüdeki tüm navigasyon linklerini seç
    const navLinks = document.querySelectorAll('header nav a, #mobile-menu a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        
        if (href === currentPage) {
            link.classList.add('text-blue-600');
            link.classList.add('font-medium');
        } else {
            // Ana sayfa durumu için özel durum
            if (currentPage === 'index.html' && href === 'index.html') {
                link.classList.add('text-blue-600');
                link.classList.add('font-medium');
            }
        }
    });
}

// Lazy loading için fonksiyon
function setupLazyLoading() {
    // Lazy load edilecek tüm görselleri seç
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    if (lazyImages.length === 0) {
        // data-src özniteliği bulunamazsa normal görsellere lazy loading ekle
        const allImages = document.querySelectorAll('img:not([loading])');
        allImages.forEach(img => {
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
                
                // Yükleme animasyonu ekle
                img.style.transition = 'opacity 0.3s';
                img.style.opacity = '0';
                
                img.onload = function() {
                    img.style.opacity = '1';
                }
            }
        });
        return;
    }
    
    // IntersectionObserver desteği varsa kullan
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src');
                    
                    if (src) {
                        img.setAttribute('src', src);
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                        
                        // Yüklendiğinde fade-in efekti
                        img.style.opacity = '1';
                    }
                }
            });
        }, { rootMargin: '50px 0px' });
        
        lazyImages.forEach(img => {
            // Yükleme durumunda görsel stili
            img.style.transition = 'opacity 0.3s';
            img.style.opacity = '0';
            
            imageObserver.observe(img);
        });
    } else {
        // Fallback - sayfa yüklendiğinde tüm görselleri yükle
        lazyImages.forEach(img => {
            const src = img.getAttribute('data-src');
            if (src) {
                img.setAttribute('src', src);
                img.removeAttribute('data-src');
            }
        });
    }
}

// Görsel önbellekleme
function preloadImages(imageArray) {
    if (!imageArray || !imageArray.length) return;
    
    const preloadedImages = [];
    
    imageArray.forEach(src => {
        const img = new Image();
        img.src = src;
        preloadedImages.push(img);
    });
    
    return preloadedImages;
}

// Hata mesajlarını göster
function showErrorMessages() {
    const errorContainers = document.querySelectorAll('.error-container');
    
    errorContainers.forEach(container => {
        const errorMessages = container.querySelectorAll('.error-message');
        
        if (errorMessages.length) {
            container.classList.remove('hidden');
            
            // Birkaç saniye sonra otomatik kapanma
            setTimeout(() => {
                container.classList.add('hidden');
            }, 5000);
            
            // Kapatma butonu ekle
            const closeButton = document.createElement('button');
            closeButton.innerHTML = '&times;';
            closeButton.className = 'absolute top-2 right-2 text-gray-500 hover:text-gray-700';
            closeButton.addEventListener('click', () => {
                container.classList.add('hidden');
            });
            
            container.appendChild(closeButton);
        }
    });
}
