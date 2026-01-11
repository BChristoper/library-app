# Dokumentasi Sistem Perpustakaan menggunakan Laravel

## Ringkasan
Aplikasi web ini membantu sebuah perpustakaan mengelola katalog buku dan pencatatan peminjaman. Pengguna dibagi menjadi petugas dan anggota. Petugas mengelola data, anggota hanya melihat dan memantau peminjaman miliknya. Aplikasi dibuat dengan Laravel (PHP) dan MySQL, dengan antarmuka Tailwind via  (Content Delivery Network) CDN.

## Metode Pengembangan
- Metode: Waterfall.
- Alasan: kebutuhan relatif jelas, scope kecil, waktu pengerjaan singkat.
- Tahap: Analisis -> Desain -> Implementasi -> Pengujian -> Dokumentasi.

## Diagram dan Pemodelan
- ERD: ada di `docs/ERD DIAGRAM.drawio.xml`.
- Use Case: petugas (CRUD buku, catat pinjam/kembali, laporan), anggota (lihat buku, lihat peminjaman).
- Class Diagram sederhana: Controller -> Service -> Model (contoh BookController -> BookService -> Book).
- Component Diagram: UI (Blade) -> Controller -> Model/Database.

## Lingkungan Pengembangan
- IDE: Visual Studio Code.
- Runtime lokal: Laragon.
- Bahasa/Framework: PHP + Laravel.
- DBMS: MySQL.

## Requirement / Dependensi
- PHP 8.2+.
- Composer.
- MySQL.
- Laragon (untuk server lokal).
- Browser modern (Chrome/Edge/Firefox).
- Node.js hanya diperlukan jika ingin menggunakan tooling frontend tambahan (opsional).

## Fitur Utama
- Login petugas dan anggota + logout.
- Hak akses berbasis peran (petugas vs anggota).
- Buku: daftar + pencarian (judul/penulis/ISBN), tambah, ubah, hapus.
- Anggota: daftar + pencarian (nama/kode/email), tambah, ubah, hapus.
- Peminjaman: daftar + filter status (aktif/kembali/terlambat) + pencarian.
- Peminjaman (petugas): catat pinjam, catat kembali, jatuh tempo otomatis +7 hari.
- Stok buku otomatis berkurang saat pinjam dan bertambah saat kembali.
- Laporan peminjaman aktif dan terlambat (petugas).

## Modul dan Fitur (Detail)
- Modul Buku
  - Menampilkan katalog buku + pencarian.
  - Tambah, ubah, hapus buku (khusus petugas).
- Modul Anggota (dikelola sebagai akun user ber-role `anggota`)
  - Menampilkan daftar anggota + pencarian.
  - Tambah, ubah, hapus anggota (khusus petugas).
- Modul Peminjaman
  - Catat peminjaman buku (petugas).
  - Jatuh tempo otomatis +7 hari.
  - Pengembalian buku dan stok kembali.
  - Filter status (aktif/kembali/terlambat) pada daftar.
- Modul Login
  - Login untuk petugas dan anggota.

## Struktur Database (Ringkas)
Tabel utama:
- `users`: akun petugas/anggota.
- `books`: data buku.
- `loans`: data peminjaman.

Relasi:
- `loans.user_id` mengacu ke `users.id` (1 user bisa memiliki banyak peminjaman).
- `loans.book_id` mengacu ke `books.id` (1 buku bisa muncul di banyak peminjaman).

## Struktur Database (Detail)
Tabel utama:
- users: akun login + data anggota (role = `anggota`).
- books: data buku.
- loans: data peminjaman.
- sessions: data sesi login (opsional, jika menggunakan driver database).

Relasi:
- loans.user_id -> users.id
- loans.book_id -> books.id

## Cara Menjalankan
1. Install dependency:
   ```bash
   composer install
   ```
2. Salin `.env` dan atur koneksi database:
   ```bash
   cp .env.example .env
   ```
   Contoh isi:
   ```text
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=library_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Generate app key:
   ```bash
   php artisan key:generate
   ```
4. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```
5. Jalankan seeder (akun petugas + data buku contoh):
   ```bash
   php artisan db:seed
   ```
6. Buka aplikasi:
   - Laragon: `http://library-app.test`
   - Atau: `php artisan serve`

## Debugbar (Opsional)
Jika ingin menampilkan bar debug di bawah halaman:
1. Install package:
   ```bash
   composer require barryvdh/laravel-debugbar --dev
   ```
2. Pastikan `.env`:
   ```text
   APP_DEBUG=true
   ```
3. Reload halaman.

## Alur Singkat
1. Petugas login.
2. Petugas menambah data buku.
3. Petugas mencatat peminjaman:
   - `loan_date` otomatis tanggal hari ini.
   - `due_date` otomatis +7 hari.
4. Petugas mencatat pengembalian (mengisi `return_date`).
5. Anggota login dan melihat peminjaman miliknya.

## Proses Utama (Detail)
### Tambah Buku
- Input form buku.
- Validasi data.
- Simpan ke tabel books.

### Peminjaman Buku (Petugas)
- Petugas memilih anggota (user role `anggota`) dan buku.
- Cek stok buku.
- Simpan data ke loans dengan due_date = loan_date + 7 hari.
- Kurangi stok buku.

### Pengembalian Buku (Petugas)
- Petugas klik tombol Kembalikan.
- Isi return_date.
- Update loan dan tambah stok buku.

## Dokumentasi Fungsi Utama (Ringkas)
### BookController
- index(q): menampilkan daftar buku dan pencarian.
- create(): form tambah buku.
- store(data): validasi dan simpan buku.
- edit(book): form edit buku.
- update(book, data): update buku.
- destroy(book): hapus buku.

### MemberController
- index(q): menampilkan daftar anggota.
- create(): form tambah anggota.
- store(data): validasi dan simpan anggota.
- edit(member): form edit anggota.
- update(member, data): update anggota.
- destroy(member): hapus anggota.

### LoanController
- index(q, status): daftar peminjaman, filter status.
- create(): form pinjam.
- store(data): simpan peminjaman + due_date +7.
- update(loan): pengembalian dan stok kembali.
- report(): laporan peminjaman aktif dan jatuh tempo.

### AuthController
- showLogin(): tampilkan halaman login.
- login(): autentikasi user.
- logout(): keluar dari aplikasi.

## Middleware dan Service
- Middleware: `auth` (wajib login) dan `role:petugas` (akses khusus petugas) di `app/Http/Middleware/RoleMiddleware.php`.
- Service: `app/Services/BookService.php` untuk operasi create/update/delete buku.

## Seeder
- `DatabaseSeeder`: membuat akun petugas.
- `BookSeeder`: mengisi data buku contoh.

## Error Handling
- Validasi input memakai Laravel validation.
- Error validasi ditampilkan di layout menggunakan session errors.
- Pesan error ditulis dalam bahasa Indonesia.
- Stok habis: peminjaman ditolak.
- Tanggal pengembalian tidak valid: ditolak.
- Buku dengan peminjaman aktif: tidak bisa dihapus.

## Ukuran Performa
- Pengukuran lokal menggunakan Laravel Debugbar menunjukkan waktu respon sekitar 300 ms - 1 detik (tergantung data dan mesin).

## Pemetaan Kompetensi Tambahan
### Program Baca-Tulis, Fungsi, Array, Akses File
- Input form = proses baca data dari pengguna; output ditampilkan di Blade.
- Fungsi/prosedur: method controller/service (contoh `store`, `update`).
- Array: digunakan pada seeder (`BookSeeder`) sebagai data koleksi.
- Akses file: Laravel memiliki `storage` untuk log; proyek ini tidak memakai upload khusus.

### Kompilasi/Debugging/Pengujian
- "Compile" untuk PHP dimaknai sebagai menjalankan aplikasi tanpa error.
- Debugging: menggunakan error Laravel + (opsional) Debugbar.
- Pengujian: dilakukan secara manual dengan skenario sederhana (tambah buku, pinjam, kembali).

### Reuse Komponen dan Lisensi
- Reuse: Laravel, Tailwind CDN, dan Debugbar (opsional).
- Lisensi: digunakan untuk keperluan pengembangan sesuai lisensi open-source masing-masing.

### Dokumentasi Modul dan Generate Doc
- Dokumentasi modul disusun di README ini.
- ERD disediakan di `docs/ERD DIAGRAM.drawio.xml`.

## Akun Login (Seeder)
- Petugas: `petugas@example.com` / `password`.
- Anggota: dibuat dari menu Anggota oleh petugas.

## Berkas Tambahan
- ERD ada di `docs/ERD DIAGRAM.drawio.xml`.
