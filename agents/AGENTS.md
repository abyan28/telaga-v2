# Instruksi Global untuk AI Coding Agents

Instruksi ini berlaku untuk semua agen backend dan UI/UX.

## Sumber kebenaran

Semua jalur `docs/...` dan rujukan `/AGENTS.md`, `/README.md` pada paket ini relatif terhadap folder `agents/` (DEC-048).

Urutan prioritas ketika ada konflik:
1. Keputusan terbaru yang disetujui dan dicatat pada `docs/00-fondasi/decision-log.md`.
2. `docs/00-fondasi/rules.md` untuk detail aturan bisnis, lalu `docs/00-fondasi/prd.md` untuk kebutuhan produk (DEC-045).
3. Domain architecture, ERD, data dictionary, status lifecycle, dan workflows.
4. Dokumentasi backend atau UI/UX yang relevan.
5. Kode yang sedang ada.

Kebijakan konflik (berlaku untuk semua agen): jika sumber berbeda tingkat dan maknanya jelas, ikuti sumber berperingkat lebih tinggi dan catat. Jika konflik atau kekosongan menyangkut keputusan berdampak besar, atau dua sumber setingkat bertentangan, hentikan perubahan pada bagian itu, catat di decision-log/open-decisions, dan lanjutkan bagian yang tidak terblokir.

Kode lama bukan otomatis sumber kebenaran karena TELAGA v2 direncanakan sebagai penulisan ulang. Sistem versi awal di `D:\Laravel\Al Kautsar` hanya referensi: jangan menyalin kode atau struktur, dan jangan membacanya untuk menebak aturan; ringkasannya ada di `docs/05-perencanaan/legacy-reference.md` dan hal yang belum jelas dikonfirmasi ke pemilik (DEC-096).

## Sebelum mengubah kode

- Baca dokumen relevan dan periksa struktur kode nyata.
- Identifikasi domain, aturan, status, permission, dan layar yang terdampak.
- Untuk perubahan banyak file, susun rencana singkat.
- Jangan membuat asumsi menjadi keputusan produk tanpa mencatatnya.
- Jangan melakukan refactor besar yang tidak terkait tugas.

## Sesudah mengubah kode

- Tambahkan/perbarui automated tests.
- Jalankan tests, formatter, lint, dan type-check yang relevan bila tersedia.
- Laporkan file berubah, perilaku baru, hasil tes yang benar-benar dijalankan, batasan, dan dokumen yang diperbarui.
- Jangan menyatakan tes/deployment lulus jika belum diuji.

## Aturan arsitektur

- Gunakan modular monolith; jangan menambahkan microservices tanpa keputusan eksplisit.
- Laravel memegang business logic, validasi final, transaksi, dan otorisasi.
- React tidak boleh menjadi satu-satunya lapisan keamanan.
- Gunakan Inertia untuk halaman web; API terpisah hanya jika kebutuhan nyata menuntutnya.
- Gunakan MySQL sebagai database utama.
- Jangan memindahkan data relasional/histori ke JSON bebas.
- Jangan menggunakan soft delete untuk setiap tabel secara otomatis.
- Transaksi keuangan/tabungan yang sudah diposting tidak boleh diedit atau dihapus melalui CRUD biasa.

## Bahasa dan istilah

- Gunakan Bahasa Indonesia untuk dokumen, label UI, dan istilah bisnis.
- Istilah teknis umum seperti RBAC, API, PDF, SMTP, OTP, UI/UX, CRUD boleh tetap bahasa Inggris.
- Label yang dipilih: **Riwayat Penempatan Siswa**.
- “Jurnal Guru” diganti menjadi **Pelaksanaan Pembelajaran** jika paket pembelajaran kelak dikembangkan.

## Larangan

- Jangan memperluas scope hanya karena mudah dibuat.
- Jangan membangun modul pembelajaran yang ditunda.
- Jangan membangun komunikasi dua arah dengan wali di V2 awal; komunikasi hanya pengumuman satu arah. Pengisian Refleksi Orang Tua pada rapor adalah isian formulir, bukan percakapan (DEC-035).
- Jangan membangun MFA, fitur izin publikasi, diskon/prorata tagihan, atau permohonan penarikan tabungan di portal pada V2 awal (DEC-036, 037, 041, 044).
- Jangan menaruh credential, API key, password, OTP, atau data pribadi nyata dalam kode, log, prompt, fixture publik, atau repository.
- Jangan menyamakan penyembunyian menu dengan authorization.
- Jangan mengekspos dokumen privat melalui URL publik.
- Jangan membuat perubahan skema destruktif tanpa backup, rencana migrasi, dan rollback.
- Jangan membuat operasi finansial non-atomik atau yang dapat menggandakan transaksi.

## Laporan akhir agen

1. Ringkasan perubahan.
2. File utama yang diubah.
3. Aturan bisnis yang diimplementasikan.
4. Perintah tes dan hasil aktual.
5. Risiko atau hal belum diuji.
6. Dokumentasi yang diperbarui.
7. Pembaruan `agents/tasklist.md` (lihat bagian berikut).

## Tasklist dan serah-terima (DEC-077)

Pelaksanaan dilacak di `agents/tasklist.md`. Aturan wajib bagi semua agen:

1. Setelah menyelesaikan satu task, **sebelum melapor ke pengguna**, perbarui `agents/tasklist.md`.
2. Tandai task selesai dengan `[✓]` dan emoji ✅ di depan judul task. Belum dikerjakan: `[ ]`. Terhenti di tengah karena limit: `[~]`, dengan kondisinya dicatat.
3. Perbarui `Progress` keseluruhan. Rumus: bobot task selesai ÷ bobot semua task × 100%, bobot `[Mudah]` = 1, `[Sedang]` = 2, `[Sulit]` = 3. Task yang dibatalkan atau digabung tidak dihitung.
4. Tambahkan catatan singkat di bawah task tentang file yang dibuat atau diubah. Contoh:
   ```markdown
   - [✓] ✅ Task 1.2 - Migrasi dan model identitas `[Sedang]` (Selesai)
     - Membuat `database/migrations/xxxx_create_orang_table.php`
   ```
5. Perbarui setiap selesai satu task; jangan menumpuk di akhir sesi.
6. Berikan ringkasan yang jelas di akhir setiap task (isi mengikuti "Laporan akhir agen" di atas).
7. Jika limit penggunaan mendekat, berhenti di **checkpoint yang rapi**: selesaikan atau kembalikan task yang sedang berjalan ke kondisi stabil, perbarui bagian "Titik lanjut" di `tasklist.md`, lalu laporkan.
8. Agen berikutnya cukup membaca `tasklist.md` (bagian "Titik lanjut") untuk tahu tepat task yang dilanjutkan.
9. Jangan mencentang task bila tes yang relevan belum dijalankan dan lulus; bila tes tidak dapat dijalankan, catat dengan jujur.

Sebelum mulai bekerja, baca `tasklist.md` terlebih dahulu.
