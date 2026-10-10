# Workflow Vibe Coding TELAGA v2

## 1. Prinsip kolaborasi

Dokumentasi adalah kontrak lintas agen. Backend dan UI/UX boleh dikerjakan paralel hanya ketika kontrak data, state, route dan acceptance criteria sudah cukup jelas. Jangan meminta dua agen mengubah file yang sama secara bersamaan tanpa pembagian kerja.

## 2. Urutan kerja per fitur

1. **Pemilik produk/AI perencana:** pilih satu fitur, jelaskan tujuan dan acceptance criteria.
2. **Desain domain/backend:** tentukan aturan bisnis, model data, izin, state lifecycle dan respons backend.
3. **Kontrak UI-backend:** sepakati route/action, props/response, validation errors, loading/error state dan permission.
4. **Backend agent:** buat skema/migration, aturan bisnis, authorization, audit, test dan endpoint/halaman server.
5. **UI/UX agent:** buat halaman, form, tabel, komponen dan state dengan kontrak yang disepakati.
6. **Integrasi:** uji alur browser penuh, bukan hanya masing-masing bagian.
7. **QA/security review:** jalankan acceptance criteria dan skenario negatif.
8. **Dokumentasi:** perbarui rules, ERD/data dictionary/workflow atau guide jika perubahan menetap.

Untuk fitur sederhana, beberapa tahap bisa digabung, tetapi jangan menghilangkan kontrak dan pengujian.

### Alur paralel kontrak-first (DEC-076)

- **Kontrak dulu.** Langkah 3 (route, tipe props TypeScript, format error, permission) selesai dan tertulis sebelum Backend dan UI/UX bekerja paralel. Konvensi umum ada di `02-backend/api-contract-conventions.md`.
- **Backend dan UI/UX paralel setelah kontrak.** Backend menghasilkan props sesuai kontrak dengan data nyata. UI/UX membangun halaman memakai tipe props yang sama.
- **Fixture hanya untuk pengembangan.** UI/UX boleh memakai berkas contoh data (fixture) di folder khusus yang jelas bertanda sementara. Fixture tidak boleh masuk produksi, tidak boleh mengarang route atau payload di luar kontrak, dan harus diganti data nyata pada langkah Integrasi.
- **Integrasi** mengganti fixture dengan data nyata dan menguji alur browser penuh.
- **Tahap 0–1:** Backend membangun fondasi (autentikasi, role, props bersama) sementara UI/UX membangun token desain, kerangka aplikasi, komponen dasar, dan halaman auth. Keduanya tidak saling menunggu.
- **Prototipe awal untuk umpan balik.** UI/UX membuat prototipe klik 3–4 layar kunci (formulir PPDB, absen pegawai di ponsel, rapor, pembayaran) agar pegawai RA menilai lebih awal. Prototipe bukan kode produksi.
- **Syarat hasil UI.** Lulus pemeriksaan tipe TypeScript, lint, dan build; ditinjau sebelum digabung.
- **Kepemilikan file.** Backend (Claude/Codex): controller, route, resource, Policy, migrasi, tes backend. UI/UX (Gemini/Antigravity): halaman dan komponen React serta tes frontend. Berkas tipe diubah lewat perubahan kontrak yang disetujui kedua pihak.

## 3. Pembagian agen

- Claude/Codex: backend, data layer, business logic, security, migration, automated tests, integrasi.
- Gemini/Antigravity: information architecture yang telah disepakati, UI components, page layout, responsive behavior, accessibility dan frontend tests.
- Satu agen koordinator (atau pengguna) mengelola keputusan lintas domain, conflict resolution dan decision log.
- Semua agen membaca `agents/tasklist.md` sebelum mulai dan memperbaruinya setelah tiap task selesai, sesuai aturan di `agents/AGENTS.md` (bagian "Tasklist dan serah-terima").

## 4. Git dan perubahan

- Satu task/PR harus mempunyai lingkup dan acceptance criteria jelas.
- Hindari perubahan besar tidak terkait dalam satu commit.
- Jangan menimpa pekerjaan rekan/agen tanpa memeriksa diff.
- Jalankan test sebelum merge.
- Tidak ada secrets/data produksi di repo.
- Nama branch dan aturan review dapat ditentukan saat repository bootstrap; jangan menganggap kolaborasi harus langsung mengubah branch `main`.

## 5. Jika dokumen tidak lengkap

Ikuti kebijakan konflik di `agents/AGENTS.md`. Jangan menebak keputusan berdampak besar. Catat: (a) apa yang belum jelas, (b) pilihan yang mungkin, (c) konsekuensi, (d) rekomendasi default aman. Lanjutkan bagian yang tidak diblokir. Keputusan baru yang disetujui harus diperbarui ke decision log dan dokumen relevan.

## 6. Setiap tugas harus mencantumkan

- Tujuan dan konteks fitur.
- Dokumen sumber yang harus dibaca.
- Ruang lingkup dan hal yang tidak dikerjakan.
- Role/permission dan cakupan record.
- Perubahan skema/data.
- Acceptance criteria positif dan negatif.
- Test wajib.
- Risiko, dependensi dan keputusan terbuka.
