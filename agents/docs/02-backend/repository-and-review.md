# Repository, Branch, dan Pedoman Review (Task 0.2)

## Lokasi dan isi repository

- Akar repository: `D:\Laravel\Telaga` (aplikasi Laravel langsung di akar, dipindahkan atas permintaan pemilik 2026-10-10).
- Ikut repository: kode aplikasi, `agents/` (dokumentasi dan tasklist), dan `audit/`.
- **Tidak** ikut repository: `referensi/` (dokumen sumber sekolah yang dapat memuat data pribadi), `.env`, `vendor/`, `node_modules/`, hasil build (`public/build`). Diatur di `.gitignore`.
- Jangan menaruh secret, data anak asli, atau dump database di repository (AGENTS.md).

## Branch

- `main`: selalu hijau (semua pemeriksaan lulus) dan siap dipasang ke staging. Tidak ada commit langsung selain perubahan dokumen kecil.
- `fitur/<kode-task>-<ringkas>` untuk fitur (contoh `fitur/1.8-login`), `perbaikan/<ringkas>` untuk bug, `dok/<ringkas>` untuk dokumentasi.
- Satu cabang = satu task atau satu kelompok task kecil yang berkaitan di `tasklist.md`. Gabungkan ke `main` lewat pull request (atau, bila bekerja sendiri, merge setelah checklist di bawah lulus).
- Pesan commit: bahasa Indonesia, diawali kode task, contoh `Task 1.8: login dengan throttling`. Satu perubahan skema = satu migrasi baru; jangan mengedit migrasi yang sudah dipakai di staging/produksi.
- Kepemilikan file mengikuti `docs/04-ai-agents/workflow.md` (Claude: backend/integrasi/QA; Gemini: UI/UX). Dua agen tidak mengerjakan file yang sama dalam cabang berbeda.

## Pemeriksaan sebelum merge

Perintah (semua harus lulus):

```
composer test        # pint (cek gaya) + phpstan + php artisan test
npm run check        # format + lint (vite-plus)
npm run types:check  # TypeScript
npm run build
```

`composer lint` dan `npm run check:fix` memperbaiki gaya otomatis.

## Pedoman review

Pemeriksa memastikan:

1. Aturan bisnis yang dirujuk task (BR-*, DEC-*) benar-benar diterapkan; tidak ada aturan baru yang tidak tercatat (catat di `decision-log.md`/`open-decisions.md`).
2. Otorisasi di backend (Policy/permission), bukan hanya menyembunyikan menu.
3. Validasi di backend, transaksi dan idempotensi untuk uang/tabungan/penerimaan siswa; tidak ada edit/hapus transaksi terposting.
4. Tes mencakup jalur sukses **dan** jalur negatif (akses tanpa izin, input salah, POST langsung).
5. Tidak ada secret, OTP, atau data pribadi di kode, log, fixture, atau pesan error.
6. Migrasi mengikuti `database-conventions.md` (nama Indonesia, PK `id_<entitas>`, tanpa CHECK/JSON khusus/window function).
7. `tasklist.md` diperbarui (✅, progress, catatan file) dan dokumentasi terkait diperbarui.
