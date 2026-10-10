# Dokumen, Berkas, Media, dan Storage

## 1. Perbedaan istilah

- **Dokumen:** entitas bisnis yang menjelaskan tujuan, kategori, status dan konteks dokumen.
- **Berkas:** objek fisik yang tersimpan; satu Dokumen dapat mempunyai beberapa versi berkas.
- **Media:** gambar/video untuk konten publik CMS, berbeda dari dokumen pribadi.

Jangan memakai satu entitas generik untuk menyamakan dokumen siswa, bukti pembayaran, PDF surat, dan galeri publik tanpa aturan akses yang berbeda.

## 2. Versi dokumen

- Penggantian berkas menambah versi, tidak menimpa versi historis.
- Verifikasi selalu merujuk versi tertentu (`verifikasi_dokumen.id_versi_dokumen` wajib).
- Metadata minimum: storage disk/key, nama berkas asli, tipe yang terdeteksi, ukuran, pengunggah, waktu, nomor versi, konteks dan status. **Status dimiliki versi dokumen**; status tingkat dokumen hanya ringkasan.
- Hash dapat digunakan untuk pemeriksaan integritas/deteksi duplikasi jika sesuai.
- Penghapusan versi lama mengikuti kebijakan retensi dan legalitas, bukan tombol umum bagi pengguna.

## 3. Relasi dokumen

Gunakan junction eksplisit bila suatu dokumen melekat pada entitas berbeda, misalnya `dokumen_siswa`, `dokumen_pegawai`, `dokumen_pendaftaran`, `dokumen_pembayaran`, `dokumen_surat`. Ini menjaga integritas FK dan memudahkan authorization. Hindari polymorphic relation untuk semua dokumen bila membuat FK tidak dapat ditegakkan.

## 4. Storage lokal dan produksi

- Development: filesystem lokal melalui abstraksi Laravel.
- Produksi: disk server/hosting pada folder non-publik yang ikut backup (DEC-053). Object storage S3-compatible tetap dapat dipakai belakangan karena akses lewat abstraksi disk Laravel.
- Jangan menanam path provider di business logic; gunakan konfigurasi disk dan storage key.
- Periksa storage limit, backup, private file serving, koneksi luar, biaya, retensi, dan batas hosting.
- Shared hosting belum dianggap lulus sampai upload/download privat, kapasitas, permission, backup dan deployment diuji.

## 4A. Penamaan dan penghapusan berkas (pelajaran sistem lama)

- Struktur folder rapi per jenis, tahun ajaran, dan murid dengan ID agar nama kembar tidak bertabrakan (mis. `pembayaran/{jenis}/{tahun-ajaran}/{nama-id}/`); nama berkas berisi jenis, periode, tanggal, dan waktu. Nama objek tetap aman dan tidak berasal langsung dari masukan pengguna; folder tersebut tidak berada di web root.
- Saat berkas diganti (termasuk ekstensi berbeda), berkas lama dihapus dari penyimpanan agar tidak menumpuk — kecuali versi dokumen yang menjadi bukti historis (BR-DOK-003), yang tetap disimpan.
- Foto pendaftaran dapat menjadi foto profil murid; foto profil privat secara default.

## 5. Keamanan unduhan

- Berkas sensitif jangan berada pada public web root.
- Download harus melewati otorisasi berdasarkan pemilik/konteks/role.
- Presigned URL sementara dapat dipertimbangkan untuk object storage, tetapi TTL singkat dan URL tidak boleh ditulis ke log.
- Jangan mengandalkan URL yang sulit ditebak sebagai pengamanan.

## 6. Validasi upload

- Allowlist hanya format yang dibutuhkan per konteks, bukan satu daftar luas untuk semua upload. Nilai awal disetujui (DEC-067; dapat dikonfigurasi per jenis dokumen; foto kegiatan rapor mengikuti batas gambar): dokumen PPDB/pegawai/bukti pembayaran PDF, JPG, PNG maks 5 MB; gambar CMS dan tanda tangan JPG, PNG, WebP maks 2 MB. Persyaratan dokumen PPDB per periode disimpan di `persyaratan_dokumen_pendaftaran`.
- Validasi ekstensi, MIME hasil deteksi dan ukuran maksimal.
- Batasi dimensi/pixel untuk gambar yang dirender ulang jika relevan.
- Gunakan nama objek yang aman dan tidak berasal langsung dari nama file asli.
- Tangani kegagalan saat metadata tersimpan tetapi file gagal diunggah, dan sebaliknya; proses pembersihan berkas yatim perlu didesain.
- Jangan mengizinkan berkas dapat dieksekusi server.

## 7. Media publik CMS

Media publik boleh dilayani secara langsung hanya setelah status publikasinya sah dan tidak mengandung informasi privat. Foto guru/siswa tidak otomatis publik; publikasi membutuhkan kebijakan dan otorisasi yang sesuai.

## 8. Dokumen yang wajib tercakup

- PPDB: KK, akta kelahiran, dan dokumen lain yang disepakati.
- Pembayaran: bukti transfer atau lampiran relevan.
- Pegawai: dokumen kepegawaian yang diperlukan.
- Surat: template dan artefak surat yang diterbitkan (PDF dan Word/.docx, privat).
- Rapor: PDF privat, snapshot versi, dan gambar tanda tangan (privat).
- CMS: logo, banner dan gambar galeri publik.

Jenis dokumen dan field wajib dibuat configurable sejauh kebutuhan nyata, tetapi jangan membangun pembuat formulir generik yang kompleks sebelum pola PPDB terbukti.
