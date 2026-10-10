# Strategi Pengujian TELAGA v2

## 1. Lapisan pengujian

- Unit test: aturan bisnis terisolasi, perhitungan alokasi, transisi status dan helper domain.
- Feature/integration test: route, validasi, database, session, policy, filesystem/email fake, dan workflow antarentitas.
- UI test: alur penting, validasi form, state loading/error/empty, responsif dan aksesibilitas; alat final dipilih saat bootstrap.
- Security test: percobaan akses tanpa izin, manipulasi ID, CSRF, rate limit, unggahan, sesi dan kebocoran informasi.
- Deployment smoke test: migration, assets, queue/cron, email, upload private, PDF, backup dan restore pada environment staging.

## 2. Prioritas test wajib

### Identitas dan akses
- Pendaftaran mandiri hanya dapat membuat akun wali dan membuat `orang` baru; kemungkinan kecocokan ditandai untuk tinjauan, tidak digabung otomatis.
- Setiap akun terkait tepat satu orang; satu orang tidak dapat memiliki dua akun.
- OTP expired/salah/reuse/resend rate-limited ditolak; OTP untuk tujuan lain ditolak.
- Respons pendaftaran/login/reset tidak membocorkan email terdaftar (BR-AUTH-011).
- Akun ditangguhkan/dinonaktifkan kehilangan sesi aktif (BR-AUTH-014).
- Token reset dan undangan staf sekali pakai, kedaluwarsa, dan undangan baru membatalkan yang lama.
- Pengguna tidak dapat mengubah role sendiri; cache izin tidak mempertahankan hak yang dicabut.
- Guru (wali kelas dan pendamping) hanya dapat mengakses kelas/record sesuai penugasan yang berlaku; pendamping tidak dapat menyusun rapor atau mencatat setoran.
- Wali hanya dapat melihat anak dengan hubungan `terverifikasi`; selama pendaftaran hanya pengajuannya sendiri.
- Peristiwa BR-AUD-002 menghasilkan entri audit tanpa rahasia.

### PPDB
- Satu pengajuan mewakili satu calon anak; data calon tersimpan di pendaftaran dan `orang` baru terbentuk hanya saat diterima.
- Pendaftar ditolak/ditarik tidak membuat `orang`.
- Calon dengan nama + tanggal lahir + periode sama ditandai kemungkinan duplikat (peringatan).
- Dokumen wajib periode (BR-PPDB-006) menjadi prasyarat transisi yang mensyaratkannya.
- Biaya pendaftaran menghasilkan tagihan ke pendaftaran, bukan ke siswa; pengajuan tidak dapat masuk `dalam_pemeriksaan` sebelum biaya lunas dan terverifikasi (BR-PPDB-014).
- Setelah diterima, tagihan daftar ulang dibuat untuk siswa satu kali (idempoten).
- “Buat NIS”: urutan abjad nama dalam satu pembuatan massal, format NSM + 2 digit tahun + 3 digit urut, susulan mendapat nomor lanjutan, NIS yang sudah terbit tidak berubah, dan dua eksekusi bersamaan tidak membuat nomor ganda.
- Satu wali dapat membuat pengajuan beberapa anak.
- Riwayat status tidak hilang saat status terbaru berubah.
- Proses penerimaan yang dicoba ulang tidak menggandakan siswa/orang.

### Absensi pegawai mandiri
- Absen di luar radius sekolah ditolak; waktu memakai jam server (mengubah jam ponsel tidak berpengaruh).
- Satu catatan per pegawai per tanggal; absen ganda ditolak.
- Koordinat tersimpan, hanya dapat dilihat admin berwenang, dan akses melihatnya diaudit; pegawai lain tidak melihatnya.
- Absen masuk setelah jam masuk resmi ditandai terlambat (tanpa toleransi); tidak memengaruhi apa pun selain laporan.
- Hari non-kerja (Sabtu, Minggu) tidak mewajibkan absen; radius dapat diubah di pengaturan dan diuji pada batas (mis. 10 m dan lebih besar).
- Koreksi/pencatatan oleh admin mensyaratkan alasan dan tercatat di audit; pegawai tanpa akun dapat dicatat admin.
- Pegawai hanya dapat mengabsen dirinya sendiri.

### PPDB, daftar ulang, dan akun (dari skenario sistem lama, DEC-078..096)
- Usia 4–6 tahun dihitung per 1 Juli tahun awal tahun ajaran target (bukan tahun kalender saat mendaftar); di luar rentang ditolak.
- PPDB ditutup: pendaftaran akun/formulir baru ditolak (termasuk POST langsung → 403); pendaftar lulus tanpa pembayaran daftar ulang tidak dapat mengunggah dan kelulusannya dibatalkan; ortu tanpa anak hidup tidak dapat login; setelah dibuka lagi, login pulih.
- Biaya pendaftaran dan SPP kurang dari nilai tagihan ditolak; daftar ulang boleh sebagian; pembayaran melebihi sisa ditolak; bukti kedua saat masih ada yang pending ditolak.
- Verifikasi pembayaran idempoten: verifikasi dua kali tidak menggandakan saldo atau email.
- Urutan gerbang: dokumen tidak dapat diverifikasi sebelum pembayaran terverifikasi; Lulus/Gagal hanya setelah semua dokumen diterima; keputusan ganda tidak menggandakan tagihan daftar ulang.
- Izin edit: selama aktif verifikasi dan keputusan terkunci; mengganti berkas memundurkan status dan mematikan izin.
- Buat NIS: PPDB harus ditutup, NSM terisi, tanpa bukti daftar ulang pending bernilai 0; menghasilkan angkatan otomatis.
- Rumus refund: bayar penuh → refund = 70% total; bayar sebagian → refund = terbayar − denda; terbayar < denda → wajib lunasi denda dulu; akun diblokir hanya setelah pelunasan terverifikasi dan tanpa anak aktif.
- Login dengan email/no. HP/username; kode aktivasi sekali pakai, kedaluwarsa, dan percobaan dibatasi; tidak ada kata sandi awal dari NIK.
- Wali dengan beberapa anak: setiap anak punya pendaftaran/status/tagihan sendiri dan tidak saling menimpa; wali tidak dapat membayar tagihan anak wali lain.
- Impor CSV/Excel: format salah ditolak per baris, NIK duplikat dilewati, baris valid tetap diproses, laporan baris gagal akurat, akun wali digabung per no. HP.
- Ekspor CSV/PDF mengikuti scope pengguna dan tercatat di audit.
- Tanggal terbit SPP mengikuti pengaturan; generate dua kali tidak menggandakan; hanya murid `aktif` ditagih.
- Huruf kapital otomatis tidak mengubah email, kata sandi, username, enum, atau narasi (uji tiap pengecualian BR-UI-001).

### Wizard tahun ajaran (DEC-097)
- Nama dan tanggal bawaan benar (1 Juli–30 Juni; Ganjil/Genap) dan dapat dikoreksi di pratinjau; cut-off bawaan = tanggal selesai periode.
- Hanya satu tahun ajaran aktif; tahun lama menjadi selesai; data lama utuh.
- Salin struktur kelas hanya menyalin nama kelas, fase, kapasitas, ruangan, bukan murid, wali kelas, guru pendamping, atau penugasan.
- Peringatan (SPP belum lunas, rapor belum terbit, setoran belum diposting) tidak memblokir tetapi aktivasi tanpa konfirmasi ditolak.
- Penempatan yang masih aktif otomatis selesai pada akhir tahun ajaran; murid tetap aktif dan tidak naik kelas otomatis.

### Penempatan dan absensi
- Penempatan beririsan untuk siswa yang sama ditolak (termasuk dua request bersamaan).
- Perpindahan mempertahankan histori.
- Absensi unik per siswa dan tanggal, meski kelas berbeda; status hanya salah satu dari hadir/sakit/izin/tanpa_keterangan.

### Keuangan
- Generate tagihan bulanan idempoten (dijalankan dua kali tidak menggandakan) dan memeriksa periode, penempatan/status siswa, serta jenis tagihan (BR-FIN-015/016/017).
- Pembayaran atas nama wali dapat dialokasikan ke tagihan beberapa anak miliknya, dan tidak ke tagihan anak orang lain.
- Refund hanya untuk pembayaran daftar ulang, menghitung bagian ditahan dari pengaturan saat itu, tertaut ke pembayaran asal, dan perubahan persentase dicatat di audit.
- Usulan alokasi otomatis ke tagihan terlama dulu; petugas dapat mengubah sebelum posting; setelah posting tidak dapat diubah.
- Alokasi total tidak melebihi pembayaran.
- Alokasi tidak melebihi sisa tagihan.
- Pembayaran sebagian tidak menghasilkan status lunas.
- Pembayaran yang menunggu verifikasi tidak membuat mutasi resmi.
- Posting pembayaran menciptakan mutasi terkait satu kali.
- Retry/double submit tidak menggandakan transaksi.
- Transfer akun tidak muncul sebagai pendapatan/pengeluaran.
- Transaksi terposting tidak dapat diubah/hapus melalui endpoint biasa.

### Tabungan
- Setoran wali kelas belum mengubah saldo resmi sebelum diposting; `diterima` belum mengubah saldo.
- Selisih disimpan; yang diposting adalah `nominal_diterima` per siswa dan dapat direkonsiliasi.
- Posting batch menghasilkan mutasi terkontrol dan tidak dobel.
- Penarikan memerlukan izin dan audit, tidak boleh melebihi saldo, dan dua penarikan bersamaan tidak membuat saldo negatif.
- Guru pendamping tidak dapat mencatat setoran; wali hanya dapat melihat saldo/mutasi anaknya dan tidak dapat mengajukan penarikan.

### Dokumen, rapor, dan komunikasi
- Berkas privat tidak dapat diunduh tanpa izin.
- Verifikasi selalu merujuk versi tertentu; mengganti berkas tidak mempertahankan status disetujui.
- Perubahan template surat tidak mengubah surat lama; nomor surat tidak ganda pada request bersamaan.
- Rapor: hanya wali kelas yang dapat menyusun; hanya rapor yang disetujui Kepala RA yang dapat diterbitkan; wali hanya melihat versi terbit anaknya; Refleksi Orang Tua hanya dapat diisi wali terverifikasi sebelum diajukan dan terkunci setelah terbit.
- Data absensi/pertumbuhan yang kosong ditampilkan jelas, tidak dikarang (BR-RAP-005).
- Cut-off: kehadiran dihitung dari tanggal mulai periode sampai `tanggal_cutoff_rapor`; pengukuran yang dibuat setelah cut-off tidak dipakai; mengubah cut-off mengubah draf tetapi tidak snapshot terbit.
- Rapor dapat terbit tanpa Refleksi Orang Tua dari portal; tingkat hanya BB/MB/BSH/BSB.
- Nomor surat mengikuti format `urut/kode/bulan romawi/tahun` dan reset pada tahun baru; surat dapat diunduh sebagai PDF dan .docx, keduanya privat.
- Hanya satu tahun ajaran `aktif`; mengaktifkan yang lain menonaktifkan/menolak sesuai aturan; status siswa hanya aktif/lulus/keluar.
- Tidak ada fitur penghapusan data: entitas nonaktif diarsipkan, tidak dihapus; endpoint hapus untuk data pribadi/ledger tidak tersedia (DEC-071).
- Wali kelas hanya dapat membuat pengumuman untuk kelasnya; Admin Sekolah hanya melihat tagihan dan tidak dapat generate tagihan atau memverifikasi pembayaran non-tunai (DEC-070).
- Wali tambahan hanya terverifikasi setelah admin memeriksa unggahan KK/akta kelahiran anak.
- Snapshot rapor tidak berubah setelah data sumber diubah.
- Pembuatan versi baru tidak menghapus versi lama.
- Pengumuman menghasilkan penerima/notifikasi yang konsisten.
- Kegagalan email tidak membatalkan transaksi bisnis utama.

## 3. Transaksi dan konkurensi

Test kasus dua request bersamaan untuk mengalokasikan sisa tagihan, memposting pembayaran, menempatkan siswa, atau menerima setoran. Gunakan locking/constraint/transaction yang sesuai. Test yang hanya dijalankan serial tidak cukup membuktikan race condition aman.

## 4. Quality gates

- Static analysis/lint dan pemeriksaan format yang diadopsi proyek.
- Unit/feature test untuk area terdampak.
- Build frontend produksi berhasil.
- Tidak ada secret dalam diff.
- Migration dapat dijalankan pada database baru dan tidak menghancurkan data.
- Dokumen terkait dan acceptance criteria diperbarui.
- Tidak ada klaim “lulus” tanpa output test aktual.
