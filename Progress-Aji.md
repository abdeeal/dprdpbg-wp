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

## Fase 5 — GSAP & Interaktivitas Client-Side (dan Aset)

Interaksi interaktif dan kompilasi gaya agar sama persis dengan Next.js:

- [x] **Interaktivitas Accordion Smooth (`max-height` + `scrollHeight`)**: Menulis ulang logika animasi di SAKIP, PPID, dan Propemperda menggunakan kalkulasi dinamik `scrollHeight + 'px'` dengan kurva deselerasi lembut `cubic-bezier(0.25, 1, 0.5, 1)` (Power2.out GSAP Vercel 1:1) tanpa lompatan kaku.
- [x] **Sinkronisasi Font Global**: Mendefinisikan variabel font `:root` (Plus Jakarta Sans, Fraunces, JetBrains Mono, Montserrat) agar seluruh teks dan judul di website berubah mengikuti tipografi premium dari Vercel.
- [x] **Kompilasi Aset Vite/Tailwind**: Menjalankan perintah `npm install` dan `npm run build` lokal sehingga seluruh kelas layout (kisi-kisi, timeline, panel, warna) terkompilasi sempurna ke berkas produksi `main.css`.

---

## Optimasi Sistem Unggahan Gambar & Media (Tambahan Hari Ini)

Fungsi tambahan untuk mempermudah admin dalam mengelola konten tanpa kendala:

- [x] **Auto-Resize & Compress**: Membatasi resolusi gambar maksimal `1200px` dan kualitas WebP `75%` agar file yang diunggah (JPG/PNG/WebP) otomatis dikompresi di bawah **200KB** demi kecepatan loading web.
- [x] **Perbaikan Error Upload WebP**: Menambahkan filter prioritas GD Library dibanding Imagick untuk mengatasi error *"Server web tidak dapat menghasilkan ukuran gambar responsif"* pada server XAMPP lokal.
- [x] **Aktifkan GD Library di php.ini**: Mengaktifkan `extension=gd` di berkas `D:\instalasi_aplikasi\xampp\php\php.ini` agar GD Library aktif saat diakses lewat browser (Apache), bukan hanya lewat CLI.
- [x] **Sinkronisasi Hirarki URL Halaman**: Menata induk halaman sehingga halaman Sejarah memiliki URL terstruktur `/selayang-pandang/sejarah-kabupaten-purbalingga/` 1:1 sesuai rute Next.js.
- [x] **Sistem Keamanan & Proxy PDF (`dprd_proxy_url`)**: Membangun handler proxy PDF di `functions.php` dan proteksi `.htaccess` di `wp-content/uploads/` untuk menyembunyikan direktori upload asli dan menampilkan nama dokumen di tab browser alih-alih "(anonymous)".

---

## Perbaikan Layout & Backend Propemperda (Bug Fix & Handler Sesi Sore)

- [x] **Upgrade Meta Box Propemperda PDF Upload**: Menulis ulang [inc/meta-boxes/propemperda.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/inc/meta-boxes/propemperda.php) menggunakan UI WordPress Media Uploader modern untuk berkas PDF Propemperda dan SK Penetapan, dilengkapi indikator nama berkas aktif dan tombol hapus file.
- [x] **Pengurutan Tahun Terbaru (`meta_value_num DESC`)**: Mengubah query di [archive-list.php (Propemperda)](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/template-parts/sections/propemperda/archive-list.php) agar tahun paling baru (misal: 2026) secara otomatis selalu tampil di urutan teratas tanpa tergantung urutan penginputan.
- [x] **Typo Handler Tahun**: Mengekstrak 4 digit angka tahun murni via regex (`preg_match('/\d{4}/')`) saat penyimpanan pos, membebaskan admin dari kesalahan ketik seperti `"Tahun 2026"` atau `" 2026a "`.
- [x] **Strict Duplicate Year Handler & Lucide Warning Notice**: Mencegah overwrite dokumen lama jika terjadi duplikasi tahun. Pos baru yang duplikat otomatis dibatalkan (*Draft*) dan menampilkan kotak peringatan merah di dashboard admin dengan SVG Lucide `AlertTriangle` (`<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>`).

---

## Fase 6 — Navigasi Global (Mega Menu Navbar 1:1 Vercel)

Membangun komponen navigasi utama website secara dinamis dari WordPress:

- [x] **Header & Navbar Markup** — Membangun [header.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/header.php) dari awal dengan tiga bagian: tombol Menu/Tutup di kiri, Logo + nama di tengah, dan ikon search + tombol **Reservasi Kunjungan** di kanan.
- [x] **Mega Menu 3-Level Kolom** — Membuat panel mega menu dinamis dengan 3 kolom horizontal (Level 1 → Level 2 → Level 3).
- [x] **Animasi GSAP 1:1 & Transisi Smooth (`700ms cubic-bezier`)**:
  - Animasi menyusut (*shrink-on-scroll*) tinggi header `80px` → `64px` dan `scale(0.85)` logo saat scroll > 50px.
  - Dropdown drawer meluncur dari `-15px` ke `0px` dengan pengereman lembut `0.7s cubic-bezier(0.25, 1, 0.5, 1)`.
  - Pergantian kolom 2 dan 3 menggunakan animasi `.animate-fade-in` 0.5s.
- [x] **Kompensasi Scrollbar Lock (`paddingRight`)**: Menghitung `window.innerWidth - document.documentElement.clientWidth` secara dinamis saat menu dibuka untuk diisikan ke `document.body.style.paddingRight` agar layout dan tombol header tidak meloncat/bergeser saat scrollbar browser hilang.
- [x] **Mobile Accordion Smooth**: Menyesuaikan durasi transisi mobile menu menjadi `700ms` dengan rotasi panah 90 derajat secara perlahan.

---

## Halaman PPID — Fase 2, 4 & 5 (22 Juli 2026)

Mengerjakan halaman **PPID** (`/ppid`) agar 1:1 sesuai dengan Vercel `https://dprd-kab-purbalingga.vercel.app/ppid`, mencakup:

### Fase 2 — Content Model PPID (Custom Meta Box & Data Importer)

- [x] **Upgrade Meta Box PPID** — Menulis ulang [inc/meta-boxes/ppid.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/meta-boxes/ppid.php) agar mendukung field `description` (subtitle akordion) dan repeater `documents_json` (Judul Dokumen + Media Library PDF Uploader).
- [x] **Auto-Import 6 Data Default PPID** — Menambahkan importer di `inc/insert-default-data.php` (SK PPID, Informasi Publik, Permohonan Informasi, Serta Merta, Setiap Saat, Berkala).

### Fase 4 — Konversi Template Halaman PPID

- [x] **Template Arsip PPID** — Menulis ulang [archive-ppid.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/archive-ppid.php) lengkap dengan breadcrumb, gaya font Montserrat & Plus Jakarta Sans, serta ikon ArrowUpRight/ArrowDownLeft.

### Fase 5 — Interaktivitas Client-Side Accordion PPID

- [x] **Vanilla JS Exclusive Accordion** — Logika eksklusif 1 terbuka di `src/js/main.js` dengan animasi `scrollHeight` 400ms `cubic-bezier(0.25, 1, 0.5, 1)`.
- [x] **Kompilasi Aset Vite/Tailwind** — Menjalankan `npm run build` berhasil menghasilkan `main.css` (29.98 kB) dan `main.js` (6.81 kB).
