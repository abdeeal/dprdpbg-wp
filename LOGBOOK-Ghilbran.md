# LOGBOOK HARIAN PEKERJAAN - GHILBRAN
**Proyek:** Website DPRD Kabupaten Purbalingga (Migrasi Next.js Headless → Full WordPress Native 100% Gratis)  
**Modul Pengerjaan:** Agenda, Berita Terkini & Detail Berita, Galeri Kegiatan, Sekilas tentang Purbalingga, Unified Category Manager, Optimasi Media WebP, & Pencarian Global  
**Pengembang:** Ghilbran  
**Periode Pekerjaan:** 6 Juli 2026 – 11 Agustus 2026  

---

## 📌 PEMETAAN FASE PEKERJAAN & MIGRASI GHILBRAN

| Fase Pekerjaan | Status | Rincian Pekerjaan & Modul yang Dikerjakan Ghilbran |
|---|---|---|
| **Senin, 6 Juli 2026** | ✅ Dikerjakan | Setup & inisialisasi project Next.js (Headless), penyusunan arsitektur folder modul Agenda, Berita, Galeri, dan Sekilas Purbalingga. |
| **Selasa, 7 Juli 2026** | ✅ Dikerjakan | Setup design system token warna Tailwind v4 (`#82111A`) & integrasi 4 Google Fonts (*Fraunces*, *Plus Jakarta Sans*, *JetBrains Mono*, *Montserrat*). |
| **Rabu, 8 Juli 2026** | ✅ Dikerjakan | Pembuatan komponen React widget Agenda (`AgendaTransparansiSection`), grid Berita Utama & Terbaru (`BeritaSection`), serta baca artikel (`SingleBerita`). |
| **Kamis, 9 Juli 2026** | ✅ Dikerjakan | Pembuatan komponen React Galeri Kegiatan (`GaleriClient`) dengan filter tab & penyiapan data statistik BPS 2024 (`sekilasPurbalingga.data.js`). |
| **Jumat, 10 Juli 2026** | ✅ Dikerjakan | Penyiapan aset gambar `public/images/`, finalisasi UI mockup Next.js, & evaluasi pengalihan arsitektur ke Full WordPress Native. |
| **Fase 0 — Setup Lingkungan WP (13–14 Juli)** | ✅ Dikerjakan | XAMPP Apache, koneksi Aiven MySQL, custom theme skeleton `dprd-purbalingga`, `.gitignore`. |
| **Fase 1 — Design System & Asset Dasar WP** | ✅ Dikerjakan | Porting token warna Tailwind & font ke WordPress theme, penyiapan aset `assets/images/`, Vite build pipeline. |
| **Fase 2 — Content Model CPT & Meta Box** | ✅ Dikerjakan | Pembuatan Custom Meta Box Native untuk CPT `berita` (ringkasan & metadata), CPT `galeri` (uploader foto & kategori), dan CPT `agenda` (tanggal & waktu). |
| **Fase 3 — Pemetaan Halaman ke Template Hierarchy** | ✅ Dikerjakan | Pemetaan template `archive-berita.php`, `single-berita.php`, `archive-galeri.php`, dan `page-sekilas-tentang-purbalingga.php`. |
| **Fase 4 — Convert Komponen React ke PHP Template Parts** | ✅ Dikerjakan | Konversi section Beranda (`agenda.php`, `berita.php`, `galeri.php`), Detail Berita (`single-content.php` dengan Dropcap & Foto Tambahan Paragraf), dan 8 sub-section Sekilas BPS. |
| **Fase 5 — GSAP & Interaktivitas Client-Side Vanilla JS** | ✅ Dikerjakan | Interaktivitas Galeri (filter tab & search instan), Scroll-Spy TOC Sekilas Purbalingga, dan paginasi responsif mobile (maksimal 10 kartu/halaman). |
| **Fase 6 — Fitur Pencarian Global (Custom, Native)** | ✅ Dikerjakan | Pencarian Lintas CPT, unduhan PDF langsung pada kartu dokumen (SAKIP/PPID/Propemperda), pengalihan URL tunggal CPT dokumen, dan kartu dokumen bersih (*clear*). |
| **Fitur Kustom & Optimasi Performa** | ✅ Dikerjakan | Unified Category Manager (`inc/category-manager.php`), Kompresi Adaptif WebP (max 400 KB), Auto-Title Upload Foto Galeri, serta Parser & Sorting Tanggal Judul Galeri (Terbaru di Paling Atas). |
| **Fase 8 — Migrasi Data & QA Akhir** | ✅ Dikerjakan | Entri data Berita & Galeri baru, QA visual responsivitas UI mobile & desktop, penyusunan dokumentasi `progres-Ghilbran.md`, dan push GitHub. |

---

## 📅 LOGBOOK HARIAN PEKERJAAN GHILBRAN

---

### 🔹 MINGGU 1: PENGEMBANGAN AWAL WEBSITE MENGGUNAKAN NEXT.JS `[6 – 10 JULI 2026]`

#### 📅 Senin, 6 Juli 2026 `[Pengembangan Web Next.js — Setup & Arsitektur]`
* **Inisialisasi Proyek & Arsitektur Aplikasi Next.js:**
  * Inisialisasi basis kode proyek web menggunakan framework **Next.js (Headless)**.
  * Penyusunan arsitektur folder utama, routing halaman, dan penyiapan komponen dasar untuk modul **Agenda**, **Berita**, **Galeri**, dan **Sekilas Purbalingga**.

#### 📅 Selasa, 7 Juli 2026 `[Pengembangan Web Next.js — Design System & Typography]`
* **Setup Design System & Typography:**
  * Penyiapan token warna Tailwind (Primary maroon `#82111A`, secondary, neutral) di `tailwind.config.js`.
  * Integrasi 4 typography Google Fonts: *Fraunces*, *Plus Jakarta Sans*, *JetBrains Mono*, dan *Montserrat*.

#### 📅 Rabu, 8 Juli 2026 `[Pengembangan Web Next.js — Komponen Agenda & Berita]`
* **Pembuatan Komponen React (Agenda & Berita):**
  * Membangun komponen React untuk widget Agenda (`AgendaTransparansiSection.jsx`).
  * Membangun layout Berita Utama & Grid Berita Terbaru (`BeritaSection.jsx`) serta halaman baca artikel (`SingleBerita.jsx`).

#### 📅 Kamis, 9 Juli 2026 `[Pengembangan Web Next.js — Komponen Galeri & Sekilas Purbalingga]`
* **Pembuatan Komponen React (Galeri & Sekilas Purbalingga):**
  * Membangun komponen interaktif Galeri Kegiatan (`GaleriClient.jsx`) dengan sistem tab filter & modal preview.
  * Menyusun struktur data statistik BPS 2024 dan komponen halaman Sekilas Purbalingga (`sekilasPurbalingga.data.js`).

#### 📅 Jumat, 10 Juli 2026 `[Pengembangan Web Next.js — Finalisasi UI Mockup Next.js]`
* **Finalisasi UI Mockup & Evaluasi Arsitektur:**
  * Memindahkan dan merapikan seluruh aset gambar dasar pendukung di `public/images/`.
  * Evaluasi tampilan UI versi Next.js dan persiapan transisi strategi pengalihan arsitektur menuju **Full WordPress Native (100% Gratis)**.

---

### 🔹 MINGGU 2: PERSIAPAN & MIGRASI FULL WORDPRESS NATIVE `[FASE 0 & 1]` (13 – 17 JULI 2026)

#### 📅 Senin, 13 Juli 2026 `[Fase 0 — Setup Lingkungan WordPress & Database]`
* **Setup Lingkungan Kerja WordPress & Cloud Database:**
  * Pengaturan server lokal XAMPP Apache dan koneksi database cloud Aiven MySQL (`wp-config.php`).
  * Inisialisasi struktur kerangka custom theme `wp-content/themes/dprd-purbalingga/` (`style.css`, `index.php`, `functions.php`).
  * Konfigurasi `.gitignore` untuk melindungi kredensial database `wp-config.php`, sertifikat `*.pem`, dan aset terkompilasi.

#### 📅 Selasa, 14 Juli 2026 `[Fase 1 — Porting Design System ke Theme WP]`
* **Porting Token Warna & Style Utilities ke Theme WordPress:**
  * Memindahkan token warna dan override `@layer utilities` dari Next.js ke `src/css/main.css`.
  * Menyiapkan pemanggilan Google Fonts pada `header.php` theme.

#### 📅 Rabu, 15 Juli 2026 `[Fase 1 — Penyiapan Aset Theme WP & Vite Build]`
* **Penyiapan Aset Theme & Build Pipeline:**
  * Memindahkan aset gambar dari `public/images/` ke `wp-content/themes/dprd-purbalingga/assets/images/`.
  * Konfigurasi Vite + Tailwind v4 build pipeline (`vite.config.js`, `tailwind.config.js`, `src/css/main.css`, `src/js/main.js`).
  * Menghubungkan fungsi enqueue script dan style pada `functions.php` untuk memuat `assets/dist/main.css` dan `assets/dist/main.js`.

#### 📅 Kamis, 16 Juli 2026 `[Fase 2 — Content Model CPT & Meta Box Berita]`
* **Registrasi CPT & Custom Meta Box Admin Berita:**
  * Membantu penyesuaian file `inc/post-types.php` untuk CPT `berita`, `galeri`, `agenda`, serta taksonomi `kategori-galeri` di `inc/taxonomies.php`.
  * Membuat Meta Box Berita (`inc/meta-boxes/berita.php`) dengan penyederhanaan input ringkasan artikel, tanggal, dan waktu untuk pengguna non-teknis.

#### 📅 Jumat, 17 Juli 2026 `[Fase 2 — Custom Meta Box Galeri & Agenda]`
* **Pembuatan Custom Meta Box Admin Galeri & Agenda:**
  * Membuat Meta Box Galeri (`inc/meta-boxes/galeri.php`) dengan uploader media foto bawaan WordPress dan daftar kategori galeri kustom.
  * Membuat Meta Box Agenda (`inc/meta-boxes/agenda.php`) dengan mengeliminasi input lokasi & deskripsi yang tumpang tindih agar pas dengan widget beranda.
  * Pengujian fungsi `save_post` dan verifikasi simpan data pada seluruh Meta Box Berita, Galeri, dan Agenda.

---

### 🔹 MINGGU 3: KONVERSI KOMPONEN REACT KE PHP `[FASE 3 & 4]` (20 – 24 JULI 2026)

#### 📅 Senin, 20 Juli 2026 `[Fase 3 & 4 — Template Beranda]`
* **Konversi Template Beranda (Agenda, Berita, & Galeri):**
  * Konversi komponen React ke PHP Template Parts untuk Beranda:
    * `template-parts/sections/beranda/agenda.php` (query dinamis CPT agenda terdekat).
    * `template-parts/sections/beranda/berita.php` (grid 1 berita utama + 4 berita terbaru tanpa duplikasi).
    * `template-parts/sections/beranda/galeri.php` (grid 4 galeri foto terbaru).

#### 📅 Selasa, 21 Juli 2026 `[Fase 3 & 4 — Detail Berita]`
* **Pembuatan Halaman Detail Berita:**
  * Pembuatan template detail berita `single-berita.php` & `template-parts/sections/berita/single-content.php`:
    * Efek **Dropcap otomatis** pada paragraf pertama artikel berita.
    * Breadcrumbs dinamis & penyesuaian ikon kalender/penulis.
    * Fitur kustom **Foto Tambahan di Tengah Paragraf** (input foto ke-2, caption, dan nomor paragraf penyisipan).
    * Sidebar **Update Berita Serupa** (3 rekomendasi berita sejenis tanpa duplikasi).

#### 📅 Rabu, 22 Juli 2026 `[Fase 3 & 4 — Sekilas Purbalingga]`
* **Pembuatan Halaman Sekilas Purbalingga & TOC:**
  * Pembuatan halaman `page-sekilas-tentang-purbalingga.php` beserta 8 sub-section data statistik BPS (`letak-geografis.php`, `luas-wilayah.php`, `topografi-tanah.php`, `hidrologi.php`, `pemerintahan.php`, `kepegawaian.php`, `kependudukan.php`, `sosial-fasilitas.php`).
  * Integrasi **Sidebar Table of Contents** dengan fitur *scroll-spy* interaktif.
  * Pembuatan database fallback statistik `inc/sekilas-data.php`.

#### 📅 Kamis, 23 Juli 2026 `[Fase 3 & 4 — Arsip Berita]`
* **Pembuatan Halaman Arsip Berita:**
  * Pembuatan template `archive-berita.php` dengan grid 3 kolom berita, form pencarian berita, dan paginasi halaman.
  * Penyesuaian native excerpt berita dan fitur kompresi gambar awal.

#### 📅 Jumat, 24 Juli 2026 `[Fase 3 — Permalinks & Routing]`
* **Routing Permalink Berita:**
  * Integrasi fungsi native `get_permalink()` di seluruh struktur berita kustom untuk mencegah error 404.

---

### 🔹 MINGGU 4: PENGUJIAN VISUAL & INTERAKTIVITAS UI `[FASE 5]` (27 – 31 JULI 2026)

#### 📅 Senin, 27 Juli 2026 `[Fase 4 — Grid Layout Review]`
* **Review Tata Letak Grid Berita & Galeri:**
  * Review dan penyesuaian tata letak grid berita & galeri beranda agar presisi piksel-ke-piksel dengan Next.js.

#### 📅 Selasa, 28 Juli 2026 `[Fase 5 — Interaktivitas Vanilla JS Galeri]`
* **Pengujian Interaktivitas Vanilla JS Galeri:**
  * Pengujian interaktivitas client-side Vanilla JS untuk tab filter kategori galeri & pencarian instan pada halaman galeri.

#### 📅 Rabu, 29 Juli 2026 `[Fase 5 — Scroll-Spy TOC]`
* **Pengujian Scroll-Spy Sekilas Purbalingga:**
  * Pengujian navigasi daftar isi *scroll-spy* pada halaman Sekilas Purbalingga di berbagai ukuran layar.

#### 📅 Kamis, 30 Juli 2026 `[Fase 4 — Admin Meta Box Testing]`
* **Pengujian Fitur Sisip Foto Paragraf Berita:**
  * Pengujian fitur penyisipan foto tambahan di tengah paragraf artikel berita pada editor WP Admin dan tampilan detail berita.

#### 📅 Jumat, 31 Juli 2026 `[Fase 5 — QA Responsivitas UI]`
* **QA Responsivitas UI:**
  * Pengujian responsivitas tampilan UI modul Agenda, Berita, Galeri, dan Sekilas Purbalingga pada perangkat mobile & desktop.

---

### 🔹 MINGGU 5: OPTIMASI MEDIA, UNIFIED CATEGORY MANAGER, PENCARIAN GLOBAL, & QA `[FASE 6, FITUR KUSTOM, & FASE 8]` (3 – 7 AGUSTUS 2026)

#### 📅 Senin, 3 Agustus 2026 `[Fitur Kustom — Validasi Admin]`
* **Penyempurnaan Form Input Berita & Agenda Admin:**
  * Setting default tanggal agenda ke hari ini (`min=today`) serta validasi backend agar tidak memilih tanggal lampau.
  * Menambahkan fitur pemisahan otomatis tag berita saat di-paste (*Auto-split Tags*).

#### 📅 Selasa, 4 Agustus 2026 `[Fitur Kustom — Auto-detection Berita]`
* **Otomatisasi Deteksi Tanggal Berita & Presets:**
  * Mengembangkan fungsi auto-detection hari dan tanggal rilis dari isi artikel berita (misal: *"Kamis, 9 Juli 2026"*).
  * Format tampilan tanggal berita menjadi `Hari, DD NamaBulan YYYY`.
  * Setting default Penulis/Sumber ke *"Humpro DPRD Kabupaten Purbalingga"* dengan tombol preset `⚡ Humpro DPRD`.
  * Styling Meta Box admin berita (auto-expand textarea caption foto utama, warna tombol hapus merah).

#### 📅 Rabu, 5 Agustus 2026 `[Fitur Kustom — Optimasi Gambar WebP]`
* **Optimasi Performa Media & Gambar WebP:**
  * Pembuatan kompresi otomatis gambar (JPG/PNG) ke format **WebP** dengan kompresi adaptif (target maks 400 KB, resolusi 1920px 90% kualitas).
  * Penghapusan file mentah JPG/PNG asli dari server secara otomatis.
  * Pembersihan file duplikat sub-ukuran bawaan WP (`-150x150`, `-300x200`) dan mematikan generator sub-sizes otomatis.
  * Alokasi memori PHP 256M saat proses kompresi foto besar.

#### 📅 Kamis, 6 Agustus 2026 `[Fase 8 — Seeding Galeri & UI Finishing]`
* **Penyempurnaan Tampilan Berita & Entri Galeri Baru:**
  * Entri foto kegiatan Galeri baru (kategori Rapat Paripurna & Rapat Komisi).
  * Penyempurnaan tombol **Lihat Semua Berita** di sidebar berita tunggal menjadi tautan teks *borderless* (`Lihat Semua Berita →`).

#### 📅 Jumat, 7 Agustus 2026 `[Fase 5, 6, Fitur Kustom, & 8 — Final Features & QA]`
* **Penyempurnaan Galeri Kegiatan & Paginasi Responsif Mobile `[Fase 5 & Fitur Kustom]`:**
  * Layout grid Galeri desktop: 2 kolom ke kanan $\times$ 10 baris ke bawah (20 kartu per halaman).
  * **Paginasi Responsif Mobile:** Mengatur batas kartu galeri secara dinamis — **maksimal 10 kartu ke bawah** per halaman pada layar HP (`< 640px`) dan **20 kartu** per halaman pada desktop (`>= 640px`).
  * **Desain Filter Popover Minimalis:** Tombol filter kustom berbentuk kotak presisi (`w-12 h-12`) dengan ikon 3-garis horizontal (`filter-3`) dan menu popover melayang tanpa teks terlipat (`whitespace-nowrap`). Warna ikon filter disamakan dengan pencarian (`text-body-secondary`).
  * **Otomatisasi Judul dari Nama File Foto:** Nama file foto yang diunggah otomatis diproses, dibersihkan dari garis/strip dan ekstensi file, lalu diisikan langsung sebagai Judul Galeri secara instan.
  * **Ekstraksi Tanggal & Pengurutan Kronologis (Terbaru Paling Atas):** Parser tanggal kustom yang mendeteksi berbagai format tanggal di judul (`YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`) untuk mengurutkan seluruh galeri dari **tanggal terbaru di paling atas** hingga **terlama di paling bawah**. Pembersihan otomatis ekstensi `.jpg`/`.png` dari judul tampilan.
* **Sistem Manajemen Kategori Admin (Unified Category Manager) `[Fitur Kustom]`:**
  * **Single Meta Box Tunggal ("Kategori [Tipe Konten]"):** Menyatukan Meta Box kategori bawaan yang duplikat menjadi 1 box kustom tunggal yang bersih di sidebar kanan WP Admin (`inc/category-manager.php`).
  * **Fitur Hapus & Tambah Kategori Instan:** Menyediakan tombol merah **Hapus** pada setiap item kategori via AJAX (dilengkapi dialog konfirmasi) dan form **+ Tambah Kategori Baru**.
  * Diaktifkan khusus pada tipe konten `Galeri`, `Berita`, dan `Alat Kelengkapan`, serta di-nonaktifkan pada CPT dokumen (`SAKIP`, `PPID`, `Propemperda`).
* **Modul Pencarian Global & UI Dokumen `[Fase 6]`:**
  * **Unduhan Berkas Dokumen Langsung:** Tautan berkas PDF pada hasil pencarian dokumen (SAKIP, PPID, Propemperda) langsung ditampilkan di dalam kartu pencarian, sehingga ketika di-klik akan **langsung mengunduh/membuka file PDF**.
  * **Pengalihan Otomatis URL Tunggal (Single Redirect):** Mengalihkan pengguna secara otomatis dari URL tunggal CPT dokumen yang kosong ke file PDF atau ke halaman arsip utama dengan *accordion* terbuka.
  * **Desain Kartu Dokumen Bersih (*Clear*):** Menghapus garis pembatas (`border-t`) dan label teks tambahan pada kartu hasil pencarian dokumen.
  * **Item Anggota Hover-only:** Item hasil pencarian Anggota & Organisasi dapat di-hover secara interaktif namun tidak dapat di-klik menuju URL tunggal kosong.
* **Dokumentasi & Push GitHub `[Fase 8]`:**
  * Pembaruan berkas [progres-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/progres-Ghilbran.md) dan penyesuaian [LOGBOOK-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/LOGBOOK-Ghilbran.md).
  * Push seluruh hasil pengerjaan ke repositori GitHub.

---

### 🔹 MINGGU 6: PENYUSUNAN BUKU PANDUAN TUTORIAL, DOKUMENTASI, & FINISHING `[FASE 8]` (10 – 11 AGUSTUS 2026)

#### 📅 Senin, 10 Agustus 2026 `[Fase 8 — Penyusunan Buku Panduan Tutorial Admin]`
* **Penyusunan Buku Panduan Pengguna (User Manual) Bagian I:**
  * Pembuatan berkas dokumentasi [Panduan-Pengelolaan-Berita-Galeri-Agenda.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/Panduan-Pengelolaan-Berita-Galeri-Agenda.md) sebagai buku panduan tutorial lengkap pengelolaan konten untuk admin Humpro.
  * Menyusun Bab 1 s.d. Bab 3: Pengenalan alur kerja editor admin WP, tata cara penulisan Berita (auto-detect tanggal, preset penulis `⚡ Humpro DPRD`, sisip foto paragraf dengan live preview, auto-split tags), dan pengelolaan Galeri Kegiatan (auto-title dari nama file, parser pengurutan tanggal, & paginasi mobile).

#### 📅 Selasa, 11 Agustus 2026 `[Fase 8 — Penyempurnaan Tutorial & Finishing Logbook]`
* **Penyempurnaan Buku Panduan Pengguna (User Manual) Bagian II & Logbook:**
  * Menyelesaikan Bab 4 s.d. Bab 6 pada [Panduan-Pengelolaan-Berita-Galeri-Agenda.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/Panduan-Pengelolaan-Berita-Galeri-Agenda.md): Pengelolaan Agenda Transparansi (validasi H+0), Fitur Otomatisasi Performa (Auto-compress WebP adaptif & Dropcap), serta panduan Troubleshooting & FAQ.
  * Refactoring dan penyelarasan akhir berkas jurnal [LOGBOOK-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/LOGBOOK-Ghilbran.md) hingga siap untuk serah terima proyek dan push repositori GitHub.

---
**Status Akhir Pekerjaan Ghilbran:** Seluruh modul yang Anda kerjakan (Agenda, Berita, Galeri Kegiatan, Sekilas tentang Purbalingga, Optimasi Media WebP, Unified Category Manager, & Pencarian Global) pada Fase 0, 1, 2, 3, 4, 5, 6, Fitur Kustom, dan 8 telah 100% selesai dikembangkan dan teruji.
