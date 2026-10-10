# Panduan Implementasi Frontend

## 1. Stack dan integrasi

- **Core:** React + TypeScript.
- **Komunikasi Backend:** Inertia.js untuk komunikasi halaman penuh dengan Laravel (DEC-006).
- **Komponen Dasar:** shadcn/ui berbasis Radix UI primitives.
- **Styling:** Tailwind CSS berbasis utility dan variabel CSS token (DEC-005).
- **Animasi & Interaksi Mikro:** Framer Motion (untuk Motion Primitives) dan Lucide React untuk ikonografi konsisten.
- **Pembagian 3 Surface Layout (DEC-098):**
  - `resources/js/Layouts/PublicLayout.tsx` — Navbar dengan pengumuman PPDB, footer publik.
  - `resources/js/Layouts/AdminLayout.tsx` — Arsitektur *shadcn-admin* (collapsible sidebar, topbar search Cmd+K, breadcrumb).
  - `resources/js/Layouts/PortalLayout.tsx` — Tata letak mobile-first khusus wali murid.
- **Arsitektur Tema Ganda (DEC-099):**
  - Menggunakan class-based Tailwind (`darkMode: ['class']`).
  - Default tampilan adalah **Light Mode** yang hangat (`#FAF9F6`).
  - Dukungan **Dark Mode** disiapkan secara terstruktur: komponen menggunakan kelas semantik (`bg-background text-foreground bg-card text-card-foreground border-border`) sehingga saat kelas `.dark` disematkan pada elemen `<html>`, tampilan berubah proporsional dan tidak pecah.
- **Isolasi Token Warna & Rebranding Logo (DEC-099):** Nilai warna primer dan aksen diisolasi sebagai variabel CSS pada `resources/css/app.css` dan dipetakan di `tailwind.config.js`. Saat logo baru RA Al Kautsar selesai dibuat, pembaruan warna hanya dilakukan pada berkas konfigurasi token tersebut.
- Jangan menambahkan Next.js atau router SPA terpisah tanpa keputusan arsitektur baru.

## 2. Pembagian tanggung jawab

Frontend bertanggung jawab pada presentasi, interaksi, form, filter dan feedback. Laravel tetap menjadi sumber kebenaran untuk validasi bisnis, authorization, kalkulasi keuangan, status workflow, dan akses data.

Jangan menghitung nilai final keuangan hanya di frontend. Jika preview perhitungan diperlukan, backend tetap memvalidasi hasil saat submit.

## 3. Tipe dan props

- Tipe data frontend mengikuti kontrak props/response yang disepakati dan konvensi di `02-backend/api-contract-conventions.md` (props bersama, format error, lokasi tipe).
- Hindari `any` tanpa alasan.
- Pisahkan komponen reusable dari komponen halaman domain.
- Jangan mengirim kolom sensitif ke browser hanya karena komponen tidak menampilkannya.
- Jangan mengandalkan props lama untuk izin berisiko; tindakan tetap divalidasi server-side.

## 4. Forms

- Field memiliki label, helper text, error state, disabled/loading state bila relevan.
- Disable submit saat request berjalan dan cegah double submit.
- Pertahankan input aman saat validasi server gagal.
- Untuk form multistep, tampilkan kemajuan, kemampuan kembali dan ringkasan sebelum submit.
- Upload menunjukkan progress/keberhasilan/kegagalan dan persyaratan file.
- Data yang belum disimpan tidak boleh diam-diam hilang ketika navigasi berubah.

## 5. Tables and lists

- Gunakan pagination/filter server-side untuk data besar.
- Pertahankan query/filter yang sesuai saat navigasi detail-kembali.
- Tampilkan status dan tindakan berdasarkan izin yang diberikan backend sebagai petunjuk UI, tetapi jangan menjadikan itu kontrol keamanan.
- Konfirmasi aksi bulk dan jelaskan jumlah record yang ditargetkan.

## 6. State dan responsivitas

Setiap halaman perlu memodelkan loading, success, empty, validation error, permission denied, not found dan server error. Jangan menampilkan layar kosong saat gagal. Tampilan ponsel diprioritaskan untuk pembacaan dan tugas harian; tabel berat bisa berubah menjadi list/card atau mempunyai tampilan alternatif yang jelas.

## 7. Kinerja frontend

- Hindari memuat seluruh data besar pada klien.
- Optimalkan gambar dan media.
- Hindari render ulang mahal tanpa kebutuhan.
- Gunakan lazy loading hanya bila meningkatkan pengalaman dan tidak mengganggu alur penting.
- Jangan menambahkan state management global besar tanpa kebutuhan yang terbukti.

## 8. Integrasi Inertia

Ikuti pola starter kit/versi Inertia yang dipilih pada bootstrap. Jangan mencampur pola versi berbeda atau membuat wrapper abstrak tanpa kebutuhan. Perubahan route, props, redirect dan error harus diuji sebagai alur penuh browser.

## 9. Implementasi pustaka UI & animasi (DEC-098)

1. **Latar Belakang:** Gunakan utilitas CSS dari **Feral UI Gradients** (`https://feralui.dev/gradients`) untuk mesh/radial backgrounds dan SVG data-URI dari **Patterncraft** (`https://patterncraft.store/`).
2. **Motion Primitives (`https://motion-primitives.com/`):** Terapkan komponen accordion, animated tabs, text reveal, dan modal dialog menggunakan Framer Motion. Pastikan `reducedMotion="user"` diaktifkan agar menghormati aksesibilitas.
3. **Komponen Taktil:** Seleksi elemen mikro (bouncy button, toggle switch) dari **Uiverse.io** (`https://uiverse.io/`) dengan konversi ke sintaks Tailwind CSS murni.
4. **Navigasi Inertia:** Terapkan pola **Transitions.dev** (`https://transitions.dev/`) untuk transisi antar-halaman yang halus tanpa lag.
5. **Generator Neat (`https://neat.rebase.pro/`):** Dilarang menyematkan script WebGL runtime. Hanya boleh menyematkan berkas hasil ekspor statis (WebP/SVG) atau video ringan loop (~200KB).
