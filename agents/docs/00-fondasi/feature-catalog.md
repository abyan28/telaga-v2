# Katalog Fitur TELAGA v2

Status fitur dibatasi ke empat label: **Inti**, **Ditunda**, **Di luar cakupan**, **Perlu keputusan**. Label ini khusus untuk fitur; status keputusan (DISETUJUI, REKOMENDASI, TERBUKA, DITUNDA, DI LUAR CAKUPAN) pada README dan decision-log berlaku untuk keputusan. Daftar “Ditunda” dan “Di luar cakupan” di dokumen ini adalah sumber tunggal; dokumen lain merujuk ke sini.

## Inti V2

| Domain | Fitur inti | Catatan batas |
|---|---|---|
| Identitas & Akses | Pengguna, login, OTP, role, permission, Policy, audit | Signup mandiri hanya wali murid |
| Data Master | Tahun ajaran, periode, kelas, ruangan, referensi | Data harus mempunyai status/ruang lingkup waktu yang jelas |
| PPDB | Periode, formulir, wali, dokumen, biaya pendaftaran, histori status, pemeriksaan dan keputusan | Satu pengajuan untuk satu calon anak; satu wali dapat memiliki banyak pengajuan; data calon disimpan di pendaftaran |
| Kesiswaan | Siswa, wali, Riwayat Penempatan Siswa, absensi, kesehatan, pertumbuhan, portofolio | Data sensitif dibatasi oleh role/scope |
| Keuangan siswa | Jenis tagihan, tagihan, pembayaran, alokasi, verifikasi, refund uang daftar ulang | Pembayaran atas nama wali; parsial dan tunai/non-tunai; tanpa diskon/prorata. Refund saat wali mengundurkan diri setelah daftar ulang (30% ditahan); disetujui Admin Sekolah atau Bendahara (DEC-056) |
| Keuangan sekolah | Kas/bank, pemasukan/pengeluaran, kategori, transfer, laporan | Tidak termasuk double-entry accounting penuh |
| Tabungan siswa | Rekening, batch setoran, rincian, penerimaan/rekonsiliasi, penarikan, mutasi | Saldo resmi berubah saat posting; wali kelas mencatat setoran; wali hanya melihat saldo/mutasi |
| Pegawai & Kehadiran | Profil pegawai, jabatan, histori jabatan, penugasan akademik, absensi pegawai mandiri (ponsel, GPS area sekolah) dengan koreksi admin | Guru adalah jabatan; tidak ada payroll lengkap |
| Administrasi | Kalender akademik, agenda, jenis/template/surat | Surat historis mempertahankan template/data versi yang dipakai; keluaran PDF dan Word |
| Dokumen | Jenis, metadata, berkas/versi, verifikasi, relasi konteks | Dokumen privat secara default |
| Rapor LCPA | Draf, input manual yang diperlukan, Refleksi Orang Tua (portal opsional), persetujuan Kepala RA, data auto-fill tersedia, snapshot, PDF dan versi | Tidak bergantung pada modul asesmen yang ditunda |
| Laporan & dashboard | Rekap absensi, tagihan/tunggakan, arus kas per akun, saldo tabungan, status PPDB, dashboard per role | Rincian di `05-perencanaan/report-catalog.md` |
| Komunikasi | Pengumuman satu arah, daftar penerima, portal notification, email | Tidak ada balasan/komentar wali |
| Website/CMS | Halaman, program sekolah, galeri/media, informasi pendaftaran, konfigurasi | Tidak menduplikasi data siswa/pegawai |
| Operasional | Audit log, pengaturan, backup/recovery, logging | Backup dan restore harus diuji |

### Tambahan dari pelajaran sistem lama (DEC-078..096)

| Domain | Fitur | Catatan |
|---|---|---|
| PPDB | Buka/tutup + tahun ajaran target terpisah, kuota, validasi usia 4–6 tahun, izin edit wali, ubah keputusan, Calon Murid + Buat NIS | Murid `calon` sampai NIS |
| Identitas | Login email/no. HP/username; kode aktivasi akun wali lama | Tanpa kata sandi awal dari NIK |
| Kesiswaan | Profil lengkap murid, profil ayah dan ibu, alamat berjenjang, angkatan dari NIS, impor CSV/Excel | |
| Keuangan | Cicilan hanya daftar ulang; bank asal transfer; rekening tujuan sekolah; tanggal terbit SPP dapat diatur | |
| Laporan | Ekspor CSV/PDF (murid, SPP, transaksi) | Diaudit |
| Notifikasi | Email otomatis dengan rincian transaksi | Lewat antrean |
| Tahun ajaran | Wizard “Tahun ajaran berikutnya” | Rincian: OD-37 |
| CMS | Biaya dari pengaturan, logo/favicon/hak cipta, slide Curriculum Highlight | |

## Ditunda, bukan dibatalkan

- Kurikulum.
- Komponen Kurikulum.
- Tujuan Pembelajaran.
- Perencanaan Pembelajaran.
- RPPM.
- RPPH.
- Pelaksanaan Pembelajaran.
- Asesmen.
- Perkembangan berbasis asesmen.

Sebelum membangun paket ini, lakukan discovery dengan guru/pengelola RA untuk memahami proses riil.

## Di luar cakupan V2 awal

- Modul khusus keselamatan/perlindungan anak.
- Native mobile app.
- MFA/otentikasi dua faktor (DEC-041).
- Fitur izin/persetujuan publikasi (DEC-036).
- Diskon, beasiswa, dan prorata tagihan (DEC-037).
- Permohonan penarikan tabungan oleh wali di portal (DEC-044).
- Chat, komentar, dan balasan dua arah kepada wali.
- WhatsApp sebagai kanal wajib.
- Payroll/HR lengkap.
- Akuntansi lanjutan/double-entry penuh.
- Pengadaan, transportasi, dan microservices.

## Perlu keputusan atau proof of concept

- Provider dan paket hosting.
- Versi MySQL yang didukung hosting.
- Strategi queue di shared hosting.
- PDF renderer untuk LCPA.
- Detail struktur tabel rapor (butir capaian, foto, narasi).
- Kebijakan retensi dokumen dan data pribadi (OD-14).
