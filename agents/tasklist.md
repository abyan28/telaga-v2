# Tasklist Pengembangan TELAGA v2

**Progress: 2,8%** (10 dari 357 poin selesai)
**Terakhir diperbarui:** 2026-10-10 (Tahap 0 dikerjakan kecuali Task 0.1 yang menunggu pemilihan hosting)

## Titik lanjut

- **Task berikutnya:** Task 1.1 (Tahap 1). Task 0.1 dan 0.9 menunggu vendor hosting dipilih (OD-01); tidak memblokir Tahap 1.
- **Task setengah jalan (`[~]`):** Task 0.1 (bagian lokal selesai, bagian hosting kosong) dan Task 0.9 (semua kriteria lulus kecuali catatan 0.1 yang lengkap).
- **Catatan serah-terima:** aplikasi ada di akar `D:/Laravel/Telaga` (DEC-100). Database lokal MariaDB XAMPP (`telaga_v2`, `telaga_v2_test`, user `telaga_dev`; kata sandi hanya di `.env` lokal). Repository git sudah di-`init` (cabang `main`) **tanpa commit**; belum ada commit karena belum diminta pemilik. Terjemahan Indonesia (`lang/id`) belum ada, dikerjakan bersama pesan error Tahap 1. Agen yang berhenti karena limit wajib memperbarui bagian ini (task `[~]`, kondisi file, langkah berikutnya) sebelum melapor.

## Cara memakai (ringkasan aturan di `AGENTS.md`)

- `[ ]` belum dikerjakan · `[~]` terhenti di tengah (catat kondisinya) · `[✓] ✅` selesai (tes relevan sudah dijalankan dan lulus).
- Bobot kesulitan: `[Mudah]` = 1, `[Sedang]` = 2, `[Sulit]` = 3. Progress = bobot task selesai ÷ bobot semua task × 100%. Task dibatalkan atau digabung tidak dihitung.
- Agen: **Claude** mengerjakan Backend, Integrasi, dan QA; **Gemini** mengerjakan UI/UX. Lihat bagian "Pembagian agen" di bawah.
- Perbarui file ini setiap selesai satu task, sebelum melapor. Tambahkan catatan file yang dibuat/diubah di bawah task, contoh:
  ```markdown
  - [✓] ✅ Task 1.2 - Migrasi dan model identitas `[Sedang]` (Selesai)
    - Membuat `database/migrations/xxxx_create_orang_table.php`
  ```
- Urutan fitur: kontrak (props/route/tipe) → Backend → UI/UX (paralel, memakai fixture sementara) → Integrasi → QA (DEC-076). Fixture hanya untuk pengembangan.
- Task yang diblokir keputusan terbuka diberi rujukan OD. Jangan menebak; catat dan lanjutkan task lain.

## Pembagian agen

Ringkasan: **Claude** 132 task (298 poin) · **Gemini** 27 task (55 poin) · **Keduanya** 2 task (4 poin). Total 161 task, 357 poin. Gemini hanya mengerjakan UI/UX (halaman dan komponen React); semua yang berkaitan dengan database, aturan bisnis, otorisasi, integrasi, dan QA dikerjakan Claude.

- **Claude (Backend, Integrasi, QA):** semua task lain di luar dua daftar di bawah. Task berlabel "Claude (Backend); Gemini meninjau" adalah kontrak: Claude menulisnya, Gemini meninjau sebelum UI dibangun.
- **Gemini (UI/UX):** Task 1.18, 1.19, 1.20, 1.21, 1.22, 1.29, 1.30, 2.3, 2.7, 2.17, 2.18, 2.33, 2.34, 3.11, 3.12, 4.14, 4.15, 4.16, 4.22, 5.9, 5.10, 6.12, 6.13, 6.14, 7.4, 7.5, 7.11.
- **Keduanya:** Task 7.6 (dashboard: Claude menyediakan data, Gemini membangun tampilan) dan Task 8.8 (uji aksesibilitas: Claude menguji, Gemini memperbaiki UI).
- Gemini tidak boleh mengubah controller, route, migrasi, atau kebijakan akses; Claude tidak menulis halaman dan komponen React kecuali untuk integrasi minimal dan atas persetujuan pemilik (kepemilikan file di `docs/04-ai-agents/workflow.md`).
- Task Gemini bergantung pada kontrak dari Claude (task "Kontrak" tiap tahap). Gemini memakai fixture sementara sampai Task Integrasi tahap itu selesai.
- Task 1.30 dan 7.11: landing page dan dashboard admin dirancang baru dan disetujui pemilik lewat prototipe sebelum diterapkan (DEC-095).
---

## Tahap 0 — Pemeriksaan hosting minimal dan bootstrap

- [~] Task 0.1 - Catat hasil pemeriksaan kompatibilitas hosting minimal (PHP 8.3, versi MySQL/MariaDB, cron/scheduler, queue, SSH, storage privat) `[Mudah]` · Agen: Claude (Integrasi) · Aturan: BR-OPS-003, BR-OPS-005, OD-01, OD-02
  - Selesai bila: hasil tercatat di dokumen; keputusan tipe PK dan fitur database dikonfirmasi.
    - Sebagian: `docs/05-perencanaan/hasil-pemeriksaan-lingkungan.md` memuat hasil lokal dan tabel hosting yang masih kosong. Selesaikan saat vendor hosting dipilih (OD-01).
- [✓] ✅ Task 0.2 - Siapkan repository bersih dan strategi branch/review `[Mudah]` · Agen: Claude (Backend)
  - Selesai bila: repo, aturan branch, dan pedoman review tertulis.
    - Membuat `agents/docs/02-backend/repository-and-review.md`; `git init -b main` di `D:/Laravel/Telaga`; `.gitignore` menambah `/referensi`. Belum ada commit.
- [✓] ✅ Task 0.3 - Pasang starter kit Laravel 13 React (Inertia, TypeScript, Tailwind, shadcn/ui) `[Sedang]` · Agen: Claude (Backend) · Butuh: 0.1, 0.2
  - Selesai bila: aplikasi baru berjalan lokal dan build berhasil.
    - Memasang `laravel/react-starter-kit` (branch utama: Laravel 13.35, Inertia 3, React 19, Tailwind 4, Vite 8) di akar repo; `composer.json` dikunci `config.platform.php=8.3.0` lalu `composer update -W` agar kompatibel PHP 8.3. Bukti: `npm run build` berhasil, `/up` dan `/login` 200.
- [✓] ✅ Task 0.4 - Siapkan MySQL lokal, `.env.example` tanpa secret, dan koneksi database `[Mudah]` · Agen: Claude (Backend) · Butuh: 0.3
  - Selesai bila: koneksi tervalidasi; tidak ada secret di repo.
    - `.env.example` (MySQL, tanpa secret, locale id), `.env` lokal (gitignored), `phpunit.xml` memakai `telaga_v2_test` di MariaDB. Database dan user dibuat di MariaDB 10.4.32 XAMPP. Perbaikan tabel Aria sistem XAMPP yang rusak (cadangan: `D:/Laravel/xampp-data-backup-20261010`).
- [✓] ✅ Task 0.5 - Tetapkan formatter, lint, static analysis, test runner, lockfile, dan pipeline build `[Sedang]` · Agen: Claude (Backend) · Butuh: 0.3
  - Selesai bila: perintah format/lint/tes/build berjalan dan terdokumentasi.
    - Mengubah `composer.json` (phpstan `--memory-limit=1G`, platform 8.3), `vite.config.ts` (fmt mengabaikan `agents/`, `audit/`, `referensi/`), `tsconfig.json` (kecualikan kode wayfinder yang dihasilkan), `.github/workflows/tests.yml` (layanan MariaDB; belum dijalankan di GitHub). Perintah di `README.md`. `composer ci:check` lulus: pint, phpstan, vite-plus check, tsc, 39 tes.
- [✓] ✅ Task 0.6 - Rekonsiliasi nama tabel/PK/autentikasi bawaan starter kit dengan DEC-028; buat satu migrasi contoh `[Sedang]` · Agen: Claude (Backend) · Aturan: DEC-028, OD-08 · Butuh: 0.3, 0.4
  - Selesai bila: model `pengguna` dengan PK `id_pengguna` dan login bawaan berfungsi; keputusan dicatat.
    - Membuat `app/Models/Pengguna.php`, `database/factories/PenggunaFactory.php`, `database/migrations/0001_01_01_000000_create_pengguna_table.php` (menggantikan `users`); mengubah `config/auth.php`, `config/fortify.php`, `FortifyServiceProvider`, `AppServiceProvider` (kata sandi min 12), `routes/settings.php`, controller/request/concern pengaturan, seeder.
    - Menghapus registrasi publik, 2FA, passkey, hapus akun (DEC-041, DEC-071) beserta halaman/komponen React, migrasi, dan tes terkait; mengedit halaman `login`, `welcome`, `confirm-password`, `profile`, `security` seperlunya.
    - Tes: login, reset sandi, verifikasi email, ganti sandi (min 12), rute terlarang tidak ada, `pengguna`/`id_pengguna`. Keputusan: DEC-100. Catatan: OD-08 (paket RBAC) tidak terpengaruh; kolom `id_orang`/`username`/`no_hp` menyusul Tahap 1.
- [✓] ✅ Task 0.7 - Health check, penanganan error, dan zona waktu Asia/Jakarta `[Mudah]` · Agen: Claude (Backend) · Aturan: BR-OPS-001 · Butuh: 0.3
  - Selesai bila: endpoint health check, halaman error tanpa stack trace, zona waktu satu tempat.
    - `/up` (bawaan Laravel) diuji; `config/app.php` timezone `Asia/Jakarta`; `resources/views/errors/{layout,403,404,419,429,500,503}.blade.php` berbahasa Indonesia; `tests/Feature/OperationalTest.php` (4 tes).
- [✓] ✅ Task 0.8 - Tulis dokumentasi menjalankan proyek di lokal `[Mudah]` · Agen: Claude (Integrasi) · Butuh: 0.3–0.7
  - Selesai bila: orang baru dapat menjalankan proyek dari dokumentasi.
    - Membuat `README.md` (prasyarat, langkah lokal, perintah harian). Langkah dijalankan berurutan oleh agen; belum diuji oleh orang lain.
- [~] Task 0.9 - Gerbang Tahap 0 `[Mudah]` · Agen: Claude (QA) · Butuh: 0.1–0.8
  - Selesai bila: aplikasi berjalan, build dan tes kosong lulus, koneksi MySQL valid, catatan 0.1 tersimpan, DEC-028 diterapkan.
    - Terverifikasi 2026-10-10: `composer ci:check` lulus (39 tes), `/up` 200, koneksi MariaDB valid, DEC-028 diterapkan pada `pengguna`. Tersisa hanya kelengkapan catatan 0.1 (hosting).

## Tahap 1 — Fondasi keamanan, identitas, dan layanan dasar

- [ ] Task 1.1 - Tulis konvensi kontrak: props bersama, tipe TypeScript, format error `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 0.9
  - Selesai bila: `api-contract-conventions.md` diterapkan pada kerangka; berkas tipe bersama ada.
- [ ] Task 1.2 - Migrasi dan model `orang`, `pengguna`, `profil_wali`, `siswa`, `pegawai` `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-ID-001, BR-ID-002 · Butuh: 0.9
  - Selesai bila: tabel dan relasi sesuai data dictionary; akun wajib terkait satu orang.
- [ ] Task 1.3 - Migrasi `pengaturan_sistem`, riwayat pengaturan, `profil_sekolah`, `penomoran` dan seed kunci awal `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-FIN-007, BR-FIN-008, BR-OPS-001 · Butuh: 1.2
  - Selesai bila: perubahan pengaturan tercatat; penomoran aman dari nomor ganda.
- [ ] Task 1.4 - Migrasi dan layanan `audit_log` append-only `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AUD-001–003 · Butuh: 1.2
  - Selesai bila: peristiwa BR-AUD-002 tercatat tanpa rahasia; tidak ada endpoint ubah/hapus.
- [ ] Task 1.5 - Registrasi mandiri wali (orang baru, respons tidak membocorkan email) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-ID-007, BR-ID-015, BR-AUTH-011 · Butuh: 1.2
  - Selesai bila: hanya wali yang dapat mendaftar sendiri; kemungkinan kecocokan ditandai.
- [ ] Task 1.6 - OTP: tabel `kode_verifikasi`, generator aman, TTL, percobaan, jeda kirim ulang, rate limit `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-AUTH-002–010 · Butuh: 1.2, 1.15
  - Selesai bila: kode kedaluwarsa/salah/dipakai ulang ditolak; tidak ada OTP di log.
- [ ] Task 1.7 - Verifikasi email dengan OTP dan status akun `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-ID-009 · Butuh: 1.5, 1.6
  - Selesai bila: akun belum terverifikasi tidak dapat login.
- [ ] Task 1.8 - Login, logout, regenerasi sesi, throttle login, pencabutan sesi akun nonaktif `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AUTH-013, BR-AUTH-014 · Butuh: 1.7
  - Selesai bila: akun ditangguhkan/dinonaktifkan kehilangan sesi.
- [ ] Task 1.9 - Reset kata sandi (OTP lalu token sekali pakai) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AUTH-009 · Butuh: 1.6, 1.8
  - Selesai bila: token sekali pakai dan kedaluwarsa; sesi lain dicabut setelah reset.
- [ ] Task 1.10 - Undangan akun staf (tautan sekali pakai, TTL 48 jam) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AUTH-016 · Butuh: 1.8, 1.12
  - Selesai bila: undangan baru membatalkan yang lama; kata sandi minimum 12 karakter.
- [ ] Task 1.11 - Antrean tinjauan kecocokan `orang` dan penautan manual `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-ID-015 · Butuh: 1.5, 1.12
  - Selesai bila: tidak ada penggabungan otomatis; tindakan diaudit.
- [ ] Task 1.12 - Role, permission, katalog `modul.objek.tindakan`, dan seed role default `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-ID-004–006, DEC-075 · Butuh: 1.2
  - Selesai bila: role default sesuai `permission-matrix.md`.
- [ ] Task 1.13 - Policy dan scope data dasar (guru-kelas, wali-anak, pendaftaran sendiri) `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-ID-005, BR-ID-011, BR-ID-014 · Butuh: 1.12
  - Selesai bila: manipulasi ID dan akses lintas kelas/wali ditolak.
- [ ] Task 1.14 - Perlindungan role (tidak naik role sendiri; Admin Utama minimal dua dilindungi) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-ID-012, BR-ID-013 · Butuh: 1.12
  - Selesai bila: pengguna tidak dapat mengubah role sendiri; perubahan role diaudit.
- [ ] Task 1.15 - Email (Mailjet development), queue database, job idempoten dengan retry berbatas `[Sedang]` · Agen: Claude (Backend) · Butuh: 0.9
  - Selesai bila: email OTP terkirim lewat queue di lingkungan dev; kegagalan tercatat tanpa rahasia.
- [ ] Task 1.16 - Penyimpanan berkas privat: dokumen, versi, verifikasi per versi, unduhan terotorisasi `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-DOK-001–003, BR-DOK-007, BR-DOK-008 · Butuh: 1.13
  - Selesai bila: berkas privat tidak dapat diunduh anonim; allowlist dan batas ukuran ditegakkan.
- [ ] Task 1.17 - Notifikasi portal dasar `[Sedang]` · Agen: Claude (Backend) · Butuh: 1.8
  - Selesai bila: notifikasi per pengguna dengan status dibaca.
- [ ] Task 1.18 - Token desain, kerangka aplikasi (sidebar/drawer, layout internal dan portal wali), komponen dasar `[Sedang]` · Agen: Gemini (UI/UX) · Aturan: WCAG 2.1 AA · Butuh: 1.1
  - Selesai bila: layout responsif, komponen dasar dengan state loading/error/kosong; tsc, lint, build lulus.
- [ ] Task 1.19 - Halaman auth: daftar, login, OTP, reset, terima undangan `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 1.18
  - Selesai bila: semua halaman memakai kontrak; pesan error berbahasa Indonesia.
- [ ] Task 1.20 - Halaman kelola pengguna, undangan, role, dan tinjauan kecocokan `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 1.18
  - Selesai bila: halaman memakai props kontrak dan fixture bertanda sementara.
- [ ] Task 1.21 - Halaman pengaturan sistem, profil sekolah, dan lihat audit log `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 1.18
  - Selesai bila: perubahan pengaturan menampilkan konfirmasi.
- [ ] Task 1.22 - Prototipe klik layar kunci (formulir PPDB, absen pegawai di ponsel, rapor, pembayaran) untuk umpan balik pegawai RA `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 1.18
  - Selesai bila: pemilik/pegawai RA sudah meninjau; catatan umpan balik tersimpan. Bukan kode produksi.
- [ ] Task 1.23 - Integrasi Tahap 1: ganti fixture dengan data nyata; uji alur browser penuh `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 1.19–1.21
  - Selesai bila: alur akun wali dan staf berjalan di browser.
- [ ] Task 1.25 - Impor sekali data referensi: wilayah resmi (provinsi–kelurahan) dan daftar bank, per batch `[Sedang]` · Agen: Claude (Backend) · Aturan: OD-38, BR-SIS-013, BR-FIN-021 · Butuh: 1.2
  - Selesai bila: data terimpor tanpa melebihi batas hosting; sumber dan lisensi tercatat; tabel hanya dibaca aplikasi.
- [ ] Task 1.26 - Login tiga identitas (email, no. HP, username): normalisasi HP, username unik, cek ketersediaan langsung `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AUTH-001, BR-SIS-014 · Butuh: 1.8
  - Selesai bila: login berhasil lewat ketiganya; pesan kesalahan seragam; throttle berlaku.
- [ ] Task 1.27 - Kode aktivasi akun wali lama: tabel, penerbitan admin, throttle, aktivasi (kata sandi, username, email OTP) `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-AUTH-017, DEC-083 · Butuh: 1.6, 1.26
  - Selesai bila: kode sekali pakai dan kedaluwarsa; tidak ada kata sandi awal dari NIK; kode baru membatalkan lama.
- [ ] Task 1.28 - Aturan validasi terpusat (HP/nama/NIK/NUPTK) dan huruf kapital otomatis dengan daftar pengecualian `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-014, BR-UI-001 · Butuh: 1.1
  - Selesai bila: satu titik aturan dipakai semua form dan impor; tiap pengecualian kapital teruji.
- [ ] Task 1.29 - UI login tiga identitas dan aktivasi akun wali `[Sedang]` · Agen: Gemini (UI/UX) · Aturan: BR-AUTH-001, BR-AUTH-017 · Butuh: 1.18
  - Selesai bila: halaman memakai kontrak; pesan Indonesia; ramah ponsel.
- [ ] Task 1.30 - Desain baru landing page dan dashboard admin (elegan, simpel, modern) beserta prototipe untuk ditinjau pemilik `[Sedang]` · Agen: Gemini (UI/UX) · Aturan: DEC-095, BR-UI-002 · Butuh: 1.18
  - Selesai bila: pemilik menyetujui prototipe; tidak menyalin tata letak sistem lama.
- [ ] Task 1.24 - Gerbang Tahap 1: tes keamanan `security-baseline.md` §7 `[Sulit]` · Agen: Claude (QA) · Butuh: 1.1–1.23, 1.25–1.30
  - Selesai bila: skenario akun, akses role, dan otorisasi negatif lulus; upload privat tidak dapat diunduh anonim.

## Tahap 2 — Data master, tagihan/pembayaran dasar, dan PPDB

- [ ] Task 2.1 - Kontrak master data, tagihan, dan PPDB (route, props, tipe) `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 1.24
  - Selesai bila: kontrak tertulis di tugas dan berkas tipe.
- [ ] Task 2.2 - Master: tahun ajaran (aktif tunggal), periode pembelajaran (+cut-off), ruangan, kelas (+fase), jenis penugasan `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AKD-001–003, BR-RAP-016 · Butuh: 2.1
  - Selesai bila: aktivasi tahun ajaran kedua ditolak sesuai aturan.
- [ ] Task 2.3 - UI master data `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 2.1
  - Selesai bila: daftar/form dengan filter, paginasi, empty state.
- [ ] Task 2.4 - Jenis tagihan, tagihan (siswa XOR pendaftaran), generate idempoten `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-015–017 · Butuh: 2.2
  - Selesai bila: generate dua kali tidak menggandakan; tagihan terikat tepat satu pemilik.
- [ ] Task 2.5 - Pembayaran tunai dan non-tunai (bukti), verifikasi, posting atomik `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-009–013 · Butuh: 2.4, 1.16
  - Selesai bila: menunggu verifikasi tidak membuat mutasi resmi; rollback teruji.
- [ ] Task 2.6 - Alokasi pembayaran (usulan otomatis terlama, ubah sebelum posting, batas pembayaran dan sisa tagihan) `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-002–004, BR-FIN-020 · Butuh: 2.5
  - Selesai bila: tes batas dan konkurensi lulus.
- [ ] Task 2.7 - UI tagihan dan pembayaran (wali dan bendahara) `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 2.1
  - Selesai bila: form uang menampilkan nominal dan konsekuensi; cegah submit ganda.
- [ ] Task 2.8 - Periode pendaftaran, persyaratan dokumen, penanda dipublikasikan `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-PPDB-001, BR-PPDB-006, BR-CMS-003 · Butuh: 2.2
  - Selesai bila: periode lama tetap tersimpan.
- [ ] Task 2.9 - Pendaftaran: data calon, wali, nomor `PPDB-{tahun}-{urut}`, peringatan duplikat `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-PPDB-002, 003, 011, 015 · Butuh: 2.8
  - Selesai bila: satu pengajuan satu calon; pelamar ditolak tidak membuat `orang`.
- [ ] Task 2.10 - Unggah dokumen PPDB dan verifikasi per versi `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-PPDB-006, BR-DOK-008 · Butuh: 2.9, 1.16
  - Selesai bila: dokumen wajib menjadi prasyarat transisi.
- [ ] Task 2.11 - Transisi status pendaftaran, riwayat, prasyarat biaya lunas `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-PPDB-004, 009, 014 · Butuh: 2.9, 2.5
  - Selesai bila: tidak dapat masuk pemeriksaan sebelum biaya lunas terverifikasi.
- [ ] Task 2.12 - Keputusan penerimaan idempoten: `orang`, `siswa`, hubungan wali terverifikasi, tagihan daftar ulang `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-PPDB-005, 007, BR-FIN-019 · Butuh: 2.11
  - Selesai bila: retry tidak menggandakan `orang`/siswa/tagihan.
- [ ] Task 2.13 - Verifikasi wali tambahan dengan KK/akta `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-002, BR-ID-011 · Butuh: 2.12
  - Selesai bila: akses wali hanya setelah terverifikasi.
- [ ] Task 2.14 - Aksi “Buat NIS” massal `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-011 · Butuh: 2.12, 1.3
  - Selesai bila: urut abjad, susulan lanjut nomor, NIS tidak berubah, tanpa nomor ganda.
- [ ] Task 2.15 - Riwayat Penempatan Siswa dengan pencegahan tumpang tindih dan konkurensi `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-AKD-006–008 · Butuh: 2.12
  - Selesai bila: dua request bersamaan tidak membuat penempatan ganda.
- [ ] Task 2.16 - Notifikasi status PPDB ke wali (portal dan email) `[Sedang]` · Agen: Claude (Backend) · Butuh: 2.11, 1.15, 1.17
  - Selesai bila: kegagalan email tidak membatalkan transisi.
- [ ] Task 2.17 - UI PPDB wali: formulir multi-langkah, dokumen, status, tagihan pendaftaran `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 2.1
  - Selesai bila: data aman dipertahankan saat validasi gagal; responsif di ponsel.
- [ ] Task 2.18 - UI PPDB petugas: daftar, periksa, putuskan, riwayat, NIS, penempatan `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 2.1
  - Selesai bila: tindakan menampilkan konfirmasi objek yang jelas.
- [ ] Task 2.19 - Laporan LAP-05 status PPDB `[Mudah]` · Agen: Claude (Backend) · Butuh: 2.11
  - Selesai bila: sesuai `report-catalog.md`.
- [ ] Task 2.20 - Integrasi Tahap 2: alur browser penuh akun wali hingga penempatan `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 2.3, 2.7, 2.17, 2.18, 2.33, 2.34
  - Selesai bila: fixture sudah diganti data nyata.
- [ ] Task 2.22 - Pengaturan PPDB: buka/tutup, kuota, tahun ajaran target terpisah `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-PPDB-017, BR-PPDB-018 · Butuh: 2.2
  - Selesai bila: pengaturan benar-benar memengaruhi perilaku; perubahan diaudit.
- [ ] Task 2.23 - Validasi usia calon 4–6 tahun per 1 Juli tahun ajaran target `[Mudah]` · Agen: Claude (Backend) · Aturan: BR-PPDB-016 · Butuh: 2.9
  - Selesai bila: pendaftaran di luar rentang ditolak; uji PPDB Oktober untuk tahun ajaran berikutnya.
- [ ] Task 2.24 - Profil ayah dan ibu, alamat keluarga berjenjang, dan gerbang profil lengkap sebelum mendaftar `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-SIS-013, BR-PPDB-024 · Butuh: 1.25, 2.1
  - Selesai bila: wali belum lengkap tidak dapat mendaftar; satu alamat untuk semua anak.
- [ ] Task 2.25 - Data murid lengkap (profil murid, NIK, NISN, angkatan dari NIS) dan salin saat diterima `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-012, BR-SIS-011 · Butuh: 2.9
  - Selesai bila: cabang required_if berfungsi; NIK unik 16 digit.
- [ ] Task 2.26 - Izin edit wali dan Ubah Keputusan `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-PPDB-021, BR-PPDB-022 · Butuh: 2.11, 2.12
  - Selesai bila: izin edit mengunci verifikasi/keputusan; berkas diganti memundurkan status; ubah keputusan dicatat.
- [ ] Task 2.27 - Gerbang PPDB ditutup: blokir daftar baru, blokir bayar dan batalkan kelulusan, blokir login ortu tanpa anak hidup `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-PPDB-019 · Butuh: 2.12, 2.22
  - Selesai bila: perilaku teruji termasuk POST langsung dan pemulihan saat PPDB dibuka.
- [ ] Task 2.28 - Calon Murid, pembatalan, dan hitungan refund (denda dan refund) `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-008, BR-FIN-018, BR-SIS-011, DEC-079 · Butuh: 2.12, 2.5
  - Selesai bila: rumus refund benar pada contoh; calon menjadi aktif hanya setelah Buat NIS (2.14).
- [ ] Task 2.29 - Cicilan per jenis, satu bukti pending per tagihan, bank asal transfer, rekening tujuan sekolah `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-005, BR-FIN-021 · Butuh: 2.5, 1.25
  - Selesai bila: SPP/pendaftaran kurang ditolak; verifikasi idempoten; bukti kedua saat pending ditolak.
- [ ] Task 2.30 - Email otomatis dengan rincian transaksi lewat antrean `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-NTF-001 · Butuh: 1.15, 2.5, 2.12
  - Selesai bila: empat kelompok peristiwa terkirim; kegagalan tidak membatalkan transaksi.
- [ ] Task 2.31 - Impor murid lama (CSV dan Excel): template, cek format, laporan baris gagal, akun wali dengan kode aktivasi `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-IMP-001, OD-36 · Butuh: 2.24, 2.25, 1.27
  - Selesai bila: NIK duplikat dilewati; baris valid tetap diproses; kakak-adik digabung per no. HP; berjalan di hosting target.
- [ ] Task 2.32 - Wizard “Tahun ajaran berikutnya” (nama otomatis, tanggal, periode, salin kelas, pratinjau, peringatan) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-AKD-009..011, DEC-097 · Butuh: 2.2
  - Selesai bila: pratinjau tanggal bawaan dan koreksi; salin kelas opsional tanpa murid/penugasan; peringatan tidak memblokir tetapi perlu konfirmasi; satu tahun ajaran aktif; tahun lama selesai dan penempatan lama otomatis selesai; data lama utuh.
- [ ] Task 2.33 - UI admin: pengaturan PPDB, wizard tahun ajaran, Calon Murid, impor, bank dan rekening sekolah `[Sedang]` · Agen: Gemini (UI/UX) · Aturan: DEC-094, DEC-095 · Butuh: 2.1
  - Selesai bila: konfirmasi jelas untuk aksi uang dan NIS; ramah ponsel.
- [ ] Task 2.34 - UI wali: profil ayah/ibu, alamat berjenjang, formulir pendaftaran lengkap, tab antar-anak, edit/izin edit, unggah bukti dengan bank asal `[Sulit]` · Agen: Gemini (UI/UX) · Aturan: BR-SIS-012, BR-SIS-013, BR-PPDB-023 · Butuh: 2.1
  - Selesai bila: data aman dipertahankan saat validasi gagal; thumbnail berkas; responsif.
- [ ] Task 2.35 - Seeder data uji sintetis (±80 murid dan ortu, 20 guru, transaksi pada beberapa skenario status) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-OPS-007 · Butuh: 2.12, 2.5
  - Selesai bila: idempoten; tanpa data nyata; menutupi skenario status PPDB dan pembayaran.
- [ ] Task 2.21 - Gerbang Tahap 2 `[Sulit]` · Agen: Claude (QA) · Butuh: 2.1–2.20, 2.22–2.35
  - Selesai bila: alur PPDB end-to-end, multi-anak, retry penerimaan, dan penempatan bersamaan lulus.

## Tahap 3 — Kesiswaan dan kehadiran

- [ ] Task 3.1 - Kontrak kesiswaan, pegawai, dan absensi `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 2.21
  - Selesai bila: kontrak tertulis.
- [ ] Task 3.2 - Profil siswa dan wali dengan field terbatas per role `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-001–003 · Butuh: 3.1
  - Selesai bila: field sensitif tidak terkirim ke browser tanpa hak.
- [ ] Task 3.3 - Absensi harian siswa (unik siswa+tanggal) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-004, 005 · Butuh: 3.1
  - Selesai bila: absensi ganda ditolak meski kelas berbeda.
- [ ] Task 3.4 - Profil kesehatan, pengukuran pertumbuhan, dropdown keterangan DDTK `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-006–009, BR-RAP-018 · Butuh: 3.1
  - Selesai bila: koreksi tidak menghapus histori.
- [ ] Task 3.5 - Portofolio privat `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-SIS-010 · Butuh: 3.1, 1.16
  - Selesai bila: foto anak tidak tampil publik.
- [ ] Task 3.6 - Pegawai, jabatan, riwayat jabatan `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-PEG-001–004 · Butuh: 3.1
  - Selesai bila: perubahan jabatan membuat histori baru.
- [ ] Task 3.7 - Penugasan akademik (wali kelas, guru pendamping) dan scope akses guru `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-ID-017, BR-AKD-004 · Butuh: 3.6
  - Selesai bila: pendamping tidak dapat setor/menyusun rapor.
- [ ] Task 3.8 - Absen mandiri pegawai: GPS, waktu server, jam masuk 07.00, terlambat, Senin–Jumat `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-PEG-008, BR-PEG-010 · Butuh: 3.6, 1.3
  - Selesai bila: di luar radius ditolak; satu catatan per hari; koordinat hanya terlihat admin dan aksesnya diaudit.
- [ ] Task 3.9 - Pencatatan dan koreksi absensi pegawai oleh admin (alasan wajib, diaudit) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-PEG-009 · Butuh: 3.8
  - Selesai bila: pegawai tanpa akun dapat dicatat; koreksi tanpa alasan ditolak.
- [ ] Task 3.10 - Laporan LAP-01 (absensi siswa) dan LAP-06 (absensi pegawai) `[Sedang]` · Agen: Claude (Backend) · Butuh: 3.3, 3.8
  - Selesai bila: sesuai `report-catalog.md`; scope per role.
- [ ] Task 3.11 - UI kesiswaan: daftar, detail, absensi yang ramah ponsel, kesehatan, portofolio `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 3.1
  - Selesai bila: tabel berat berubah menjadi daftar/kartu di ponsel.
- [ ] Task 3.12 - UI pegawai, penugasan, dan “Absen Saya” di ponsel `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 3.1
  - Selesai bila: izin lokasi, ditolak di luar area, dan pesan bantuan ke admin jelas.
- [ ] Task 3.13 - Integrasi Tahap 3 `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 3.2–3.12
  - Selesai bila: alur browser penuh; uji di ponsel nyata dengan HTTPS.
- [ ] Task 3.14 - Gerbang Tahap 3 `[Sulit]` · Agen: Claude (QA) · Butuh: 3.1–3.13
  - Selesai bila: akses lintas kelas/wali diuji, histori penempatan terjaga, absensi unik.

## Tahap 4 — Keuangan dan tabungan

- [ ] Task 4.1 - Kontrak keuangan dan tabungan `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 3.14
  - Selesai bila: kontrak tertulis.
- [ ] Task 4.2 - Tagihan bulanan SPP: generate idempoten dengan pemeriksaan periode dan penempatan `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-015, BR-FIN-016, BR-FIN-007 · Butuh: 4.1
  - Selesai bila: SPP bawaan dari pengaturan; tanpa diskon/prorata.
- [ ] Task 4.3 - Reversal pembayaran, alokasi, dan mutasi `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-013, 014, BR-KEU-008 · Butuh: 4.1
  - Selesai bila: transaksi terposting tidak dapat diubah/hapus; reversal tertaut asal.
- [ ] Task 4.4 - Penyelesaian refund daftar ulang: persetujuan Admin Sekolah/Bendahara, posting mutasi keuangan (hitungan denda ada di Task 2.28) `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-FIN-008, BR-FIN-018, BR-FIN-019 · Butuh: 4.3
  - Selesai bila: refund tertaut pembayaran asal dan menghasilkan mutasi; perubahan persentase diaudit.
- [ ] Task 4.5 - Akun keuangan, kategori, metode pembayaran, mutasi kas/bank `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-KEU-001–009 · Butuh: 4.1
  - Selesai bila: mutasi memiliki FK sumber eksplisit.
- [ ] Task 4.6 - Transfer antar-akun atomik `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-KEU-003, 007 · Butuh: 4.5
  - Selesai bila: transfer bukan pemasukan/pengeluaran di laporan.
- [ ] Task 4.7 - Rekening tabungan per siswa `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-TAB-001, 002 · Butuh: 4.1
  - Selesai bila: satu rekening per siswa.
- [ ] Task 4.8 - Setoran: batch, rincian `nominal_diajukan`/`nominal_diterima`, status `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-TAB-003–008 · Butuh: 4.7, 3.7
  - Selesai bila: setoran wali kelas belum mengubah saldo resmi.
- [ ] Task 4.9 - Posting setoran, mutasi tabungan, penanganan selisih `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-TAB-005, 007, 010, 011 · Butuh: 4.8
  - Selesai bila: yang diposting `nominal_diterima`; posting dua kali tidak menggandakan.
- [ ] Task 4.10 - Penarikan tabungan (saldo tidak negatif, penguncian rekening) `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-TAB-009, BR-TAB-012 · Butuh: 4.9
  - Selesai bila: dua penarikan bersamaan tidak membuat saldo negatif.
- [ ] Task 4.11 - Reversal mutasi tabungan `[Sedang]` · Agen: Claude (Backend) · Butuh: 4.9
  - Selesai bila: tertaut mutasi asal dengan alasan.
- [ ] Task 4.12 - Rekonsiliasi saldo dengan ledger `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-KEU-006 · Butuh: 4.6, 4.9
  - Selesai bila: saldo dapat direkonsiliasi dari mutasi.
- [ ] Task 4.13 - Laporan LAP-02, LAP-03, LAP-04 `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-KEU-009 · Butuh: 4.2, 4.6, 4.9
  - Selesai bila: dapat ditelusuri ke mutasi sumber.
- [ ] Task 4.14 - UI bendahara: verifikasi, posting, kas/bank, transfer, refund `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 4.1
  - Selesai bila: aksi uang menampilkan nominal dan konsekuensi.
- [ ] Task 4.15 - UI setoran wali kelas dan rekonsiliasi bendahara `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 4.1
  - Selesai bila: selisih ditampilkan jelas.
- [ ] Task 4.16 - UI wali: tagihan, pembayaran, saldo/mutasi tabungan (hanya lihat) `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 4.1
  - Selesai bila: tidak ada permohonan penarikan di portal.
- [ ] Task 4.17 - Tes konkurensi dan idempotensi keuangan `[Sulit]` · Agen: Claude (QA) · Butuh: 4.2–4.12
  - Selesai bila: alokasi, posting, transfer, dan penarikan bersamaan aman.
- [ ] Task 4.18 - Integrasi Tahap 4 `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 4.14–4.16
  - Selesai bila: alur browser penuh bendahara, wali kelas, wali.
- [ ] Task 4.20 - Jadwal terbit SPP sesuai pengaturan tanggal (1–28) dengan scheduler nyata dan tombol manual `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-FIN-022, DEC-090 · Butuh: 4.2
  - Selesai bila: tanggal pengaturan benar-benar dipakai; idempoten; email hanya tagihan baru.
- [ ] Task 4.21 - Ekspor CSV dan PDF: data murid, laporan SPP, transaksi, dengan filter dan audit `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-IMP-002 · Butuh: 4.13
  - Selesai bila: mengikuti scope pengguna; tercatat di audit.
- [ ] Task 4.22 - UI ekspor dan pengaturan SPP/rekening `[Sedang]` · Agen: Gemini (UI/UX) · Aturan: BR-IMP-002 · Butuh: 4.1
  - Selesai bila: filter jelas; unduhan terkonfirmasi.
- [ ] Task 4.19 - Gerbang Tahap 4 `[Sulit]` · Agen: Claude (QA) · Butuh: 4.1–4.18, 4.20–4.22
  - Selesai bila: invarian finansial, double submit, rollback, dan akses bendahara lulus.

## Tahap 5 — Dokumen lanjutan, surat, kalender, pengumuman

- [ ] Task 5.1 - Kontrak dokumen, surat, kalender, pengumuman `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 4.19
  - Selesai bila: kontrak tertulis.
- [ ] Task 5.2 - Dokumen lanjutan: junction eksplisit (siswa, pegawai, pendaftaran, pembayaran, surat) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-DOK-004 · Butuh: 5.1
  - Selesai bila: FK ditegakkan; akses sesuai konteks.
- [ ] Task 5.3 - Jenis surat, template berversi, penomoran `urut/kode/bulan romawi/tahun` `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-DOK-005, 006 · Butuh: 5.1, 1.3
  - Selesai bila: template baru tidak mengubah surat lama; nomor tidak ganda.
- [ ] Task 5.4 - Spike: pembuat PDF dan .docx yang berjalan di hosting target `[Sulit]` · Agen: Claude (Backend) · Aturan: OD-07, DEC-062 · Butuh: 0.1
  - Selesai bila: pilihan pustaka dicatat sebagai keputusan.
- [ ] Task 5.5 - Surat terbit: snapshot data, keluaran PDF dan .docx privat `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-DOK-009 · Butuh: 5.3, 5.4
  - Selesai bila: kedua format terbentuk dan tidak dapat diunduh anonim.
- [ ] Task 5.6 - Kalender akademik dan agenda `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-KAL-001 · Butuh: 5.1
  - Selesai bila: agenda publik hanya yang dipublikasikan.
- [ ] Task 5.7 - Pengumuman satu arah: target, versi, penjadwalan (scheduler), daftar penerima `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-KOM-001–005 · Butuh: 5.1, 1.15
  - Selesai bila: perubahan setelah terbit membuat versi baru; tidak ada fitur balasan.
- [ ] Task 5.8 - Email massal lewat queue dan riwayat pengiriman `[Sedang]` · Agen: Claude (Backend) · Butuh: 5.7
  - Selesai bila: kegagalan email tidak membatalkan pengumuman.
- [ ] Task 5.9 - UI dokumen, surat (unduh PDF/Word), kalender, pengumuman `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 5.1
  - Selesai bila: dialog konfirmasi publikasi menyebut objeknya.
- [ ] Task 5.10 - UI notifikasi dan pengumuman untuk wali `[Mudah]` · Agen: Gemini (UI/UX) · Butuh: 5.1
  - Selesai bila: tampil sesuai scope wali.
- [ ] Task 5.11 - Integrasi Tahap 5 `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 5.9, 5.10
  - Selesai bila: alur browser penuh.
- [ ] Task 5.12 - Gerbang Tahap 5 `[Sulit]` · Agen: Claude (QA) · Butuh: 5.1–5.11
  - Selesai bila: dokumen privat, nomor surat bersamaan, delivery failure, dan penjadwalan lulus.

## Tahap 6 — Rapor LCPA

- [ ] Task 6.1 - Proof of concept PDF LCPA (termasuk gambar tanda tangan) dan perbandingan renderer `[Sulit]` · Agen: Claude (Backend) · Aturan: OD-07 · Butuh: 5.4
  - Selesai bila: renderer dipilih berdasarkan kriteria `reports-and-pdf.md`.
- [ ] Task 6.2 - Kontrak rapor `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 5.12
  - Selesai bila: kontrak tertulis.
- [ ] Task 6.3 - Seed `butir_capaian` dari dokumen sumber dan verifikasi teks `[Sedang]` · Agen: Claude (Backend) · Aturan: OD-12 · Butuh: 6.2
  - Selesai bila: 4 + 4 + 7 butir sesuai template.
- [ ] Task 6.4 - Model rapor: rapor, capaian, bagian, Panca Cinta, foto, pengukuran, ekstrakurikuler, refleksi `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-RAP-001, 002, 009, 010, 017 · Butuh: 6.3
  - Selesai bila: unik per siswa dan periode; tingkat hanya BB/MB/BSH/BSB.
- [ ] Task 6.5 - Auto-fill kepala dokumen, kehadiran (cut-off), dan pengukuran DDTK `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-RAP-004, 005, 016 · Butuh: 6.4, 3.3, 3.4
  - Selesai bila: data kosong ditampilkan jelas, tidak dikarang.
- [ ] Task 6.6 - Unggah foto pose kegiatan privat per bagian `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-RAP-012 · Butuh: 6.4, 1.16
  - Selesai bila: foto tidak publik.
- [ ] Task 6.7 - Gambar tanda tangan pegawai (privat) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-RAP-015 · Butuh: 6.4, 1.16
  - Selesai bila: hanya pegawai bersangkutan/admin dapat mengganti.
- [ ] Task 6.8 - Alur draf → persetujuan Kepala RA → terbit `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-RAP-013 · Butuh: 6.4, 3.7
  - Selesai bila: hanya rapor yang disetujui yang terbit; hanya wali kelas yang menyusun.
- [ ] Task 6.9 - Snapshot immutable, versi, dan revisi `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-RAP-006–008 · Butuh: 6.8
  - Selesai bila: perubahan sumber setelah terbit tidak mengubah snapshot.
- [ ] Task 6.10 - Render PDF dan penyimpanan privat `[Sulit]` · Agen: Claude (Backend) · Aturan: BR-RAP-008 · Butuh: 6.1, 6.9
  - Selesai bila: PDF dari versi tertentu; retry aman.
- [ ] Task 6.11 - Refleksi Orang Tua di portal (opsional) `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-RAP-014 · Butuh: 6.4
  - Selesai bila: rapor dapat terbit tanpa isian; isian terkunci setelah terbit.
- [ ] Task 6.12 - UI penyusunan rapor wali kelas `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 6.2
  - Selesai bila: bagian otomatis dan manual dibedakan jelas.
- [ ] Task 6.13 - UI persetujuan dan penerbitan Kepala RA, pratinjau cetak `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 6.2
  - Selesai bila: pratinjau halaman cetak tersedia.
- [ ] Task 6.14 - UI wali: rapor terbit dan Refleksi Orang Tua `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 6.2
  - Selesai bila: wali hanya melihat versi terbit.
- [ ] Task 6.15 - Integrasi Tahap 6 dan fidelity review PDF terhadap template `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 6.10–6.14
  - Selesai bila: pemilik sekolah menerima hasil cetak.
- [ ] Task 6.16 - Gerbang Tahap 6 `[Sulit]` · Agen: Claude (QA) · Butuh: 6.1–6.15
  - Selesai bila: fidelity PDF lulus; snapshot tidak berubah; akses PDF sesuai role.

## Tahap 7 — Website/CMS dan laporan

- [ ] Task 7.1 - Kontrak CMS, dashboard, dan laporan `[Sedang]` · Agen: Claude (Backend); Gemini meninjau · Butuh: 6.16
  - Selesai bila: kontrak tertulis.
- [ ] Task 7.2 - Halaman, program sekolah, galeri/media, informasi pendaftaran, pengaturan website `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-CMS-001–004 · Butuh: 7.1
  - Selesai bila: hanya konten yang dipublikasikan tampil; tidak menduplikasi data siswa/pegawai.
- [ ] Task 7.3 - Profil guru publik dari pegawai yang dipublikasikan `[Mudah]` · Agen: Claude (Backend) · Aturan: BR-CMS-002 · Butuh: 7.2
  - Selesai bila: pegawai tidak dipublikasikan tidak tampil.
- [ ] Task 7.4 - Halaman publik responsif `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 7.1
  - Selesai bila: beranda, profil, program, guru, galeri, informasi pendaftaran, kontak.
- [ ] Task 7.5 - UI admin CMS `[Sedang]` · Agen: Gemini (UI/UX) · Butuh: 7.1
  - Selesai bila: publikasi ditandai jelas.
- [ ] Task 7.6 - Dashboard per role dengan definisi metrik `[Sedang]` · Agen: Claude (Backend) + Gemini (UI/UX) · Butuh: 7.1
  - Selesai bila: setiap angka dapat ditelusuri ke sumber.
- [ ] Task 7.7 - Konsolidasi laporan dan ekspor sensitif berotorisasi dan diaudit `[Sedang]` · Agen: Claude (Backend) · Butuh: 7.6
  - Selesai bila: ekspor hanya sesuai role dan tercatat.
- [ ] Task 7.8 - Integrasi Tahap 7 `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 7.4–7.7
  - Selesai bila: alur browser penuh.
- [ ] Task 7.10 - CMS: biaya dari pengaturan keuangan, logo/favicon/hak cipta, slide Curriculum Highlight `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-CMS-005, DEC-092 · Butuh: 7.1
  - Selesai bila: biaya satu sumber; logo dipakai di semua portal.
- [ ] Task 7.11 - UI final landing page dan dashboard admin sesuai prototipe yang disetujui (1.30), termasuk slide dan pengelolaan logo `[Sulit]` · Agen: Gemini (UI/UX) · Aturan: DEC-095 · Butuh: 1.30, 7.1
  - Selesai bila: sesuai prototipe; responsif; WCAG 2.1 AA.
- [ ] Task 7.9 - Gerbang Tahap 7 `[Sulit]` · Agen: Claude (QA) · Butuh: 7.1–7.8, 7.10–7.11
  - Selesai bila: konten publik tidak membocorkan data privat; responsif; metrik dapat ditelusuri.

## Tahap 8 — Hardening dan produksi

- [ ] Task 8.1 - Pemeriksaan hosting penuh: queue/cron/scheduler, storage, PDF/docx, email `[Sulit]` · Agen: Claude (Integrasi) · Aturan: BR-OPS-003 · Butuh: 7.9
  - Selesai bila: semua item runbook diuji pada paket hosting aktual; vendor ditetapkan (OD-01).
- [ ] Task 8.2 - Konfigurasi email produksi (SPF/DKIM) dan uji delivery `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 8.1
  - Selesai bila: OTP dan pengumuman terkirim di produksi.
- [ ] Task 8.3 - Backup mingguan (retensi 8 minggu) dan restore drill `[Sulit]` · Agen: Claude (Integrasi) · Aturan: BR-OPS-002, DEC-047 · Butuh: 8.1
  - Selesai bila: restore ke lingkungan terisolasi berhasil dan terekam.
- [ ] Task 8.4 - Uji prosedur pemulihan Admin Utama `[Sedang]` · Agen: Claude (QA) · Butuh: 8.1
  - Selesai bila: prosedur berjalan dan tercatat.
- [ ] Task 8.5 - Security review dan uji keamanan dasar `[Sulit]` · Agen: Claude (QA) · Butuh: 7.9
  - Selesai bila: temuan kritis/tinggi ditutup.
- [ ] Task 8.6 - Performa dasar, logging, dan pemantauan tanpa membocorkan data `[Sedang]` · Agen: Claude (Backend) · Aturan: BR-OPS-004 · Butuh: 7.9
  - Selesai bila: kegagalan job dapat ditelusuri.
- [ ] Task 8.7 - UAT tiap role `[Sedang]` · Agen: Claude (QA) · Butuh: 8.1
  - Selesai bila: skenario tiap role diterima pemilik.
- [ ] Task 8.8 - Uji aksesibilitas WCAG 2.1 AA dan responsif `[Sedang]` · Agen: Claude (QA) + Gemini (UI/UX) · Butuh: 7.9
  - Selesai bila: temuan diperbaiki.
- [ ] Task 8.9 - Tinjau risiko yang diterima: RPO backup, tanpa MFA, tanpa penghapusan data, refund tanpa pemisahan `[Mudah]` · Agen: Claude (QA) · Butuh: 8.5
  - Selesai bila: penerimaan risiko tertulis atau diubah oleh pemilik.
- [ ] Task 8.10 - Pelatihan pengguna dan dokumentasi operasional `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 8.7
  - Selesai bila: panduan per role tersedia.
- [ ] Task 8.11 - Go-live checklist dan rencana rollback `[Sedang]` · Agen: Claude (Integrasi) · Butuh: 8.1–8.10
  - Selesai bila: `acceptance-checklist.md` terisi.
- [ ] Task 8.12 - Gerbang Tahap 8 `[Sulit]` · Agen: Claude (QA) · Butuh: 8.1–8.11
  - Selesai bila: semua item production-readiness lulus atau pengecualian diterima tertulis.
