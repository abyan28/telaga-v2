# Data Dictionary Logis TELAGA v2

Dokumen ini mendefinisikan entitas dan field. Status per bagian:

- **Field-level (usulan awal)** — bagian 1–3 dan `riwayat_penempatan_siswa`: tipe, nullability, kunci, dan constraint sudah diusulkan untuk Tahap 1–2. Ini masih usulan untuk ditinjau sebelum migrasi pertama dan diuji pada MySQL hosting target; tipe final ditetapkan di migrasi.
- **Struktural** — bagian 4–6: tujuan entitas dan field logis, dengan perbaikan struktural hasil audit. Field-level akan dilengkapi sebelum tahap modul terkait dimulai (OD-10).
- Tanda **⚠** = atribut/nilai yang perlu konfirmasi pemilik sebelum migrasi.

## Konvensi

- Nama tabel/kolom domain: Bahasa Indonesia `snake_case` (DEC-028). Tabel kerangka Laravel/paket (sessions, jobs, failed_jobs, cache, migrations, dll.) memakai nama bawaan.
- PK: `id_<entitas>`; FK: `id_<entitas_yang_dirujuk>`. Tipe usulan: `BIGINT UNSIGNED AUTO_INCREMENT` untuk semua PK/FK (konsisten, signedness sama). Keputusan final saat bootstrap setelah versi MySQL hosting diperiksa.
- Kolom waktu audit memakai `created_at` dan `updated_at` bawaan Laravel (DEC-028). Kolom waktu bisnis diberi nama eksplisit (`tanggal_absensi`, `tanggal_pembayaran`, `tanggal_mulai`). Pelaku tindakan disimpan sebagai `id_pengguna_<peran>` hanya bila relevan.
- Nominal uang: `DECIMAL(15,2)` rupiah; tidak memakai float/double. Persentase: `DECIMAL(5,2)`.
- Tanggal bisnis: `DATE`. Peristiwa: `DATETIME` disimpan dan ditampilkan menurut zona waktu Asia/Jakarta (DEC-042).
- Status memakai kode terbatas (`ENUM` atau tabel referensi sesuai pola Laravel) dengan transisi eksplisit (`status-lifecycle.md`).
- Kolom `email_verified_at` pada `pengguna` mengikuti nama bawaan Laravel karena terkait langsung dengan mekanisme verifikasi kerangka.

## 1. Identitas & akses (field-level, usulan)

> Pembaruan DEC-078..096: bagian “Tambahan dari sistem lama” di akhir bagian 3 menambahkan field murid, profil ayah/ibu, alamat berjenjang, bank, kode aktivasi, dan kolom pengaturan baru. Tabel `pengguna` mendapat `username` dan `no_hp` (login tiga identitas, DEC-083).

### `orang`
| Kolom | Tipe | Null | Keterangan |
|---|---|---|---|
| id_orang | bigint unsigned PK | tidak | |
| nama_lengkap | varchar(150) | tidak | |
| jenis_kelamin | enum('laki_laki','perempuan') | ya | wajib untuk calon/murid (DEC-082) |
| tempat_lahir | varchar(100) | ya | wajib untuk calon/murid dan orang tua (DEC-082) |
| tanggal_lahir | date | ya | wajib untuk calon siswa/siswa pada level aplikasi |
| alamat | text | ya | jalan/RT/RW; alamat berjenjang keluarga disimpan di `keluarga` (BR-SIS-013) |
| nomor_telepon | varchar(30) | ya | |
| email_kontak | varchar(190) | ya | kontak saja; bukan email login (login ada di `pengguna.email`) |
| nomor_identitas | varchar(32) | ya | NIK 16 digit angka, unik; wajib untuk calon/murid (BR-SIS-012); tidak dipakai untuk penggabungan otomatis |
| status | enum('aktif','nonaktif') | tidak | default `aktif` |
| created_at, updated_at | timestamp | ya | |

Index: `(nama_lengkap)`, `(tanggal_lahir)`. **Tanpa** unique pada nama. Pencocokan kemungkinan duplikat dilakukan aplikasi dan ditinjau staf (BR-ID-015, DEC-027).

### `pengguna`
| Kolom | Tipe | Null | Keterangan |
|---|---|---|---|
| id_pengguna | bigint unsigned PK | tidak | |
| id_orang | bigint unsigned FK | **tidak** | UNIQUE; RESTRICT (BR-ID-002) |
| email | varchar(190) | **ya** | UNIQUE bila diisi; null sampai wali lama mengisi email saat aktivasi (DEC-083) |
| username | varchar(30) | ya | UNIQUE; 6–30 karakter `[A-Za-z0-9_.]`; boleh dipakai login |
| no_hp | varchar(20) | ya | UNIQUE; ternormalisasi (0 → 62); boleh dipakai login |
| kata_sandi | varchar(255) | tidak | hash; model mengoverride kolom password bawaan |
| status_akun | enum('aktif','ditangguhkan','dinonaktifkan') | tidak | terpisah dari verifikasi email (BR-ID-009) |
| email_verified_at | datetime | ya | null = belum diverifikasi |
| remember_token | varchar(100) | ya | |
| terakhir_login_at | datetime | ya | |
| created_at, updated_at | timestamp | ya | |

Satu email = satu akun. Dua wali yang berbagi satu email tidak dapat memiliki dua akun; salah satunya perlu email berbeda atau mendaftar dengan email sendiri. Karena login juga lewat no. HP/username (DEC-083), pasangan wali yang berbagi email tetap dapat memiliki akun masing-masing dengan username/no. HP berbeda.

### `kode_verifikasi` (OTP)
| Kolom | Tipe | Null | Keterangan |
|---|---|---|---|
| id_kode_verifikasi | bigint unsigned PK | tidak | |
| id_pengguna | FK | tidak | CASCADE (data teknis) |
| tujuan | enum('verifikasi_email','reset_kata_sandi') | tidak | tidak dapat saling dipakai (BR-AUTH-009) |
| kode_hash | varchar(255) | tidak | tidak menyimpan kode asli |
| berlaku_sampai | datetime | tidak | TTL dari `pengaturan_sistem` (awal 10 menit) |
| percobaan | tinyint unsigned | tidak | default 0; maks 5 |
| dikirim_pada | datetime | tidak | dasar jeda kirim ulang 60 detik |
| dikonsumsi_pada | datetime | ya | |
| dibatalkan_pada | datetime | ya | diisi saat kode baru terbit |
| alamat_ip | varchar(45) | ya | |
| created_at | timestamp | ya | |

Index: `(id_pengguna, tujuan, dikonsumsi_pada)`. Kode aktif per (pengguna, tujuan) maksimal satu, dipastikan di transaksi aplikasi.

### `kode_aktivasi`
`id_kode_aktivasi` PK, `id_pengguna` FK, `kode_hash` varchar(255), `berlaku_sampai` datetime, `percobaan` tinyint (dibatasi), `dipakai_pada` datetime null, `dibatalkan_pada` datetime null, `id_pengguna_pembuat` FK, `created_at`. Dibuat admin untuk akun wali lama (BR-AUTH-017); kode baru membatalkan yang lama; tidak ada kata sandi awal dari NIK/no. HP.

### `token_reset_kata_sandi`
`id_token_reset_kata_sandi` PK, `id_pengguna` FK, `token_hash` varchar(255), `berlaku_sampai` datetime, `digunakan_pada` datetime null, `created_at`. Terbit hanya setelah OTP `reset_kata_sandi` valid (DEC-040). Sekali pakai.

### `undangan_pengguna`
`id_undangan_pengguna` PK, `id_orang` FK, `email` varchar(190), `id_role` FK (role yang diberikan setelah diterima), `token_hash` varchar(255), `berlaku_sampai` datetime (TTL awal 48 jam), `dipakai_pada` null, `dibatalkan_pada` null, `id_pengguna_pengundang` FK, `created_at`. Undangan baru membatalkan yang lama untuk orang yang sama (BR-AUTH-016).

### `role`, `permission`, `pengguna_role`, `role_permission`
| Tabel | Kolom |
|---|---|
| `role` | id_role PK; kode varchar(50) UNIQUE; nama varchar(100); deskripsi text null; sistem boolean (role sistem dilindungi dari penghapusan, mis. Admin Utama); created_at, updated_at |
| `permission` | id_permission PK; kode varchar(100) UNIQUE (`modul.objek.tindakan`); deskripsi varchar(255) |
| `pengguna_role` | id_pengguna FK, id_role FK; PK(id_pengguna, id_role); id_pengguna_pemberi FK; diberikan_pada datetime. Pencabutan menghapus baris dan dicatat di `audit_log`. |
| `role_permission` | id_role FK, id_permission FK; PK(id_role, id_permission) |

Nama tabel/kolom disesuaikan bila paket RBAC dipakai (DEC-024, DEC-028).

### `audit_log`
| Kolom | Tipe | Null | Keterangan |
|---|---|---|---|
| id_audit_log | bigint unsigned PK | tidak | |
| waktu | datetime(6) | tidak | |
| id_pengguna | FK | ya | null untuk tindakan sistem/anonim |
| aksi | varchar(100) | tidak | |
| objek_tipe | varchar(100) | tidak | |
| objek_id | varchar(64) | ya | |
| hasil | enum('berhasil','gagal','ditolak') | tidak | |
| alamat_ip | varchar(45) | ya | |
| ringkasan_perubahan | text (JSON sebagai teks) | ya | payload teknis terkontrol, tanpa password/OTP/token/isi file (BR-AUD-003); disimpan sebagai teks agar tidak bergantung pada tipe/fungsi JSON database (DEC-059) |

Append-only: aplikasi tidak mengubah atau menghapus baris. Index: `(objek_tipe, objek_id)`, `(id_pengguna, waktu)`, `(waktu)`.

### `pengaturan_sistem` dan `riwayat_pengaturan_sistem`
`pengaturan_sistem`: `kunci` varchar(100) PK, `nilai` text, `tipe` enum('integer','desimal','string','boolean'), `deskripsi`, `updated_at`, `id_pengguna_pengubah` FK null. `riwayat_pengaturan_sistem`: `id_riwayat` PK, `kunci`, `nilai_lama`, `nilai_baru`, `id_pengguna` FK, `waktu`. Setiap perubahan dicatat (BR-FIN-008).

Kunci awal: `spp_nominal_bawaan` (250000, DEC-060), `refund_persen_ditahan` (30), `otp_ttl_menit` (10), `otp_maks_percobaan` (5), `otp_jeda_kirim_ulang_detik` (60), `undangan_ttl_jam` (48), `reset_token_ttl_menit` (30), `login_maks_gagal` (5 per 5 menit), `otp_reset_maks_per_jam` (5), `sesi_idle_menit` (120), `unggah_maks_dokumen_mb` (5), `unggah_maks_gambar_mb` (2), `zona_waktu` (Asia/Jakarta), `sekolah_latitude`, `sekolah_longitude`, `absensi_radius_meter` (10, DEC-072; dapat diubah), `absensi_jam_masuk` (satu jam untuk semua pegawai; nilai awal 07:00 WIB, DEC-073), `absensi_hari_kerja` (Senin–Jumat).

### `profil_sekolah`
Satu baris: `id_profil_sekolah` PK, `nama_sekolah`, `nsm` varchar(12) (dipakai membentuk NIS), `npsn` varchar(10), `nomor_izin_operasional`, `nama_yayasan`, `alamat`, `nomor_telepon`, `email`, `id_pegawai_kepala` FK null (Kepala RA saat ini, dipakai pada pengesahan rapor), `updated_at`. Logo/media mengikuti `media`. Sumber field: profil RA pada dokumen referensi; nilai tidak disalin ke dokumentasi.

### `penomoran`
`id_penomoran` PK, `kode` varchar(50) (mis. `pendaftaran`, `siswa`, `surat:<id_jenis_surat>`), `tahun` smallint null, `nilai_terakhir` int unsigned, `format` varchar(100). UNIQUE(`kode`,`tahun`). Pengambilan nomor memakai `SELECT … FOR UPDATE` dalam transaksi agar tidak ganda.

Format yang diputuskan: nomor surat `urut/kode/bulan romawi/tahun` reset tahunan (DEC-051); NIS = NSM + 2 digit tahun + 3 digit urut (DEC-050), dibuat massal oleh aksi “Buat NIS” (urut abjad nama, susulan lanjut nomor); nomor pendaftaran `PPDB-{tahun}-{urut 4 digit}` unik per periode (DEC-058).

### `siswa`, `profil_wali`, `pegawai`
| Tabel | Kolom |
|---|---|
| `siswa` | id_siswa PK; id_orang FK UNIQUE; nomor_induk varchar(20) null UNIQUE (NIS, diisi oleh aksi “Buat NIS”, BR-SIS-011); nisn varchar(10) null UNIQUE; angkatan smallint null (dari 2 digit tahun NIS); tanggal_masuk date; status enum('calon','aktif','lulus','keluar') (DEC-066, DEC-079; `calon` sampai NIS dibuat); created_at, updated_at. Atribut tambahan murid di bagian “Tambahan dari sistem lama”. |
| `profil_wali` | id_profil_wali PK; id_orang FK UNIQUE; data pekerjaan, pendidikan, penghasilan, agama, dan no. HP orang tua ada pada bagian Tambahan dari sistem lama; created_at, updated_at |
| `pegawai` | id_pegawai PK; id_orang FK UNIQUE; nomor_pegawai (NIP; tanda “-” pada rapor bila kosong) varchar(30) null UNIQUE; nuptk varchar(20) null; pendidikan_terakhir varchar(100) null; keterangan_kepegawaian varchar(100) null; tanggal_mulai date (TMT); tanggal_berakhir date null; status_kerja enum('aktif','nonaktif'); dipublikasikan boolean default false (profil guru publik oleh petugas berwenang, DEC-036); created_at, updated_at |

### `hubungan_siswa_wali`
`id_hubungan_siswa_wali` PK; `id_siswa` FK; `id_profil_wali` FK; `jenis_hubungan` enum('ayah','ibu','wali_lain'); `wali_utama` boolean; `kontak_darurat` boolean; `status_verifikasi` enum('menunggu','terverifikasi','ditolak'); `dasar_verifikasi` enum('penerimaan_ppdb','bukti_dokumen') null; `id_dokumen_bukti` FK null (versi dokumen KK atau akta kelahiran anak untuk wali tambahan, DEC-063); `id_pengguna_verifikator` FK null; `diverifikasi_pada` datetime null; UNIQUE(`id_siswa`,`id_profil_wali`). Wali pengaju PPDB menjadi `terverifikasi` dengan dasar `penerimaan_ppdb`; wali tambahan menjadi `terverifikasi` oleh admin dengan `bukti_dokumen` (DEC-033, DEC-063). Akses wali ke data anak hanya bila `status_verifikasi = terverifikasi`.

Data minimal: kumpulkan hanya atribut yang punya tujuan operasional, terutama untuk anak (`security-baseline.md` §4).

## 2. Data master dan akademik dasar (field-level, usulan)

| Tabel | Kolom |
|---|---|
| `tahun_ajaran` | id_tahun_ajaran PK; nama varchar(20) UNIQUE; tanggal_mulai date; tanggal_selesai date; status enum('direncanakan','aktif','selesai'). Hanya satu tahun ajaran `aktif` sekaligus (DEC-066; ditegakkan di aplikasi/transaksi). |
| `periode_pembelajaran` | id_periode_pembelajaran PK; id_tahun_ajaran FK; kode varchar(20); nama varchar(50) (mis. Ganjil/Genap); tanggal_mulai; tanggal_selesai; **tanggal_cutoff_rapor** date (default = tanggal_selesai; dapat diubah admin, DEC-052); status enum('direncanakan','aktif','selesai'); UNIQUE(id_tahun_ajaran,kode) |
| `ruangan` | id_ruangan PK; kode varchar(20) UNIQUE; nama varchar(100); kapasitas smallint null; status enum('aktif','nonaktif') |
| `kelas` | id_kelas PK; id_tahun_ajaran FK; nama varchar(50) (mis. Kelompok A/B); tingkat varchar(30); fase varchar(30) default 'Fondasi' (kepala rapor); kapasitas smallint null; id_ruangan FK null; status enum('aktif','nonaktif'); UNIQUE(id_tahun_ajaran,nama) |
| `jenis_penugasan` | id_jenis_penugasan PK; kode varchar(30) UNIQUE (nilai awal `wali_kelas`, `guru_pendamping`); nama; aktif boolean |
| `penugasan_akademik` | id_penugasan_akademik PK; id_pegawai FK; id_kelas FK; id_jenis_penugasan FK; id_periode_pembelajaran FK null; tanggal_mulai date; tanggal_selesai date null. Akses guru berlaku selama tanggal operasi berada di rentang penugasan (DEC-046). |
| `riwayat_penempatan_siswa` | id_riwayat_penempatan_siswa PK; id_siswa FK; id_kelas FK; tanggal_mulai date; tanggal_selesai date null; alasan_perpindahan varchar(255) null; status enum('aktif','selesai'); id_pengguna FK (pelaku); created_at. Tidak ada tumpang tindih untuk satu siswa (BR-AKD-007); ditegakkan di transaksi dengan penguncian baris siswa (MySQL tidak dapat menjamin lewat CHECK). |

## 3. PPDB (field-level, usulan)

| Tabel | Kolom |
|---|---|
| `periode_pendaftaran` | id_periode_pendaftaran PK; nama varchar(100); id_tahun_ajaran FK; tanggal_buka date; tanggal_tutup date; status enum('draf','dibuka','ditutup','diarsipkan'); dipublikasikan boolean default false (BR-CMS-003) |
| `persyaratan_dokumen_pendaftaran` | id_persyaratan PK; id_periode_pendaftaran FK; id_jenis_dokumen FK; wajib boolean; UNIQUE(id_periode_pendaftaran,id_jenis_dokumen) (BR-PPDB-006) |
| `pendaftaran` | id_pendaftaran PK; id_periode_pendaftaran FK; nomor_pendaftaran varchar(30) berformat `PPDB-{tahun}-{urut 4 digit}`, UNIQUE(id_periode_pendaftaran,nomor_pendaftaran); status enum (lihat `status-lifecycle.md` §2); id_pengguna_pengaju FK; tanggal_pengajuan datetime null; **data calon**: nama_lengkap_calon varchar(150), jenis_kelamin_calon enum, tempat_lahir_calon varchar(100), tanggal_lahir_calon date, NIK calon varchar(16) unik, nama_panggilan_calon, alamat calon mengikuti alamat keluarga (`keluarga`), serta atribut `profil_murid`; id_orang_calon FK null (diisi saat diterima); id_siswa FK null (diisi saat diterima); kemungkinan_duplikat boolean; created_at, updated_at. Index non-unique `(nama_lengkap_calon, tanggal_lahir_calon, id_periode_pendaftaran)` untuk peringatan duplikat (BR-PPDB-003, DEC-029). |
| `pendaftaran_wali` | id_pendaftaran FK, id_profil_wali FK; PK komposit; jenis_hubungan enum; kontak_utama boolean |
| `riwayat_status_pendaftaran` | id_riwayat PK; id_pendaftaran FK; status_asal; status_tujuan; waktu datetime; id_pengguna FK; catatan text null. Penulisan bersama perubahan status dalam satu transaksi. |

Biaya pendaftaran ditagih ke pendaftaran melalui `tagihan.id_pendaftaran` (bagian 4).

### Tambahan dari sistem lama (field-level, usulan; DEC-078..096)

| Entitas / kolom | Keterangan |
|---|---|
| `orang` | tambahan: `nama_panggilan` varchar(50) null, `agama` varchar(20) null (calon/murid: otomatis `ISLAM`), `warga_negara` varchar(50) null |
| `profil_murid` (1:1 `siswa`, juga tersedia pada data `pendaftaran` sebelum diterima) | `anak_ke` tinyint, `jumlah_saudara` tinyint, `bahasa_keseharian` varchar(100), `kondisi_kesehatan` varchar(255), `ukuran_baju` enum('S','M','L','XL','XXL','Jumbo'), `sudah_mengaji` enum('Sudah','Belum'), `ngaji_dimana` varchar(255) null, `ngaji_metode` varchar(100) null, `ngaji_jilid` varchar(50) null, `pernah_belajar` enum('PAUD','Les','Belum'), `belajar_keterangan` varchar(255) null; cabang `required_if` pada aplikasi (BR-SIS-012) |
| `pendaftaran` | field data calon di atas disimpan di pendaftaran sampai diterima (DEC-029) dan disalin ke `orang`/`profil_murid` saat diterima; tambahan: `izin_edit` boolean (BR-PPDB-021), status `dibatalkan` |
| `profil_wali` (diperluas) | `jenis_hubungan` enum('ayah','ibu','wali_lain') ada pada `hubungan_siswa_wali`; data tiap orang tua: `agama`, `pendidikan_terakhir` enum('S3','S2','S1','D4','D3','D2','D1','SMA','SMP','SD'), `pekerjaan` enum('PNS','Swasta','Pedagang','TNI','POLRI','Guru','Lainnya'), `pekerjaan_lain` varchar(100) null, `penghasilan_bulanan` enum('<1 juta','1–2 juta','3–5 juta','>5 juta'), `no_hp` (dapat berbeda ayah/ibu) |
| `keluarga` (atau `alamat_keluarga`) | satu alamat keluarga per akun wali: `alamat_jalan` text (jalan/RT/RW), `id_provinsi`, `id_kota`, `id_kecamatan`, `id_kelurahan` (FK ke tabel wilayah), `ada_ayah` boolean, `ada_ibu` boolean; profil wajib lengkap sebelum mendaftarkan anak (BR-SIS-013/BR-PPDB-024) |
| `wilayah_provinsi`, `wilayah_kota`, `wilayah_kecamatan`, `wilayah_kelurahan` | data referensi resmi (±99 ribu baris), kunci kode wilayah; hierarki berjenjang; diimpor sekali dari sumber data (OD-38), hanya dibaca |
| `bank` | `id_bank` PK, `nama` varchar(100) UNIQUE; diimpor sekali (±110 bank), bisa dicari |
| `pembayaran` | tambahan: `id_bank_asal` FK null (bank asal transfer), `tanggal_bayar` date, waktu unggah = `created_at`; satu bukti berstatus menunggu per tagihan (BR-FIN-021) |
| `pengaturan_sistem` kunci baru | `ppdb_dibuka` (0/1), `ta_target_ppdb` (FK tahun ajaran), `kuota_pendaftaran` (angka), `nsm_sekolah` (12 digit), `persen_ditahan` (30), `spp_tanggal_terbit` (1–28, bawaan 1), `bank_sekolah`, `rekening_sekolah`, `atas_nama_rekening` |
| `pegawai` | `nuptk` varchar(16) null UNIQUE, `nomor_pegawai` (NIP) null; `jenis_kelamin`; `tanggal_mulai` (mulai mengajar); `jabatan` (BR-PEG-011); `foto` melalui dokumen privat |
| `tahun_ajaran` | status `direncanakan/aktif/selesai`; dibuat lewat wizard (DEC-097); `dibuat_dari` FK null (tahun yang disalin strukturnya); penempatan yang masih aktif otomatis `selesai` saat tahun berakhir; `kelas.disalin_dari` FK null untuk kelas hasil salinan |
| `impor_murid` / `impor_murid_baris` | riwayat impor: berkas, pelaku, waktu, jumlah berhasil/dilewati/gagal; baris gagal dengan pesan (BR-IMP-001) |
| `refund_pembayaran` | tambahan: `denda` (salinan `persen_ditahan` × total), `refund` = max(0, terbayar − denda), status `menunggu_pelunasan_denda` bila terbayar < denda (DEC-080) |

## 4. Keuangan dan tabungan (struktural)

Perubahan struktural yang sudah diputuskan dan wajib diikuti saat field-level disusun:

| Entitas | Tujuan | Field logis penting |
|---|---|---|
| `jenis_tagihan` | Jenis biaya | id_jenis_tagihan, nama/kode, frekuensi, nilai bawaan opsional (dari konfigurasi), tahun/periode berlaku, status. Jenis bawaan: `biaya_pendaftaran` (ditagih ke pendaftaran), `daftar_ulang` (ditagih ke siswa setelah diterima; dapat di-refund), `spp` bulanan, dan jenis lain yang dikonfigurasi. |
| `tagihan` | Kewajiban pembayaran | id_tagihan, **id_siswa XOR id_pendaftaran** (tepat satu terisi; CHECK bila MySQL mendukung, selain itu divalidasi aplikasi), id_jenis_tagihan, id_tahun_ajaran, periode tagihan, nominal, jumlah_terbayar (cache terhitung), status, jatuh_tempo. UNIQUE(pemilik tagihan, id_jenis_tagihan, periode tagihan) agar generate idempoten (BR-FIN-017). |
| `metode_pembayaran` | Cara bayar | id_metode_pembayaran, kode, nama, aktif |
| `pembayaran` | Satu kejadian pembayaran | id_pembayaran, nomor, **id_profil_wali pembayar** (bukan id_siswa; DEC-037), id_metode_pembayaran, tanggal, nominal, status, diverifikasi_oleh/pada, alasan penolakan; bukti pembayaran melalui `dokumen_pembayaran`; **kolom reversal**: id_pembayaran_asal, alasan_reversal, id_pengguna_reversal, waktu_reversal |
| `alokasi_pembayaran` | Alokasi pembayaran ke tagihan | id_alokasi_pembayaran, id_pembayaran, id_tagihan, nominal_alokasi; **kolom reversal**: id_alokasi_asal, alasan |
| `refund_pembayaran` | Refund atas pembayaran daftar ulang saat wali mengajukan pengunduran diri (BR-FIN-008, BR-FIN-018) | id_refund, id_pembayaran (pembayaran daftar ulang), id_siswa, tanggal_pengunduran_diri, nominal_dibayar, persen_ditahan (salinan pengaturan saat itu), nominal_ditahan, nominal_dikembalikan, alasan, status, diajukan_oleh, disetujui_oleh/pada (Admin Sekolah atau Bendahara, DEC-056), id_mutasi_keuangan |
| `akun_keuangan` | Kas atau rekening bank | id_akun_keuangan, nama, jenis akun, saldo awal, tanggal awal, status |
| `kategori_transaksi_keuangan` | Kategori pemasukan/pengeluaran | id_kategori_transaksi_keuangan, kode, nama, jenis transaksi, aktif |
| `mutasi_keuangan` | Buku mutasi kas/bank | id_mutasi_keuangan, id_akun_keuangan, id_kategori, tanggal, arah, nominal, deskripsi, status posting; **FK sumber eksplisit nullable** (mis. id_pembayaran, id_transfer_keuangan, id_refund; tepat satu atau tidak ada untuk entri manual; BR-KEU-005); **kolom reversal**: id_mutasi_asal, alasan |
| `transfer_keuangan` | Perpindahan antar-akun | id_transfer_keuangan, id_akun_sumber, id_akun_tujuan, nominal, tanggal, status, id_mutasi_keluar, id_mutasi_masuk |
| `rekening_tabungan` | Rekening per siswa | id_rekening_tabungan, id_siswa UNIQUE, status. Saldo tidak boleh negatif (BR-TAB-012). |
| `setoran_tabungan` | Batch setoran oleh wali kelas | id_setoran_tabungan, tanggal setoran, id_pengguna pencatat, status (lihat lifecycle §5), jumlah_diajukan, jumlah_diterima, selisih, catatan_penyelesaian, id_pengguna_penerima, diterima_pada, diposting_pada |
| `detail_setoran_tabungan` | Rincian per siswa | id_detail_setoran_tabungan, id_setoran_tabungan, id_siswa, **nominal_diajukan**, **nominal_diterima**, catatan selisih (DEC-031) |
| `mutasi_tabungan` | Pergerakan saldo resmi | id_mutasi_tabungan, id_rekening_tabungan, id_detail_setoran opsional, tanggal posting, jenis (setoran, penarikan, adjustment, reversal), nominal, id_pengguna pelaku, id_mutasi_asal (reversal), alasan |

Idempotensi: operasi yang bisa dicoba ulang (posting pembayaran, posting setoran, generate tagihan, penerimaan PPDB) memiliki kunci unik alami atau `kunci_idempotensi` varchar(64) UNIQUE pada tabel terkait; rinciannya ditetapkan di field-level Tahap 4.

Saldo menjadi hasil ledger yang sah. Bila cache saldo digunakan, nilainya harus dapat direkonsiliasi dan bukan satu-satunya sumber kebenaran.

## 5. Pegawai, dokumen dan administrasi (struktural)

| Entitas | Tujuan | Field logis penting |
|---|---|---|
| `jabatan` | Katalog jabatan | id_jabatan, kode, nama, deskripsi, aktif |
| `riwayat_jabatan_pegawai` | Histori jabatan | id_riwayat_jabatan_pegawai, id_pegawai, id_jabatan, tanggal_mulai, tanggal_selesai |
| `absensi_pegawai` | Kehadiran pegawai (domain terpisah dari siswa) | id_absensi_pegawai, id_pegawai, tanggal, jam_masuk, jam_pulang (waktu server, WIB), latitude_masuk, longitude_masuk, latitude_pulang, longitude_pulang (decimal(10,7), null; koordinat pribadi dengan akses terbatas, DEC-072), jarak_masuk_m, jarak_pulang_m, hasil_lokasi enum('dalam_area','di_luar_area','tidak_tersedia'), terlambat boolean (jam_masuk lebih dari `absensi_jam_masuk`, BR-PEG-010), status, sumber enum('mandiri','admin'), alasan_koreksi, id_pengguna_koreksi. UNIQUE(id_pegawai, tanggal). Absen di luar area ditolak untuk sumber `mandiri`; admin dapat mencatat/mengoreksi dengan alasan wajib (BR-PEG-008/009, DEC-064). |
| `absensi_siswa` | Kehadiran harian siswa (domain terpisah dari pegawai) | id_absensi_siswa, id_siswa, id_kelas, tanggal, status enum('hadir','sakit','izin','tanpa_keterangan'), catatan, id_pengguna pencatat. **UNIQUE(id_siswa, tanggal)** (BR-SIS-005, DEC-034). |
| `jenis_dokumen` | Kategori dokumen | id_jenis_dokumen, kode, nama, sensitivitas/akses default |
| `dokumen` | Metadata bisnis dokumen | id_dokumen, id_jenis_dokumen, judul, pemilik/pencatat, konteks melalui junction eksplisit. Status tingkat dokumen hanya ringkasan turunan dari versi terkini. |
| `versi_dokumen` | Versi fisik dokumen | id_versi_dokumen, id_dokumen, nomor versi, **status** (`diunggah`, `menunggu_verifikasi`, `disetujui`, `ditolak`, `kedaluwarsa`), storage disk/key, nama asli, MIME terdeteksi, ukuran, hash bila diperlukan, pengunggah, waktu |
| `verifikasi_dokumen` | Histori pemeriksaan | id_verifikasi_dokumen, **id_versi_dokumen (wajib)**, pemeriksa, status, catatan, waktu (BR-DOK-008) |
| `jenis_surat` | Kategori surat | id_jenis_surat, kode, nama, format penomoran (merujuk `penomoran`) |
| `template_surat` | Template surat versi | id_template_surat, id_jenis_surat, versi, konten/template, status |
| `surat` | Surat terbit | id_surat, id_jenis_surat, id_template_surat (versi dipakai), nomor, tanggal, data snapshot, penerima/konteks melalui FK eksplisit (siswa/pegawai/pendaftaran, nullable), file keluaran (PDF dan Word/.docx, DEC-062; keduanya privat), status |
| `kalender_akademik` | Kalender dalam tahun ajaran | id_kalender_akademik, id_tahun_ajaran, nama, status |
| `jenis_kegiatan` | Jenis agenda | id_jenis_kegiatan, kode, nama |
| `agenda` | Acara/jadwal | id_agenda, id_kalender_akademik, id_jenis_kegiatan, judul, mulai/selesai, lokasi, dipublikasikan |
| `pengumuman` | Konten pengumuman resmi | id_pengumuman, judul, isi, rentang publikasi, pembuat, status, **target** (seluruh wali aktif / kelas / siswa, melalui `target_pengumuman`) |
| `target_pengumuman` | Target distribusi | id_target_pengumuman, id_pengumuman, jenis_target enum('semua_wali','kelas','siswa'), id_kelas null, id_siswa null |
| `versi_pengumuman` | Histori teks setelah terbit (workflows §8.7) | id_versi_pengumuman, id_pengumuman, nomor_versi, judul, isi, id_pengguna, waktu |
| `penerima_pengumuman` | Pengiriman ke penerima | id_penerima_pengumuman, id_pengumuman, id_pengguna, status portal, waktu baca opsional |
| `notifikasi` | Notifikasi portal | id_notifikasi, id_pengguna, jenis, payload terkontrol, waktu baca |
| `riwayat_pengiriman_email` | Rekam status email | id_riwayat_pengiriman_email, penerima, jenis pesan, status, provider/message ID bila tersedia, percobaan, waktu, kode error yang aman |

Relasi dokumen eksplisit sesuai konteks: `dokumen_siswa`, `dokumen_pegawai`, `dokumen_pendaftaran`, `dokumen_pembayaran`, `dokumen_surat`, agar FK dapat ditegakkan. Tabel junction ini ditambahkan bersama modul terkait.

## 6. Rapor dan CMS (struktural)

| Entitas | Tujuan | Field logis penting |
|---|---|---|
| `rapor` | Agregat rapor per siswa-periode | id_rapor, id_siswa, id_tahun_ajaran, id_periode_pembelajaran, status (`draf`, `menunggu_persetujuan`, `siap_diterbitkan`, `terbit`), pembuat (wali kelas), id_pengguna_penyetuju (Kepala RA), disetujui_pada. UNIQUE(id_siswa, id_periode_pembelajaran). |
| `butir_capaian` | Master butir capaian dari template LCPA (seed) | id_butir_capaian, kode_bagian enum('nilai_agama','jati_diri','dasar_literasi_dst'), nomor tinyint, teks, aktif. Template saat ini: 4 butir Nilai Agama, 4 Jati Diri, 7 Dasar-dasar Literasi/Matematika/Sains/Teknologi/Rekayasa/Seni. Perubahan teks butir tidak mengubah rapor terbit (snapshot menyalin teks). |
| `rapor_capaian` | Tingkat ketercapaian per butir | id_rapor_capaian, id_rapor, id_butir_capaian, tingkat enum('BB','MB','BSH','BSB') null (BR-RAP-017). UNIQUE(id_rapor, id_butir_capaian). |
| `rapor_bagian` | Narasi per bagian | id_rapor_bagian, id_rapor, kode_bagian enum('nilai_agama','jati_diri','dasar_literasi_dst','panca_cinta'), deskripsi (narasi “Deskripsi Capaian Perkembangan”). |
| `rapor_panca_cinta` | Deskripsi per aspek Panca Cinta | id_rapor_panca_cinta, id_rapor, aspek enum('cinta_allah_rasul','cinta_ilmu','cinta_lingkungan','cinta_diri_sesama','cinta_tanah_air'), deskripsi |
| `rapor_foto` | Pose kegiatan per bagian/aspek | id_rapor_foto, id_rapor, kode_bagian atau aspek Panca Cinta, urutan, referensi berkas privat (melalui dokumen/versi). Jumlah foto per area diatur saat desain layout PDF. |
| `rapor_pengukuran` | Isian DDTK pada rapor | id_rapor_pengukuran, id_rapor, jenis enum('tinggi_badan','berat_badan','lingkar_kepala'), nilai (dari `pengukuran_pertumbuhan` terakhir pada/sebelum cut-off), kode_keterangan (pilihan dropdown dari referensi terkontrol, rujukan Permenkes No. 2 Tahun 2020, DEC-057; mis. “normal”, “tidak diukur”) |
| `rapor_ekstrakurikuler` | Ekstra kurikuler | id_rapor_ekstrakurikuler, id_rapor, nama, capaian_perkembangan |
| `rapor_refleksi` | Refleksi guru dan orang tua | id_rapor_refleksi, id_rapor, jenis enum('guru','orang_tua'), isi, id_pengguna pengisi null (null bila hanya ditulis tangan pada cetakan), waktu. Refleksi orang tua melalui portal bersifat opsional (DEC-054). |
| `rapor_versi` | Snapshot tiap penerbitan/revisi | id_rapor_versi, id_rapor, nomor versi, status (`terbit`, `digantikan`), snapshot terstruktur (seluruh tabel rapor di atas disalin, termasuk teks butir, kepala dokumen tahun/semester/kelompok/fase), nama/jabatan/NIP penanda tangan, referensi gambar tanda tangan saat terbit, waktu terbit, penerbit, alasan revisi, storage key PDF, hash opsional |
| `tanda_tangan` | Gambar tanda tangan privat | id_tanda_tangan, id_pegawai, storage disk/key, MIME, ukuran, aktif |
| `halaman` | Konten halaman website | id_halaman, slug unik, judul, konten, status publikasi |
| `program_sekolah` | Program/layanan sekolah | id_program_sekolah, judul, deskripsi, status publikasi, urutan |
| `galeri` | Galeri publik | id_galeri, judul, deskripsi, status |
| `media` | Metadata media publik | id_media, storage key, nama asli, alt text, MIME, ukuran, status publikasi |
| `galeri_media` | Hubungan galeri-media | id_galeri, id_media, urutan |
| `informasi_pendaftaran` | Konten informasi PPDB publik | id_informasi_pendaftaran, id_periode_pendaftaran opsional, konten, status publikasi |
| `pengaturan_website` | Konfigurasi website terbatas | id_pengaturan_website, kunci, nilai terkontrol, tipe nilai |

Profil kesehatan, pengukuran pertumbuhan, dan portofolio (struktur dari dokumentasi awal):

| Entitas | Tujuan | Field logis penting |
|---|---|---|
| `profil_kesehatan` | Data kesehatan dasar terkini | id_profil_kesehatan, id_siswa UNIQUE, golongan darah, alergi, catatan kesehatan relevan, tanggal pemutakhiran |
| `pengukuran_pertumbuhan` | Histori ukuran tubuh | id_pengukuran, id_siswa, tanggal_ukur, tinggi_badan_cm, berat_badan_kg, lingkar_kepala_cm, catatan, id_pengguna pencatat. Koreksi tidak menghapus histori (BR-SIS-009). |
| `portofolio` | Artefak perkembangan | id_portofolio, id_siswa, tanggal, judul, deskripsi, referensi berkas privat |

## 7. Hal yang masih terbuka sebelum migrasi

- Atribut data pribadi murid dan orang tua sudah diputuskan (DEC-082); field-level entitas Tahap 3+ menyusul (OD-10).
- Tidak ada penghapusan data; entitas dinonaktifkan/diarsipkan (DEC-071).
- Daftar nilai status siswa dan tahun ajaran aktif tunggal.
- Verifikasi teks butir capaian pada `butir_capaian` terhadap dokumen sumber saat seed (OD-12); daftar opsi dropdown pengukuran dikonfirmasi di OD-33.
- Field-level entitas Tahap 3 dan seterusnya (bagian 4–6).
- Kebijakan penghapusan, soft delete, retensi data dan versi berkas (OD-14).
