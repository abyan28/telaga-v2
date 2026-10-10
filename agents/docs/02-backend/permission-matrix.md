# Matriks Role dan Permission

Dokumen ini memuat (1) kapabilitas default per role bawaan, (2) katalog permission `modul.objek.tindakan` beserta lingkup record, dan (3) aturan pemisahan tugas. Role dapat dikustomisasi lewat konfigurasi; backend tetap menegakkan Policy dan scope data pada setiap request.

**Status:** seluruh default role dalam tabel ini telah disetujui pemilik (DEC-032, 044, 046, 056, 068, 070, 075). Rujukan DEC menunjuk keputusannya; sel tanpa rujukan berasal dari matriks awal yang tidak diubah. Perubahan lebih lanjut dicatat di decision-log.

Legenda: ✓ = diizinkan penuh dalam scope; L = hanya lihat; S = scope terbatas (lihat kolom Scope); — = bukan kewenangan default.

## 1. Kapabilitas default per role

| Kapabilitas | Admin Utama | Admin Sekolah | Kepala RA | Bendahara | Guru (wali kelas) | Guru (pendamping) | Wali Murid |
|---|---|---|---|---|---|---|---|
| Setting sistem, role, permission | ✓ | — (DEC-075) | — | — | — | — | — |
| Data master (tahun ajaran, kelas, ruangan, referensi) | ✓ | ✓ | L | — | L | L | — |
| Periode PPDB | ✓ | ✓ | L | L | — | — | — |
| Memproses pendaftaran (periksa dokumen) | ✓ | ✓ | L | S: bagian pembayaran | — | — | S: ajukan milik sendiri |
| Memutuskan penerimaan | ✓ | ✓ (DEC-068) | ✓ (DEC-070) | — | — | — | L: status sendiri |
| Profil siswa | ✓ | ✓ | L | L: data minimum transaksi | S: kelas ditugaskan | S: kelas ditugaskan (L) | S: anak terverifikasi, field terbatas |
| Menetapkan penempatan siswa | ✓ | ✓ | ✓ (DEC-075) | — | L | L | — |
| Mencatat absensi siswa | ✓ | ✓ (DEC-075) | L | — | S: kelas ditugaskan | S: kelas ditugaskan | L: anak sendiri |
| Kesehatan & pertumbuhan | ✓ | ✓ (DEC-075) | L (DEC-075) | — | S: ubah kelas ditugaskan | L: kelas ditugaskan (DEC-075) | L: anak sendiri |
| Pegawai dan jabatan | ✓ | ✓ | L | — | L: profil sendiri | L: profil sendiri | — |
| Absensi pegawai | ✓ (koreksi) | ✓ (catat/koreksi dengan alasan; pegawai tanpa akun) | L | — | S: absen sendiri via ponsel (GPS) | S: absen sendiri via ponsel (GPS) | — |
| Tagihan (buat/generate, batalkan) | ✓ | L (DEC-070) | L | ✓ | — | — | L: tagihan anak terverifikasi (atau pendaftaran sendiri) |
| Mencatat pembayaran tunai | ✓ | ✓ (DEC-068) | — | ✓ | — | — | — |
| Mengajukan bukti pembayaran non-tunai | — | — | — | — | — | — | ✓ (atas tagihan sendiri) |
| Verifikasi dan posting pembayaran non-tunai, alokasi | ✓ | — (DEC-070) | — | ✓ | — | — | — |
| Refund pembayaran daftar ulang | ✓ (setuju) | ✓ (setuju, DEC-056) | — | ✓ (setuju, DEC-056) | — | — | — |
| Kas/bank, mutasi, transfer | ✓ | — | L | ✓ | — | — | — |
| Mencatat setoran tabungan | ✓ | — | L | ✓ | ✓ (DEC-046) | — | — |
| Menerima/memposting setoran, penarikan tabungan | ✓ | — (DEC-068) | — | ✓ | — | — | — |
| Melihat saldo/mutasi tabungan | ✓ | L (DEC-075) | L | ✓ | L: kelas ditugaskan | L: kelas ditugaskan (DEC-075) | L: anak terverifikasi (DEC-044) |
| Dokumen privat | ✓ | ✓ (DEC-075) | L | S: dokumen transaksi | S: scope tugas | S: scope tugas (L) | S: dokumen anak sendiri |
| Verifikasi dokumen | ✓ | ✓ | — | S: bukti pembayaran | — | — | — |
| Menyusun rapor | ✓ | — | — | — | ✓ (DEC-046) | — | — |
| Mengisi Refleksi Orang Tua (opsional; bisa juga ditulis tangan pada cetakan) | — | — | — | — | — | — | ✓ (anak terverifikasi, sebelum diterbitkan; DEC-054) |
| Buat NIS massal (“Buat NIS”) | ✓ | ✓ | — | — | — | — | — |
| Calon Murid (lihat, batalkan dengan refund) | ✓ | ✓ | L | ✓ (refund) | — | — | — |
| Buka/tutup PPDB, kuota, tahun ajaran target, wizard tahun ajaran | ✓ | ✓ (mengikuti pembagian kerja sistem lama) | — | — | — | — | — |
| Izin edit wali dan Ubah Keputusan PPDB | ✓ | ✓ | ✓ (Ubah Keputusan) | — | — | — | — |
| Impor murid lama (CSV/Excel), terbitkan kode aktivasi wali | ✓ | ✓ | — | — | — | — | — |
| Ekspor CSV/PDF (murid, SPP, transaksi) | ✓ | S: murid | S: laporan baca | S: SPP dan transaksi | — | — | — |
| Catat pengunduran diri dan ajukan refund daftar ulang | ✓ | ✓ (DEC-056) | — | ✓ (DEC-056) | — | — | — |
| Menyetujui rapor | ✓ | — | ✓ (DEC-032) | — | — | — | — |
| Menerbitkan rapor (setelah disetujui) | ✓ | — (DEC-075) | ✓ | — | — | — | — |
| Melihat rapor terbit | ✓ | L | L | — | L: kelas ditugaskan | L: kelas ditugaskan | L: versi terbit anak sendiri |
| Pengumuman (buat/terbitkan) | ✓ | ✓ | ✓ (DEC-070) | — | S: kelasnya (DEC-070) | — | — |
| Surat | ✓ | ✓ | L | — | — | — | — |
| CMS | ✓ | ✓ | — | — | — | — | — |
| Laporan | ✓ | S: operasional | ✓ (baca) | S: keuangan | S: kelas ditugaskan | S: kelas ditugaskan | — |
| Audit log | ✓ | L terbatas (DEC-070) | — | S: audit transaksi keuangan saja (DEC-075) | — | — | — |
| Undang akun staf, kelola akun | ✓ | S: tanpa memberi role Admin Utama (DEC-070) | — | — | — | — | — |
| Tinjau/tautkan kecocokan `orang` | ✓ | ✓ | — | — | — | — | — |
| Verifikasi hubungan wali–anak tambahan | ✓ | ✓ | — | — | — | — | — |

Catatan pembacaan:
- `—` bukan kewenangan default; tidak melarang penugasan khusus dengan permission eksplisit.
- Admin Utama tidak mengabaikan audit, workflow, dan integritas transaksi.
- Kepala RA tidak otomatis dapat mengubah data keuangan.
- Guru dibatasi pada kelas/penugasan yang berlaku pada tanggal operasi.
- Wali murid wajib punya hubungan wali–anak `terverifikasi`; akses tidak ditentukan oleh input ID.
- Field tertentu lebih sensitif daripada seluruh record (mis. guru pencatat absensi belum tentu melihat identitas wali atau kesehatan lengkap).

## 2. Katalog permission

Format kode: `modul.objek.tindakan`. Scope: `semua`, `kelas` (penugasan berlaku), `sendiri`, `anak` (hubungan terverifikasi), `pendaftaran_sendiri`.

| Kode | Scope | Keterangan |
|---|---|---|
| `identitas.pengguna.lihat` / `.undang` / `.ubah_status` | semua | undang = kirim undangan staf |
| `identitas.role.lihat` / `.kelola` | semua | sensitif, diaudit |
| `identitas.orang.tinjau_kecocokan` | semua | tautkan manual (BR-ID-015) |
| `identitas.hubungan_wali.verifikasi` | semua | wali tambahan (DEC-033) |
| `audit.log.lihat` | semua / terbatas | |
| `pengaturan.sistem.lihat` / `.ubah` | semua | termasuk persentase refund, SPP bawaan; perubahan diaudit |
| `akademik.master.lihat` / `.kelola` | semua | tahun ajaran, periode, kelas, ruangan, referensi |
| `akademik.penugasan.kelola` | semua | |
| `ppdb.periode.lihat` / `.kelola` | semua | |
| `ppdb.pendaftaran.ajukan` | pendaftaran_sendiri | wali |
| `ppdb.pendaftaran.lihat` | semua / pendaftaran_sendiri | |
| `ppdb.pendaftaran.periksa` | semua | |
| `ppdb.pendaftaran.putuskan` | semua | terima/tolak |
| `siswa.siswa.lihat` | semua / kelas / anak | |
| `siswa.siswa.buat` / `.ubah` | semua | |
| `siswa.penempatan.lihat` / `.kelola` | semua / kelas | |
| `siswa.absensi.lihat` | semua / kelas / anak | |
| `siswa.absensi.catat` | kelas | |
| `siswa.kesehatan.lihat` / `.ubah` | semua / kelas / anak | field sensitif dibatasi |
| `siswa.portofolio.lihat` / `.kelola` | semua / kelas / anak | |
| `pegawai.pegawai.lihat` / `.kelola` | semua / sendiri | |
| `pegawai.absensi.catat_sendiri` | sendiri | absen mandiri (GPS); wajib akun |
| `pegawai.absensi.catat` / `.koreksi` / `.lihat` | semua / sendiri | catat/koreksi oleh admin dengan alasan wajib, diaudit |
| `keuangan.tagihan.lihat` | semua / anak / pendaftaran_sendiri | |
| `keuangan.tagihan.kelola` | semua | generate/batalkan |
| `keuangan.pembayaran.catat_tunai` | semua | |
| `keuangan.pembayaran.ajukan` | anak / pendaftaran_sendiri | wali |
| `keuangan.pembayaran.verifikasi` / `.posting` / `.alokasi` | semua | |
| `keuangan.pembayaran.balik` | semua | reversal, wajib alasan |
| `keuangan.refund.ajukan` / `.setujui` | semua | pengaju ≠ penyetuju |
| `keuangan.akun.kelola`, `keuangan.mutasi.lihat`/`.catat`, `keuangan.transfer.catat` | semua | |
| `tabungan.setoran.catat` | kelas (wali kelas) | |
| `tabungan.setoran.terima` / `.posting` | semua | |
| `tabungan.penarikan.catat` | semua | |
| `tabungan.saldo.lihat` | semua / kelas / anak | |
| `dokumen.dokumen.lihat` / `.unggah` | semua / kelas / anak / sendiri | |
| `dokumen.versi.verifikasi` | semua | per versi |
| `surat.surat.buat` / `.terbitkan` / `.lihat` | semua | |
| `rapor.rapor.lihat` | semua / kelas / anak (terbit saja) | |
| `rapor.rapor.susun` | kelas (wali kelas) | |
| `rapor.refleksi_orang_tua.isi` | anak | opsional; sebelum diterbitkan |
| `siswa.nis.buat` | semua | aksi “Buat NIS” per periode |
| `siswa.calon.lihat` / `siswa.calon.batalkan` | semua | halaman Calon Murid; batal memicu refund |
| `ppdb.pengaturan.kelola` | semua | buka/tutup, kuota, tahun ajaran target |
| `ppdb.izin_edit.kelola` / `ppdb.keputusan.ubah` | semua | BR-PPDB-021/022 |
| `akademik.tahun_ajaran.wizard` | semua | tahun ajaran berikutnya |
| `siswa.impor.jalankan` | semua | impor CSV/Excel |
| `identitas.kode_aktivasi.terbitkan` | semua | akun wali lama |
| `laporan.ekspor.murid` / `.spp` / `.transaksi` | sesuai scope | diaudit (BR-IMP-002) |
| `keuangan.pengunduran_diri.catat` | semua | memicu refund daftar ulang (DEC-056) |
| `rapor.rapor.setujui` | semua | Kepala RA |
| `rapor.rapor.terbitkan` | semua | hanya rapor yang sudah disetujui |
| `komunikasi.pengumuman.kelola` / `.terbitkan` | semua / kelas (wali kelas, DEC-070) | |
| `komunikasi.notifikasi.lihat` | sendiri | |
| `kalender.agenda.lihat` / `.kelola` | semua | |
| `cms.konten.kelola` | semua | |
| `laporan.laporan.lihat` | sesuai laporan di `report-catalog.md` | |
| `sistem.ekspor.jalankan` | sesuai laporan | sensitif, diaudit |

## 3. Pemisahan tugas (maker–checker)

Keputusan yang sudah ada: rapor disusun wali kelas, disetujui Kepala RA, lalu terbit (DEC-032); setoran dicatat wali kelas dan diterima/diposting bendahara (DEC-031, DEC-046).

Aturan untuk transisi lain (disetujui pemilik, DEC-068, DEC-075):

| Transisi | Aturan |
|---|---|
| Pembayaran non-tunai | Pengaju (wali) dan pemverifikasi (bendahara/admin berwenang) berbeda secara alami |
| Pembayaran tunai | Dicatat petugas berwenang; reversal diaudit (pemisahan orang tidak diwajibkan, DEC-068) |
| Refund daftar ulang | Disetujui Admin Sekolah atau Bendahara (DEC-056); pengaju dan penyetuju tidak wajib berbeda, tercatat di audit (DEC-068) |
| Reversal transaksi terposting | Pelaku wajib mengisi alasan; diaudit |
| Penerimaan PPDB | Diputuskan oleh role dengan `ppdb.pendaftaran.putuskan`; riwayat mencatat aktor |
| Penarikan tabungan | Dicatat bendahara/admin berwenang dengan alasan; tanpa pemeriksa kedua, tercatat di audit (DEC-075) |

## 4. Langkah sebelum implementasi

1. Seed role bawaan dari bagian 1.
2. Implementasikan permission dari katalog bagian 2 sebagai kode unik dan petakan ke Policy per objek.
3. Tulis test positif dan negatif untuk setiap permission sensitif (lihat `testing-strategy.md`).
4. Setiap perubahan katalog atau default role dicatat di decision-log.
