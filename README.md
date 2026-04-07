# PHP CMS Pro (Belajar PHP CMS)

PHP CMS Pro adalah sistem manajemen konten (Content Management System) berbasis web yang dibangun menggunakan bahasa pemrograman PHP native dan database MySQL. Aplikasi ini dirancang untuk memudahkan pengelolaan konten website seperti berita, portofolio, produk, layanan, galeri, serta dilengkapi dengan fitur *checkout* sederhana.

## 🚀 Fitur Utama

- **Manajemen Konten:**
  - **Berita (News):** Kelola publikasi artikel atau berita.
  - **Portofolio:** Tampilkan karya dan proyek terbaik.
  - **Produk:** Katalog produk beserta detailnya.
  - **Layanan (Services):** Informasi layanan yang ditawarkan.
  - **Galeri:** Manajamen foto dan album galeri.
- **Transaksi & Interaksi:**
  - Fitur *Checkout* dan proses pesanan (`checkout.php`, `process-checkout.php`, `order-success.php`).
  - Fitur *Inquiry/Contact* (`process-inquiry.php`, `inquiry-success.php`, `submit-form.php`).
- **Dashboard Admin:**
  - Tersedia di direktori `admin/` untuk mengelola semua data secara dinamis.
- **Frontend Dinamis:**
  - Halaman terpisah untuk setiap kategori (contoh: `news.php` dan `news-detail.php`).

---

## 🛠️ Persyaratan Sistem

Pastikan environment Anda memenuhi persyaratan berikut:
- **Web Server:** Apache / Nginx / XAMPP / Laragon
- **PHP:** Versi 7.4 atau 8.x ke atas.
- **Database:** MySQL atau MariaDB.
- **Ekstensi PHP:**
  - `mysqli` (untuk koneksi database)
  - `gd` (opsional jika ada pengolahan gambar)

---

## 📁 Struktur Direktori

```text
/belajarphpcms/
├── admin/                  # Panel Admin (Backend)
├── assets/                 # Aset statis frontend (CSS, JS, Images, Font)
├── config/
│   ├── database.php        # File konfigurasi koneksi PHP ke MySQL
│   └── database.sql        # Skema dan data awal database (opsional)
├── includes/               # Komponen template (Header, Footer, Navbar)
├── uploads/                # Folder penyimpanan file unggahan dari Admin (gambar produk/berita)
├── belajarphpcms.sql       # File dump database untuk import ke MySQL
├── belajarphpcms.zip       # Backup arsip project
│
# --- File Frontend (Halaman Publik) ---
├── index.php               # Halaman Utama (Home)
├── news.php                # Daftar Berita
├── news-detail.php         # Detail Berita
├── portfolio.php           # Daftar Portofolio
├── portfolio-detail.php    # Detail Portofolio
├── products.php            # Daftar Produk
├── product-detail.php      # Detail Produk
├── services.php            # Daftar Layanan
├── service-detail.php      # Detail Layanan
├── gallery.php             # Halaman Galeri
├── gallery-detail.php      # Detail Galeri
│
# --- Proses Form & Transaksi ---
├── checkout.php            # Halaman Checkout
├── process-checkout.php    # Logika pemrosesan Checkout
├── order-success.php       # Halaman Pesanan Berhasil
├── process-inquiry.php     # Pemrosesan Form Pertanyaan/Kontak
├── inquiry-success.php     # Halaman Pertanyaan Berhasil
└── submit-form.php         # Endpoint penerima pengiriman form umum
```

---

## ⚙️ Panduan Instalasi (Development Lokal)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi secara lokal dengan *environment* seperti Laragon, XAMPP, atau MAMP:

### 1. Persiapan Folder
Pindahkan/ekstrak folder aplikasi ke direktori web server Anda:
- **XAMPP:** `C:\xampp\htdocs\belajarphpcms`
- **Laragon:** `C:\laragon\www\belajarphpcms`

### 2. Setup Database
1. Buka **phpMyAdmin** atau client database seperti HeidiSQL/DBeaver.
2. Buat database baru, contoh: `belajarphpcms`.
3. Pilih fitur *Import*, lalu upload file `belajarphpcms.sql` (atau `config/database.sql`) yang ada di folder root untuk membuat struktur tabel beserta data sampelnya.

### 3. Konfigurasi Koneksi Database
Buka file `config/database.php` menggunakan *Code Editor* Anda (VSCode / Sublime / dll). Sesuaikan variabel kredensial dengan environment lokal Anda:

```php
<?php
$host = "localhost";
$user = "root";          // Default XAMPP/Laragon biasanya "root"
$password = "";          // Default XAMPP/Laragon biasanya kosong ('')
$database = "belajarphpcms"; // Sesuaikan dengan nama DB yang dibuat pada langkah 2

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

### 4. Jalankan Aplikasi
Buka Web Browser dan akses *URL* berikut sesuai web server Anda:
- **Frontend:** `http://localhost/belajarphpcms/`
- **Dashboard Admin:** `http://localhost/belajarphpcms/admin/`

> **Catatan Admin:** Periksa tabel admin/users pada *database* untuk mengetahui detail login *default* (email/username & password) jika sistem memiliki autentikasi.

---

## 💡 Dokumentasi Teknis

### Template dan Includes
Agar header, footer, dan menu tidak berulang-ulang ditulis di setiap halaman frontend, digunakan pendekatan reusability melalui folder `includes/`. 
Setiap halaman seperti `index.php` akan memanggil script ini pada bagian atas dan bawah.
Contoh:
```php
<?php include 'includes/header.php'; ?>
<!-- Konten halaman -->
<?php include 'includes/footer.php'; ?>
```

### Keamanan (Security Notes)
Jika Anda berencana membawa sistem ini ke tahap *Production* atau *Live Hosting*, sangat disarankan untuk:
1. Memastikan folder `uploads/` tidak mengeksekusi script PHP guna mencegah celah *shell upload*.
2. Memastikan konfigurasi `database.php` menggunakan password SQL yang kuat, bukan biarkan kosong.
3. Melakukan sanitasi pada semua input di file pemrosesan seperti `process-checkout.php` dan `process-inquiry.php` (Gunakan *Prepared Statements* / `mysqli_stmt` untuk mencegah SQL Injection).

### Manajemen Uploads
Semua gambar berita, produk, dan portofolio dilayani melalui direktori `/uploads`. Pastikan direktori ini *writable* `(chmod 755 atau 777)` jika di-*deploy* pada server Linux agar admin dapat mengunggah gambar.

---

## 🖋️ Tentang Modifikasi
Developer bebas menyesuaikan file CSS (`assets/css/...`) serta logika pada controller frontend. Jika terdapat bug saat transaksi *checkout*, periksa variabel `$_POST` pada halaman `process-checkout.php` untuk melacak asal masalah.

---
*Dibuat untuk keperluan pembelajaran dan operasional Content Management System secara mandiri.*
