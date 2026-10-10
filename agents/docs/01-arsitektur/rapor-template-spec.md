# Spesifikasi Template Rapor LCPA TELAGA v2

## 1. Sumber

Acuan adalah file `LAPORAN CAPAIAN PEMBELAJARAN ANAK.docx` di folder `referensi/` (versi PDF-nya tidak dipakai). Dokumentasi sebelumnya menyebut nama `...ANAK(2).docx`; nama file sebenarnya tanpa “(2)”. Implementasi harus mereplikasi urutan dan tujuan bagian pada file tersebut. Bila teks sumber tampak tidak konsisten, catat keputusan sebelum mengubahnya (contoh yang sudah diputuskan: judul “PRESENSI DAN DDTK”, DEC-054).

Isi spesifikasi di bawah diturunkan langsung dari template; butir yang bukan dari template diberi tanda “(keputusan)” atau “(usulan)”.

## 2. Bagian template

### Kepala dokumen
Judul “LAPORAN CAPAIAN PEMBELAJARAN ANAK (LCPA)”, lalu: Nama Siswa; Tahun Pelajaran (mis. 2024/2025); Semester (mis. Genap); Kelompok/kelas (mis. A); Fase (pada template: FONDASI). Nilai diambil dari siswa, tahun ajaran, periode pembelajaran, dan kelas (`kelas.fase`).

### Nilai Agama dan Budi Pekerti
- Teks capaian pembelajaran umum (satu kalimat pengantar).
- Tabel 4 butir capaian bernomor dengan kolom TINGKAT KETERCAPAIAN.
- Narasi “Deskripsi Capaian Perkembangan”.
- Area “Pose Kegiatan Agama dan Budi Pekerti” (foto).

### Jati Diri
- Teks pengantar, tabel 4 butir capaian bernomor dengan kolom tingkat.
- Narasi “Deskripsi Capaian Perkembangan”.
- Area “Pose Kegiatan Jati Diri” (foto).

### Pembelajaran Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, dan Seni
- Teks pengantar, tabel 7 butir capaian bernomor dengan kolom tingkat.
- Narasi “Deskripsi Capaian Perkembangan”.
- Area “Pose Kegiatan” (foto).

Teks butir capaian berasal dari template dan disimpan sebagai master `butir_capaian`; salin teks apa adanya saat seed dan verifikasi terhadap dokumen sumber.

### Deskripsi Panca Cinta
Lima aspek, masing-masing dengan deskripsi dan area foto kegiatan:
1. Cinta Allah SWT dan RasulNya.
2. Cinta Ilmu.
3. Cinta Lingkungan.
4. Cinta diri dan sesama.
5. Cinta Tanah air.

### Refleksi Guru dan Refleksi Orang Tua
- Refleksi Guru: kotak teks; template berisi kalimat bawaan sekolah yang dapat dipakai sebagai nilai awal dan diubah wali kelas.
- Refleksi Orang Tua: pada template berupa garis titik untuk tulisan tangan. Di sistem: boleh ditulis tangan pada cetakan atau diisi wali terverifikasi di portal sebagai opsi (DEC-054). Isian portal adalah isian formulir, bukan balasan, terkait siswa dan periode yang benar, tanpa tenggat. Rapor boleh terbit tanpa isian portal; bila ada, isian ikut snapshot.

### PRESENSI DAN DDTK
Judul pada sumber tertulis “PRESENTSI DAN DDTK”; di sistem ditulis “PRESENSI DAN DDTK” (DEC-054). DDTK = Deteksi Dini Tumbuh Kembang. Dua tabel:
- **Kehadiran:** Sakit, Izin, Tanpa Keterangan, berupa jumlah hari.
- **Perkembangan / Pengukuran:** Tinggi Badan menurut Umur (… cm), Berat Badan menurut Umur (… kg), Lingkar Kepala (… cm), masing-masing dengan keterangan (pada template: “normal”).

Aturan data:
- Kehadiran dihitung dari absensi harian (`hadir`, `sakit`, `izin`, `tanpa_keterangan`) sejak tanggal mulai periode sampai `tanggal_cutoff_rapor` (default tanggal selesai periode, dapat diubah admin; DEC-052). `Sakit`, `Izin`, `Tanpa Keterangan` memetakan satu-ke-satu ke status absensi.
- Ukuran pertumbuhan diambil yang terakhir diukur pada atau sebelum cut-off, bukan pengukuran sembarang.
- Rujukan penilaian pertumbuhan: Permenkes No. 2 Tahun 2020 tentang Standar Antropometri Anak (DEC-057). Keterangan diisi manual wali kelas melalui **dropdown**; sistem tidak menghitung Z-score atau memberi label otomatis pada V2 awal.
- Opsi dropdown (disetujui pemilik, DEC-069; istilah mengikuti kategori Permenkes untuk indeks yang relevan, tanpa Z-score yang dikarang):
  - Tinggi Badan menurut Umur: Sangat pendek, Pendek, Normal, Tinggi, Tidak diukur.
  - Berat Badan menurut Umur: Berat badan sangat kurang, Berat badan kurang, Berat badan normal, Risiko berat badan lebih, Tidak diukur.
  - Lingkar Kepala: Normal, Perlu perhatian, Tidak diukur. Lingkar kepala tidak dipastikan tercakup pada Permenkes tersebut, sehingga opsinya hanya pilihan manual sekolah.
- Daftar opsi disimpan sebagai referensi terkontrol (BR-AKD-005) agar dapat disesuaikan sekolah tanpa mengubah kode.
- Jika data absensi atau pengukuran tidak lengkap, sistem tidak boleh mengarang; tampilkan peringatan atau tandai data belum lengkap.

### Ekstra Kurikuler
Tabel nama ekstrakurikuler dan capaian perkembangan; diisi manual.

### Pengesahan
Tiga kolom tanda tangan:
- Orang Tua/Wali Siswa.
- Guru kelas/Wali kelas (nama dan NIP; “-” bila kosong).
- Mengetahui Kepala RA (nama dan NIP; “-” bila kosong).

Pengesahan memakai gambar tanda tangan pada PDF (DEC-035) untuk guru kelas/wali kelas dan Kepala RA. Gambar disimpan privat (`tanda_tangan`) dan hanya diganti oleh pegawai bersangkutan atau admin berwenang. Kolom Orang Tua/Wali Siswa dibiarkan kosong untuk ditandatangani pada cetakan (DEC-058). Snapshot menyimpan nama, jabatan, NIP, dan referensi gambar yang dipakai saat terbit. Ini bukan tanda tangan elektronik tersertifikasi dan tidak boleh disajikan demikian.

## 3. Skala Tingkat Ketercapaian

Setiap butir capaian memiliki satu tingkat: **BB** (Belum Berkembang), **MB** (Mulai Berkembang), **BSH** (Berkembang Sesuai Harapan), **BSB** (Berkembang Sangat Baik) (DEC-054, BR-RAP-017). Template tidak menuliskan skala di kolomnya; skala ini diajukan sebagai contoh umum PAUD dan diterima pemilik. Bukan skor numerik.

## 4. Model data dan snapshot

- Rapor berkaitan dengan satu siswa dan satu periode (unik per siswa dan periode).
- Alur: wali kelas menyusun → (opsional) wali mengisi refleksi → wali kelas mengajukan → Kepala RA menyetujui → terbit (DEC-032).
- Draf boleh diedit dengan audit bila perlu.
- Penerbitan menyimpan snapshot seluruh konten yang benar-benar tampil (tabel `rapor_*`, teks butir, kepala dokumen, kehadiran, pengukuran, refleksi, penanda tangan dan NIP, referensi gambar tanda tangan) beserta referensi PDF final.
- Snapshot terbit immutable. Perubahan menghasilkan versi baru yang melewati persetujuan lagi.
- Setiap versi menyimpan penerbit, waktu, nomor versi, status dan alasan revisi jika relevan.
- Rapor dan PDF hanya terlihat oleh role yang berwenang dan wali yang terhubung sah (versi terbit saja).

## 5. UI/UX rapor

- Gunakan alur draf → pemeriksaan kelengkapan → pengajuan → persetujuan → pratinjau → terbitkan.
- Sediakan pratinjau halaman cetak sebelum penerbitan.
- Jelaskan bagian mana yang otomatis dari TELAGA (kepala dokumen, kehadiran, pengukuran) dan mana yang diinput manual.
- Tampilkan peringatan jika data kehadiran/pertumbuhan belum tersedia atau rentang tanggalnya tidak sesuai cut-off.
- Jangan memberi skor numerik otomatis untuk Tingkat Ketercapaian.
- Formulir unggah foto pose kegiatan per bagian menjelaskan format dan ukuran; foto tersimpan privat.

## 6. Kriteria fidelity PDF

PDF uji dibandingkan dengan dokumen sumber dari sisi urutan bagian, jumlah/pemisahan halaman yang wajar, ukuran kertas, margin, font, tabel capaian bernomor, area foto, refleksi dan area pengesahan. Exact pixel-perfect rendering bukan syarat kecuali diputuskan; struktur dan hasil cetak harus dapat diterima oleh pemilik sekolah.
