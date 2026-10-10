# Arahan UI/UX TELAGA v2

## 1. Karakter produk

TELAGA adalah aplikasi manajemen PAUD (RA Al Kautsar). UI mengusung pendekatan **Playful Minimalist**: profesional dan rapi bagi pengelola sekolah, namun tetap hangat, ceria, dan ramah bagi anak usia 4–6 tahun serta wali murid (DEC-098). Prioritasnya adalah keterbacaan, kejelasan status, efisiensi kerja harian, serta minimnya kesalahan saat mengubah data atau memproses transaksi.

## 1A. Landing page dan dashboard admin dirancang baru (DEC-095, DEC-098)

Sistem lama hanya referensi fungsi. Landing page, dashboard admin, dan portal wali murid TIDAK menyalin tata letak lama; dirancang baru: elegan, simpel, modern, dan ringan di ponsel. Tiga lapis antarmuka (*surfaces*) dibangun terpadu:
1. **Public Web / Landing Page:** Citra sekolah ramah anak, informasi program sentra, alur PPDB 3 langkah, galeri, dan FAQ.
2. **Admin Dashboard (Internal):** Berbasis arsitektur *shadcn-admin* yang efisien, fungsional, dan padat data tanpa ornamen berlebih.
3. **Portal Wali Murid:** Tampilan mobile-first yang intuitif, berfokus murni pada data anak yang terhubung sah.

## 2. Arah visual (Playful Minimalist & Tema Ganda — DEC-098, DEC-099)

- **Fokus Light Mode yang Hangat:** Tema utama terang dengan latar *warm milk / off-white* (`#FAF9F6`), bukan putih tajam menyilaukan.
- **Kesiapan Struktural Dark Mode (DEC-099):** Dark mode TIDAK diabaikan. Seluruh warna diikat ke variabel semantik Tailwind (`bg-background`, `text-foreground`, `bg-card`, dll.) dengan mode `class="dark"`. Bila mode gelap diaktifkan, kontras teks dan warna permukaan tetap memenuhi standar aksesibilitas WCAG 2.1 AA.
- **Palet Warna & Rebranding Logo (DEC-099):** Palet warna saat ini menggunakan warna dasar biru langit lembut (*Soft Sky Blue*, `sky-600` / `#0284C7`) dipadu aksen pastel ceria (*Sunshine Yellow* `#FEF08A`, *Mint Green* `#D1FAE5`, *Soft Peach* `#FFEDD5`). Palet ini merupakan **baseline awal** yang akan dievaluasi dan diselaraskan kembali saat logo resmi RA Al Kautsar selesai proses *rebranding*.
- **Geometri Squircle Lembut:** Menggunakan sudut melengkung ramah anak (`rounded-2xl`, `rounded-3xl`, dan `rounded-full` untuk tombol pil), menghindari sudut lancip yang kaku.
- Ruang putih cukup, hierarki tipografi jelas, jarak antar elemen konsisten.
- Status memakai label dengan teks dan ikon/warna; jangan bergantung pada warna saja.
- Hindari dashboard yang terlalu ramai, gradient berlebihan, efek kaca, animasi dekoratif berat, dan terlalu banyak kartu tanpa informasi bernilai.
- Foto/ilustrasi anak hanya dipakai jika tersedia secara sah dan memang memperkuat desain; jangan membuat data/foto anak fiktif yang tampak nyata.

## 3. Bahasa dan terminologi

Bahasa UI utama adalah Bahasa Indonesia. Pertahankan istilah yang telah disepakati:
- Diksi resmi subjek didik adalah **Murid** atau **Siswa** (DEC-099). Diksi “Santri” **tidak digunakan**.
- `Riwayat Penempatan Siswa`, bukan “enrollment”.
- `Pelaksanaan Pembelajaran` untuk fitur roadmap yang sebelumnya disebut “Jurnal Guru”.
- `Rapor LCPA`, `Setoran Tabungan`, `Verifikasi Pembayaran`, `Pengumuman`.
- Label peran: `Wali Murid`, `Guru Kelas`, `Guru Pendamping`, `Bendahara`, `Kepala RA`, `Admin Sekolah`, `Admin Utama`.

Gunakan istilah yang sama pada menu, breadcrumb, judul, tombol, validasi, notifikasi, dokumen, dan test. Istilah teknis berbahasa Inggris boleh muncul di dokumentasi developer bila memang standar, tidak perlu dipaksakan ke label pengguna.

## 4. Dashboard berdasarkan konteks

- Admin: ringkasan pendaftaran, siswa, agenda dan tugas yang memerlukan tindakan, sesuai kewenangan.
- Bendahara: pembayaran menunggu verifikasi, tagihan, setoran tabungan menunggu diterima, ringkasan arus kas.
- Guru: kelas yang ditugaskan, kehadiran; wali kelas juga setoran tabungan dan rapor yang perlu disusun; guru pendamping tanpa setoran dan rapor.
- Kepala RA: ringkasan dan laporan yang relevan, rapor menunggu persetujuan, tanpa harus menampilkan tindakan transaksi.
- Wali: status pendaftaran, tagihan, saldo tabungan anak, notifikasi, pengumuman, rapor terbit, dan Refleksi Orang Tua yang perlu diisi.

Angka pada dashboard harus mempunyai definisi yang terdokumentasi dan sumber data yang bisa ditelusuri; definisinya ada di `05-perencanaan/report-catalog.md`.

## 4A. Alur cepat mobile khusus guru di kelas (DEC-099)

Guru PAUD mencatat kehadiran dan setoran tabungan harian menggunakan ponsel langsung di kelas. Antarmuka ponsel untuk peran guru dioptimalkan dalam format **Quick-Action Mobile Card**:
- Tampilan berbasis kartu per murid (*thumb-friendly*), memuat foto anak dan nama panggilan.
- Tombol kehadiran berukuran besar (`[Hadir]`, `[Sakit]`, `[Izin]`, `[Alpa]`) yang mudah ditekan dengan satu jempol tanpa risiko salah sentuh.
- Pencatatan tabungan harian menyediakan tombol nominal cepat (`[+5.000]`, `[+10.000]`, `[+20.000]`, `[Nominal Lain]`) untuk meminimalkan waktu input saat murid menyetor di pagi hari.

## 5. Formulir

- Kelompokkan field berdasarkan topik dan urutan kerja nyata.
- Label selalu terlihat; placeholder bukan pengganti label.
- Jelaskan field opsional/wajib dengan pola yang konsisten.
- Tampilkan kesalahan dekat field dan ringkasan kesalahan pada form panjang.
- Setelah validasi gagal, pertahankan nilai input yang aman.
- Untuk operasi uang, tampilkan nominal dan konsekuensi sebelum tindakan final.
- Aksi destruktif atau sulit dibalik memerlukan konfirmasi yang menyebut objek/tindakan secara jelas.
- Form upload menjelaskan format, ukuran maksimum dan tujuan penggunaan berkas.

## 6. Tabel dan daftar

- Hindari menampilkan semua kolom di layar kecil; prioritaskan identitas, status dan tindakan utama.
- Gunakan server-side pagination untuk daftar berukuran besar.
- Filter mempunyai label jelas dan cara menghapus filter.
- Tampilkan empty state yang memberi langkah berikutnya, bukan hanya “Tidak ada data”.
- Bulk action hanya tersedia untuk record dan tindakan yang aman, dengan permission serta ringkasan jumlah target.

## 7. Desain interaksi

Semua tindakan penting harus memberikan feedback: loading, sukses, gagal, atau perlu tindakan lanjutan. Hindari tombol yang tampak aktif tetapi tidak melakukan apa-apa. Jangan menyatakan berhasil sebelum backend mengonfirmasi. Form transaksi mencegah submit ganda dan menjelaskan status akhir.

## 7A. Dialog konfirmasi bertahap (Double Confirmation Dialog — DEC-099)

Aksi kritis yang bersifat permanen (*immutable ledger*) atau memengaruhi status resmi wajib dilindungi dialog konfirmasi bertahap:
- **Transaksi Finansial:** Posting setoran tabungan, penarikan tabungan, verifikasi pembayaran SPP, dan approval refund wajib menampilkan nominal, nama murid/wali sasaran, serta dampak pembukuan sebelum tombol konfirmasi aktif.
- **Publikasi Rapor:** Penerbitan rapor LCPA wajib menampilkan pratinjau ringkas versi snapshot yang akan dikunci dan peringatan bahwa perubahan berikutnya harus melalui revisi versi baru.
- Dialog memanfaatkan komponen animasi mikro (*Motion Primitives*) untuk transisi fokus yang jelas dan mencegah klik ganda (*double submit*).

## 8. Responsif

Desktop mendukung pekerjaan operasional dengan tabel dan panel detail. Pada tablet, kurangi kepadatan. Pada ponsel, fokus pada alur harian, kartu/ringkasan dan form yang mudah disentuh. Hindari horizontal overflow sebagai solusi default untuk seluruh halaman.

## 9. Etika dan privasi

- Jangan menampilkan data anak di halaman publik tanpa dasar yang sesuai.
- Nama, foto, kesehatan, rapor dan dokumen siswa tampil hanya untuk pengguna berwenang.
- Jangan gunakan notifikasi/toast yang berisi nomor identitas lengkap atau data sensitif.
- Sediakan konfirmasi tambahan untuk ekspor atau perubahan berisiko.

## 10. Pustaka referensi UI & generator animasi terpilih (DEC-098)

Seluruh agen AI dan pengembang UI/UX wajib mematuhi panduan sumber referensi desain berikut untuk menjaga konsistensi, keindahan visual, dan performa tinggi (bebas lag):

1. **Feral UI Gradients (`https://feralui.dev/gradients`) — Pilihan Utama Latar Gradasi**
   - **Peran:** Menghasilkan palet gradasi *mesh* dan *radial* modern berbasis CSS/SVG murni.
   - **Panduan Teknis:** Digunakan pada area Hero landing page dan latar belakang panel login/portal. Tanpa JavaScript runtime (0 KB JS overhead), aman dari beban render.
2. **Patterncraft (`https://patterncraft.store/`) — Pilihan Utama Pola Latar Belakang PAUD**
   - **Peran:** Pola latar belakang SVG ringan (*polka-dots*, gelombang *soft waves*, *grid doodles*).
   - **Panduan Teknis:** Digunakan sebagai pemanis latar belakang kartu program belajar, pembatas seksi, dan *empty state*. Sangat ringan (1–2 KB SVG data-URI), memberi kesan ramah anak tanpa memberatkan memori.
3. **Motion Primitives (`https://motion-primitives.com/`) — Standar Animasi Mikro Interaktif**
   - **Peran:** Komponen animasi mikro berbasis **Framer Motion + Tailwind CSS** (selaras dengan *shadcn/ui*).
   - **Panduan Teknis:** Digunakan untuk *text reveal* hero, accordion FAQ yang mulus, tab switching, dan dialog konfirmasi transaksi. Otomatis menghormati setelan `prefers-reduced-motion` pengguna (aksesibilitas).
4. **Uiverse.io (`https://uiverse.io/`) — Elemen Mikro Taktil & Empuk**
   - **Peran:** Inspirasi dan cuplikan komponen CSS/Tailwind murni untuk tombol empuk (*bouncy buttons*), sakelar toggle ceria, dan *loading spinner* animasi ramah anak.
   - **Panduan Teknis:** Lakukan seleksi ketat (*cherry-pick*); gunakan hanya yang bernuansa pastel, rounded, dan ramah anak. Hindari gaya gelap/cyberpunk.
5. **Transitions.dev (`https://transitions.dev/`) — Transisi Navigasi Halaman**
   - **Peran:** Pola transisi antar-halaman berbasis *CSS View Transitions API* asli browser.
   - **Panduan Teknis:** Digunakan secara halus pada perpindahan route Inertia.js (durasi singkat < 250ms agar pengguna operasional tidak merasa terhambat animasi).
6. **Beautiful UI (`https://www.beautifului.dev/`) — Inspirasi Kartu & Showcase Modern**
   - **Peran:** Inspirasi komponen kartu, testimoni, dan badge berbasis Tailwind.
   - **Panduan Teknis:** Terapkan dengan mengadopsi styling utility Tailwind-nya tanpa menginstal dependensi monolitik tambahan.
7. **Neat (`https://neat.rebase.pro/`) — Generator Fluid Gradient (HANYA UNTUK EKSPOR ASET)**
   - **Peran:** Generator visual gradasi cairan dinamis.
   - **⚠️ Peringatan Kinerja:** **DILARANG** menjalankan script WebGL/Canvas Neat secara live di aplikasi web karena menguras GPU dan membuat baterai/ponsel panas.
   - **Panduan Teknis:** Jalankan generator di browser desainer, atur perpaduan warna *sky-blue* dan pastel, lalu **ekspor hasilnya menjadi format WebP/SVG statis atau klip video WebM ringan (~200KB)** untuk dipakai sebagai background looping statis.
