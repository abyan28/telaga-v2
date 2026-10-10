# Konvensi Kontrak Backend–Frontend (Inertia)

Dokumen ini menetapkan konvensi bersama antara agen backend dan UI/UX agar tiap tugas tidak menegosiasikan ulang hal yang sama. Statusnya **usulan teknis awal**; sesuaikan dengan pola starter kit dan versi Inertia yang terpasang saat bootstrap, lalu catat perubahan di decision-log. Kontrak per fitur tetap dicantumkan di `task-template.md`.

## 1. Prinsip

- Halaman dikirim lewat Inertia; endpoint JSON terpisah hanya bila kebutuhan nyata (DEC-006).
- Backend menjadi sumber kebenaran validasi, otorisasi, dan status. Frontend tidak menghitung nilai final keuangan.
- Props hanya berisi field yang boleh dilihat pengguna. Jangan mengirim objek model penuh; gunakan Resource/DTO eksplisit.

## 2. Route dan penamaan

- Nama route: `modul.objek.tindakan` mengikuti kode permission (mis. `ppdb.pendaftaran.lihat`), sehingga satu permission dapat dipetakan ke route yang jelas.
- Rute internal berprefiks per domain; portal wali dan halaman publik dipisahkan dari rute internal.
- Parameter rute memakai ID biasa; otorisasi tidak bergantung pada ketidakterdugaan ID.

## 3. Props bersama (shared props)

Setiap halaman internal menerima, sebagai props bersama:
- `auth.pengguna`: `id_pengguna`, `nama`, `email`, daftar role (kode).
- `auth.izin`: daftar kode permission pengguna (hanya untuk petunjuk UI, bukan kontrol keamanan).
- `flash`: `sukses`, `galat`, `info` (pesan satu kali, berbahasa Indonesia).
- `tahun_ajaran_aktif`: ringkasan (id, nama) bila relevan.
- `profil_sekolah`: nama dan logo.

## 4. Props halaman daftar dan detail

- Daftar: `data` (array item), `meta` (`halaman`, `per_halaman`, `total`), `filter` (nilai filter aktif yang dipertahankan lewat query string), `izin` (aksi yang diizinkan untuk daftar ini).
- Detail: `data` (objek), `riwayat` bila ada, `izin` per aksi (mis. `dapat_ubah`, `dapat_setujui`).
- Pagination dan filter sisi server; query string dipertahankan saat kembali dari detail.

## 5. Format error

- Validasi gagal: format error validasi Inertia/Laravel (`errors` per field), pesan berbahasa Indonesia. Nilai input aman dipertahankan.
- Akses ditolak: halaman 403 dengan pesan umum tanpa membocorkan keberadaan data. Untuk record yang tidak boleh diketahui keberadaannya, gunakan 404.
- Konflik versi/data berubah: 409 dengan pesan meminta muat ulang.
- Error server: halaman error dengan ID korelasi, tanpa stack trace.
- Duplikasi submit pada operasi keuangan: respons idempoten (hasil operasi yang sama) atau pesan jelas, bukan transaksi ganda.

## 6. Tipe TypeScript

- Lokasi: `resources/js/types/` dikelompokkan per domain (mis. `ppdb.ts`, `keuangan.ts`). Tipe bersama (`auth`, `meta`, `izin`) di `shared.ts`.
- Pemilik: agen backend menerbitkan bentuk props di deskripsi tugas; agen UI/UX menulis file tipe dan memperbaruinya bersamaan dengan perubahan kontrak. Perubahan props yang merusak wajib dicatat di PR.
- Hindari `any`. Nilai uang dikirim sebagai string desimal atau bilangan bulat satuan terkecil secara konsisten (⚠ pilih satu saat bootstrap) dan diformat di UI.

## 7. Data sensitif

Jangan mengirim ke browser: kolom hash, token, nomor identitas lengkap tanpa kebutuhan, atau field kesehatan bagi pengguna tanpa hak. Masking di backend, bukan di komponen.

## 8. Pengujian kontrak

Fitur baru memuat test feature yang memeriksa bentuk props (field wajib ada, field sensitif tidak ada) dan test negatif otorisasi.
