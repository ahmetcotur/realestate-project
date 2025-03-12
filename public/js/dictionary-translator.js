/**
 * Dictionary-Translator.js
 * Sadece Türkçe dil desteği
 */

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
  // Dil butonlarını ayarla - sadece görsel olarak göster, fonksiyonel olmasın
  setupLanguageButtons();
  
  // Dil tercihini sıfırla (her zaman Türkçe)
  resetLanguagePreference();
});

// Dil butonlarını ayarla
function setupLanguageButtons() {
  const languageButtons = document.querySelectorAll('[data-language]');
  
  languageButtons.forEach(button => {
    const lang = button.getAttribute('data-language');
    
    // TR butonunu aktif, EN butonunu pasif göster
    if (lang === 'tr') {
      activateButton(button);
    } else {
      deactivateButton(button);
    }
    
    // Tüm butonlara tıklandığında varsayılan davranışı engelle
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      // TR butonuna tıklanırsa sayfayı yenile
      if (lang === 'tr') {
        window.location.reload();
      } else {
        // Diğer butonlara tıklanırsa bilgi mesajı göster
        showLanguageNotice();
      }
    });
  });
}

// TR butonunu aktif göster
function activateButton(button) {
  button.classList.add('active-language', 'font-bold');
  
  // Mobil veya masaüstü menü stiline göre farklı sınıflar ekle
  if (button.closest('.py-4')) {
    // Mobil menü için
    button.classList.add('text-blue-300');
  } else {
    // Masaüstü menü için
    button.classList.add('text-blue-600');
  }
}

// TR olmayan butonları pasif göster
function deactivateButton(button) {
  button.classList.remove('active-language', 'font-bold', 'text-blue-600', 'text-blue-300');
  
  // Pasif butona özel stil
  button.style.opacity = '0.5';
  button.style.cursor = 'not-allowed';
  
  // Title özniteliğine açıklama ekle
  button.setAttribute('title', 'Bu site sadece Türkçe dilini desteklemektedir.');
}

// Dil tercihini sıfırla (her zaman Türkçe)
function resetLanguagePreference() {
  // Dil tercihini localStorage'da Türkçe olarak ayarla
  localStorage.setItem('preferred_language', 'tr');
  
  // HTML lang özniteliğini güncelle
  document.documentElement.lang = 'tr';
}

// Dil değiştirme bilgi mesajı göster
function showLanguageNotice() {
  // Eğer zaten bir bilgi mesajı gösteriliyorsa tekrar gösterme
  if (document.getElementById('language-notice')) {
    return;
  }
  
  // Bilgi mesajı oluştur
  const notice = document.createElement('div');
  notice.id = 'language-notice';
  notice.innerHTML = 'Bu site sadece Türkçe dilini desteklemektedir.';
  notice.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #3b82f6;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    font-size: 14px;
  `;
  
  // Bilgi mesajını sayfaya ekle
  document.body.appendChild(notice);
  
  // 3 saniye sonra bilgi mesajını kaldır
  setTimeout(function() {
    notice.style.opacity = '0';
    notice.style.transition = 'opacity 0.5s ease';
    
    setTimeout(function() {
      if (notice.parentNode) {
        notice.parentNode.removeChild(notice);
      }
    }, 500);
  }, 3000);
} 