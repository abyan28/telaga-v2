# Workflow Bisnis Lintas Modul

## 1. PPDB hingga siswa aktif

1. Admin membuka `Periode Pendaftaran` dengan tanggal dan tahun ajaran tujuan, serta menentukan dokumen yang disyaratkan.
2. Wali membuat akun (membuat `orang` baru; kemungkinan kecocokan ditinjau staf, BR-ID-015), memverifikasi email melalui OTP, lalu melengkapi profil.
3. Wali membuat satu pengajuan untuk satu calon anak; wali yang sama bisa membuat beberapa pengajuan untuk anak berbeda.
4. Wali mengisi data calon anak (disimpan di `pendaftaran`, belum menjadi `orang`) dan mengunggah dokumen yang diminta. Sistem menandai kemungkinan duplikat (nama + tanggal lahir + periode) sebagai peringatan bagi petugas.
5. Sistem menyimpan riwayat status setiap perubahan dan pelakunya.
6. Biaya pendaftaran (bila dikonfigurasi) ditagih ke pendaftaran (`tagihan.id_pendaftaran`); wali membayar sesuai alur pembayaran (bagian 3). Pengajuan baru dapat diperiksa dan diputuskan setelah biaya pendaftaran lunas dan terverifikasi (BR-PPDB-014). Petugas lalu memeriksa kelengkapan dokumen.
7. Pengajuan dapat dikembalikan untuk perbaikan, diterima atau ditolak dengan catatan sesuai hak akses.
8. Ketika diterima, proses harus idempotent: pembentukan `orang` calon (disalin dari data pendaftaran), `siswa`, dan hubungan wali tidak boleh menggandakan profil ketika job atau request dicoba ulang. Wali pengaju otomatis `terverifikasi`. Sistem membuat tagihan daftar ulang untuk siswa baru (BR-FIN-019).
9. Petugas membuat `Riwayat Penempatan Siswa` pada kelas yang sesuai. Penerimaan tidak otomatis menentukan penempatan jika belum ditetapkan.
10. Wali menerima status melalui portal dan email bila kanal email aktif.
11. Selama pendaftaran, wali hanya mengakses pengajuannya sendiri. Wali tambahan untuk siswa yang sudah ada ditambahkan staf dan diverifikasi admin dengan bukti unggahan Kartu Keluarga atau akta kelahiran anak (DEC-063).
12. Wali mengajukan pengunduran diri setelah membayar daftar ulang: status siswa menjadi `keluar` dan refund atas pembayaran daftar ulang diproses (bagian 3.10). Pengunduran diri dicatat dan refund disetujui oleh Admin Sekolah atau Bendahara (DEC-056).
13. Setelah daftar ulang selesai, admin menjalankan “Buat NIS” per periode: siswa diterima yang belum ber-NIS diurutkan abjad nama lalu diberi NIS (NSM + 2 digit tahun + 3 digit urut). Siswa yang menyusul mendapat nomor lanjutan; NIS yang sudah terbit tidak berubah (BR-SIS-011).

### 1A. Calon Murid, Buat NIS, dan pembatalan (DEC-079, DEC-080)

1. Setelah diterima, murid berstatus `calon`; tagihan daftar ulang dibuat. Yang sudah membayar daftar ulang (sebagian atau lunas) muncul di halaman **Calon Murid** (sebelum daftar murid resmi).
2. Pembatalan hanya dari halaman admin: denda = `persen_ditahan` × total daftar ulang; refund = max(0, terbayar − denda). Bila terbayar < denda, tombol batal diganti kewajiban melunasi denda dulu; setelah terverifikasi, murid `keluar`, kelulusan dibatalkan, dan akun ortu diblokir bila tidak ada anak aktif.
3. “Buat NIS” hanya dapat dijalankan bila PPDB ditutup, NSM terisi, dan tidak ada bukti daftar ulang pending dengan terbayar 0. Semua calon yang memenuhi syarat diurutkan abjad nama, diberi NIS, dan menjadi `aktif`; `angkatan` terisi otomatis. Halaman Calon Murid lalu kosong.

### 1B. PPDB dibuka/ditutup dan tahun ajaran target

1. Admin membuka/menutup PPDB, mengatur kuota, dan menavigasi tahun ajaran target PPDB terpisah dari tahun ajaran aktif.
2. Saat ditutup: akun wali baru dan formulir baru ditolak; kelulusan yang belum membayar daftar ulang sama sekali dibatalkan; login ortu ditolak bila tidak ada anak hidup (BR-PPDB-019).
3. Validasi usia dihitung dari 1 Juli tahun awal tahun ajaran target (BR-PPDB-016).

### 1C. Akun wali lama dan impor murid

1. Admin menambah murid lama (form atau impor CSV/Excel); bila no. HP wali diisi, sistem membuat/menautkan akun wali (kakak-adik dengan no. HP sama → satu akun) dan menerbitkan **kode aktivasi** (BR-AUTH-017).
2. Wali memasukkan kode, menetapkan kata sandi, username, dan email (OTP), lalu melengkapi profil ayah/ibu dan alamat berjenjang.
3. Impor: unggah berkas bertemplate → cek format per baris → NIK sudah ada dilewati → baris gagal dilaporkan; baris valid tetap diproses (BR-IMP-001).

## 2. Penempatan dan perpindahan kelas

1. Setiap kelas mengacu pada tahun ajaran tertentu.
2. Penempatan mempunyai tanggal mulai dan tanggal akhir opsional/aktual.
3. Perpindahan menutup atau mengakhiri penempatan lama lalu membuat penempatan baru dalam satu operasi konsisten dengan penguncian baris siswa.
4. Satu siswa maksimal memiliki satu penempatan aktif pada satu waktu (mutlak, BR-AKD-007, DEC-039).
5. Histori lama dipertahankan untuk kebutuhan rapor dan audit.

## 3. Pembayaran tagihan

1. Tagihan dibuat untuk siswa (atau pendaftaran untuk biaya pendaftaran), jenis tagihan, periode dan tahun ajaran yang relevan. Generate tagihan bulanan idempoten (unik per pemilik, jenis, periode). Tidak ada diskon, beasiswa, atau prorata pada V2 awal.
2. Pembayaran dicatat atas nama wali pembayar. Pembayaran tunai dicatat oleh petugas sekolah yang berwenang. Alur penerimaan dan pembuatan mutasi kas dilakukan secara konsisten.
3. Pembayaran non-tunai dapat diajukan wali dengan bukti; statusnya menunggu verifikasi.
4. Pemeriksa menyetujui atau menolak pembayaran dengan alasan bila ditolak. Untuk pembayaran non-tunai, pengaju (wali) dan pemeriksa berbeda secara alami. Pemisahan orang yang sama tidak diwajibkan untuk refund/reversal; semua tindakan tercatat di audit (DEC-068).
5. Sistem mengusulkan alokasi otomatis pembayaran yang disetujui ke tagihan terlama dulu (termasuk tagihan beberapa anak milik wali yang sama); petugas dapat mengubah alokasi sebelum posting (BR-FIN-020). Cicilan hanya untuk daftar ulang; biaya pendaftaran dan SPP harus terlunasi penuh per tagihan (BR-FIN-005). Bukti memuat bank asal dan tanggal bayar; hanya satu bukti menunggu per tagihan (BR-FIN-021). Email rincian dikirim lewat antrean untuk tagihan baru dan hasil verifikasi (BR-NTF-001).
6. Jumlah alokasi tidak boleh melampaui pembayaran dan tidak boleh melebihi sisa tagihan.
7. Pembaruan status pembayaran, alokasi, status tagihan, dan mutasi keuangan terkait harus atomik atau memakai pola konsistensi yang terdokumentasi.
8. Pembayaran yang telah diposting tidak dihapus. Koreksi menggunakan reversal/penyesuaian yang terhubung ke transaksi asal.
9. Email atau notifikasi adalah efek samping setelah transaksi sah; kegagalan email tidak membatalkan pembayaran.
10. Refund saat wali mengajukan pengunduran diri setelah membayar daftar ulang: diajukan atas pembayaran daftar ulang; sistem menghitung bagian ditahan memakai persentase pengaturan saat itu (awal 30% ditahan, 70% dikembalikan) dan menyimpan salinannya; setelah disetujui, refund diposting sebagai transaksi tertaut ke pembayaran asal dan menghasilkan mutasi keuangan. Penyetuju: Admin Sekolah atau Bendahara (DEC-056).

## 4. Pemasukan, pengeluaran, dan transfer kas/bank

- Pemasukan/pengeluaran langsung dicatat dengan akun dan kategori yang sesuai.
- Transfer antara dua akun mencatat perpindahan sumber-ke-tujuan; bukan pemasukan atau pengeluaran operasional.
- Mutasi memiliki status posting yang jelas dan pencatat/pengesah.
- Nilai saldo diperoleh dari saldo awal dan mutasi resmi, atau dari cache yang dapat direkonsiliasi.
- Pembatalan transaksi yang telah diposting dilakukan melalui pembalikan/penyesuaian, bukan penghapusan.

## 5. Tabungan siswa dan setoran batch

1. Wali kelas mencatat batch setoran untuk tanggal yang relevan dan rincian `nominal_diajukan` per siswa.
2. Pencatatan awal berstatus menunggu diterima dan belum mengubah saldo resmi.
3. Bendahara menghitung uang yang diserahkan dan mengisi `nominal_diterima` per siswa.
4. Jika total sama dengan yang diajukan, status `diterima`; jika berbeda, status `ada_selisih`. Simpan nilai diajukan, nilai diterima, selisih dan catatan penyelesaian. Jangan menimpa rincian untuk menyembunyikan selisih.
5. Setelah bendahara mengonfirmasi, sistem memposting mutasi tabungan sebesar `nominal_diterima` untuk setiap rincian secara atomik; hanya pada saat itu saldo resmi berubah.
6. Pengambilan tabungan hanya oleh peran yang diizinkan dan harus memiliki jejak pelaku, tanggal, nominal serta alasan. Penarikan tidak boleh melebihi saldo; pemeriksaan dan pengurangan dilakukan dalam transaksi dengan penguncian rekening.
7. Mutasi terposting dikoreksi dengan reversal/penyesuaian.
8. Wali melihat saldo dan mutasi anak di portal; tidak ada permohonan penarikan di portal.

## 6. Rapor LCPA

1. Wali kelas memilih siswa, tahun ajaran dan periode rapor.
2. Sistem mengisi data yang tersedia dan diizinkan: kepala dokumen (nama siswa, tahun pelajaran, semester, kelompok/kelas, fase), kehadiran (absensi harian dari tanggal mulai periode sampai cut-off), dan pengukuran DDTK terakhir pada atau sebelum cut-off (BR-RAP-016).
3. Wali kelas memberi tingkat (BB/MB/BSH/BSB) untuk tiap butir capaian, menulis narasi per bagian, deskripsi Panca Cinta, mengunggah foto pose kegiatan, mengisi Refleksi Guru, ekstrakurikuler, dan keterangan DDTK.
4. Wali boleh mengisi Refleksi Orang Tua di portal (opsional) atau menulis tangan pada cetakan; ini isian formulir, bukan balasan. Rapor dapat terbit tanpa isian portal.
5. Validasi kelengkapan dilakukan sebelum draf dapat diajukan untuk persetujuan.
6. Kepala RA menyetujui atau mengembalikan dengan catatan.
7. Saat terbit, sistem membuat snapshot versi yang menyimpan konten yang benar-benar diterbitkan (termasuk refleksi dan penanda tangan serta gambar tanda tangan yang dipakai) serta referensi PDF.
8. Pembaruan data sumber setelah publikasi tidak mengubah snapshot rapor yang telah terbit.
9. Revisi menghasilkan versi baru dan melewati persetujuan lagi, mempertahankan versi lama, pelaku, waktu dan alasan revisi.
10. Akses PDF mengikuti aturan privasi wali/siswa dan role pegawai; wali hanya melihat versi terbit.

## 7. Dokumen dan surat

- Satu Dokumen adalah rekaman bisnis; setiap unggahan baru menjadi Versi Dokumen.
- Verifikasi merujuk versi yang diperiksa agar keputusan tidak ambigu jika berkas kemudian diganti.
- Berkas sensitif diakses melalui backend yang menjalankan authorization.
- Surat menyimpan jenis, nomor, tanggal dan versi template yang dipakai. Memperbarui template tidak mengubah surat lama.
- Surat diunduh dalam format PDF dan Word (.docx) yang dapat diedit; keduanya privat (BR-DOK-009).
- Penomoran surat memakai tabel `penomoran` dengan penguncian transaksi untuk mencegah nomor ganda dalam ruang lingkup konfigurasi.

## 8. Pengumuman satu arah

1. Petugas membuat dan menjadwalkan atau menerbitkan pengumuman.
2. Sistem menentukan penerima sesuai `target_pengumuman` (seluruh wali aktif, kelas tertentu, atau siswa tertentu).
3. Simpan daftar penerima yang menjadi bukti distribusi.
4. Buat notifikasi portal dan antrean email.
5. Status dibaca merupakan telemetri, bukan fitur balasan.
6. Tidak ada komentar atau percakapan dari wali pada V2 awal.
7. Perubahan teks pengumuman setelah terkirim membuat `versi_pengumuman` baru; jangan mengubah secara diam-diam isi yang sudah menjadi bukti komunikasi.

## 8A. Absensi pegawai mandiri

1. Pegawai yang wajib absen (guru, kepala, operator) membuka “Absen Saya” di ponsel dan menekan Masuk atau Pulang.
2. Browser meminta lokasi; backend menghitung jarak ke titik sekolah dan menolak absensi di luar radius. Waktu diambil dari server (WIB), bukan dari ponsel.
3. Sistem menyimpan jam, koordinat, jarak, dan hasil validasi lokasi (koordinat hanya dapat dilihat admin berwenang). Satu catatan per pegawai per tanggal. Absen hanya diwajibkan pada hari kerja (Senin–Jumat).
4. Jika lokasi tidak tersedia atau ditolak, pegawai menghubungi admin; admin mencatat atau mengoreksi dengan alasan wajib dan tindakan itu diaudit.
5. Pegawai tanpa akun dicatat hadirnya oleh admin.
6. Absen masuk setelah jam masuk resmi (satu jam untuk semua pegawai, diatur di pengaturan) ditandai terlambat, tanpa toleransi; hanya penanda di laporan. Radius awal 10 meter dapat diubah; jam masuk resmi awal 07.00 WIB. Koreksi admin selalu dengan alasan wajib dan diaudit (DEC-074).

## 8B. Tahun ajaran berikutnya (wizard, DEC-094, DEC-097)

1. Admin menekan Tahun ajaran berikutnya. Sistem mengusulkan nama otomatis (mis. 2027/2028), tanggal bawaan 1 Juli–30 Juni, serta periode Ganjil (1 Juli–31 Desember) dan Genap (1 Januari–30 Juni) dengan cut-off rapor bawaan = tanggal selesai periode.
2. Pratinjau: admin menyesuaikan tanggal dan boleh mencentang salin struktur kelas (nama kelas, fase, kapasitas, ruangan; tanpa murid, wali kelas, guru pendamping, atau penugasan).
3. Sistem menampilkan peringatan yang tidak memblokir bila di tahun lama masih ada tagihan SPP belum lunas, rapor belum terbit, atau setoran tabungan belum diposting; admin wajib mengonfirmasi.
4. Konfirmasi: tahun baru dibuat dan diaktifkan, tahun lama menjadi selesai (hanya satu aktif), penempatan lama otomatis berstatus selesai. Murid tetap aktif; admin menempatkan murid ke kelas baru (BR-AKD-010). Tahun lama tidak dibuat mundur otomatis; data lama tetap tersimpan.
5. Tahun ajaran target PPDB dinavigasi terpisah (tahun yang sedang dibuka untuk pendaftaran).

## 9. Akun dan hak akses

- Akun wali dibuat lewat pendaftaran mandiri dan verifikasi email.
- Akun staf dibuat melalui undangan: petugas berwenang mengirim tautan sekali pakai dengan TTL awal 48 jam; penerima menetapkan kata sandi. Undangan baru membatalkan undangan lama. Role Guru diberikan manual oleh admin.
- Pemulihan kata sandi: OTP email, lalu token reset sekali pakai.
- Perubahan role, permission, status akun, dan konfigurasi sensitif dicatat di audit log.
- Akses pada kelas, siswa, berkas dan data keuangan selalu diperiksa di backend untuk setiap request.
- Pemulihan Admin Utama mengikuti prosedur tertulis di `auth-rbac.md` (tanpa MFA).
