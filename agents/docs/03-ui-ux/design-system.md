# Design System TELAGA v2

Dokumen ini menentukan aturan desain, bukan kode visual final. Gemini/Antigravity perlu membangun token dan komponen yang konsisten sebelum membuat banyak halaman.

## 1. Prinsip

1. Konsistensi lebih penting daripada membuat tiap layar tampak unik.
2. Gunakan komponen shadcn/ui sebagai fondasi dan modifikasi melalui token/variant yang terkontrol.
3. Tailwind CSS dipakai konsisten; hindari style inline acak atau angka spacing yang tidak memiliki alasan.
4. Semua komponen harus memiliki state interaktif dan aksesibilitas yang relevan.
5. Jangan membuat ulang primitive dasar jika shadcn/ui telah menyediakan komponen sesuai kebutuhan.

## 2. Token desain yang ditetapkan (DEC-098, DEC-099)

- **Tipografi:**
  - Font Utama (Body, UI, & Judul Ramah): **Nunito** (Google Fonts) — ramah anak, humanist membulat, hangat.
  - Font Sekunder (Data Numerik & Tabel): **Plus Jakarta Sans** — angka rapi, tegas, mudah dipindai di tabel keuangan dan NIS murid.
- **Radius & Bentuk (Squircle Tokens):**
  - `rounded-full`: Tombol aksi utama, lencana (*badge*), *chips*, dan tombol navigasi.
  - `rounded-2xl` (16px): Input field, kartu dashboard ringkas, dialog modal.
  - `rounded-3xl` (24px): Kartu program belajar di landing page, kartu santri/murid cepat, kartu metrik utama.
  - `rounded-4xl` (32px): Kontainer hero dan panel utama.
- **Warna & Tema Ganda (DEC-099):**
  - **Light Mode (Fokus Utama):**
    - `background`: `#FAF9F6` (Warm Milk / Off-White).
    - `surface / card`: `#FFFFFF`.
    - `primary`: `sky-600` (`#0284C7`), `primary-hover`: `sky-500` (`#0EA5E9`).
    - `foreground / text`: `slate-800` (`#1E293B`), `muted`: `slate-500`.
    - `pastel-yellow`: `#FEF08A` (aksen pengumuman, bintang ceria, status menunggu).
    - `pastel-mint`: `#D1FAE5` (aksen sukses, lunas, pilar keislaman/alam).
    - `pastel-peach`: `#FFEDD5` (aksen sorotan, pilar motorik).
  - **Dark Mode (Kesiapan Semantik):**
    - `background`: `#0F172A` (Slate 900).
    - `surface / card`: `#1E293B` (Slate 800).
    - `primary`: `sky-500` (`#0EA5E9`).
    - `foreground / text`: `slate-100` (`#F1F5F9`), `muted`: `slate-400`.
    - `border`: `slate-700` (`#334155`).
  - **Mekanisme Rebranding Logo:** Seluruh token warna di atas adalah *initial baseline* yang diisolasi dalam variabel CSS (`app.css`) dan `tailwind.config.js`. Ketika proses rebranding logo resmi RA Al Kautsar selesai, token dasar ini siap dievaluasi dan diselaraskan tanpa merombak struktur komponen.
- **Spacing:** Gunakan skala utilitas Tailwind konsisten (kelipatan 4px: `p-2`, `p-4`, `p-6`).
- **Shadow:** Lembut dan berwarna pastel (*soft colored shadows*, mis. `shadow-sky-100`), bukan bayangan hitam pekat.
- **Motion:** Singkat, transisi halus, dan wajib menghormati `prefers-reduced-motion`.

## 3. Komponen dasar

Bangun atau standarkan berbasis *shadcn/ui*:
- **Button Variants:** Primary pill, secondary, outline, ghost, icon button, dan **Tactile/Playful Button** (efek bouncy halus `hover:scale-105 active:scale-95`).
- **Input & Select:** Label permanen di atas, helper text, error state semantik, select/combobox berbasis Radix.
- **Form Field:** Field kelompok ramah dengan penanda wajib/opsional yang konsisten.
- **Badge / Pill Tag:** Lencana status warna pastel (Lunas, Menunggu Verifikasi, Revisi, Diterima, Ditolak).
- **Table / Data List:** Data table berbasis TanStack Table, server-side pagination, sorting, dan filter bar.
- **Dialog / Double Confirmation Modal (DEC-099):** Modal konfirmasi dua tahap menggunakan *Motion Primitives* untuk aksi transaksi keuangan dan publikasi rapor.
- **Quick-Action Mobile Card (DEC-099):** Kartu kehadiran dan setoran tabungan harian murid khusus layar ponsel guru.
- **Tabs, Breadcrumb, Sidebar Navigation:** Sidebar collapsible *shadcn-admin* yang dapat berubah menjadi drawer di ponsel.
- **Toast / Notification & Skeleton Loading:** Loading spinner ceria dan skeleton loader yang tidak menyebabkan layout shift.

## 4. Status color

Warna status harus punya makna konsisten, contoh konseptual:
- sukses/diterima/lunas: success (`pastel-mint` / text `emerald-800`);
- menunggu/draf/perlu diproses: warning (`pastel-yellow` / text `amber-900`);
- ditolak/gagal: destructive (`rose-100` / text `rose-800`);
- informasi: info (`sky-100` / text `sky-800`);
- dinonaktifkan/diarsipkan: muted (`slate-100` / text `slate-700`).

Pastikan kontras dan teks status tetap terbaca tanpa mengandalkan warna semata (sertakan ikon atau teks yang jelas).

## 5. Form validation

Gunakan satu pola untuk kesalahan server-side dan client-side. Server tetap sumber validasi terakhir. Komponen form membaca respons validasi Inertia/Laravel tanpa menggandakan aturan bisnis yang kompleks di frontend.

## 6. Kualitas UI

- Keyboard focus terlihat.
- Ukuran target sentuh memadai (minimal 44x44px di mobile).
- Label terkait input secara semantik.
- Dialog mempunyai fokus yang benar dan dapat ditutup sesuai aturan.
- Tabel dapat dipakai dengan keyboard bila komponen mendukung.
- Kontras memenuhi target WCAG 2.1 tingkat AA (DEC-042).
- Tidak ada komponen dekoratif yang menghalangi pekerjaan utama.

## 7. Integrasi 3 pilar visual & referensi (DEC-098)

1. **Latar Belakang & Gradasi:** Gunakan **Feral UI Gradients** (`https://feralui.dev/gradients`) untuk mesh/radial gradient murni CSS/SVG.
2. **Tekstur & Pola Latar Belakang:** Gunakan **Patterncraft** (`https://patterncraft.store/`) untuk pola SVG geometris/polka-dots/waves ringan di belakang kartu program dan hero.
3. **Interaksi Mikro & Dialog:** Gunakan **Motion Primitives** (`https://motion-primitives.com/`) berbasis Framer Motion untuk accordion FAQ, tabs transition, dan modal konfirmasi ganda.
4. **Elemen Taktil Ceria:** Adopsi cuplikan tombol empuk dari **Uiverse.io** (`https://uiverse.io/`) dengan seleksi bernuansa pastel ramah anak.
5. **Catatan Kinerja:** Hindari menjalankan generator WebGL live seperti **Neat** (`https://neat.rebase.pro/`) secara langsung di runtime; ekspor sebagai aset statis bila diperlukan.
