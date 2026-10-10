# Instruksi Agen AI — UI/UX (Gemini/Antigravity)

## Misi

Bangun pengalaman pengguna TELAGA yang sederhana, konsisten, modern, ramah pengguna non-teknis, responsif dan berbahasa Indonesia. Gunakan React + TypeScript + Inertia, shadcn/ui dan Tailwind CSS dengan pendekatan **Playful Minimalist** (tipografi Nunito, squircle tokens, Soft Sky Blue + pastel baseline) serta 3 Pilar Visual (Feral Gradients, Patterncraft, Motion Primitives) sesuai DEC-098 dan DEC-099.

## Sebelum mengerjakan tugas

1. Baca `agents/AGENTS.md`, `agents/README.md` (jalur `docs/...` relatif terhadap `agents/`), dan `agents/tasklist.md` (bagian "Titik lanjut"). Setelah tiap task selesai, perbarui `tasklist.md` sebelum melapor. Fixture hanya boleh sementara di folder khusus dan sesuai kontrak (lihat `workflow.md`).
2. Baca PRD, rules, glossary, decision log (khususnya DEC-098 & DEC-099), information architecture, UI/UX direction, design system, frontend implementation guide, dan `02-backend/api-contract-conventions.md`.
3. Periksa route/props/komponen yang benar-benar ada; jangan mengarang API atau payload backend.
4. Tentukan state loading, empty, error, success, permission denied dan responsif.
5. Koordinasikan kebutuhan props/validasi backend secara eksplisit; jangan menyisipkan business logic di frontend.

## Larangan

- Jangan mengganti istilah bisnis yang sudah disepakati; diksi resmi adalah **Murid** atau **Siswa**, **DILARANG** menggunakan kata "Santri" (DEC-099).
- Jangan membuat menu pembelajaran yang ditunda seolah-olah sudah menjadi bagian V2.
- Jangan memakai mock data sebagai hasil produksi atau menampilkan data palsu setelah endpoint tersedia.
- Jangan membangun ulang komponen shadcn/ui tanpa kebutuhan yang jelas.
- Jangan menganggap menu tersembunyi sudah mengamankan data.
- Jangan mengirim seluruh objek backend ke browser jika ada data sensitif.
- Jangan membuat tombol inert atau mengklaim tindakan sukses sebelum respons backend berhasil.
- Jangan menambahkan framework/router terpisah tanpa keputusan baru.
- Jangan mengabaikan Dark Mode; pastikan komponen baru memakai token semantik Tailwind dan rapi saat class `.dark` aktif (DEC-099).
- Jangan menjalankan script WebGL live loop (seperti generator Neat live) di runtime klien karena menguras performa/baterai (DEC-098).

## Cara implementasi

- Pakai token design system squircle (`rounded-2xl`, `rounded-3xl`, `rounded-full`) dan tipografi Nunito (Plus Jakarta Sans untuk data numerik/tabel).
- Rujuk 7 URL pustaka referensi UI di `03-ui-ux/ui-ux-direction.md` Seksi 10 sebelum mendesain komponen baru (DEC-098).
- Gunakan label, validasi, helper text dan pesan error berbahasa Indonesia.
- Pertahankan input aman saat validation error.
- Hindari table layout yang tidak usable pada ponsel; sediakan **Quick-Action Mobile Card** untuk absensi dan tabungan guru di kelas (DEC-099).
- Form uang harus menampilkan nominal dan konsekuensi; cegah double submit dan lindungi dengan **Double Confirmation Dialog** (DEC-099).
- Aksesibilitas keyboard, focus, label, kontras, dialog dan reduced motion wajib diperhatikan.
- Semua data atau aksi harus menggunakan endpoint/props yang benar-benar tersedia dan tetap diotorisasi backend.

## Definition of Done

Halaman konsisten dengan IA, responsif, semua state utama tersedia, tidak ada tombol mati, TypeScript/build/lint yang relevan lulus, data sensitif tidak bocor, dan kebutuhan backend yang belum ada dicatat sebagai kontrak/tugas—bukan ditutupi dengan mock permanen.
