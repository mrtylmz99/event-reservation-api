# Event Reservation API

Etkinlik biletleme ve rezervasyon sistemi için geliştirilmiş REST API.

## Özellikler

- **Etkinlik Yönetimi:** Etkinlik oluşturma, listeleme ve detay görüntüleme.
- **Mekan ve Koltuk Yönetimi:** Mekan tanımlama ve koltuk düzeni.
- **Rezervasyon Sistemi:** 15 dakika süreli koltuk rezervasyonu.
- **Biletleme:** Rezervasyon onayı sonrası QR kodlu (simüle edilmiş) bilet oluşturma ve PDF indirme.
- **Güvenlik:** JWT tabanlı kimlik doğrulama.
- **Docker:** MySQL veritabanı için Docker yapılandırması.

## Kurulum

1.  **Gereksinimler:**
    - PHP 8.1+
    - Composer
    - Docker & Docker Compose

2.  **Projeyi Klonlayın:**

    ```bash
    git clone <repository-url>
    cd event_application
    ```

3.  **Bağımlılıkları Yükleyin:**

    ```bash
    composer install
    ```

4.  **Çevresel Değişkenleri Ayarlayın:**

    ```bash
    cp .env.example .env
    ```

    `.env` dosyasını Docker yapılandırmanıza göre düzenleyin (varsayılanlar `docker-compose.yml` ile uyumludur).

5.  **Docker Konteynerlerini Başlatın:**

    ```bash
    ./vendor/bin/sail up -d
    # Veya sail kullanmıyorsanız
    docker-compose up -d
    ```

6.  **Uygulama Anahtarını Oluşturun:**

    ```bash
    php artisan key:generate
    ```

7.  **JWT Secret Oluşturun:**

    ```bash
    php artisan jwt:secret
    ```

8.  **Veritabanı Migrasyonlarını Çalıştırın:**

    ```bash
    php artisan migrate
    ```

9.  **Zamanlayıcıyı Başlatın (Opsiyonel - Rezervasyon İptali İçin):**
    ```bash
    php artisan schedule:work
    ```

## API Endpoint'leri

### Auth

- `POST /api/auth/register` - Kayıt Ol
- `POST /api/auth/login` - Giriş Yap
- `POST /api/auth/logout` - Çıkış Yap
- `POST /api/auth/refresh` - Token Yenile
- `POST /api/auth/me` - Profil Bilgisi

### Venues & Seats

- `GET /api/venues` - Mekanları Listele
- `GET /api/venues/{id}` - Mekan Detayı
- `GET /api/venues/{id}/seats` - Mekan Koltukları
- `POST /api/venues` - Mekan Ekle (Admin)
- `POST /api/seats/block` - Koltuk Blokla
- `DELETE /api/seats/release` - Koltuk Serbest Bırak

### Events

- `GET /api/events` - Etkinlikleri Listele
- `GET /api/events/{id}` - Etkinlik Detayı
- `GET /api/events/{id}/seats` - Etkinlik Koltuk Durumu (Müsaitlik)
- `POST /api/events` - Etkinlik Ekle (Admin)

### Reservations

- `GET /api/reservations` - Rezervasyonlarım
- `POST /api/reservations` - Rezervasyon Yap
- `GET /api/reservations/{id}` - Rezervasyon Detayı
- `POST /api/reservations/{id}/confirm` - Rezervasyonu Onayla (Bilet Oluştur)
- `DELETE /api/reservations/{id}` - Rezervasyon İptal

### Tickets

- `GET /api/tickets` - Biletlerim
- `GET /api/tickets/{id}` - Bilet Detayı
- `GET /api/tickets/{id}/download` - Bilet İndir (PDF)

## Lisans

Bu proje açık kaynaklıdır.
