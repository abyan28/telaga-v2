# Roadmap Produk TELAGA v2 (Ringkasan)

Dokumen ini hanya ringkasan arah produk. **Urutan tahap, dependensi, dan gerbang selesai yang berlaku ada di [`implementation-plan.md`](../05-perencanaan/implementation-plan.md)** (DEC-045). Bila berbeda, ikuti `implementation-plan.md`. Tidak ada janji tanggal rilis.

## Ringkasan tahap

| Tahap | Fokus |
|---|---|
| 0 | Pemeriksaan hosting minimal dan bootstrap proyek |
| 1 | Fondasi keamanan dan identitas, termasuk email/queue/OTP dan dokumen/storage dasar |
| 2 | Data master, tagihan/pembayaran dasar, PPDB, penempatan awal |
| 3 | Kesiswaan, pegawai, dan kehadiran |
| 4 | Keuangan sekolah dan tabungan |
| 5 | Dokumen, surat, notifikasi, pengumuman, kalender |
| 6 | Rapor LCPA |
| 7 | Website/CMS dan laporan |
| 8 | Hardening, UAT, dan produksi |

## Status konsolidasi spesifikasi

- [x] Identitas produk, scope awal, stack inti.
- [x] Aturan bisnis lintas modul.
- [x] MySQL dan shared hosting sebagai preferensi.
- [x] Keputusan hasil audit dokumentasi (DEC-027..DEC-048).
- [ ] Tinjau PRD, rules, ERD, dan data dictionary setelah revisi audit.
- [ ] Tutup keputusan terbuka berprioritas tinggi di `open-decisions.md` (kolom Prioritas).

## Roadmap setelah V2 awal

Kurikulum, Komponen Kurikulum, Tujuan Pembelajaran, Perencanaan Pembelajaran, RPPM, RPPH, Pelaksanaan Pembelajaran, Asesmen, dan Perkembangan berbasis asesmen. Lakukan discovery proses RA sebelum mendesain fitur tersebut.
