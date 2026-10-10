# Katalog Laporan dan Dashboard V2

Dasar: DEC-043. Laporan adalah hasil query dari data utama, bukan salinan data. Setiap laporan menjalankan otorisasi dan scope; ekspor adalah tindakan sensitif yang diaudit. Definisi di bawah adalah rancangan awal; rumus rinci dan kolom final ditetapkan saat modul terkait dikembangkan. Butir ⚠ perlu konfirmasi pemilik.

## 1. Laporan

| ID | Laporan | Sumber data | Filter utama | Role (default ⚠) |
|---|---|---|---|---|
| LAP-01 | Rekap absensi siswa | `absensi_siswa`, penempatan, kelas | kelas, rentang tanggal, siswa | Admin, Kepala RA, wali kelas/pendamping (kelasnya) |
| LAP-02 | Tagihan dan tunggakan | `tagihan`, `alokasi_pembayaran` | periode, jenis tagihan, kelas, status | Admin Utama, Bendahara, Kepala RA (baca) |
| LAP-03 | Arus kas per akun | `mutasi_keuangan`, `akun_keuangan` | akun, rentang tanggal, kategori | Admin Utama, Bendahara, Kepala RA (baca) |
| LAP-04 | Saldo dan mutasi tabungan | `rekening_tabungan`, `mutasi_tabungan` | kelas, siswa, rentang tanggal | Admin Utama, Bendahara, wali kelas/pendamping (kelasnya); wali (anaknya) |
| LAP-06 | Rekap absensi pegawai (hadir, terlambat) | `absensi_pegawai` | pegawai, rentang tanggal | Admin Utama, Admin Sekolah, Kepala RA |
| LAP-05 | Status PPDB | `pendaftaran`, `riwayat_status_pendaftaran` | periode, status | Admin, Kepala RA |

Aturan umum:
- Transfer antar-akun tidak dihitung sebagai pemasukan/pengeluaran (BR-KEU-003).
- Laporan keuangan dapat ditelusuri ke mutasi sumber (BR-KEU-009).
- Angka mengikuti data terposting; data menunggu verifikasi/posting ditampilkan terpisah, tidak dicampur.
- Data tidak lengkap ditandai, bukan dikarang.

## 2. Dashboard per role

Setiap angka dashboard harus mempunyai definisi dan sumber yang dapat ditelusuri (lihat tabel).

| Role | Kartu/ringkasan | Definisi dan sumber |
|---|---|---|
| Admin Utama / Admin Sekolah | Pendaftaran menunggu pemeriksaan; pendaftaran menunggu keputusan; tugas yang perlu tindakan | jumlah `pendaftaran` per status |
| Bendahara | Pembayaran menunggu verifikasi; setoran menunggu diterima; total tunggakan | jumlah pembayaran `menunggu_verifikasi`; setoran `menunggu_diterima`; jumlah sisa tagihan `belum_lunas` melewati jatuh tempo |
| Guru (wali kelas / pendamping) | Kelas yang ditugaskan; absensi hari ini belum diisi; rapor menunggu penyusunan (wali kelas) | penugasan berlaku; absensi tanggal berjalan; rapor `draf` |
| Kepala RA | Rapor menunggu persetujuan; ringkasan kehadiran; ringkasan PPDB | rapor `menunggu_persetujuan`; LAP-01 dan LAP-05 ringkas |
| Wali | Status pendaftaran; tagihan belum lunas; saldo tabungan anak; pengumuman dan notifikasi terbaru; rapor terbit; Refleksi Orang Tua yang perlu diisi | data anak terverifikasi |

## 2A. Ekspor (DEC-085, BR-IMP-002)

Format CSV dan PDF. Ekspor mengikuti scope dan izin pengguna, serta tercatat di audit.

| Ekspor | Filter | Role (default) |
|---|---|---|
| Data murid | kelas, tahun ajaran, status, angkatan/murid baru | Admin Utama, Admin Sekolah |
| Laporan SPP | murid, orang tua, tahun ajaran, bulan, periode tanggal, status bayar (sudah/belum/semua) | Admin Utama, Bendahara, Kepala RA (baca) |
| Transaksi | jenis, status, tanggal | Admin Utama, Bendahara |

## 3. Belum ditetapkan

- Ekspor dalam format Excel (.xlsx) tidak termasuk V2 awal (impor mendukung Excel; ekspor CSV dan PDF).
- Laporan tambahan di luar daftar di atas (mis. laporan pegawai) tidak termasuk V2 awal kecuali diputuskan.
