# Laporan Progres Migrasi - Ghilbran
**Proyek:** Website DPRD Kabupaten Purbalingga (Next.js Headless → WordPress Native)
**Fase:** Fase 4 (Convert Komponen React → PHP Template Parts) & Pengembangannya

Dokumen ini mencatat seluruh modul, fitur kustom, dan optimasi yang telah diselesaikan untuk bagian **Agenda, Berita, Galeri, Sekilas tentang Purbalingga, Pencarian Global, dan Sistem Manajemen Kategori Admin** dalam migrasi WordPress (100% Gratis & Bebas Plugin Berbayar).

---

## 📌 Timeline Ringkasan Alur Pengerjaan
* **6 – 10 Juli 2026 (Tahap Awal — Next.js Framework)**: Pengembangan awal aplikasi web menggunakan framework **Next.js (Headless)**, mencakup penyiapan basis kode React, token warna Tailwind v4, integrasi 4 Google Fonts (*Fraunces*, *Plus Jakarta Sans*, *JetBrains Mono*, *Montserrat*), serta penyiapan komponen UI React dan mock data JSON untuk modul Agenda, Berita, Galeri, dan Sekilas Purbalingga.
* **13 Juli 2026 – Sekarang (Tahap Persiapan & Implementasi Full WordPress Native)**: Persiapan dan migrasi menyeluruh ke **WordPress Native (100% Gratis & Tanpa Plugin Berbayar)**. Mencakup penyiapan XAMPP Apache, database cloud Aiven MySQL, kerangka custom theme `wp-content/themes/dprd-purbalingga/`, pendaftaran CPT & Custom Meta Box Native, konversi komponen React ke PHP Template Parts, Vanilla JS interaktivitas, Unified Category Manager, Open Graph Meta Tags sosial media, dan QA akhir.

---

## 📅 1. Agenda & Transparansi Kinerja (Beranda)
* **File Template:** `template-parts/sections/beranda/agenda.php`
* **Pekerjaan yang Diselesaikan:**
  * Konversi visual komponen `AgendaTransparansiSection.jsx` ke PHP Native.
  * Query dinamis data agenda dari CPT `agenda` berdasarkan tanggal rilis terdekat.
  * Implementasi widget samping untuk tautan dokumen **Propemperda** dan **SAKIP** sesuai visual aslinya.
  * Penyederhanaan input Agenda pada admin WordPress (Hanya input Tanggal dan Waktu/Jam, menghapus input Lokasi & Keterangan yang tidak terpakai).

---

## 📰 2. Berita Terkini & Detail Berita
* **File Template Beranda:** `template-parts/sections/beranda/berita.php`
* **File Template Detail Berita:** `single-berita.php` & `template-parts/sections/berita/single-content.php`
* **Pekerjaan yang Diselesaikan:**
  * Pembuatan grid berita di halaman depan: 1 Berita Utama (Featured) beresolusi gambar besar di sebelah kiri dan 4 Berita Terbaru di sebelah kanan (tanpa ada berita duplikat).
  * Pengurutan berita secara otomatis dan kronologis (berdasarkan tanggal dan jam terbit terbaru).
  * **Halaman Detail Berita Premium:**
    * Tampilan visual lengkap dengan Breadcrumbs dinamis.
    * Efek **Dropcap otomatis** (huruf pertama di paragraf pembuka artikel secara otomatis membesar dan tebal bergaya majalah kustom).
    * Penambahan ikon kalender (waktu rilis) dan ikon user (penulis/sumber) yang kompatibel di semua ukuran browser.
    * Tombol **Bagikan (Share)** interaktif (membuka menu share bawaan pada smartphone atau otomatis menyalin link URL ke clipboard pada komputer).
    * Sidebar kanan **Update Berita Serupa** (menampilkan 3 rekomendasi berita sejenis, otomatis mengecualikan berita yang sedang dibaca).
    * Penyempurnaan tombol **Lihat Semua Berita** menjadi tautan teks *borderless* yang bersih tanpa kotak border yang mengganggu (`Lihat Semua Berita →`).
  * **Kemudahan Input Admin Berita (Meta Box Kustom):**
    * Kolom kustom **Ringkasan Berita (Tampil di Halaman Depan)** agar admin tidak perlu mencari menu kutipan di sidebar kanan bawaan WordPress.
    * Menonaktifkan dukungan kutipan default WordPress agar panel *"Tambah kutipan..."* di sidebar kanan hilang untuk menghilangkan kebingungan admin.
    * **Foto Tambahan di Tengah Paragraf:** Fitur kustom di mana admin bisa mengunggah foto tambahan kedua, mengisi caption foto tambahan, dan menulis nomor paragraf ke-berapa foto tersebut ingin disisipkan secara otomatis di dalam teks berita.

---

## 🖼️ 3. Galeri Kegiatan (Beranda & Arsip Halaman)
* **File Template Beranda:** `template-parts/sections/beranda/galeri.php`
* **File Template Daftar Galeri:** `template-parts/sections/galeri/archive-list.php` (dipanggil di `archive-galeri.php`)
* **Pekerjaan yang Diselesaikan:**
  * Tampilan grid 4 galeri terbaru di halaman utama.
  * **Interaktivitas Halaman Galeri (Vanilla JS & Dynamic Layout):**
    * **Desain Tombol Filter Popover Minimalis:** Tombol filter kustom berbentuk kotak presisi (`w-12 h-12`) dengan ikon 3-garis horizontal (`filter-3`). Saat ditekan, menampilkan menu popover melayang tanpa teks terlipat (`whitespace-nowrap`).
    * **Paginasi Responsif Sesuai Ukuran Layar:** Batas kartu galeri diatur dinamis — **maksimal 10 kartu ke bawah** per halaman pada layar HP / mobile (< 640px) dan **20 kartu** per halaman pada layar desktop (>= 640px).
    * **Ekstraksi Tanggal & Pengurutan Otomatis (Terbaru ke Terlama):** Sistem secara otomatis mengekstrak tanggal kegiatan dari judul foto (mendukung berbagai format: `YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`) dan mengurutkan seluruh galeri secara *Descending* (terbaru di paling atas, terlama di bawah).
    * **Pembersihan Ekstensi File pada Judul:** Ekstensi file seperti `.jpg`, `.Jpg`, `.png`, dan `.webp` yang tersisa di judul secara otomatis dibersihkan dari tampilan publik.
    * **Otomatisasi Judul dari Nama File:** Saat admin mengunggah foto galeri baru, nama file foto secara otomatis diproses, dibersihkan dari garis/strip, dan diisikan langsung sebagai Judul Galeri secara instan.

---

## 🏷️ 4. Sistem Manajemen Kategori Admin (Unified Category Manager)
* **File Utama:** `inc/category-manager.php` & `inc/taxonomies.php`
* **Pekerjaan yang Diselesaikan:**
  * **1 Meta Box Tunggal Ringkas ("Kategori [Tipe Konten]"):** Menyatukan Meta Box kategori bawaan yang duplikat menjadi 1 box kustom tunggal yang bersih di sidebar kanan WP Admin.
  * **Fitur Hapus & Tambah Kategori Instan:** Menyediakan tombol merah **Hapus** pada setiap item kategori untuk menghapus kategori yang salah buat secara permanen dari database via AJAX (dilengkapi konfirmasi), serta tombol **+ Tambah Kategori Baru**.
  * **Scoping Tipe Konten yang Tepat:** Meta Box kategori diaktifkan khusus untuk CPT yang membutuhkan pengelompokan kategori (`Galeri`, `Berita`, `Alat Kelengkapan`) dan di-nonaktifkan pada CPT dokumen (`SAKIP`, `PPID`, `Propemperda`) agar tampilan admin tetap rapi.

---

## 🏛️ 5. Sekilas Tentang Purbalingga (Data Statistik & Profil)
* **File Template Halaman:** `page-sekilas-tentang-purbalingga.php`
* **File Sub-Section (`template-parts/sections/sekilas/`):**
  * `letak-geografis.php` (Card kompas batas wilayah & tabel jarak kota besar).
  * `luas-wilayah.php` (Tabel luas wilayah per kecamatan).
  * `topografi-tanah.php` (Ketinggian wilayah & jenis tanah).
  * `hidrologi.php` (List sungai utama).
  * `pemerintahan.php` (Jumlah Kecamatan, Desa, Kelurahan, RT/RW).
  * `kepegawaian.php` (Statistik jenis kelamin dan golongan ASN).
  * `kependudukan.php` (Kepadatan penduduk & laju pertumbuhan).
  * `sosial-fasilitas.php` (Tabel jumlah Sekolah, Rumah Sakit, Tempat Ibadah).
* **Fitur Tambahan:**
  * **Sidebar Daftar Isi (Table of Contents):** Daftar isi di kanan layar yang otomatis berpindah aktif (*scroll-spy*) mengikuti posisi scroll mouse pembaca secara real-time.
  * **Penyediaan Data Fallback (`inc/sekilas-data.php`):** Menyimpan database data statistik BPS 2024 Purbalingga sebagai fallback otomatis jika database online kosong agar tampilan website tidak kosong.

---

## 🚀 6. Optimasi Performa & Keamanan Sistem
* **Kompresi & Konversi WebP Otomatis:**
  * Ditambahkan hook di `functions.php` agar setiap gambar berformat JPG, JPEG, atau PNG yang diunggah ke WordPress otomatis dikonversi ke format **WebP** dengan tingkat kompresi optimal **80%**.
  * File asli JPG/PNG otomatis dihapus dari server untuk menghemat kapasitas penyimpanan hosting.
* **Perbaikan URL (Permalink):**
  * Mengintegrasikan fungsi native `get_permalink()` di seluruh struktur berita kustom untuk mencegah error 404 saat perpindahan halaman.
  * Penyelarasan file konfigurasi `.htaccess` lokal dengan nama folder `/dprdpbg-wp/` untuk kelancaran jalannya REST API lokal.

---

## 🔍 7. Pencarian Global & Penyempurnaan Fitur Tambahan
* **File Terkait:** `inc/search.php`, `template-parts/sections/pencarian/results.php`, `inc/meta-boxes/galeri.php`
* **Pekerjaan yang Diselesaikan:**
  * **Pencarian Lintas CPT (Global Search):** Sistem pencarian memindai seluruh *Custom Post Type* secara komprehensif (Berita, Galeri, Anggota, Alat Kelengkapan, Tokoh Sejarah, SAKIP, PPID, Propemperda).
  * **Unduhan Berkas Dokumen Langsung:** Pada hasil pencarian dokumen (SAKIP, PPID, Propemperda), berkas file PDF langsung ditampilkan di dalam kartu pencarian dengan ikon unduh, sehingga ketika di-klik akan **langsung membuka/mengunduh file PDF** secara terbuka tanpa melalui halaman tunggal yang kosong.
  * **Pengalihan Otomatis URL Tunggal (Single Redirect):** Jika ada URL tunggal CPT dokumen yang diakses, sistem secara otomatis mengalihkan pengguna langsung ke file PDF atau ke halaman arsip utama dengan *accordion* kategori yang terbuka.
  * **Desain Kartu Dokumen Bersih (*Clear*):** Menghapus garis pembatas dan label teks tambahan pada kartu hasil pencarian dokumen agar tampil rapi dan bersih.
  * **Pengurutan Pintar (Smart Sorting):** Berita & Dokumen diurutkan berdasarkan tanggal terbaru. Pada pencarian Anggota, pimpinan (Ketua DPRD, Wakil Ketua) secara otomatis tampil di posisi teratas mendahului anggota biasa.
  * **Daftar Jabatan Anggota:** Mengimplementasikan pemindai multi-jabatan (`dprd_get_member_positions`) sehingga seluruh jabatan anggota DPRD di berbagai komisi/fraksi tampil runtut sebagai *bullet points*.
  * **Hasil Anggota Hover-only:** Item hasil pencarian Anggota & Organisasi dapat di-hover secara interaktif namun tidak dapat di-klik menuju URL tunggal yang kosong.

---

## ⏳ Apa Saja yang Belum Dikerjakan? (Menuju Fase 8: Migrasi & Go-Live)
Secara fitur fungsional (Fase 0 hingga 7), sistem sudah **100% selesai dibangun**. Langkah selanjutnya difokuskan pada tahap penyelesaian akhir (*Finishing* & QA) sesuai dengan panduan migrasi:

1. **Migrasi Data Penuh (Data Entry/Seeding):**
   Memasukkan data asli/dummy secara menyeluruh (berita, semua daftar anggota dewan, file PDF SAKIP/PPID, dsb) untuk menggantikan teks statis atau data uji coba saat ini.
2. **Quality Assurance (QA) Visual & Responsivitas:**
   Mengecek keselarasan desain piksel-ke-piksel (*pixel-perfect*) dengan versi Next.js asli/Figma, baik di tampilan HP maupun Desktop.
3. **Optimasi SEO (Search Engine Optimization):**
   Memastikan setiap template menyuntikkan Meta Title, Meta Description, dan struktur Heading (H1, H2) yang tepat agar ramah mesin pencari.
4. **Persiapan Deployment ke Server Production (cPanel):**
   Memindahkan struktur database lokal ke database production cPanel dan menyesuaikan file `wp-config.php` ke environment live.
