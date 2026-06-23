# Luwungragi

Sistem Informasi Penyewaan Kostum dan Kebaya Luwungragi berbasis Laravel 12. Project ini dibuat menyesuaikan instruksi pada `intruksi1.json` dengan fokus pada katalog busana, booking, pembayaran, pengembalian, dashboard admin, dan monitoring owner.

## Stack

- Laravel 12
- Eloquent ORM dengan relasi terstruktur untuk `users`, `costumes`, `rentals`, `rental_details`, `payments`, dan `returns`
- JWT custom service + middleware untuk login berbasis token
- Blade Component-Based UI
- Bootstrap 5 + custom CSS untuk tampilan responsif

## Fitur Utama

- Katalog kostum dan kebaya dengan filter pencarian, kategori, dan cek ketersediaan real-time berdasarkan tanggal sewa.
- Booking penyewaan oleh customer dengan pilihan pembayaran `Midtrans`, `Manual Transfer`, dan `On Site`.
- Dashboard penyewa untuk memantau status transaksi dan melihat e-invoice.
- Dashboard admin untuk mengelola katalog, pelanggan, transaksi, verifikasi pembayaran, dan proses pengembalian plus denda.
- Dashboard owner untuk melihat ringkasan performa usaha, tren pendapatan, metode pembayaran, dan top item.
- Halaman laporan bulanan yang siap dicetak.

## Role Demo

Setelah seeding, akun berikut tersedia:

- Admin: `admin@luwungragi.test` / `password123`
- Owner: `owner@luwungragi.test` / `password123`
- Customer: `ratri@luwungragi.test` / `password123`

## Struktur Penting

- `app/Services/JwtService.php`: generator dan validator token JWT.
- `app/Http/Middleware/*Jwt*.php`: middleware autentikasi dan guest redirect berbasis JWT.
- `app/Services/AvailabilityService.php`: kalkulasi stok tersedia sesuai rentang tanggal sewa.
- `app/Services/RentalWorkflowService.php`: booking, update pembayaran, dan proses pengembalian.
- `app/Services/ReportService.php`: agregasi data dashboard admin, owner, dan laporan bulanan.
- `resources/views/components`: Blade components reusable.

## Menjalankan Project

1. Install dependency:
   `composer install`
2. Salin env:
   `cp .env.example .env`
3. Generate key:
   `php artisan key:generate`
4. Migrasi dan seed:
   `php artisan migrate:fresh --seed`
5. Jalankan server:
   `php artisan serve`

## Template Env

Template env sekarang dipisah supaya lebih jelas:

- `.env.example.local`: untuk testing lokal via Docker dengan database native host.
- `.env.example.vps`: untuk deploy VPS production.
- `.env.example`: alias template VPS agar alur lama `cp .env.example .env` tetap aman dipakai di server.

## Test Docker Lokal dengan MySQL Native

Untuk test lokal, container Docker hanya dipakai untuk app Laravel dengan Nginx + PHP-FPM 8.4. Database tetap memakai MySQL/MariaDB native yang jalan di host.

Yang sudah disiapkan di repo:

- `docker-compose.yml`: konfigurasi dasar app.
- `docker-compose.local.yml`: override lokal untuk koneksi ke MySQL host tanpa mengubah compose utama VPS.

Langkah yang disarankan:

1. Pastikan service MySQL/MariaDB native sedang berjalan di host lokal.
2. Pastikan database dan kredensial pada `.env` memang valid untuk database lokal Anda.
3. Pastikan MySQL menerima koneksi TCP dari container. Jika server Anda hanya listen ke Unix socket atau `127.0.0.1`, container tidak akan bisa masuk sampai bind address dibuka ke alamat host yang bisa dijangkau Docker.
4. Jalankan app container:
   `docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build`
5. Pantau startup:
   `docker compose -f docker-compose.yml -f docker-compose.local.yml logs -f app`
6. Akses aplikasi di:
   `http://localhost:8080`

Kalau host database Anda tidak bisa diakses lewat `host.docker.internal`, Anda bisa override host dan port tanpa mengubah kredensial utama aplikasi:

- `DOCKER_DB_HOST=192.168.1.10 docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build`
- `DOCKER_DB_PORT=3307 docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build`

Catatan lokal:

- Override lokal hanya mengganti `DB_HOST` dan `DB_PORT` di dalam container. Nilai `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` tetap mengikuti `.env`.
- Network Docker lokal sekarang tidak wajib external network manual. Compose akan membuat network sendiri kecuali Anda set `DOCKER_USE_EXTERNAL_NETWORK=true`.
- Jika `RUN_MIGRATIONS=true`, container akan mencoba migrasi saat startup. Jadi MySQL host harus siap menerima koneksi lebih dulu.

## Deploy Docker di VPS

Project ini sekarang sudah disiapkan untuk alur deploy berbasis Docker di VPS. Setup yang ditambahkan:

- `Dockerfile`: build production image Laravel dengan Nginx + PHP-FPM 8.4 + asset Vite.
- `docker-compose.yml`: jalankan container app dengan volume persisten untuk `storage`.
- `docker-compose.local.yml`: override lokal khusus untuk test di laptop/development host.
- `docker/.env.vps.example`: template env production untuk VPS.
- `docker/entrypoint.sh`: auto `storage:link`, cache optimasi, dan migrasi opsional saat startup.

Alur deploy yang disarankan:

1. Clone atau pull repo di VPS.
2. Buat external Docker network sekali saja:
   `docker network create web_network`
3. Salin template env:
   `cp docker/.env.vps.example .env`
4. Isi nilai penting di `.env`:
   - `APP_URL`
   - `APP_KEY`
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - kredensial Midtrans production
   - pastikan mode Midtrans cocok dengan key:
     `MIDTRANS_IS_PRODUCTION=true` untuk `Mid-server-...` / `Mid-client-...`
     `MIDTRANS_IS_PRODUCTION=false` untuk `SB-Mid-server-...` / `SB-Mid-client-...`
5. Generate `APP_KEY`, lalu simpan ke `.env` dengan format `APP_KEY=base64:...`.
   Contoh generate:
   `openssl rand -base64 32`
6. Jalankan container:
   `docker compose up -d --build`
7. Cek status deploy:
   - `docker compose ps`
   - `docker compose logs -f app`

Catatan deploy:

- Compose ini mengasumsikan database MySQL sudah ada, baik container terpisah maupun database external. Service database tidak saya masukkan agar setup VPS tetap ringan dan fleksibel.
- Source code tidak di-bind mount ke container. Jadi alur deploy-nya cukup `git pull` lalu `docker compose up -d --build`.
- Data upload, session file, view cache, dan log Laravel tetap aman saat redeploy karena `storage` memakai named volume.
- Port host default adalah `8080`. Kalau mau ganti, ubah `APP_PORT` di `.env`.
- Network Docker default adalah `web_network`. Kalau di VPS kamu namanya beda, ubah `DOCKER_WEB_NETWORK`.
- Untuk memakai external network yang sudah dibuat reverse proxy, set `DOCKER_USE_EXTERNAL_NETWORK=true`.
- `RUN_MIGRATIONS=true` akan otomatis menjalankan `php artisan migrate --force` saat container start.

## Environment

Tambahan env yang sudah disiapkan:

- `JWT_COOKIE_NAME`
- `JWT_TTL`
- `MIDTRANS_MERCHANT_ID`
- `MIDTRANS_CLIENT_KEY`
- `MIDTRANS_SERVER_KEY`
- `MIDTRANS_IS_PRODUCTION`

Catatan deploy gambar:

- Gambar katalog disimpan di storage lokal Laravel, tepatnya disk `public`.
- Saat container start, entrypoint akan selalu menjalankan `php artisan storage:link --force` agar symlink `public/storage` tetap benar setelah deploy atau rebuild.
- Selama Docker named volume `storage` tetap dipakai, file upload yang dilakukan di VPS akan tetap ada saat redeploy.

Catatan: integrasi Midtrans saat ini masih berupa placeholder token/transaksi agar alur bisnis tetap bisa didemokan tanpa SDK eksternal. Konfigurasi `config/services.php` sudah disiapkan untuk penyambungan live berikutnya.

## Verifikasi

Yang berhasil diverifikasi di environment saat ini:

- `php artisan route:list`
- `php artisan view:cache`
- `find app config database routes tests -name '*.php' -print0 | xargs -0 -n 1 php -l`

Yang belum bisa dituntaskan penuh di environment ini:

- `php artisan test`
- request yang menyentuh database saat runtime

Penyebabnya, extension PHP untuk database belum aktif di mesin ini. Output `php -m` menunjukkan `PDO` ada, tetapi driver seperti `pdo_sqlite` atau `pdo_mysql` belum tersedia. Setelah driver database diaktifkan, jalankan ulang:

- `php artisan migrate:fresh --seed`
- `php artisan test`

## Catatan Pengembangan Lanjut

- Sambungkan `RentalWorkflowService` ke Midtrans API live.
- Tambahkan service worker terpisah kalau nanti job async mulai aktif dan antrean tidak lagi cukup ditangani secara sinkron.
- Tambahkan notifikasi email/WhatsApp untuk status pesanan.
- Tambahkan pagination dan export PDF/Excel untuk laporan.
# luwungragi_kebaya
# luwungragi_kebaya
# luwungragi_kebaya
# luwungragi_kebaya
