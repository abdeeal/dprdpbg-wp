# LOGBOOK HARIAN PEKERJAAN - GHILBRAN
**Proyek:** Website DPRD Kabupaten Purbalingga (Migrasi Next.js → WordPress Native)  
**Pengembang:** Ghilbran  
**Periode:** 6 Agustus 2026 – 7 Agustus 2026  

---

## 📅 Kamis, 6 Agustus 2026

### 1. Agenda & Transparansi Kinerja
- **Pengaturan Tanggal Agenda:** Mengatur tanggal default agenda ke hari ini (`min=today`) serta menambahkan validasi backend agar tanggal agenda tidak boleh tanggal lampau.

### 2. Modul Berita & Editor WordPress Admin
- **Deteksi Tanggal Otomatis:** Mengimplementasikan fungsi pendeteksi otomatis hari dan tanggal rilis dari dalam isi teks artikel berita (misal: *"Kamis, 9 Juli 2026"*).
- **Format Tanggal Berita:** Mengubah format tampilan tanggal rilis menjadi `Hari, DD NamaBulan YYYY`.
- **Fitur Auto-Split Tags:** Menambahkan fitur pemisahan otomatis tag berita saat di-paste dari daftar bullet atau baris baru.
- **Preset Penulis / Sumber:** Menetapkan default Nama Penulis / Sumber ke *"Humpro DPRD Kabupaten Purbalingga"* serta menyediakan tombol pintas `⚡ Humpro DPRD`.
- **Penyempurnaan Styling Meta Box Admin:**
  - Memperbesar box area *Caption* dan *Blockquote*.
  - Memperkecil lebar kolom angka nomor paragraf.
  - Mengubah warna tombol/link *Hapus Gambar* menjadi merah.
  - Menambahkan *auto-expand* tinggi textarea Keterangan Foto Utama secara dinamis saat diketik.

### 3. Performa Server & Optimasi Gambar WebP
- **Konversi & Kompresi WebP Adaptif:** Membuat algoritma kompresi otomatis untuk gambar yang diunggah (JPG/PNG) menjadi format **WebP** dengan tingkat kompresi adaptif (target maksimal 400 KB, resolusi tajam 1920px 90% kualitas).
- **Penghapusan File Mentah Asli:** Memastikan file mentah JPG/PNG yang asli langsung otomatis dihapus dari server setelah terkonversi ke WebP untuk menghemat kapasitas hosting.
- **Pembersihan File Duplikat Sub-size:** Membersihkan file duplikat sub-ukuran bawaan WordPress (`-150x150`, `-300x200`, dll) dan mematikan generator sub-sizes otomatis.
- **Optimasi Memori:** Mengalokasikan batas memori PHP 256M saat proses kompresi foto berukuran besar.

### 4. Pengisian Data & Struktur Alat Kelengkapan
- **Keanggotaan Badan Anggaran:** Mengimpor dan menyusun keanggotaan Badan Anggaran periode 2024–2029 lengkap (25 anggota).
- **Struktur Komisi & Bapemperda:** Memperbaiki butir tugas *repeater*, menambahkan fitur *search select* anggota, serta mengimpor foto dan struktur keanggotaan Bapemperda dan Komisi I, II, III, IV.
- **Penambahan Konten SAKIP & PPID:** Menyesuaikan backend SAKIP & PPID serta mengunggah dokumen PDF terkait.
- **Entri Foto Galeri:** Menambahkan foto kegiatan baru ke CPT Galeri dengan kategori *Rapat Paripurna* dan *Rapat Komisi*.

---

## 📅 Jumat, 7 Agustus 2026

### 1. Halaman Galeri Kegiatan & Paginasi Responsif
- **Layout & Grid Paginasi:** Menyetel layout galeri desktop menjadi 2 kolom ke kanan dan 10 baris ke bawah (20 kartu per halaman).
- **Paginasi Responsif Mobile:** Mengatur batas kartu galeri secara dinamis — **maksimal 10 kartu ke bawah** per halaman pada layar HP / mobile (`< 640px`) dan **20 kartu** per halaman pada desktop (`>= 640px`).
- **Desain Filter Popover Minimalis:** Mengubah tombol filter menjadi kotak presisi (`w-12 h-12`) dengan ikon 3-garis horizontal (`filter-3`) dan menu popover melayang tanpa teks terlipat (`whitespace-nowrap`). Penyesuaian warna ikon filter disamakan dengan ikon pencarian (`text-body-secondary`).
- **Otomatisasi Judul dari Nama File Foto:** Saat foto diunggah, nama file foto (misal: `2026.05.22 RAPAT KOMISI II.jpg`) secara otomatis diproses, dibersihkan dari garis/strip dan ekstensi file, lalu diisikan langsung sebagai Judul Galeri secara instan.
- **Ekstraksi Tanggal & Pengurutan Kronologis (Terbaru Paling Atas):** Mengembangkan parser tanggal kustom yang mendeteksi berbagai format tanggal di judul (`YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`) untuk mengurutkan seluruh galeri dari **tanggal terbaru di paling atas** hingga **terlama di paling bawah**.
- **Pembersihan Ekstensi File pada Judul:** Otomatis membersihkan ekstensi file `.jpg`, `.Jpg`, `.png`, `.webp` dari tampilan judul publik.

### 2. Sistem Manajemen Kategori Admin (Unified Category Manager)
- **Single Meta Box Tunggal ("Kategori [Tipe Konten]"):** Menyatukan Meta Box kategori bawaan yang duplikat menjadi 1 box kustom tunggal yang bersih di sidebar kanan WP Admin.
- **Fitur Hapus & Tambah Kategori Instan:** Menyediakan tombol merah **Hapus** pada setiap item kategori via AJAX langsung dari Meta Box dan WP Admin Sidebar Menu (dilengkapi dialog konfirmasi), serta form **+ Tambah Kategori Baru**.
- **Scoping Tipe Konten:** Mengaktifkan Meta Box kategori khusus pada `Galeri`, `Berita`, dan `Alat Kelengkapan`, serta mematikan Meta Box kategori pada CPT dokumen (`SAKIP`, `PPID`, `Propemperda`) agar tampilan admin rapi.

### 3. Pencarian Global & UI Dokumen/Berita
- **Unduhan Berkas Dokumen Langsung:** Tautan berkas PDF pada hasil pencarian dokumen (SAKIP, PPID, Propemperda) langsung ditampilkan di dalam kartu pencarian, sehingga ketika di-klik akan **langsung mengunduh/membuka file PDF**.
- **Pengalihan Otomatis URL Tunggal (Single Redirect):** Mengalihkan pengguna secara otomatis dari URL tunggal CPT dokumen yang kosong ke file PDF atau ke halaman arsip utama dengan *accordion* kategori terbuka.
- **Desain Kartu Dokumen Bersih (*Clear*):** Menghapus garis pembatas (`border-t`) dan label teks tambahan pada kartu hasil pencarian dokumen agar tampil rapi dan *clear*.
- **Item Anggota Hover-only:** Item hasil pencarian Anggota & Organisasi dapat di-hover secara interaktif namun tidak dapat di-klik menuju URL tunggal kosong.
- **Tombol "Lihat Semua Berita" Borderless:** Menghapus kotak outline border dan background hover pada tombol *Lihat Semua Berita* di sidebar artikel serupa (`Lihat Semua Berita →`).

### 4. Dokumentasi & Sinkronisasi Repositori Git
- Pembaruan dokumen `progres-Ghilbran.md` dan pembuatan `LOGBOOK-Ghilbran.md`.
- Sinkronisasi repositori Git (`git add`, `git commit`, `git pull --rebase`, `git push origin main`) hingga status `Everything up-to-date`.

---
**Ringkasan Status Proyek:** Seluruh fungsi backend, frontend, interaktivitas Vanilla JS, optimasi media WebP, dan manajemen admin kustom telah 100% selesai dan siap menuju tahap penyelesaian akhir (Seeding data & Go-Live).
