# INDIVIDUAL PROJECT 5: LIBRAGE - Library Management System

## Overview
LIBRAGE adalah sistem manajemen perpustakaan digital yang dirancang untuk meningkatkan efisiensi pengelolaan koleksi buku dan transaksi peminjaman. Sistem ini mendukung berbagai peran pengguna: **Admin**, **Pegawai Perpustakaan**, **Mahasiswa**, dan **Guest**, masing-masing dengan hak akses yang spesifik. Dilengkapi dengan fitur opsional seperti sistem notifikasi, denda, reservasi, rekomendasi buku, dan pelaporan analitik.

---

## User Levels

### 1. Admin
- **Akses Penuh:** Mengelola pengguna, koleksi buku, serta memantau seluruh aktivitas sistem.
- **Fitur Utama:**
  - Manajemen pengguna (Admin, Pegawai, Mahasiswa).
  - Manajemen koleksi buku.
  - Monitoring aktivitas perpustakaan.

### 2. Pegawai Perpustakaan
- **Peran:** Menangani peminjaman, pengembalian, dan pembaruan data buku.
- **Fitur Utama:**
  - Proses peminjaman dan konfirmasi pengembalian.
  - Memperbarui data koleksi buku.

### 3. Mahasiswa
- **Akses:** Dapat memanfaatkan layanan peminjaman buku dan fitur katalog.
- **Fitur Utama:**
  - Melihat katalog dan detail buku.
  - Meminjam, memperpanjang pinjaman, serta memberikan ulasan.
  - Melihat riwayat peminjaman.

### 4. Guest (Pengguna Umum)
- **Akses Terbatas:** Hanya dapat melihat katalog buku.
- **Catatan:** Harus login sebagai mahasiswa untuk menggunakan fitur lainnya.

---

## CMS Modules

### 1. **User Management (Admin)**
- **List Users:** Melihat daftar semua pengguna.
- **Create User:** Menambahkan pengguna baru dengan validasi data.
- **Edit User:** Mengubah data pengguna yang telah ada.
- **Delete User:** Menghapus pengguna yang tidak aktif.

### 2. **Book Management (Admin & Pegawai)**
- **List Books:** Menampilkan koleksi buku lengkap.
- **Create Book:** Menambah buku baru dengan validasi data lengkap.
- **Edit Book:** Memperbarui informasi buku.
- **Delete Book:** Menghapus buku yang tidak tersedia.

### 3. **Loan Management (Pegawai & Mahasiswa)**
- **Loan Books:** Mahasiswa dapat meminjam buku.
- **Return Books:** Pegawai mengonfirmasi pengembalian.
- **Renew Loans:** Perpanjangan pinjaman oleh mahasiswa.
- **Loan History:** Melihat riwayat peminjaman mahasiswa.

---

## Layout Requirements

1. **Login/Register Page**
   - Login untuk semua peran.
   - Register khusus mahasiswa dengan validasi email universitas.
   
2. **Homepage**
   - Menampilkan koleksi buku terbaru dan populer.
   - Search bar untuk pencarian cepat.

3. **Book Catalog**
   - Filter dan pengurutan berdasarkan kategori, tahun terbit, atau popularitas.

4. **Book Details Page**
   - Informasi lengkap buku.
   - Tombol "Pinjam Buku" untuk mahasiswa terdaftar.

5. **Dashboard Mahasiswa**
   - Riwayat peminjaman, perpanjangan, dan pengaturan profil.

6. **Dashboard Admin**
   - Monitoring aktivitas pengguna dan laporan statistik.

7. **Dashboard Pegawai**
   - Proses peminjaman, pengembalian, dan pembaruan stok buku.

---

## Advanced Features (Optional Upgrades)
- **Notification System:** Pengingat peminjaman dan pengembalian buku.
- **Fines & Penalty System:** Otomatisasi perhitungan denda keterlambatan.
- **Book Reservation System:** Reservasi buku yang sedang dipinjam.
- **Recommendation System:** Rekomendasi berdasarkan riwayat pinjaman.
- **Analytics & Reporting:** Statistik peminjaman dan ketersediaan buku.

---

## Installation

1. **Clone repository:**
   ```bash
   git clone https://github.com/yourusername/library-management.git

2. **Install dependencies:**
   ```bash
   composer install
   npm install

3. **Set up .env file:**
   - Atur database credentials.

4. **Install dependencies:**
   ```bash
   php artisan migrate

5. **Run Seeder:**
   ```bash
   php artisan db:seed --class=KategoriSeeder
   php artisan db:seed --class=BukuSeeder
   php artisan db:seed --class=UserSeeder

6. **Run Server:**
   ```bash
   php artisan serve
   npm run dev
