# Matriks Keterlacakan Kebutuhan

Skema ID tunggal: **`BR-*` dari `rules.md`** adalah acuan; kolom PRD merujuk ID modul (`PRD-*`) di `prd.md`. ID lama (ID-01, AUTH-01, FIN-01, dst.) digantikan oleh tabel ini. Matriks mencakup aturan kritis (identitas, keamanan, integritas data/keuangan, privasi, rapor); aturan lain ditambahkan saat modulnya dikerjakan. Aturan yang belum tercakup tetap berlaku.

Bukti uji merujuk daftar di `02-backend/testing-strategy.md` dan checklist di `02-backend/security-baseline.md` §7.

| Aturan | PRD | Dokumen desain | Implementasi utama | Bukti uji minimum |
|---|---|---|---|---|
| BR-ID-002 | PRD-IDA | `domain-architecture.md`, `data-dictionary.md` | unique `pengguna.id_orang`, FK wajib | test duplikat akun; akun tanpa orang ditolak |
| BR-ID-007 | PRD-IDA | `auth-rbac.md`, `workflows.md` | registrasi wali | negative test signup staf/admin |
| BR-ID-011, BR-SIS-003 | PRD-IDA, PRD-SIS | `auth-rbac.md`, `permission-matrix.md` | Policy hubungan terverifikasi | akses anak/pendaftaran orang lain ditolak |
| BR-ID-012, BR-ID-013 | PRD-IDA | `auth-rbac.md` | endpoint profil/role | ubah role sendiri ditolak; audit tercatat |
| BR-ID-014 | PRD-IDA | `security-baseline.md` | Policy semua endpoint | direct URL/ID manipulation tests |
| BR-ID-015 | PRD-IDA | `auth-rbac.md`, `workflows.md` | antrean tinjauan kecocokan | tidak ada auto-merge; respons tidak bocor |
| BR-ID-016, BR-ID-017 | PRD-IDA, PRD-AKD | `permission-matrix.md` | pemberian role manual; Policy penugasan | pendamping tidak dapat setor/rapor |
| BR-AUTH-002..008 | PRD-IDA | `auth-rbac.md` | layanan OTP, `kode_verifikasi` | expired/reuse/invalid/resend tests |
| BR-AUTH-009, BR-AUTH-016 | PRD-IDA | `auth-rbac.md` | reset OTP→token; undangan staf | token sekali pakai; undangan baru membatalkan lama |
| BR-AUTH-011 | PRD-IDA | `auth-rbac.md`, `security-baseline.md` | respons seragam | test tidak membedakan email terdaftar |
| BR-AUTH-013 | PRD-IDA | `auth-rbac.md` | rate limit login | test throttle |
| BR-AUTH-014 | PRD-IDA | `auth-rbac.md`, `status-lifecycle.md` | invalidasi sesi | akun ditangguhkan kehilangan sesi |
| BR-AKD-007 | PRD-AKD | `workflows.md` §2, `database-conventions.md` | penempatan service + penguncian | overlap & concurrency tests |
| BR-PPDB-003, BR-PPDB-011 | PRD-PPDB | `workflows.md`, `data-dictionary.md` | form/pendaftaran | satu pengajuan satu calon; peringatan duplikat; ditolak tidak buat `orang` |
| BR-PPDB-004 | PRD-PPDB | `status-lifecycle.md` | transition service/history | status history test |
| BR-PPDB-006 | PRD-PPDB | `data-dictionary.md` | persyaratan dokumen | prasyarat dokumen wajib |
| BR-PPDB-007 | PRD-PPDB | `workflows.md` | penerimaan idempoten | retry tidak menggandakan `orang`/siswa |
| BR-PPDB-012, BR-PPDB-014 | PRD-PPDB, PRD-KEU | `workflows.md`, `status-lifecycle.md` §2 | tagihan ke pendaftaran; prasyarat lunas | tidak dapat masuk pemeriksaan sebelum lunas terverifikasi |
| BR-FIN-019 | PRD-PPDB, PRD-KEU | `workflows.md` §1 | tagihan daftar ulang untuk siswa | dibuat sekali (idempoten) |
| BR-FIN-005, BR-FIN-021 | PRD-KEU | `workflows.md` §3 | cicilan per jenis; satu bukti pending; verifikasi idempoten | SPP/pendaftaran kurang ditolak; daftar ulang boleh sebagian; verifikasi dua kali tidak ganda |
| BR-FIN-008, BR-FIN-018 | PRD-KEU | `workflows.md` §1A | rumus refund denda | bayar penuh 70%; sebagian = terbayar − denda; < denda wajib lunasi |
| BR-FIN-022 | PRD-KEU | `workflows.md` | jadwal SPP sesuai pengaturan | tanggal mengikuti pengaturan; idempoten |
| BR-PPDB-016–024 | PRD-PPDB | `workflows.md` §1A–1B | usia, kuota, buka/tutup, gerbang, izin edit, ubah keputusan | uji PPDB di `testing-strategy.md` |
| BR-SIS-012–014 | PRD-SIS | `data-dictionary.md` | form/profil murid, ayah/ibu, alamat, validasi | field wajib dan validasi |
| BR-AUTH-001, BR-AUTH-017 | PRD-IDA | `auth-rbac.md` | login tiga identitas; kode aktivasi | tanpa kata sandi dari NIK; percobaan dibatasi |
| BR-IMP-001, BR-IMP-002 | PRD-SIS, PRD-LAP | `workflows.md` §1C, `report-catalog.md` | impor CSV/Excel; ekspor CSV/PDF | baris gagal dilaporkan; ekspor diaudit |
| BR-NTF-001 | PRD-ADM | `email-notifications-queue.md` §5A | email dengan rincian lewat antrean | kegagalan email tidak membatalkan |
| BR-UI-001 | — | `rules.md` | satu titik kapital otomatis dengan pengecualian | uji tiap pengecualian |
| BR-OPS-007 | — | `security-baseline.md` §7A | persyaratan pelajaran sistem lama | daftar centang §7A |
| BR-SIS-011 | PRD-PPDB, PRD-SIS | `workflows.md` §1.13, `data-dictionary.md` | aksi “Buat NIS” + `penomoran` | urutan abjad, susulan lanjut, tidak berubah, tanpa nomor ganda |
| BR-PEG-008, BR-PEG-009, BR-PEG-010 | PRD-PEG | `workflows.md` §8A, `data-dictionary.md` | absen mandiri GPS; koreksi admin | di luar radius ditolak; waktu server; koordinat tidak tersimpan; koreksi diaudit |
| BR-FIN-020 | PRD-KEU | `workflows.md` §3.5 | usulan alokasi terlama | usulan benar; ubah sebelum posting; tidak ubah setelah posting |
| BR-DOK-009 | PRD-ADM | `documents-and-storage.md`, `reports-and-pdf.md` | pembuat PDF dan docx | kedua format terbentuk dan privat |
| BR-OPS-006 | PRD-IDA | `security-baseline.md` | arsip, tanpa fitur hapus | tidak ada endpoint hapus data pribadi/ledger; entitas nonaktif diarsipkan |
| BR-AKD-001 | PRD-AKD | `status-lifecycle.md` §9 | tahun ajaran aktif tunggal | aktivasi kedua ditolak/menonaktifkan sesuai aturan |
| BR-RAP-016, BR-RAP-017 | PRD-RAP | `rapor-template-spec.md` | cut-off per periode; skala tingkat | kehadiran/pengukuran sesuai cut-off; tingkat hanya BB/MB/BSH/BSB |
| BR-SIS-004, BR-SIS-005 | PRD-SIS | `data-dictionary.md`, `database-conventions.md` | unique siswa+tanggal | absensi ganda ditolak |
| BR-SIS-008 | PRD-SIS | `permission-matrix.md` | Policy kesehatan | akses data kesehatan tanpa hak ditolak |
| BR-SIS-010, BR-CMS-004 | PRD-SIS, PRD-CMS | `documents-and-storage.md` | media publik vs dokumen privat | foto anak tidak tampil publik tanpa tindakan petugas |
| BR-FIN-002..004 | PRD-KEU | `database-conventions.md`, `workflows.md` | alokasi transaksional | boundary & concurrency tests |
| BR-FIN-005 | PRD-KEU | `status-lifecycle.md` | status tagihan | parsial tidak lunas |
| BR-FIN-007, BR-FIN-008 | PRD-KEU | `data-dictionary.md` | `pengaturan_sistem` + riwayat | perubahan pengaturan diaudit |
| BR-FIN-010, BR-FIN-012 | PRD-KEU | `status-lifecycle.md`, `workflows.md` | verifikasi + posting atomik | menunggu verifikasi tidak buat mutasi resmi; rollback test |
| BR-FIN-013, BR-FIN-014 | PRD-KEU | `rules.md`, `security-baseline.md` | reversal flows | deny mutation; reversal tertaut asal |
| BR-FIN-015..017 | PRD-KEU | `database-conventions.md` | generate tagihan idempoten | dijalankan dua kali tidak ganda |
| BR-FIN-018 | PRD-KEU | `workflows.md` §3.10 | refund | refund tertaut pembayaran, persen tersalin |
| BR-KEU-003 | PRD-KEU | `workflows.md` | transfer service | transfer bukan pemasukan/pengeluaran |
| BR-KEU-005..009 | PRD-KEU | `data-dictionary.md` | FK sumber eksplisit | trace ke sumber; rekonsiliasi saldo |
| BR-TAB-004, BR-TAB-005 | PRD-KEU | `workflows.md` §5 | alur setoran | pending vs posted balance |
| BR-TAB-007, BR-TAB-011 | PRD-KEU | `workflows.md` §5 | rekonsiliasi per rincian | selisih tersimpan; posting `nominal_diterima` |
| BR-TAB-009, BR-TAB-012 | PRD-KEU | `workflows.md` §5 | penarikan dengan kunci | tidak melebihi saldo; concurrency |
| BR-DOK-002, BR-DOK-007 | PRD-ADM | `documents-and-storage.md` | private download handler | unduh tanpa izin ditolak |
| BR-DOK-003, BR-DOK-008 | PRD-ADM | `data-dictionary.md` | versioning; verifikasi per versi | ganti file mempertahankan versi lama |
| BR-DOK-005, BR-DOK-006 | PRD-ADM | `workflows.md` §7 | template versi; `penomoran` | template baru tidak ubah surat lama; nomor tidak ganda |
| BR-RAP-006..008 | PRD-RAP | `rapor-template-spec.md` | publishing/version service | sumber berubah setelah terbit |
| BR-RAP-005 | PRD-RAP | `rapor-template-spec.md` | auto-fill dengan penanda kosong | data kosong tampil jelas |
| BR-RAP-013, BR-RAP-014 | PRD-RAP | `workflows.md` §6, `status-lifecycle.md` §7 | persetujuan; refleksi wali | hanya disetujui yang terbit; refleksi terkunci setelah terbit |
| BR-RAP-001, BR-RAP-015 | PRD-RAP | `rapor-template-spec.md`, `reports-and-pdf.md` | form, template, renderer, tanda tangan | fidelity review |
| BR-KOM-001, BR-KOM-002 | PRD-ADM | `workflows.md` §8 | pengumuman/penerima/notifikasi | tidak ada fitur balasan |
| BR-KOM-005 | PRD-ADM | `email-notifications-queue.md` | after-commit job | kegagalan email tidak membatalkan |
| BR-CMS-001..003 | PRD-CMS | `domain-architecture.md`, `feature-catalog.md` | proyeksi/referensi sumber utama | tidak ada duplikasi data; profil guru hanya yang dipublikasikan |
| BR-AUD-001..003 | PRD-IDA | `security-baseline.md` | audit log append-only | entri audit untuk peristiwa BR-AUD-002 tanpa rahasia |
| BR-OPS-001 | PRD-IDA | `prd.md` §11 | konfigurasi zona waktu | tanggal absensi/jatuh tempo WIB |
| BR-OPS-002, BR-OPS-003 | — | `backup-and-recovery.md`, `deployment-runbook.md` | prosedur backup; PoC hosting | restore drill; staging PoC |

## Aturan pemeliharaan

- Baris tidak dianggap selesai hanya karena kode ada; bukti uji harus dilampirkan atau dirujuk.
- Bila aturan berubah, perbarui `rules.md`, PRD bila perlu, dan matriks ini.
- Bila acceptance criteria belum dapat diuji otomatis, jelaskan mengapa dan tetapkan bukti manual.
- Tambahkan baris untuk aturan BR yang belum tercakup saat modulnya dikerjakan; tugas (`task-template.md`) mencantumkan ID aturan yang diimplementasikan.
