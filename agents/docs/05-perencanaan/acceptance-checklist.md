# Production Readiness Checklist

Daftar ini diisi menjelang produksi; bukan klaim bahwa semua pemeriksaan sudah selesai.

## Produk dan data
- [ ] Semua modul V2 yang termasuk scope memenuhi acceptance criteria.
- [ ] Modul pembelajaran yang ditunda tidak tampil sebagai fitur aktif.
- [ ] Data master tidak terduplikasi dan FK utama benar.
- [ ] Histori PPDB, penempatan, role, dokumen dan rapor terpelihara.
- [ ] Keuangan dan tabungan dapat direkonsiliasi dari ledger.
- [ ] Semua status transisi validasi dan audit-nya diuji.

## Security
- [ ] HTTPS, secret, debug mode dan permission server aman.
- [ ] OTP 6 digit diuji: TTL, reuse, retries, resend dan rate limit; reset kata sandi dan undangan staf diuji.
- [ ] Role/policy dites dengan akun dari semua konteks.
- [ ] Data wali-anak dan akses berkas privat dites.
- [ ] Upload dan export diuji.
- [ ] Tidak ada secret atau data anak dalam repository/log yang tidak tepat.
- [ ] Prosedur pemulihan akun berprivilege tinggi ditentukan (pengganti MFA).

## Hosting/runtime
- [ ] Versi PHP/ekstensi/Laravel kompatibel.
- [ ] MySQL versi produksi divalidasi.
- [ ] Document root diarahkan ke `public`.
- [ ] Deployment dapat dijalankan dan di-rollback dengan prosedur yang diuji.
- [ ] Cron/queue diproses dengan reliabel.
- [ ] Email produksi dikonfigurasi dan delivery diuji.
- [ ] Upload privat/publik sesuai penyimpanan, limit dan permission.
- [ ] PDF LCPA lulus tes visual dan fungsional pada target runtime.

## Operasional
- [ ] Logging dan error reporting bisa digunakan tanpa membocorkan data sensitif.
- [ ] Backup database dan berkas berhasil dilakukan.
- [ ] Restore drill berhasil dan hasilnya direkam.
- [ ] RPO/RTO ditentukan sesuai kemampuan nyata (nilai awal: mingguan, retensi 8 minggu, RTO 1 hari kerja).
- [ ] Akun admin utama dan pemulihannya diuji (minimal dua Admin Utama; prosedur pemulihan server tertulis; tanpa MFA, DEC-041).
- [ ] Penerimaan risiko tertulis: backup mingguan (RPO ±7 hari) ditinjau ulang atau diterima pemilik (DEC-047).
- [ ] Penerimaan risiko tertulis: tidak ada MFA (DEC-041) dan tidak ada fitur izin publikasi (DEC-036).
- [ ] Penerimaan risiko tertulis: pengaju dan penyetuju refund/reversal boleh orang yang sama, hanya diaudit (DEC-068).
- [ ] Penerimaan risiko tertulis: tidak ada penghapusan data, semua menjadi arsip (DEC-071); tinjauan terhadap ketentuan perlindungan data pribadi yang berlaku dilakukan sebelum produksi.
- [ ] Absen GPS pegawai diuji di ponsel nyata pada HTTPS (izin lokasi, akurasi pada radius 10 m, penolakan) dan prosedur koreksi admin berjalan; radius disesuaikan bila absen sah sering ditolak.
- [ ] Koordinat pegawai hanya dapat dilihat admin berwenang dan aksesnya tercatat.
- [ ] SOP perubahan data, koreksi keuangan, perubahan permission dan insiden tersedia.
- [ ] Pengguna sekolah mendapat pelatihan sesuai peran.

## Sign-off
- Pemilik sistem:
- Tanggal:
- Pengecualian yang diterima:
- Bukti test/deployment:
