# Email, Notifikasi, dan Queue

## 1. Kanal

- Notifikasi dalam portal adalah kanal utama di dalam TELAGA.
- Email adalah kanal tambahan untuk OTP, pemulihan akun, pemberitahuan status, pembayaran, dan pengumuman.
- Pengumuman kepada wali bersifat satu arah; tidak ada reply/chat/comment pada V2 awal.

## 2. Provider email

- Development: Mailjet.
- Produksi: Mailjet atau provider transactional email sejenis (DEC-053). SMTP bawaan hosting tidak dipilih karena risiko deliverability; tetap dapat dipakai sebagai cadangan bila provider tidak dapat dijangkau dari hosting.
- Gunakan konfigurasi environment/transport Laravel agar provider dapat ditukar tanpa mengubah business logic.
- Verifikasi SPF, DKIM dan DMARC untuk domain pengirim sebelum produksi.
- Periksa batas harian/bulanan, kebijakan transactional email, deliverability, rate limit, event/bounce, dan biaya.

## 3. OTP

OTP 6 digit, TTL awal 10 menit, maksimal 5 percobaan per kode, jeda resend 60 detik. Pengiriman OTP mempunyai prioritas lebih tinggi daripada email massal. Jangan memasukkan kode ke payload log atau notifikasi database.

## 4. Queue

- Database queue merupakan opsi awal; Redis bukan dependensi wajib V2.
- Semua job email non-kritis, pengumuman massal, dan pembuatan PDF dievaluasi untuk queue.
- Konfirmasi pembayaran/tabungan harus terjadi dalam alur transaksi utama; email pemberitahuan dijalankan setelah transaksi berhasil.
- Job harus idempotent atau menyimpan mekanisme untuk mencegah efek ganda saat retry.
- Retry harus berbatas, menggunakan backoff yang sesuai, dan error akhir dapat diperiksa.
- Jangan memasukkan OTP plaintext, token rahasia, dokumen lengkap, atau seluruh data anak ke payload job jika cukup menyimpan ID dan memuat ulang data secara berotorisasi.

## 5. Shared hosting

Sebelum produksi, pastikan hosting mengizinkan worker permanen atau tentukan strategi cron yang didukung. Pengumuman berstatus `terjadwal` juga membutuhkan scheduler (cron per menit atau setara) yang berjalan; pastikan ini bagian dari pemeriksaan hosting. Email OTP, reset, dan undangan staf dibutuhkan sejak Tahap 1. Cron pemroses antrean harus punya interval dan batas eksekusi yang aman. Jika antrean tidak diproses, UI perlu menunjukkan status yang tepat dan operasional dapat mendeteksi backlog.

Jangan berasumsi email atau queue bisa berjalan hanya karena aplikasi bisa memuat halaman web.

## 5A. Peristiwa email otomatis (DEC-089, BR-NTF-001)

Selain OTP, reset, dan undangan, dikirim lewat antrean dengan rincian transaksi (bukan sekadar pemberitahuan):
- Pendaftaran terkirim dan bukti pembayaran diterima sistem.
- Hasil seleksi: lulus atau gagal (dengan langkah berikutnya).
- Tagihan baru: biaya pendaftaran, daftar ulang, SPP bulanan (nominal, jatuh tempo, rekening tujuan).
- Pembayaran terverifikasi atau ditolak: rincian, alasan penolakan, sisa tunggakan.
Kegagalan email tidak membatalkan transaksi. Email tagihan SPP hanya untuk tagihan baru. Email OTP/reset dikirim dengan prioritas lebih tinggi.

## 6. Riwayat pengiriman

Simpan status minimal: antre, terkirim/diterima provider jika provider menyediakan bukti, gagal, retry dijadwalkan, gagal final. Simpan message ID eksternal bila tersedia dan kode error aman; jangan menyimpan credential atau isi pesan sensitif berlebihan.

Status provider `accepted` bukan jaminan pesan masuk ke inbox. Jangan mengklaim email pasti terbaca.

## 7. Notifikasi portal

- Kaitkan notifikasi ke pengguna penerima.
- Sediakan status belum dibaca/dibaca.
- Jangan mengandalkan notifikasi sebagai sumber data bisnis utama: status pembayaran tetap berasal dari pembayaran, bukan dari notifikasi.
- Penayangan data di notifikasi harus memperhatikan otorisasi dan tidak membocorkan data anak ke akun yang salah.
