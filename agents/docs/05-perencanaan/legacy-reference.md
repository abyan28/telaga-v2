# Referensi Sistem Lama (Al Kautsar v1)

Sistem versi awal berada di `D:\Laravel\Al Kautsar` (Laravel 13, MySQL, Blade + Tailwind + Alpine). **Hanya referensi.** TELAGA v2 dibangun ulang total: kode, struktur, dan UI tidak disalin (DEC-096, DEC-095). Datanya dummy dan tidak dimigrasi (DEC-084). Dokumen ini merangkum apa yang diserap dan apa yang sengaja ditinggalkan. Agen tidak perlu membaca sistem lama; bila perlu rincian perilaku, konfirmasikan ke pemilik.

## 1. Cakupan sistem lama

PPDB, daftar ulang, SPP, data murid/guru/ortu, CMS website, laporan CSV/PDF, email. Tidak ada: absensi, rapor, tabungan, kas/bank, surat, dokumen berversi, bendahara/kepala RA (hanya role `admin`, `guru`, `ortu`). Ukuran: ±20 tabel aplikasi, 19 model, 19 controller, ±144 tes.

## 2. Aturan yang diserap ke v2

| Aturan lama | Letak di v2 |
|---|---|
| Cicilan per jenis (pendaftaran penuh, SPP lunas per tagihan, daftar ulang boleh dicicil) | BR-FIN-005, DEC-078 |
| Calon Murid → Buat NIS (NSM + 2 digit tahun + 3 digit urut abjad); angkatan dari NIS | BR-SIS-011, DEC-079 |
| Rumus refund = terbayar − (30% × total daftar ulang) | BR-FIN-008, DEC-080 |
| Usia 4–6 tahun per 1 Juli; kuota; PPDB buka/tutup + tahun ajaran target terpisah; efek PPDB ditutup | BR-PPDB-016..019, DEC-081 |
| Profil murid kaya, profil ayah/ibu, alamat berjenjang, validasi HP/nama/NIK/NUPTK | BR-SIS-012..014, DEC-082 |
| Login tiga identitas; akun wali lama diaktifkan (kode aktivasi menggantikan password dari NIK) | BR-AUTH-001, BR-AUTH-017, DEC-083 |
| Impor CSV murid lama (v2 juga Excel); ekspor CSV/PDF | BR-IMP-001/002, DEC-084/085 |
| Bank asal transfer + rekening sekolah; satu bukti pending per tagihan | BR-FIN-021, DEC-086 |
| Huruf kapital otomatis (dengan pengecualian) | BR-UI-001, DEC-087 |
| Izin edit wali, ubah keputusan, urutan gerbang, multi-anak | BR-PPDB-020..023, DEC-088 |
| Email dengan rincian transaksi | BR-NTF-001, DEC-089 |
| Tanggal terbit SPP dapat diatur (di v1 tidak berfungsi, di v2 harus berfungsi) | BR-FIN-022, DEC-090 |
| CMS: biaya dari pengaturan, logo/favicon/hak cipta, slide Curriculum Highlight | BR-CMS-005, DEC-092 |
| Wali kelas, guru banyak kelas, satu kelas satu wali kelas | DEC-046 (v2 menambah guru pendamping) |
| Tahun ajaran: satu aktif, navigasi prev/next | BR-AKD-001, DEC-094 (v2 diperluas jadi wizard) |
| Audit log aksi penting | BR-AUD-* |

## 3. Yang sengaja ditinggalkan atau diubah

- Kode dan struktur kode (controller besar, tanpa Policy, tanpa enum).
- Konvensi PK/FK Inggris (`id_students`, `id_parents`); v2 memakai konvensi Indonesia (DEC-028).
- Role admin tunggal; v2 memisahkan Admin Utama, Admin Sekolah, Kepala RA, Bendahara.
- Hapus data murid/guru (sudah dibuang di v1); v2 juga tanpa hapus (DEC-071).
- Catatan perkembangan guru (v1 menghapusnya sementara); v2 memakai portofolio (DEC-091).
- Password awal dari NIK/no. HP/NUPTK; v2 memakai kode aktivasi dan undangan.
- NUPTK wajib untuk semua guru; v2 opsional (DEC-093).
- Tampilan landing page dan dashboard admin; v2 desain baru (DEC-095).
- Status murid `aktif/lulus/nonaktif` di v1; v2: `calon/aktif/lulus/keluar`.

## 4. Kelemahan v1 yang menjadi persyaratan v2 (BR-OPS-007)

1. Rute `/storage/{path}` publik menyajikan KK, akta, foto anak, dan bukti bayar tanpa autentikasi.
2. Tanpa batas percobaan login dan OTP.
3. Password awal mudah ditebak (NIK, NUPTK); seeder admin dengan password tetap.
4. Hitungan uang memakai float; perbandingan nominal dengan float.
5. `payment_transactions.referensi_id` polimorfik tanpa FK.
6. Tidak ada kunci/idempotensi saat unggah dan verifikasi pembayaran.
7. Status siswa di form tidak sinkron dengan enum database.
8. Transisi status (ubah keputusan, izin edit, pembatalan) tidak memeriksa status asal.
9. Pengaturan yang ada di UI tetapi tidak berfungsi (tanggal generate SPP, kuota).
10. Otorisasi di controller tanpa Policy; pengubahan akun oleh admin tanpa pemeriksaan role.
11. Ekstensi berkas dari nama berkas klien; CMS tidak menghapus berkas lama.
12. Perintah reset data yang dapat menghapus seluruh data produksi.

## 5. Pemetaan tabel v1 → v2 (ringkas)

| v1 | v2 |
|---|---|
| `users` (+`role`) | `pengguna` + `pengguna_role` |
| `parents` (ayah_*/ibu_*) | `orang` + `profil_wali` + `hubungan_siswa_wali` + `keluarga` |
| `students` | `orang` + `siswa` + `profil_murid` |
| `teachers`, `class_teacher` | `pegawai`, `penugasan_akademik` |
| `classes`, `academic_years` | `kelas`, `tahun_ajaran`, `periode_pembelajaran` |
| `registration_forms`, `registration_documents` | `pendaftaran`, `dokumen` + `versi_dokumen` + `verifikasi_dokumen` |
| `monthly_spp_bills`, `re_registration_payments` | `tagihan` (+ jenis tagihan) |
| `payment_transactions` | `pembayaran`, `alokasi_pembayaran`, `refund_pembayaran`, `mutasi_keuangan` |
| `settings` | `pengaturan_sistem` (+ riwayat) |
| `site_contents`, `site_features` | `halaman`, `program_sekolah`, `media`, `pengaturan_website` |
| `audit_logs` | `audit_log` |
| `banks`, `t_provinsi/kota/kecamatan/kelurahan` | `bank`, `wilayah_*` (data referensi, OD-38) |
| `student_progress_notes` | tidak dibawa (DEC-091) |

## 6. Data yang dapat dipakai ulang

Daftar bank (`database/data/bank.csv`, ±110) dan data wilayah (`database/data/wilayah_indonesia.sql`, ±99 ribu baris) dari repo lama dapat menjadi sumber data referensi setelah sumber, lisensi, dan kelengkapannya diverifikasi (OD-38). Hanya data, bukan kode.

## 7. Hal di v1 yang belum diputuskan untuk v2

Lihat `open-decisions.md`: OD-36 (pustaka Excel), OD-37 (rincian wizard tahun ajaran), OD-38 (sumber data wilayah/bank).
