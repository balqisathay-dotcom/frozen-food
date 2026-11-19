# Aplikasi Pencatatan Produk Frozen Food Pak Budi

- **Nama**  : (isi nama Anda)
- **Kelas** : (isi kelas Anda)

Aplikasi web untuk mencatat produk frozen food beserta kategori dan supplier-nya.
Dibuat dengan PHP Native (PDO), MySQL/MariaDB, dan Bootstrap 5.

## Fitur
- CRUD produk, kategori, dan supplier
- Filter produk berdasarkan kategori, supplier, dan pencarian nama
- Validasi input dan konfirmasi sebelum menghapus
- Kategori/supplier yang masih dipakai produk tidak bisa dihapus (muncul pesan peringatan)

## Langkah Menjalankan
1. Pasang XAMPP/Laragon (PHP 8+ dan MySQL/MariaDB), lalu jalankan Apache dan MySQL.
2. Salin folder projek ini ke `htdocs` (XAMPP) atau `www` (Laragon).
3. Buka phpMyAdmin, lalu **Import** file `database.sql` (database `frozen_food` dibuat otomatis).
4. Jika perlu, sesuaikan user/password database di `config.php`.
5. Buka `http://localhost/<nama-folder>/index.php` di browser.

> Koneksi internet diperlukan agar Bootstrap (CDN) termuat.

## Struktur Berkas
```
index.php            Beranda (ringkasan)
produk*.php          Daftar, form tambah/ubah, hapus produk
kategori*.php        Daftar, form tambah/ubah, hapus kategori
supplier*.php        Daftar, form tambah/ubah, hapus supplier
config.php           Koneksi database
database.sql         Struktur tabel + data contoh
includes/            header, footer, fungsi bantu
assets/              CSS dan JavaScript
```
