# Progress Migrasi DPRD Kabupaten Purbalingga
Hari ini: Rabu, 22 Juli 2026

## 1. Fase 0.5 & 2 — Penataan CPT & Database Cleanup
- [x] Perbaikan error sintaksis trailing brackets pada `insert-default-data.php`.
- [x] Pembaruan helper `dprd_import_setup_ak` untuk mendukung parameter `post_date` custom guna penyusunan urutan kronologis di dashboard admin.
- [x] Penyusunan 7 pos utama Alat Kelengkapan secara berurutan:
  1. Pimpinan DPRD
  2. Badan Musyawarah
  3. Badan Anggaran
  4. Badan Pembentukan Peraturan Daerah (diubah dari Bapemperda)
  5. Badan Kehormatan
  6. Komisi
  7. Fraksi
- [x] Pembersihan halaman statis lama tipe `page` (Bamus, Banggar, BK, dll.) untuk mencegah konflik routing.
- [x] Penghapusan pos duplikat (slug `badan-pembentukan-peraturan-daerah-2`) di database.

## 2. Penyesuaian Routing & Rewrite Rules (`/profil-dprd/`)
- [x] Pembaruan rewrite slug CPT `alat-kelengkapan` di `post-types.php` menjadi `profil-dprd`.
- [x] Registrasi query vars custom (`komisi_num` dan `fraksi_slug`).
- [x] Pemicuan *flush rewrite rules* (v6) agar WordPress mengenali routing baru lokal secara instan.
- [x] Sanitasi dan perbaikan otomatis URL menu navigasi di database via hook `functions.php` agar seluruh link navigasi (`/profil-dprd/pimpinan-dprd/`, dll.) tidak error 404.

## 3. Fase 4 — Konversi Komponen ke PHP Template (Profil DPRD & Footer)
- [x] Pembuatan berkas helper ikon SVG Lucide di `inc/lucide-icons.php` (tanpa dependensi luar).
- [x] Pembuatan berkas template arsip CPT `archive-alat-kelengkapan.php` untuk menampilkan halaman indeks landing `/profil-dprd` (gaya `NavigationIndex`).
- [x] Pembuatan berkas template tunggal `template-parts/sections/alat-kelengkapan/single-content.php` yang mengelola sub-routing dinamis:
  - Halaman indeks Komisi (`/profil-dprd/komisi/`) & Fraksi (`/profil-dprd/fraksi/`).
  - Halaman spesifik Komisi I - IV dengan tabel dua kolom Mitra Kerja.
  - Halaman Fraksi-Fraksi dengan grid keanggotaan.
  - Halaman BK dengan red banner statistik dan sanksi khusus.
  - Halaman Bamus, Banggar, Bapemperda dengan Dasar Hukum & Tugas.
- [x] Pembuatan berkas `footer.php` 100% 1:1 persis source code Next.js `Footer.jsx`:
  - Judul brand **DPRD Purbalingga** menggunakan `font-montserrat`.
  - Tata letak 4 kolom (`Tautan Cepat`, `Informasi`, `Hubungi Kami`, `Brand & Alamat`).
  - 3 tombol lingkaran aksen (*Pill buttons*).
  - Tautan bermodel `font-mono` dan ikon kontak merah.
  - Baris Hak Cipta & Tautan Kebijakan di bagian bawah.

## 4. Pembaruan & Polish Halaman Pimpinan DPRD & Alat Kelengkapan
- [x] Pembaruan data tasks pimpinan di database mencakup ke-3 kategori lengkap (*Kepemimpinan*, *Perwakilan*, *Administrasi*).
- [x] Standardisasi komponen Breadcrumbs (`template-parts/ui/breadcrumbs`) di seluruh 14 halaman Alat Kelengkapan.
- [x] Perataan kontainer judul & deskripsi halaman ke sebelah kiri sejajar breadcrumbs (`w-full text-left`).
- [x] Styling nama anggota non-bold (`font-normal`) dengan container tinggi seragam 2 baris (`h-[2.6em] min-h-[2.6em] flex items-center justify-center`).
- [x] Pengenalan flag `$dasar_is_html` pada `single-content.php` sehingga Dasar Pembentukan Badan Kehormatan (Red Banner Stats) dirender utuh sebagai HTML tanpa terbungkus `esc_html()`.
- [x] Penerapan `md:leading-[1.8]` pada deskripsi dasar pembentukan agar jarak antar baris desktop konsisten 1.8.
- [x] Kompilasi ulang aset produksi Vite/Tailwind (`npm run build`).

## 5. Implementasi & Penyesuaian Halaman Beranda & Navbar
- [x] Optimasi penyimpanan server dengan penghapusan otomatis (_auto-delete_) gambar original saat operasi _crop_ & _upload_ gambar di dashboard admin.
- [x] Penambahan animasi native JavaScript (`IntersectionObserver`) pada _Infostrip Counter_ halaman beranda yang perilakunya (ease-out, jeda waktu, kalkulasi tahun) persis dengan ekstensi GSAP ScrollTrigger di Next.js.
- [x] Pembuatan *section* **Berita Video** di `front-page.php` yang secara dinamis mengambil 6 video YouTube terbaru milik DPRD Purbalingga via `fetch_feed()` RSS bawaan WordPress, lengkap dengan formating layout 1:1 Next.js.
- [x] Migrasi form interaktif **Search Navbar** yang menggunakan _Vanilla JavaScript_ untuk mengelola *state toggle* (expand/shrink, auto-focus input) beserta penyesuaian susunan *z-index* *backdrop* sehingga navbar tidak ikut memudar.
- [x] Kompilasi ulang aset produksi Vite/Tailwind (`npm run build`) untuk menerapkan utilitas *class Tailwind* yang disalin dari komponen video dan search UI Next.js.
