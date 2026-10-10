# Glosarium TELAGA v2

| Istilah | Pengertian |
|---|---|
| Orang | Identitas dasar yang dapat memiliki profil siswa, wali, pegawai, dan/atau akun. |
| Pengguna | Akun login; bukan sinonim Orang. |
| Role | Kumpulan permission yang diberikan kepada pengguna. |
| Permission | Izin untuk melakukan tindakan tertentu. |
| Policy | Keputusan backend apakah suatu tindakan diperbolehkan untuk record tertentu. |
| Jabatan | Posisi pegawai, misalnya Guru, Kepala RA, Bendahara, OB. |
| Penugasan | Tanggung jawab kerja/akademik untuk rentang waktu tertentu. |
| Wali murid | Orang yang mempunyai hubungan sah dengan satu atau lebih siswa. |
| Calon siswa | Anak dalam proses pendaftaran yang belum tentu menjadi siswa. Datanya disimpan di Pendaftaran sampai diterima. |
| PPDB | Proses Pendaftaran Murid Baru. |
| Pendaftaran (Pengajuan) | Satu pengajuan untuk satu calon anak dalam satu periode pendaftaran. “Pendaftaran” adalah istilah baku; “pengajuan” hanya sinonim dalam kalimat. |
| Pegawai (Staf) | Orang yang bekerja di sekolah. “Pegawai” adalah istilah baku untuk data; “staf” dipakai untuk akun/undangan staf. |
| Wali kelas | Jenis penugasan akademik: pemegang kelas yang menyusun rapor, menandatangani, dan mencatat setoran tabungan. |
| Guru pendamping | Jenis penugasan akademik: mengakses siswa kelasnya tanpa hak rapor dan setoran. |
| Absensi | Catatan kehadiran harian siswa (status hadir, sakit, izin, tanpa keterangan) atau pegawai; keduanya domain terpisah. |
| Verifikasi | Pemeriksaan oleh petugas berwenang atas dokumen (per versi), pembayaran, atau hubungan wali–anak. |
| Rekonsiliasi | Pencocokan jumlah rincian dan jumlah yang diterima/saldo dengan mutasi resmi. |
| Selisih | Perbedaan antara nominal diajukan dan nominal diterima pada setoran tabungan. |
| Penarikan | Pengambilan dana tabungan siswa oleh bendahara/admin berwenang; bukan permohonan di portal. |
| Refund | Pengembalian sebagian uang pendaftaran/pangkal saat pendaftar mundur (70% dikembalikan, 30% ditahan; dapat diatur). |
| Riwayat Penempatan Siswa | Histori siswa ditempatkan di kelas/tahun ajaran, termasuk perpindahan. |
| Tahun ajaran | Rentang tahun akademik sekolah. |
| Periode pembelajaran | Semester/periode dalam tahun ajaran. |
| Tagihan | Kewajiban pembayaran. |
| Pembayaran | Uang yang dicatat dan dapat dialokasikan ke tagihan. |
| Alokasi pembayaran | Bagian nominal pembayaran yang dialokasikan ke tagihan tertentu. |
| Mutasi keuangan | Entri pada ledger kas/bank sekolah. |
| Transfer keuangan | Pemindahan dana antarakun; bukan pendapatan/pengeluaran operasional. |
| Rekening tabungan | Rekening pencatatan tabungan siswa. |
| Setoran tabungan | Batch pengajuan/penerimaan setoran yang direkonsiliasi. “Setoran tabungan” adalah istilah baku; “batch setoran” hanya sinonim teknis. |
| Rapor (Laporan) | “Rapor” adalah dokumen LCPA per siswa. “Laporan” adalah keluaran agregat (keuangan, absensi, dll.) dan bukan rapor. |
| Wali Murid | Nama role pengguna bagi wali. Pada dokumen rapor, bagian pengesahan menyebutnya “Orang Tua/Wali Siswa”. |
| Mutasi tabungan | Transaksi resmi yang memengaruhi saldo tabungan. |
| Dokumen | Objek bisnis dengan metadata, status, konteks, dan versi. |
| Berkas | File fisik di storage. |
| Media | Aset untuk website/CMS publik. |
| Snapshot | Salinan data pada waktu tertentu yang tidak berubah setelah diterbitkan. |
| LCPA | Laporan Capaian Pembelajaran Anak. |
| DDTK | Deteksi Dini Tumbuh Kembang: bagian rapor yang memuat pengukuran tinggi badan, berat badan, dan lingkar kepala. Judul bagian rapor: “PRESENSI DAN DDTK”. |
| NIS | Nomor Induk Siswa: NSM (12 digit) + 2 digit tahun + 3 digit urut, dibuat massal per periode oleh admin. |
| NSM | Nomor Statistik Madrasah, identitas RA; disimpan di Profil Sekolah. |
| Daftar ulang | Pembayaran/penegasan tempat setelah pendaftar diterima; tagihannya dibuat untuk siswa dan menjadi dasar refund bila wali mengundurkan diri. |
| Cut-off rapor | Tanggal batas data yang dihitung ke rapor suatu semester (kehadiran dan pengukuran terakhir). Default = tanggal selesai semester. |
| Absen mandiri | Pencatatan jam masuk/pulang oleh pegawai sendiri lewat ponsel, sah hanya bila berada di area sekolah (validasi GPS terhadap titik dan radius sekolah). |
| Calon murid | Murid berstatus `calon`: sudah diterima, belum ber-NIS. Menjadi `aktif` saat NIS dibuat. |
| Kode aktivasi | Kode sekali pakai berumur terbatas dari admin untuk mengaktifkan akun wali lama; wali lalu menetapkan kata sandi dan email sendiri. |
| Angkatan | Tahun masuk murid, diturunkan dari 2 digit tahun pada NIS. |
| Izin edit | Pembukaan sementara oleh admin agar wali dapat mengedit pendaftaran setelah pembayaran terverifikasi. |
| Tahun ajaran target PPDB | Tahun ajaran yang sedang dibuka untuk pendaftaran; dapat berbeda dari tahun ajaran aktif sistem. |
| Fase | Tahap capaian pada kepala rapor; pada RA bernilai “Fondasi”. |
| Tingkat Ketercapaian | Penilaian per butir capaian: BB, MB, BSH, BSB. |
| Panca Cinta | Lima area pada template LCPA sumber. |
| Modular monolith | Satu aplikasi/deployment dengan batas modul internal yang jelas. |
| Idempotensi | Proses yang dijalankan berulang tidak menimbulkan efek bisnis ganda. |
| Posting | Menjadikan transaksi sebagai catatan resmi/ledger. |
| Reversal/adjustment | Transaksi koreksi yang menautkan dan membalik/memperbaiki transaksi asal tanpa menghapus histori. |
