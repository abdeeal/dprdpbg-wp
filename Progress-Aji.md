# Progress Pekerjaan Aji — Migrasi Next.js ke WordPress
Dokumen ini mencatat daftar tugas bagian **Aji** (SAKIP, Propemperda, Tokoh Sejarah, dan Selayang Pandang) yang dimulai dari **Fase 4** sesuai dengan panduan `Migrassion Next to WP.md` yang telah diselesaikan hari ini.

---

## ## Fase 4 — Convert Komponen → PHP Template Parts

Pada fase ini, komponen-komponen React/Next.js telah berhasil dikonversi menjadi berkas PHP Template Parts yang dinamis:

- [x] **UI Breadcrumbs**: Membuat berkas [breadcrumbs.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/ui/breadcrumbs.php) untuk navigasi petunjuk arah dinamis secara otomatis.
- [x] **SAKIP Archive List**: Membuat berkas [archive-list.php (SAKIP)](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/sections/sakip/archive-list.php) untuk menampilkan dokumen SAKIP per Kategori secara dinamis dari database.
- [x] **Propemperda Archive List**: Membuat berkas [archive-list.php (Propemperda)](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/sections/propemperda/archive-list.php) untuk menampilkan dokumen per Tahun Anggaran (terbaru ke terlama) dengan dua slot file.
- [x] **Tokoh Sejarah Grid**: Membuat berkas [archive-list.php (Tokoh)](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/sections/tokoh-sejarah/archive-list.php) untuk menampilkan grid profil tokoh dengan foto unggulan atau inisial nama.
- [x] **Layout Sejarah Purbalingga**: Membuat berkas [content.php (Sejarah)](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/sections/sejarah/content.php) yang menggabungkan kotak Hari Jadi, narasi asal-usul, timeline vertikal, sumber referensi babad, dan memanggil grid tokoh sejarah di bawahnya.
- [x] **Tampilan Induk Selayang Pandang**: Membuat berkas [page-selayang-pandang.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/page-selayang-pandang.php) untuk menampilkan daftar direktori menu dinamis dengan tanda panah `↗` (Gambar 2).

---

## ## Fase 5 — GSAP & Interaktivitas Client-Side (dan Aset)

Interaksi interaktif dan kompilasi gaya agar sama persis dengan Next.js:

- [x] **Interaktivitas Accordion**: Menambahkan skrip Vanilla JS interaktif di berkas list SAKIP dan Propemperda agar accordion meluncur buka-tutup dengan lancar saat diklik.
- [x] **Sinkronisasi Font Global**: Mendefinisikan variabel font `:root` (Plus Jakarta Sans, Fraunces, JetBrains Mono, Montserrat) agar seluruh teks dan judul di website berubah mengikuti tipografi premium dari Vercel.
- [x] **Kompilasi Aset Vite/Tailwind**: Menjalankan perintah `npm install` dan `npm run build` lokal sehingga seluruh kelas layout (kisi-kisi, timeline, panel, warna) terkompilasi sempurna ke berkas produksi `main.css`.

---

## ## Optimasi Sistem Unggahan Gambar & Media (Tambahan Hari Ini)

Fungsi tambahan untuk mempermudah admin dalam mengelola konten tanpa kendala:

- [x] **Auto-Resize & Compress**: Membatasi resolusi gambar maksimal `1200px` dan kualitas WebP `75%` agar file yang diunggah (JPG/PNG/WebP) otomatis dikompresi di bawah **200KB** demi kecepatan loading web.
- [x] **Perbaikan Error Upload WebP**: Menambahkan filter prioritas GD Library dibanding Imagick untuk mengatasi error *"Server web tidak dapat menghasilkan ukuran gambar responsif"* pada server XAMPP lokal.
- [x] **Aktifkan GD Library di php.ini**: Mengaktifkan `extension=gd` di berkas `D:\instalasi_aplikasi\xampp\php\php.ini` agar GD Library aktif saat diakses lewat browser (Apache), bukan hanya lewat CLI.
- [x] **Sinkronisasi Hirarki URL Halaman**: Menata induk halaman sehingga halaman Sejarah memiliki URL terstruktur `/selayang-pandang/sejarah-kabupaten-purbalingga/` 1:1 sesuai rute Next.js.

---

## ## Perbaikan Layout (Bug Fix — Sesi Sore)

Perbaikan dua masalah tata letak yang ditemukan setelah pengecekan visual di browser lokal:

- [x] **Perbaikan H1 Sejarah** — Menyamakan kelas `h1` di [page-sejarah-kabupaten-purbalingga.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/page-sejarah-kabupaten-purbalingga.php) agar menggunakan `font-display font-black text-3xl md:text-[36px] tracking-tight text-primary` persis seperti di Vercel (sebelumnya `text-4xl font-bold` saja).
- [x] **Perbaikan Banner CTA Full-Width** — Memindahkan banner *"Ingin Mengetahui Lebih Lanjut?"* dari dalam `content.php` (di mana ia terjebak di dalam container `max-w-7xl`) ke `page-sejarah-kabupaten-purbalingga.php` di luar container, sehingga banner merah bisa menjadi penuh selebar layar (full-width) seperti di Vercel.
- [x] **Recompile Aset Tailwind** — Menjalankan ulang `npm run build` setelah perbaikan layout; ukuran CSS berhasil bertambah (23KB → 26KB) membuktikan kelas baru berhasil terkompilasi.

---

## ## Fase 6 — Navigasi Global (Mega Menu Navbar)

Membangun komponen navigasi utama website secara dinamis dari WordPress:

- [x] **Header & Navbar Markup** — Membangun [header.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/header.php) dari awal dengan tiga bagian: tombol Menu/Tutup di kiri, Logo + nama di tengah, dan ikon search + tombol **Reservasi Kunjungan** di kanan.
- [x] **Mega Menu 3-Level Kolom** — Membuat panel mega menu dinamis dengan 3 kolom horizontal:
  - **Kolom 1** — Seluruh item level 1 dari WordPress menu (Selayang Pandang, Profil DPRD, Galeri, dll.)
  - **Kolom 2** — Sub-menu level 2 yang ditampilkan sesuai item yang di-hover di kolom 1
  - **Kolom 3** — Sub-sub-menu level 3 (contoh: Komisi I, II, III, IV) yang tampil sesuai hover kolom 2
- [x] **Integrasi Menu WordPress** — Menu dibaca berdasarkan **lokasi** `primary` menggunakan `get_nav_menu_locations()` agar selalu sesuai dengan menu "Navbar" yang di-assign ke lokasi "Menu Utama" di WordPress Admin.
- [x] **Logika Interaksi Vanilla JS** — Seluruh logika interaksi mega menu ditulis di [src/js/main.js](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/src/js/main.js): animasi buka/tutup, hover level 1→2→3, navigasi klik, tutup dengan `Escape`, dan shadow scroll.
- [x] **Perbaikan Default Active State** — Item aktif default saat menu dibuka adalah item pertama yang memiliki anak (bukan index 0), sehingga kolom 2 & 3 tidak kosong.
- [x] **Perbaikan Border Tombol** — Menghapus border default browser pada `<button>` dengan `border-0 bg-transparent outline-none`.
- [x] **Perbaikan Breadcrumb Multi-Level** — Menulis ulang [breadcrumbs.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/ui/breadcrumbs.php) dengan `wp_get_post_parent_id()` rekursif: **Beranda › Selayang Pandang › Sejarah Kabupaten Purbalingga**.

---

## ## Halaman PPID — Fase 2, 4 & 5 (22 Juli 2026)

Mengerjakan halaman **PPID** (`/ppid`) agar 1:1 sesuai dengan Vercel `https://dprd-kab-purbalingga.vercel.app/ppid`, mencakup:

### Fase 2 — Content Model PPID (Custom Meta Box & Data Importer)

- [x] **Upgrade Meta Box PPID** — Menulis ulang [inc/meta-boxes/ppid.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/meta-boxes/ppid.php) agar mendukung field `description` (subtitle akordion) dan repeater `documents_json` (Judul Dokumen + URL Link File PDF) sesuai struktur data `ppid.data.js` Vercel. Admin sekarang bisa menambah/hapus dokumen langsung di WordPress tanpa sentuh kode.
- [x] **Auto-Import 6 Data Default PPID** — Menambahkan blok importer di [inc/insert-default-data.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/insert-default-data.php) yang secara otomatis membuat 6 pos CPT `ppid` saat pertama kali tema dimuat:
  1. **SK PPID** — 5 dokumen SK Pembentukan Komisi, Badan Musyawarah, Badan Anggaran, Badan Kehormatan, dan Perubahan Fraksi
  2. **Informasi Publik**
  3. **Permohonan Informasi**
  4. **Informasi Serta Merta**
  5. **Informasi Setiap Saat**
  6. **Informasi Berkala**

### Fase 4 — Konversi Template Halaman PPID

- [x] **Template Arsip PPID** — Menulis ulang [archive-ppid.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/archive-ppid.php) dengan komponen lengkap:
  - Breadcrumb dinamis (`Beranda › PPID`)
  - Judul halaman `PPID` bergaya `font-display font-black text-3xl md:text-[36px] text-primary` persis Vercel
  - Subtitle bergaya `font-mono text-sm tracking-wide text-body-secondary`
  - Garis pembatas `border-t border-primary/40` di atas daftar akordion
  - Setiap akordion menampilkan: judul pos, deskripsi singkat, daftar tautan dokumen dengan gaya `font-mono underline decoration-primary/40`
  - Ikon berganti dinamis: **ArrowUpRight** (terbuka) dan **ArrowDownLeft** (tertutup) sesuai komponen `AccordionItem.jsx` Vercel
  - Item pertama (SK PPID) terbuka secara default saat halaman dimuat

### Fase 5 — Interaktivitas Client-Side Accordion PPID

- [x] **Vanilla JS Accordion** — Menambahkan logika interaksi buka/tutup smooth di [src/js/main.js](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/src/js/main.js) untuk seluruh `.dprd-accordion-item`: animasi transisi `max-height` + `opacity` 300ms, pergantian ikon ArrowUpRight ↔ ArrowDownLeft secara instan.
- [x] **Kompilasi Aset Vite/Tailwind** — Menjalankan `npm run build` berhasil menghasilkan `main.css` (29.98 kB) dan `main.js` (6.81 kB).

