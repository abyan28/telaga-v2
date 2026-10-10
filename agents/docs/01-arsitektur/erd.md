# ERD Logis TELAGA v2

- Status: ERD konseptual/logis, bukan DDL siap produksi. Disinkronkan dengan `data-dictionary.md`.
- Database yang dipilih: MySQL.
- Penamaan: tabel domain `snake_case` Bahasa Indonesia; PK `id_<entitas>`; FK memakai nama PK yang dirujuk (DEC-028). Tabel kerangka Laravel tidak digambarkan.
- Tipe data final, panjang kolom, unique index, check constraint, dan strategi soft delete ditetapkan bersama data dictionary dan versi MySQL hosting target.

## 1. ERD inti — identitas, akademik, PPDB, dan penempatan

```mermaid
erDiagram
    ORANG ||--|| PENGGUNA : "memiliki akun (wajib per pengguna)"
    ORANG ||--o| SISWA : memiliki_profil
    ORANG ||--o| PROFIL_WALI : memiliki_profil
    ORANG ||--o| PEGAWAI : memiliki_profil
    PENGGUNA }o--o{ ROLE : melalui_pengguna_role
    ROLE }o--o{ PERMISSION : melalui_role_permission
    PENGGUNA ||--o{ KODE_VERIFIKASI : menerima
    PENGGUNA ||--o{ TOKEN_RESET_KATA_SANDI : memiliki
    ORANG ||--o{ UNDANGAN_PENGGUNA : diundang
    PENGGUNA ||--o{ AUDIT_LOG : pelaku
    PROFIL_WALI }o--o{ SISWA : melalui_hubungan_siswa_wali
    PENGGUNA ||--o{ KODE_AKTIVASI : memiliki
    PROFIL_WALI ||--o| KELUARGA : alamat
    KELUARGA }o--|| WILAYAH_KELURAHAN : lokasi
    WILAYAH_PROVINSI ||--o{ WILAYAH_KOTA : memiliki
    WILAYAH_KOTA ||--o{ WILAYAH_KECAMATAN : memiliki
    WILAYAH_KECAMATAN ||--o{ WILAYAH_KELURAHAN : memiliki
    SISWA ||--o| PROFIL_MURID : memiliki
    BANK ||--o{ PEMBAYARAN : bank_asal
    TAHUN_AJARAN ||--o{ PERIODE_PEMBELAJARAN : memiliki
    TAHUN_AJARAN ||--o{ KELAS : menaungi
    RUANGAN o|--o{ KELAS : digunakan
    PERIODE_PENDAFTARAN ||--o{ PENDAFTARAN : menerima
    TAHUN_AJARAN ||--o{ PERIODE_PENDAFTARAN : tujuan
    PERIODE_PENDAFTARAN ||--o{ PERSYARATAN_DOKUMEN_PENDAFTARAN : mensyaratkan
    JENIS_DOKUMEN ||--o{ PERSYARATAN_DOKUMEN_PENDAFTARAN : jenis
    PENDAFTARAN }o--o| ORANG : calon_orang_setelah_diterima
    PENDAFTARAN }o--o| SISWA : menjadi_siswa
    PENDAFTARAN ||--o{ RIWAYAT_STATUS_PENDAFTARAN : memiliki_histori
    PENDAFTARAN }o--o{ PROFIL_WALI : melalui_pendaftaran_wali
    SISWA ||--o{ RIWAYAT_PENEMPATAN_SISWA : memiliki
    KELAS ||--o{ RIWAYAT_PENEMPATAN_SISWA : ditempati
    PEGAWAI ||--o{ PENUGASAN_AKADEMIK : mendapat_tugas
    KELAS ||--o{ PENUGASAN_AKADEMIK : memiliki
    JENIS_PENUGASAN ||--o{ PENUGASAN_AKADEMIK : jenis
    PERIODE_PEMBELAJARAN o|--o{ PENUGASAN_AKADEMIK : cakupan
    PEGAWAI ||--o{ RIWAYAT_JABATAN_PEGAWAI : memiliki
    JABATAN ||--o{ RIWAYAT_JABATAN_PEGAWAI : dicatat
```

## 2. ERD logis — kesiswaan dan kehadiran

```mermaid
erDiagram
    SISWA ||--o{ ABSENSI_SISWA : memiliki
    KELAS ||--o{ ABSENSI_SISWA : konteks
    SISWA ||--o| PROFIL_KESEHATAN : memiliki
    SISWA ||--o{ PENGUKURAN_PERTUMBUHAN : memiliki
    SISWA ||--o{ PORTOFOLIO : memiliki
    PEGAWAI ||--o{ ABSENSI_PEGAWAI : memiliki
```

## 3. ERD logis — keuangan dan tabungan

```mermaid
erDiagram
    JENIS_TAGIHAN ||--o{ TAGIHAN : menjadi_dasar
    TAHUN_AJARAN ||--o{ TAGIHAN : periode
    SISWA o|--o{ TAGIHAN : ditagih_atau
    PENDAFTARAN o|--o{ TAGIHAN : ditagih_atau
    PROFIL_WALI ||--o{ PEMBAYARAN : pembayar
    METODE_PEMBAYARAN ||--o{ PEMBAYARAN : digunakan
    PEMBAYARAN ||--o{ ALOKASI_PEMBAYARAN : memiliki
    TAGIHAN ||--o{ ALOKASI_PEMBAYARAN : dialokasikan
    PEMBAYARAN o|--o{ PEMBAYARAN : reversal_dari
    PEMBAYARAN ||--o{ REFUND_PEMBAYARAN : direfund
    AKUN_KEUANGAN ||--o{ MUTASI_KEUANGAN : mencatat
    KATEGORI_TRANSAKSI_KEUANGAN ||--o{ MUTASI_KEUANGAN : mengategorikan
    PEMBAYARAN o|--o{ MUTASI_KEUANGAN : sumber
    REFUND_PEMBAYARAN o|--o{ MUTASI_KEUANGAN : sumber
    TRANSFER_KEUANGAN o|--o{ MUTASI_KEUANGAN : sumber
    MUTASI_KEUANGAN o|--o{ MUTASI_KEUANGAN : reversal_dari
    AKUN_KEUANGAN ||--o{ TRANSFER_KEUANGAN : sumber_akun
    AKUN_KEUANGAN ||--o{ TRANSFER_KEUANGAN : tujuan_akun
    SISWA ||--o| REKENING_TABUNGAN : memiliki
    REKENING_TABUNGAN ||--o{ MUTASI_TABUNGAN : mencatat
    SETORAN_TABUNGAN ||--o{ DETAIL_SETORAN_TABUNGAN : merangkum
    SISWA ||--o{ DETAIL_SETORAN_TABUNGAN : menyetor_untuk
    DETAIL_SETORAN_TABUNGAN o|--o{ MUTASI_TABUNGAN : menghasilkan
    PENGGUNA ||--o{ SETORAN_TABUNGAN : pencatat
    MUTASI_TABUNGAN o|--o{ MUTASI_TABUNGAN : reversal_dari
```

Pembayaran, alokasi, mutasi keuangan, setoran tabungan, dan mutasi tabungan adalah catatan bisnis yang berbeda. Jangan menyatukannya dalam satu tabel transaksi generik yang menghilangkan integritas FK. `tagihan` terikat tepat satu dari `siswa` atau `pendaftaran`.

## 4. ERD logis — dokumen, komunikasi, rapor, dan CMS

```mermaid
erDiagram
    JENIS_DOKUMEN ||--o{ DOKUMEN : mengelompokkan
    DOKUMEN ||--o{ VERSI_DOKUMEN : memiliki_versi
    VERSI_DOKUMEN ||--o{ VERIFIKASI_DOKUMEN : diverifikasi
    JENIS_SURAT ||--o{ TEMPLATE_SURAT : memiliki_template
    TEMPLATE_SURAT ||--o{ SURAT : dipakai_untuk
    JENIS_SURAT ||--o{ SURAT : jenis
    PENGUMUMAN ||--o{ TARGET_PENGUMUMAN : menargetkan
    PENGUMUMAN ||--o{ VERSI_PENGUMUMAN : memiliki_versi
    PENGUMUMAN ||--o{ PENERIMA_PENGUMUMAN : dikirim_ke
    PENGGUNA ||--o{ PENERIMA_PENGUMUMAN : menjadi_penerima
    PENGGUNA ||--o{ NOTIFIKASI : menerima
    KALENDER_AKADEMIK ||--o{ AGENDA : memiliki
    TAHUN_AJARAN ||--o{ KALENDER_AKADEMIK : memiliki
    JENIS_KEGIATAN ||--o{ AGENDA : mengategorikan
    SISWA ||--o{ RAPOR : memiliki
    PERIODE_PEMBELAJARAN ||--o{ RAPOR : periode
    BUTIR_CAPAIAN ||--o{ RAPOR_CAPAIAN : dinilai
    RAPOR ||--o{ RAPOR_CAPAIAN : tingkat
    RAPOR ||--o{ RAPOR_BAGIAN : narasi
    RAPOR ||--o{ RAPOR_PANCA_CINTA : aspek
    RAPOR ||--o{ RAPOR_FOTO : dokumentasi
    RAPOR ||--o{ RAPOR_PENGUKURAN : ddtk
    RAPOR ||--o{ RAPOR_EKSTRAKURIKULER : ekstra
    RAPOR ||--o{ RAPOR_REFLEKSI : refleksi
    RAPOR ||--o{ RAPOR_VERSI : mempunyai_snapshot
    PEGAWAI ||--o{ TANDA_TANGAN : memiliki
    MEDIA }o--o{ GALERI : melalui_galeri_media
    HALAMAN ||--o{ MEDIA : referensi_opsional
    PERIODE_PENDAFTARAN o|--o{ INFORMASI_PENDAFTARAN : konten
```

Entitas rapor mengikuti struktur template LCPA; field final dirancang saat Tahap 6 dan setelah proof of concept PDF. Entitas pendukung tanpa relasi bisnis di diagram: `riwayat_pengiriman_email`, `pengaturan_sistem`, `riwayat_pengaturan_sistem`, `profil_sekolah`, `penomoran`, `program_sekolah`, `pengaturan_website`, `audit_log` (dirujuk pada diagram 1).

## 5. Kelompok entitas

### Identitas & akses
- `orang`, `pengguna`, `kode_verifikasi`, `token_reset_kata_sandi`, `undangan_pengguna`, `role`, `permission`, `pengguna_role`, `role_permission`, `audit_log`.
- `siswa`, `profil_wali`, `hubungan_siswa_wali`, `pegawai`.

### Pengaturan
- `pengaturan_sistem`, `riwayat_pengaturan_sistem`, `profil_sekolah`, `penomoran`.

### Data master dan akademik dasar
- `tahun_ajaran`, `periode_pembelajaran`, `kelas`, `ruangan`, `jenis_penugasan`, `penugasan_akademik`.

### PPDB dan kesiswaan
- `periode_pendaftaran`, `persyaratan_dokumen_pendaftaran`, `pendaftaran`, `pendaftaran_wali`, `riwayat_status_pendaftaran`, `riwayat_penempatan_siswa`, `absensi_siswa`, `profil_kesehatan`, `pengukuran_pertumbuhan`, `portofolio`.

### Pegawai
- `jabatan`, `riwayat_jabatan_pegawai`, `absensi_pegawai`, `tanda_tangan`.

### Keuangan
- `jenis_tagihan`, `tagihan`, `pembayaran`, `alokasi_pembayaran`, `refund_pembayaran`, `metode_pembayaran`, `akun_keuangan`, `kategori_transaksi_keuangan`, `mutasi_keuangan`, `transfer_keuangan`, `rekening_tabungan`, `setoran_tabungan`, `detail_setoran_tabungan`, `mutasi_tabungan`.

### Administrasi dan komunikasi
- `jenis_surat`, `template_surat`, `surat`, `jenis_dokumen`, `dokumen`, `versi_dokumen`, `verifikasi_dokumen`, junction dokumen eksplisit, `kalender_akademik`, `jenis_kegiatan`, `agenda`, `pengumuman`, `target_pengumuman`, `versi_pengumuman`, `penerima_pengumuman`, `notifikasi`, `riwayat_pengiriman_email`.

### Rapor dan CMS
- `rapor`, `butir_capaian`, `rapor_capaian`, `rapor_bagian`, `rapor_panca_cinta`, `rapor_foto`, `rapor_pengukuran`, `rapor_ekstrakurikuler`, `rapor_refleksi`, `rapor_versi` (detail setelah PoC); `halaman`, `program_sekolah`, `galeri`, `media`, `galeri_media`, `informasi_pendaftaran`, `pengaturan_website`.

## 6. Hal yang belum boleh dianggap final

1. Struktur detail data rapor dan cara mengarsipkan snapshot PDF.
2. Field-level dictionary untuk entitas Tahap 3 dan seterusnya.
3. Batas unique key untuk orang, nomor identitas, dan data wali (pencocokan manual, DEC-027).
4. Cara mengelola saldo rekening dan saldo tabungan sebagai nilai turunan/cache.
5. Detail field dan constraint harus cocok dengan versi MySQL hosting; jangan menggunakan fitur MySQL yang belum terverifikasi.
