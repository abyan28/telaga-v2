# Arsitektur Domain TELAGA v2

- Status: rancangan logis disetujui sebagai fondasi; rincian implementasi perlu ditinjau saat desain tabel.
- Prinsip arsitektur: modular monolith.

## 1. Batas domain

| Domain | Tanggung jawab | Data utama |
|---|---|---|
| Identitas & Akses | orang, akun, role, permission, kebijakan | orang, pengguna, role, permission |
| Data Master & Akademik Dasar | tahun ajaran, periode, kelas, ruangan, penugasan akademik | tahun_ajaran, periode_pembelajaran, kelas, penugasan_akademik |
| PPDB | periode pendaftaran, formulir, dokumen, histori status, keputusan | periode_pendaftaran, pendaftaran, riwayat_status_pendaftaran |
| Kesiswaan | siswa, relasi wali, penempatan, kehadiran, kesehatan, pertumbuhan | siswa, hubungan_siswa_wali, riwayat_penempatan_siswa |
| Keuangan | tagihan, pembayaran, refund, kas/bank, transfer; **Tabungan sebagai submodul terpisah** (BR-TAB-001) | tagihan, pembayaran, mutasi_keuangan, mutasi_tabungan |
| Pegawai & Kehadiran | profil pegawai, jabatan, riwayat jabatan, absensi | pegawai, jabatan, riwayat_jabatan_pegawai, absensi_pegawai |
| Administrasi & Dokumen | surat, dokumen, kalender, agenda, pengumuman | surat, dokumen, kalender_akademik, pengumuman |
| Rapor | draf, data rapor, refleksi, persetujuan, penerbitan, snapshot dan PDF | rapor, butir_capaian, rapor_capaian, rapor_bagian, rapor_foto, rapor_refleksi, rapor_versi (bentuk akhir dirinci setelah PoC) |
| Laporan | keluaran agregat (absensi, tagihan, arus kas, tabungan, status PPDB) | hasil query; tidak menyalin data utama |
| Website/CMS | halaman publik, program sekolah, galeri dan konfigurasi publik | halaman, program_sekolah, galeri, media |
| Layanan lintas domain | email, notifikasi, audit log, penyimpanan | notifikasi, riwayat_pengiriman_email, audit_log, metadata berkas |

## 2. Prinsip batas domain

1. Domain memiliki tanggung jawab jelas, tetapi pada V2 berjalan dalam satu aplikasi dan satu codebase.
2. Hindari membuat microservices tanpa kebutuhan dan keputusan arsitektur baru.
3. Domain boleh menggunakan layanan domain lain lewat antarmuka layanan yang jelas, bukan menyebarkan business logic ke controller atau komponen UI.
4. Satu fakta bisnis mempunyai sumber kebenaran utama. CMS tidak membuat salinan profil siswa atau pegawai hanya untuk ditampilkan di website.
5. Laporan biasa adalah hasil query, bukan salinan data utama. Rapor yang telah diterbitkan berbeda: ia adalah snapshot historis.
6. Catatan transaksi keuangan dan tabungan yang sudah diposting tidak dihapus atau ditimpa; koreksi melalui pembalikan/penyesuaian yang tercatat.
7. Berkas fisik dibedakan dari entitas bisnis Dokumen dan dari Media publik CMS.

## 3. Entitas orang sebagai identitas pusat

`orang` menjadi data identitas dasar. Profil `siswa`, `profil_wali`, `pegawai`, dan `pengguna` ditautkan kepada orang yang sama jika relevan. Tidak semua orang harus mempunyai seluruh jenis profil atau akun.

- Satu orang maksimal memiliki satu akun pengguna, dan setiap akun wajib terkait satu orang (DEC-027).
- Wali yang mendaftar mandiri membuat orang baru; kemungkinan kecocokan dengan orang yang ada ditinjau dan ditautkan manual oleh staf. Data calon siswa tetap di pendaftaran sampai diterima, baru kemudian menjadi orang (DEC-029).
- Orang yang merupakan guru sekaligus wali murid tidak dibuat menjadi dua orang hanya karena memiliki dua peran di sekolah.
- Profil wali adalah profil/kemampuan bertindak sebagai wali, bukan duplikasi identitas orang.
- Akun tidak wajib dimiliki setiap pegawai.
- Relasi orang ke profil dan aturan pencocokan identitas harus mencegah duplikasi dan penggabungan orang secara tidak sah.

## 4. Hubungan lintas domain yang penting

- Pendaftaran (berisi data calon, tagihan biaya pendaftaran) → keputusan penerimaan → pembentukan orang calon, profil siswa dan hubungan wali (wali pengaju terverifikasi) → Riwayat Penempatan Siswa.
- Tahun Ajaran → Kelas; siswa masuk ke kelas melalui Riwayat Penempatan Siswa.
- Pegawai → Riwayat Jabatan Pegawai; Pegawai → Penugasan Akademik yang menghubungkan pegawai, kelas, tahun/periode, dan jenis penugasan.
- Absensi siswa dan absensi pegawai merupakan domain data terpisah.
- Profil kesehatan menyimpan kondisi dasar; pengukuran pertumbuhan menyimpan deret waktu pengukuran.
- Jenis Tagihan → Tagihan → Pembayaran → Alokasi Pembayaran. Mutasi keuangan baru dicatat mengikuti status penerimaan/pengesahan pembayaran.
- Setoran Tabungan → detail setoran per siswa → penerimaan/rekonsiliasi bendahara → mutasi tabungan resmi.
- Dokumen → versi dokumen; pengaitan dokumen ke siswa, pendaftaran, pegawai, pembayaran, atau surat menggunakan relasi eksplisit.
- Pengumuman → penerima → notifikasi portal dan pengiriman email; tidak ada balasan dari wali.
- CMS menampilkan data sekolah yang memang diizinkan publik; CMS bukan salinan master data.

## 5. Lapisan aplikasi

- Presentation: halaman React, komponen UI, formulir dan status tampilan.
- Delivery: route/controller atau endpoint yang menerima request, memvalidasi input, memanggil kasus penggunaan, dan membentuk respons.
- Application/domain: aturan bisnis, otorisasi, transaksi, state transition, dan orkestrasi antarentitas.
- Infrastructure: ORM/database, filesystem, email, queue, PDF, dan layanan eksternal.

Nama folder/namespace final dapat mengikuti konvensi Laravel yang teruji, tetapi tanggung jawab tersebut harus tetap terpisah.

## 6. Paket pembelajaran yang ditunda

Kurikulum, Komponen Kurikulum, Tujuan Pembelajaran, Perencanaan Pembelajaran, RPPM, RPPH, Pelaksanaan Pembelajaran, Asesmen, dan Perkembangan berbasis asesmen disimpan dalam roadmap. Jangan membentuk skema sementara yang berpura-pura menjadi model finalnya. Rapor V2 tetap dapat diterbitkan dengan isian manual yang ditetapkan pada spesifikasi rapor.
