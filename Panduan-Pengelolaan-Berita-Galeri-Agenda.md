# 📘 BUKU PANDUAN PENGGUNA (USER MANUAL)
## MODUL PENGELOLAAN BERITA, GALERI, DAN AGENDA
### Website Resmi DPRD Kabupaten Purbalingga

---

## 📋 DAFTAR ISI

- [BAB 1: PENDAHULUAN & PENGENALAN ANTARMUKA ADMIN](#bab-1-pendahuluan--pengenalan-antarmuka-admin)
  - [1.1 Alur Kerja Umum (Workflow)](#11-alur-kerja-umum-workflow)
  - [1.2 Struktur Layar Editor Admin](#12-struktur-layar-editor-admin)
- [BAB 2: PANDUAN PENGELOLAAN MODUL BERITA](#bab-2-panduan-pengelolaan-modul-berita)
  - [Langkah 1: Mengakses Menu Berita](#langkah-1-mengakses-menu-berita)
  - [Langkah 2: Menulis Judul Artikel Berita](#langkah-2-menulis-judul-artikel-berita)
  - [Langkah 3: Menulis Naskah Berita & Paragraf (Dropcap Otomatis)](#langkah-3-menulis-naskah-berita--paragraf-dropcap-otomatis)
  - [Langkah 4: Menentukan Foto Utama & Caption (Wajib)](#langkah-4-menentukan-foto-utama--caption-wajib)
  - [Langkah 5: Fitur Auto-Detect Tanggal Rilis & Preset Penulis](#langkah-5-fitur-auto-detect-tanggal-rilis--preset-penulis)
  - [Langkah 6: Menyelipkan Foto Tambahan di Tengah Artikel (Dengan Live Preview)](#langkah-6-menyelipkan-foto-tambahan-di-tengah-artikel-dengan-live-preview)
  - [Langkah 7: Menyelipkan Teks Kutipan (Blockquote)](#langkah-7-menyelipkan-teks-kutipan-blockquote)
  - [Langkah 8: Fitur Auto-Split Tags Berita](#langkah-8-fitur-auto-split-tags-berita)
  - [Langkah 9: Ringkasan Berita, Kategori, & Featured Slide](#langkah-9-ringkasan-berita-kategori--featured-slide)
  - [Langkah 10: Menerbitkan Berita (Publishing)](#langkah-10-menerbitkan-berita-publishing)
- [BAB 3: PANDUAN PENGELOLAAN MODUL GALERI KEGIATAN](#bab-3-panduan-pengelolaan-modul-galeri-kegiatan)
  - [Langkah 1: Mengakses Menu Galeri](#langkah-1-mengakses-menu-galeri)
  - [Langkah 2: Mengunggah Foto & Otomatisasi Judul dari Nama File](#langkah-2-mengunggah-foto--otomatisasi-judul-dari-nama-file)
  - [Langkah 3: Sistem Parser & Pengurutan Otomatis Berdasarkan Tanggal Judul](#langkah-3-sistem-parser--pengurutan-otomatis-berdasarkan-tanggal-judul)
  - [Langkah 4: Pengelolaan Kategori Galeri (Unified Category Manager)](#langkah-4-pengelolaan-kategori-galeri-unified-category-manager)
  - [Langkah 5: Fitur Paginasi Responsif Mobile (Max 10 Card/Halaman)](#langkah-5-fitur-paginasi-responsif-mobile-max-10-cardhalaman)
  - [Langkah 6: Menerbitkan Galeri](#langkah-6-menerbitkan-galeri)
- [BAB 4: PANDUAN PENGELOLAAN MODUL AGENDA TRANSPARANSI KINERJA](#bab-4-panduan-pengelolaan-modul-agenda-transparansi-kinerja)
  - [Langkah 1: Mengakses Menu Agenda](#langkah-1-mengakses-menu-agenda)
  - [Langkah 2: Menulis Judul Agenda Kegiatan](#langkah-2-menulis-judul-agenda-kegiatan)
  - [Langkah 3: Menentukan Tanggal & Jam (Validasi Tanggal Minimal Hari Ini)](#langkah-3-menentukan-tanggal--jam-validasi-tanggal-minimal-hari-ini)
  - [Langkah 4: Menerbitkan Agenda Ke Beranda](#langkah-4-menerbitkan-agenda-ke-beranda)
- [BAB 5: FITUR OTOMATISASI SISTEM & EFISIENSI KERJA](#bab-5-fitur-otomatisasi-sistem--efisiensi-kerja)
  - [5.1 Sistem Auto-Crop & Auto-Compress WebP Adaptif](#51-sistem-auto-crop--auto-compress-webp-adaptif)
  - [5.2 Fitur Dropcap Majalah Otomatis](#52-fitur-dropcap-majalah-otomatis)
  - [5.3 Fitur Live Preview Kata Terakhir Paragraf](#53-fitur-live-preview-kata-terakhir-paragraf)
- [BAB 6: TROUBLESHOOTING & PERTANYAAN SERING DITANYAKAN (FAQ)](#bab-6-troubleshooting--pertanyaan-sering-ditanyakan-faq)

---

## BAB 1: PENDAHULUAN & PENGENALAN ANTARMUKA ADMIN

Buku panduan ini disusun sebagai pedoman operasional standar (SOP) bagi Pengelola Konten / Admin Humas Sekretariat DPRD Kabupaten Purbalingga dalam mengoperasikan modul **Berita**, **Galeri Kegiatan**, dan **Agenda Transparansi Kinerja** pada sistem WordPress Native.

### 1.1 Alur Kerja Umum (Workflow)

```
[Mulai] ➔ [Login Dashboard Admin]
   │
   ├──> [Modul Berita]: Input Judul ➔ Dropcap Naskah ➔ Foto Utama/Caption ➔ Auto-Detect Tanggal & Preset Penulis ➔ Sisip Foto/Kutipan Paragraf ➔ Terbitkan
   │
   ├──> [Modul Galeri]: Unggah Foto ➔ Auto-Populate Judul Nama File ➔ Auto-Sort Tanggal Judul ➔ Kelola Kategori (Tambah/Hapus) ➔ Terbitkan
   │
   └──> [Modul Agenda]: Input Nama Agenda ➔ Tanggal (Min Today) & Jam ➔ Terbitkan (Otomatis Tampil di Beranda)
```

### 1.2 Struktur Layar Editor Admin

Layar Editor WordPress Admin terbagi menjadi **3 Blok Utama**:

1. **BLOK TENGAH (AREA UTAMA):**
   - Kolom Judul Utama.
   - Editor Naskah Artikel (Gutenberg / Block Editor).
   - Panel **Informasi Tambahan** (Hari, Jam, Penulis, Caption Utama / Uploader Media Foto).
   - Panel **📸 Foto & Caption Tambahan Berita** (Disisipkan di Tengah Artikel).
   - Panel **💬 Kutipan / Blockquote Berita** (Disisipkan di Tengah Artikel).
2. **BLOK SIDEBAR KANAN (PENGATURAN SISTEM):**
   - Panel **Gambar Utama** (Featured Image).
   - Panel **Kutipan / Excerpt** (Ringkasan singkat berita).
   - Panel **Kategori** (Dengan fitur unified tambah & hapus kategori kustom).
   - Panel **Tag** (Dengan fitur auto-split tags saat di-paste).
   - Panel **Pengaturan Berita** (Centang Featured Slide Utama).
3. **BLOK BILAH ATAS (BAR NAVIGASI):**
   - Tombol **Simpan Konsep (Draft)**.
   - Tombol **Pratinjau (Preview)**.
   - Tombol **Terbitkan (Publish)** / **Perbarui (Update)**.

---

## BAB 2: PANDUAN PENGELOLAAN MODUL BERITA

### Langkah 1: Mengakses Menu Berita
1. Buka peramban (browser) dan ketik URL Administrator: `http://localhost/dprd-purbalingga/wp-admin`
2. Masukkan **Username** dan **Password** akun Anda.
3. Pada bilah menu sebelah kiri dashboard, arahkan kursor ke menu **Berita**, lalu klik **Tambah Berita Baru**.

---

### Langkah 2: Menulis Judul Artikel Berita
1. Klik pada kolom paling atas yang bertuliskan *"Tambahkan judul berita"*.
2. Ketik judul berita yang ringkas, lugas, dan informatif.
3. **Standar Penulisan:** Gunakan kapital di setiap awal kata (Contoh: `DPRD Purbalingga Gelar Rapat Paripurna Persetujuan Raperda APBD 2026`).

---

### Langkah 3: Menulis Naskah Berita & Paragraf (Dropcap Otomatis)
1. Klik pada area kosong di bawah judul.
2. Tuliskan atau tempelkan (paste) naskah berita Anda.
3. Tekan **Enter** untuk membuat paragraf baru.
4. 💡 **Informasi:** Huruf pertama dari kalimat pembuka artikel akan **otomatis diformat menjadi Dropcap** (huruf besar tebal bergaya majalah kustom) saat berita diakses publik.

---

### Langkah 4: Menentukan Foto Utama & Caption (Wajib)

1. **Mengunggah Gambar Utama:**
   - Lihat ke **Sidebar Kanan**.
   - Buka panel **Gambar Utama** (Featured Image).
   - Klik **Tetapkan Gambar Utama**.
   - Pilih foto dari pustaka media atau klik tab *Unggah Berkas* untuk mengambil foto baru dari komputer.
   - Klik tombol **Tetapkan Gambar Utama** di pojok kanan bawah.
2. **Mengisi Keterangan Foto (Caption) — ⚠️ WAJIB:**
   - Gulir ke area bawah editor naskah, cari panel **Informasi Tambahan Berita**.
   - Cari kolom **Keterangan Foto Utama (Caption & Sumber Foto)**.
   - Ketik penjelasan foto beserta sumbernya.
   - *Contoh:* `Bupati Purbalingga menyerahkan berkas Raperda kepada Ketua DPRD dalam Rapat Paripurna (Foto: Humpro DPRD)`

---

### Langkah 5: Fitur Auto-Detect Tanggal Rilis & Preset Penulis

Masih di dalam panel **Informasi Tambahan Berita**:
1. **Fitur Auto-Detect Tanggal Rilis:**
   - Sistem secara otomatis memindai naskah artikel Anda. Jika di dalam paragraf pertama terdapat penyebutan tanggal (misal: *"Purbalingga, Kamis 9 Juli 2026"*), sistem akan **otomatis mendeteksi dan mengisikan tanggal rilis** dalam format resmi `Hari, DD NamaBulan YYYY` (Contoh: `Kamis, 9 Juli 2026`).
   - Anda juga dapat mengubah tanggal rilis secara manual di kolom yang tersedia.
2. **Preset Penulis / Sumber (`⚡ Humpro DPRD`):**
   - Kolom Nama Penulis secara default terisi *"Humpro DPRD Kabupaten Purbalingga"*.
   - Jika Anda mengubahnya dan ingin mengembalikannya dengan cepat, klik tombol preset **`⚡ Humpro DPRD`** di samping kolom.

---

### Langkah 6: Menyelipkan Foto Tambahan di Tengah Artikel (Dengan Live Preview)

Apabila Anda memiliki foto kegiatan tambahan yang ingin ditampilkan di antara paragraf:

1. Gulir ke panel **📸 Foto & Caption Tambahan Berita (Disisipkan di Tengah Artikel)**.
2. Klik tombol **+ Tambah Baris**.
3. Klik tombol **Pilih Gambar** untuk memilih foto dari pustaka media.
4. Ketik penjelasan foto pada kolom **Keterangan Foto (Caption)**.
5. **Menentukan Posisi Paragraf & Menggunakan Live Preview:**
   - Pada kolom **Disisipkan Setelah Paragraf Ke- (Angka)**, ketik angka urutan paragraf (Misal: ketik `2` agar foto muncul di bawah paragraf ke-2).
   - **🔴 INDIKATOR TEKS OTOMATIS (LIVE PREVIEW):**
     * Saat Anda mengetik angka `2`, secara otomatis akan muncul **kotak hijau** di bawah kolom tersebut yang menampilkan potongan kata terakhir dari paragraf ke-2 naskah Anda.
     * *Tampilan di layar:* `🟢 Akhir Paragraf ke-2: "...disetujui oleh seluruh anggota dewan."`
     * Jika angka melebihi jumlah paragraf, indikator akan berubah menjadi **kotak merah**: `⚠️ Artikel hanya memiliki 4 paragraf.`

---

### Langkah 7: Menyelipkan Teks Kutipan (Blockquote)

Untuk menonjolkan pernyataan penting pimpinan atau narasumber di tengah artikel:

1. Gulir ke panel **💬 Kutipan / Blockquote Berita (Disisipkan di Tengah Artikel)**.
2. Klik **+ Tambah Baris**.
3. Ketik isi pernyataan narasumber di kolom **Isi Teks Kutipan (Blockquote)**.
4. Ketik angka paragraf di kolom **Disisipkan Setelah Paragraf Ke-**.
5. Gunakan petunjuk **Live Preview** di bawah kolom untuk memastikan posisi kutipan sudah pas.

---

### Langkah 8: Fitur Auto-Split Tags Berita

1. Di **Sidebar Kanan**, buka panel **Tag Berita**.
2. Anda dapat menempelkan (paste) daftar kata kunci dari daftar berbutir (*bullet list*) atau daftar baris baru.
3. **Sistem Auto-Split:** Sistem secara otomatis memisahkan baris-baris teks tersebut menjadi tag-tag terpisah secara rapi tanpa perlu mengetikkan tanda koma satu per satu.

---

### Langkah 9: Ringkasan Berita, Kategori, & Featured Slide

1. **Ringkasan Berita (Excerpt):**
   - Di **Sidebar Kanan**, buka panel **Kutipan** (Excerpt).
   - Ketik ringkasan artikel sebanyak 1 hingga 2 kalimat yang akan tampil di kartu berita beranda.
2. **Kategori Berita:**
   - Buka panel **Kategori Berita** di sidebar kanan. Centang kategori yang sesuai (misal: *Rapat Paripurna*, *Komisi*, *Reses*, dll).
3. **Berita Utama (Featured Slide):**
   - Buka panel **Pengaturan Berita**. Centang **Tampilkan di Slide Utama (Featured)** jika berita ini ingin dijadikan berita sorotan utama (*Headline* slider beranda).

---

### Langkah 10: Menerbitkan Berita (Publishing)

1. Klik tombol **Pratinjau (Preview)** di kanan atas jika ingin melihat tampilan sementara.
2. Klik tombol **Terbitkan...** (Publish) lalu konfirmasi publikasi. Berita kini resmi tayang dan dapat diakses publik!

---

## BAB 3: PANDUAN PENGELOLAAN MODUL GALERI KEGIATAN

### Langkah 1: Mengakses Menu Galeri
1. Buka peramban dan navigasikan ke `http://localhost/dprd-purbalingga/wp-admin`
2. Di bilah menu sebelah kiri dashboard, klik menu **Galeri**, lalu klik **Tambah Galeri Baru**.

---

### Langkah 2: Mengunggah Foto & Otomatisasi Judul dari Nama File

1. Pada panel **Informasi Galeri**, klik tombol **Pilih / Unggah Foto**.
2. Unggah foto kegiatan baru dari komputer atau pilih foto dari pustaka media.
3. Klik tombol **Gunakan Foto Ini**.
4. ⚡ **FITUR OTOMATISASI JUDUL:**
   - Begitu foto dipilih (misal nama filenya: `2026.05.22_RAPAT_KOMISI_II.jpg`), sistem secara otomatis akan:
     * Menghapus ekstensi file (`.jpg`, `.png`, `.webp`, dll).
     * Mengubah tanda strip (`-`) dan garis bawah (`_`) menjadi spasi.
     * Mengubah format kata menjadi Kapital.
     * Langsung mengisikan kolom **Judul Galeri** secara instan (`2026.05.22 Rapat Komisi II`).

---

### Langkah 3: Sistem Parser & Pengurutan Otomatis Berdasarkan Tanggal Judul

* **Pengurutan Kronologis Otomatis:**
  * Sistem galeri dilengkapi parser tanggal pintar yang secara otomatis mendeteksi tanggal kegiatan dari judul foto (mendukung berbagai format: `YYYY.MM.DD`, `YYYY-MM-DD`, `DD-MM-YYYY`, `DD.MM.YYYY`, `DD [Bulan] YYYY`).
  * Di halaman publik `/galeri`, seluruh foto galeri secara otomatis diurutkan **dari tanggal terbaru di paling atas hingga terlama di paling bawah**.
  * Ekstensi file seperti `.jpg` atau `.png` yang tersisa pada judul secara otomatis dibersihkan dari tampilan publik sehingga tampil rapi.

---

### Langkah 4: Pengelolaan Kategori Galeri (Unified Category Manager)

1. Pada **Sidebar Kanan**, cari panel **Kategori Galeri**.
2. Centang kategori yang sesuai untuk foto kegiatan tersebut (misal: *Rapat Komisi*, *Rapat Paripurna*, *Kunjungan Kerja*, *Reses*, *Audiensi*).
3. 🔴 **FITUR HAPUS KATEGORI INSTAN:**
   * Jika ada kategori yang salah buat, Anda dapat langsung mengeklik tombol merah **Hapus** di sebelah kanan nama kategori tersebut.
   * Konfirmasi dialog hapus, dan kategori akan terhapus secara permanen dari database via AJAX tanpa perlu reload halaman.
4. ➕ **MENAMBAH KATEGORI BARU:**
   * Klik tombol **+ Tambah Kategori Baru** di bawah daftar untuk membuat pengelompokan kategori galeri baru secara langsung.

---

### Langkah 5: Fitur Paginasi Responsif Mobile (Max 10 Card/Halaman)

* **Tampilan Mobile (< 640px):** Di layar smartphone, daftar foto galeri secara otomatis dibatasi maksimal **10 kartu ke bawah** per halaman agar tidak terlalu panjang saat di-scroll. Jika terdapat lebih dari 10 foto, foto sisanya akan otomatis beralih ke Halaman 2 (*Pagination*).
* **Tampilan Desktop (>= 640px):** Di layar komputer/laptop, galeri menampilkan **20 kartu** per halaman (10 baris ke bawah $\times$ 2 kolom ke kanan).

---

### Langkah 6: Menerbitkan Galeri
1. Setelah foto diunggah, judul terisi, dan kategori dicentang, klik tombol **Terbitkan...** (Publish).
2. Foto kegiatan galeri kini resmi tayang pada grid beranda maupun halaman arsip galeri.

---

## BAB 4: PANDUAN PENGELOLAAN MODUL AGENDA TRANSPARANSI KINERJA

### Langkah 1: Mengakses Menu Agenda
1. Buka dashboard admin dan klik menu **Agenda** di bilah navigasi sebelah kiri.
2. Klik **Tambah Agenda Baru**.

---

### Langkah 2: Menulis Judul Agenda Kegiatan
1. Klik pada kolom *"Tambahkan judul agenda"*.
2. Ketik nama agenda kegiatan dewan (Contoh: `Rapat Paripurna Persetujuan Raperda APBD Kabupaten Purbalingga Tahun 2026`).

---

### Langkah 3: Menentukan Tanggal & Jam (Validasi Tanggal Minimal Hari Ini)

1. Gulir ke panel **Informasi Agenda**.
2. **Tanggal Kegiatan:**
   * Klik kolom tanggal dan pilih tanggal pelaksanaan kegiatan.
   * 🔒 **VALIDASI TANGGAL KINERJA:** Sistem secara otomatis membatasi tanggal minimal ke hari ini (`min=today`). Anda tidak dapat memilih tanggal lampau yang sudah lewat untuk menjaga keakuratan jadwal agenda mendahului pelaksanaan.
3. **Jam / Waktu Pelaksanaan:**
   * Ketik jam pelaksanaan kegiatan (Contoh: `09.00 WIB - Selesai`).

---

### Langkah 4: Menerbitkan Agenda Ke Beranda
1. Klik tombol **Terbitkan** (Publish).
2. Agenda kegiatan secara otomatis akan tampil pada widget **Agenda Transparansi Kinerja** di halaman depan (beranda) website berdasarkan urutan pelaksanaan terdekat.

---

## BAB 5: FITUR OTOMATISASI SISTEM & EFISIENSI KERJA

Website DPRD Purbalingga dilengkapi 3 fitur otomatisasi native yang berjalan di latar belakang:

### 5.1 Sistem Auto-Crop & Auto-Compress WebP Adaptif
Setiap foto (JPG / PNG) yang Anda unggah akan secara otomatis dikonversi oleh server menjadi format generasi baru **WebP** dengan tingkat kompresi adaptif optimal (maksimal 400 KB, resolusi 1920px 90% kualitas). File mentah JPG/PNG yang asli langsung otomatis dihapus dari server untuk menghemat kapasitas penyimpanan hosting.

### 5.2 Fitur Dropcap Majalah Otomatis
Sistem secara otomatis mengambil karakter pertama pada naskah berita Anda dan menerapkan gaya *dropcap* serif khas jurnalistik majalah tebal secara presisi.

### 5.3 Fitur Live Preview Kata Terakhir Paragraf
Memudahkan Admin saat menentukan posisi Foto & Kutipan tambahan berita dengan menampilkan potongan kata terakhir paragraf secara *real-time* saat angka diketik.

---

## BAB 6: TROUBLESHOOTING & PERTANYAAN SERING DITANYAKAN (FAQ)

* **Q1: Mengapa tombol Terbitkan Berita terkunci / tidak bisa diklik?**
  * **Jawab:** Kolom *Keterangan Foto Utama (Caption & Sumber Foto)* belum diisi. Lengkapi kolom tersebut pada panel Informasi Tambahan Berita.
* **Q2: Mengapa foto tambahan saya tidak muncul di posisi paragraf yang saya inginkan?**
  * **Jawab:** Periksa indikator teks hijau pada kolom *Disisipkan Setelah Paragraf Ke-*. Pastikan angka tidak melebihi total paragraf naskah artikel Anda.
* **Q3: Bagaimana cara menghapus kategori galeri atau berita yang salah buat?**
  * **Jawab:** Buka panel Kategori di sidebar kanan editor admin, lalu klik tombol merah **Hapus** di samping nama kategori tersebut.
* **Q4: Mengapa urutan foto galeri di website live berubah otomatis?**
  * **Jawab:** Sistem galeri secara otomatis mengurutkan foto berdasarkan tanggal yang tertera pada judul (format `YYYY.MM.DD`, dll) dari yang terbaru di paling atas hingga terlama di bawah.

---

*Buku Panduan Pengelolaan Modul Berita, Galeri, dan Agenda DPRD Kabupaten Purbalingga v3.0.*
