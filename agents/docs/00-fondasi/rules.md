# Aturan Bisnis TELAGA v2

Aturan ini berlaku lintas modul. Setiap perubahan harus memperbarui dokumen terkait dan `decision-log.md` bila keputusan produk berubah.

Untuk detail aturan, dokumen ini menang atas `prd.md` (DEC-045). Rujukan `DEC-xxx` pada aturan menunjuk keputusan yang mendasarinya. Aturan tanpa rujukan DEC berasal dari dokumentasi awal.

## A. Identitas, akun, dan akses
- **BR-ID-001** — `Orang` adalah identitas dasar untuk menghindari duplikasi data orang yang sama di profil siswa, wali, pegawai, dan pengguna.
- **BR-ID-002** — Satu orang maksimal mempunyai satu akun pengguna, dan setiap akun pengguna wajib terkait tepat satu orang (DEC-027).
- **BR-ID-003** — Tidak semua pegawai harus mempunyai akun login.
- **BR-ID-004** — Satu akun dapat memiliki beberapa role bila diotorisasi.
- **BR-ID-005** — Role menentukan tindakan; Policy dan scope data menentukan record yang boleh diakses.
- **BR-ID-006** — Jabatan berbeda dari role, permission, dan penugasan.
- **BR-ID-007** — Pendaftaran mandiri hanya tersedia untuk wali murid.
- **BR-ID-008** — Akun admin/guru/bendahara dibuat atau diundang melalui proses sekolah.
- **BR-ID-009** — Status akun, verifikasi email, dan status pendaftaran anak adalah tiga hal terpisah.
- **BR-ID-010** — Satu wali dapat memiliki banyak anak; satu anak dapat memiliki beberapa wali.
- **BR-ID-011** — Hubungan wali-anak tidak dianggap sah hanya karena pengguna mengetahui atau memasukkan ID anak.
- **BR-ID-012** — Pengguna tidak dapat menaikkan role/permission dirinya melalui pengaturan profil biasa.
- **BR-ID-013** — Perubahan role/permission yang sensitif diaudit.
- **BR-ID-014** — Semua otorisasi dijalankan di backend termasuk pada request langsung.
- **BR-ID-015** — Wali yang mendaftar mandiri membuat `orang` baru. Bila ada kemungkinan kecocokan dengan orang yang sudah ada (mis. guru yang juga wali), staf berwenang meninjau dan menautkan secara manual. Penggabungan otomatis hanya berdasarkan nama atau email dilarang. Respons ke pendaftar tidak boleh membocorkan keberadaan orang atau email yang sudah ada (DEC-027).
- **BR-ID-016** — Role Guru diberikan manual oleh admin berwenang. Jabatan Guru tidak otomatis memberi role (DEC-046).
- **BR-ID-017** — Penugasan akademik berjenis wali kelas atau guru pendamping. Keduanya mengakses siswa kelas yang ditugaskan selama penugasan berlaku. Hanya wali kelas yang menyusun rapor, menandatangani rapor, dan mencatat setoran tabungan (DEC-046).

## B. Autentikasi, OTP, dan sesi
- **BR-AUTH-001** — Login web memakai satu isian yang menerima email, no. HP, atau username, beserta kata sandi (DEC-083). No. HP dinormalisasi (0 → 62). Pesan kesalahan tidak membedakan identitas yang tidak ada dan kata sandi yang salah.
- **BR-AUTH-002** — OTP email berisi **6 digit**.
- **BR-AUTH-003** — Masa berlaku OTP awal adalah 10 menit.
- **BR-AUTH-004** — Maksimal 5 percobaan per kode OTP.
- **BR-AUTH-005** — Jeda awal sebelum meminta kode baru adalah 60 detik.
- **BR-AUTH-006** — Penerbitan kode baru membatalkan kode sebelumnya untuk alur yang sama.
- **BR-AUTH-007** — OTP hanya dapat dipakai sekali, dibangkitkan secara aman, dan tidak disimpan/log dalam teks biasa.
- **BR-AUTH-008** — OTP diberi rate limit per akun/email dan sinyal tambahan yang sesuai.
- **BR-AUTH-009** — OTP verifikasi email dan pemulihan akun tidak boleh saling tertukar. Pemulihan kata sandi: OTP email yang valid menerbitkan token reset sekali pakai berumur pendek (DEC-040).
- **BR-AUTH-010** — OTP, password, token, dan secret tidak boleh masuk log.
- **BR-AUTH-011** — Respons login/pemulihan tidak membocorkan apakah email terdaftar.
- **BR-AUTH-012** — Password memakai hashing aman, bukan enkripsi yang bisa dibalik.
- **BR-AUTH-013** — Percobaan login dibatasi untuk menghambat brute force.
- **BR-AUTH-014** — Session diregenerasi setelah login; logout membatalkan sesi; akun nonaktif kehilangan akses.
- **BR-AUTH-015** — MFA tidak dibangun pada V2 awal (DEC-041). Sebagai kompensasi: kata sandi minimum 12 karakter, rate limit login, audit tindakan sensitif, dan prosedur pemulihan Admin Utama tertulis.
- **BR-AUTH-017** — Akun wali lama (murid diinput admin atau impor) diaktifkan lewat kode aktivasi sekali pakai berumur terbatas yang dibuat admin dan diserahkan langsung. Tidak ada kata sandi awal yang diturunkan dari NIK, no. HP, atau data publik lain. Saat aktivasi wali menetapkan kata sandi (minimum 12 karakter), username, dan email (diverifikasi OTP). Percobaan kode dibatasi; kode baru membatalkan kode lama (DEC-083, DEC-096).
- **BR-AUTH-016** — Akun staf dibuat melalui undangan: tautan sekali pakai dengan TTL awal 48 jam untuk menetapkan kata sandi. Undangan baru membatalkan undangan lama (DEC-040).

## C. Data master & akademik dasar
- **BR-AKD-001** — Tahun ajaran memiliki tanggal mulai/selesai dan status yang jelas. Hanya satu tahun ajaran berstatus `aktif` pada satu waktu (DEC-066). Status murid: `calon` (diterima, belum ber-NIS), `aktif`, `lulus`, `keluar` (DEC-079). Tahun ajaran baru dibuat lewat wizard “Tahun ajaran berikutnya” (DEC-094, DEC-097); tahun ajaran target PPDB dinavigasi terpisah dari tahun ajaran aktif (BR-PPDB-018).
- **BR-AKD-002** — Periode pembelajaran terikat pada tahun ajaran.
- **BR-AKD-003** — Kelas/kelompok terikat pada tahun ajaran yang sesuai.
- **BR-AKD-004** — Perubahan penugasan guru/wali kelas disimpan sebagai histori.
- **BR-AKD-005** — Nilai referensi yang menjadi pilihan memakai kode/status terkontrol, bukan teks bebas.
- **BR-AKD-006** — Riwayat Penempatan Siswa menyimpan siswa, kelas, tanggal mulai/akhir, serta alasan jika relevan.
- **BR-AKD-007** — Satu siswa maksimal mempunyai satu penempatan aktif pada satu waktu; rentang tanggal penempatan siswa yang sama tidak boleh tumpang tindih (DEC-039).
- **BR-AKD-008** — Perpindahan kelas di tengah tahun menutup/menyelesaikan histori sebelumnya dan membuat histori baru.
- **BR-AKD-009** — Wizard Tahun ajaran berikutnya menyiapkan nama otomatis (mis. 2027/2028), tanggal bawaan 1 Juli–30 Juni, periode Ganjil (1 Juli–31 Desember) dan Genap (1 Januari–30 Juni) dengan cut-off rapor bawaan = tanggal selesai periode; admin menyesuaikan di pratinjau. Salin struktur kelas bersifat opsional dan hanya menyalin nama kelas, fase, kapasitas, dan ruangan, bukan murid, wali kelas, guru pendamping, maupun penugasan (DEC-097).
- **BR-AKD-010** — Tidak ada kenaikan kelas otomatis. Pada akhir tahun ajaran penempatan yang masih aktif otomatis berstatus selesai; murid tetap aktif; admin menempatkan murid ke kelas tahun ajaran baru lewat Riwayat Penempatan Siswa (DEC-097; alat penempatan massal: OD-39).
- **BR-AKD-011** — Sebelum tahun ajaran baru diaktifkan, sistem menampilkan peringatan yang tidak memblokir bila di tahun lama masih ada tagihan SPP belum lunas, rapor belum terbit, atau setoran tabungan belum diposting; admin wajib mengonfirmasi. Tahun lama tetap dapat dibuka untuk dilihat (DEC-097).

## D. PPDB
- **BR-PPDB-001** — Periode pendaftaran dikelola per tahun/periode penerimaan; periode lama tetap tersimpan.
- **BR-PPDB-002** — Wali yang sama boleh mendaftarkan lebih dari satu anak.
- **BR-PPDB-003** — Satu pendaftaran merepresentasikan satu calon anak dalam satu periode pendaftaran. Kemungkinan duplikat (nama + tanggal lahir + periode sama) ditandai sebagai peringatan kepada petugas, bukan penolakan otomatis (DEC-029).
- **BR-PPDB-004** — Setiap perubahan status pendaftaran menghasilkan histori berisi pelaku, waktu, status asal/tujuan, dan catatan jika perlu.
- **BR-PPDB-005** — Keputusan akhir dijalankan backend oleh pengguna berwenang.
- **BR-PPDB-006** — Dokumen yang diwajibkan harus lengkap/terverifikasi sebelum transisi yang mensyaratkannya.
- **BR-PPDB-007** — Penerimaan membentuk/mengaitkan `orang` dan profil siswa (data calon disalin dari pendaftaran) tanpa menghapus data pendaftaran. Wali pengaju otomatis terverifikasi pada hubungan wali–anak (DEC-029, DEC-033).
- **BR-PPDB-008** — Penolakan/penarikan tidak menghapus histori yang wajib dipertanggungjawabkan.
- **BR-PPDB-009** — Status pendaftaran, pembayaran, dan kelengkapan dokumen merupakan dimensi berbeda.
- **BR-PPDB-010** — Informasi yang tampil di halaman publik hanya yang ditandai dipublikasikan oleh petugas berwenang. Tidak ada mekanisme izin publikasi terpisah (DEC-036).
- **BR-PPDB-011** — Data calon anak disimpan pada pendaftaran sampai diterima (DEC-029). Pendaftar yang ditolak atau menarik diri tidak membentuk `orang`.
- **BR-PPDB-012** — Biaya pendaftaran (jenis tagihan `biaya_pendaftaran`) ditagih ke pendaftaran. Nominal dan jenis tagihan dikonfigurasi (DEC-030).
- **BR-PPDB-013** — Selama pendaftaran, wali hanya mengakses pengajuannya sendiri (DEC-033).
- **BR-PPDB-014** — Bila biaya pendaftaran dikonfigurasi, pengajuan baru dapat diperiksa dan diputuskan setelah biaya pendaftaran lunas dan terverifikasi (DEC-049).
- **BR-PPDB-015** — Nomor pendaftaran berformat `PPDB-{tahun}-{urut 4 digit}`, unik per periode pendaftaran (DEC-058).
- **BR-PPDB-016** — Usia calon harus 4 sampai 6 tahun pada 1 Juli tahun awal tahun ajaran tujuan (bukan tahun kalender saat mendaftar). Contoh: PPDB 2027/2028 dibuka Oktober 2026 → cut-off 1 Juli 2027 (DEC-081).
- **BR-PPDB-017** — Admin mengatur kuota pendaftaran (target diterima) per periode; kuota dipakai sebagai ringkasan di dashboard dan peringatan, bukan penolakan otomatis kecuali ditetapkan kemudian (DEC-081).
- **BR-PPDB-018** — PPDB memiliki status buka/tutup dan tahun ajaran target PPDB yang terpisah dari tahun ajaran aktif sistem; SPP, kelas, dan murid sistem tetap mengikuti tahun ajaran aktif (DEC-081).
- **BR-PPDB-019** — Saat PPDB ditutup: (a) pendaftaran akun wali baru dan formulir baru ditolak; (b) pendaftar berstatus lulus yang belum membayar daftar ulang sama sekali tidak dapat mengunggah bukti dan kelulusannya dibatalkan; (c) login ortu ditolak bila tidak ada anak “hidup” (aktif, calon lulus, atau dibatalkan dengan denda belum lunas). Saat PPDB dibuka lagi, gerbang (c) tidak berlaku (DEC-081).
- **BR-PPDB-020** — Urutan wajib: pembayaran biaya pendaftaran → verifikasi pembayaran → verifikasi dokumen → seleksi. Dokumen tidak dapat diverifikasi sebelum pembayaran terverifikasi; tombol Lulus/Gagal baru tersedia setelah semua dokumen diterima; server menolak keputusan bila status bukan “diproses seleksi” (DEC-088).
- **BR-PPDB-021** — Wali boleh mengedit pendaftaran sampai pembayaran terverifikasi. Setelahnya admin dapat membuka “izin edit” sementara: selama aktif, verifikasi dan keputusan terkunci; berkas yang diganti mengembalikan statusnya ke menunggu verifikasi dan memundurkan status pendaftaran; izin edit padam setelah wali menyimpan (DEC-088).
- **BR-PPDB-022** — Admin dapat “Ubah Keputusan” setelah lulus/gagal selama belum ada pembayaran daftar ulang; tindakan dicatat di audit (DEC-088).
- **BR-PPDB-023** — Satu wali dapat mendaftarkan beberapa anak; setiap anak memiliki pendaftaran, status, dan tagihan sendiri; portal menyediakan pindah antar-anak (DEC-088).
- **BR-PPDB-024** — Wali wajib melengkapi profil (ayah dan ibu, alamat) sebelum dapat mendaftarkan anak (DEC-082).

## E. Siswa, wali, absensi, kesehatan, pertumbuhan
- **BR-SIS-001** — Data inti siswa menjadi sumber untuk absensi, pembayaran, dokumen, penempatan, dan rapor.
- **BR-SIS-002** — Hubungan siswa-wali mencatat jenis hubungan/status verifikasi dan penanda wali utama bila dibutuhkan. Wali pengaju PPDB terverifikasi otomatis saat diterima; wali tambahan diverifikasi admin dengan bukti unggahan Kartu Keluarga atau akta kelahiran anak (DEC-033, DEC-063).
- **BR-SIS-003** — Wali hanya mengakses anak yang terhubung sah dan data yang diizinkan.
- **BR-SIS-004** — Absensi siswa bersifat harian. Mencatat siswa, tanggal, status (`hadir`, `sakit`, `izin`, `tanpa_keterangan`), kelas/penempatan relevan, dan pencatat (DEC-034).
- **BR-SIS-005** — Absensi duplikat untuk kombinasi siswa dan tanggal yang sama harus dicegah (unik pada siswa + tanggal, tidak bergantung kelas).
- **BR-SIS-006** — Profil kesehatan dipisahkan dari riwayat pengukuran pertumbuhan.
- **BR-SIS-007** — Pengukuran menyimpan tanggal ukur, nilai, satuan, pencatat, dan catatan yang diperlukan.
- **BR-SIS-008** — Data kesehatan/pertumbuhan hanya dapat diakses role yang berkewenangan.
- **BR-SIS-009** — Koreksi pengukuran tidak boleh diam-diam menghapus histori yang diperlukan.
- **BR-SIS-010** — Foto/portofolio anak privat secara default. Publikasi hanya melalui tindakan petugas berwenang; tidak ada fitur izin publikasi maupun kotak centang persetujuan (DEC-036, DEC-055).
- **BR-SIS-011** — NIS berformat NSM (12 digit dari Profil Sekolah) + 2 digit tahun + 3 digit urut. Admin menjalankan “Buat NIS” per periode: siswa diterima yang belum ber-NIS diurutkan abjad nama lalu diberi nomor berurutan. Siswa yang menyusul mendapat nomor lanjutan; NIS yang sudah terbit tidak pernah berubah atau dipakai ulang. 2 digit tahun = tahun awal tahun ajaran (DEC-050, DEC-058). Murid berstatus `calon` menjadi `aktif` hanya saat NIS dibuat (DEC-079). Syarat Buat NIS: PPDB sudah ditutup, NSM terisi (12 digit), dan tidak ada bukti daftar ulang pending dengan terbayar 0 (agar urutan abjad tidak terlewat). `angkatan` diturunkan otomatis dari 2 digit tahun NIS (mis. 26 → 2026), juga untuk murid lama yang dimasukkan lewat form atau impor.
- **BR-SIS-012** — Data murid wajib saat pendaftaran: nama lengkap, nama panggilan, jenis kelamin, agama (otomatis Islam), tempat dan tanggal lahir, NIK (16 digit angka, unik), anak ke-, jumlah saudara, warga negara, bahasa keseharian, kondisi kesehatan khusus, ukuran baju (S/M/L/XL/XXL/Jumbo), status mengaji (Sudah/Belum; bila Sudah: tempat, metode, jilid), pernah belajar (PAUD/Les/Belum; bila PAUD/Les: keterangan). NISN (10 digit) menyusul setelah masuk sekolah (DEC-082).
- **BR-SIS-013** — Profil orang tua memuat data ayah dan ibu secara terpisah (nama, tempat/tanggal lahir, agama, pendidikan terakhir S3–SD, pekerjaan + keterangan bila lainnya, penghasilan bulanan, no. HP; no. HP dapat berbeda); salah satu dapat ditandai “tidak ada”. Alamat keluarga diisi satu kali (jalan/RT/RW + provinsi, kota/kabupaten, kecamatan, kelurahan dari data wilayah resmi) dan dipakai untuk semua anak (DEC-082).
- **BR-SIS-014** — Validasi masukan: no. HP angka 9–14 digit dan dinormalisasi (awalan 0 → 62); nama hanya huruf, spasi, titik, apostrof, tanda hubung; NIK 16 digit angka; NUPTK 16 digit angka bila diisi. Aturan didefinisikan sekali dan dipakai semua form dan impor.

## F. Pegawai
- **BR-PEG-001** — Guru merupakan jabatan/posisi pegawai, bukan tipe pegawai terpisah.
- **BR-PEG-002** — Perubahan jabatan membuat histori baru; tidak menimpa histori lama.
- **BR-PEG-003** — Penugasan akademik terpisah dari profil pegawai dan dapat berubah sesuai tahun/periode.
- **BR-PEG-004** — Pegawai tidak wajib memiliki akun, kecuali pegawai yang wajib absen mandiri (guru, kepala, operator; BR-PEG-008).
- **BR-PEG-005** — Absensi pegawai berbeda dari absensi siswa.
- **BR-PEG-006** — Dokumen pegawai privat kecuali dipublikasikan oleh petugas berwenang (DEC-036).
- **BR-PEG-007** — Payroll dan HR lengkap berada di luar cakupan awal.
- **BR-PEG-008** — Pegawai yang wajib absen mencatat jam masuk dan jam pulang sendiri lewat ponsel, hanya bila berada di area sekolah (validasi GPS terhadap titik dan radius di pengaturan). Waktu dicatat dari server (WIB). Satu catatan per pegawai per tanggal (DEC-064). Radius awal 10 meter dan dapat diubah di pengaturan. Sistem menyimpan koordinat lengkap, jarak, dan hasil validasi lokasi; koordinat adalah data pribadi dengan akses terbatas dan tidak ditampilkan ke pengguna lain (DEC-072). Hari wajib masuk: Senin–Jumat.
- **BR-PEG-009** — Pegawai tanpa akun dicatat hadirnya oleh admin. Bila GPS gagal atau ditolak, admin dapat mencatat atau mengoreksi absensi dengan alasan wajib; setiap koreksi diaudit (DEC-074).
- **BR-PEG-011** — NUPTK dan NIP guru opsional; NUPTK, bila diisi, 16 digit angka dan unik; NIP tampil “-” pada rapor bila kosong. Data guru memuat jenis kelamin, tempat/tanggal lahir, pendidikan terakhir, tanggal mulai mengajar, jabatan, dan foto (DEC-093).
- **BR-PEG-010** — Satu jam masuk resmi untuk semua pegawai, nilai awal 07.00 WIB, dapat diubah di pengaturan. Absen masuk setelah jam itu (pukul 07.00.01 dan seterusnya) ditandai terlambat tanpa toleransi; penanda hanya informasi di laporan dan tidak memengaruhi hal lain (DEC-072).

## G. Tagihan dan pembayaran
- **BR-FIN-001** — Tagihan, pembayaran, alokasi pembayaran, dan mutasi keuangan adalah objek berbeda.
- **BR-FIN-002** — Satu pembayaran dicatat atas nama wali pembayar dan dapat dialokasikan ke satu atau lebih tagihan, termasuk tagihan beberapa anak milik wali yang sama, melalui record alokasi eksplisit (DEC-037).
- **BR-FIN-003** — Total alokasi tidak boleh melebihi nominal pembayaran.
- **BR-FIN-004** — Alokasi ke satu tagihan tidak boleh melebihi sisa tagihan.
- **BR-FIN-005** — Cicilan per jenis (DEC-078): biaya pendaftaran wajib dibayar penuh; SPP wajib lunas per tagihan bulan (tidak boleh dicicil); hanya daftar ulang yang boleh dicicil. Pembayaran sebagian pada daftar ulang mengurangi sisa tagihan; tagihan lunas saat seluruh nilainya telah dialokasikan dari pembayaran terverifikasi. Server menolak pembayaran pendaftaran/SPP yang kurang dari nilai tagihan, dan pembayaran yang melebihi sisa.
- **BR-FIN-006** — Denda keterlambatan SPP tidak digunakan pada rancangan awal.
- **BR-FIN-007** — Nominal SPP bawaan Rp250.000 untuk semua kelompok; nominal harus dapat dikonfigurasi (DEC-037, DEC-060).
- **BR-FIN-008** — Refund berlaku ketika wali mengajukan pengunduran diri setelah membayar uang daftar ulang dan sebelum NIS dibuat. Rumus: denda = `persen_ditahan` (bawaan 30%) × total daftar ulang; refund = max(0, terbayar − denda). Contoh total Rp1.000.000: bayar penuh refund Rp700.000; bayar Rp500.000 refund Rp200.000; terbayar kurang dari denda → wajib melunasi denda dulu, refund 0, dan akun ortu diblokir hanya setelah pelunasan terverifikasi serta tidak ada anak aktif (DEC-080). Persentase dapat diatur dari dashboard, perubahan diaudit, bukan konstanta tersebar (DEC-038, DEC-049). Refund disetujui Admin Sekolah atau Bendahara (Admin Utama juga berwenang). Pengaju dan penyetuju tidak wajib orang berbeda; tindakan tercatat di audit (DEC-056, DEC-068).
- **BR-FIN-009** — Pembayaran tunai di sekolah dapat dicatat admin/bendahara berwenang sesuai alur kas.
- **BR-FIN-010** — Pembayaran non-tunai yang memerlukan bukti menunggu verifikasi sampai diperiksa role berwenang.
- **BR-FIN-011** — Status pembayaran tidak otomatis sama dengan status tagihan.
- **BR-FIN-012** — Verifikasi dan pembentukan mutasi terkait harus atomik.
- **BR-FIN-013** — Pembayaran/alokasi/mutasi yang telah diposting tidak dapat diedit/dihapus melalui CRUD biasa.
- **BR-FIN-014** — Koreksi transaksi dilakukan melalui reversal/adjustment dengan alasan, waktu, pelaku, dan relasi ke asal.
- **BR-FIN-015** — Generate tagihan bulanan harus idempoten.
- **BR-FIN-016** — Sebelum generate tagihan, periksa periode, penempatan/status siswa, dan jenis tagihan yang berlaku. Diskon, beasiswa, dan prorata tidak ada pada V2 awal (DEC-037).
- **BR-FIN-017** — Setiap tagihan terikat tepat satu dari `siswa` atau `pendaftaran` (DEC-030). Tagihan unik per (pemilik tagihan, jenis tagihan, periode tagihan) agar generate idempoten.
- **BR-FIN-018** — Refund dicatat sebagai transaksi tertaut ke pembayaran daftar ulang asal (reversal sebagian), bukan penghapusan atau pengubahan pembayaran (DEC-038, DEC-049).
- **BR-FIN-021** — Bukti pembayaran memuat jumlah, tanggal bayar, bank asal transfer (dipilih dari daftar bank yang dapat dicari), berkas bukti, dan waktu unggah; halaman unggah menampilkan rekening tujuan sekolah (bank, nomor, atas nama) dari pengaturan. Hanya satu bukti berstatus menunggu verifikasi per tagihan; unggahan kedua ditolak. Verifikasi bersifat idempoten: bukti yang sudah diverifikasi tidak dapat diverifikasi ulang dan tidak mengirim email berulang (DEC-086, DEC-096).
- **BR-FIN-022** — Tagihan SPP bulanan diterbitkan otomatis pada tanggal yang diatur admin (1–28, bawaan 1) untuk murid berstatus `aktif`; jadwal otomatis benar-benar mengikuti pengaturan itu. Tombol generate manual tersedia sebagai cadangan. Idempoten per murid dan bulan; email tagihan hanya untuk tagihan baru (DEC-090).
- **BR-FIN-020** — Saat pembayaran diverifikasi, sistem mengusulkan alokasi otomatis ke tagihan terlama dulu (dari pembayar yang sama); petugas dapat mengubah alokasi sebelum posting. Setelah posting alokasi tidak diubah, hanya dikoreksi dengan reversal (DEC-061).
- **BR-FIN-019** — Setelah pendaftaran diterima dan siswa terbentuk, tagihan daftar ulang dibuat untuk siswa (jenis tagihan `daftar_ulang`). Hanya pembayaran daftar ulang yang dapat di-refund (DEC-049).

## H. Keuangan sekolah
- **BR-KEU-001** — Modul keuangan mencakup pemasukan/pengeluaran sekolah di luar pembayaran siswa.
- **BR-KEU-002** — Akun kas dan bank dibedakan sesuai kebutuhan.
- **BR-KEU-003** — Transfer antar-akun bukan pemasukan/pengeluaran operasional.
- **BR-KEU-004** — Mutasi memiliki akun, tanggal, nominal, arah, kategori bila sesuai, deskripsi, pelaku, dan referensi sumber.
- **BR-KEU-005** — Hindari relasi polimorfik generik untuk sumber transaksi saat FK eksplisit masih layak.
- **BR-KEU-006** — Saldo harus dapat direkonsiliasi dengan mutasi ledger.
- **BR-KEU-007** — Transfer diproses secara atomik.
- **BR-KEU-008** — Mutasi terposting dikoreksi dengan penyesuaian/pembalikan, bukan penghapusan.
- **BR-KEU-009** — Laporan dapat menelusuri mutasi ke sumbernya.

## I. Tabungan siswa
- **BR-TAB-001** — Tabungan terpisah dari SPP/tagihan dan keuangan umum sekolah.
- **BR-TAB-002** — Rekening tabungan mempunyai relasi eksplisit ke siswa.
- **BR-TAB-003** — Wali kelas dapat mencatat setoran harian/batch jika diberi permission (DEC-046).
- **BR-TAB-004** — Catatan guru belum menambah saldo resmi.
- **BR-TAB-005** — Saldo resmi berubah hanya ketika batch berstatus `diposting` oleh bendahara atau role berwenang; `diterima` belum mengubah saldo.
- **BR-TAB-006** — Batch setoran memiliki detail setoran per siswa/rekening.
- **BR-TAB-007** — Per rincian siswa dicatat `nominal_diajukan` dan `nominal_diterima`; pada batch dicatat jumlah diajukan, diterima, dan selisih. Yang diposting adalah `nominal_diterima` per siswa (DEC-031).
- **BR-TAB-008** — Status batch: `draf`, `menunggu_diterima`, `diterima`, `ada_selisih`, `diposting`, `dibatalkan` (lihat `status-lifecycle.md` §5).
- **BR-TAB-009** — Penarikan oleh bendahara/admin yang berwenang melalui workflow tercatat. Wali tidak mengajukan penarikan di portal dan hanya melihat saldo/mutasi anak (DEC-044).
- **BR-TAB-010** — Mutasi tabungan terposting tidak diedit/dihapus langsung; gunakan adjustment/reversal.
- **BR-TAB-011** — Total header/detail direkonsiliasi secara atomik ketika batch diposting.
- **BR-TAB-012** — Saldo rekening tabungan tidak boleh negatif. Penarikan tidak boleh melebihi saldo; pemeriksaan dilakukan dalam transaksi dengan penguncian agar penarikan bersamaan aman.

## J. Rapor LCPA
- **BR-RAP-001** — Rapor V2 awal menggunakan struktur template LCPA yang diberikan.
- **BR-RAP-002** — Narasi dan Tingkat Ketercapaian diinput manual oleh wali kelas sampai modul asesmen dikembangkan. Refleksi Orang Tua diisi wali melalui portal sebelum rapor terbit (DEC-035).
- **BR-RAP-003** — Rapor tidak bergantung pada Kurikulum, RPPM, RPPH, Pelaksanaan Pembelajaran, atau Asesmen yang ditunda.
- **BR-RAP-004** — Identitas, penempatan, absensi, pertumbuhan diambil dari sumber resmi bila tersedia dan periode sesuai.
- **BR-RAP-005** — Jangan mengarang nilai jika data otomatis belum tersedia; tunjukkan data kosong secara jelas.
- **BR-RAP-006** — Versi rapor terbit adalah snapshot immutable.
- **BR-RAP-007** — Revisi membuat versi baru dan mempertahankan versi lama.
- **BR-RAP-008** — PDF dihasilkan dari versi tertentu.
- **BR-RAP-009** — Tingkat Ketercapaian bukan otomatis skor numerik; jangan menciptakan grading tanpa keputusan baru.
- **BR-RAP-010** — Panca Cinta pada sumber: Cinta Allah SWT dan RasulNya, Cinta Ilmu, Cinta Lingkungan, Cinta diri dan sesama, Cinta Tanah air. Setiap aspek memiliki deskripsi narasi dan area foto kegiatan.
- **BR-RAP-011** — Judul bagian ditulis “PRESENSI DAN DDTK” (koreksi dari “PRESENTSI” pada sumber). DDTK = Deteksi Dini Tumbuh Kembang (DEC-054).
- **BR-RAP-012** — Dokumentasi rapor mengikuti hak akses file privat.
- **BR-RAP-013** — Alur rapor: wali kelas menyusun → Kepala RA menyetujui → diterbitkan. Penerbitan hanya setelah persetujuan (DEC-032).
- **BR-RAP-014** — Refleksi Orang Tua boleh ditulis tangan pada cetakan atau diisi wali terverifikasi di portal (opsional). Isian portal adalah formulir terkait siswa dan periode yang benar, bukan balasan atau percakapan. Rapor boleh terbit tanpa isian portal dan tanpa tenggat. Isian yang ada ikut snapshot saat terbit dan tidak dapat diubah setelahnya (DEC-035, DEC-054).
- **BR-RAP-015** — Pengesahan memakai gambar tanda tangan pada PDF. Gambar tanda tangan disimpan privat; versi terbit menyimpan nama, jabatan, dan referensi gambar yang dipakai saat itu (DEC-035).
- **BR-RAP-016** — Cut-off rapor adalah `tanggal_cutoff_rapor` pada periode pembelajaran (default = tanggal selesai, dapat diubah admin per semester). Jumlah kehadiran dihitung dari absensi harian sejak tanggal mulai periode sampai cut-off; ukuran pertumbuhan diambil yang terakhir diukur pada atau sebelum cut-off (DEC-052).
- **BR-RAP-017** — Skala Tingkat Ketercapaian: BB (Belum Berkembang), MB (Mulai Berkembang), BSH (Berkembang Sesuai Harapan), BSB (Berkembang Sangat Baik). Setiap butir capaian pada template memiliki satu tingkat; bukan skor numerik (DEC-054).
- **BR-RAP-018** — Rujukan penilaian pertumbuhan adalah Permenkes No. 2 Tahun 2020 tentang Standar Antropometri Anak. Keterangan pada tabel Pengukuran diisi manual wali kelas melalui dropdown (termasuk pilihan “tidak diukur”); sistem tidak menghitung atau melabeli otomatis pada V2 awal (DEC-057).

## K. Dokumen dan surat
- **BR-DOK-001** — Dokumen adalah objek bisnis/metadata; berkas adalah file; media adalah aset publik/CMS.
- **BR-DOK-002** — Dokumen privat tidak dapat diakses hanya karena URL diketahui.
- **BR-DOK-003** — Versi baru tidak menimpa histori versi sebelumnya.
- **BR-DOK-004** — Gunakan FK/relasi eksplisit untuk konteks siswa, pegawai, pendaftaran, pembayaran, dan surat.
- **BR-DOK-005** — Surat dan template surat berbeda; perubahan template tidak mengubah surat historis.
- **BR-DOK-006** — Penomoran surat berformat `urut/kode/bulan romawi/tahun`, mis. 001/RA-AK/X/2026, reset tiap tahun; kode dapat dikonfigurasi dan nomor tidak ganda (DEC-051).
- **BR-DOK-009** — Surat keluar tersedia dalam format PDF dan Word (.docx) yang dapat diedit; rapor hanya PDF. Berkas hasil bersifat privat (DEC-062).
- **BR-DOK-007** — File divalidasi tipe/ukuran dan diakses melalui authorization.
- **BR-DOK-008** — Verifikasi mencatat pelaku, waktu, status, dan versi yang diperiksa.

## L. Komunikasi, kalender, CMS
- **BR-KOM-001** — Pengumuman adalah komunikasi satu arah dari sekolah kepada penerima.
- **BR-KOM-002** — Wali tidak dapat membalas/berkomentar di sistem pada V2 awal.
- **BR-KOM-003** — Pengumuman dapat membuat notifikasi portal dan email.
- **BR-KOM-004** — Status dibaca adalah telemetry sistem, bukan bukti bahwa isi dipahami.
- **BR-KOM-005** — Kegagalan email tidak membatalkan pengumuman yang sudah diterbitkan.
- **BR-KAL-001** — Agenda terkait dengan kalender/tahun/periode dan hanya tampil publik bila diizinkan.
- **BR-CMS-001** — CMS bukan database kedua untuk data akademik/pegawai.
- **BR-CMS-002** — Profil guru publik bersumber dari profil pegawai yang ditandai dipublikasikan oleh petugas berwenang (DEC-036).
- **BR-CMS-003** — Informasi pendaftaran publik membaca periode pendaftaran aktif yang berstatus dipublikasikan.
- **BR-CMS-004** — Media publik tidak otomatis cocok untuk dokumen privat.

## M. Audit dan operasional
- **BR-AUD-001** — Audit mencatat aktor, waktu, tindakan, objek, hasil, dan konteks relevan.
- **BR-AUD-002** — Perubahan role, PPDB, verifikasi dokumen, pembayaran, mutasi tabungan/keuangan, dan penerbitan rapor diaudit.
- **BR-AUD-003** — Audit log tidak menyimpan password, OTP, token, atau konten file sensitif.
- **BR-OPS-001** — Zona waktu operasi adalah Asia/Jakarta (WIB) dan dikonfigurasi di satu tempat; jangan hard-code zona waktu berbeda pada modul (DEC-042).
- **BR-OPS-002** — Backup mencakup database dan file yang direferensikan.
- **BR-OPS-003** — Shared hosting siap hanya setelah versi PHP/MySQL, document root, cron/queue, storage, email, dan PDF diuji.
- **BR-OPS-006** — Tidak ada data yang dihapus: seluruh record, termasuk data pribadi dan dokumen, menjadi arsip sekolah. Entitas yang tidak lagi aktif dinonaktifkan atau diarsipkan, bukan dihapus (DEC-065, DEC-071). Permintaan penghapusan dari pemilik data tidak dilayani oleh sistem; risiko ini diterima pemilik dan perlu ditinjau terhadap ketentuan perlindungan data pribadi sebelum produksi.
- **BR-OPS-005** — Target runtime konservatif: PHP 8.3 (minimum Laravel 13) dan MySQL/MariaDB versi lama yang masih didukung Laravel 13; aplikasi tidak bergantung pada fitur database baru (CHECK, fungsi JSON khusus, window function) (DEC-059).
- **BR-OPS-004** — Kegagalan job async bisa ditelusuri tanpa bocor rahasia.

## N. Impor, ekspor, notifikasi, tampilan, dan pelajaran sistem lama
- **BR-IMP-001** — Murid lama dimasukkan lewat impor CSV dan Excel (.xlsx) dengan template resmi. Sistem memeriksa format tiap baris, melewati NIK yang sudah ada, mengumpulkan baris gagal beserta alasannya dalam laporan "Baris N: pesan", dan tidak membatalkan baris yang valid. Opsi membuat akun wali dari no. HP memakai kode aktivasi (BR-AUTH-017); murid dengan no. HP wali yang sama digabung ke satu akun wali (selaras BR-ID-015). Alamat berjenjang dilengkapi wali sendiri (DEC-084).
- **BR-IMP-002** — Ekspor CSV dan PDF tersedia untuk data murid (filter kelas, tahun ajaran, status, angkatan/murid baru), laporan SPP (filter murid, ortu, tahun ajaran, bulan, periode tanggal, status bayar: sudah/belum/semua), dan transaksi. Ekspor memerlukan izin, mengikuti scope data pengguna, dan tercatat di audit (DEC-085).
- **BR-NTF-001** — Email otomatis berisi rincian transaksi, dikirim lewat antrean: pendaftaran terkirim dan bukti bayar diterima; hasil seleksi; tagihan baru (pendaftaran, daftar ulang, SPP); pembayaran terverifikasi atau ditolak (alasan dan sisa tunggakan). Kegagalan email tidak membatalkan transaksi (DEC-089, BR-KOM-005).
- **BR-UI-001** — Huruf kapital otomatis untuk data isian (nama, tempat lahir, alamat, nama kelas, jabatan, dll.). Dikecualikan: email, kata sandi, username, pilihan/enum, narasi rapor, refleksi, pengumuman, isi surat, konten website, catatan, nama berkas. Diterapkan di satu titik dengan daftar pengecualian yang diuji (DEC-087).
- **BR-UI-002** — Landing page dan dashboard admin dirancang baru (elegan, simpel, modern); sistem lama hanya referensi fungsi (DEC-095).
- **BR-CMS-005** — Biaya di halaman Info Pendaftaran diambil dari pengaturan keuangan (satu sumber). Logo, favicon, dan hak cipta footer dapat diubah admin dan dipakai di login serta semua portal. Beranda memiliki slide foto “Curriculum Highlight” berotomatis yang dikelola admin (DEC-092).
- **BR-OPS-007** — Sistem lama hanya referensi; kode tidak disalin. Kelemahannya menjadi persyaratan v2 (DEC-096): berkas privat tidak disajikan publik tanpa otorisasi; login dan OTP dibatasi percobaan; tidak ada kata sandi awal yang dapat ditebak; uang tidak memakai tipe floating point dan tidak dihitung dengan float; referensi pembayaran memakai FK eksplisit; verifikasi pembayaran idempoten dan terkunci; otorisasi memakai Policy; transisi status memeriksa status asal; setiap pengaturan yang tampil di UI harus berfungsi.