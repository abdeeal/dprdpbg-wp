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
- [x] **Sinkronisasi Hirarki URL Halaman**: Menata induk halaman sehingga halaman Sejarah memiliki URL terstruktur `/selayang-pandang/sejarah-kabupaten-purbalingga/` 1:1 sesuai rute Next.js.
