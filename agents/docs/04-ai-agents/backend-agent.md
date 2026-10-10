# Instruksi Agen AI — Backend (Claude/Codex)

## Misi

Implementasikan backend TELAGA v2 sesuai spesifikasi, bukan berdasarkan asumsi atau preferensi framework yang tidak tertulis. Produk adalah aplikasi sekolah PAUD berbahasa Indonesia dengan Laravel + React/Inertia + MySQL dan deployment sasaran shared hosting (belum divalidasi).

## Sebelum mengerjakan tugas

1. Baca `agents/AGENTS.md`, `agents/README.md` (jalur `docs/...` relatif terhadap `agents/`), dan `agents/tasklist.md` (bagian "Titik lanjut"). Setelah tiap task selesai, perbarui `tasklist.md` sebelum melapor.
2. Baca `docs/00-fondasi/prd.md`, `rules.md`, `glossary.md`, `decision-log.md` dan dokumen terkait fitur.
3. Baca domain architecture, ERD, data dictionary dan workflow yang relevan.
4. Identifikasi acceptance criteria, invariant data, permission dan histori yang terpengaruh.
5. Periksa kode/test/dokumentasi aktual pada branch kerja. Jangan mengasumsikan struktur yang belum diperiksa.

## Larangan

- Jangan mengubah scope atau istilah bisnis tanpa pembaruan dokumen/keputusan.
- Jangan memasukkan fitur pembelajaran yang ditunda ke V2 awal.
- Jangan memakai PostgreSQL; database yang dipilih sekarang MySQL.
- Jangan mengubah menjadi microservices, Next.js atau API backend terpisah tanpa keputusan arsitektur baru.
- Jangan membuat seluruh business logic dalam controller/model hook tanpa struktur dan alasan jelas.
- Jangan mengandalkan frontend untuk authorization.
- Jangan menghapus data keuangan/tabungan terposting atau histori rapor.
- Jangan memasukkan secret, OTP, data anak asli atau kredensial ke source control/log/test fixture.
- Jangan mengklaim test berhasil jika belum benar-benar dijalankan.

## Pola kerja

1. Mulai dengan membaca tugas, lalu sebutkan file dan aturan yang terpengaruh pada ringkasan kerja internal/PR.
2. Implementasikan perubahan sekecil mungkin namun lengkap.
3. Buat/perbarui migration, validasi, Policy, transaksi, audit dan test yang diperlukan.
4. Jalankan test yang relevan, lint/static analysis dan build yang tersedia. Jika gagal, perbaiki atau jelaskan blocker secara faktual.
5. Perbarui dokumen yang berubah, termasuk decision log bila keputusan baru dibuat.
6. Tinjau keamanan, akses scope, data leak, N+1, dan retry/double submission.

## Aturan transaksi

- Operasi multi-tabel keuangan/tabungan harus transactional.
- Periksa concurrency dan idempotency pada posting/allocation/acceptance.
- Jangan melakukan email/remote API yang rawan gagal di tengah transaksi finansial tanpa pola outbox/after-commit yang sesuai.
- Sediakan histori aktor, waktu, status lama/baru dan alasan pada keputusan penting.

## Kriteria selesai

Tugas baru dianggap selesai jika acceptance criteria terpenuhi, test relevan lulus, migration aman, authorization ditegakkan di backend, error dan status UI dapat dipahami, dan dokumentasi terkait diperbarui. Laporkan batas pengujian lingkungan secara jujur.
