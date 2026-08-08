# LOGBOOK HARIAN PEKERJAAN - GHILBRAN
**Proyek:** Website DPRD Kabupaten Purbalingga (Migrasi Next.js → WordPress Native)  
**Modul Tipe:** Agenda, Berita, Galeri, Sekilas tentang Purbalingga, Pencarian Global, & Category Manager  
**Pengembang:** Ghilbran  
**Periode:** 6 Juli 2026 – 7 Agustus 2026  

---

## 📅 Periode 6 Juli – 19 Juli 2026 (Fase Persiapan & Perencanaan)
* **Analisis & Perencanaan Migrasi (Fase 0):**
  * Analisis struktur komponen React Next.js (`AgendaTransparansiSection.jsx`, `GaleriClient.jsx`, `SingleBerita.jsx`, dan data BPS `SekilasPurbalingga`).
  * Perencanaan migrasi full native tanpa plugin berbayar (ACF Pro / Meta Box premium diganti dengan core API WordPress).
  * Penyiapan lingkungan kerja lokal (XAMPP, Aiven Cloud MySQL Database, dan kerangka custom theme `dprd-purbalingga`).

---

## 📅 Senin, 20 Juli 2026
* **Penyiapan Content Model (Fase 2) - Modul Agenda, Berita, & Galeri:**
  * Penyesuaian registrasi Custom Post Types (CPT) `berita`, `galeri`, dan `agenda`.
  * Penyederhanaan input Meta Box Admin agar ramah pengguna non-teknis:
    * Custom Meta Box Berita (penyederhanaan input ringkasan & metadata).
    * Custom Meta Box Galeri (uploader foto langsung & penyesuaian kategori).
    * Custom Meta Box Agenda (simplifikasi input tanggal & waktu, menghapus input lokasi & deskripsi yang tidak terpakai).

---

## 📅 Selasa, 21 Juli 2026
* **Konversi Komponen (Fase 4) - Modul Agenda, Berita, Galeri, & Sekilas Purbalingga:**
  * Konversi komponen React ke PHP Template Parts untuk Beranda:
    * `template-parts/sections/beranda/agenda.php`
    * `template-parts/sections/beranda/berita.php`
    * `template-parts/sections/beranda/galeri.php`
  * Pembuatan Halaman Sekilas tentang Purbalingga (`page-sekilas-tentang-purbalingga.php`) beserta 8 sub-section data statistik BPS (Letak Geografis, Luas Wilayah, Topografi, Hidrologi, Pemerintahan, Kepegawaian, Kependudukan, Fasilitas Sosial).
  * Pembuatan Halaman Detail Berita (`single-berita.php` & `template-parts/sections/berita/single-content.php`):
    * Efek **Dropcap otomatis** pada paragraf pertama artikel berita.
    * Integrasi Breadcrumbs dinamis & penyesuaian ikon kalender/penulis.
    * Fitur **Foto Tambahan di Tengah Paragraf** (dapat diatur nomor paragraf penyisipannya).
    * Sidebar **Update Berita Serupa** (3 rekomendasi berita sejenis tanpa duplikasi).

---

## 📅 Rabu, 22 Juli 2026
* **Penyempurnaan Modul Berita & Sekilas Purbalingga:**
  * **Halaman Arsip Berita (`archive-berita.php`):** Pembuatan grid 3 kolom berita, form pencarian berita, dan paginasi halaman.
  * **Sidebar Table of Contents Sekilas Purbalingga:** Integrasi fitur *scroll-spy* otomatis yang berpindah aktif mengikuti posisi scroll mouse pembaca.
  * **Penyediaan Data Fallback Sekilas Purbalingga (`inc/sekilas-data.php`):** Menyimpan database data statistik BPS Purbalingga sebagai fallback otomatis jika database kosong.
  * Konversi gambar otomatis ke WebP dan penyesuaian excerpt native berita.

---

## 📅 Kamis, 6 Agustus 2026
* **Modul Agenda & Berita Admin:**
  * Setting default tanggal agenda ke hari ini (`min=today`) serta validasi backend agar tanggal agenda tidak boleh tanggal lampau.
  * **Auto-detection Tanggal Rilis Berita:** Pendeteksi otomatis hari dan tanggal rilis dari dalam isi artikel berita (misal: *"Kamis, 9 Juli 2026"*).
  * Format tampilan tanggal rilis berita menjadi `Hari, DD NamaBulan YYYY`.
  * Fitur pemisahan otomatis tag berita saat di-paste dari daftar bullet atau baris baru (*Auto-split Tags*).
  * Setting default Penulis/Sumber ke *"Humpro DPRD Kabupaten Purbalingga"* dengan tombol preset `⚡ Humpro DPRD`.
  * Styling Meta Box admin berita (memperbesar box area *Caption* & *Blockquote*, memperkecil kolom angka nomor paragraf, merubah warna tombol *Hapus Gambar* ke merah, dan *auto-expand* textarea caption foto utama).
* **Performa Server & Optimasi Gambar WebP:**
  * Algoritma kompresi otomatis gambar (JPG/PNG) ke format **WebP** dengan tingkat kompresi adaptif (target maksimal 400 KB, resolusi 1920px 90% kualitas).
  * Penghapusan file mentah JPG/PNG asli secara otomatis dari server setelah terkonversi ke WebP untuk menghemat hosting.
  * Pembersihan file duplikat sub-ukuran bawaan WordPress (`-150x150`, `-300x200`) dan mematikan generator sub-sizes otomatis.
  * Menambahkan alokasi memori PHP 256M saat proses kompresi foto besar.

---

## 📅 Jumat, 7 Agustus 2026
* **Modul Galeri Kegiatan & Paginasi Responsif:**
  * Setting grid Galeri desktop: 2 kolom ke kanan dan 10 baris ke bawah (20 kartu per halaman).
  * **Paginasi Responsif Mobile:** Mengatur batas kartu galeri secara dinamis — **maksimal 10 kartu ke bawah** per halaman pada layar HP (`< 640px`) dan **20 kartu** per halaman pada desktop (`>= 640px`).
  * **Desain Filter Popover Minimalis:** Tombol filter kustom berbentuk kotak presisi (`w-12 h-12`) dengan ikon 3-garis horizontal (`filter-3`) dan menu popover melayang tanpa teks terlipat (`whitespace-nowrap`). Warna ikon filter disamakan dengan ikon pencarian (`text-body-secondary`).
  * **Otomatisasi Judul dari Nama File Foto:** Nama file foto yang diunggah otomatis diproses, dibersihkan dari garis/strip dan ekstensi file, lalu diisikan langsung sebagai Judul Galeri secara instan.
  * **Ekstraksi Tanggal & Pengurutan Kronologis (Terbaru Paling Atas):** Parser tanggal kustom yang mendeteksi berbagai format tanggal di judul (`YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`) untuk mengurutkan seluruh galeri dari **tanggal terbaru di paling atas** hingga **terlama di paling bawah**.
  * Pembersihan otomatis ekstensi file `.jpg`, `.Jpg`, `.png`, `.webp` dari tampilan judul publik.
* **Sistem Manajemen Kategori Admin (Unified Category Manager):**
  * **Single Meta Box Tunggal ("Kategori [Tipe Konten]"):** Menyatukan Meta Box kategori bawaan yang duplikat menjadi 1 box kustom tunggal yang bersih di sidebar kanan WP Admin (`inc/category-manager.php`).
  * **Fitur Hapus & Tambah Kategori Instan:** Menyediakan tombol merah **Hapus** pada setiap item kategori via AJAX (dilengkapi dialog konfirmasi) dan form **+ Tambah Kategori Baru**.
  * Diaktifkan khusus pada tipe konten `Galeri`, `Berita`, dan `Alat Kelengkapan`, serta di-nonaktifkan pada CPT dokumen (`SAKIP`, `PPID`, `Propemperda`).
* **Modul Pencarian Global & UI Dokumen/Berita:**
  * **Unduhan Berkas Dokumen Langsung:** Tautan berkas PDF pada hasil pencarian dokumen (SAKIP, PPID, Propemperda) langsung ditampilkan di dalam kartu pencarian, sehingga ketika di-klik akan **langsung mengunduh/membuka file PDF**.
  * **Pengalihan Otomatis URL Tunggal (Single Redirect):** Mengalihkan pengguna secara otomatis dari URL tunggal CPT dokumen yang kosong ke file PDF atau ke halaman arsip utama dengan *accordion* kategori terbuka.
  * **Desain Kartu Dokumen Bersih (*Clear*):** Menghapus garis pembatas (`border-t`) dan label teks tambahan pada kartu hasil pencarian dokumen.
  * **Item Anggota Hover-only:** Item hasil pencarian Anggota & Organisasi dapat di-hover secara interaktif namun tidak dapat di-klik menuju URL tunggal kosong.
  * **Tombol "Lihat Semua Berita" Borderless:** Menghapus kotak outline border dan background hover pada tombol *Lihat Semua Berita* di sidebar artikel serupa (`Lihat Semua Berita →`).
* **Dokumentasi & Push GitHub:**
  * Pembaruan berkas [progres-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/progres-Ghilbran.md) dan penyesuaian [LOGBOOK-Ghilbran.md](file:///d:/XAMPP/htdocs/dprd-purbalingga/LOGBOOK-Ghilbran.md).
  * Push seluruh hasil pengerjaan ke repositori GitHub.

---
**Ringkasan Status Pekerjaan Ghilbran:** Seluruh modul yang ditugaskan (Agenda, Berita, Galeri, Sekilas tentang Purbalingga, Pencarian Global, dan Category Manager) telah 100% selesai dikembangkan dan siap digunakan.
