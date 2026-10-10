# TELAGA v2 — Paket Dokumentasi Produk dan Vibe Coding

**TELAGA = PAUD School Management System** untuk RA Al Kautsar.

- Versi dokumentasi: 1.0-draft
- Tanggal: 9 Oktober 2026
- Bahasa utama: Bahasa Indonesia
- Status: Fondasi produk dan arsitektur disepakati; direvisi 9 Oktober 2026 berdasarkan audit dokumentasi (keputusan DEC-027..048). Detail tertentu tetap perlu divalidasi; lihat `docs/05-perencanaan/open-decisions.md`.

## Tujuan

Paket ini menjadi satu sumber acuan pengembangan TELAGA v2 bagi AI coding agents. Isinya meliputi kebutuhan produk, aturan bisnis, arsitektur domain dan ERD logis, backend, UI/UX, workflow agen, pengujian, dan rencana produksi.

Paket ini **bukan aplikasi yang sudah selesai** dan ERD-nya bukan SQL final siap produksi. Field-level schema, constraint, index, serta deployment harus ditinjau sebelum implementasi dan diuji di MySQL yang sesuai dengan hosting target.

## Stack yang disepakati

| Bagian | Keputusan |
|---|---|
| Backend | Laravel 13 |
| Arsitektur | Modular monolith |
| Integrasi frontend-backend | Inertia |
| Frontend | React + TypeScript |
| UI | shadcn/ui |
| CSS | Tailwind CSS |
| Database | MySQL (kompatibel MariaDB; target versi konservatif, DEC-059) |
| Runtime | PHP 8.3 (minimum Laravel 13) |
| Deployment | Shared hosting menjadi preferensi; vendor/paket belum dipilih |
| Email development | Mailjet |
| Email produksi | Mailjet atau provider transactional sejenis (DEC-053) |
| Storage development | Lokal |
| Storage produksi | Disk server di folder non-publik, ikut backup (DEC-053) |
| Penamaan database | Tabel domain Bahasa Indonesia, PK `id_<entitas>`; tabel kerangka Laravel nama bawaan (DEC-028) |
| Zona waktu | Asia/Jakarta (DEC-042) |
| Queue | Database queue sebagai opsi awal; harus diuji dengan hosting |
| PDF | Mesin belum final; lakukan proof of concept LCPA |

Verifikasi versi runtime, paket dan dependensi ketika bootstrap. Jangan menganggap setiap shared hosting mendukung seluruh kemampuan Laravel.

## Mulai dari sini

Baca [`docs/INDEX.md`](docs/INDEX.md) untuk indeks lengkap. Paket ini mengandung dokumen fondasi bersama, rancangan arsitektur/data, instruksi backend, instruksi UI/UX, serta workflow AI coding.

## Cara membaca

1. Baca `agents/AGENTS.md` dan dokumen ini. Semua jalur `docs/...` relatif terhadap folder `agents/`. Untuk melanjutkan pengerjaan, baca `agents/tasklist.md` (bagian "Titik lanjut").
2. Sebelum mengubah scope, baca `docs/00-fondasi/prd.md`, `rules.md`, dan `decision-log.md`.
3. Sebelum mengubah data, baca `docs/01-arsitektur/domain-architecture.md`, `erd.md`, `data-dictionary.md`, dan `workflows.md`.
4. Agen backend mengikuti `docs/04-ai-agents/backend-agent.md` dan dokumentasi backend.
5. Agen UI/UX mengikuti `docs/04-ai-agents/uiux-agent.md` dan dokumentasi UI/UX.
6. Catat keputusan baru di decision log dan perbarui dokumen terdampak.

## Status keputusan

- **DISETUJUI** — disepakati dalam diskusi.
- **REKOMENDASI** — diusulkan, perlu pemeriksaan/persetujuan pada detail implementasi.
- **TERBUKA** — belum diputuskan.
- **DITUNDA** — tidak dikerjakan pada implementasi awal, tetapi dapat dikembangkan kemudian.
- **DI LUAR CAKUPAN** — sengaja tidak dikerjakan dalam cakupan awal.

## Sumber acuan

- Diskusi kebutuhan dan keputusan produk TELAGA v2.
- Template rapor `referensi/LAPORAN CAPAIAN PEMBELAJARAN ANAK.docx` dan profil RA `referensi/VISI  MISI DAN TUJUAN PENDIDIKAN & Profil.docx` yang diberikan pengguna.
- Laravel Starter Kits: https://laravel.com/starter-kits
- Laravel deployment: https://laravel.com/framework/docs/deployment
- MySQL release model: https://dev.mysql.com/doc/refman/8.4/en/mysql-releases.html
- shadcn/ui: https://ui.shadcn.com/docs

## Sistem lama

Versi awal sistem berada di `D:\Laravel\Al Kautsar` dan hanya referensi (v2 dibangun ulang total). Ringkasannya di [`docs/05-perencanaan/legacy-reference.md`](docs/05-perencanaan/legacy-reference.md).

## Aturan penting

- Paket pembelajaran ditunda, bukan dibatalkan; jangan dimasukkan ke implementasi awal tanpa keputusan baru.
- Rapor LCPA tetap fitur inti dan tidak boleh bergantung pada modul pembelajaran yang ditunda.
- MySQL adalah database utama.
- Jangan menjadikan spreadsheet, file, atau JSON bebas sebagai sumber kebenaran transaksi.
- Data pribadi anak dan dokumen sensitif tidak boleh disimpan pada URL publik.
- Deployment shared hosting belum terbukti sampai proof of concept lulus.
