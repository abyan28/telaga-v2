# Keputusan Terbuka TELAGA v2

Daftar ini mencegah rekomendasi sementara dianggap keputusan final. Perbarui kolom status ketika keputusan diambil. Prioritas: **T** = tinggi (menghambat tahap tercantum), **S** = sedang, **R** = rendah. Pemilik default adalah pemilik produk/sekolah kecuali disebut lain. Keputusan yang sudah diambil dicatat di `decision-log.md`.

| ID | Topik | Status | Prio | Tahap terhambat | Cara menutup keputusan |
|---|---|---|---|---|---|
| OD-01 | Vendor/paket shared hosting | Terbuka; syarat minimum konservatif ditetapkan (PHP 8.3, DEC-059) agar cocok di hampir semua shared hosting | T | 0A, 8 | Bandingkan versi PHP, MySQL, CLI/deploy, cron, worker, storage, PDF, backup dan biaya |
| OD-02 | Versi database produksi | Target konservatif diputuskan (DEC-059): MySQL/MariaDB versi lama yang didukung Laravel 13, tanpa CHECK/JSON khusus/window function; versi persis diverifikasi di Tahap 0A | T | 0A | Periksa versi paket hosting dan kompatibilitas migration |
| OD-03 | Strategi deployment tanpa root | Terbuka | S | 8 | Proof of concept deployment pada staging yang mirip hosting |
| OD-04 | Email produksi | **Diputuskan: Mailjet atau provider transactional sejenis (DEC-053)** | — | — | Verifikasi SPF/DKIM domain dan kuota saat Tahap 8 |
| OD-05 | Storage produksi | **Diputuskan: disk server non-publik, ikut backup (DEC-053)** | — | — | Uji kapasitas dan izin folder pada hosting terpilih |
| OD-06 | Queue processing | Rekomendasi database queue | T | 1 | Pastikan worker permanen atau cron; uji backlog dan retry |
| OD-07 | PDF driver | Terbuka | T | 6 | PoC LCPA dan keluaran surat |
| OD-08 | Package RBAC | Kandidat Spatie; nama tabel mengikuti DEC-028 | T | 1 | Verifikasi kompatibilitas Laravel 13, pemetaan nama tabel/kolom, Policy, scope data |
| OD-09 | MFA | **Diputuskan: tidak dibangun (DEC-041)** | — | — | Selesai; prosedur pemulihan Admin Utama ditulis (auth-rbac) |
| OD-10 | Field-level data dictionary | Sebagian: Tahap 1–2 diusulkan dan atribut pribadi murid/orang tua sudah diputuskan (DEC-082); Tahap 3+ menyusul | T | 1–8 | Konfirmasi atribut ⚠, lalu lengkapi per tahap sebelum migrasi |
| OD-11 | Pencocokan/deduplikasi orang | **Kebijakan diputuskan (DEC-027)**; atribut pencocokan rinci masih terbuka | T | 1 | Tetapkan atribut yang ditampilkan kepada staf peninjau (nama, tanggal lahir, telepon, dll.) |
| OD-12 | Detail skema rapor | Struktur dan skala BB/MB/BSH/BSB diputuskan (DEC-054); tersisa verifikasi teks butir capaian dan field final setelah PoC | S | 6 | Seed `butir_capaian` dari dokumen sumber; finalisasi tabel rapor setelah PoC |
| OD-13 | Ejaan judul bagian | **Diputuskan: “PRESENSI DAN DDTK”; DDTK = Deteksi Dini Tumbuh Kembang (DEC-054)** | — | — | Selesai |
| OD-14 | Retensi data pribadi/dokumen | **Diputuskan: tidak ada data yang dihapus, semua menjadi arsip (DEC-071)**; risiko terhadap ketentuan perlindungan data pribadi ditinjau sebelum produksi | — | — | Selesai |
| OD-15 | RPO/RTO | **Sebagian: mingguan, retensi 8 minggu, RTO 1 hari kerja (DEC-047)**; risiko RPO ±7 hari | S | 8 | Tinjau ulang sebelum produksi; tentukan lokasi salinan dan penanggung jawab |
| OD-16 | Bukti verifikasi wali tambahan | **Diputuskan: unggahan KK atau akta kelahiran anak, diverifikasi admin (DEC-063)**; alur sengketa/koreksi belum dirinci | R | 2–3 | Tentukan alur koreksi bila data wali salah atau disengketakan |
| OD-17 | Format penomoran | **Diputuskan: surat, NIS, dan nomor pendaftaran (DEC-050, DEC-051, DEC-058)** | — | — | Selesai |
| OD-18 | Skema session/cache | Terbuka untuk hosting | S | 1 | Gunakan default yang didukung; uji batas dan invalidasi sesi |
| OD-19 | Refund: kapan berlaku | **Diputuskan: saat wali mengajukan pengunduran diri setelah membayar daftar ulang (DEC-049)** | — | — | Penyetuju dan pencatat: lihat OD-32 |
| OD-20 | Cut-off periode rapor | **Diputuskan: `tanggal_cutoff_rapor`, default tanggal selesai, dapat diubah admin (DEC-052)** | — | — | Selesai |
| OD-21 | Alur pencatatan absensi pegawai | **Diputuskan: mandiri via ponsel, jam masuk/pulang, wajib di area sekolah GPS (DEC-064)** | — | — | Detail radius dan koreksi: OD-35 |
| OD-22 | Format keluaran surat | **Diputuskan: PDF dan Word/.docx (DEC-062)** | — | — | Selesai |
| OD-23 | Prasyarat pembayaran biaya pendaftaran | **Diputuskan: harus lunas dan terverifikasi sebelum diperiksa/diputuskan (DEC-049)** | — | — | Selesai |
| OD-24 | Cara alokasi pembayaran ke tagihan | **Diputuskan: usulan otomatis ke tagihan terlama, dapat diubah petugas sebelum posting (DEC-061)** | — | — | Selesai |
| OD-25 | Refleksi Orang Tua | **Diputuskan: opsional (tulis tangan atau portal), tanpa tenggat, rapor boleh terbit tanpanya (DEC-054)** | — | — | Selesai |
| OD-26 | Nominal SPP bawaan dan jumlah siswa | **SPP Rp250.000 semua kelompok diputuskan (DEC-060)**; jumlah ±300 siswa masih perkiraan | R | 8 | Konfirmasi jumlah siswa untuk uji beban |
| OD-27 | Default role bawaan | **Diputuskan: semua default role disetujui (DEC-068, DEC-070, DEC-075)** | — | — | Selesai |
| OD-28 | Nilai awal parameter keamanan | **Diputuskan (DEC-067)** | — | — | Selesai; dapat dikonfigurasi |
| OD-29 | Status siswa dan tahun ajaran aktif tunggal | **Diputuskan (DEC-066)** | — | — | Selesai |
| OD-30 | Tanda tangan Orang Tua/Wali pada rapor | **Diputuskan: kolom dibiarkan kosong untuk tanda tangan di cetakan (DEC-058)** | — | — | Selesai |
| OD-31 | Standar acuan pertumbuhan | **Diputuskan: Permenkes No. 2 Tahun 2020 sebagai rujukan; keterangan diisi manual lewat dropdown (DEC-057)** | — | — | Opsi dropdown: lihat OD-33 |
| OD-32 | Penyetuju refund daftar ulang | **Diputuskan: Admin Sekolah atau Bendahara (DEC-056)** | — | — | Selesai; aturan pengaju ≠ penyetuju masih usulan |
| OD-33 | Opsi dropdown keterangan pengukuran DDTK | **Diputuskan: sesuai usulan (DEC-069)** | — | — | Selesai |
| OD-34 | Penghapusan data pribadi | **Diputuskan: tidak ada data yang dihapus (DEC-071)** | — | — | Selesai |
| OD-35 | Absensi pegawai GPS: rincian | **Diputuskan: radius awal 10 m, koordinat disimpan, jam masuk 07.00 WIB, Senin–Jumat, terlambat tanpa toleransi, koreksi admin dengan alasan wajib (DEC-072, DEC-073, DEC-074)** | — | — | Selesai; uji akurasi GPS di lapangan tercantum di acceptance checklist |
| OD-36 | Pustaka pembaca .xlsx yang aman dan ringan untuk shared hosting (ekstensi PHP yang dibutuhkan, batas memori) | Terbuka; keputusan lewat spike di Task 2.31 pada hosting terpilih; perlu perkiraan jumlah baris sekali impor | S | 2 | Spike: uji impor berkas contoh di hosting target |
| OD-37 | Rincian wizard Tahun ajaran berikutnya | **Diputuskan (DEC-097)**: tanggal bawaan, salin kelas opsional, tanpa kenaikan kelas otomatis, peringatan tidak memblokir | — | — | Selesai |
| OD-38 | Sumber dan lisensi data wilayah resmi (±99 ribu baris) dan daftar bank; cara impor sekali pada hosting tanpa melebihi batas | Terbuka; data dari repo sistem lama dipakai sebagai kandidat sumber | S | 1 | Verifikasi sumber/lisensi data, kelengkapan, dan impor per batch |
| OD-39 | Alat penempatan massal murid ke kelas tahun ajaran baru dan aturan kenaikan kelas (mis. kelompok A ke B ke lulus) | Terbuka (di luar wizard V2 awal) | R | 3 | Tentukan aturan kenaikan RA dan bentuk alat penempatan |

## Cara memperbarui

Setiap baris yang ditutup dipindahkan ke `decision-log.md` dengan ID, tanggal, alasan, alternatif, dampak ke dokumen, dan status. Jangan menghapus keputusan lama yang sudah digantikan; catat keputusan pengganti. Tahap tidak boleh dimulai bila ada baris berprioritas T yang menghambat tahap itu dan masih terbuka, kecuali ada penerimaan risiko tertulis.
