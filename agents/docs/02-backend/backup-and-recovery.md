# Backup dan Pemulihan

## 1. Cakupan

Backup TELAGA mencakup sekurangnya:

- database MySQL, termasuk skema, transaksi dan referensi;
- berkas privat dan publik yang dikelola aplikasi;
- PDF rapor/surat yang wajib disimpan untuk rekam historis atau dapat diregenerasi dari snapshot secara tervalidasi;
- konfigurasi operasional yang aman untuk dipulihkan, tanpa memasukkan secret secara tidak terlindungi;
- dokumentasi release dan identitas versi yang sedang berjalan.

## 2. Prinsip

- Backup bukan backup yang teruji sampai pernah dilakukan restore.
- Simpan salinan terpisah dari hosting utama jika kondisi biaya/kemampuan memungkinkan.
- Enkripsi backup yang memuat data pribadi dan batasi siapa yang dapat mengaksesnya.
- Tentukan jadwal dan retensi setelah volume data dan paket hosting diketahui.
- Jangan menyimpan backup pada direktori publik yang dapat diunduh melalui URL.
- Database dan berkas perlu direkonsiliasi karena metadata berkas dapat ada di DB sementara objek fisiknya berada di storage lain.

## 3. Target pemulihan (DEC-047)

Nilai awal yang diputuskan pemilik:
- Frekuensi: **mingguan** (database dan berkas), retensi **8 minggu**.
- RPO efektif: sekitar **7 hari** — data yang dibuat setelah backup terakhir (termasuk pembayaran, setoran tabungan, dan penerbitan rapor) dapat hilang bila terjadi kegagalan.
- RTO sasaran: **1 hari kerja**.

**Risiko yang diterima pemilik:** data keuangan hingga sekitar satu minggu dapat hilang. Rekomendasi: tinjau ulang sebelum produksi, mis. menambah backup database harian, karena volume transaksi keuangan dan tabungan dapat tinggi. Lokasi salinan di luar hosting dan siapa yang menjalankan backup belum ditetapkan.

Jangan mengklaim RPO/RTO terpenuhi sebelum mekanisme backup dan proses restore terbukti.

## 4. Uji restore

- Restore ke lingkungan terisolasi.
- Verifikasi integritas database dan foreign key.
- Periksa jumlah dan akses sampel berkas, PDF rapor dan surat.
- Uji login, permission, pembayaran historis dan snapshot rapor.
- Dokumentasikan waktu, masalah, dan perubahan prosedur.
- Hapus salinan uji sesuai kebijakan data setelah pengujian.
