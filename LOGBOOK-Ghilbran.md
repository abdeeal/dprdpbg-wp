# LOGBOOK HARIAN PEKERJAAN - GHILBRAN
**Proyek:** Website DPRD Kabupaten Purbalingga (Migrasi Next.js Headless → Full WordPress Native 100% Gratis)  
**Pengembang:** Ghilbran  
**Periode Pekerjaan:** 6 Juli 2026 – 7 Agustus 2026  

---

## 📅 MINGGU 1: SETUP LINGKUNGAN, DATABASE, & TOOLING NATIVE (6 – 10 JULI 2026)

### 🔹 Senin, 6 Juli 2026
* **Fase 0 — Setup Lingkungan Lokal & Database:**
  * Pengaturan server lokal XAMPP (menyalakan Apache web server).
  * Konfigurasi koneksi database cloud Aiven MySQL Free-tier pada `wp-config.php` (host, port, SSL CA certificate).
  * Inisialisasi struktur kerangka custom theme kosong pada `wp-content/themes/dprd-purbalingga/` (`style.css`, `index.php`, `functions.php`).
  * Konfigurasi `.gitignore` untuk melindungi kredensial database `wp-config.php`, sertifikat `*.pem`, `node_modules/`, dan `assets/dist/`.

### 🔹 Selasa, 7 Juli 2026
* **Fase 0.5 — Pembangunan Tooling Native Pengganti ACF Pro / Meta Box (Bagian A & B):**
  * Merancang sistem custom field native tanpa plugin berbayar menggunakan API WordPress Core `add_meta_box()`.
  * Membangun class reusable `DPRD_Repeater_Field` (`inc/class-repeater-field.php`) untuk menangani data berbentuk list/tabel bercabang (*repeater field*).
  * Menyimpan data repeater sebagai **JSON string** pada meta key `wp_postmeta` agar ringan dan mudah di-decode.

### 🔹 Rabu, 8 Juli 2026
* **Fase 0.5 — Script JS Admin Repeater & Options Page (Bagian B & C):**
  * Membuat script `src/js/admin-repeater.js` untuk fungsi tambah/hapus baris repeater secara dinamis di halaman edit admin.
  * Membuat helper PHP `get_dprd_repeater()` untuk memanggil data repeater di template.
  * Membangun Options Page Native `"Pengaturan Situs DPRD"` (`inc/options-pages.php`) menggunakan `add_menu_page()` untuk mengelola navigasi menu dan statistik hero beranda.

### 🔹 Kamis, 9 Juli 2026
* **Fase 1 — Setup Design System & Assets Dasar:**
  * Menyalin token warna (Primary maroon `#82111A`, secondary, neutral) dari `tailwind.config.js` proyek Next.js.
  * Menyiapkan typography Google Fonts / self-hosted: *Fraunces*, *Plus Jakarta Sans*, *JetBrains Mono*, dan *Montserrat*.
  * Memindahkan aset gambar dasar dari `public/images/` ke `wp-content/themes/dprd-purbalingga/assets/images/`.

### 🔹 Jumat, 10 Juli 2026
* **Fase 1 — Build Pipeline & Uji Coba Asset Enqueue:**
  * Inisialisasi build pipeline Vite + Tailwind CSS v4 (`vite.config.js`, `tailwind.config.js`, `src/css/main.css`, `src/js/main.js`).
  * Menghubungkan enqueue script dan style pada `functions.php` untuk memuat hasil kompilasi `assets/dist/main.css` dan `assets/dist/main.js`.
  * Verifikasi tampilan dasar theme skeleton dan koneksi database Aiven.

---

## 📅 MINGGU 2: CONTENT MODEL CPT & CUSTOM META BOX NATIVE (13 – 17 JULI 2026)

### 🔹 Senin, 13 Juli 2026
* **Fase 2 — Registrasi CPT & Taksonomi:**
  * Membuat `inc/post-types.php` untuk meredefinisi Custom Post Types (CPT): `berita`, `galeri`, `agenda`, `anggota`, `alat-kelengkapan`, `sakip`, `ppid`, `propemperda`, dan `tokoh-sejarah`.
  * Membuat `inc/taxonomies.php` untuk meredefinisi Custom Taxonomies: `jenis` dan `kategori-galeri`.

### 🔹 Selasa, 14 Juli 2026
* **Fase 2 — Custom Meta Box Admin Berita & Agenda:**
  * Membuat Meta Box Berita (`inc/meta-boxes/berita.php`) dengan penyederhanaan input ringkasan artikel, tanggal, dan waktu untuk pengguna non-teknis.
  * Membuat Meta Box Agenda (`inc/meta-boxes/agenda.php`) dengan mengeliminasi input lokasi & deskripsi yang tumpang tindih agar pas dengan widget beranda.

### 🔹 Rabu, 15 Juli 2026
* **Fase 2 — Custom Meta Box Admin Galeri & Uploader Media:**
  * Membuat Meta Box Galeri (`inc/meta-boxes/galeri.php`) dengan uploader media foto bawaan WordPress.
  * Penyesuaian kategori galeri kustom (Rapat Paripurna, Rapat Komisi, Kunjungan Kerja, Reses, Audiensi).

### 🔹 Kamis, 16 Juli 2026
* **Fase 2 — Custom Meta Box Alat Kelengkapan & Dokumen:**
  * Mengintegrasikan class `DPRD_Repeater_Field` pada CPT `alat-kelengkapan` untuk mengelola daftar anggota dewan (`members`) dan butir tugas (`tugasList`).
  * Membuat handler Meta Box dokumen CPT `sakip`, `ppid`, dan `propemperda`.

### 🔹 Jumat, 17 Juli 2026
* **Fase 2 — Refactoring & Pengujian Simpan Data Meta Box:**
  * Pengujian fungsi `save_post` dan verifikasi simpan data pada seluruh Meta Box CPT.
  * Penyederhanaan label dan deskripsi petunjuk di admin WordPress agar tidak membingungkan admin Humpro.

---

## 📅 MINGGU 3: KONVERSI KOMPONEN REACT KE PHP TEMPLATE PARTS (20 – 24 JULI 2026)

### 🔹 Senin, 20 Juli 2026
* **Fase 3 & 4 — Template Beranda (Agenda, Berita, & Galeri):**
  * Konversi komponen React ke PHP Template Parts untuk Beranda:
    * `template-parts/sections/beranda/agenda.php` (query dinamis CPT agenda terdekat & widget Propemperda/SAKIP).
    * `template-parts/sections/beranda/berita.php` (grid 1 berita utama + 4 berita terbaru tanpa duplikasi).
    * `template-parts/sections/beranda/galeri.php` (grid 4 galeri foto terbaru).

### 🔹 Selasa, 21 Juli 2026
* **Fase 4 — Detail Berita & Fitur Sisip Foto Paragraf:**
  * Pembuatan template detail berita `single-berita.php` & `template-parts/sections/berita/single-content.php`:
    * Efek **Dropcap otomatis** pada paragraf pertama artikel berita.
    * Breadcrumbs dinamis & penyesuaian ikon kalender/penulis.
    * Fitur kustom **Foto Tambahan di Tengah Paragraf** (input foto ke-2, caption, dan nomor paragraf penyisipan).
    * Sidebar **Update Berita Serupa** (3 rekomendasi berita sejenis).

### 🔹 Rabu, 22 Juli 2026
* **Fase 4 — Halaman Sekilas Purbalingga & Table of Contents:**
  * Pembuatan halaman `page-sekilas-tentang-purbalingga.php` beserta 8 sub-section data statistik BPS (`letak-geografis.php`, `luas-wilayah.php`, `topografi-tanah.php`, `hidrologi.php`, `pemerintahan.php`, `kepegawaian.php`, `kependudukan.php`, `sosial-fasilitas.php`).
  * Integrasi **Sidebar Table of Contents** dengan fitur *scroll-spy* interaktif.
  * Pembuatan database fallback statistik `inc/sekilas-data.php`.

### 🔹 Kamis, 23 Juli 2026
* **Fase 4 — Halaman Arsip Berita & Paginasi:**
  * Pembuatan template `archive-berita.php` dengan grid 3 kolom berita, form pencarian berita, dan paginasi halaman.
  * Konversi gambar otomatis ke WebP dan penyesuaian excerpt native berita.

### 🔹 Jumat, 24 Juli 2026
* **Fase 5 — GSAP Animations & Client-Side Interactivity:**
  * Integrasi animasi GSAP scroll-trigger pada `main.js` untuk komponen header & section.
  * Pengujian kelancaran responsivitas tampilan desktop dan mobile.

---

## 📅 MINGGU 4: FINETUNING, REST API, & DOKUMEN SECURITY (27 – 31 JULI 2026)

### 🔹 Senin, 27 Juli 2026
* **Pengujian REST API & Routing Permalinks:**
  * Penyesuaian konfigurasi `.htaccess` lokal untuk menangani rute REST API WordPress dan mencegah error 404 pada permalink kustom.
  * Verifikasi fungsi native `get_permalink()` di seluruh template.

### 🔹 Selasa, 28 Juli 2026
* **Keamanan Dokumen PDF & File Proxy:**
  * Mengembangkan handler proxy PDF aman untuk menyembunyikan path fisik folder `wp-content/uploads/` saat pengguna mengunduh berkas SAKIP/PPID.
  * Menambahkan proteksi file `.htaccess` pada folder upload.

### 🔹 Rabu, 29 Juli 2026
* **Penyempurnaan Halaman PPID, SAKIP, & Propemperda:**
  * Membangun accordion interaktif pada halaman PPID, SAKIP, dan Propemperda.
  * Menambahkan fitur auto-open accordion berdasarkan parameter URL (`?id=slug`).

### 🔹 Kamis, 30 Juli 2026
* **Pengalihan Halaman Kosong (503 Fallback):**
  * Membuat template handler `503.php` / fallback navigasi untuk menu yang belum memiliki konten.

### 🔹 Jumat, 31 Juli 2026
* **Review Mingguan & QA Internal:**
  * Memeriksa keselarasan visual halaman dengan Figma/Next.js live.
  * Pengujian performa sistem di environment lokal.

---

## 📅 MINGGU 5: OPTIMASI MEDIA, GALERI RESPONSIVE, & CATEGORY MANAGER (3 – 7 AGUSTUS 2026)

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
* **Seeding Data & Penyempurnaan Tampilan Berita:**
  * Impor data susunan keanggotaan Badan Anggaran 2024–2029 (25 anggota), Komisi I–IV, Bapemperda, SAKIP, PPID, dan entri foto Galeri baru.
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
  * Pembaruan berkas [progres-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/progres-Ghilbran.md) dan pembuatan [LOGBOOK-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/LOGBOOK-Ghilbran.md).
  * Push seluruh hasil pengerjaan ke repositori GitHub.

---
**Status Akhir Pekerjaan:** Seluruh modul (Agenda, Berita, Galeri, Sekilas tentang Purbalingga, Pencarian Global, dan Category Manager) telah 100% selesai dikembangkan dan teruji.
