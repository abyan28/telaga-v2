# Information Architecture TELAGA v2

## 1. Prinsip navigasi

- Navigasi berdasarkan pekerjaan dan domain, bukan berdasarkan nama tabel.
- Menu yang terlihat mengikuti role dan permission, tetapi backend tetap memeriksa akses.
- Pencarian, filter, pagination dan state kosong harus konsisten antarhalaman.
- Sidebar responsif: pada desktop dapat menetap; pada layar sempit menjadi drawer yang dapat ditutup.
- Wali murid memperoleh portal sederhana yang terpisah secara pengalaman dari dashboard internal sekolah.
- Hindari satu dashboard yang menampilkan informasi semua modul tanpa prioritas.

## 2. Peta navigasi internal

```text
Dashboard
Identitas & Akses
  Pengguna dan Undangan Staf
  Tinjauan Kecocokan Orang
  Role
  Permission
Data Master & Akademik Dasar
  Tahun Ajaran (wizard “Tahun ajaran berikutnya”)
  Periode Pembelajaran
  Kelas
  Ruangan
  Jenis Penugasan
  Referensi Terkontrol
PPDB
  Pengaturan PPDB (buka/tutup, kuota, tahun ajaran target)
  Periode Pendaftaran
  Daftar Pendaftaran
  Pemeriksaan Dokumen
  Histori Status
  Calon Murid (dan Buat NIS)
Kesiswaan
  Data Siswa
  Impor Murid Lama (CSV/Excel)
  Data Wali dan Hubungan (profil ayah/ibu, alamat, kode aktivasi)
  Riwayat Penempatan Siswa
  Absensi Siswa
  Kesehatan & Pertumbuhan
  Portofolio
Keuangan
  Jenis Tagihan
  Tagihan
  Pembayaran & Verifikasi
  Refund
  Metode Pembayaran
  Akun Kas/Bank
  Kategori Transaksi
  Pemasukan & Pengeluaran
  Transfer Antar-Akun
  Tabungan Siswa
  Penarikan Tabungan
  Rekonsiliasi Setoran
  Laporan Keuangan
Pegawai & Kehadiran
  Data Pegawai
  Jabatan & Riwayat Jabatan
  Penugasan Akademik
  Absensi Pegawai
Administrasi & Dokumen
  Jenis dan Template Surat
  Surat (unduh PDF atau Word)
  Dokumen dan Versi
  Jenis Dokumen
  Verifikasi Dokumen
  Kalender Akademik
  Jenis Kegiatan
  Agenda
  Pengumuman
  Notifikasi/Status Pengiriman
Rapor LCPA
  Draf Rapor (wali kelas)
  Persetujuan Rapor (Kepala RA)
  Pemeriksaan dan Penerbitan
  Riwayat Versi
Laporan
  Rekap Absensi, Tagihan dan Tunggakan, Arus Kas, Saldo Tabungan, Status PPDB
  (lihat 05-perencanaan/report-catalog.md)
Website/CMS
  Halaman
  Program Sekolah
  Galeri & Media
  Informasi Pendaftaran
  Pengaturan Website
Pengaturan Sistem
  Profil Sekolah
  Pengaturan Umum (SPP bawaan dan tanggal terbit, persentase denda refund, NSM, rekening sekolah, daftar bank, parameter OTP)
  Tanda Tangan
  Audit Log
Profil dan Pengaturan Akun Saya (semua pengguna staf)
Absen Saya (pegawai yang wajib absen; ponsel, tombol Masuk/Pulang dengan lokasi)
Koreksi Absensi Pegawai (admin; alasan wajib)
```

Peta adalah rancangan awal; menu dan izin final akan dipetakan lewat permission matrix. Menu yang tidak relevan untuk role tidak harus ditampilkan.

## 3. Portal wali murid

```text
Beranda
Profil Saya
Anak Saya
  Profil Anak
  Status Pendaftaran
  Dokumen Pendaftaran
  Tagihan dan Pembayaran
  Tabungan (saldo dan mutasi, hanya lihat)
  Rapor (versi terbit) dan Refleksi Orang Tua (opsional)
Pengumuman
Notifikasi
Pengaturan Akun
```

Isi portal mengikuti hubungan wali-anak terverifikasi. Selama pendaftaran, wali hanya melihat pengajuannya sendiri; tagihan biaya pendaftaran tampil pada pengajuan tersebut. Tidak ada permohonan penarikan tabungan di portal. Jika akun belum mempunyai anak yang terhubung, tampilkan alur pendaftaran yang jelas. Jangan tampilkan nama anak milik akun lain.

## 4. Halaman publik website

Beranda, Profil Sekolah, Program Sekolah, Guru/Tenaga Pendidik (dari profil pegawai yang ditandai dipublikasikan), Galeri, Informasi Pendaftaran, Kontak/informasi publik, login, serta pendaftaran wali murid. Konten halaman ditentukan oleh CMS, tetapi data operasional seperti status pendaftaran dibaca dari sumber yang benar dan hanya dipublikasikan sesuai aturan.

## 5. Struktur halaman inti

Halaman daftar umumnya mencakup judul, tindakan utama, pencarian/filter relevan, tabel atau list, pagination dan empty state. Halaman detail memakai pola ringkasan + tab/section yang relevan, histori dan tindakan yang dibatasi oleh permission. Formulir kompleks dibagi ke bagian yang logis dengan penyimpanan draf bila kehilangan isian berisiko.

## 6. Pencarian dan filter

- Sediakan pencarian berdasarkan nama/nomor internal dengan akses yang sesuai.
- Filter harus mempertahankan konteks ketika membuka detail dan kembali ke daftar bila memungkinkan.
- Jangan melakukan pencarian lintas domain yang mengungkap informasi yang tidak berhak dilihat pengguna.
- Ekspor data merupakan tindakan sensitif, perlu permission dan audit sesuai risiko.
