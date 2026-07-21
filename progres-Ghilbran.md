# Laporan Progres Migrasi - Ghilbran
**Proyek:** Website DPRD Kabupaten Purbalingga (Next.js Headless → WordPress Native)
**Fase:** Fase 4 (Convert Komponen React → PHP Template Parts) & Pengembangannya

Dokumen ini mencatat seluruh modul, fitur kustom, dan optimasi yang telah diselesaikan untuk bagian **Agenda, Berita, Galeri, dan Sekilas tentang Purbalingga** dalam migrasi WordPress (100% Gratis & Bebas Plugin Berbayar).

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
  * **Interaktivitas Halaman Galeri (Vanilla JS):** Meniru persis perilaku React (`GaleriClient.jsx`) tanpa memberatkan server:
    * *Pencarian Instan:* Filter pencarian judul foto secara real-time saat mengetik.
    * *Penyaring Tab Kategori:* Klik tab kategori (Rapat Paripurna, Reses, Rapat Komisi, dll.) akan menyaring foto secara instan.
    * *Pagination Dinamis:* Pembagian halaman foto secara instan di browser.

---

## 🏛️ 4. Sekilas Tentang Purbalingga (Data Statistik & Profil)
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
  * **Sidebar Daftar Isi (Table of Contents):** Daftar isi di kanan layar yang otomatis berpindah aktif (scroll-spy) mengikuti posisi scroll mouse pembaca secara real-time.
  * **Penyediaan Data Fallback (`inc/sekilas-data.php`):** Menyimpan database data statistik BPS 2024 Purbalingga sebagai fallback otomatis jika database online kosong agar tampilan website tidak kosong.

---

## 🚀 5. Optimasi Performa & Keamanan Sistem
* **Kompresi & Konversi WebP Otomatis:**
  * Ditambahkan hook di `functions.php` agar setiap gambar berformat JPG, JPEG, atau PNG yang diunggah ke WordPress otomatis dikonversi ke format **WebP** dengan tingkat kompresi optimal **80%**.
  * File asli JPG/PNG otomatis dihapus dari server untuk menghemat kapasitas penyimpanan hosting.
* **Perbaikan URL (Permalink):**
  * Mengintegrasikan fungsi native `get_permalink()` di seluruh struktur berita kustom untuk mencegah error 404 saat perpindahan halaman.
  * Penyelarasan file konfigurasi `.htaccess` lokal dengan nama folder `/dprdpbg-wp/` untuk kelancaran jalannya REST API lokal.

---
*Laporan progres ini disusun sebagai bukti penyelesaian pekerjaan Fase 4.*
