# LOGBOOK HARIAN PEKERJAAN - GHILBRAN
**Proyek:** Website DPRD Kabupaten Purbalingga (Migrasi Next.js → WordPress Native 100% Gratis)  
**Pengembang:** Ghilbran  
**Periode:** 20 Juli 2026 – 7 Agustus 2026  

---

## 📅 Senin, 20 Juli 2026
* **Fase 1 (Design System & Fondasi Assets):**
  * Konfigurasi awal tema WordPress native `dprd-purbalingga`.
  * Integrasi token warna, typography font (Fraunces, Plus Jakarta Sans, JetBrains Mono, Montserrat), dan utilities CSS Tailwind.
  * Setup build pipeline Vite & Tailwind CSS untuk mengompilasi `main.css` dan `main.js`.
* **Fase 2 (Content Model - CPT & Meta Box):**
  * Registrasi Custom Post Types (CPT): `berita`, `galeri`, `agenda`, `anggota`, `alat-kelengkapan`, `sakip`, `ppid`, `propemperda`, `tokoh-sejarah`.
  * Registrasi Custom Taxonomies: `jenis` dan `kategori-galeri`.
  * Penyederhanaan Meta Box admin bawaan agar ramah bagi pengguna non-teknis:
    * Custom Meta Box Berita (penyederhanaan input ringkasan & metadata).
    * Custom Meta Box Galeri (uploader foto langsung & penyesuaian kategori).
    * Custom Meta Box Agenda (simplifikasi input tanggal & waktu).

---

## 📅 Selasa, 21 Juli 2026
* **Fase 3 (Template Hierarchy) & Fase 4 (Konversi Komponen React ke PHP):**
  * Konversi komponen React menjadi PHP Template Parts untuk Beranda (`agenda.php`, `berita.php`, `galeri.php`) dan Sekilas tentang Purbalingga (`page-sekilas-tentang-purbalingga.php` & 8 sub-section data statistik).
  * Pembuatan template detail berita `single-berita.php` & `template-parts/sections/berita/single-content.php`:
    * Efek **Dropcap otomatis** pada paragraf pertama artikel berita.
    * Integrasi Breadcrumbs dinamis & penyesuaian ikon kalender/penulis.
    * Fitur **Foto Tambahan di Tengah Paragraf** (dapat diatur nomor paragraf penyisipannya).
    * Sidebar **Update Berita Serupa** (3 berita sejenis tanpa duplikasi).
  * Impor data default struktur keanggotaan Pimpinan DPRD, Komisi I–IV, Fraksi, Bapemperda, Badan Musyawarah, Badan Anggaran, dan Badan Kehormatan.
  * Fitur awal konversi gambar otomatis ke WebP dan custom excerpt.

---

## 📅 Rabu, 22 Juli 2026
* **Interaktivitas UI, Fitur Keamanan, & Routing:**
  * Penyelarasan Navbar Mega Menu 3-level, breadcrumb multi-level, dan penguncian scrollbar body.
  * Halaman PPID, SAKIP, dan Propemperda dinamis berbasis database dengan accordion interaktif yang dapat terbuka otomatis via parameter URL (`?id=slug`).
  * Integrasi halaman 503 (*Under Construction*) otomatis untuk tautan menu yang belum memiliki konten.
  * **Sistem Keamanan Proxy PDF:** Menyembunyikan path folder `wp-content/uploads/` saat dokumen PDF diunduh/dibuka, serta proteksi file `.htaccess`.
  * Perbaikan REST API 404 & penyesuaian konfigurasi `.htaccess` lokal.

---

## 📅 Kamis, 6 Agustus 2026
* **Modul Agenda & Transparansi Kinerja:**
  * Pengaturan tanggal default agenda ke hari ini (`min=today`) serta menambahkan validasi backend agar tanggal agenda tidak boleh tanggal lampau.
* **Modul Berita & Editor WordPress Admin:**
  * **Deteksi Tanggal Otomatis:** Mengimplementasikan fungsi pendeteksi otomatis hari dan tanggal rilis dari dalam isi teks artikel berita (misal: *"Kamis, 9 Juli 2026"*).
  * **Format Tanggal Berita:** Mengubah format tampilan tanggal rilis menjadi `Hari, DD NamaBulan YYYY`.
  * **Fitur Auto-Split Tags:** Pemisahan otomatis tag berita saat di-paste dari daftar bullet atau baris baru.
  * **Preset Penulis / Sumber:** Default Nama Penulis / Sumber ke *"Humpro DPRD Kabupaten Purbalingga"* dengan tombol preset `⚡ Humpro DPRD`.
  * **Penyempurnaan Styling Meta Box Admin:** Memperbesar box area *Caption* & *Blockquote*, memperkecil lebar kolom angka nomor paragraf, mengubah warna tombol *Hapus Gambar* menjadi merah, serta *auto-expand* tinggi textarea Keterangan Foto Utama.
* **Performa Server & Optimasi Gambar WebP:**
  * Algoritma kompresi otomatis gambar (JPG/PNG) ke format **WebP** dengan tingkat kompresi adaptif (target maksimal 400 KB, resolusi 1920px 90% kualitas).
  * Penghapusan file mentah JPG/PNG asli secara otomatis dari server setelah terkonversi ke WebP untuk menghemat hosting.
  * Membersihkan file duplikat sub-ukuran bawaan WordPress (`-150x150`, `-300x200`) dan mematikan generator sub-sizes otomatis.
  * Mengalokasikan batas memori PHP 256M saat proses kompresi foto besar.
* **Pengisian Data & Struktur Alat Kelengkapan:**
  * Mengimpor susunan keanggotaan Badan Anggaran 2024–2029 (25 anggota).
  * Memperbaiki butir tugas *repeater*, fitur *search select* anggota, serta mengimpor foto dan struktur Bapemperda & Komisi I–IV.
  * Menyesuaikan backend SAKIP & PPID serta mengunggah dokumen PDF terkait dan foto kegiatan Galeri baru.

---

## 📅 Jumat, 7 Agustus 2026
* **Halaman Galeri Kegiatan & Paginasi Responsif:**
  * Layout grid Galeri desktop: 2 kolom ke kanan dan 10 baris ke bawah (20 kartu per halaman).
  * **Paginasi Responsif Mobile:** Mengatur batas kartu galeri secara dinamis — **maksimal 10 kartu ke bawah** per halaman pada layar HP (`< 640px`) dan **20 kartu** per halaman pada desktop (`>= 640px`).
  * **Desain Filter Popover Minimalis:** Tombol filter kustom berbentuk kotak presisi (`w-12 h-12`) dengan ikon 3-garis horizontal (`filter-3`) dan menu popover melayang tanpa teks terlipat (`whitespace-nowrap`). Penyesuaian warna ikon filter disamakan dengan ikon pencarian (`text-body-secondary`).
  * **Otomatisasi Judul dari Nama File Foto:** Nama file foto yang diunggah otomatis diproses, dibersihkan dari garis/strip dan ekstensi file, lalu diisikan langsung sebagai Judul Galeri secara instan.
  * **Ekstraksi Tanggal & Pengurutan Kronologis (Terbaru Paling Atas):** Parser tanggal kustom yang mendeteksi berbagai format tanggal di judul (`YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`) untuk mengurutkan seluruh galeri dari **tanggal terbaru di paling atas** hingga **terlama di paling bawah**.
  * **Pembersihan Ekstensi File:** Otomatis membersihkan ekstensi file `.jpg`, `.Jpg`, `.png`, `.webp` dari tampilan judul publik.
* **Sistem Manajemen Kategori Admin (Unified Category Manager):**
  * **Single Meta Box Tunggal ("Kategori [Tipe Konten]"):** Menyatukan Meta Box kategori bawaan yang duplikat menjadi 1 box kustom tunggal yang bersih di sidebar kanan WP Admin.
  * **Fitur Hapus & Tambah Kategori Instan:** Menyediakan tombol merah **Hapus** pada setiap item kategori via AJAX langsung dari Meta Box dan WP Admin Sidebar Menu (dilengkapi dialog konfirmasi), serta form **+ Tambah Kategori Baru**.
  * **Scoping Tipe Konten:** Mengaktifkan Meta Box kategori khusus pada `Galeri`, `Berita`, dan `Alat Kelengkapan`, serta mematikan Meta Box kategori pada CPT dokumen (`SAKIP`, `PPID`, `Propemperda`).
* **Pencarian Global & UI Dokumen/Berita:**
  * **Unduhan Berkas Dokumen Langsung:** Tautan berkas PDF pada hasil pencarian dokumen (SAKIP, PPID, Propemperda) langsung ditampilkan di dalam kartu pencarian, sehingga ketika di-klik akan **langsung mengunduh/membuka file PDF**.
  * **Pengalihan Otomatis URL Tunggal (Single Redirect):** Mengalihkan pengguna secara otomatis dari URL tunggal CPT dokumen yang kosong ke file PDF atau ke halaman arsip utama dengan *accordion* kategori terbuka.
  * **Desain Kartu Dokumen Bersih (*Clear*):** Menghapus garis pembatas (`border-t`) dan label teks tambahan pada kartu hasil pencarian dokumen.
  * **Item Anggota Hover-only:** Item hasil pencarian Anggota & Organisasi dapat di-hover secara interaktif namun tidak dapat di-klik menuju URL tunggal kosong.
  * **Tombol "Lihat Semua Berita" Borderless:** Menghapus kotak outline border dan background hover pada tombol *Lihat Semua Berita* di sidebar artikel serupa (`Lihat Semua Berita →`).
* **Dokumentasi & Sinkronisasi Repositori Git:**
  * Pembaruan berkas [progres-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/progres-Ghilbran.md) dan pembuatan [LOGBOOK-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/LOGBOOK-Ghilbran.md).
  * Sinkronisasi repositori Git (`git add`, `git commit`, `git pull --rebase`, `git push origin main`) hingga status `Everything up-to-date`.

---
**Ringkasan Status Proyek:** Seluruh fungsi backend, frontend, interaktivitas Vanilla JS, optimasi media WebP, dan manajemen admin kustom telah 100% selesai dan siap menuju tahap penyelesaian akhir (Seeding data & Go-Live).
