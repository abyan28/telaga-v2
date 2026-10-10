# Laporan Audit Dokumentasi TELAGA v2

- Tanggal audit: 9 Oktober 2026
- Objek: seluruh dokumen Markdown di `D:\Laravel\Telaga\agents` (39 file)
- Status laporan: final untuk audit awal (pra-implementasi)
- Dokumen sumber: **tidak diubah** (bukti hash di bagian 8)

## 0. Cakupan dan batasan audit

1. Seluruh 39 file `.md` di `agents/` dibaca penuh, bukan hanya nama file atau ringkasan.
2. Folder `referensi/` **tidak diperiksa**, sesuai instruksi pemilik untuk menghemat kuota. Ini mencakup `LAPORAN CAPAIAN PEMBELAJARAN ANAK(2).docx`. Temuan yang mungkin sebagian terjawab oleh sumber itu diberi tanda ⚑.
3. Stack yang ditetapkan (Laravel 13, Inertia, React + TypeScript, shadcn/ui, Tailwind CSS, MySQL) diperlakukan sebagai keputusan final. Tidak ditemukan masalah teknis konkret yang membenarkan penggantian, sehingga tidak ada usulan mengganti stack.
4. Tidak ada kebutuhan baru yang dikarang. Bila aturan bisnis belum ditetapkan, laporan hanya mencatat kekosongannya dan meminta keputusan pemilik.
5. Bila ada konflik antardokumen, semua sumber dicatat dan tidak ada yang dipilih secara diam-diam.

Fakta terukur yang dipakai:

| Fakta | Nilai | Sumber |
|---|---|---|
| Jumlah aturan bisnis | 137 aturan `BR-*` | `rules.md` |
| Dokumen lain yang merujuk ID `BR-*` | 0 | pencarian seluruh `agents/` |
| Baris matriks keterlacakan | 25 | `requirements-traceability.md` |
| Entitas pada data dictionary | 65, semuanya tanpa tipe data, nullability, atau constraint | `data-dictionary.md` |

---

## 1. Ringkasan eksekutif

**Kesimpulan: dokumentasi belum siap untuk implementasi penuh.** Fondasinya kuat dan arahnya konsisten. Kekurangannya terpusat pada lapisan data dan pada beberapa keputusan yang masih menggantung.

**Yang sudah baik:**
- Stack, modular monolith, dan MySQL konsisten di semua dokumen.
- Parameter OTP (6 digit, 10 menit, 5 percobaan, jeda 60 detik) identik di lima dokumen.
- Label bisnis yang disepakati (Riwayat Penempatan Siswa, Pelaksanaan Pembelajaran) dipakai konsisten.
- Daftar modul yang ditunda dan yang di luar cakupan konsisten di semua dokumen yang menyebutnya.
- Prinsip ledger tak dapat diubah, berkas privat, komunikasi satu arah, dan otorisasi di backend ditegaskan berulang tanpa kontradiksi.
- Semua file terdaftar di `INDEX.md`.
- Dokumen sendiri jujur menandai bagian yang belum final.

**Yang menghambat:**

| Prioritas | Jumlah | Intisari |
|---|---:|---|
| Kritis | 2 | Strategi identitas `orang`/akun belum ada (X-01). Data dictionary belum field-level untuk 65 entitas (X-02). |
| Tinggi | 9 | Konvensi penamaan DB bentrok dengan kerangka Laravel (X-03). Urutan tahap melanggar dependensi (X-04). Data calon anak PPDB tidak punya tempat (X-05). Biaya pendaftaran vs tagihan (X-06). Verifikasi dokumen kontradiktif (X-07). Status setoran tabungan bentrok (X-08). Katalog permission belum ada (X-09). Maker-checker belum diputuskan (X-10). Verifikasi hubungan wali–anak kabur (X-11). |
| Sedang | 18 | Lifecycle status, absensi, model rapor, entitas yang hilang, model keuangan, ERD, auth, NFR, laporan, IA, kontrak FE–BE, keterlacakan, sinkronisasi roadmap, open decisions, dan 3 temuan satu-file. |
| Rendah | 6 | Terminologi, presedensi, decision-log, kebersihan dokumen, dan 2 temuan satu-file. |
| **Total** | **35** | 30 lintas dokumen (X-01..X-30) dan 5 satu-file (D-01..D-05). |

**Dampak praktis:**
- **Tahap 0 (bootstrap)** dapat dimulai bila konvensi penamaan diputuskan (X-03) dan pemeriksaan kompatibilitas hosting minimal dilakukan.
- **Tahap 1 (identitas dan keamanan)** belum dapat dimulai dengan aman sebelum X-01, X-02 (subset), X-09, X-12 (akun), dan X-19 diselesaikan.
- **Tahap 2 (PPDB)** dan seterusnya juga memiliki prasyarat. Daftar lengkap ada di bagian 7.
- **17 butir keputusan pemilik proyek** tercantum di bagian 5. Sebagian besar menentukan bentuk skema, jadi sebaiknya diputuskan sebelum modul terkait dirancang.

---

## 2. Inventaris dokumen (39 file)

Semua berstatus **dibaca penuh**. Kolom ID merujuk temuan di bagian 3 dan 4.

### 2.1 Pintu masuk

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `AGENTS.md` | Instruksi global bagi semua agen: urutan sumber kebenaran, aturan arsitektur, larangan | semua dokumen; `decision-log`, `prd`, `rules` | X-28, X-30 | Siap setelah perbaikan |
| `README.md` | Gambaran paket, stack, status keputusan, aturan penting | `INDEX`, `AGENTS` | X-02 (pengakuan "bukan SQL final"), X-30 | Siap setelah perbaikan |
| `docs/INDEX.md` | Indeks 36 dokumen | semua | X-25, X-30 | Siap setelah perbaikan |

### 2.2 `00-fondasi`

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `prd.md` | Kebutuhan dan scope produk, role, modul, kriteria keberhasilan | `rules`, `feature-catalog`, `roadmap` | D-01, X-20, X-21, X-22 | Siap setelah perbaikan |
| `rules.md` | 137 aturan bisnis lintas modul | `prd`, `data-dictionary`, `workflows` | D-02, X-08, X-12, X-13, X-16, X-17, X-19 | Siap setelah perbaikan |
| `decision-log.md` | 26 keputusan (DEC-001..026) | `AGENTS`, `open-decisions` | X-29, X-03 | Siap setelah perbaikan |
| `glossary.md` | Kamus istilah | `rules`, `prd` | X-27 | Siap setelah perbaikan |
| `roadmap.md` | Tahapan produk 0–7 | `implementation-plan`, `feature-catalog` | X-04, X-25, X-26 | Belum siap |
| `feature-catalog.md` | Inventaris fitur inti, ditunda, di luar cakupan | `prd`, `roadmap` | X-30 | Siap |

### 2.3 `01-arsitektur`

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `domain-architecture.md` | Batas domain, entitas `orang`, lapisan aplikasi | `erd`, `data-dictionary` | X-01, X-14 | Siap setelah perbaikan |
| `erd.md` | ERD logis (3 diagram Mermaid) | `data-dictionary` | X-18, X-07 | Belum siap |
| `data-dictionary.md` | 65 entitas dan field logis | `erd`, `database-conventions` | X-01, X-02, X-05–X-08, X-13–X-16 | Belum siap |
| `status-lifecycle.md` | Status dan transisi 8 objek | `rules`, `workflows` | X-12, X-08 | Siap setelah perbaikan |
| `workflows.md` | 9 alur lintas modul | `rules`, `status-lifecycle` | X-04–X-06, X-17, X-19 | Siap setelah perbaikan |
| `rapor-template-spec.md` | Spesifikasi LCPA dan snapshot | `reports-and-pdf` | X-14, X-13 | Belum siap (hanya untuk modul Rapor) |

### 2.4 `02-backend`

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `backend-guide.md` | Kontrak kerja agen backend | semua dokumen backend | X-27, X-28 | Siap |
| `database-conventions.md` | Konvensi MySQL, unique, invarian | `data-dictionary` | X-03, D-03, X-13, X-17 | Siap setelah perbaikan |
| `auth-rbac.md` | Autentikasi, OTP, role, scope data | `rules`, `permission-matrix` | X-01, X-09, X-11, X-19 | Siap setelah perbaikan |
| `permission-matrix.md` | Matriks role–kapabilitas (rancangan awal) | `auth-rbac` | X-09, X-10, X-22 | Belum siap |
| `security-baseline.md` | Baseline keamanan dan checklist uji | `testing-strategy` | X-19, X-24 | Siap setelah perbaikan |
| `documents-and-storage.md` | Dokumen, berkas, media, storage | `data-dictionary` | X-07, X-15 | Siap setelah perbaikan |
| `email-notifications-queue.md` | Email, notifikasi, queue | `deployment-runbook` | D-04 | Siap setelah perbaikan |
| `reports-and-pdf.md` | Rapor, snapshot, PoC PDF | `rapor-template-spec` | D-05, X-14 | Siap setelah perbaikan |
| `testing-strategy.md` | Strategi dan prioritas uji | `rules`, `security-baseline` | X-24 | Siap setelah perbaikan |
| `deployment-runbook.md` | Rancangan runbook deployment | `open-decisions` | X-04, D-04 | Draf sesuai desain; siap setelah hosting dipilih |
| `backup-and-recovery.md` | Cakupan backup dan uji restore | `open-decisions` | X-26 | Draf sesuai desain; siap setelah RPO/RTO diputuskan |

### 2.5 `03-ui-ux`

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `information-architecture.md` | Navigasi internal, portal wali, halaman publik | `permission-matrix` | X-22 | Siap setelah perbaikan |
| `ui-ux-direction.md` | Arah visual, bahasa, dashboard, formulir | `design-system` | X-21 | Siap setelah perbaikan |
| `design-system.md` | Aturan token dan komponen | `frontend-implementation` | X-20 | Siap setelah perbaikan |
| `frontend-implementation.md` | Panduan React/Inertia/TypeScript | `uiux-agent` | X-23 | Siap setelah perbaikan |
| `accessibility-and-ui-states.md` | Aksesibilitas dan state UI | `design-system` | X-20 | Siap setelah perbaikan |

### 2.6 `04-ai-agents`

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `backend-agent.md` | Instruksi agen Claude/Codex | `AGENTS`, `backend-guide` | X-28 | Siap |
| `uiux-agent.md` | Instruksi agen Gemini/Antigravity | `AGENTS`, `frontend-implementation` | X-23 | Siap |
| `workflow.md` | Alur kerja kolaborasi antaragen | `task-template` | X-23, X-28 | Siap setelah perbaikan |
| `task-template.md` | Template tugas per fitur | `workflow` | X-24 | Siap setelah perbaikan |

### 2.7 `05-perencanaan`

| File | Fungsi | Terkait ke | Temuan | Kesiapan |
|---|---|---|---|---|
| `implementation-plan.md` | Tahap 0–8 beserta gerbang | `roadmap` | X-04, X-25 | Belum siap |
| `open-decisions.md` | 18 keputusan terbuka (OD-01..18) | `decision-log`, `roadmap` | X-26 | Belum siap |
| `acceptance-checklist.md` | Checklist kesiapan produksi | `deployment-runbook` | X-24 | Siap (sebagai checklist akhir) |
| `requirements-traceability.md` | Matriks 25 baris | `rules`, desain | X-24 | Belum siap |

### 2.8 Dokumen yang hilang, duplikat, atau tidak lengkap

- **Hilang:** katalog permission lengkap, katalog laporan/dashboard, konvensi kontrak backend–frontend, acceptance criteria per modul, kebutuhan nonfungsional, spesifikasi OTP/pengaturan sebagai entitas. Lihat X-09, X-15, X-20, X-21, X-23, X-24.
- **Duplikat/tumpang tindih:** `roadmap.md` dan `implementation-plan.md` memuat tahapan yang berbeda (X-25). Daftar "ditunda" diulang di ≥6 dokumen (konsisten saat ini, X-30).
- **Tidak lengkap:** data dictionary, ERD, matriks permission, open-decisions, matriks keterlacakan.

---

## 3. Temuan per dokumen

Temuan lintas dokumen ditulis lengkap sekali di bagian 4. Bagian ini memuat temuan yang murni milik satu file. Untuk peta file ke ID, lihat tabel di bagian 2.

### D-01 · Sedang · `prd.md`
- **Bagian:** seluruh dokumen; §9 Kriteria keberhasilan.
- **Bukti:** PRD tidak memuat ID kebutuhan maupun acceptance criteria per modul. §9: "Fitur utama bekerja pada mobile dan desktop."
- **Masalah dan dampak:** kriteria keberhasilan tidak terukur dan tidak dapat dirujuk dari uji atau matriks keterlacakan. Agen tidak dapat menautkan kode ke kebutuhan PRD. Hubungan ke 137 aturan hanya implisit.
- **Rekomendasi:** beri ID kebutuhan pada tiap modul PRD (mis. `PRD-PPDB-01`), tambahkan kriteria terukur untuk "fitur utama" dan "mobile dan desktop", dan hubungkan ke aturan BR.
- **Keputusan pemilik:** tidak untuk ID. Ya untuk definisi "fitur utama" dan perangkat target (lihat X-20).

### D-02 · Sedang · `rules.md`
- **Bagian:** BR-AKD-007, BR-SIS-004, BR-TAB-008, BR-FIN-005, BR-FIN-007, BR-RAP-004, BR-AUTH-015.
- **Bukti:**
  - BR-AKD-007: "tidak boleh tumpang tindih pada cakupan aturan yang sama"
  - BR-SIS-004: "tanggal/sesi sesuai model"
  - BR-TAB-008: "kandidat: Menunggu Disetor, Diterima, Ada Selisih, Dibatalkan"
  - BR-FIN-005: "syarat pelunasan/verifikasi terpenuhi"
  - BR-FIN-007: "Nominal SPP awal yang pernah ditentukan adalah Rp250.000"
  - BR-RAP-004: "periode sesuai"
- **Masalah dan dampak:** frasa menggantung membuat aturan tidak dapat diuji, dan agen dilarang menebak (`AGENTS.md`). `rules.md` juga tidak memberi status keputusan per aturan (DISETUJUI/REKOMENDASI/TERBUKA seperti definisi di `README.md`), jadi tidak terlihat aturan mana yang baru kandidat.
- **Rekomendasi:** ganti frasa menggantung dengan definisi konkret setelah keputusan terkait diambil (lihat X-08, X-13, X-16, X-17). Tambahkan kolom status per aturan.
- **Keputusan pemilik:** ya (mengikuti X-08, X-13, X-16, X-17).

### D-03 · Sedang · `database-conventions.md`
- **Bagian:** §4 Unik dan index; §6 Invariant utama.
- **Bukti:** daftar calon constraint mencakup email pengguna, kode role/permission, nomor pendaftaran, nomor surat, absensi, alokasi. Daftar invarian utama tidak memuat keunikan tagihan atau keunikan pendaftaran per calon.
- **Masalah dan dampak:** BR-FIN-015 ("Generate tagihan bulanan harus idempoten") dan BR-PPDB-003 ("Satu pendaftaran merepresentasikan satu calon anak dalam satu periode pendaftaran") memerlukan constraint keunikan, tetapi tidak tercatat sebagai invarian. Risiko tagihan ganda dan pendaftaran ganda.
- **Rekomendasi:** tambahkan ke §4/§6: keunikan tagihan per (siswa, jenis tagihan, periode) dan keunikan pendaftaran per (identitas calon, periode pendaftaran). Rumusan akhir bergantung pada X-05 dan X-01.
- **Keputusan pemilik:** tidak (turunan dari keputusan lain).

### D-04 · Rendah · `email-notifications-queue.md`, `deployment-runbook.md`
- **Bagian:** email-notifications-queue §5; deployment-runbook §1 dan §5; status-lifecycle §8.
- **Bukti:** status pengumuman "`terjadwal`" (lifecycle §8). Dokumen email/deployment hanya membahas cron untuk pemrosesan queue.
- **Masalah dan dampak:** pengumuman terjadwal membutuhkan scheduler yang berjalan. Di shared hosting ini belum tentu tersedia, dan tidak masuk checklist pemeriksaan hosting.
- **Rekomendasi:** tambahkan "scheduler/cron untuk tugas terjadwal" ke daftar pemeriksaan hosting (runbook §1, OD-03, OD-06).
- **Keputusan pemilik:** tidak.

### D-05 · Rendah · `reports-and-pdf.md`
- **Bagian:** §4 Proof of concept PDF.
- **Bukti:** kriteria PoC semuanya khusus LCPA. Data dictionary `surat` memuat "file keluaran". `backup-and-recovery.md` §1 menyebut "PDF rapor/surat".
- **Masalah dan dampak:** pemilihan mesin PDF (DEC-023) hanya diuji pada LCPA, padahal surat juga menghasilkan berkas keluaran dengan kebutuhan berbeda. Format keluaran surat (PDF atau lainnya) tidak ditetapkan.
- **Rekomendasi:** perluas cakupan PoC atau nyatakan bahwa keluaran surat diputuskan terpisah.
- **Keputusan pemilik:** ya (format keluaran surat).

---

## 4. Temuan lintas dokumen

### 4.1 Kritis

#### X-01 · Kritis · Identitas `orang`, pencocokan, dan akun belum didefinisikan
- **Dokumen:** `data-dictionary.md` §1 dan §7; `domain-architecture.md` §3; `rules.md` BR-ID-002, BR-ID-007, BR-AUTH-011; `open-decisions.md` OD-11.
- **Bukti:**
  - `pengguna`: "id_orang unik/nullable sesuai model"
  - dictionary §7: "Apakah `email` disimpan pada `orang`, `pengguna`, atau keduanya"
  - domain-architecture §3: orang yang guru sekaligus wali "tidak dibuat menjadi dua orang"
  - BR-AUTH-011: "Respons login/pemulihan tidak membocorkan apakah email terdaftar"
  - OD-11: "Belum final"
- **Masalah dan dampak:** wali mendaftar sendiri (BR-ID-007), padahal orangnya mungkin sudah ada sebagai pegawai atau wali lain. Tidak ada aturan penautan, dan penautan otomatis berdasarkan email atau nama berisiko menggabungkan orang yang salah. Tanpa aturan, hasilnya orang ganda (melanggar BR-ID-001/002) atau tautan akun yang salah. `id_orang nullable` juga bertentangan dengan prinsip PRD bahwa satu orang maksimal satu akun, karena akun tanpa orang tidak punya identitas dasar. Ini fondasi Tahap 1 dan memengaruhi semua modul yang merujuk `orang`.
- **Rekomendasi:** tetapkan (a) apakah akun selalu terkait satu `orang`, (b) atribut pencocokan dan proses tinjauan manual (OD-11 sudah menyebut "hindari auto-merge dari nama saja"), (c) tempat penyimpanan email dan perilaku bila dua wali memakai satu email, (d) alur untuk guru yang juga wali. Catat sebagai DEC baru.
- **Keputusan pemilik:** **ya**.

#### X-02 · Kritis · Data dictionary belum field-level untuk 65 entitas
- **Dokumen:** `data-dictionary.md` (header, §7); `README.md`; `erd.md` §5; `open-decisions.md` OD-10.
- **Bukti:**
  - dictionary header: "belum merupakan definisi SQL final. Sebelum coding skema, lengkapi tipe data, nullability, panjang, default, index, unique key, constraint dan kebijakan histori"
  - `orang`: "nama_lengkap, tanggal_lahir (bila diperlukan), data identitas terkontrol, status"
  - OD-10: "Belum final"
- **Masalah dan dampak:** semua 65 entitas hanya berupa daftar nama field logis tanpa tipe, nullability, panjang, default, unique, index, atau aturan `ON DELETE`. Atribut dasar yang dibutuhkan alur lain juga tidak ada. Contoh: `orang` tidak memiliki jenis kelamin, alamat, atau kontak. Rapor dan PPDB membutuhkan identitas anak, dan `profil_wali` hanya "data kontak/relasi yang dibutuhkan". Migrasi pertama tidak dapat ditulis tanpa menebak, sedangkan `AGENTS.md` dan `workflow.md` melarang menebak.
- **Rekomendasi:** finalisasi per tahap, bukan sekaligus. Mulai dari entitas Tahap 1 (`orang`, `pengguna`, `role`, `permission`, `pengguna_role`, `role_permission`, `audit_log`, tabel OTP dan pengaturan dari X-15). Gerbang tiap tahap: dictionary entitas terkait lengkap sebelum migrasi ditulis.
- **Keputusan pemilik:** **ya** (khususnya atribut data pribadi minimal yang boleh dikumpulkan, mengingat prinsip data minimal pada `security-baseline.md` §4).

### 4.2 Tinggi

#### X-03 · Tinggi · Konvensi penamaan DB Indonesia vs kerangka Laravel
- **Dokumen:** `database-conventions.md` §1–§2; `data-dictionary.md` (Konvensi, §1); `decision-log.md` DEC-024; `open-decisions.md` OD-08; `implementation-plan.md` Tahap 0.
- **Bukti:**
  - database-conventions §1: "Nama tabel dan kolom menggunakan Bahasa Indonesia `snake_case`. PK memakai `id_<entitas>`." Juga "Timestamp dan tanggal bernama spesifik."
  - dictionary: menyebut `created_at`, `updated_at`, `created_by` dan `email_verified_at` (bahasa Inggris)
  - database-conventions §2: "Tipe PK final ditetapkan saat bootstrap setelah kondisi MySQL hosting diperiksa."
  - DEC-024 (Spatie): "Verifikasi kompatibilitas dan naming integrasi" (REKOMENDASI)
- **Masalah dan dampak:** Tahap 0 memasang starter kit resmi Laravel, dan kerangka kerja, autentikasi, session, queue, notifikasi, serta paket RBAC membawa konvensi nama bawaan masing-masing (mis. `users`, kolom `id`, `jobs`, `sessions`). Dokumen tidak memberi aturan untuk tabel kerangka dan tidak ada keputusan di decision-log. Dokumen juga bertentangan dengan dirinya sendiri: konvensi mewajibkan nama Indonesia dan penanda waktu "bernama spesifik", sedangkan dictionary memakai `created_at`/`email_verified_at`. Semua migrasi dan model bergantung pada keputusan ini, dan mengubahnya belakangan berarti menulis ulang. Catatan: nama bawaan kerangka dan paket di atas **belum diverifikasi** terhadap versi yang akan dipasang. Verifikasi itu bagian dari rekomendasi.
- **Rekomendasi:** putuskan sebelum Tahap 0 selesai dan catat sebagai DEC baru: (a) apakah nama tabel/PK/timestamp bahasa Indonesia berlaku untuk tabel kerangka dan paket, (b) cara menyesuaikan model autentikasi dan paket RBAC, (c) aturan tipe PK. Periksa juga bagaimana autentikasi bawaan starter kit direkonsiliasi dengan OTP dan alur verifikasi yang didefinisikan dokumen.
- **Keputusan pemilik:** **ya**.

#### X-04 · Tinggi · Urutan tahap melanggar dependensi
- **Dokumen:** `implementation-plan.md` (Tahap 1, 2, 4, 5, 8); `roadmap.md` (Tahap 2, 5, 7); `workflows.md` §1; `database-conventions.md` §7; `erd.md` §5.
- **Bukti:**
  - PPDB (Tahap 2): "unggah dokumen dan histori status." Modul Dokumen: Tahap 5 ("Dokumen/versi/verifikasi dan relasi eksplisit").
  - Tahap 1: "verifikasi email OTP 6 digit". Email, notifikasi, queue: Tahap 5.
  - workflows §1.6 "pembayaran bila biaya pendaftaran diwajibkan" dan §1.10 "Wali menerima status melalui portal dan email". Keuangan: Tahap 4. Notifikasi: Tahap 5.
  - Uji hosting: Tahap 8 (rencana) dan Tahap 7 (roadmap). database-conventions §7: "Jangan mengasumsikan versi MySQL, dukungan CHECK, akses CLI". erd §5: "jangan menggunakan fitur MySQL yang belum terverifikasi."
- **Masalah dan dampak:** (a) PPDB tidak dapat diselesaikan sebelum modul dokumen/storage ada. (b) OTP tidak dapat berfungsi tanpa email dan queue. (c) PPDB bergantung pada pembayaran dan notifikasi. (d) Keputusan skema (tipe PK, constraint, versi MySQL) dibuat sebelum hosting diketahui dan baru diuji di tahap terakhir. Agen yang mengikuti rencana akan terhenti atau membuat solusi sementara yang kelak dibongkar.
- **Rekomendasi:** (a) tarik kemampuan dasar dokumen/storage, email/queue (termasuk OTP), dan notifikasi dasar ke tahap fondasi. (b) putuskan nasib pembayaran pendaftaran (X-06). (c) lakukan pemeriksaan kompatibilitas hosting minimal (versi PHP dan MySQL, cron, queue, scheduler, storage) sebelum Tahap 0/1, tanpa menunggu PoC penuh. (d) tata ulang tahap dan tetapkan satu dokumen tahapan (X-25).
- **Keputusan pemilik:** **ya**.

#### X-05 · Tinggi · Data calon anak PPDB tidak punya tempat penyimpanan
- **Dokumen:** `workflows.md` §1; `data-dictionary.md` §3 `pendaftaran`; `rules.md` BR-PPDB-003, BR-PPDB-007, BR-PPDB-008.
- **Bukti:**
  - workflows §1.4: "Wali mengisi data calon anak"
  - §1.8: "pembentukan/penautan `orang`, `siswa` dan hubungan wali" terjadi ketika diterima
  - dictionary `pendaftaran`: "id_orang calon bila sudah dibuat/ditautkan" tanpa field data anak
  - BR-PPDB-008: penolakan/penarikan "tidak menghapus histori"
- **Masalah dan dampak:** sebelum diterima, `orang` calon belum dibuat. Data yang diisi wali (nama, tanggal lahir, dst.) tidak punya tabel. Dua jalan keluar, yaitu membuat `orang` untuk setiap pelamar atau menaruh field di `pendaftaran`, masing-masing bertentangan dengan salah satu dokumen. Pencegahan pendaftaran ganda untuk anak yang sama (BR-PPDB-003) juga tidak dapat ditegakkan tanpa identitas calon.
- **Rekomendasi:** putuskan apakah data calon disimpan di `pendaftaran` (disalin ke `orang` saat diterima) atau `orang` dibuat sejak pengajuan. Definisikan field minimal dan kunci keunikan.
- **Keputusan pemilik:** **ya**.

#### X-06 · Tinggi · Biaya pendaftaran PPDB vs `tagihan.id_siswa`
- **Dokumen:** `workflows.md` §1.6; `data-dictionary.md` §4 `tagihan`, `pembayaran`; `rules.md` BR-PPDB-007; `decision-log.md`; `open-decisions.md`.
- **Bukti:**
  - workflows §1.6: "pembayaran bila biaya pendaftaran diwajibkan"
  - `tagihan`: field `id_siswa` (tidak disebut opsional)
  - `pembayaran`: "id_siswa/pembayar sesuai aturan"
  - BR-PPDB-007: penerimaan membentuk/mengaitkan profil siswa
- **Masalah dan dampak:** ada atau tidaknya biaya pendaftaran tidak diputuskan di DEC maupun OD, padahal alurnya bersyarat pada itu. Bila ada, tagihan harus ada sebelum `siswa` ada, tetapi `tagihan` terikat pada `siswa`. Modul Keuangan juga dikerjakan setelah PPDB (X-04).
- **Rekomendasi:** putuskan apakah ada biaya pendaftaran. Bila ada, tentukan entitas penagihan untuk calon (tagihan terikat `pendaftaran` atau tidak) dan metode pembayarannya. Bila tidak, hapus rujukan bersyarat di workflows §1.6 dan PRD.
- **Keputusan pemilik:** **ya**.

#### X-07 · Tinggi · Granularitas verifikasi dokumen kontradiktif
- **Dokumen:** `data-dictionary.md` §5; `erd.md` §3; `rules.md` BR-DOK-008; `status-lifecycle.md` §6; `documents-and-storage.md` §2; `testing-strategy.md`.
- **Bukti:**
  - dictionary `verifikasi_dokumen`: "id_versi_dokumen atau id_dokumen sesuai alur"
  - ERD: `DOKUMEN ||--o{ VERIFIKASI_DOKUMEN`
  - BR-DOK-008: "Verifikasi mencatat ... versi yang diperiksa"
  - lifecycle §6: "Keputusan verifikasi harus merujuk versi tertentu"
  - dictionary `versi_dokumen`: tidak memuat kolom status, sedangkan lifecycle §6 memberi status per versi (`diunggah`, `menunggu_verifikasi`, `disetujui`, `ditolak`, `kedaluwarsa`)
- **Masalah dan dampak:** ERD menghubungkan verifikasi ke dokumen, sedangkan aturan, lifecycle, dan uji mewajibkan versi. Status dan verifikasi ditaruh di tingkat dokumen atau versi tergantung dokumen yang dibaca, sehingga implementasi bisa memverifikasi dokumen setelah berkasnya diganti. PPDB (BR-PPDB-006) bergantung pada ini.
- **Rekomendasi:** tetapkan verifikasi selalu mengacu `versi_dokumen`. Tambahkan kolom status ke `versi_dokumen`, dan koreksi ERD serta dictionary. Tentukan juga apakah `dokumen.status` ada sebagai ringkasan.
- **Keputusan pemilik:** tidak (dokumen sudah condong ke versi; tinggal diselaraskan). Konfirmasi singkat dianjurkan.

#### X-08 · Tinggi · Setoran tabungan: status dan perlakuan selisih
- **Dokumen:** `rules.md` BR-TAB-005, BR-TAB-007, BR-TAB-008, BR-TAB-011; `status-lifecycle.md` §5; `workflows.md` §5; `data-dictionary.md` §4.
- **Bukti:**
  - BR-TAB-008: "kandidat: Menunggu Disetor, Diterima, Ada Selisih, Dibatalkan"
  - lifecycle §5: "`draf` → `menunggu_diterima` → (`diterima` atau `ada_selisih`) → `diposting`"
  - BR-TAB-005: "Saldo resmi berubah setelah setoran diterima/diposting"
  - workflows §5.5: "memposting mutasi tabungan untuk setiap rincian yang sah"
  - `detail_setoran_tabungan`: hanya "nominal, catatan selisih"; `setoran_tabungan`: "nominal usulan/nominal diterima sesuai desain"
- **Masalah dan dampak:** dua daftar status berbeda (`Menunggu Disetor` vs `menunggu_diterima`, `diposting` hanya di lifecycle). "Diterima/diposting" mencampur dua langkah yang di lifecycle terpisah. Yang terpenting: bila `ada_selisih`, tidak ada aturan nominal siswa mana yang diposting. Rincian per siswa hanya memiliki satu nominal, sedangkan selisih tercatat di tingkat batch. Tanpa aturan ini, posting saldo siswa bisa salah (lebih besar atau lebih kecil dari yang sebenarnya diterima).
- **Rekomendasi:** satukan daftar status. Tentukan apakah `diterima` dan `diposting` dua langkah. Putuskan kebijakan selisih: (a) batch ditolak sampai diselesaikan, (b) diposting per siswa sesuai nominal diterima, atau (c) lainnya. Tambahkan `nominal_diajukan` dan `nominal_diterima` ke rincian bila (b).
- **Keputusan pemilik:** **ya**.

#### X-09 · Tinggi · Katalog permission dan aturan role belum ada
- **Dokumen:** `permission-matrix.md`; `auth-rbac.md` §5–§6; `data-dictionary.md` `jenis_penugasan`; `rules.md` BR-TAB-003; `implementation-plan.md` Tahap 1.
- **Bukti:**
  - matriks: "Matriks ini adalah dasar discovery, bukan aturan backend final" (±15 baris memakai "Sesuai permission", "Sesuai izin", atau "Terbatas" tanpa definisi)
  - auth-rbac §5: "Permission list lengkap dibangun dari matriks modul dan tindakan"; hanya 5 contoh kode
  - auth-rbac §6: "Jabatan guru tidak secara otomatis sama dengan role Guru; aturan pemberian role harus eksplisit."
  - BR-TAB-003: "Guru/wali kelas dapat mencatat setoran"
  - `jenis_penugasan`: "kode, nama" tanpa nilai
  - gerbang Tahap 1: "skenario ... negative authorization lulus"
- **Masalah dan dampak:** tanpa daftar kode permission dan lingkup record per permission, uji otorisasi tidak dapat ditulis dan gerbang Tahap 1 tidak dapat diverifikasi. Tidak ada aturan kapan jabatan Guru memberi role Guru. Tidak jelas jenis penugasan mana (wali kelas, pendamping, dll.) yang membuka akses siswa atau hak setor tabungan, atau menjadi penanda tangan rapor ("Guru kelas/Wali kelas").
- **Rekomendasi:** hasilkan katalog `modul.objek.tindakan` lengkap dengan lingkup (semua, per kelas, milik sendiri, anak terhubung) dan role bawaan. Definisikan nilai `jenis_penugasan` dan hak yang diturunkan darinya. Tetapkan aturan pemberian role Guru.
- **Keputusan pemilik:** **ya** (nilai jenis penugasan, siapa boleh apa).

#### X-10 · Tinggi · Pemisahan tugas (maker–checker) dan pemegang keputusan belum diputuskan
- **Dokumen:** `permission-matrix.md`; `rules.md` BR-FIN-009, BR-FIN-010, BR-PPDB-005; `workflows.md` §6; `status-lifecycle.md` §7.
- **Bukti:**
  - matriks "Mencatat/verifikasi pembayaran": Bendahara ✓
  - "Memutuskan penerimaan": Admin Sekolah "Sesuai permission", Kepala RA "Sesuai kebijakan sekolah"
  - "Membuat dan menerbitkan rapor": Guru "Siswa/kelas yang ditugaskan", Kepala RA "Review/approve sesuai proses"
  - workflows §6 dan lifecycle §7: tidak ada langkah persetujuan Kepala RA
  - matriks "Langkah sebelum implementasi" butir 3: "Tentukan siapa boleh melakukan transisi status dan siapa yang menjadi maker/checker bila dibutuhkan."
  - BR-PPDB-005: "pengguna berwenang"
- **Masalah dan dampak:** orang yang sama dapat mencatat dan memverifikasi pembayaran tunai, dan pemegang keputusan penerimaan serta penerbitan rapor tidak ditetapkan. Rapor memuat konflik langsung: matriks menyiratkan persetujuan Kepala RA, workflow tidak. Ini menyangkut integritas keuangan dan keabsahan dokumen resmi.
- **Rekomendasi:** putuskan, per transisi sensitif (verifikasi pembayaran, penerimaan PPDB, penerbitan rapor, penerimaan setoran, penarikan tabungan), siapa yang boleh dan apakah pelaku harus berbeda dari pencatat. Perbarui matriks, workflows, dan lifecycle.
- **Keputusan pemilik:** **ya**.

#### X-11 · Tinggi · Verifikasi hubungan wali–anak belum jelas
- **Dokumen:** `rules.md` BR-ID-011, BR-SIS-003; `auth-rbac.md` §7; `data-dictionary.md` `hubungan_siswa_wali`, `pendaftaran_wali`; `open-decisions.md` OD-16; `workflows.md` §1.
- **Bukti:**
  - auth-rbac §7: "Wali hanya melihat anak yang hubungannya telah terverifikasi."
  - `hubungan_siswa_wali`: "status verifikasi"
  - OD-16: "Rincian perlu finalisasi — Tetapkan bukti, petugas pemeriksa, koreksi data dan alur sengketa"
  - `pendaftaran_wali`: "hubungan/persetujuan"
- **Masalah dan dampak:** dasar kontrol akses data anak (kesehatan, rapor, tagihan, dokumen) tidak dapat diimplementasikan karena tidak ada kriteria "terverifikasi". Tidak jelas apakah penerimaan PPDB otomatis memverifikasi wali yang mengajukan, siapa memverifikasi wali tambahan, dan apa yang terjadi bila ada sengketa. Salah desain berarti kebocoran data anak atau wali tidak bisa melihat anaknya.
- **Rekomendasi:** putuskan kapan hubungan terbentuk, siapa memverifikasi, bukti apa, dan alur koreksi atau sengketa. Tentukan akses wali selama masa pendaftaran (sebelum ada `siswa`).
- **Keputusan pemilik:** **ya**.

### 4.3 Sedang

#### X-12 · Sedang · Status lifecycle tidak lengkap dan tidak konsisten
- **Dokumen:** `status-lifecycle.md` §1–§7; `rules.md` BR-ID-009, BR-PPDB-008; `data-dictionary.md`.
- **Bukti:**
  - lifecycle §1: akun `menunggu_verifikasi` ("menunggu verifikasi email"). BR-ID-009: "Status akun, verifikasi email, dan status pendaftaran anak adalah tiga hal terpisah." Dictionary memakai `email_verified_at` terpisah dari "status akun".
  - §1: `ditangguhkan` = "login/aksi dibatasi sementara"; `dinonaktifkan` = "tidak dapat login". "Aksi dibatasi" tidak didefinisikan.
  - §2 teks: "Pengajuan yang ditarik atau dibatalkan membutuhkan alasan", tetapi daftar status tidak memuat `ditarik`/`dibatalkan`. `diajukan` dan `menunggu_pemeriksaan` tidak dibedakan.
  - §3: "Status final ... ditetapkan saat spesifikasi pembayaran detail diselesaikan."
  - §4: `belum_lunas` menyandang dua arti. `dibatalkan/diubah` bukan kode status.
  - §7: tidak jelas status dipegang `rapor` atau `rapor_versi`.
  - Entitas dengan kolom status tetapi tanpa lifecycle: `siswa`, `tahun_ajaran`, `periode_pendaftaran`, `kelas`, `riwayat_penempatan_siswa`, `mutasi_keuangan` ("status posting"), `transfer_keuangan`, `surat`, `agenda`, `dokumen`.
- **Masalah dan dampak:** kode status adalah kontrak lintas migrasi, kebijakan, dan UI. Daftar yang kabur menghasilkan enum berbeda antara backend dan UI, dan transisi yang tidak tervalidasi.
- **Rekomendasi:** finalisasi kode status per entitas dan matriks transisi (asal, tujuan, siapa, prasyarat) sebelum modul terkait dirancang. Pisahkan status akun dari verifikasi email sesuai BR-ID-009.
- **Keputusan pemilik:** ya untuk makna `ditangguhkan` dan status siswa/tahun ajaran. Lainnya teknis.

#### X-13 · Sedang · Model absensi dan kosakata status
- **Dokumen:** `rules.md` BR-SIS-004, BR-SIS-005; `data-dictionary.md` §3 `absensi_siswa`; `database-conventions.md` §4; `rapor-template-spec.md` Kehadiran; `testing-strategy.md`.
- **Bukti:**
  - BR-SIS-004: "tanggal/sesi sesuai model"
  - BR-SIS-005: "kombinasi siswa dan tanggal/sesi yang sama harus dicegah"
  - dictionary `absensi_siswa`: "id_siswa, id_kelas, tanggal, status hadir, catatan, id_pengguna pencatat" (tanpa sesi)
  - database-conventions §4: "kombinasi siswa, kelas, dan tanggal untuk absensi"
  - rapor: Kehadiran = Sakit, Izin, Tanpa Keterangan, dihitung "dalam rentang periode rapor dan aturan cut-off yang disepakati"
- **Masalah dan dampak:** harian atau per sesi tidak diputuskan. Unique key yang mencakup `id_kelas` memungkinkan dua catatan untuk siswa yang sama pada tanggal yang sama bila kelas berbeda, bertentangan dengan BR-SIS-005. Nilai status absensi tidak didefinisikan, sedangkan rapor membutuhkan tiga kategori tertentu. Aturan cut-off tidak tertulis di dokumen mana pun.
- **Rekomendasi:** putuskan granularitas absensi, daftar status dan pemetaannya ke kategori rapor, serta aturan cut-off. Revisi unique key sesuai granularitas.
- **Keputusan pemilik:** **ya**.

#### X-14 · Sedang · Model data dan proses rapor
- **Dokumen:** `domain-architecture.md` §1; `data-dictionary.md` §6; `erd.md` §3; `rapor-template-spec.md`; `reports-and-pdf.md` §5; `open-decisions.md` OD-12, OD-13.
- **Bukti:**
  - domain-architecture: "rapor_bagian (nama logis; bentuk akhir perlu dirinci)" tidak ada di dictionary/ERD
  - `rapor_versi`: "payload snapshot terstruktur atau child tables"
  - ERD §3: hanya `SISWA ||--o{ RAPOR`
  - spec "Refleksi Orang Tua": "Refleksi wali, jika diisi dari portal, harus terkait siswa/periode yang benar"
  - spec Pengesahan: "Cara tanda tangan ... belum ditetapkan"
  - spec: "Tingkat Ketercapaian" tanpa daftar nilai ⚑
  - spec Perkembangan: "Jika interpretasi 'normal' membutuhkan standar yang belum diputuskan"
- **Masalah dan dampak:** tidak ada tempat penyimpanan untuk isi draf (capaian, tingkat, narasi, ekstrakurikuler, refleksi, foto). Skala Tingkat Ketercapaian tidak tertulis, padahal ini kolom inti rapor ⚑. Refleksi Orang Tua dari portal tidak punya alur: tidak ada di PRD, matriks, atau portal wali. Waktu pengisiannya juga bentrok dengan snapshot yang tidak dapat diubah setelah terbit. Foto atau dokumentasi kegiatan tidak punya sumber data. Metode tanda tangan, standar pertumbuhan, dan ejaan DDTK masih terbuka (hanya DDTK ada di OD-13).
- **Rekomendasi:** modelkan entitas bagian rapor dan snapshot setelah PoC (sesuai OD-12). Putuskan skala Tingkat Ketercapaian ⚑, alur Refleksi Orang Tua, metode pengesahan, dan standar pertumbuhan. Tambahkan yang belum ada ke `open-decisions.md`.
- **Keputusan pemilik:** **ya**.

#### X-15 · Sedang · Entitas atau konsep yang dibutuhkan aturan tetapi tidak ada di dictionary/ERD
- **Dokumen:** `rules.md`; `data-dictionary.md`; `erd.md`; `workflows.md`; `information-architecture.md`; `security-baseline.md` §4.
- **Bukti dan dampak:**

| Kebutuhan | Rujukan | Kondisi |
|---|---|---|
| Penyimpanan OTP | BR-AUTH-002..008 | tidak ada tabel atau entitas |
| Pengaturan sistem dan profil sekolah | BR-FIN-007, BR-FIN-008 ("pengaturan yang dapat diaudit"); auth-rbac "dapat dikonfigurasi"; IA "Profil Sekolah / Pengaturan Umum" | hanya `pengaturan_website` ("konfigurasi website terbatas") |
| Persyaratan dokumen PPDB per periode | BR-PPDB-006 "Dokumen yang diwajibkan" | `jenis_dokumen` hanya "sensitivitas/akses default" |
| Penomoran surat dan nomor siswa | BR-DOK-006, workflows §7, OD-17 | tidak ada entitas penghitung atau konfigurasi |
| Target dan versi teks pengumuman | workflows §8.2 ("target yang sah"), §8.7 ("aturan histori") | `pengumuman` dan `penerima_pengumuman` tanpa target atau versi |
| Izin atau persetujuan publikasi | BR-PPDB-010, BR-CMS-002, BR-CMS-003, BR-PEG-006, BR-SIS-010; security-baseline §4 "persetujuan wali" | tidak ada flag atau entitas persetujuan pada `pegawai`, `periode_pendaftaran`, `portofolio`, atau data anak |
| Konteks surat | `surat` | tidak ada penerima atau konteks (hanya junction `dokumen_surat`) |

  Dampak: tiap kebutuhan akan diselesaikan agen secara ad hoc, dan bentuk datanya sulit diubah belakangan. Beberapa memengaruhi keamanan (OTP, persetujuan publikasi data anak).
- **Rekomendasi:** tambahkan entitas yang diperlukan ke dictionary dan ERD pada saat tahap terkait difinalisasi. OTP dan pengaturan dibutuhkan pada Tahap 1.
- **Keputusan pemilik:** ya untuk persetujuan publikasi, persetujuan data anak, dan format penomoran (OD-17).

#### X-16 · Sedang · Celah model keuangan dan tabungan
- **Dokumen:** `data-dictionary.md` §4; `rules.md` BR-FIN-002, BR-FIN-007, BR-FIN-008, BR-FIN-014, BR-FIN-016, BR-KEU-005, BR-KEU-008, BR-TAB-009; `erd.md` §2; `database-conventions.md` §6.
- **Bukti dan dampak:**
  1. **Pembayar vs siswa.** `pembayaran`: "id_siswa/pembayar sesuai aturan". BR-FIN-002 mengizinkan satu pembayaran dialokasikan ke beberapa tagihan, sedangkan tagihan terikat siswa dan PRD memungkinkan satu wali punya banyak anak. Pembayaran gabungan beberapa anak tidak dapat dimodelkan bila `id_siswa` wajib.
  2. **Reversal.** BR-FIN-014 dan BR-KEU-008 mewajibkan koreksi lewat reversal yang tertaut ke asal. Dictionary hanya menyediakan "referensi reversal" pada `mutasi_tabungan`, tidak pada `pembayaran`, `alokasi_pembayaran`, atau `mutasi_keuangan`.
  3. **Referensi mutasi.** BR-KEU-005 meminta FK eksplisit untuk sumber transaksi. Dictionary hanya menulis "referensi eksplisit yang relevan", dan ERD tidak memuat hubungan PEMBAYARAN–MUTASI_KEUANGAN.
  4. **Aturan tagihan.** BR-FIN-016: "periksa ... aturan tagihan". Tidak ada dokumen yang mendefinisikan aturan itu, dan tidak ada entitas aturan tagihan. Pertanyaan terbuka: apakah diskon, beasiswa, atau tagihan sebagian bulan berlaku. Dokumen tidak menyebutnya, jadi tidak diasumsikan.
  5. **Penarikan tabungan.** BR-TAB-009 menyebut workflow tercatat, tetapi tidak ada invarian bahwa penarikan tidak boleh melebihi saldo, dan tidak ada entitas permohonan atau penarikan selain "jenis" pada `mutasi_tabungan`.
  6. **Idempotensi.** dictionary §7: "Idempotency/reference keys bagi operasi yang bisa dicoba ulang" belum dirancang.
  7. **Refund.** BR-FIN-008: "Default awal persentase refund adalah 30%". Tidak ada alur, entitas, status, atau konteks (refund atas apa?) di PRD, workflows, lifecycle, maupun katalog fitur. Aturan yatim.
  8. **SPP.** BR-FIN-007: nominal "yang pernah ditentukan" belum dikonfirmasi sebagai nilai berlaku.
- **Rekomendasi:** putuskan butir 1, 4, 7, 8 di tingkat produk. Lalu lengkapi dictionary dan ERD pada Tahap 4: pembayar, field reversal pada semua objek terposting, kunci referensi sumber mutasi, invarian saldo ≥ 0 jika disetujui, dan kunci idempotensi.
- **Keputusan pemilik:** **ya** (butir 1, 4, 5, 7, 8).

#### X-17 · Sedang · Cakupan tumpang tindih penempatan siswa
- **Dokumen:** `rules.md` BR-AKD-007; `workflows.md` §2.4; `database-conventions.md` §6, §7.
- **Bukti:**
  - BR-AKD-007: "tidak boleh tumpang tindih pada cakupan aturan yang sama"
  - workflows §2.4: "Tidak boleh ada dua penempatan aktif yang tumpang tindih untuk siswa yang sama"
  - database-conventions §6: "menurut aturan V2"
- **Masalah dan dampak:** dua rumusan berbeda (mutlak per siswa vs "cakupan yang sama"), dan "aturan V2" tidak ada. Tidak jelas apakah siswa boleh ditempatkan di dua kelas pada tahun ajaran yang sama (mis. kelompok tambahan). Selain itu, rentang tanggal tidak dapat dijamin dengan constraint sederhana di MySQL (database-conventions §6 mengakuinya), jadi perlu desain penguncian.
- **Rekomendasi:** putuskan cakupan aturan (per siswa atau per siswa dan tahun ajaran) dan satukan redaksi tiga dokumen. Rancang penegakan lewat transaksi dan penguncian serta uji konkurensi.
- **Keputusan pemilik:** **ya**.

#### X-18 · Sedang · ERD tidak sinkron dengan data dictionary
- **Dokumen:** `erd.md` §1–§4; `data-dictionary.md`.
- **Bukti:**
  - ERD §1 menyatakan relasi ditulis "pada tingkat konsep".
  - Entitas di dictionary yang tidak muncul di diagram: `periode_pembelajaran`, `jenis_penugasan`, `absensi_siswa`, `profil_kesehatan`, `pengukuran_pertumbuhan`, `portofolio`, `absensi_pegawai`, `riwayat_pengiriman_email`, `audit_log`, `program_sekolah`, `pengaturan_website`, `informasi_pendaftaran`.
  - Relasi di dictionary yang tidak muncul: `tagihan`→`tahun_ajaran`, `mutasi_tabungan`→`detail_setoran_tabungan`, `rapor`→`tahun_ajaran`/`periode_pembelajaran`, `pembayaran`→`mutasi_keuangan`, `transfer_keuangan`→`mutasi_keuangan`, `setoran_tabungan`→`pengguna`.
  - Diagram §2 menulis `REKENING_TABUNGAN ||--o{ MUTASI_TABUNGAN` dua kali (kata kerja berbeda).
- **Masalah dan dampak:** ERD tidak dapat dipakai sebagai acuan skema karena relasi penting hilang. Duplikasi baris menandakan sisa edit.
- **Rekomendasi:** sinkronkan ERD dengan dictionary saat dictionary difinalisasi per tahap (X-02). Hapus baris ganda.
- **Keputusan pemilik:** tidak.

#### X-19 · Sedang · Alur autentikasi dan parameter keamanan non-OTP
- **Dokumen:** `auth-rbac.md` §3–§4; `rules.md` BR-AUTH-009, BR-AUTH-013, BR-AUTH-015; `workflows.md` §9; `security-baseline.md`; `documents-and-storage.md` §6; `open-decisions.md` OD-09, OD-18.
- **Bukti:**
  - auth-rbac §3: "OTP verifikasi email, reset password, dan alur lain harus terikat pada tujuan masing-masing". §4: "Token reset mempunyai masa berlaku, sekali pakai".
  - workflows §9: "Akun staf dibuat/diundang oleh petugas berwenang" tanpa alur undangan.
  - auth-rbac §4: "Rekomendasi minimum kata sandi: 12 karakter"
  - BR-AUTH-013: "Percobaan login dibatasi", tanpa angka
  - auth-rbac §3: "Angka/rentang rate limit lebih lengkap dapat dikonfigurasi", tanpa nilai
  - BR-AUTH-015: MFA "direkomendasikan sebelum produksi"
  - documents-and-storage §6: "Allowlist hanya format yang dibutuhkan per konteks", tanpa daftar atau ukuran
- **Masalah dan dampak:**
  - Reset password disebut dengan OTP di satu bagian dan dengan token di bagian lain. Tidak jelas apakah ini satu mekanisme dua langkah atau dua mekanisme berbeda.
  - Undangan staf tidak punya alur (token, masa berlaku, penetapan kata sandi).
  - Angka rate limit login, masa sesi, panjang kata sandi minimum yang diwajibkan, batas ukuran dan tipe berkas belum ada.
  - MFA dan pemulihan akun berprivilege tinggi belum diputuskan.
  - Semua ini harus ada sebelum Tahap 1 selesai dan sebelum uji keamanan dapat ditulis dengan ambang yang jelas.
- **Rekomendasi:** tetapkan mekanisme reset, alur undangan, nilai awal parameter keamanan dan upload, serta keputusan MFA (OD-09) dan prosedur pemulihan Admin Utama.
- **Keputusan pemilik:** **ya** (MFA, pemulihan, nilai awal parameter).

#### X-20 · Sedang · Kebutuhan nonfungsional tidak ditetapkan
- **Dokumen:** `prd.md`; `design-system.md` §6; `accessibility-and-ui-states.md`; `rules.md` BR-OPS-001; `data-dictionary.md` (Konvensi).
- **Bukti:**
  - design-system §6: "Kontras memenuhi tingkat aksesibilitas yang ditargetkan." Tidak ada dokumen yang menyebut target itu.
  - BR-OPS-001: "Zona waktu operasi harus ... dikonfirmasi sebelum production." Nilainya tidak pernah disebut.
  - PRD §9: "Fitur utama bekerja pada mobile dan desktop."
- **Masalah dan dampak:** PRD tidak memiliki bagian kebutuhan nonfungsional: jumlah pengguna dan data, kinerja, ketersediaan, dukungan browser dan perangkat, target aksesibilitas, zona waktu, dan batas upload. Uji kinerja Tahap 8 dan "responsive/accessibility tests" tidak punya ambang. Zona waktu memengaruhi tanggal absensi, jatuh tempo, dan cut-off rapor.
- **Rekomendasi:** tambahkan bagian NFR ke PRD dengan nilai awal yang disetujui.
- **Keputusan pemilik:** **ya** (zona waktu, target aksesibilitas, perangkat, perkiraan volume).

#### X-21 · Sedang · Katalog laporan dan definisi dashboard tidak ada
- **Dokumen:** `prd.md` §4; `roadmap.md` Tahap 6; `ui-ux-direction.md` §4; `information-architecture.md`.
- **Bukti:**
  - PRD §4 Kepala RA: "pemantauan dan laporan yang diizinkan"
  - roadmap Tahap 6: "Laporan yang disepakati"
  - ui-ux-direction §4: "Angka pada dashboard harus mempunyai definisi yang terdokumentasi dan sumber data yang bisa ditelusuri."
  - IA hanya memuat "Laporan Keuangan"
- **Masalah dan dampak:** tidak ada daftar laporan, rumus, filter, atau hak akses. Padahal kegunaan utama Kepala RA adalah laporan. Tidak ada dasar untuk implementasi atau uji.
- **Rekomendasi:** buat katalog laporan dan dashboard per role (definisi metrik, sumber, filter, lingkup izin) sebelum Tahap 6.
- **Keputusan pemilik:** **ya**.

#### X-22 · Sedang · Portal wali dan IA tidak sinkron dengan matriks dan dictionary
- **Dokumen:** `permission-matrix.md`; `prd.md` §4; `information-architecture.md` §2–§4; `ui-ux-direction.md` §4; `rules.md` BR-TAB-009, BR-CMS-002.
- **Bukti:**
  - matriks Tabungan: Wali "Melihat saldo/mutasi anak sesuai izin" dan penarikan "Permohonan sesuai alur yang nanti disetujui"
  - PRD §4 Wali: tidak menyebut tabungan. Portal wali di IA §3 tidak memiliki menu tabungan. Dashboard wali di ui-ux-direction tidak menyebut tabungan.
  - BR-TAB-009: penarikan oleh bendahara/admin
  - IA internal tidak memiliki layar untuk: kategori transaksi keuangan, metode pembayaran, jenis dokumen, jenis kegiatan, jenis penugasan, referensi terkontrol (BR-AKD-005), penarikan tabungan, profil/pengaturan akun staf
  - BR-CMS-002 "Profil guru publik", tanpa halaman publik terkait di IA §4
- **Masalah dan dampak:** matriks menambah kapabilitas wali (lihat tabungan, mengajukan penarikan) yang tidak ada di PRD, IA, atau aturan. Entitas master di dictionary tidak punya layar pengelolaan. Agen UI dan backend akan menafsirkan berbeda.
- **Rekomendasi:** putuskan apakah wali melihat tabungan dan mengajukan penarikan. Selaraskan PRD, IA, dan matriks. Tambahkan layar master yang dibutuhkan.
- **Keputusan pemilik:** **ya** (tabungan wali).

#### X-23 · Sedang · Kontrak backend–frontend belum punya konvensi
- **Dokumen:** `workflow.md` §2; `frontend-implementation.md` §3, §8; `uiux-agent.md`; `task-template.md`; `domain-architecture.md` §5.
- **Bukti:**
  - frontend-implementation §3: "Tipe data frontend mengikuti kontrak props/response yang disepakati."
  - workflow §2.3: "Kontrak UI-backend: sepakati route/action, props/response, validation errors..."
  - uiux-agent: "Periksa route/props/komponen yang benar-benar ada; jangan mengarang API atau payload backend."
- **Masalah dan dampak:** kontrak dirujuk tetapi tidak ada konvensi umum yang dapat dipakai bersama: bentuk props Inertia, props bersama (identitas dan izin pengguna), format error validasi dan error umum, penamaan route, lokasi dan pemilik tipe TypeScript, serta penanganan flash message. Pada tugas awal, agen UI tidak punya route atau props yang "benar-benar ada", sehingga tiap tugas menegosiasikan ulang hal yang sama.
- **Rekomendasi:** buat dokumen konvensi kontrak (satu halaman) pada Tahap 0/1: struktur props, props bersama, format error, penamaan route dan tipe, siapa memegang file tipe.
- **Keputusan pemilik:** tidak (keputusan teknis tim).

#### X-24 · Sedang · Keterlacakan, akseptansi, dan cakupan uji lemah
- **Dokumen:** `requirements-traceability.md`; `rules.md`; `prd.md`; `testing-strategy.md`; `security-baseline.md` §7; `acceptance-checklist.md`; `task-template.md`.
- **Bukti:**
  - traceability: ID sendiri (ID-01, AUTH-01, FIN-01, ...), 25 baris. `rules.md`: 137 aturan `BR-*`, tidak ada dokumen lain yang merujuk ID BR.
  - PRD tanpa ID kebutuhan. `acceptance-checklist.md`: hanya checklist kesiapan produksi. Acceptance criteria diserahkan per tugas (`task-template.md`).
  - `testing-strategy.md` dan `security-baseline.md` §7 tidak memuat uji untuk: respons yang tidak membocorkan email terdaftar (BR-AUTH-011), akun nonaktif kehilangan sesi (BR-AUTH-014), generate tagihan idempoten dan pemeriksaan prasyarat (BR-FIN-015/016), prasyarat dokumen PPDB (BR-PPDB-006), template surat tidak mengubah surat lama (BR-DOK-005), data rapor kosong ditampilkan jelas (BR-RAP-005), cache izin usang (auth-rbac §8), dan jejak audit untuk peristiwa BR-AUD-002. (Pernyataan ini diverifikasi dengan pencarian kata kunci pada kedua dokumen.)
- **Masalah dan dampak:** sekitar 18% aturan (25 dari 137) tercakup matriks, dan ID tidak dapat ditelusuri silang. Banyak aturan keamanan dan integritas tidak memiliki uji yang tertulis. "Selesai" per modul bergantung pada acceptance criteria yang belum ada.
- **Rekomendasi:** pakai satu skema ID (`BR-*` sebagai acuan), perluas matriks menjadi semua aturan atau aturan kritis, tambahkan ID pada PRD, tambahkan butir uji yang hilang, dan tambahkan kolom "ID aturan" pada `task-template.md`.
- **Keputusan pemilik:** tidak.

#### X-25 · Sedang · `roadmap.md` dan `implementation-plan.md` tidak sejalan
- **Dokumen:** `roadmap.md`; `implementation-plan.md`; `INDEX.md`.
- **Bukti:**

| Hal | `roadmap.md` | `implementation-plan.md` |
|---|---|---|
| Jumlah tahap | 0–7 | 0–8 |
| Pegawai, jabatan, penugasan | Tahap 2 | Tahap 3 |
| Riwayat Penempatan Siswa | Tahap 3 | Tahap 2 |
| Rapor | Tahap 5 (bersama administrasi) | Tahap 6 (terpisah) |
| PoC PDF | Tahap 5 | Tahap 6 |
| Uji hosting | Tahap 7 | Tahap 8 |

  `INDEX.md`: roadmap = "fitur inti, ditunda, dan di luar cakupan", padahal isinya tahapan (peran itu milik `feature-catalog.md`).
- **Masalah dan dampak:** dua urutan berbeda tanpa pernyataan mana yang berlaku. Agen dapat memilih urutan yang salah.
- **Rekomendasi:** pilih satu dokumen tahapan otoritatif dan jadikan yang lain rujukan atau hapus. Koreksi deskripsi di INDEX. Sekaligus selesaikan X-04.
- **Keputusan pemilik:** **ya**.

#### X-26 · Sedang · `open-decisions.md` tidak lengkap dan tidak dapat dikelola
- **Dokumen:** `open-decisions.md`; `roadmap.md` Tahap 0; `backup-and-recovery.md`; `decision-log.md` DEC-025; `rapor-template-spec.md`; `rules.md`.
- **Bukti:**
  - roadmap Tahap 0: "Tutup open decisions prioritas tinggi." Tabel OD hanya punya kolom ID, Topik, Status saat ini, Cara menutup (tanpa prioritas, pemilik, tahap penghambat).
  - "Cara memperbarui": "Setiap baris harus mempunyai penanggung jawab/keputusan, tanggal ...". Kolom itu tidak ada.
  - Butir terbuka di dokumen lain yang **tidak ada** di OD: metode tanda tangan rapor, standar "normal" pertumbuhan, skala Tingkat Ketercapaian ⚑, aturan cut-off rapor, zona waktu (BR-OPS-001), maker-checker, granularitas absensi, ada tidaknya biaya PPDB, nilai SPP dan refund, frekuensi dan retensi backup (DEC-025: "Frekuensi/retensi/lokasi masih terbuka").
- **Masalah dan dampak:** perintah roadmap tidak dapat dijalankan karena "prioritas tinggi" tidak didefinisikan. Daftar keputusan yang nyata lebih panjang dari yang tercatat, jadi gerbang tahap tidak bisa dicek terhadap keputusan yang menghambat.
- **Rekomendasi:** tambahkan kolom pemilik, prioritas, tahap yang terhambat, dan tanggal. Masukkan butir yang hilang (bagian 5 laporan ini dapat dijadikan dasar).
- **Keputusan pemilik:** ya (penentuan pemilik dan prioritas).

### 4.4 Rendah

#### X-27 · Rendah · Terminologi dan glosarium
- **Dokumen:** `glossary.md`; `backend-guide.md` §4; `domain-architecture.md`; `rapor-template-spec.md`; `workflows.md`; `data-dictionary.md`.
- **Bukti:** variasi istilah untuk konsep yang sama: pegawai/staf ("akun staf"); pendaftaran/pengajuan; setoran/batch setoran; kelas/kelompok; "Orang Tua/Wali Siswa" (rapor) vs "Wali Murid" (role); modul "Reports" (backend-guide) vs "Rapor" (domain) vs "Laporan" (IA). Glosarium tidak memuat: Pendaftaran/Pengajuan, Pegawai, Staf, Absensi, Verifikasi, Rekonsiliasi, Selisih, Penarikan. Tabungan ada di domain Keuangan, padahal BR-TAB-001 menyatakannya "terpisah" dari keuangan umum.
- **Dampak:** UI, kode, dan uji dapat memakai istilah berbeda. `ui-ux-direction.md` §3 menuntut istilah sama di menu, tombol, dan uji.
- **Rekomendasi:** tambahkan istilah ke glosarium dengan satu istilah baku per konsep. Perjelas bahwa tabungan adalah submodul terpisah dalam domain Keuangan.
- **Keputusan pemilik:** tidak.

#### X-28 · Rendah · Presedensi dan kebijakan konflik
- **Dokumen:** `AGENTS.md`; `backend-guide.md` §1; `workflow.md` §5.
- **Bukti:**
  - AGENTS "Urutan prioritas ketika ada konflik": decision-log (1), lalu "prd.md dan rules.md" (2, satu tingkat)
  - backend-guide §1: "Jika instruksi bertabrakan, hentikan perubahan pada bagian konflik dan catat pada decision log"
  - workflow §5: "Jangan menebak keputusan berdampak besar. Catat ... Lanjutkan bagian yang tidak diblokir."
- **Dampak:** AGENTS meminta konflik diselesaikan lewat urutan prioritas, sedangkan dua dokumen lain meminta berhenti dan mencatat. PRD dan rules berada di tingkat yang sama tanpa aturan bila bertentangan.
- **Rekomendasi:** tulis satu kebijakan: urutan prioritas hanya berlaku bila sumber tidak ambigu, selain itu berhenti dan catat. Tetapkan mana yang menang antara PRD dan rules.
- **Keputusan pemilik:** ya (presedensi PRD vs rules).

#### X-29 · Rendah · `decision-log.md` tidak mengikuti aturan perubahannya sendiri
- **Dokumen:** `decision-log.md`; `rules.md`.
- **Bukti:** "Aturan perubahan": keputusan baru harus memuat "ID, tanggal, keputusan lama/baru, alasan, alternatif, dampak ... dan daftar dokumen yang diperbarui". Tabel hanya berkolom ID, Keputusan, Status, Catatan. Tidak ada DEC untuk konvensi penamaan DB, autentikasi berbasis sesi, atau daftar role awal.
- **Dampak:** `AGENTS.md` menempatkan decision-log pada prioritas tertinggi, tetapi entri tidak memuat konteks yang cukup untuk menimpa dokumen lain dengan aman.
- **Rekomendasi:** tambahkan kolom tanggal dan dampak. Catat DEC untuk X-03 dan keputusan lain yang diambil.
- **Keputusan pemilik:** tidak.

#### X-30 · Rendah · Kebersihan dokumen
- **Dokumen:** `AGENTS.md`; `README.md`; `backend-agent.md`; `uiux-agent.md`; `feature-catalog.md`; `prd.md`; `roadmap.md`.
- **Bukti dan dampak:**
  - README, backend-agent, dan uiux-agent memakai `/AGENTS.md` dan `/README.md`, sedangkan file berada di `agents/`. Lokasi akhir paket dokumentasi di dalam repositori Laravel belum ditetapkan. Alat agen biasanya membaca instruksi dari root repositori.
  - Taksonomi status: README memakai 5 label (DISETUJUI, REKOMENDASI, TERBUKA, DITUNDA, DI LUAR CAKUPAN). `feature-catalog.md` memakai 4 label (Inti, Ditunda, Di luar cakupan, Perlu keputusan).
  - Daftar modul ditunda diulang di PRD, feature-catalog, roadmap, domain-architecture, README, dan AGENTS. Saat ini konsisten, tetapi menambah risiko ketidaksinkronan di masa depan.
- **Rekomendasi:** tetapkan lokasi akhir dokumen dan perbarui jalur. Rujuk daftar "ditunda" ke satu sumber (mis. feature-catalog).
- **Keputusan pemilik:** ya (lokasi akhir paket dokumentasi).

---

## 5. Keputusan yang memerlukan konfirmasi pemilik proyek

Dikelompokkan menurut tahap yang diblokir.

**Sebelum Tahap 0 / 1**
1. Pencocokan `orang`, email bersama, akun tanpa orang, dan alur guru yang juga wali (X-01).
2. Atribut data pribadi minimal yang dikumpulkan (X-02).
3. Konvensi penamaan DB terhadap kerangka dan paket, tipe PK, pilihan paket RBAC (X-03).
4. Dokumen tahapan yang otoritatif dan urutan tahap (X-04, X-25).
5. Maker-checker pada pembayaran, keputusan PPDB, penerbitan rapor, setoran, dan penarikan (X-10).
6. Nilai `jenis_penugasan` dan aturan pemberian role Guru (X-09).
7. Mekanisme reset password, alur undangan staf, MFA, dan pemulihan Admin Utama (X-19).
8. Zona waktu, target aksesibilitas, dukungan perangkat/browser, perkiraan volume (X-20).
9. Presedensi PRD vs rules (X-28) dan lokasi akhir paket dokumentasi (X-30).

**Sebelum Tahap 2**
10. Tempat data calon anak dan kunci anti-duplikasi (X-05).
11. Ada atau tidaknya biaya pendaftaran PPDB dan cara menagihnya (X-06).
12. Siapa dan bukti apa untuk memverifikasi hubungan wali–anak (X-11).
13. Cakupan aturan tumpang tindih penempatan (X-17).
14. Makna `ditangguhkan` dan status siswa/tahun ajaran (X-12).

**Sebelum Tahap 3–4**
15. Absensi harian atau per sesi, daftar status, dan aturan cut-off (X-13).
16. Kebijakan selisih setoran tabungan (X-08).
17. Pembayaran gabungan beberapa anak, aturan tagihan (diskon/beasiswa/sebagian bulan), nominal SPP yang berlaku, serta makna dan alur refund 30% (X-16).
18. Wali melihat tabungan dan mengajukan penarikan atau tidak (X-22).

**Sebelum Tahap 5–7**
19. Rapor: penerbit dan persetujuan Kepala RA, alur Refleksi Orang Tua, metode pengesahan, skala Tingkat Ketercapaian ⚑, standar pertumbuhan, ejaan DDTK (X-14).
20. Izin publikasi (guru, informasi PPDB) dan persetujuan data anak (X-15).
21. Format penomoran surat dan nomor siswa (OD-17) dan format keluaran surat (D-05).
22. Katalog laporan dan dashboard per role (X-21).

**Menjelang produksi (sudah tercatat di OD, dikonfirmasi ulang)**
23. Vendor dan paket hosting, versi MySQL, email produksi, storage produksi, strategi queue (OD-01..OD-07).
24. Kebijakan retensi data (OD-14), RPO/RTO (OD-15), frekuensi dan lokasi backup (DEC-025).

---

## 6. Urutan perbaikan berdasarkan prioritas

| Urutan | Sebelum | Temuan | Catatan |
|---:|---|---|---|
| 1 | Tahap 0 | X-03, X-26, pemeriksaan kompatibilitas hosting minimal (bagian X-04) | Pemeriksaan: versi PHP dan MySQL, cron, queue, scheduler, storage. |
| 2 | Tahap 1 | X-01, X-02 (subset Tahap 1), X-09, X-10, X-12 (akun), X-15 (OTP, pengaturan), X-19 | Tanpa ini migrasi dan uji otorisasi pertama tidak dapat ditulis. |
| 3 | Tahap 2 | X-04 (tata ulang), X-25, X-05, X-06, X-07, X-11, X-12 (pendaftaran), X-15 (persyaratan dokumen), X-17 | X-04/X-25 sebaiknya selesai paling awal agar rencana tidak menyesatkan agen. |
| 4 | Tahap 3–4 | X-13, X-08, X-16, X-18, D-03 | Absensi dan keuangan. |
| 5 | Tahap 5–7 | X-14, X-15 (sisanya), X-21, X-22, D-05, D-04 | Rapor, dokumen, laporan, CMS. |
| 6 | Berkelanjutan | X-20, X-24, D-01, D-02, X-23 | Kualitas dokumen dan kontrak kerja; X-23 sebaiknya sebelum tugas UI pertama. |
| 7 | Kapan saja | X-27, X-28, X-29, X-30 | Rendah; X-28 lebih baik lebih awal karena memengaruhi cara agen menangani konflik. |

---

## 7. Penilaian kesiapan implementasi

Penilaian berdasarkan isi dokumen, bukan jumlah dokumen yang tersedia.

### 7.1 Per kelompok dokumen

| Kelompok | Status | Yang sudah siap | Yang harus diperbaiki | Belum bisa diputuskan |
|---|---|---|---|---|
| Fondasi produk | **Siap setelah perbaikan tertentu** | Scope, stack, role, modul, 137 aturan, ditunda vs di luar cakupan | D-01, D-02, X-20, X-21, X-27, X-29 | NFR, katalog laporan |
| Arsitektur domain dan workflows | **Siap setelah perbaikan tertentu** | Batas domain, prinsip, 9 alur | X-01, X-05, X-06, X-17 | Kebijakan identitas, biaya PPDB |
| ERD dan data dictionary | **Belum siap** | Daftar entitas dan prinsip pemodelan | X-02, X-07, X-15, X-16, X-18 | Atribut data pribadi, entitas pendukung |
| Status lifecycle | **Siap setelah perbaikan tertentu** | Prinsip transisi dan histori | X-12, X-08 | Makna status akun dan pembayaran |
| Spesifikasi rapor | **Belum siap** (hanya untuk modul Rapor) | Struktur bagian template | X-14, X-13 | Skala ⚑, tanda tangan, refleksi orang tua |
| Backend (auth-rbac, security, storage, email/queue, reports, testing, backend-guide, database-conventions) | **Siap setelah perbaikan tertentu** | Prinsip keamanan, OTP, ledger, upload, queue | X-03, X-19, D-03, D-04, D-05, X-24 | MFA, nilai parameter, mesin PDF, provider |
| Matriks permission | **Belum siap** | Arah umum per role | X-09, X-10, X-22 | Maker-checker, tabungan wali |
| Deployment dan backup | **Draf sesuai desain**; siap setelah hosting dipilih | Daftar pemeriksaan | X-04 (pemeriksaan lebih awal), X-26 | Vendor, RPO/RTO, retensi |
| UI/UX (5 dokumen) | **Siap setelah perbaikan tertentu** | Prinsip, IA, design system, state | X-20, X-21, X-22, X-23 | Token warna/identitas, target aksesibilitas |
| Instruksi agen AI (4 dokumen) | **Siap setelah perbaikan tertentu** | Pembagian peran dan larangan | X-23, X-24, X-28 | – |
| Perencanaan (rencana, keputusan terbuka, keterlacakan) | **Belum siap** | Gerbang tahap, checklist produksi | X-04, X-25, X-26, X-24 | Urutan tahap |

### 7.2 Per tahap implementasi

| Tahap (`implementation-plan.md`) | Status | Syarat |
|---|---|---|
| 0 — Bootstrap | **Siap setelah perbaikan tertentu** | X-03 diputuskan; pemeriksaan kompatibilitas hosting minimal |
| 1 — Fondasi keamanan dan identitas | **Belum siap** | X-01, X-02 (subset), X-09, X-10, X-12 (akun), X-15 (OTP, pengaturan), X-19 |
| 2 — Data master dan PPDB | **Belum siap** | X-04–X-07, X-11, X-17 |
| 3 — Kesiswaan dan kehadiran | **Siap setelah perbaikan tertentu** | X-13, X-02 (subset) |
| 4 — Keuangan dan tabungan | **Belum siap** | X-08, X-16, X-10 |
| 5 — Dokumen, surat, notifikasi, kalender | **Siap setelah perbaikan tertentu** | X-07, X-15 |
| 6 — Rapor LCPA | **Belum siap** | X-14, X-13 ⚑ |
| 7 — Website/CMS | **Siap setelah perbaikan tertentu** | X-15 (izin publikasi), X-22 |
| 8 — Hardening dan produksi | **Belum siap** (sesuai desain; bergantung OD-01..OD-15) | Pemilihan hosting dan keputusan operasional |

### 7.3 Keseluruhan

**Belum siap untuk implementasi penuh.** Bagian yang siap: arah produk, aturan lintas modul, prinsip keamanan dan integritas, serta panduan agen. Bagian yang harus diperbaiki: lapisan data (dictionary dan ERD), katalog permission, urutan tahap, dan daftar keputusan terbuka. Bagian yang belum bisa diputuskan tanpa pemilik: 24 butir di bagian 5.

Jalur tercepat menuju kesiapan: selesaikan batch 1 dan 2 pada bagian 6 (9 temuan: X-01, X-02, X-03, X-09, X-10, X-12, X-15, X-19, X-26; termasuk 2 Kritis dan 3 Tinggi), lalu lanjutkan per tahap.

---

## 8. Bukti integritas audit

- Hash SHA256 seluruh file di `agents/` dicatat sebelum laporan ditulis, lalu dihitung ulang sesudahnya. Hasil: 39 file sebelum dan 39 file sesudah, **0 perbedaan**. Tidak ada dokumen sumber yang berubah.
- Folder `referensi/` tidak dibuka. Hanya daftar folder tingkat atas yang dilihat.
- Jumlah temuan di laporan: 35 (30 lintas dokumen X-01..X-30 dan 5 satu-file D-01..D-05). Cocok dengan tabel bagian 1: 2 Kritis, 9 Tinggi, 18 Sedang, 6 Rendah.
- Klaim ketidakhadiran (X-24) diverifikasi dengan pencarian kata kunci pada `testing-strategy.md` dan `security-baseline.md`.
- Klaim jumlah (137 aturan, 25 baris keterlacakan, 65 entitas, 0 rujukan `BR-*` di dokumen lain) dihitung dengan pencarian atau penghitungan langsung, bukan estimasi.
- Batas verifikasi: nama tabel dan kolom bawaan kerangka Laravel serta paket RBAC (X-03) disebut sebagai risiko yang harus diverifikasi saat bootstrap. Itu belum diperiksa terhadap versi yang akan dipasang.

---

## 9. Status perbaikan (diperbarui 9 Oktober 2026)

Isi audit di atas tidak diubah. Revisi dokumen dilakukan setelah pemilik memutuskan butir utama (DEC-027..048 di `decision-log.md`). Kolom Status: **Selesai** = diperbaiki di dokumen; **Sebagian** = diperbaiki tetapi ada bagian yang menunggu pemilik; **Menunggu pemilik** = belum bisa ditutup.

| ID | Status | Catatan |
|---|---|---|
| X-01 | Sebagian | Kebijakan diputuskan (DEC-027); atribut pencocokan rinci terbuka (OD-11) |
| X-02 | Sebagian | Field-level usulan untuk Tahap 1–2; atribut ⚠ perlu konfirmasi; Tahap 3+ menyusul (OD-10) |
| X-03 | Selesai | DEC-028; verifikasi nama bawaan paket dilakukan saat bootstrap |
| X-04 | Selesai | `implementation-plan.md` disusun ulang; pemeriksaan hosting minimal ditambahkan sebagai Tahap 0A |
| X-05 | Selesai | DEC-029 |
| X-06 | Selesai | DEC-030 dan DEC-049: biaya pendaftaran wajib lunas sebelum diperiksa; tagihan daftar ulang untuk siswa |
| X-07 | Selesai | Verifikasi per versi; status di `versi_dokumen` |
| X-08 | Selesai | DEC-031; status disatukan |
| X-09 | Sebagian | Katalog permission dan penugasan dibuat; default role berpenanda ⚠ menunggu persetujuan (OD-27) |
| X-10 | Sebagian | Rapor dan setoran diputuskan; transisi lain berupa usulan (OD-27) |
| X-11 | Sebagian | DEC-033; jenis bukti wali tambahan terbuka (OD-16) |
| X-12 | Sebagian | Lifecycle dilengkapi; beberapa nilai usulan (OD-23, OD-24, OD-29) |
| X-13 | Selesai | Absensi harian (DEC-034); cut-off rapor ditetapkan (DEC-052) |
| X-14 | Sebagian | Template rapor sudah dibaca dan spesifikasi ditulis ulang; skala BB/MB/BSH/BSB, judul PRESENSI DAN DDTK, refleksi opsional, dan tanda tangan diputuskan (DEC-054). Tersisa: teks butir saat seed, field final setelah PoC, standar “normal” DDTK (OD-12, OD-30, OD-31) |
| X-15 | Sebagian | Entitas ditambahkan; izin publikasi sengaja ditiadakan (DEC-036, DEC-055); format NIS dan nomor surat diputuskan (DEC-050, DEC-051); nomor pendaftaran dan tahun NIS masih usulan (OD-17) |
| X-16 | Sebagian | Struktur diperbaiki; refund hanya untuk daftar ulang (DEC-049); tersisa penyetuju refund, cara alokasi, dan nominal SPP (OD-32, OD-24, OD-26) |
| X-17 | Selesai | DEC-039 |
| X-18 | Selesai | ERD disinkronkan dengan dictionary |
| X-19 | Sebagian | Alur reset/undangan diputuskan; MFA ditiadakan (DEC-041); nilai parameter masih usulan (OD-28) |
| X-20 | Sebagian | NFR awal ditambahkan (DEC-042); target kinerja dan ketersediaan belum ditetapkan |
| X-21 | Selesai | `report-catalog.md`; format ekspor terbuka |
| X-22 | Selesai | DEC-044; IA dan matriks disinkronkan |
| X-23 | Selesai (usulan teknis) | `api-contract-conventions.md` dibuat; disesuaikan saat bootstrap |
| X-24 | Selesai | Matriks berbasis `BR-*` (±45 baris aturan kritis dari 137, bukan semua); uji ditambahkan; PRD memakai ID modul |
| X-25 | Selesai | DEC-045 |
| X-26 | Selesai | `open-decisions.md` diperluas dengan prioritas dan tahap terhambat |
| X-27 | Selesai | Glosarium dilengkapi |
| X-28 | Selesai | Kebijakan konflik di `AGENTS.md`; rules menang atas PRD |
| X-29 | Selesai | Kolom tanggal dan dampak ditambahkan |
| X-30 | Selesai | DEC-048; jalur dikoreksi |
| D-01 | Sebagian | ID modul dan NFR ada; acceptance criteria per modul tetap lewat `task-template.md` |
| D-02 | Sebagian | Frasa menggantung diganti pada aturan yang sudah diputuskan; kolom status per aturan belum ditambahkan (diganti rujukan DEC) |
| D-03 | Selesai | Invarian ditambahkan |
| D-04 | Selesai | Scheduler masuk runbook dan email/queue |
| D-05 | Sebagian | Cakupan PoC diperluas; format keluaran surat terbuka (OD-22) |

**Pembaruan lanjutan (9 Oktober 2026, setelah membaca dokumen sumber di `referensi/`):** `LAPORAN CAPAIAN PEMBELAJARAN ANAK.docx` dan profil RA dibaca (ATP dan modul ajar dilewati; file terkunci dan untuk modul yang ditunda). Tanda ⚑ pada laporan di atas terjawab sebagian: struktur dan jumlah butir capaian (4, 4, 7), tiga area foto, Panca Cinta, NIP penanda tangan, dan fase berasal dari template. Skala Tingkat Ketercapaian tidak tertulis di template; BB/MB/BSH/BSB berasal dari jawaban pemilik. Nama file sumber yang disebut dokumen (“(2).docx”) dikoreksi. Keputusan baru dicatat sebagai DEC-049 sampai DEC-055.

**Pembaruan berikutnya:** pemilik memutuskan penyetuju refund (Admin Sekolah atau Bendahara, DEC-056), rujukan pertumbuhan Permenkes No. 2 Tahun 2020 dengan dropdown manual (DEC-057), nomor pendaftaran, tahun NIS, dan kolom tanda tangan wali (DEC-058), serta target runtime konservatif PHP 8.3 dan MySQL/MariaDB tanpa fitur baru (DEC-059). X-16 dan X-14 kini tinggal bergantung pada field final rapor setelah PoC (OD-12).

**Pembaruan keempat (9 Oktober 2026):** OD-27 (butir utama, DEC-070), OD-34 (tidak ada data yang dihapus, DEC-071), dan sebagian besar OD-35 (DEC-072) diputuskan. X-09 dan X-10 kini **Selesai** kecuali sisa penanda ⚠ minor. Risiko baru yang diterima pemilik: permintaan penghapusan data pribadi tidak dilayani (perlu ditinjau terhadap ketentuan perlindungan data pribadi), koordinat pegawai disimpan, dan radius GPS 10 m berisiko menolak absen sah.

**Pembaruan ketiga (9 Oktober 2026):** pemilik menyetujui opsi dropdown pengukuran (DEC-069), SPP Rp250.000 (DEC-060), alokasi otomatis terlama (DEC-061), surat PDF dan Word (DEC-062), bukti wali tambahan KK/akta (DEC-063), absensi pegawai mandiri GPS (DEC-064), retensi permanen (DEC-065), status siswa dan tahun ajaran aktif tunggal (DEC-066), parameter keamanan (DEC-067), serta peran Admin Sekolah dan refund tanpa pemisahan wajib (DEC-068). Dampak pada status: X-11, X-16, X-19, X-12 kini **Selesai**; X-09/X-10 tinggal default role lain berpenanda ⚠ (OD-27). Temuan baru dari keputusan: OD-34 (penghapusan data vs ledger/rapor terbit) dan OD-35 (rincian absen GPS). Risiko yang diterima pemilik: satu petugas dapat mengajukan dan menyetujui refund sendiri; data lokasi pegawai harus diminimalkan.

**Perlu diperiksa pemilik** — nilai berikut saya tulis sebagai usulan karena belum diputuskan: parameter keamanan (TTL token reset 30 menit, login 5 percobaan/5 menit, sesi idle 120 menit, batas upload 5 MB dan 2 MB), minimal dua Admin Utama, status siswa dan tahun ajaran aktif tunggal, default role bawaan dan pemisahan tugas di luar rapor/setoran, serta persetujuan refund oleh Admin Utama. Semuanya ditandai ⚠ atau tercantum di OD-26..32. Refund 30% adalah 30% ditahan dan 70% dikembalikan, hanya atas uang daftar ulang saat wali mengundurkan diri (sesuai jawaban pemilik).

**Pembaruan kelima (10 Oktober 2026, setelah mempelajari sistem lama di D:\Laravel\Al Kautsar):** sistem lama dipelajari sebagai referensi (dokumen dan kode). Hasilnya DEC-078 sampai DEC-096: cicilan per jenis, status murid calon dan Buat NIS, rumus refund, aturan PPDB (usia, kuota, buka/tutup, gerbang), profil murid dan orang tua lengkap dengan alamat berjenjang, login tiga identitas dan kode aktivasi, impor CSV/Excel, ekspor CSV/PDF, bank asal transfer, email dengan rincian, tanggal terbit SPP, CMS tambahan, tahun ajaran wizard, dan persyaratan keamanan dari kelemahan sistem lama. X-05, X-11, X-15, dan X-16 diperkaya oleh aturan ini. Ringkasan di docs/05-perencanaan/legacy-reference.md; OD baru: OD-36, OD-37, OD-38.
