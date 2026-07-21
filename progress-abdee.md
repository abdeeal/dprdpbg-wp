# Progress Migrasi DPRD Kabupaten Purbalingga
Hari ini: Selasa, 21 Juli 2026

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
- [x] Pemicuan *flush rewrite rules* (v4) agar WordPress mengenali routing baru lokal secara instan.

## 3. Fase 4 — Konversi Komponen ke PHP Template (Profil DPRD)
- [x] Pembuatan berkas helper ikon SVG Lucide di `inc/lucide-icons.php` (tanpa dependensi luar).
- [x] Pembuatan berkas template arsip CPT `archive-alat-kelengkapan.php` untuk menampilkan halaman indeks landing `/profil-dprd` (gaya `NavigationIndex`).
- [x] Pembuatan berkas template tunggal `template-parts/sections/alat-kelengkapan/single-content.php` yang mengelola sub-routing dinamis:
  - Halaman indeks Komisi (`/profil-dprd/komisi/`) & Fraksi (`/profil-dprd/fraksi/`).
  - Halaman spesifik Komisi I - IV dengan tabel dua kolom Mitra Kerja.
  - Halaman Fraksi-Fraksi dengan grid keanggotaan.
  - Halaman BK dengan red banner statistik dan sanksi khusus.
  - Halaman Bamus, Banggar, Bapemperda dengan Dasar Hukum & Tugas.
- [x] Penghapusan berkas template halaman statis lama (`page-pimpinan-dprd.php`, `page-badan-musyawarah.php`, dsb.) untuk menjaga kerapian tema.

## 4. Pembaruan & Polish Halaman Pimpinan DPRD (Sesuai Screenshot User)
- [x] Pembaruan data tasks pimpinan di database mencakup ke-3 kategori lengkap (*Kepemimpinan*, *Perwakilan*, *Administrasi*).
- [x] Penyesuaian lebar foto pimpinan menjadi `w-[220px]` (tanpa border/shadow tebal).
- [x] Penataan ulang margin Dasar Hukum (tanpa garis pemisah horizontal) dan list bullet points tugas pimpinan (titik merah kecil, garis pembatas abu-abu tipis).
- [x] Pengubahan pemisah breadcrumbs di seluruh halaman profil DPRD dari garis miring `/` menjadi karakter chevron `›` berwarna abu-abu redup.
