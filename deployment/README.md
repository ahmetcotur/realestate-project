# Remax Pupa Emlak - cPanel Kurulum Kılavuzu

Bu belge, Remax Pupa Emlak projesi için cPanel sunucusuna kurulum adımlarını içermektedir.

## Gereksinimler

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- Composer
- Node.js & npm (opsiyonel, frontend derleme için)
- cPanel erişimi
- SSH erişimi (önerilir, ama zorunlu değil)

## 1. Dosyaların Yüklenmesi

### SSH ile Kurulum (Önerilen)

1. Proje dosyalarını sunucunuzun public_html veya ilgili alt klasörüne yükleyin:
   ```
   scp -r remax-pupa-emlak.zip kullanici@sunucu.com:~/public_html/
   ssh kullanici@sunucu.com
   cd ~/public_html
   unzip remax-pupa-emlak.zip
   ```

### FTP ile Kurulum

1. FileZilla veya benzeri bir FTP istemcisi kullanarak tüm dosyaları public_html veya ilgili alt klasöre yükleyin.

## 2. Veritabanı Kurulumu

1. cPanel üzerinden "MySQL Veritabanları" bölümüne girin
2. Yeni bir veritabanı oluşturun (ör: `kullanici_remaxdb`)
3. Yeni bir veritabanı kullanıcısı oluşturun ve tüm izinleri verin
4. Veritabanı bilgilerini not edin

## 3. .env Dosyasının Yapılandırılması

`.env.example` dosyasını `.env` olarak kopyalayın ve aşağıdaki bilgileri güncelleyin:

```
APP_NAME="Remax Pupa Emlak"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=kullanici_remaxdb
DB_USERNAME=kullanici_remaxuser
DB_PASSWORD=sifreniz

MAIL_MAILER=smtp
MAIL_HOST=mail.domain.com
MAIL_PORT=587
MAIL_USERNAME=info@domain.com
MAIL_PASSWORD=mail_sifreniz
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 4. Kurulum Komutları

SSH erişiminiz varsa aşağıdaki komutları çalıştırın:

```bash
# Uygulama anahtarı oluşturma
php artisan key:generate

# Veritabanı tablolarını oluşturma
php artisan migrate

# Örnek verileri yükleme
php artisan db:seed

# Storage linkini oluşturma
php artisan storage:link

# Önbelleği temizleme
php artisan optimize:clear
```

SSH erişiminiz yoksa, cPanel'deki "Terminal" aracını kullanabilirsiniz.

## 5. Dizin İzinleri

Aşağıdaki klasörlerin yazma izinlerine sahip olduğundan emin olun:

```
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 6. Web Sunucusu Yapılandırması

### Apache için .htaccess (çoğu cPanel sunucusunda Apache kullanılır)

public/.htaccess dosyası zaten mevcuttur. Eğer domain.com/public şeklinde çalışıyorsa, ana dizine aşağıdaki .htaccess dosyasını ekleyin:

```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## 7. Dummy Verilerin Yüklenmesi

Proje, örnek verilerle birlikte gelir. Verileri yüklemek için:

```
php artisan db:seed
```

## 8. Olası Sorunlar ve Çözümleri

1. **500 Internal Server Error**: storage ve bootstrap/cache klasörlerinin izinlerini kontrol edin.
2. **Beyaz Ekran**: APP_DEBUG=true yaparak hata mesajlarını görüntüleyin.
3. **Resimler Görünmüyor**: `php artisan storage:link` komutunu çalıştırdığınızdan emin olun.
4. **Yetki Hatası**: .env dosyasındaki veritabanı bilgilerini kontrol edin.

## 9. Güvenlik Önlemleri

- APP_DEBUG=false olduğundan emin olun
- .env dosyasının web'den erişilemediğini kontrol edin
- Laravel sürümünüzü düzenli olarak güncelleyin
- Güçlü şifreler kullanın

## 10. Yardım ve Destek

Teknik destek için: [destek@domain.com](mailto:destek@domain.com) 