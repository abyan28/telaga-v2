# Rapor, Snapshot, dan PDF

## 1. Acuan rapor

Gunakan dokumen yang diberikan sebagai sumber format: `referensi/LAPORAN CAPAIAN PEMBELAJARAN ANAK.docx` (rincian di `01-arsitektur/rapor-template-spec.md`). Jangan mengganti istilah atau mengoreksi teks sumber secara diam-diam ketika tujuan pekerjaan adalah mereplikasi format. Ketidakkonsistenan/kemungkinan typo dicatat sebagai keputusan (contoh: “PRESENTSI” menjadi “PRESENSI”, DEC-054).

Struktur konten yang dikenali pada template:

1. Identitas laporan dan siswa.
2. Nilai Agama dan Budi Pekerti: capaian, tingkat ketercapaian, deskripsi.
3. Jati Diri: capaian, tingkat ketercapaian, deskripsi.
4. Pembelajaran Dasar-Dasar Literasi, Matematika, Sains, Teknologi, Rekayasa dan Seni: capaian, tingkat ketercapaian, deskripsi.
5. Deskripsi Panca Cinta: Cinta Allah SWT dan RasulNya; Cinta Ilmu; Cinta Lingkungan; Cinta Diri dan Sesama; Cinta Tanah Air.
6. Refleksi Guru.
7. Refleksi Orang Tua.
8. PRESENSI DAN DDTK (Deteksi Dini Tumbuh Kembang).
9. Kehadiran: Sakit, Izin, Tanpa Keterangan.
10. Perkembangan: tinggi badan/menurut umur, berat badan/menurut umur, lingkar kepala.
11. Ekstra Kurikuler: nama ekstrakurikuler dan capaian perkembangan.
12. Pengesahan: orang tua/wali, guru kelas/wali kelas (nama, NIP), kepala RA (nama, NIP).

Setiap bagian capaian memiliki area foto “Pose Kegiatan”; kepala dokumen memuat tahun pelajaran, semester, kelompok/kelas, dan fase. Teks sumber memuat variasi ejaan/capitalization. Pertahankan untuk replica awal atau catat perbaikan sebagai keputusan terpisah dari implementasi.

## 2. Batas fitur awal

- Paket kurikulum dan asesmen ditunda.
- Wali kelas memasukkan tingkat ketercapaian dan narasi manual sesuai template; wali boleh mengisi Refleksi Orang Tua di portal (opsional) atau menulis tangan pada cetakan; Kepala RA menyetujui sebelum terbit (DEC-032, DEC-054).
- Jangan mengubah tingkat ketercapaian menjadi angka jika sumber tidak mengharuskannya.
- Data identitas, kelas/tahun/periode, absensi dan pertumbuhan dapat ditarik dari sumber TELAGA yang sudah ada.
- Bagian yang belum mempunyai sumber data dapat diisi manual dengan validasi dan audit yang sesuai.

## 3. Snapshot terbit

- Draf dapat diedit oleh role yang diizinkan.
- Pemeriksaan kelengkapan dilakukan sebelum penerbitan.
- Penerbitan membuat snapshot konten yang dipakai, data penting yang ditampilkan, metadata penerbitan, dan PDF.
- Perubahan selanjutnya pada nama, kelas, absensi, atau ukuran tubuh tidak boleh mengubah snapshot lama.
- Revisi menghasilkan versi baru yang merujuk versi terdahulu dan menyimpan alasan, pelaku, waktu dan file PDF baru.
- PDF hanya dapat diunduh oleh pengguna yang berwenang.

## 4. Proof of concept PDF

Cakupan PoC mencakup LCPA (termasuk gambar tanda tangan) dan keluaran surat dalam PDF serta Word/.docx (DEC-062); rapor hanya PDF. Pembuatan .docx diuji pada hosting target (pustaka pembuat .docx harus berjalan tanpa dependensi sistem yang tidak tersedia). Sebelum memilih driver, lakukan uji pada lingkungan pengembangan dan staging yang mendekati hosting. Bandingkan setidaknya opsi renderer HTML/CSS yang feasible. Kriteria:

- ukuran kertas, margin dan orientasi;
- pemenggalan halaman, tidak ada halaman kosong tidak sengaja;
- font, karakter Bahasa Indonesia, gambar;
- tabel, refleksi, tanda tangan dan footer;
- konsistensi halaman dari browser/server;
- penggunaan memori dan durasi;
- dependensi eksternal dan kecocokan shared hosting;
- penyimpanan PDF privat dan proses retry aman.

Jangan memfinalkan PDF engine sebelum uji ini lulus.

## 5. Template dan data

Template rapor harus memisahkan data siswa/periode dari konten manual. Struktur penyimpanan akhir dapat memakai tabel bagian/detail atau payload snapshot terstruktur, tetapi semua versi terbit harus dapat direproduksi. Jangan menyimpan snapshot sebagai JSON yang tidak tervalidasi atau sebagai satu-satunya data untuk seluruh catatan rapor yang masih dapat diedit.
