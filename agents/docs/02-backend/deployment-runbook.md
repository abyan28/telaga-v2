# Deployment Runbook (Rancangan)

Status: belum menjadi prosedur produksi final. Sesuaikan hanya setelah vendor dan paket hosting dipilih dan staging diuji.

## 0. Pemeriksaan kompatibilitas minimal sebelum Tahap 0

Sebelum bootstrap dan keputusan tipe PK/constraint, catat minimal: versi PHP, ekstensi PHP, versi MySQL, dukungan cron/scheduler, queue worker atau cron, akses CLI/SSH, dan storage privat. Hasilnya menentukan keputusan skema (`database-conventions.md` §2, §7). Pemeriksaan penuh tetap dilakukan pada Tahap 8.

### Syarat minimum hosting (untuk dicek ke vendor; vendor belum dipilih, DEC-053)

| Syarat | Keterangan |
|---|---|
| PHP | **8.3** (batas minimum Laravel 13 menurut dokumentasi resmi) beserta ekstensi yang dibutuhkan. Pilih versi PHP 8.3 pada panel hosting; bila hosting hanya menyediakan versi lebih rendah, hosting tersebut tidak memenuhi syarat |
| Database | MySQL atau MariaDB pada versi lama yang masih didukung Laravel 13 (periksa dokumentasi saat Tahap 0A). Banyak shared hosting memakai MariaDB; aplikasi tidak boleh bergantung pada CHECK constraint, tipe/fungsi JSON khusus, atau window function (DEC-059) |
| Akses | SSH/CLI dan Composer, atau alur build/deploy alternatif yang jelas |
| Document root | Dapat diarahkan ke folder `public` |
| Penjadwalan | Cron/scheduler dan pemrosesan queue (worker atau cron per menit) |
| Storage | Folder non-publik yang dapat ditulis, kuota cukup untuk dokumen, foto kegiatan, PDF rapor, dan backup |
| HTTPS | Sertifikat tersedia |
| Jaringan keluar | Dapat mengirim ke penyedia email (Mailjet atau sejenis) |
| Backup | Mekanisme backup database dan berkas yang dapat dijalankan |
| Impor Excel | Ekstensi PHP yang dibutuhkan pustaka pembaca .xlsx (mis. zip, xml; gd bila diperlukan) tersedia; pustaka dipilih agar jalan di shared hosting (OD-36) |
| Data referensi | Mengimpor data wilayah (±99 ribu baris) dan daftar bank sekali saat setup tanpa melebihi batas `max_allowed_packet`/waktu eksekusi (impor per batch) |
| Scheduler | Cron per menit untuk jadwal SPP sesuai tanggal terbit yang diatur admin dan `queue:work --stop-when-empty` |
| PDF | Renderer PDF yang dipilih dapat berjalan (hasil PoC) |

Bila ada syarat yang tidak terpenuhi, alternatifnya VPS kecil. Hasil pengecekan dicatat sebagai bagian Tahap 0A.

## 1. Pemeriksaan sebelum memilih hosting

- Versi PHP dan ekstensi yang dibutuhkan Laravel 13.
- Scheduler/cron untuk tugas terjadwal (pengumuman `terjadwal`, pemrosesan queue, backup).
- Ketersediaan MySQL dan versi/konfigurasi server.
- CLI/SSH/Composer atau workflow build/deploy alternatif.
- Pengaturan document root ke direktori `public`.
- Akses environment/secret yang aman.
- HTTPS, cron, queue runner, outbound email, batas CPU/memori/proses.
- Penyimpanan berkas dan batas unggah.
- PDF renderer serta dependensinya.
- Mekanisme backup dan pemulihan.

## 2. Tahapan lingkungan

1. Lokal: implementasi fitur dan test otomatis.
2. Staging: konfigurasi mendekati produksi dengan data sintetis; validasi migration, upload, email, queue, PDF dan permission.
3. Produksi: deployment terkendali setelah acceptance checklist lulus.

Jangan menggunakan data anak asli pada staging kecuali ada dasar, pengamanan dan persetujuan yang sesuai.

## 3. Checklist deployment

- [ ] Backup database dan berkas saat ini bila ada perubahan pada sistem yang sudah berjalan.
- [ ] Pastikan release yang akan dipasang sudah melalui quality gates.
- [ ] Konfigurasi environment production, tidak menyalin `.env` lokal.
- [ ] Install dependency sesuai lockfile dan environment yang didukung.
- [ ] Build asset frontend secara reproducible pada pipeline/lokal yang disetujui.
- [ ] Jalankan migration yang telah ditinjau dan kompatibel dengan strategi deployment.
- [ ] Bersihkan/rebuild cache konfigurasi/rute/view sesuai panduan Laravel dan batas hosting.
- [ ] Pastikan storage link/permissions aman dan file privat tidak berada di public root.
- [ ] Pastikan cron/queue berjalan bila dipakai.
- [ ] Uji login, authorization, upload privat, email, PDF dan transaksi dengan akun/data uji.
- [ ] Periksa log setelah deployment.

## 4. Rollback

Setiap rilis memiliki identitas versi. Untuk kegagalan aplikasi, lakukan rollback kode/assets sesuai mekanisme hosting. Untuk perubahan database, rollback tidak otomatis aman; utamakan migration yang kompatibel maju/mundur sesuai analisis, atau restore bila prosedur dan dampaknya sudah dinilai. Jangan menjalankan rollback destruktif secara membabi buta pada data produksi.

## 5. Shared hosting

Runbook final harus menjelaskan bagaimana deploy tanpa hak root, cara menjadwalkan tugas, cara menjalankan/menjadwalkan queue, lokasi log, batas ukuran unggah, prosedur backup database, serta solusi bila PDF driver tidak tersedia. Semua harus diuji pada paket hosting aktual, bukan diasumsikan dari nama vendor.
