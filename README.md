# TELAGA v2

Sistem manajemen sekolah PAUD untuk RA Al Kautsar. Laravel 13 + Inertia + React/TypeScript + Tailwind + shadcn/ui + MySQL/MariaDB.

Dokumentasi produk, aturan bisnis, dan daftar task ada di folder [`agents/`](agents/README.md). Mulai dari [`agents/tasklist.md`](agents/tasklist.md).

## Prasyarat

| Alat            | Versi                        | Catatan                                                                                                                                                           |
| --------------- | ---------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| PHP             | 8.3 atau lebih baru          | Ekstensi: bcmath, ctype, curl, fileinfo, gd, intl, mbstring, openssl, pdo_mysql, tokenizer, xml, zip. Dependensi dikunci agar kompatibel PHP 8.3 (target hosting) |
| Composer        | 2.x                          |                                                                                                                                                                   |
| Node.js         | 22 atau lebih baru (dan npm) |                                                                                                                                                                   |
| MySQL / MariaDB | MySQL 8 atau MariaDB 10.4+   | Contoh: MySQL dari XAMPP                                                                                                                                          |

## Menjalankan di lokal

1. Pasang dependensi: `composer install` lalu `npm install`.
2. Salin konfigurasi: `copy .env.example .env` (Windows) atau `cp .env.example .env`, lalu `php artisan key:generate`.
3. Nyalakan MySQL/MariaDB, lalu buat dua database dan satu user (ganti `KATA_SANDI`):

    ```sql
    CREATE DATABASE telaga_v2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE DATABASE telaga_v2_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER 'telaga_dev'@'localhost' IDENTIFIED BY 'KATA_SANDI';
    CREATE USER 'telaga_dev'@'127.0.0.1' IDENTIFIED BY 'KATA_SANDI';
    GRANT ALL ON telaga_v2.* TO 'telaga_dev'@'localhost', 'telaga_dev'@'127.0.0.1';
    GRANT ALL ON telaga_v2_test.* TO 'telaga_dev'@'localhost', 'telaga_dev'@'127.0.0.1';
    ```

4. Isi `DB_PASSWORD` di `.env` dengan kata sandi tadi. **Jangan** memasukkan `.env` ke git.
5. Migrasi: `php artisan migrate`.
6. Jalankan: `composer dev` (server Laravel, queue, log, dan Vite), lalu buka alamat yang tertera (biasanya http://localhost:8000). Untuk hanya server PHP: `php artisan serve`; untuk hanya Vite: `npm run dev`.

Cek kesehatan aplikasi: `GET /up` harus mengembalikan 200.

> Catatan XAMPP: bila MySQL XAMPP berhenti sendiri di Windows dengan galat InnoDB/Aria, lihat log `xampp\mysql\data\mysql_error.log`; untuk tabel Aria rusak gunakan `aria_chk -r` setelah mencadangkan folder `data`.

## Perintah harian

| Tujuan                                             | Perintah                                               |
| -------------------------------------------------- | ------------------------------------------------------ |
| Semua pemeriksaan PHP (gaya, analisis statis, tes) | `composer test`                                        |
| Hanya tes                                          | `php artisan test` (memakai database `telaga_v2_test`) |
| Perbaiki gaya PHP                                  | `composer lint`                                        |
| Analisis statis PHP                                | `composer types:check`                                 |
| Format + lint JS/TS                                | `npm run check` (perbaiki: `npm run check:fix`)        |
| Cek tipe TypeScript                                | `npm run types:check`                                  |
| Build produksi                                     | `npm run build`                                        |
| Semua pemeriksaan CI                               | `composer ci:check`                                    |

## Aturan penting

- Tidak ada secret, kata sandi, atau data anak asli di repository, log, atau fixture.
- Nama tabel domain Bahasa Indonesia, PK `id_<entitas>` (DEC-028). Tabel kerangka Laravel memakai nama bawaan.
- Zona waktu aplikasi Asia/Jakarta, diatur satu tempat di `config/app.php`.
- Pedoman branch dan review: [`agents/docs/02-backend/repository-and-review.md`](agents/docs/02-backend/repository-and-review.md).
