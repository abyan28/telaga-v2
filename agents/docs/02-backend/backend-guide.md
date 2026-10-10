# Panduan Backend TELAGA v2

## 1. Tujuan

Panduan ini menjadi kontrak kerja Claude/Codex untuk bagian server. Ikuti PRD, `rules.md`, domain architecture, ERD, data dictionary dan workflow. Kebijakan konflik mengikuti `agents/AGENTS.md`: bila sumber jelas dan tidak ambigu, ikuti urutan prioritasnya; bila ambigu atau tidak ada keputusan, hentikan perubahan pada bagian konflik dan catat; jangan menebak aturan bisnis. Konvensi props/route/error untuk frontend ada di `api-contract-conventions.md`.

## 2. Stack

- Laravel 13.
- PHP versi yang didukung Laravel 13 dan tersedia di hosting target; verifikasi saat bootstrap/deployment.
- MySQL sebagai database utama.
- React + TypeScript + Inertia sebagai frontend yang dilayani oleh aplikasi yang sama.
- Queue database sebagai opsi awal; lingkungan shared hosting harus diuji.
- Mailjet untuk pengembangan; produksi Mailjet atau provider transactional sejenis (DEC-053).
- Penyimpanan lokal saat development; produksi disk server di folder non-publik yang ikut backup (DEC-053).
- PDF driver belum final sampai proof of concept LCPA lulus.

## 3. Prinsip implementasi

1. Mulai dari aturan bisnis dan invariant, bukan langsung membuat CRUD generik.
2. Controller menangani request/response dan otorisasi awal; business logic diletakkan pada layanan/use case atau pola aplikasi yang konsisten.
3. Form Request/validator menegakkan validasi bentuk input. Aturan lintas-entitas tetap divalidasi di lapisan aplikasi dan database bila memungkinkan.
4. Gunakan transaksi database untuk operasi multi-tabel yang harus atomik.
5. Gunakan Policy untuk pemeriksaan akses terhadap objek dan cakupan data.
6. UI tidak pernah menjadi satu-satunya pengaman.
7. Gunakan eager loading secara sadar; hindari query berulang yang menyebabkan N+1.
8. Jangan mengembalikan model dengan kolom sensitif tanpa resource/transformasi yang tepat.
9. Semua operasi yang dapat dipanggil ulang (queue, webhook di masa depan, submission pembayaran) dirancang agar idempotent.
10. Tidak ada penghapusan fisik terhadap ledger atau histori bisnis tanpa kebijakan eksplisit.

## 4. Modular monolith

Kelompokkan kode menurut domain dengan pendekatan yang tetap sesuai konvensi Laravel. Nama direktori final boleh mengikuti starter kit, tetapi jangan membagi aplikasi ke layanan terpisah. Modul minimal (nama kode Inggris; padanan domain di `domain-architecture.md`): Identity, Academic, PPDB, Student, Finance (termasuk submodul Savings), Staff, Documents, Administration/Communications, Rapor (rapor LCPA), Reports (laporan agregat), CMS, Shared Services. Nama tabel mengikuti DEC-028.

Jangan menaruh seluruh business logic dalam controller raksasa. Hindari service abstraction yang tidak memberi batas atau manfaat yang jelas. Kode bersama hanya digunakan bila benar-benar ada perilaku bersama.

## 5. Database dan perubahan skema

- Semua perubahan struktur dilacak melalui migration.
- Jangan memakai `migrate:fresh` atau tindakan destruktif pada database berisi data penting.
- Tinjau FK, unique index, index query, nullability, default, kolom audit dan kebijakan retensi sebelum menerapkan migration.
- Pastikan tipe FK kompatibel dengan PK yang dirujuk.
- Gunakan transaksi untuk posting pembayaran/tabungan.
- Constraint aplikasi dan database harus sejalan; jangan mengandalkan validasi frontend.
- Seeder hanya untuk data referensi dan akun demo non-produksi yang jelas ditandai. Jangan menanam kata sandi atau data anak sungguhan.

## 6. Authentication dan authorization

Gunakan session-based web authentication dan perlindungan CSRF. Implementasi final mengikuti `auth-rbac.md` dan `security-baseline.md`. Package role/permission dapat dipakai setelah kompatibilitas versi, model User, nama tabel dan kebijakan custom diperiksa.

## 7. File, email, queue, PDF

Ikuti dokumen `documents-and-storage.md`, `email-notifications-queue.md`, dan `reports-and-pdf.md`. Semua integrasi eksternal harus dikonfigurasi dari environment dan memiliki penanganan kegagalan. Jangan menulis provider tertentu ke business logic.

## 8. Logging dan audit

Log operasional harus berguna tanpa membocorkan password, OTP, token, bukti identitas, konten rapor sensitif, atau data finansial berlebih. Audit log bisnis berbeda dari log teknis. Audit harus mencatat aktor, tindakan, objek, waktu, hasil, dan perubahan yang diperlukan untuk tindakan penting.

## 9. Definition of Done backend

- Business rule dan Policy tersedia.
- Validasi input dan error response konsisten.
- Transaksi dan histori benar.
- Automated tests relevan dibuat dan lulus.
- Indeks, eager loading, keamanan data dan status diuji.
- Migrasi aman dan dapat dijalankan dari database kosong.
- Dokumentasi terkait diperbarui.
- Tidak ada secret, data produksi, atau file sensitif di repository.
