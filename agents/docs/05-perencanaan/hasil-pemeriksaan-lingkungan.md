# Hasil Pemeriksaan Lingkungan (Task 0.1)

Dicatat 2026-10-10. Status: **lingkungan lokal sudah diperiksa; paket hosting produksi belum dipilih (OD-01), jadi kolom hosting masih kosong.** Ini sesuai `deployment-runbook.md` §0 dan tidak boleh dianggap bukti bahwa hosting memenuhi syarat.

## 1. Lingkungan pengembangan lokal (fakta terukur)

| Item | Hasil | Catatan |
|---|---|---|
| OS | Windows 11 | |
| PHP CLI | 8.5.8 (`C:\php`, ZTS) | **Lebih baru dari target hosting (8.3).** Dependensi dikunci ke platform PHP 8.3.0 (`composer.json` → `config.platform.php`), sehingga `composer.lock` aman dipasang di PHP 8.3. CI memakai PHP 8.3. |
| Ekstensi PHP | bcmath, ctype, curl, dom, fileinfo, gd, intl, mbstring, openssl, pdo_mysql, tokenizer, xml/xmlreader/xmlwriter/simplexml, zip, zlib | Cukup untuk Laravel 13 dan pembaca .xlsx (zip, xml, gd) |
| Composer | 2.10.2 | |
| Node / npm | 24.18.0 / 11.16.0 | CI memakai Node 22 |
| Database lokal | MariaDB 10.4.32 (XAMPP), port 3306 | `sql_mode=NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION`, `max_allowed_packet=16M` |
| Basis data proyek | `telaga_v2` dan `telaga_v2_test`, user `telaga_dev` | Database `telaga` lama (30 tabel) di XAMPP tidak dipakai dan tidak diubah |
| Hasil uji di MariaDB 10.4 | migrasi dan seluruh tes lulus | Bukti awal bahwa skema konservatif (DEC-059) jalan di MariaDB lama |

## 2. Paket hosting produksi (OD-01, OD-02, OD-03) — belum diisi

Isi tabel ini setelah vendor dipilih (Tahap 0A). Syarat minimum ada di `deployment-runbook.md` §0.

| Syarat | Hasil di hosting terpilih | Lulus? |
|---|---|---|
| PHP 8.3 tersedia dan dapat dipilih | _belum diperiksa_ | |
| Ekstensi PHP (lihat tabel §1) | _belum diperiksa_ | |
| Versi MySQL/MariaDB | _belum diperiksa_ | |
| SSH/CLI + Composer, atau alur deploy lain | _belum diperiksa_ | |
| Document root dapat diarahkan ke `public` | _belum diperiksa_ | |
| Cron per menit (scheduler + `queue:work --stop-when-empty`) | _belum diperiksa_ | |
| Folder privat di luar web root (storage) | _belum diperiksa_ | |
| HTTPS, SMTP/API keluar ke penyedia email | _belum diperiksa_ | |
| `max_allowed_packet`, batas waktu eksekusi, batas unggah | _belum diperiksa_ | |

## 3. Keputusan skema yang bergantung pada hasil

- **Tipe PK:** `BIGINT UNSIGNED` auto-increment (rekomendasi `database-conventions.md` §2) dipakai mulai sekarang dan terbukti berjalan di MariaDB 10.4. Ini tidak bergantung pada vendor mana pun yang mendukung MySQL/MariaDB modern, tetapi **tetap perlu dikonfirmasi ulang saat versi database hosting diketahui**; bila hosting hanya menyediakan versi yang tidak didukung Laravel 13, hosting itu tidak memenuhi syarat (bukan skema yang diubah).
- **Fitur database:** tidak memakai CHECK constraint, tipe/fungsi JSON khusus, atau window function (DEC-059). Tidak ada perubahan.
- Driver koneksi aplikasi `mysql` (kompatibel dengan MariaDB).

## 4. Yang masih harus dikerjakan

Task 0.1 **belum bisa ditandai selesai**: kriteria "keputusan tipe PK dan fitur database dikonfirmasi" memerlukan versi database hosting nyata. Lanjutkan saat vendor hosting dipilih (OD-01).
