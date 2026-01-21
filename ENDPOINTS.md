# API Endpoint Listesi

Aşağıda projenizdeki tüm aktif endpoint'lerin tam URL listesi bulunmaktadır.

| Metot           | Tam URL (Endpoint)                                    | Açıklama                   |
| :-------------- | :---------------------------------------------------- | :------------------------- |
| **AUTH**        |                                                       |                            |
| POST            | `http://127.0.0.1:8000/api/auth/register`             | Yeni Kullanıcı Kaydı       |
| POST            | `http://127.0.0.1:8000/api/auth/login`                | Giriş Yap (Token Al)       |
| POST            | `http://127.0.0.1:8000/api/auth/me`                   | Profil Bilgilerini Gör     |
| POST            | `http://127.0.0.1:8000/api/auth/refresh`              | Token Süresini Uzat        |
| POST            | `http://127.0.0.1:8000/api/auth/logout`               | Çıkış Yap                  |
| **MEKANLAR**    |                                                       |                            |
| GET             | `http://127.0.0.1:8000/api/venues`                    | Tüm Mekanları Listele      |
| POST            | `http://127.0.0.1:8000/api/venues`                    | Yeni Mekan Ekle (Admin)    |
| GET             | `http://127.0.0.1:8000/api/venues/{id}`               | Mekan Detayını Gör         |
| PUT             | `http://127.0.0.1:8000/api/venues/{id}`               | Mekan Güncelle (Admin)     |
| DELETE          | `http://127.0.0.1:8000/api/venues/{id}`               | Mekan Sil (Admin)          |
| GET             | `http://127.0.0.1:8000/api/venues/{id}/seats`         | Mekan Koltuklarını Listele |
| **ETKİNLİKLER** |                                                       |                            |
| GET             | `http://127.0.0.1:8000/api/events`                    | Etkinlikleri Listele       |
| POST            | `http://127.0.0.1:8000/api/events`                    | Etkinlik Ekle (Admin)      |
| GET             | `http://127.0.0.1:8000/api/events/{id}`               | Etkinlik Detayını Gör      |
| PUT             | `http://127.0.0.1:8000/api/events/{id}`               | Etkinlik Güncelle (Admin)  |
| DELETE          | `http://127.0.0.1:8000/api/events/{id}`               | Etkinlik Sil (Admin)       |
| GET             | `http://127.0.0.1:8000/api/events/{id}/seats`         | Etkinlik Koltuk Durumu     |
| **REZERVASYON** |                                                       |                            |
| GET             | `http://127.0.0.1:8000/api/reservations`              | Rezervasyonlarımı Gör      |
| POST            | `http://127.0.0.1:8000/api/reservations`              | Yeni Rezervasyon Yap       |
| GET             | `http://127.0.0.1:8000/api/reservations/{id}`         | Rezervasyon Detayı         |
| POST            | `http://127.0.0.1:8000/api/reservations/{id}/confirm` | Rezervasyon Onayla (Ödeme) |
| DELETE          | `http://127.0.0.1:8000/api/reservations/{id}`         | Rezervasyon İptal Et       |
| **BİLETLER**    |                                                       |                            |
| GET             | `http://127.0.0.1:8000/api/tickets`                   | Biletlerimi Listele        |
| GET             | `http://127.0.0.1:8000/api/tickets/{id}`              | Bilet Detayı               |
| GET             | `http://127.0.0.1:8000/api/tickets/{id}/download`     | Bilet İndir (PDF)          |
