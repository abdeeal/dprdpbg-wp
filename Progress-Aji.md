# Progress Pekerjaan Aji — Migrasi Next.js ke WordPress
Dokumen ini mencatat daftar tugas bagian **Aji** (SAKIP, Propemperda, Tokoh Sejarah, Selayang Pandang, PPID, Mega Menu Navbar, dan Reservasi Kunjungan Kerja) yang dimulai dari **Fase 4** hingga **Fase 7** sesuai dengan panduan `AGENTS.md`.

---

## Fase 4 — Convert Komponen → PHP Template Parts

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

- [x] **Upgrade Meta Box PPID** — Menulis ulang [inc/meta-boxes/ppid.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/inc/meta-boxes/ppid.php) agar mendukung field `description` (subtitle akordion) dan repeater `documents_json` (Judul Dokumen + Media Library PDF Uploader).
- [x] **Auto-Import 6 Data Default PPID** — Menambahkan importer di `inc/insert-default-data.php` (SK PPID, Informasi Publik, Permohonan Informasi, Serta Merta, Setiap Saat, Berkala).

### Fase 4 — Konversi Template Halaman PPID

- [x] **Template Arsip PPID** — Menulis ulang [archive-ppid.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/archive-ppid.php) lengkap dengan breadcrumb, gaya font Montserrat & Plus Jakarta Sans, serta ikon ArrowUpRight/ArrowDownLeft.

### Fase 5 — Interaktivitas Client-Side Accordion PPID

- [x] **Vanilla JS Exclusive Accordion** — Logika eksklusif 1 terbuka di `src/js/main.js` dengan animasi `scrollHeight` 400ms `cubic-bezier(0.25, 1, 0.5, 1)`.
- [x] **Kompilasi Aset Vite/Tailwind** — Menjalankan `npm run build` berhasil menghasilkan `main.css` dan `main.js`.

---

## Fase 7 — Form Reservasi Kunjungan Kerja (23 Juli 2026)

Membangun sistem pengajuan permohonan reservasi kunjungan dinas/studi banding lengkap dari frontend hingga backend:

- [x] **Custom Post Type `reservasi`** — Meregistrasikan CPT `reservasi` di [inc/post-types.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/post-types.php) untuk menyimpan permohonan masuk secara terstruktur di Database WordPress.
- [x] **Handler Form Submission & PDF Uploader (`inc/backend-reservasi.php`)** — Mengolah data permohonan via AJAX (`dprd_submit_reservasi`), validasi file PDF Surat Permohonan (max 5MB), dan penyimpanan ke database `wp_posts` & `wp_postmeta`.
- [x] **Sinkronisasi Real-time ke Google Sheets Webhook** — Integrasi otomatis via `wp_remote_post()` ke Google Apps Script Webhook sehingga data permohonan baru langsung muncul sebagai baris baru di Google Sheets secara live.
- [x] **Form UI & Prefix WhatsApp (`+62`)** — Memperbarui [page-reservasi.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/page-reservasi.php) dengan input nomor WhatsApp ber-prefix `+62` (tanpa emoji bendera agar lebih clean), filter angka murni, dan tanggal minimal hari ini. Menambahkan apostrof (`'`) pada data payload WA ke Google Sheets agar simbol `+` tidak dibaca sebagai formula dan hilang.
- [x] **Strict Validation Rules & Frontend Inline Error**:
  - **Inline Frontend Error PDF**: Menambahkan notifikasi teks merah inline dan fungsi *auto-scroll* ketika user mencoba *submit* tanpa file PDF (mengatasi konflik native browser validation pada input hidden). Mencegah form dikirim ke backend.
  - Validasi Email wajib berformat valid (`is_email` & ada `@`).
  - Validasi WhatsApp 9–13 digit angka murni setelah prefix `+62`.
  - Validasi Tanggal kunjungan tidak boleh masa lalu & khusus hari kerja (Senin–Jumat).
  - Validasi Jumlah Peserta minimal 1 orang.
  - Validasi Berkas Surat Permohonan wajib `.pdf` & max 5MB.
- [x] **Custom Modal Popup Alert (Premium UI)** — Mengganti alert browser standar dengan Modal khusus berdesain *clean* (font *regular*, deskripsi tidak *overflow*, dan tombol tutup transparan teks merah). Dilengkapi keyframe CSS *custom SVG animation* (*circle draw* + memunculkan centang *smooth* untuk sukses, dan animasi *shake* untuk error).
- [x] **Detail Meta Box Dashboard Admin** — Menampilkan seluruh detail instansi, email, tanggal permohonan, tombol langsung ke WhatsApp, status permohonan (*Pending / Disetujui / Ditolak*), dan tombol unduh PDF di WordPress Admin.

---

## Peningkatan Reservasi Kunjungan — Google Drive Cloud Upload & Auto Clean-up (30 Juli 2026)

- [x] **Upload Otomatis ke Google Drive** — Mengubah sistem penyimpanan berkas PDF Surat Permohonan dari local server storage menjadi upload langsung ke Google Drive via konversi Base64 dan Google Apps Script (GAS) Webhook.
- [x] **Pembersihan Otomatis Storage Server (`unlink`)** — File PDF temporary yang diunggah pengunjung ke server WordPress akan langsung dihapus otomatis (`unlink()`) sesaat setelah berhasil diunggah ke Google Drive, sehingga penyimpanan server WordPress tidak akan penuh.
- [x] **Pencatatan Link Google Drive Publik** — Mengolah balasan JSON dari Google Apps Script di [inc/backend-reservasi.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/backend-reservasi.php) untuk mencatat URL unduhan Google Drive langsung ke Google Sheets dan ke post meta WordPress (`surat_permohonan_url`).

---

## Halaman Bagan Organisasi (30 Juli 2026)

- [x] **Struktur URL & Hierarki Halaman** — Mengatur ulang slug halaman dan parent halaman di database WordPress menjadi `/sekretariat-dprd/struktur-organisasi/bagan-organisasi/`.
- [x] **Bagan Organisasi SVG Presisi Tinggi 1:1** — Membuat template [page-bagan-organisasi.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/page-bagan-organisasi.php) dengan diagram struktur pohon (*SEKRETARIS DPRD*, *KELOMPOK JABATAN FUNGSIONAL* dengan kisi 2x7, *BAGIAN UMUM*, *BAGIAN PERSIDANGAN*, dan 6 *SUBBAGIAN*) berbasis SVG & HTML/Tailwind CSS yang 100% presisi dan identik dengan dokumen fisik asli.
- [x] **Responsivitas Mobile & Tablet** — Menambahkan *container wrapper* `overflow-x-auto hide-scrollbar` dengan min-width 950px agar bagan tetap utuh, rapi, dan dapat di-scroll horizontal tanpa terpotong di layar HP/Tab.
- [x] **Penyelarasan Tema & Layout Visual** — Menggunakan font sistem `Plus Jakarta Sans`, menyelaraskan breadcrumbs bawaan tema (`template-parts/ui/breadcrumbs`), warna font (`text-primary` untuk header, `text-ink` dan `text-body` untuk bagan), dan menghilangkan border outer card agar diagram menyatu transparan dengan latar belakang website.
- [x] **Custom Meta Box Admin (Fase 2)** — Membuat [inc/meta-boxes/bagan-organisasi.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/meta-boxes/bagan-organisasi.php) untuk memberikan kolom unggahan file gambar/PDF asli di editor WordPress Admin, yang secara otomatis menampilkan tombol *"Unduh Bagan Organisasi (Gambar)"* di bagian bawah halaman depan.

---

## Penyesuaian Backend SAKIP & Fitur Drag & Drop PPID (5 - 6 Agustus 2026)

- [x] **Penyesuaian Sistem SAKIP 100% Identik PPID**:
  - Mengubah struktur pengolahan SAKIP di [inc/meta-boxes/sakip.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/inc/meta-boxes/sakip.php) agar menggunakan field `description` (subtitle) dan repeater `documents_json` (banyak PDF uploader) persis seperti PPID.
  - Mengubah template [archive-sakip.php](file:///d:/instalasi_aplikasi/xampp/htdocs/dprd-purbalingga/wp-content/themes/dprd-purbalingga/archive-sakip.php) agar menampilkan daftar pos SAKIP sebagai *accordion* dengan transisi smooth dan ikon panah.
  - Menyiapkan 8 kategori/koleksi SAKIP utama (*Renstra*, *Renja*, *Perjanjian Kinerja*, *IKU*, *Rencana Aksi*, *Cascading Kinerja*, *DPA*, *Laporan Kinerja*).
  - Menghapus taksonomi `kategori-sakip` di `inc/taxonomies.php` yang sudah redundan sehingga antarmuka admin lebih bersih.
- [x] **Fitur Drag & Drop Reordering PPID & SAKIP**:
  - Menambahkan dukungan jQuery UI Sortable di WordPress Admin dengan pegangan **`☰`** untuk menggeser (*drag and drop*) urutan dokumen PDF secara visual.
  - Menambahkan tombol panah naik/turun cepat (**`▲` / `▼`**) untuk mengurutkan dokumen baris demi baris.
  - Mengganti emoji dengan ikon resmi WordPress Dashicons (`dashicons-lightbulb`).
- [x] **Penyesuaian Footer & Halaman Reservasi**:
  - Memperbarui alamat footer resmi `Jl. Onje No.2a, Purbalingga Lor...` dan email `dprd.purbalinggakab@gmail.com`.
  - Menghapus nomor telepon dari kolom *Hubungi Kami* di footer.
  - Memperbaiki konflik *URL rewrite* pada CPT reservasi agar URL `/reservasi/` 100% membuka Halaman Form Pendaftaran (`page-reservasi.php`).

