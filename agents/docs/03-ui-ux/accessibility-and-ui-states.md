# Aksesibilitas dan UI States

## 1. Prinsip aksesibilitas

Target: WCAG 2.1 tingkat AA (DEC-042). Browser target: browser modern dua versi terakhir pada desktop dan ponsel.

- Semua aksi penting dapat dipakai dengan keyboard.
- Focus indicator terlihat dan tidak tertutup overlay.
- Input memiliki label yang jelas; petunjuk dan error terhubung secara semantik.
- Jangan menyampaikan status hanya dengan warna atau ikon tanpa teks yang cukup.
- Gunakan heading berurutan, landmark dan teks tombol yang menjelaskan tindakannya.
- Dialog mengelola fokus dan mengembalikannya setelah ditutup.
- Gerakan tidak wajib dan menghormati preferensi reduced motion.
- Bahasa kesalahan sederhana, sopan dan memberitahu cara memperbaiki.

## 2. State wajib per halaman

- Loading: indikator yang proporsional; cegah klik ganda.
- Success: konfirmasi tindakan yang benar-benar berhasil.
- Empty: jelaskan belum ada data dan langkah berikutnya bila sesuai.
- Validation error: ringkasan dan pesan dekat field terkait.
- Permission denied: beri penjelasan umum, tanpa membocorkan data yang tidak boleh dilihat.
- Not found: jangan membedakan secara berlebihan antara record tidak ada dan tidak boleh diakses bila itu berisiko mengungkap data.
- Server/network error: pesan yang dapat ditindaklanjuti tanpa stack trace.
- Stale/conflict: bila data berubah oleh pengguna lain, minta refresh/konfirmasi; jangan menimpa perubahan secara diam-diam.

## 3. State transaksi sensitif

Untuk pembayaran, transfer, penarikan/setoran tabungan, publikasi rapor dan pengubahan role:
- Tampilkan objek dan nominal yang sedang ditindaklanjuti.
- Tampilkan ringkasan sebelum konfirmasi bila ada risiko salah sasaran.
- Tombol menunjukkan status proses dan nonaktif selama request berlangsung.
- Feedback sukses hanya muncul setelah backend mengonfirmasi hasil.
- Pada timeout, jangan langsung menyuruh mengulangi transaksi tanpa memeriksa status operasi.

## 4. Privasi pada UI

- Masking data sensitif pada ringkasan bila detail penuh tidak diperlukan.
- Jangan menaruh detail anak/nomor identitas ke toast, URL query string atau pesan error.
- Jangan menampilkan komponen data untuk anak yang bukan bagian dari scope pengguna.
- Download/export harus melalui endpoint yang melakukan otorisasi.
