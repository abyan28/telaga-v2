# Security Baseline TELAGA v2

Ini adalah baseline minimum keamanan. Tinjau terhadap risiko aktual sebelum produksi dan dokumentasikan setiap pengecualian.

## 1. Kontrol aplikasi

- Validasi server-side untuk semua input; normalisasi seperlunya dan encode output untuk konteks yang sesuai.
- Proteksi CSRF pada request berbasis sesi.
- Authorization backend untuk setiap record, tindakan sensitif, download, export, bulk action dan route yang menerima ID.
- Rate limit untuk login, OTP, reset, pendaftaran dan endpoint yang mudah disalahgunakan.
- Jangan bergantung pada nama URL, ID acak, atau menu tersembunyi sebagai authorization.
- Hindari mass assignment tak terkendali. Validasi field yang memang boleh diubah per use case.
- Tetapkan batas unggah, timeout, ukuran payload dan pagination.
- Gunakan HTTPS pada staging/produksi.

## 2. Rahasia dan konfigurasi

- Rahasia hanya pada environment/secret facility, bukan source control.
- File env produksi tidak masuk Git atau backup publik.
- Gunakan kredensial database dengan hak minimum yang diperlukan.
- Batasi akses ke dashboard/provider email/storage.
- Jangan menampilkan stack trace, SQL, credential, token, OTP atau rincian internal kepada pengguna.
- Matikan debug mode di produksi.

## 3. Berkas dan upload

- Allowlist format yang dibutuhkan, validasi ekstensi dan MIME yang terdeteksi, batasi ukuran dan jumlah.
- Jangan mempercayai nama file dari pengguna.
- Berkas privat berada di disk nonpublik atau storage privat; download harus melalui authorization.
- File HTML/script atau format aktif tidak boleh dieksekusi dari storage upload.
- Pertimbangkan pemindaian malware sesuai kapabilitas hosting dan profil risiko.
- Catat metadata versi, uploader, waktu, ukuran, dan konteks. Jangan log isi berkas.

## 4. Data pribadi anak

- Kumpulkan data minimal yang memiliki tujuan operasional.
- Batasi tampilan data kesehatan, dokumen identitas, foto, rapor, dan data wali berdasarkan role/kebutuhan.
- Ekspor dibatasi, dilacak, dan tidak ditempatkan dalam direktori publik.
- Hindari data anak sebenarnya dalam test, demo, seed atau screenshot dokumentasi.
- Retensi, penghapusan, dan pemenuhan ketentuan perlindungan data perlu ditinjau sebelum produksi (OD-14). Tidak ada fitur izin publikasi (DEC-036): foto dan data anak tidak dipublikasikan kecuali lewat tindakan petugas berwenang; risiko tanpa jejak persetujuan wali ditinjau ulang sebelum produksi.

## 5. Keuangan dan audit

- Posting keuangan/tabungan dilakukan dalam transaksi database dan mempunyai pelaku serta timestamp.
- Jangan menghapus transaksi terposting; reversal/adjustment harus merujuk transaksi asal dan beralasan.
- Cegah request ganda/double submit memakai idempotency atau status/locking sesuai kebutuhan.
- Perubahan role, verifikasi pembayaran, perubahan akun, publikasi rapor, download/ekspor berisiko tinggi dan koreksi transaksi dicatat di audit log sesuai kebijakan.
- Audit log tidak dapat dimanipulasi melalui formulir aplikasi biasa.

## 6. Logging

Log teknis dan audit bisnis mempunyai tujuan berbeda. Redaksi password, OTP, token, cookie, secret, konten dokumen, dan data pribadi yang tidak diperlukan. Catat error dengan ID korelasi agar troubleshooting memungkinkan tanpa merekam payload sensitif.

## 7. Checklist test keamanan minimum

- [ ] CSRF pada operasi state-changing.
- [ ] Rate limit login, OTP, dan reset.
- [ ] OTP kadaluarsa, salah, dipakai ulang, dan spam resend ditolak.
- [ ] User tanpa permission mendapat 403/404 sesuai kebijakan.
- [ ] Guru tidak bisa mengakses siswa di kelas lain dengan manipulasi URL.
- [ ] Wali tidak bisa mengakses anak/dokumen/tagihan orang lain.
- [ ] Upload file tipe/ukuran tidak diizinkan ditolak.
- [ ] Berkas privat tidak dapat diunduh anonim.
- [ ] Pengguna tidak dapat mengubah role sendiri.
- [ ] Race condition tidak membuat alokasi/transfer/posting/penarikan ganda.
- [ ] Error produksi tidak membocorkan stack trace/secret.
- [ ] Respons pendaftaran/login/reset tidak membedakan email terdaftar dan tidak terdaftar (BR-AUTH-011).
- [ ] Akun `ditangguhkan`/`dinonaktifkan` kehilangan sesi aktif dan tidak dapat login (BR-AUTH-014).
- [ ] Perubahan role/permission berlaku segera; cache izin tidak mempertahankan hak yang dicabut.
- [ ] Wali tidak dapat membaca pengajuan PPDB atau data anak dengan hubungan belum `terverifikasi`.
- [ ] Gambar tanda tangan dan PDF rapor tidak dapat diunduh tanpa izin; wali hanya mendapat versi terbit.
- [ ] Ekspor/laporan menjalankan otorisasi dan tercatat di audit log.
- [ ] Peristiwa BR-AUD-002 (role, PPDB, verifikasi dokumen, pembayaran, mutasi, penerbitan rapor) menghasilkan entri audit tanpa rahasia (BR-AUD-003).
- [ ] Token reset dan undangan staf kedaluwarsa, sekali pakai, dan undangan baru membatalkan yang lama.

## 7A. Pelajaran dari sistem lama (BR-OPS-007, DEC-096)

Sistem lama (referensi) menunjukkan kelemahan yang tidak boleh terulang. Setiap butir diuji di v2:
- [ ] Tidak ada rute yang menyajikan berkas privat (KK, akta, foto anak, bukti bayar) tanpa otorisasi, dan tidak ada rute “fallback storage” yang menerima path bebas.
- [ ] Login, OTP, dan kode aktivasi dibatasi percobaan.
- [ ] Tidak ada kata sandi awal yang dapat ditebak (NIK, NUPTK, no. HP, “admin123”); seeder produksi tidak memakai kata sandi tetap.
- [ ] Hitungan uang memakai tipe desimal, bukan float; perbandingan nominal memakai aritmetika desimal/integer.
- [ ] Referensi transaksi ke tagihan memakai FK eksplisit per jenis, bukan `referensi_id` polimorfik tanpa FK.
- [ ] Verifikasi pembayaran idempoten dan dikunci (tidak dapat diverifikasi dua kali, tidak mengirim email berulang); satu bukti pending per tagihan di bawah transaksi/kunci.
- [ ] Setiap aksi memakai Policy (termasuk perubahan akun oleh admin: tidak dapat mengubah akun role lebih tinggi).
- [ ] Transisi status memeriksa status asal (ubah keputusan, izin edit, pembatalan calon).
- [ ] Pengaturan yang tampil di UI benar-benar memengaruhi perilaku (mis. tanggal terbit SPP, kuota).
- [ ] Ekstensi berkas ditentukan dari tipe terdeteksi, bukan dari nama berkas klien.
- [ ] Perintah destruktif (reset data) tidak tersedia di produksi.

## 8. Parameter keamanan awal

Nilai awal disetujui pemilik (DEC-067) dan dapat dikonfigurasi: lihat `auth-rbac.md` §3–§4. Batas unggah dan allowlist per jenis dokumen: lihat `documents-and-storage.md` §6. MFA tidak dibangun (DEC-041).

Data lokasi pegawai (absen GPS) adalah data pribadi: koordinat disimpan (DEC-072) tetapi hanya dapat dilihat admin berwenang, tidak ditampilkan ke pengguna lain, dan akses melihatnya diaudit (BR-PEG-008). Retensi: tidak ada data yang dihapus; semua record menjadi arsip sekolah (BR-OPS-006, DEC-071). Karena permintaan penghapusan tidak dilayani, tinjau terhadap ketentuan perlindungan data pribadi yang berlaku sebelum produksi.
