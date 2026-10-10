# Rencana Implementasi TELAGA v2

Pelaksanaan dilacak per task di `agents/tasklist.md` (progress berbobot dan titik lanjut). Rencana ini adalah **urutan tahap otoritatif** (DEC-045): urutan dependensi, bukan estimasi waktu. `roadmap.md` hanya ringkasan. Setiap tahap harus memenuhi gerbang selesai sebelum tahap berikutnya dianggap siap. Kerjakan satu vertical slice pada satu waktu.

Sebelum tiap tahap dimulai: dictionary entitas tahap itu harus field-level (`data-dictionary.md`), keputusan pemblokir di `open-decisions.md` untuk tahap itu harus tertutup, dan permission yang dibutuhkan ada di katalog `permission-matrix.md`.

## Tahap 0 — Pemeriksaan hosting minimal dan bootstrap proyek

**0A. Pemeriksaan kompatibilitas minimal (sebelum bootstrap).** Catat versi PHP dan ekstensi, versi MySQL, ketersediaan cron/scheduler dan queue worker, akses CLI/SSH, dan storage privat (`deployment-runbook.md` §0). Hasilnya dipakai untuk memutuskan tipe PK dan fitur constraint.

**0B. Bootstrap.**
- Buat repository bersih dan tentukan strategi branch/review.
- Pasang Laravel starter kit React resmi yang kompatibel dengan Laravel 13, Inertia, React, TypeScript, Tailwind dan shadcn/ui.
- Siapkan MySQL lokal dan environment developer.
- Rekonsiliasi nama tabel/kolom bawaan starter kit dan paket dengan DEC-028; catat hasilnya.
- Tetapkan format/lint/static analysis, test framework, lockfile dan pipeline build.
- Buat health check, error handling, konfigurasi environment, zona waktu Asia/Jakarta, dan dokumentasi cara menjalankan.
- Jangan impor struktur lama tanpa audit dan keputusan.

**Gerbang selesai:** aplikasi baru berjalan pada lingkungan development; build dan test kosong berjalan; koneksi MySQL tervalidasi; catatan 0A tersimpan; DEC-028 diterapkan pada contoh migrasi pertama.

## Tahap 1 — Fondasi keamanan, identitas, dan layanan dasar

- Model `orang`, `pengguna`, `siswa`, `profil_wali`, `pegawai` (kerangka), `pengaturan_sistem`, `profil_sekolah`, `penomoran`, `audit_log`.
- Registrasi wali, login/logout, verifikasi email OTP, reset kata sandi (OTP lalu token), undangan akun staf; pencocokan manual `orang` (BR-ID-015).
- Role/permission sesuai katalog, Policy, dan pembatasan scope data.
- Layanan dasar yang dibutuhkan tahap berikutnya: email (Mailjet development) dan queue, penyimpanan berkas privat dengan versi dan verifikasi dokumen dasar, notifikasi portal dasar.
- Layout, navigasi, design tokens, dan konvensi kontrak (`api-contract-conventions.md`).

**Gerbang selesai:** skenario akun wali/staf (termasuk undangan dan reset), akses per role, dan pengujian negatif otorisasi dari `security-baseline.md` §7 lulus; upload privat tidak dapat diunduh anonim; email OTP terkirim lewat queue pada lingkungan dev.

## Tahap 2 — Data master, tagihan/pembayaran dasar, dan PPDB

- Tahun ajaran, periode, kelas, ruangan, jenis penugasan, referensi.
- Jenis tagihan, tagihan, pembayaran (tunai dan non-tunai dengan verifikasi), dan alokasi dasar secukupnya untuk biaya pendaftaran (tagihan terikat pendaftaran).
- Data referensi: wilayah resmi dan daftar bank (impor sekali, OD-38); profil ayah/ibu dan alamat keluarga berjenjang; kode aktivasi akun wali lama.
- Pengaturan PPDB: buka/tutup, kuota, tahun ajaran target; wizard tahun ajaran berikutnya.
- Periode pendaftaran, persyaratan dokumen, pendaftaran (data calon di pendaftaran, validasi usia 4–6 tahun), wali, unggah dokumen, histori status, peringatan duplikat, izin edit wali, ubah keputusan.
- Calon Murid dan pembatalan dengan refund; Buat NIS (murid `calon` → `aktif`); gerbang PPDB ditutup.
- Impor murid lama (CSV/Excel) dan email otomatis dengan rincian transaksi.
- Biaya pendaftaran sebagai prasyarat pemeriksaan (BR-PPDB-014).
- Keputusan penerimaan dan pembentukan idempoten `orang` calon, `siswa`, hubungan wali terverifikasi, tagihan daftar ulang, dan aksi “Buat NIS”.
- Riwayat Penempatan Siswa.
- Notifikasi status ke wali (portal dan email).

**Gerbang selesai:** alur PPDB dari pembuatan akun hingga penempatan awal diuji, termasuk multi-anak, penerimaan yang diulang tidak menggandakan data, pelamar ditolak tidak membentuk `orang`, tagihan pendaftaran terbayar dan terverifikasi, serta penempatan bersamaan tidak tumpang tindih.

## Tahap 3 — Kesiswaan dan kehadiran

- Profil siswa/wali, absensi harian siswa, kesehatan dasar, pengukuran pertumbuhan, portofolio sesuai scope.
- Data pegawai, jabatan/riwayat jabatan, penugasan akademik (wali kelas, guru pendamping).
- Absensi pegawai mandiri via ponsel dengan validasi GPS area sekolah, koreksi admin, dan pencatatan admin untuk pegawai tanpa akun (BR-PEG-008/009; membutuhkan akun pegawai dari Tahap 1 dan HTTPS).
- Laporan LAP-01 (rekap absensi).

**Gerbang selesai:** akses lintas kelas dan lintas wali diuji, histori penempatan terjaga, absensi unik per siswa per tanggal.

## Tahap 4 — Keuangan dan tabungan

- Lengkapi tagihan bulanan idempoten, alokasi, refund, reversal.
- Akun keuangan, kategori, mutasi kas/bank dan transfer.
- Rekening tabungan, batch setoran dengan nominal diajukan/diterima, selisih, posting, penarikan, reversal; wali melihat saldo/mutasi.
- Laporan LAP-02, LAP-03, LAP-04 dan rekonsiliasi.

**Gerbang selesai:** invariant finansial (alokasi, saldo tabungan tidak negatif), double submit, transaction rollback, concurrency penarikan dan alokasi, dan akses bendahara diuji.

## Tahap 5 — Dokumen lanjutan, surat, kalender, pengumuman

- Dokumen/versi/verifikasi lanjutan dan relasi eksplisit.
- Template/nomor/surat dengan versi template historis; keluaran PDF dan Word/.docx (BR-DOK-009).
- Kalender akademik, agenda, pengumuman satu arah (target, versi, penjadwalan), email massal lewat queue.

**Gerbang selesai:** dokumen privat, nomor surat tidak ganda saat bersamaan, delivery failure, dan penjadwalan pengumuman diuji.

## Tahap 6 — Rapor LCPA

- Proof of concept PDF (LCPA dan keluaran surat) pada lingkungan mendekati hosting.
- Struktur draf dan isian manual mengikuti template sumber; seed `butir_capaian` dari dokumen sumber dan skala BB/MB/BSH/BSB (DEC-054); tabel rapor difinalkan setelah PoC (OD-12).
- Data identitas, kehadiran dan pertumbuhan ditarik dari sumbernya.
- Refleksi Orang Tua dari portal, persetujuan Kepala RA, gambar tanda tangan.
- Snapshot versi, publikasi, PDF privat, histori revisi.

**Gerbang selesai:** PDF memenuhi fidelity checklist; snapshot tidak berubah saat sumber diubah; hanya rapor yang disetujui dapat terbit; wali hanya melihat versi terbit.

## Tahap 7 — Website/CMS dan laporan

- Halaman, program sekolah, galeri/media, informasi pendaftaran, profil guru publik (dari pegawai yang dipublikasikan), pengaturan website.
- LAP-05 dan dashboard per role sesuai `report-catalog.md`.
- Integrasi dengan data aktif hanya melalui sumber utama yang diotorisasi.

**Gerbang selesai:** konten publik tidak membocorkan data privat, tampilan responsive, dan setiap angka dashboard dapat ditelusuri ke sumber.

## Tahap 8 — Hardening dan produksi

- Uji hosting target secara penuh, email produksi, queue/cron/scheduler, storage, PDF dan batas runtime.
- Backup/restore drill (mingguan, retensi 8 minggu; tinjau ulang risiko RPO), security review, performa dasar, logging dan pemantauan.
- Prosedur pemulihan Admin Utama (tanpa MFA) diuji.
- Training pengguna dan dokumentasi operasional.

**Gerbang selesai:** semua item production-readiness checklist lulus atau pengecualian memiliki penerimaan risiko tertulis.

## Prioritas

Kerjakan satu vertical slice pada satu waktu. Jangan membangun seluruh skema dan UI setiap modul sekaligus tanpa test dan feedback. Fitur pembelajaran yang ditunda tidak dimasukkan ke tahapan V2 awal.
