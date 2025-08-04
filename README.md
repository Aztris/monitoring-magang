# Sistem Monitoring Magang

Aplikasi web berbasis Laravel 10 untuk memonitoring kegiatan magang siswa.

---

## Panduan Instalasi Proyek

Berikut adalah langkah-langkah detail untuk menginstal dan menjalankan proyek ini di lingkungan pengembangan lokal.

### 1. Prasyarat (Prerequisites)

Pastikan perangkat Anda sudah terinstal perangkat lunak berikut:
- **Server Lokal**: [XAMPP](https://www.apachefriends.org/index.html) atau [Laragon](https://laragon.org/download/) (Rekomendasi: Laragon).
- **PHP**: Versi 8.1 atau yang lebih baru.
- **Composer**: [Download & Install Composer](https://getcomposer.org/download/).
- **Git**: [Download & Install Git](https://git-scm.com/downloads).
- **Node.js & NPM**: [Download & Install Node.js](https://nodejs.org/en) (NPM sudah termasuk).

### 2. Clone Proyek dari GitHub

Buka terminal atau Git Bash, masuk ke direktori server lokal Anda (misal: `C:\laragon\www` atau `C:\xampp\htdocs`), lalu jalankan perintah berikut:

```bash
git clone https://github.com/Aztris/monitoring-magang/tree/revisi/juli25 monitoring-magang
```

Masuk ke dalam direktori proyek yang baru saja di-clone:
```bash
cd monitoring-magang
```

### 3. Konfigurasi Lingkungan

Salin file `.env.example` menjadi file `.env`. File ini berisi semua konfigurasi lingkungan untuk proyek Anda.

```bash
copy .env.example .env
```
*(Untuk pengguna Windows. Jika menggunakan Linux/MacOS, gunakan `cp .env.example .env`)*

Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monitoring_magang
DB_USERNAME=root
DB_PASSWORD=
```
- **`DB_DATABASE`**: Nama database yang akan Anda gunakan.
- **`DB_USERNAME`**: Username database Anda (default `root` untuk XAMPP/Laragon).
- **`DB_PASSWORD`**: Password database Anda (default kosong untuk XAMPP/Laragon).

### 4. Instalasi Dependensi

Instal semua dependensi PHP yang dibutuhkan oleh Laravel melalui Composer.

```bash
composer install
```

Instal semua dependensi JavaScript yang dibutuhkan melalui NPM.
```bash
npm install
```

### 5. Generate Kunci Aplikasi

Setiap aplikasi Laravel membutuhkan kunci enkripsi yang unik. Generate kunci baru dengan perintah Artisan berikut:

```bash
php artisan key:generate
```

### 6. Persiapan Database

1.  **Buat Database**: Buka aplikasi manajemen database Anda (misal: phpMyAdmin atau HeidiSQL) dan buat database baru dengan nama yang sama seperti yang Anda atur di file `.env` (contoh: `monitoring_magang`).

2.  **Jalankan Migrasi**: Perintah ini akan membuat semua tabel yang dibutuhkan oleh aplikasi di dalam database Anda.
    ```bash
    php artisan migrate
    ```

3.  **Jalankan Seeder (Membuat Akun Admin Awal)**: Perintah ini akan mengisi database dengan data awal, termasuk akun admin utama.
    ```bash
    php artisan db:seed
    ```
    Jika Anda hanya ingin menjalankan seeder untuk admin, gunakan perintah:
    ```bash
    php artisan db:seed --class=AdminUserSeeder
    ```

### 7. Buat Symbolic Link untuk Storage

Untuk memastikan file yang di-upload (seperti foto profil) dapat diakses dari web, buat symbolic link dari `public/storage` ke `storage/app/public`.

```bash
php artisan storage:link
```

### 8. Jalankan Proyek

Proyek Anda sekarang siap dijalankan. Anda perlu menjalankan dua server secara bersamaan di dua terminal terpisah.

**Terminal 1 - Menjalankan Server PHP Laravel:**
```bash
php artisan serve
```
Server akan berjalan di `http://127.0.0.1:8000`.

**Terminal 2 - Menjalankan Server Vite untuk Aset (CSS/JS):**
```bash
npm run dev
```

Sekarang, buka browser Anda dan kunjungi **`http://127.0.0.1:8000`** untuk melihat aplikasi berjalan.

---
### Catatan Tambahan (Opsional)

- Jika Anda menggunakan **Laragon**, Anda bisa menggunakan fitur "Auto-create virtual hosts" untuk mendapatkan URL yang lebih cantik (misal: `http://monitoring-magang.test`) tanpa perlu menjalankan `php artisan serve`.
- Jika ada perubahan pada file aset (CSS/JS), Vite (`npm run dev`) akan secara otomatis me-refresh browser Anda.
