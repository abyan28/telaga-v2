# Konvensi Database MySQL

## 1. Konvensi nama (DEC-028)

- **Tabel domain:** nama tabel dan kolom menggunakan Bahasa Indonesia `snake_case`.
- **Tabel kerangka Laravel/paket** (sessions, jobs, failed_jobs, cache, migrations, password reset bawaan bila dipakai, dll.): nama bawaan tidak diubah. Model domain mengatur `$table` dan `$primaryKey`; autentikasi mengarah ke tabel `pengguna` melalui konfigurasi model/provider. Integrasi paket RBAC (DEC-024) harus diverifikasi untuk pemetaan nama tabel/kolom pada bootstrap.
- PK memakai `id_<entitas>`, misalnya `id_siswa`. Tipe usulan `BIGINT UNSIGNED AUTO_INCREMENT` (lihat §2).
- FK memakai nama PK target, misalnya `id_orang`, `id_siswa`, `id_tahun_ajaran`.
- Junction table dinamai berdasarkan kedua konsep, misalnya `pengguna_role`, `role_permission`, `hubungan_siswa_wali`, `galeri_media`.
- Kolom waktu audit memakai `created_at`/`updated_at` bawaan Laravel. Tanggal bisnis bernama spesifik: gunakan `tanggal_...` untuk tanggal tanpa waktu dan nama peristiwa yang jelas untuk datetime.
- Jangan memakai kolom `data`, `metadata`, atau JSON sebagai pengganti skema relasional untuk data inti. JSON boleh dipertimbangkan untuk snapshot rapor yang immutable atau payload teknis terkontrol setelah kebutuhannya dibuktikan.

## 2. PK dan FK

Tipe PK final ditetapkan saat bootstrap setelah pemeriksaan kompatibilitas hosting minimal (versi MySQL) yang dilakukan sebelum Tahap 0 (lihat `implementation-plan.md`). Rekomendasi awal adalah `BIGINT UNSIGNED` auto-increment yang konsisten di seluruh FK, kecuali alasan bisnis/teknis yang terdokumentasi mengharuskan alternatif. Jangan menggunakan UUID hanya demi tren.

- Tipe, signedness, dan collation key harus kompatibel.
- FK dibuat untuk relasi bisnis penting.
- Gunakan `RESTRICT`/kebijakan setara untuk mencegah penghapusan entitas yang menjadi referensi sejarah keuangan/administrasi.
- Cascade hanya untuk detail teknis/junction yang memang aman dihapus bersama induk dan bukan sumber histori bisnis. Setiap cascade harus ditinjau.
- Jangan menghapus akun orang jika akan merusak relasi siswa, wali, pegawai atau audit.

## 3. Nilai uang dan tanggal

- Hindari `FLOAT` dan `DOUBLE` untuk nominal finansial. Pilih DECIMAL dengan skala sesuai aturan keuangan.
- Uang disimpan dalam mata uang yang jelas (V2 awal diasumsikan rupiah); format tampilan bukan format penyimpanan.
- Persentase disimpan dengan presisi yang memadai jika kelak diperlukan; biaya keterlambatan tidak digunakan pada aturan SPP awal.
- Gunakan tipe tanggal untuk tanggal bisnis dan timestamp/datetime untuk kejadian yang perlu waktu.
- Tetapkan satu kebijakan zona waktu aplikasi dan konversi tampilan dengan konsisten.

## 4. Unik dan index

Unique key harus ditentukan dari ruang lingkup bisnis, bukan sekadar karena nilai terlihat unik. Contoh calon constraint yang wajib dianalisis:

- `pengguna.email` unik jika email adalah identitas login.
- kode permission dan kode role unik.
- kombinasi role-permission dan pengguna-role unik.
- nomor pendaftaran unik dalam ruang lingkup yang ditentukan.
- nomor surat unik dalam ruang lingkup penomoran.
- kombinasi siswa dan tanggal untuk absensi siswa (absensi harian, DEC-034; tidak menyertakan kelas agar satu siswa tidak punya dua catatan di hari yang sama).
- alokasi pembayaran dan hubungan junction tidak boleh berduplikasi secara tidak sah.
- tagihan: unik per (pemilik tagihan: siswa atau pendaftaran, jenis tagihan, periode tagihan) agar generate idempoten (BR-FIN-017).
- pendaftaran: nomor pendaftaran unik per periode pendaftaran; kemungkinan duplikat calon (nama + tanggal lahir + periode) memakai index non-unik dan peringatan, bukan unique (BR-PPDB-003).
- rapor: unik per (siswa, periode pembelajaran).
- `pengguna.id_orang` unik dan wajib; `pengguna.email` unik.

Index disusun berdasarkan query nyata: daftar/filter siswa, tahun ajaran, periode, status, tanggal, FK, pencarian tagihan, histori pembayaran, notifikasi belum dibaca, dan audit log. Hindari menambah index berlebihan tanpa mengukur dampak penulisan.

## 5. Status dan histori

- Gunakan himpunan kode status yang terkendali.
- Simpan histori perubahan status untuk pendaftaran, penugasan/penempatan, verifikasi dan tindakan penting.
- `updated_at` tidak menggantikan riwayat keputusan.
- Soft delete bukan pengganti status bisnis. Jangan soft-delete transaksi yang seharusnya dibalikkan.

## 6. Invariant utama

- Satu orang maksimal satu akun pengguna; setiap akun terkait satu orang.
- Satu siswa maksimal satu penempatan aktif; rentang penempatan tidak tumpang tindih (BR-AKD-007). Ditegakkan dalam transaksi dengan penguncian baris siswa, karena MySQL tidak menjamin ini lewat CHECK/unique sederhana.
- Tagihan terikat tepat satu dari siswa atau pendaftaran.
- Saldo rekening tabungan tidak negatif; penarikan memeriksa saldo di bawah kunci rekening (BR-TAB-012).
- Operasi yang dapat dicoba ulang (posting pembayaran/setoran, generate tagihan, penerimaan PPDB) memakai kunci unik alami atau kunci idempotensi.
- Total alokasi pembayaran tidak melebihi nominal pembayaran.
- Alokasi per tagihan tidak melebihi sisa yang dapat ditagih.
- Tagihan tidak dinyatakan lunas sebelum seluruh nilai yang wajib dibayar telah dialokasikan secara sah.
- Total rincian setoran dibandingkan dengan nominal yang diterima sebelum posting tabungan.
- Operasi posting keuangan/tabungan harus atomik dan dapat direkonsiliasi.
- Mutasi yang telah diposting tidak diedit/hapus; gunakan reversal atau adjustment.
- Satu versi rapor terbit tidak berubah setelah dipublikasikan.

Sebagian invariant lintas-baris mungkin tidak dapat diwujudkan sebagai CHECK sederhana pada MySQL. Wajib enforce dalam transaksi aplikasi, locking yang tepat, unique constraints dan test konkurensi. Jangan berpura-pura bahwa validasi form saja cukup.

## 7. MySQL shared hosting

Target konservatif (DEC-059): PHP 8.3 dan MySQL/MariaDB versi lama yang didukung Laravel 13; banyak shared hosting memakai MariaDB. Hindari CHECK, tipe/fungsi JSON khusus, dan window function; invarian ditegakkan di aplikasi dan transaksi. Jangan mengasumsikan versi MySQL, dukungan CHECK, akses CLI, atau konfigurasi server yang sama pada semua provider. Sebelum memilih paket hosting, catat versi server dan jalankan compatibility check. Jangan menggunakan fitur vendor khusus yang tidak didukung target deployment tanpa justifikasi.
