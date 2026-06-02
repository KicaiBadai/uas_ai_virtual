# UAS AI Virtual - Chatbot AM Merchandise

![Laravel](https://img.shields.io/badge/Laravel-v10.x%20%2F%20v11.x-red)
![AI Integration](https://img.shields.io/badge/AI%20Model-Mistral%20AI%20\(open--mistral--7b\)-orange)
![Database](https://img.shields.io/badge/Database-None%20\(Knowledge%20Base\)-lightgrey)

## 📖 Deskripsi Proyek

**UAS AI Virtual** merupakan aplikasi **Chatbot Customer Service berbasis Artificial Intelligence** untuk toko online **AM Merchandise**. Aplikasi ini dikembangkan menggunakan **Laravel (PHP)** sebagai backend dan terintegrasi dengan **Mistral AI API** (`open-mistral-7b`) untuk memahami serta merespons pertanyaan pelanggan secara otomatis.

Berbeda dengan aplikasi e-commerce pada umumnya, proyek ini menerapkan konsep **No Database**, di mana seluruh informasi produk, harga, gambar, tautan produk, dan aturan operasional toko disimpan langsung di dalam **Knowledge Base** yang tertanam pada *System Prompt* AI.

Dengan pendekatan ini, chatbot dapat memberikan informasi produk secara cepat tanpa memerlukan proses query database.

---

## 🚀 Fitur Utama

### 🤖 AI Customer Service Berbasis NLP

Chatbot mampu memahami pertanyaan pelanggan menggunakan teknologi **Natural Language Processing (NLP)** melalui Mistral AI.

### 🎯 Fokus pada Katalog Produk

AI hanya melayani pertanyaan yang berkaitan dengan produk AM Merchandise. Jika pengguna bertanya di luar topik, chatbot akan mengarahkan percakapan kembali ke informasi produk secara sopan.

### 🛡️ Content Filtering

Sistem dilengkapi dengan filter untuk mendeteksi dan memblokir kata-kata:

* Kasar
* Vulgar
* NSFW (Not Safe For Work)

sebelum dikirim ke API AI.

### 🔒 Pencegahan Redireksi Eksternal

Chatbot dirancang untuk menjaga alur pembelian tetap berada di dalam website dan menghindari pengalihan otomatis ke platform eksternal yang tidak relevan.

### 🖼️ Rich Product Response

AI dapat menampilkan:

* Gambar produk menggunakan Markdown Image
* Link detail produk
* Informasi harga
* Deskripsi produk

secara otomatis dalam format yang lebih menarik.

---

## 🛠️ Teknologi yang Digunakan

| Teknologi             | Keterangan            |
| --------------------- | --------------------- |
| Laravel               | Framework Backend PHP |
| Mistral AI API        | Mesin AI/NLP          |
| Blade Template        | Tampilan Frontend     |
| HTML, CSS, JavaScript | User Interface        |
| Tailwind CSS          | Styling Frontend      |

---

## 📂 Struktur Folder Proyek

```text
uas_ai_virtual/
├── app/
│   └── Http/
│       └── Controllers/
│           └── AIController.php
│               # Logika chatbot, filter kata,
│               # knowledge base, dan API Mistral
│
├── config/
│
├── routes/
│   └── web.php
│       # Routing aplikasi
│
├── resources/
│   └── views/
│       # Tampilan antarmuka chatbot (Blade)
│
├── .env.example
│   # Template konfigurasi environment
│
└── README.md
    # Dokumentasi proyek
```

---

## ⚙️ Instalasi dan Menjalankan Proyek

### 1. Clone Repository

```bash
git clone https://github.com/KicaiBadai/uas_ai_virtual.git
cd uas_ai_virtual
```

### 2. Install Dependency

Pastikan Composer sudah terinstall, kemudian jalankan:

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Jika menggunakan Windows:

```bash
copy .env.example .env
```

Generate Laravel Application Key:

```bash
php artisan key:generate
```

Tambahkan API Key Mistral pada file `.env`:

```env
MISTRAL_API_KEY=isi_dengan_api_key_mistral_anda
```

> Karena proyek ini tidak menggunakan database, konfigurasi database dapat dikosongkan atau diabaikan.

### 4. Jalankan Server Laravel

Aktifkan XAMPP

lalu
```bash
php artisan serve
```

Aplikasi dapat diakses melalui browser pada alamat:

```text
http://127.0.0.1:8000
```

---

## 💡 Cara Kerja Sistem

1. Pengguna mengirim pesan melalui antarmuka chatbot.
2. Sistem melakukan pemeriksaan terhadap kata-kata yang dilarang.
3. Pertanyaan pengguna digabungkan dengan Knowledge Base AM Merchandise.
4. Permintaan dikirim ke Mistral AI API.
5. AI menghasilkan respons berdasarkan informasi produk yang tersedia.
6. Respons ditampilkan kembali kepada pengguna.
