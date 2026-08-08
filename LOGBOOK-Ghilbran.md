# LOGBOOK HARIAN PEKERJAAN - GHILBRAN
**Proyek:** Website DPRD Kabupaten Purbalingga (Migrasi Next.js Headless → Full WordPress Native 100% Gratis)  
**Modul Pengerjaan:** Agenda, Berita, Galeri Kegiatan, Sekilas tentang Purbalingga, Optimasi Media WebP, Unified Category Manager, & Pencarian Global  
**Pengembang:** Ghilbran  
**Periode Pekerjaan:** 6 Juli 2026 – 7 Agustus 2026  

---

## 📅 MINGGU 1: ANALISIS MODUL & PENYIAPAN CONTENT MODEL BASELINE (6 – 10 JULI 2026)

### 🔹 Senin, 6 Juli 2026
* **Analisis Kebutuhan Migrasi Modul:**
  * Analisis struktur komponen React Next.js untuk modul **Agenda** (`AgendaTransparansiSection.jsx`), **Berita** (`BeritaSection.jsx`, `SingleBerita.jsx`), **Galeri** (`GaleriClient.jsx`), dan **Sekilas Purbalingga** (`sekilasPurbalingga.data.js`).
  * Perencanaan migrasi full native tanpa plugin berbayar (ACF Pro / Meta Box premium diganti dengan WordPress Core API).

### 🔹 Selasa, 7 Juli 2026
* **Setup Lingkungan Kerja & Theme Skeleton:**
  * Pengaturan server lokal XAMPP Apache dan koneksi database cloud Aiven MySQL (`wp-config.php`).
  * Inisialisasi struktur kerangka custom theme `wp-content/themes/dprd-purbalingga/` (`style.css`, `index.php`, `functions.php`).
  * Konfigurasi `.gitignore` untuk melindungi kredensial database `wp-config.php`, sertifikat `*.pem`, dan aset terkompilasi.

### 🔹 Rabu, 8 Juli 2026
* **Inisialisasi Custom Post Types (CPT):**
  * Membuat file `inc/post-types.php` untuk meredefinisi Custom Post Types modul yang dikerjakan: `berita`, `galeri`, dan `agenda`.
  * Membuat file `inc/taxonomies.php` untuk meredefinisi taksonomi `kategori-galeri`.

### 🔹 Kamis, 9 Juli 2026
* **Perancangan Custom Meta Box Native:**
  * Merancang sistem custom field native tanpa plugin berbayar menggunakan API WordPress Core `add_meta_box()`.
  * Menentukan skema field untuk Berita (ringkasan & metadata), Galeri (kategori & uploader foto), dan Agenda (tanggal & waktu).

### 🔹 Jumat, 10 Juli 2026
* **Setup Build Pipeline Asset:**
  * Inisialisasi build pipeline Vite + Tailwind CSS v4 (`vite.config.js`, `tailwind.config.js`, `src/css/main.css`, `src/js/main.js`).
  * Menghubungkan fungsi enqueue script dan style pada `functions.php` untuk memuat `assets/dist/main.css` dan `assets/dist/main.js`.

---

## 📅 MINGGU 2: PEMBANGUNAN META BOX ADMIN & PENYESUAIAN FIELD (13 – 17 JULI 2026)

### 🔹 Senin, 13 Juli 2026
* **Custom Meta Box Admin Berita:**
  * Membuat Meta Box Berita (`inc/meta-boxes/berita.php`) dengan penyederhanaan input ringkasan artikel, tanggal, dan waktu untuk pengguna non-teknis.

### 🔹 Selasa, 14 Juli 2026
* **Custom Meta Box Admin Galeri & Uploader Media:**
  * Membuat Meta Box Galeri (`inc/meta-boxes/galeri.php`) dengan uploader media foto bawaan WordPress.
  * Menyiapkan daftar kategori galeri (Rapat Paripurna, Rapat Komisi, Kunjungan Kerja, Reses, Audiensi).

### 🔹 Rabu, 15 Juli 2026
* **Custom Meta Box Admin Agenda:**
  * Membuat Meta Box Agenda (`inc/meta-boxes/agenda.php`) dengan mengeliminasi input lokasi & deskripsi yang tumpang tindih agar pas dengan widget beranda.

### 🔹 Kamis, 16 Juli 2026
* **Penyederhanaan Antarmuka Admin WordPress:**
  * Penyederhanaan label dan deskripsi petunjuk di admin WordPress pada Meta Box Berita, Galeri, dan Agenda agar ramah bagi pengguna non-teknis (admin Humpro).

### 🔹 Jumat, 17 Juli 2026
* **Pengujian Simpan Data Meta Box:**
  * Pengujian fungsi `save_post` dan verifikasi simpan data pada seluruh Meta Box Berita, Galeri, dan Agenda.

---

## 📅 MINGGU 3: KONVERSI KOMPONEN REACT KE PHP TEMPLATE PARTS (20 – 24 JULI 2026)

### 🔹 Senin, 20 Juli 2026
* **Template Beranda (Agenda, Berita, & Galeri):**
  * Konversi komponen React ke PHP Template Parts untuk Beranda:
    * `template-parts/sections/beranda/agenda.php` (query dinamis CPT agenda terdekat).
    * `template-parts/sections/beranda/berita.php` (grid 1 berita utama + 4 berita terbaru tanpa duplikasi).
    * `template-parts/sections/beranda/galeri.php` (grid 4 galeri foto terbaru).

### 🔹 Selasa, 21 Juli 2026
* **Detail Berita & Fitur Sisip Foto Paragraf:**
  * Pembuatan template detail berita `single-berita.php` & `template-parts/sections/berita/single-content.php`:
    * Efek **Dropcap otomatis** pada paragraf pertama artikel berita.
    * Breadcrumbs dinamis & penyesuaian ikon kalender/penulis.
    * Fitur kustom **Foto Tambahan di Tengah Paragraf** (input foto ke-2, caption, dan nomor paragraf penyisipan).
    * Sidebar **Update Berita Serupa** (3 rekomendasi berita sejenis tanpa duplikasi).

### 🔹 Rabu, 22 Juli 2026
* **Halaman Sekilas Purbalingga & Table of Contents:**
  * Pembuatan halaman `page-sekilas-tentang-purbalingga.php` beserta 8 sub-section data statistik BPS (`letak-geografis.php`, `luas-wilayah.php`, `topografi-tanah.php`, `hidrologi.php`, `pemerintahan.php`, `kepegawaian.php`, `kependudukan.php`, `sosial-fasilitas.php`).
  * Integrasi **Sidebar Table of Contents** dengan fitur *scroll-spy* interaktif.
  * Pembuatan database fallback statistik `inc/sekilas-data.php`.

### 🔹 Kamis, 23 Juli 2026
* **Halaman Arsip Berita & Paginasi:**
  * Pembuatan template `archive-berita.php` dengan grid 3 kolom berita, form pencarian berita, dan paginasi halaman.
  * Penyesuaian native excerpt berita dan fitur kompresi gambar awal.

### 🔹 Jumat, 24 Juli 2026
* **Routing Permalink & Configuration:**
  * Integrasi fungsi native `get_permalink()` di seluruh struktur berita kustom untuk mencegah error 404.
  * Penyesuaian file konfigurasi `.htaccess` lokal untuk mendukung REST API.

---

## 📅 MINGGU 4: PENGUJIAN VISUAL & INTERAKTIVITAS UI (27 – 31 JULI 2026)

### 🔹 Senin, 27 Juli 2026
* **Review Tata Letak Grid Berita & Galeri:**
  * Review dan penyesuaian tata letak grid berita & galeri beranda agar presisi piksel-ke-piksel dengan Next.js.

### 🔹 Selasa, 28 Juli 2026
* **Pengujian Interaktivitas Vanilla JS Galeri:**
  * Pengujian interaktivitas client-side Vanilla JS untuk tab filter kategori galeri & pencarian instan pada halaman galeri.

### 🔹 Rabu, 29 Juli 2026
* **Pengujian Scroll-Spy Sekilas Purbalingga:**
  * Pengujian navigasi daftar isi *scroll-spy* pada halaman Sekilas Purbalingga di berbagai ukuran layar.

### 🔹 Kamis, 30 Juli 2026
* **Pengujian Fitur Sisip Foto Paragraf Berita:**
  * Pengujian fitur penyisipan foto tambahan di tengah paragraf artikel berita pada editor WP Admin dan tampilan detail berita.

### 🔹 Jumat, 31 Juli 2026
* **QA Responsivitas UI:**
  * Pengujian responsivitas tampilan UI modul Agenda, Berita, Galeri, dan Sekilas Purbalingga pada perangkat mobile & desktop.

---

## 📅 MINGGU 5: OPTIMASI MEDIA, UNIFIED CATEGORY MANAGER, & PENCARIAN GLOBAL (3 – 7 AGUSTUS 2026)

### 🔹 Senin, 3 Agustus 2026
* **Penyempurnaan Form Input Berita & Agenda Admin:**
  * Setting default tanggal agenda ke hari ini (`min=today`) serta validasi backend agar tidak memilih tanggal lampau.
  * Menambahkan fitur pemisahan otomatis tag berita saat di-paste (*Auto-split Tags*).

### 🔹 Selasa, 4 Agustus 2026
* **Otomatisasi Deteksi Tanggal Berita & Presets:**
  * Mengembangkan fungsi auto-detection hari dan tanggal rilis dari isi artikel berita (misal: *"Kamis, 9 Juli 2026"*).
  * Format tampilan tanggal berita menjadi `Hari, DD NamaBulan YYYY`.
  * Setting default Penulis/Sumber ke *"Humpro DPRD Kabupaten Purbalingga"* dengan tombol preset `⚡ Humpro DPRD`.
  * Styling Meta Box admin berita (auto-expand textarea caption foto utama, warna tombol hapus merah).

### 🔹 Rabu, 5 Agustus 2026
* **Optimasi Performa Media & Gambar WebP:**
  * Pembuatan kompresi otomatis gambar (JPG/PNG) ke format **WebP** dengan kompresi adaptif (target maks 400 KB, resolusi 1920px 90% kualitas).
  * Penghapusan file mentah JPG/PNG asli dari server secara otomatis.
  * Pembersihan file duplikat sub-ukuran bawaan WP (`-150x150`, `-300x200`) dan mematikan generator sub-sizes otomatis.
  * Alokasi memori PHP 256M saat proses kompresi foto besar.

### 🔹 Kamis, 6 Agustus 2026
* **Penyempurnaan Tampilan Berita & Entri Galeri Baru:**
  * Entri foto kegiatan Galeri baru (kategori Rapat Paripurna & Rapat Komisi).
  * Penyempurnaan tombol **Lihat Semua Berita** di sidebar berita tunggal menjadi tautan teks *borderless* (`Lihat Semua Berita →`).

### 🔹 Jumat, 7 Agustus 2026
* **Penyempurnaan Galeri Kegiatan & Paginasi Responsif Mobile:**
  * Layout grid Galeri desktop: 2 kolom ke kanan $\times$ 10 baris ke bawah (20 kartu per halaman).
  * **Paginasi Responsif Mobile:** Mengatur batas kartu galeri secara dinamis — **maksimal 10 kartu ke bawah** per halaman pada layar HP (`< 640px`) dan **20 kartu** per halaman pada desktop (`>= 640px`).
  * **Desain Filter Popover Minimalis:** Tombol filter kustom berbentuk kotak presisi (`w-12 h-12`) dengan ikon 3-garis horizontal (`filter-3`) dan menu popover melayang tanpa teks terlipat (`whitespace-nowrap`). Warna ikon filter disamakan dengan pencarian (`text-body-secondary`).
  * **Otomatisasi Judul dari Nama File Foto:** Nama file foto yang diunggah otomatis diproses, dibersihkan dari garis/strip dan ekstensi file, lalu diisikan langsung sebagai Judul Galeri secara instan.
  * **Ekstraksi Tanggal & Pengurutan Kronologis (Terbaru Paling Atas):** Parser tanggal kustom yang mendeteksi berbagai format tanggal di judul (`YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`) untuk mengurutkan seluruh galeri dari **tanggal terbaru di paling atas** hingga **terlama di paling bawah**. Pembersihan otomatis ekstensi `.jpg`/`.png` dari judul tampilan.
* **Sistem Manajemen Kategori Admin (Unified Category Manager):**
  * **Single Meta Box Tunggal ("Kategori [Tipe Konten]"):** Menyatukan Meta Box kategori bawaan yang duplikat menjadi 1 box kustom tunggal yang bersih di sidebar kanan WP Admin (`inc/category-manager.php`).
  * **Fitur Hapus & Tambah Kategori Instan:** Menyediakan tombol merah **Hapus** pada setiap item kategori via AJAX (dilengkapi dialog konfirmasi) dan form **+ Tambah Kategori Baru**.
  * Diaktifkan khusus pada tipe konten `Galeri`, `Berita`, dan `Alat Kelengkapan`, serta di-nonaktifkan pada CPT dokumen (`SAKIP`, `PPID`, `Propemperda`).
* **Pencarian Global & UI Dokumen:**
  * **Unduhan Berkas Dokumen Langsung:** Tautan berkas PDF pada hasil pencarian dokumen (SAKIP, PPID, Propemperda) langsung ditampilkan di dalam kartu pencarian, sehingga ketika di-klik akan **langsung mengunduh/membuka file PDF**.
  * **Pengalihan Otomatis URL Tunggal (Single Redirect):** Mengalihkan pengguna secara otomatis dari URL tunggal CPT dokumen yang kosong ke file PDF atau ke halaman arsip utama dengan *accordion* terbuka.
  * **Desain Kartu Dokumen Bersih (*Clear*):** Menghapus garis pembatas (`border-t`) dan label teks tambahan pada kartu hasil pencarian dokumen.
  * **Item Anggota Hover-only:** Item hasil pencarian Anggota & Organisasi dapat di-hover secara interaktif namun tidak dapat di-klik menuju URL tunggal kosong.
* **Dokumentasi & Push GitHub:**
  * Pembaruan berkas [progres-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/progres-Ghilbran.md) dan penyesuaian [LOGBOOK-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/LOGBOOK-Ghilbran.md).
  * Push seluruh hasil pengerjaan ke repositori GitHub.

---
**Status Akhir Pekerjaan Ghilbran:** Seluruh modul yang Anda kerjakan (Agenda, Berita, Galeri Kegiatan, Sekilas tentang Purbalingga, Optimasi Media WebP, Unified Category Manager, & Pencarian Global) telah 100% selesai dikembangkan dan teruji.
