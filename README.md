# AM Merchandise — E-Commerce Anime dengan Chatbot Mistral AI

> Platform e-commerce merchandise anime berbasis Laravel yang dilengkapi dua kanal AI: **AI Customer Service** untuk pengunjung dan **AI Admin Assistant** untuk operator toko.

![Laravel](https://img.shields.io/badge/Laravel-v10.x%20%2F%20v11.x-red)
![AI Integration](https://img.shields.io/badge/AI%20Model-Mistral%20AI%20\(open--mistral--7b\)-orange)
![Database](https://img.shields.io/badge/MYSQL%20\(Knowledge%20Base\)-lightgrey)


## Tentang Proyek

AM Merchandise adalah aplikasi web e-commerce untuk penjualan produk bertema anime (hoodie, kaos, topi). Sistem ini dirancang untuk mengurangi beban operasional admin melalui otomatisasi berbasis AI dan menyediakan pengalaman belanja yang interaktif bagi pengguna.

**Masalah yang diselesaikan:**
- Keterbatasan kapasitas layanan pelanggan
- Tingginya beban operasional admin dalam mengelola pesanan dan stok
- Lemahnya konversi karena pengunjung tidak mendapat bantuan rekomendasi produk

**Solusi:** Integrasi Mistral AI sebagai chatbot Customer Service (sisi user) dan Admin Assistant (sisi admin), dengan alur konfirmasi pesanan dan pengaduan melalui WhatsApp.

---

## Teknologi

| Komponen | Teknologi | Keterangan |
|---|---|---|
| Framework Backend | Laravel 10.x | MVC, routing, Eloquent ORM |
| Bahasa Pemrograman | PHP 8.1+ | Bahasa server-side utama |
| Database | MySQL 8.0 | Relational database |
| AI Model | Mistral AI API | Model bahasa untuk chatbot |
| Templating | Blade Engine | Rendering view Laravel |
| Frontend CSS | Tailwind CSS / Bootstrap | Layout dan komponen UI |
| JavaScript | Vanilla JS + Axios/Fetch | Interaksi chatbot dan form |
| Build Tool | Vite | Bundling asset Laravel modern |
| Integrasi Pesan | WhatsApp wa.me Deep Link | Konfirmasi pesanan dan pengaduan |
| Web Server | Apache / LiteSpeed | Disesuaikan dengan hosting |
| Deployment | Shared Hosting + Domain | https://jawapride.my.id |
| Version Control | Git + GitHub | uas_ai_virtual repository |

---

## Arsitektur Sistem

### Arsitektur MVC (Laravel)

```
USER ──HTTP Request──► WEBSITE LARAVEL
                              │
                              ▼
                         ROUTING          routes/web.php
                              │
                              ▼
                         CONTROLLER       app/Http/Controllers/*
                              │
                              ▼
                           MODEL          app/Models/*
                              │
                              ▼
                          DATABASE        MySQL
                              │
                       Eloquent Result
                              ▼
                            VIEW          resources/views (Blade)
                              │
                              ▼
                          RESPONSE        HTML + JSON
```

### Arsitektur Chatbot Berbasis Mistral AI

```
USER
  │ Pertanyaan / Perintah
  ▼
CHATBOT (Frontend)          ChatController / chat.blade.php
  │ POST /chatbot/ask
  ▼
MISTRAL SERVICE             app/Services/MistralService.php
  │ Build Prompt + Context
  ▼
DATASET KUSTOM  ◄── System Prompt ── SETTINGS TABLE
  │ Retrieval (kata kunci, kategori)
  ▼
MISTRAL API                 api.mistral.ai/v1/chat/completions
  │ JSON Response
  ▼
RESPONSE AI                 Disimpan ke chat_histories
  ▼
USER (UI)
```

---

## Struktur Folder

```
uas_ai_virtual/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── ProductController.php
│   │   │   ├── AdminChatController.php
│   │   │   ├── AIController.php
│   │   │   ├── ChatController.php
│   │   │   └── Controller.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── AdminChatHistory.php
│   │   ├── Order.php
│   │   ├── Product.php
│   │   ├── Setting.php
│   │   └── User.php
│   └── Services/
│       └── MistralService.php
├── config/
│   ├── app.php
│   ├── database.php
│   ├── service.php
│   └── ... (file config Laravel standar)
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   └── views/
│       ├── admin/
│       └── user/
├── routes/
│   ├── web.php
│   └── api.php
├── .env
├── composer.json
├── package.json
└── artisan
```

---

## Desain Database

Database MySQL bernama `jawapride`, engine InnoDB, charset `utf8mb4`.

### Entity Relationship Overview

```
users (admin)

categories ──< products ──< order_items >── orders
                                                │
                                                ▼
                                           complaints

chat_histories
datasets
settings
```

### Tabel Utama

**`products`** — Data merchandise
| Field | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Identifier produk |
| slug | VARCHAR(255) | URL unik produk |
| name | VARCHAR(255) | Nama produk |
| price | VARCHAR(255) | Harga format teks |
| price_raw | INT(11) | Angka harga untuk kalkulasi |
| image | VARCHAR(255) | Path/URL gambar |
| category | VARCHAR(255) | Kategori produk |
| sizes | LONGTEXT (JSON) | Array ukuran tersedia |
| stock | INT(11) | Jumlah stok (default: 0) |

**`orders`** — Pesanan pelanggan
| Field | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Identifier order |
| receipt_number | VARCHAR(255) | Nomor resi (format AMxxxxx) |
| customer_name | VARCHAR(255) | Nama pelanggan |
| customer_whatsapp | VARCHAR(255) | Nomor WhatsApp pembeli |
| product_id | BIGINT FK | Relasi ke produk |
| quantity | INT(11) | Jumlah barang |
| total_price | INT(11) | Total harga |
| status | VARCHAR(255) | Status (default: 'pending') |

**`settings`** — Konfigurasi sistem global
| Field | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Identifier |
| key | VARCHAR(100) UNIQUE | Kunci config (mis: `mistral_api_key`, `system_prompt`, `admin_wa`) |
| value | TEXT | Nilai konfigurasi |

---

## Fitur Utama

### Admin Panel

**Dashboard** — Menampilkan ringkasan real-time: total produk, kategori aktif, total pesanan, pesanan pending, total omset, model AI aktif, status database, dan status koneksi API.

**Kelola Produk (CRUD)** — Tambah, edit, hapus, dan cari produk. Mendukung upload gambar, input ukuran multiple, dan manajemen stok.

**Kelola Pesanan** — Pencarian berdasarkan resi/nama/WhatsApp, ubah status pesanan, dan konfirmasi via WhatsApp.

```
Workflow status: pending ──► diproses ──► dikirim ──► selesai
                    └──────► batal
```

**AI Admin Assistant** — Chatbot berbasis natural language yang menerjemahkan perintah admin menjadi query database.

Contoh perintah yang didukung:
- `"Tampilkan pesanan pending"`
- `"Cari pesanan atas nama Afnan"`
- `"Tambah 5 stok topi akatsuki"`
- `"Ubah status resi AM12345 menjadi dikirim"`
- `"Laporan penjualan bulan ini"`

### Storefront (User)

**Katalog Produk** — Grid produk dengan filter kategori (hoodie, kaos, topi) dan pencarian keyword.

**Form Pemesanan** — Pilih ukuran, jumlah, kurir (JNE/J&T/SiCepat), metode pembayaran. Subtotal dihitung otomatis di sisi klien.

**Pengurangan Stok Otomatis** — Stok dikurangi dalam satu `DB::transaction` setelah order berhasil dibuat.

**AI Customer Service** — Chatbot konversasional yang merekomendasikan produk, menjawab pertanyaan stok, dan mengarahkan pengaduan ke halaman `/pengaduan`.

**Pengaduan** — Form resmi pengaduan yang menyimpan data ke database sebelum meneruskan ke WhatsApp admin.

**Konfirmasi WhatsApp** — Setelah pemesanan berhasil, tombol konfirmasi membuka deep link `wa.me/{admin_wa}` dengan template pesan otomatis.

---

## Instalasi & Deployment

### Prasyarat
- PHP 8.1+
- MySQL 8.0
- Composer
- Node.js & npm

### Langkah Instalasi

```bash
# Clone repository
git clone https://github.com/KicaiBadai/uas_ai_virtual.git
cd uas_ai_virtual

# Install dependensi PHP
composer install

# Install dependensi Node.js
npm install && npm run build

# Salin file environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu jalankan migrasi
php artisan migrate --force
php artisan db:seed

# Buat symlink storage
php artisan storage:link
```

### Konfigurasi `.env`

```env
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_DATABASE=jawapride
DB_USERNAME=jawapride_user
DB_PASSWORD=********

MISTRAL_API_KEY=********
MISTRAL_MODEL=mistral-small-latest
ADMIN_WA=62xxxxxxxxxx
```

### Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Konfigurasi Hosting

Document root diarahkan ke folder `public/` Laravel. Jika hosting tidak mengizinkan perubahan document root, gunakan symlink: `public_html → public/`. SSL diaktifkan via Let's Encrypt.

---

## Konfigurasi API Mistral

1. Login ke [https://console.mistral.ai](https://console.mistral.ai)
2. Generate API key dan masukkan ke `.env` atau melalui menu **Settings** di admin panel
3. Pilih model:
   - `mistral-small-latest` — biaya rendah, direkomendasikan untuk produksi
   - `mistral-medium-latest` — kualitas lebih tinggi
4. Atur System Prompt dan Dataset di admin panel (`/admin/settings`)

**System Prompt Admin Assistant** menggunakan format JSON terstruktur:
```json
{
  "intent": "<nama_intent>",
  "params": { ... }
}
```

**System Prompt Customer Service** menggunakan gaya konversasional berbahasa Indonesia yang fokus pada produk toko.

---

## Pengujian

Pengujian menggunakan metode **Black Box** pada 24 skenario. Seluruh skenario menunjukkan hasil **OK** pada lingkungan produksi, mencakup:

- Autentikasi admin (valid & invalid)
- CRUD produk (tambah, edit, hapus, cari)
- Manajemen pesanan dan perubahan status
- Chatbot admin (query pesanan, update stok, update status)
- Chatbot user (rekomendasi produk, cek stok, penanganan keluhan)
- Alur pengaduan (valid & invalid)
- Pemesanan dan pengurangan stok otomatis
- Integrasi WhatsApp (storefront & pengaduan)
- CSRF protection dan akses tanpa autentikasi

---

## Keamanan

- **Validasi Input** — Semua request publik melewati Form Request Validation Laravel
- **CSRF Protection** — Middleware `VerifyCsrfToken` aktif di seluruh form publik (`@csrf`)
- **SQL Injection Prevention** — Semua query menggunakan Eloquent ORM / Query Builder dengan parameter binding
- **Authentication** — Session-based auth (`Auth::attempt`) dengan password bcrypt; route `/admin/*` dilindungi middleware `auth`
- **Environment Variables** — Variabel sensitif (API key, password) disimpan di `.env` dan tidak di-commit ke repository

---

## Lisensi

*Program Studi Teknik Informatika — Fakultas Teknologi Informasi dan Komunikasi — Institut Teknologi dan Bisnis Bina Sarana Global*

---
