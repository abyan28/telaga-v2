# Siklus Hidup Status TELAGA v2

Status di bawah adalah rancangan logis yang sudah diselaraskan dengan `rules.md` dan keputusan DEC-027..096. Butir bertanda **⚠** adalah usulan yang masih perlu konfirmasi pemilik. Jangan menambahkan status baru untuk menghindari pemodelan transisi yang semestinya.

## 1. Akun pengguna

Status akun dan verifikasi email adalah dua hal terpisah (BR-ID-009).

- `status_akun`:
  - `aktif`: akun dapat login sesuai kebijakan.
  - `ditangguhkan`: login ditolak sementara (mis. dugaan penyalahgunaan); sesi aktif dicabut; data tetap; dapat diaktifkan kembali oleh admin berwenang. ⚠ makna diusulkan.
  - `dinonaktifkan`: akun tidak dipakai lagi (mis. pegawai keluar); login ditolak; sesi dicabut. ⚠ makna diusulkan.
- Verifikasi email: ditunjukkan oleh `email_verified_at` (kosong = belum diverifikasi). Akun baru wali berstatus `aktif` tetapi belum dapat login sebelum email diverifikasi.

Transisi harus mencabut atau memeriksa ulang sesi aktif. Pemulihan akun (OTP lalu token reset) adalah alur terpisah dari verifikasi awal email.

## 2. Pendaftaran

Alur normal (usulan nama status):

`draf` → `diajukan` → `dalam_pemeriksaan` → (`perlu_perbaikan` → `diajukan`) → `menunggu_keputusan` → (`diterima` atau `ditolak`).

- `diajukan`: dikirim wali, antre untuk petugas. `dalam_pemeriksaan`: petugas sedang memeriksa dokumen/data.
- `perlu_perbaikan`: dikembalikan ke wali; setelah dilengkapi kembali ke `diajukan`.
- Status akhir tambahan: `ditarik` (oleh wali, dari `draf` sampai `menunggu_keputusan`) dan `dibatalkan` (oleh petugas/sistem). Keduanya wajib beralasan dan mencatat aktor.
- Wali yang mengajukan pengunduran diri setelah membayar daftar ulang: pendaftaran tetap `diterima`; status siswa menjadi `keluar` dan refund atas pembayaran daftar ulang mengikuti BR-FIN-008/BR-FIN-018. Pengunduran diri dicatat Admin Sekolah atau Bendahara (DEC-056).
- Status lama disimpan di `riwayat_status_pendaftaran`; kolom status terkini di pendaftaran adalah proyeksi status terbaru.
- Penerimaan hanya oleh role/permission yang ditentukan (lihat `permission-matrix.md`). Saat `diterima`, `orang` calon, `siswa`, dan hubungan wali dibentuk secara idempoten; wali pengaju terverifikasi otomatis (DEC-033).
- Status pendaftaran, pembayaran biaya pendaftaran, dan kelengkapan dokumen adalah tiga dimensi berbeda (BR-PPDB-009). Transisi `diajukan` → `dalam_pemeriksaan` mensyaratkan biaya pendaftaran (bila dikonfigurasi) sudah lunas dan terverifikasi (BR-PPDB-014, DEC-049).
- Setelah `diterima`, tagihan daftar ulang dibuat untuk siswa (BR-FIN-019).
- Penempatan kelas adalah proses terpisah dari penerimaan.
- Status tambahan: `dibatalkan` (kelulusan dibatalkan saat PPDB ditutup tanpa pembayaran daftar ulang, atau pembatalan calon; BR-PPDB-019). Dokumen tidak dapat diverifikasi sebelum pembayaran pendaftaran terverifikasi, dan Lulus/Gagal hanya dari `dalam_pemeriksaan` setelah semua dokumen diterima (BR-PPDB-020).
- “Izin edit” (BR-PPDB-021) adalah penanda pada pendaftaran, bukan status: selama aktif, verifikasi dan keputusan terkunci; berkas yang diganti memundurkan status ke `diajukan`/menunggu verifikasi berkas. “Ubah Keputusan” mengembalikan `diterima`/`ditolak` ke `dalam_pemeriksaan` selama belum ada pembayaran daftar ulang (BR-PPDB-022).

## 3. Pembayaran

Dua dimensi dipisah:

- Verifikasi: `draf` → `menunggu_verifikasi` (non-tunai dengan bukti) → `disetujui` atau `ditolak`. Pembayaran tunai yang dicatat petugas berwenang langsung `disetujui`.
- Posting: `belum_diposting` → `diposting`. Posting, pembentukan mutasi keuangan, dan alokasi dilakukan atomik. Koreksi setelah posting memakai reversal (pembayaran/alokasi/mutasi baru bertaut ke asal); pembayaran asal berstatus `dibalik`.
- `dibatalkan` hanya sebelum posting (dari `draf` atau `menunggu_verifikasi`).
- Alokasi ke tagihan: sistem mengusulkan otomatis ke tagihan terlama dulu; petugas dapat mengubah sebelum posting (BR-FIN-020, DEC-061).

Jangan menyimpulkan tagihan lunas dari bukti transfer yang belum disetujui.

## 4. Tagihan

- `belum_lunas`: sisa tagihan masih ada, termasuk saat baru dibayar sebagian. Pembayaran sebagian terlihat dari `jumlah_terbayar` (nilai turunan), bukan status terpisah.
- `lunas`: seluruh nilai tagihan yang sah sudah dialokasikan dari pembayaran yang diposting.
- `dibatalkan`: hanya melalui proses berotorisasi dengan alasan; histori pembayaran tidak dihapus. Perubahan nominal dilakukan sebagai penyesuaian tercatat.

## 5. Setoran tabungan

`draf` → `menunggu_diterima` → (`diterima` atau `ada_selisih`) → `diposting`; serta `dibatalkan`.

- Wali kelas mengisi `nominal_diajukan` per siswa lalu mengajukan (`menunggu_diterima`). Catatan guru belum mengubah saldo.
- Bendahara mengisi `nominal_diterima` per siswa. Jika total diterima sama dengan total diajukan → `diterima`; jika berbeda → `ada_selisih`.
- Keduanya dilanjutkan ke `diposting` setelah bendahara mengonfirmasi (untuk `ada_selisih` catatan penyelesaian wajib). Yang diposting adalah `nominal_diterima` per siswa (DEC-031). Saldo resmi hanya berubah pada `diposting`.
- `dibatalkan` hanya sebelum posting. Setelah posting, koreksi lewat reversal mutasi tabungan.

## 6. Dokumen dan versi

Status melekat pada **versi dokumen**: `diunggah`, `menunggu_verifikasi`, `disetujui`, `ditolak`, `kedaluwarsa` (bila masa berlaku relevan). Keputusan verifikasi selalu merujuk versi tertentu. Versi yang menjadi bukti historis tidak dihapus karena ada versi baru. Status tingkat dokumen, bila ditampilkan, adalah ringkasan dari versi terkini.

## 7. Rapor

Alur kerja pada `rapor`: `draf` → `menunggu_persetujuan` → `siap_diterbitkan` → `terbit`.

- Wali kelas menyusun dan mengisi bagian manual; wali boleh mengisi Refleksi Orang Tua di portal (opsional) atau menulis tangan pada cetakan; tanpa tenggat (DEC-054). Wali kelas mengajukan (`menunggu_persetujuan`) setelah pemeriksaan kelengkapan.
- Kepala RA menyetujui (`siap_diterbitkan`) atau mengembalikan ke `draf` dengan catatan (DEC-032).
- Penerbitan membuat `rapor_versi` berstatus `terbit`. Versi sebelumnya menjadi `digantikan`. Versi terbit tidak diedit; koreksi membuat versi baru yang melewati alur persetujuan lagi.
- PDF dan data snapshot setiap versi harus konsisten.

## 8. Pengumuman

Status: `draf`, `terjadwal`, `terbit`, `diarsipkan`. Status email tiap penerima diatur terpisah dalam catatan pengiriman, agar kegagalan email tidak mengubah pengumuman yang sudah terbit. Perubahan isi setelah terbit membuat `versi_pengumuman` baru. `terjadwal` membutuhkan scheduler yang berjalan di hosting.

## 9. Status entitas lain

| Entitas | Status | Catatan |
|---|---|---|
| `siswa` | `calon` → `aktif` → `lulus` atau `keluar`; `calon` → `keluar` (batal sebelum NIS, dengan refund) | `calon` terbentuk saat diterima; menjadi `aktif` hanya saat “Buat NIS”; transisi lain oleh petugas berwenang dengan alasan (DEC-066, DEC-079) |
| `tahun_ajaran`, `periode_pembelajaran` | `direncanakan` → `aktif` → `selesai` | hanya satu tahun ajaran `aktif` sekaligus (DEC-066) |
| `periode_pendaftaran` | `draf` → `dibuka` → `ditutup` → `diarsipkan` | tampil publik bila `dipublikasikan` |
| `riwayat_penempatan_siswa` | `aktif` → `selesai` | perpindahan menutup yang lama dan membuka baru dalam satu transaksi |
| `hubungan_siswa_wali` | `menunggu` → `terverifikasi` atau `ditolak` | DEC-033 |
| `mutasi_keuangan`, `transfer_keuangan` | `diposting`, `dibalik` | tidak ada hapus; koreksi reversal |
| `refund_pembayaran` | `diajukan` → `disetujui` atau `ditolak` → `diposting` | disetujui Admin Sekolah atau Bendahara (DEC-056) |
| `surat` | `draf` → `terbit` → `dibatalkan` | nomor tidak dipakai ulang |
| `agenda` | `draf` → `dipublikasikan` → `diarsipkan` | |
| `template_surat` | `draf`, `aktif`, `nonaktif` | versi baru tidak mengubah surat lama |

## 10. Aturan implementasi state transition

- Setiap transisi penting memvalidasi status asal, izin aktor, prasyarat dan konsistensi data.
- Perubahan status dan penulisan histori dilakukan dalam satu transaksi saat berada di database yang sama.
- `updated_at` bukan pengganti histori status.
- Perubahan yang berdampak uang, role, penerimaan siswa, dokumen sensitif atau publikasi rapor harus mencatat aktor dan waktu.
