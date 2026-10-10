# PRD — Product Requirements Document TELAGA v2

- Status: fondasi produk **DISETUJUI**, rincian fitur dapat ditinjau melalui decision log.
- Produk: **TELAGA = Teknologi Layanan Akademik & Guru Al Kautsar**.
- Organisasi awal: RA Al Kautsar.

## 1. Ringkasan

TELAGA v2 adalah sistem manajemen sekolah berbasis web untuk menyatukan data orang/siswa/wali/pegawai, pendaftaran siswa baru, akademik dasar, absensi, kesehatan dan pertumbuhan, keuangan, tabungan siswa, administrasi, dokumen, rapor, komunikasi resmi satu arah, dan website/CMS.

Produk dibangun ulang dengan fokus pada aturan bisnis yang konsisten, data yang tidak diduplikasi, akses yang aman, UI sederhana dan responsif, histori yang dapat diaudit, serta dokumentasi yang dapat dipakai AI coding agents.

## 2. Masalah yang ingin diselesaikan

- Data yang sama tidak dicatat berulang di modul berbeda.
- PPDB, dokumen, pembayaran, dan histori status terintegrasi.
- Role hanya dapat mengakses tindakan dan data yang menjadi kewenangannya.
- Tagihan/pembayaran, kas sekolah, dan tabungan siswa dipisah dengan benar.
- Dokumen, surat, rapor, pengumuman, dan website memiliki alur terstruktur.
- Pengguna dapat bekerja dari perangkat desktop maupun ponsel.
- Agen AI tidak perlu menebak aturan atau teknologi yang sudah ditetapkan.

## 3. Tujuan

1. Mempunyai sumber data konsisten untuk orang, pengguna, siswa, wali, pegawai, kelas, dan tahun ajaran.
2. Menangani pendaftaran dari pengajuan sampai keputusan dan pembentukan profil siswa.
3. Mendukung absensi, kesehatan/pertumbuhan, dan tabungan siswa.
4. Menangani tagihan, pembayaran, mutasi kas/bank, dan transaksi tabungan dengan audit trail.
5. Menghasilkan rapor berdasarkan template LCPA sekolah.
6. Menyediakan surat, dokumen, kalender, pengumuman satu arah, notifikasi, dan CMS.
7. Memastikan keamanan diterapkan di backend.
8. Memungkinkan pengoperasian dengan shared hosting jika kompatibilitas telah dibuktikan.

## 4. Pengguna dan role awal

- **Admin Utama:** pengelolaan sistem dan akses paling tinggi sesuai kebijakan.
- **Admin Sekolah:** administrasi sekolah sesuai permission.
- **Kepala RA:** pemantauan dan laporan yang diizinkan.
- **Bendahara:** tagihan, pembayaran, akun/mutasi keuangan, tabungan, rekonsiliasi sesuai permission.
- **Guru:** data kelas yang ditugaskan, absensi. Berdasarkan jenis penugasan: *wali kelas* menyusun rapor, menandatangani, dan mencatat setoran tabungan harian; *guru pendamping* mengakses siswa kelasnya tanpa hak tersebut (DEC-046).
- **Wali Murid:** profil sendiri, anak yang terhubung sah, pendaftaran, pembayaran, dokumen, pengumuman, saldo dan mutasi tabungan anak (hanya lihat), rapor yang diizinkan, dan (opsional) pengisian Refleksi Orang Tua pada rapor.

Role dapat dikonfigurasi. Jabatan, penugasan, role, dan permission adalah konsep berbeda. Tidak semua pegawai memerlukan akun.

ID kebutuhan modul memakai format `PRD-<KODE>` dan dirujuk dari `requirements-traceability.md`. Untuk detail aturan, `rules.md` menang atas dokumen ini (DEC-045).

## 5. Modul inti

### Identitas & Akses (PRD-IDA)
Data orang, akun (setiap akun terkait satu orang), verifikasi email/OTP, login dengan email/no. HP/username, kode aktivasi untuk akun wali lama, pemulihan akun (OTP lalu token reset), undangan akun staf, role, permission, Policy, audit perubahan akses. Tanpa MFA pada V2 awal (DEC-041).

### Data Master & Akademik Dasar (PRD-AKD)
Tahun ajaran, periode pembelajaran, kelas/kelompok, ruangan, referensi, penugasan akademik (wali kelas, guru pendamping).

### Pendaftaran Murid Baru (PRD-PPDB)
Buka/tutup PPDB dengan tahun ajaran target terpisah, kuota, validasi usia 4–6 tahun per 1 Juli, periode pendaftaran per tahun, pendaftaran untuk satu anak per pengajuan (data calon disimpan di pendaftaran; wali dapat mengedit sampai pembayaran terverifikasi, admin dapat membuka izin edit dan mengubah keputusan), banyak anak per wali, upload dokumen, pemeriksaan, histori status, keputusan diterima/ditolak, biaya pendaftaran yang ditagih ke pendaftaran dan harus lunas sebelum pengajuan diperiksa/diputuskan, pembentukan orang/profil siswa saat diterima, tagihan daftar ulang untuk siswa, dan pembuatan NIS massal (NSM + tahun + urut abjad). Murid berstatus `calon` sampai NIS dibuat (halaman Calon Murid).

### Kesiswaan (PRD-SIS)
Profil siswa (data lengkap termasuk status mengaji, pernah belajar, ukuran baju; angkatan dari NIS), profil ayah dan ibu, alamat keluarga berjenjang, hubungan wali, Riwayat Penempatan Siswa, absensi harian, profil kesehatan dasar, pengukuran pertumbuhan, portofolio (privat secara default), serta impor murid lama lewat CSV/Excel.

### Keuangan (PRD-KEU)
Jenis tagihan, tagihan bulanan (tanggal terbit dapat diatur), cicilan hanya untuk daftar ulang (pendaftaran dan SPP wajib lunas), pembayaran atas nama wali pembayar dengan bank asal transfer dan rekening tujuan sekolah, alokasi pembayaran, verifikasi tunai/non-tunai, refund uang daftar ulang saat wali mengajukan pengunduran diri (30% ditahan, 70% dikembalikan, dapat diatur), kas/bank, kategori transaksi, mutasi, transfer akun, rekening dan mutasi tabungan (wali hanya melihat), batch setoran serta rekonsiliasi.

### Pegawai & Kehadiran (PRD-PEG)
Profil pegawai, jabatan, riwayat jabatan, dokumen pegawai, absensi pegawai mandiri lewat ponsel (jam masuk/pulang, wajib di area sekolah/GPS; pegawai tanpa akun dicatat admin). Guru adalah jabatan. Payroll/HR lengkap tidak termasuk.

### Administrasi, Dokumen & Komunikasi (PRD-ADM)
Jenis/template/surat (keluaran PDF dan Word), dokumen dan versi, verifikasi, kalender akademik, agenda, pengumuman satu arah, penerima, notifikasi portal, email, audit log.

### Rapor LCPA (PRD-RAP)
Template rapor berbasis dokumen sumber, input manual tingkat ketercapaian dan narasi oleh wali kelas, Refleksi Orang Tua (tulis tangan di cetakan atau diisi wali di portal, opsional), skala BB/MB/BSH/BSB, persetujuan Kepala RA, pemakaian data absensi/pertumbuhan jika tersedia, snapshot versi terbit, dan PDF privat dengan gambar tanda tangan. Rapor tidak bergantung pada modul pembelajaran yang ditunda.

### Laporan & Dashboard (PRD-LAP)
Rekap absensi siswa, rekap absensi pegawai (hadir dan terlambat), tagihan/tunggakan, arus kas per akun, saldo tabungan, status PPDB, dan dashboard per role. Ekspor CSV/PDF untuk data murid, laporan SPP, dan transaksi. Rincian di `05-perencanaan/report-catalog.md` (DEC-043, DEC-085).

### Website/CMS (PRD-CMS)
Halaman publik, program sekolah, galeri/media, slide “Curriculum Highlight”, logo/favicon/hak cipta, informasi pendaftaran (biaya diambil dari pengaturan keuangan), pengaturan website. CMS bukan database kedua untuk data siswa/pegawai. Landing page dan dashboard admin dirancang baru (DEC-095).

## 6. Dikecualikan dari V2 awal

- Aplikasi mobile native.
- Chat/komentar/balasan wali murid.
- WhatsApp sebagai kanal wajib.
- Payroll dan HR lengkap.
- Akuntansi lanjutan/double-entry penuh.
- Pengadaan, transportasi, dan microservices.
- Modul keselamatan/perlindungan anak khusus: **di luar cakupan awal**.

## 7. Ditunda, bukan dibatalkan

- Kurikulum.
- Komponen Kurikulum.
- Tujuan Pembelajaran.
- Perencanaan Pembelajaran.
- RPPM.
- RPPH.
- Pelaksanaan Pembelajaran (label yang menggantikan “Jurnal Guru”).
- Asesmen.
- Perkembangan berbasis asesmen.

Workflow pembelajaran RA perlu dipelajari sebelum modul-modul tersebut didesain. Modul ini tidak menjadi dependensi rapor V2 awal.

## 8. Prinsip produk

- Catat data sekali, gunakan kembali sesuai kewenangan.
- Histori bisnis penting tidak dihapus.
- Status menggunakan lifecycle terkontrol.
- Backend adalah otoritas final untuk validasi dan permission.
- Operasi keuangan/tabungan atomik, idempoten, dan dapat diaudit.
- Satu orang maksimal memiliki satu akun pengguna, tetapi bisa memiliki beberapa profil bisnis jika relevan.
- Semua fitur utama responsif, berbahasa Indonesia, sederhana dan konsisten.
- Jangan menambah layanan infrastruktur jika manfaatnya belum jelas.

## 9. Kriteria keberhasilan

- PPDB dapat ditelusuri dari pengajuan sampai keputusan.
- Wali hanya melihat anak yang berhubungan sah dengannya.
- Guru tidak dapat membuka data kelas lain dengan URL atau request langsung.
- Alokasi tagihan konsisten; cicilan hanya pada daftar ulang (biaya pendaftaran dan SPP wajib lunas per tagihan).
- Transaksi yang diposting tidak dapat diubah/dihapus biasa.
- Setoran tabungan direkonsiliasi, termasuk selisih.
- Snapshot rapor tidak berubah setelah terbit.
- File privat terlindungi.
- Fitur utama (alur harian tiap role: login, PPDB, absensi, pembayaran, setoran, rapor, pengumuman) dapat diselesaikan pada ponsel dan desktop sesuai browser target di bagian 11.
- Backup/restore diuji sebelum produksi.

## 10. Asumsi dan batas

- Target awal satu sekolah; multi-tenant bukan kebutuhan V2.
- Laravel 13, React + TypeScript, Inertia, shadcn/ui, Tailwind, dan MySQL telah dipilih.
- Shared hosting merupakan preferensi, bukan bukti semua fitur dapat berjalan di semua paket.
- Mailjet dipakai untuk development; email produksi memakai Mailjet atau provider transactional sejenis (DEC-053).
- Storage produksi: disk server di folder non-publik (DEC-053). Mesin PDF dan vendor hosting belum final.
- Nominal/default keuangan harus dapat dikonfigurasi.

## 11. Kebutuhan nonfungsional (nilai awal, DEC-042)

- **Zona waktu:** Asia/Jakarta (WIB), dikonfigurasi satu tempat.
- **Aksesibilitas:** WCAG 2.1 tingkat AA sebagai target.
- **Perangkat dan browser:** browser modern dua versi terakhir pada desktop dan ponsel.
- **Skala:** sekitar 300 siswa (perkiraan, belum dikonfirmasi) dan staf sekolah; satu sekolah.
- **Keamanan:** tanpa MFA (DEC-041); kata sandi minimum 12 karakter; parameter lain di `auth-rbac.md` dan `security-baseline.md`.
- **Backup:** mingguan, retensi 8 minggu, RTO sasaran 1 hari kerja; risiko kehilangan data hingga sekitar 7 hari diterima pemilik (DEC-047).
- **Retensi:** tidak ada data yang dihapus; seluruh record menjadi arsip sekolah (DEC-071). Risiko terhadap ketentuan perlindungan data pribadi perlu ditinjau sebelum produksi.
- **Belum ditetapkan:** target kinerja terukur (waktu respons) dan jam layanan/ketersediaan.
