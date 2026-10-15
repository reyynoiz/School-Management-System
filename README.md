# School-Management-System

Sistem Informasi Manajemen Sekolah berbasis Laravel 11. Bertujuan untuk mengelola data siswa, guru, kelas, dan mata pelajaran dengan pembatasan akses berdasarkan role (admin, teacher, student).

## Fitur

**Umum**
- Login, Logout, dan Register (pendaftar baru otomatis dengan role `student`)
- Dashboard berbeda per role. Admin melihat kartu ringkasan, grafik jumlah siswa per kelas, komposisi gender, dan siswa terbaru. Teacher dan Student melihat profil masing-masing
- Profil pengguna: ubah nama, username, email, telepon, ganti password, dan upload foto profil
- Tampilan responsif, tabel memakai DataTables, format tanggal Indonesia, serta breadcrumb di setiap halaman

**Data master (CRUD)**
- Siswa, Guru, Kelas, dan Mata Pelajaran, dengan validasi form, autofocus, placeholder, dan tanda `*` pada field wajib
- Foto Siswa dan Guru (JPG/PNG, maksimal 2 MB), disimpan di `public/uploads/students/` dan `public/uploads/teachers/` dengan nama `YmdHis-nama-file`
- Filter tabel: Siswa (kelas, jenis kelamin) dan Guru (mata pelajaran, jenis kelamin)
- Soft delete memakai kolom `archived`, data tidak langsung hilang dari database

**Khusus admin**
- **Archive Management**: lihat data ter-arsip per modul, lalu Restore atau Hapus Permanen
- **Import Excel/CSV** untuk Siswa dan Guru (semua-atau-tidak-sama-sekali: satu baris salah membatalkan seluruh import)
- **Kelola User**: aktifkan atau nonaktifkan akun, dan reset password (password acak baru ditampilkan sekali)

**Untuk semua role**
- **Export PDF** daftar Siswa dan Guru, mengikuti filter yang sedang aktif

## Hak Akses

| Fitur                                   | Admin | Teacher | Student |
|-----------------------------------------|:-----:|:-------:|:-------:|
| Dashboard dan Profil                    |  Ya   |   Ya    |   Ya    |
| Lihat data Siswa/Guru/Kelas/Mapel       |  Ya   |   Ya    |   Ya    |
| Export PDF                              |  Ya   |   Ya    |   Ya    |
| Tambah, ubah, hapus data                |  Ya   |  Tidak  |  Tidak  |
| Import, Archive, Kelola User            |  Ya   |  Tidak  |  Tidak  |

Akses ke URL yang tidak diizinkan menghasilkan halaman 403.

## Teknologi

Laravel 11, PHP 8.2, MySQL, Laravel Breeze (Blade), Tailwind CSS, Alpine.js, Vite, DataTables, Chart.js, `barryvdh/laravel-dompdf` (PDF), dan `maatwebsite/excel` (import).

## Instalasi

Prasyarat: PHP 8.2+, Composer, Node.js, dan MySQL (misalnya lewat XAMPP). Ekstensi PHP yang dibutuhkan: `gd`, `zip`, `mbstring`, `pdo_mysql` (dan `pdo_sqlite` untuk menjalankan test).

```bash
git clone https://github.com/reyynoiz/School-Management-System.git
cd School-Management-System
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Buat database kosong bernama `db_school`, lalu sesuaikan `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_school
DB_USERNAME=root
DB_PASSWORD=
```

Kemudian jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
```

`migrate --seed` hanya untuk database yang baru dibuat. Menjalankannya di database yang sudah berisi akun akan bentrok dengan email yang sudah ada.

Jika muncul error `Unknown collation: utf8mb4_0900_ai_ci`, tambahkan `DB_COLLATION=utf8mb4_unicode_ci` di `.env`.

## Menjalankan Aplikasi

Buka dua terminal:

```bash
npm run dev
```

```bash
php artisan serve
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

## Akun Demo

Dibuat otomatis oleh seeder:

| Role    | Email           | Password |
|---------|-----------------|----------|
| Admin   | admin@school.id | admin123 |
| Teacher | guru@school.id  | guru123  |
| Student | siswa@school.id | siswa123 |

Agar Dashboard guru atau siswa menampilkan profil, hubungkan akunnya lewat field **Akun Login** pada form tambah/ubah Guru atau Siswa (login sebagai admin).

## Import Data

Menu **Import Excel/CSV** ada di halaman Siswa dan Guru (khusus admin). Format file: `.xlsx`, `.xls`, atau `.csv` dengan baris pertama sebagai header.

| Modul | Header kolom                                              | Catatan                                              |
|-------|-----------------------------------------------------------|------------------------------------------------------|
| Siswa | `nis, nama, jenis_kelamin, kelas, username, email`        | `kelas` harus sama persis dengan nama kelas yang ada |
| Guru  | `nip, nama, jenis_kelamin, mata_pelajaran, username, email` | `mata_pelajaran` boleh kosong                      |

- `jenis_kelamin` diisi `L`/`P` atau `Laki-laki`/`Perempuan`
- `username` dan `email` boleh kosong. Jika keduanya diisi, akun login dibuat otomatis dengan password awal `password123` (sebaiknya segera direset lewat **Kelola User**)
- Format kolom `nis` dan `nip` sebagai **Text** di Excel agar angka nol di depan tidak hilang

## Pengujian

```bash
php artisan test
```

Test memakai database SQLite in-memory (diatur di `phpunit.xml`), sehingga tidak mengubah data di `db_school`.

## Struktur dan Penamaan

- Tabel diawali `tbl_` (`tbl_users`, `tbl_students`, `tbl_teachers`, `tbl_classes`, `tbl_subjects`)
- Controller berakhiran `Controller`, view dikelompokkan per fitur (`students/`, `teachers/`, `archives/`, `users/`, dan seterusnya)
- Hapus data berarti mengubah `archived` menjadi `1`, bukan menghapus baris dari database
- Relasi: Siswa ke Kelas, Guru ke Mata Pelajaran, serta Siswa dan Guru ke User lewat `user_id`

## Catatan Produksi

Sebelum dipakai di luar lingkungan lokal, set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`.