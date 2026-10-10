# Autentikasi, OTP, dan RBAC

## 1. Autentikasi

- Web menggunakan session/cookie, bukan token autentikasi yang disimpan di localStorage.
- Login melalui satu isian yang menerima email, no. HP (dinormalisasi 0 → 62), atau username, beserta kata sandi (BR-AUTH-001, DEC-083). Pencarian akun tidak boleh membocorkan keberadaan akun lewat pesan atau perbedaan waktu respons.
- Akun wali lama diaktifkan lewat kode aktivasi sekali pakai dari admin (BR-AUTH-017): kode berumur terbatas, percobaan dibatasi, kode baru membatalkan yang lama; wali menetapkan kata sandi (minimum 12 karakter), username, dan email (diverifikasi OTP). Tidak ada kata sandi awal yang diturunkan dari NIK, no. HP, atau data publik.
- Akun ortu diblokir login bila PPDB ditutup dan tidak ada anak “hidup” (BR-PPDB-019); akun guru/staf dinonaktifkan lewat `status_akun`, bukan dihapus.
- Kata sandi disimpan dengan password hashing framework; jangan pernah menyimpan plaintext atau mengenkripsi kata sandi agar bisa dibaca kembali.
- Login, pemulihan, OTP, dan undangan akun mempunyai rate limit dan respons yang tidak membocorkan keberadaan akun.
- Sesi diperbarui setelah login dan dibatalkan saat logout. Status akun diperiksa ulang untuk akses sensitif dan penangguhan/nonaktif harus memutus kemampuan akses berikutnya.
- CSRF protection tetap aktif untuk request state-changing yang menggunakan sesi web.

## 2. Pendaftaran mandiri dan staf

- Pendaftaran akun mandiri hanya untuk wali murid. Pendaftaran membuat `orang` baru; bila ada kemungkinan kecocokan dengan orang yang sudah ada, staf berwenang meninjau dan menautkan manual (BR-ID-015). Setiap akun wajib terkait satu orang. Respons ke pendaftar tidak membedakan email baru dan email yang sudah ada.
- Pegawai yang wajib absen mandiri (guru, kepala, operator) harus punya akun (BR-PEG-008); pegawai lain tidak wajib.
- Akun pegawai/admin dibuat melalui undangan: tautan sekali pakai, TTL awal 48 jam, dibatalkan oleh undangan baru, untuk menetapkan kata sandi (BR-AUTH-016). Role diberikan setelah undangan diterima oleh admin berwenang atau sesuai role yang ditetapkan pada undangan.
- Profil akun, profil wali, dan pengajuan PPDB adalah objek berbeda.
- Tidak setiap pegawai harus mempunyai akun.
- Satu orang maksimal mempunyai satu akun; penautan orang harus mencegah duplikasi dan memerlukan aturan pencocokan aman.
- Akun aktif tidak berarti anak otomatis diterima/menjadi siswa.

## 3. OTP email

Nilai awal yang disepakati:

| Parameter | Nilai |
|---|---:|
| Panjang | 6 digit |
| Masa berlaku | 10 menit |
| Percobaan maksimum per kode | 5 |
| Jeda pengiriman ulang | 60 detik |

Aturan wajib:

- Gunakan generator acak kriptografis yang aman dan kode berformat digit dengan leading zero yang diperbolehkan.
- Kode berlaku satu kali. Penggunaan sukses mengonsumsi kode; kode baru membatalkan kode lama.
- Batasi jumlah percobaan, pengiriman ulang, alamat, IP dan pola penyalahgunaan. Angka/rentang rate limit lebih lengkap dapat dikonfigurasi.
- Simpan kode secara terlindungi; karena OTP pendek, hash saja tidak menggantikan TTL dan rate limit.
- Jangan log atau memasukkan OTP ke audit log.
- OTP verifikasi email, reset password, dan alur lain harus terikat pada tujuan masing-masing dan tidak dapat saling dipertukarkan.
- Kegagalan email tercatat tanpa mencatat kode rahasia.
- Respons pengguna tidak membocorkan apakah email sudah terdaftar.

Email OTP adalah verifikasi kontrol email. MFA tidak dibangun pada V2 awal (DEC-041, risiko diterima pemilik). Kompensasinya: kata sandi minimum 12 karakter, rate limit login, audit tindakan sensitif, dan prosedur pemulihan Admin Utama di bawah.

## 4. Password dan pemulihan akun

- Kata sandi minimum 12 karakter (aturan, BR-AUTH-015); izinkan frasa sandi panjang.
- Gunakan kebijakan hashing dan verifikasi framework yang didukung.
- Pemulihan: pengguna meminta OTP `reset_kata_sandi` ke email; OTP valid menerbitkan token reset sekali pakai berumur pendek; token dipakai untuk menetapkan kata sandi baru (DEC-040). Nilai awal TTL token reset: 30 menit (dapat dikonfigurasi).
- Batasi percobaan login dan endpoint reset. Nilai awal yang disetujui pemilik (DEC-067) dan dapat dikonfigurasi: login maks 5 percobaan gagal per akun+IP per 5 menit lalu jeda; permintaan OTP/reset maks 5 per jam per email; sesi idle 120 menit.
- Token reset mempunyai masa berlaku, sekali pakai, dan tidak masuk log.
- Setelah reset kata sandi, semua sesi lain akun tersebut dicabut.
- Hindari pertanyaan keamanan berbasis informasi pribadi anak.

### Pemulihan Admin Utama (tanpa MFA)

- Minimal dua akun dengan role Admin Utama pada orang berbeda, agar satu akun yang terkunci dapat dipulihkan oleh yang lain.
- Bila seluruh akun Admin Utama tidak dapat diakses: pemulihan oleh pengelola teknis lewat perintah server (bukan antarmuka web) yang menerbitkan token reset untuk akun tertentu, dan tindakan ini wajib dicatat di audit log serta dilaporkan ke pemilik sekolah.
- Prosedur lengkap dan penanggung jawabnya ditulis pada runbook sebelum produksi (`acceptance-checklist.md`).

## 5. Role dan permission

Role awal (dapat dikonfigurasi): Admin Utama, Admin Sekolah, Kepala RA, Bendahara, Guru, Wali Murid. Ini bukan daftar permanen yang tertanam secara kaku ke business logic.

Contoh permission:

- `siswa.lihat`, `siswa.buat`, `siswa.ubah`
- `ppdb.verifikasi`
- `pembayaran.verifikasi`
- `tabungan.setoran.terima`
- `pengguna.role.kelola`

Katalog permission lengkap (`modul.objek.tindakan`) beserta lingkup record ada di `permission-matrix.md`. Setiap permission memiliki kode unik dan makna yang jelas. Paket Spatie Laravel Permission boleh dipakai setelah kompatibilitas Laravel 13, struktur tabel, model User dan kebutuhan scope data diperiksa. Package tidak menggantikan Policy.

## 6. Perbedaan konsep

- **Role:** kumpulan kewenangan yang dapat diberikan ke akun.
- **Permission:** tindakan yang diizinkan, misalnya membuat siswa.
- **Jabatan:** posisi kepegawaian, misalnya Guru atau Kepala RA.
- **Penugasan:** tanggung jawab pada kelas, tahun atau kegiatan.
- **Policy/data scope:** apakah pengguna boleh mengakses catatan tertentu.

Jabatan guru tidak secara otomatis sama dengan role Guru. Role Guru diberikan manual oleh admin berwenang (BR-ID-016, DEC-046). Akses guru ke siswa diturunkan dari penugasan akademik yang berlaku: **wali kelas** dan **guru pendamping** keduanya mengakses siswa kelas yang ditugaskan; hanya **wali kelas** yang menyusun rapor, menandatangani, dan mencatat setoran tabungan (BR-ID-017).

## 7. Scope data

- Guru dengan izin `siswa.lihat` tetap dibatasi pada kelas yang ditugaskan, kecuali izin yang lebih luas memang diberikan.
- Wali hanya melihat anak yang hubungannya berstatus `terverifikasi` (wali pengaju PPDB terverifikasi otomatis saat diterima; wali tambahan diverifikasi staf dengan bukti, DEC-033). Sebelum diterima, wali hanya mengakses pengajuannya sendiri.
- Wali tidak boleh mengakses pembayaran atau dokumen anak lain hanya dengan menebak ID.
- Bendahara hanya dapat menjalankan operasi keuangan sesuai permission dan proses persetujuan.
- Admin konfigurasi tidak otomatis dapat melewati kontrol audit atau invariant keuangan.
- File privat, PDF rapor, ekspor, endpoint AJAX, dan action bulk juga wajib menjalankan policy.

## 8. Perlindungan pengelolaan role

- Pengguna tidak dapat menaikkan role sendiri melalui endpoint profil biasa.
- Lindungi role Admin Utama dari penghapusan/penonaktifan yang menghilangkan seluruh administrator sah.
- Perubahan pemberian/pencabutan permission dan role tercatat pada audit log.
- Perubahan izin berlaku sesuai kebijakan cache; jangan mempertahankan hak yang telah dicabut akibat cache usang.
- Pengujian harus mencakup direct URL/API request, manipulasi ID, akses lintas kelas, akses lintas wali, dan bulk actions.
