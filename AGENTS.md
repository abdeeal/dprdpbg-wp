# Alur Migrasi: Next.js (Headless) → Full WordPress (100% Gratis)
### Website DPRD Kabupaten Purbalingga

Revisi dari dokumen migrasi sebelumnya — **tanpa plugin berbayar sama sekali**
(tidak pakai ACF Pro, tidak pakai Meta Box premium). Semua field custom,
repeater/group, dan options page dibangun native lewat WordPress core API.

Referensi: `AGENTS.md` (konvensi project), seluruh isi `src/` repo
`github.com/abdeeal/dprd-kab-purbalingga`.

---

## Fase 0 — Setup Lingkungan Lokal & Database

1. XAMPP: cukup nyalakan **Apache** saja (MySQL lokal tidak dipakai).
2. Database: **Aiven free-tier MySQL** (cloud, gratis selamanya, mendukung remote access) — sudah dikonfigurasi di `wp-config.php` (host, port, SSL CA).
3. Custom theme kosong di `wp-content/themes/dprd-purbalingga/` — sudah dibuat (`style.css`, `index.php`, `functions.php`).
4. `.gitignore` sudah disiapkan (`wp-config.php`, `*.pem`, `node_modules/`, `assets/dist/`, dsb.) supaya kredensial Aiven tidak bocor ke repo public.

**Checklist Fase 0**
- [x] WordPress lokal jalan, connect ke Aiven
- [x] Custom theme aktif
- [x] `.gitignore` aman

---

## Fase 0.5 — Tooling Pengganti ACF Pro/Meta Box (Native, Gratis)

Karena tidak pakai plugin field-builder premium, kita bangun 3 komponen dasar sendiri di dalam theme — dipakai berulang di semua CPT nanti:

### A. Field Sederhana → `add_meta_box()` native
Untuk field non-repeater (text, textarea, image, select, checkbox, date) — pakai WordPress core API langsung, tanpa plugin apa pun.

### B. Field Repeater/Group → Custom Meta Box + JS manual
Untuk kebutuhan seperti `navigation.data.js` (menu bercabang), `tugasList`, `members` — dibangun sendiri dengan pola "tambah/hapus baris" (vanilla JS, data disimpan sebagai **JSON string** di 1 field `wp_postmeta`, bukan array serialize PHP biasa, supaya gampang di-decode di template).

**File baru:** `inc/class-repeater-field.php` — 1 class reusable, dipakai untuk semua repeater di seluruh CPT (bukan bikin ulang tiap kali butuh repeater).

```php
<?php
/**
 * Reusable Repeater Meta Box (pengganti ACF Repeater — 100% native & gratis)
 * Data disimpan sebagai JSON di 1 meta key, decode via get_dprd_repeater().
 */
class DPRD_Repeater_Field {
    private $id;
    private $title;
    private $post_type;
    private $sub_fields; // ['key' => 'label'] — kolom tiap baris repeater

    public function __construct($id, $title, $post_type, $sub_fields) {
        $this->id = $id;
        $this->title = $title;
        $this->post_type = $post_type;
        $this->sub_fields = $sub_fields;

        add_action('add_meta_boxes', [$this, 'register']);
        add_action('save_post', [$this, 'save']);
    }

    public function register() {
        add_meta_box($this->id, $this->title, [$this, 'render'], $this->post_type, 'normal', 'default');
    }

    public function render($post) {
        wp_nonce_field('dprd_repeater_' . $this->id, $this->id . '_nonce');
        $raw = get_post_meta($post->ID, $this->id, true);
        $rows = $raw ? json_decode($raw, true) : [];
        ?>
        <div class="dprd-repeater" data-field-id="<?php echo esc_attr($this->id); ?>">
            <table class="widefat dprd-repeater-table">
                <thead>
                    <tr>
                        <?php foreach ($this->sub_fields as $label) : ?>
                            <th><?php echo esc_html($label); ?></th>
                        <?php endforeach; ?>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="dprd-repeater-rows">
                    <?php foreach ($rows as $i => $row) : ?>
                        <?php $this->render_row($i, $row); ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="button" class="button dprd-add-row">+ Tambah Baris</button>
            <input type="hidden" class="dprd-repeater-data" name="<?php echo esc_attr($this->id); ?>" value="">
        </div>
        <?php
    }

    private function render_row($index, $row) {
        echo '<tr class="dprd-repeater-row">';
        foreach (array_keys($this->sub_fields) as $key) {
            $value = esc_attr($row[$key] ?? '');
            echo "<td><input type=\"text\" class=\"widefat\" data-key=\"{$key}\" value=\"{$value}\"></td>";
        }
        echo '<td><button type="button" class="button dprd-remove-row">Hapus</button></td>';
        echo '</tr>';
    }

    public function save($post_id) {
        if (!isset($_POST[$this->id . '_nonce']) || !wp_verify_nonce($_POST[$this->id . '_nonce'], 'dprd_repeater_' . $this->id)) return;
        if (!isset($_POST[$this->id])) return;

        $json = wp_unslash($_POST[$this->id]); // dikirim sebagai JSON string dari JS
        $decoded = json_decode($json, true);

        if (is_array($decoded)) {
            update_post_meta($post_id, $this->id, wp_json_encode($decoded));
        }
    }
}

/**
 * Helper ambil data repeater di template — mirip have_rows()/get_field() ACF
 */
function get_dprd_repeater($post_id, $field_id) {
    $raw = get_post_meta($post_id, $field_id, true);
    return $raw ? json_decode($raw, true) : [];
}
```

**File JS:** `src/js/admin-repeater.js` (di-enqueue khusus halaman admin edit post):
```js
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.dprd-repeater').forEach((wrapper) => {
    const tbody = wrapper.querySelector('.dprd-repeater-rows');
    const hiddenInput = wrapper.querySelector('.dprd-repeater-data');
    const addBtn = wrapper.querySelector('.dprd-add-row');

    function syncToHiddenInput() {
      const rows = [...tbody.querySelectorAll('.dprd-repeater-row')].map((row) => {
        const data = {};
        row.querySelectorAll('input[data-key]').forEach((input) => {
          data[input.dataset.key] = input.value;
        });
        return data;
      });
      hiddenInput.value = JSON.stringify(rows);
    }

    addBtn.addEventListener('click', () => {
      const newRow = tbody.querySelector('.dprd-repeater-row')?.cloneNode(true)
        ?? document.createElement('tr'); // fallback kalau belum ada row sama sekali
      newRow.querySelectorAll('input').forEach((input) => (input.value = ''));
      tbody.appendChild(newRow);
      attachRemoveHandler(newRow);
    });

    function attachRemoveHandler(row) {
      row.querySelector('.dprd-remove-row')?.addEventListener('click', () => {
        row.remove();
        syncToHiddenInput();
      });
    }

    tbody.querySelectorAll('.dprd-repeater-row').forEach(attachRemoveHandler);

    // Sync sebelum form submit
    wrapper.closest('form')?.addEventListener('submit', syncToHiddenInput);
  });
});
```

### C. Options Page → `add_menu_page()` native
Pengganti ACF Options Page — untuk navigasi, banner beranda, stats hero. Native WordPress, gratis 100%.

```php
<?php
// inc/options-pages.php
add_action('admin_menu', function () {
    add_menu_page(
        'Pengaturan Situs DPRD',
        'Pengaturan Situs',
        'manage_options',
        'dprd-site-settings',
        'dprd_render_site_settings_page',
        'dashicons-admin-generic',
        60
    );
});

function dprd_render_site_settings_page() {
    if (isset($_POST['dprd_settings_nonce']) && wp_verify_nonce($_POST['dprd_settings_nonce'], 'dprd_save_settings')) {
        update_option('dprd_navigation_json', wp_unslash($_POST['dprd_navigation_json'] ?? ''));
        update_option('dprd_hero_stats_anggota', sanitize_text_field($_POST['dprd_hero_stats_anggota'] ?? ''));
        // ... field lain (banner, stats lain) ditambahkan sesuai kebutuhan
        echo '<div class="notice notice-success"><p>Pengaturan disimpan.</p></div>';
    }

    $navigation = get_option('dprd_navigation_json', '[]');
    ?>
    <div class="wrap">
        <h1>Pengaturan Situs DPRD Purbalingga</h1>
        <form method="post">
            <?php wp_nonce_field('dprd_save_settings', 'dprd_settings_nonce'); ?>

            <h2>Navigasi Menu</h2>
            <div class="dprd-repeater" data-field-id="dprd_navigation_json">
                <!-- Struktur repeater bercabang sama pola dengan Bagian B di atas,
                     cuma nested (children di dalam tiap row) -->
            </div>

            <h2>Statistik Hero Beranda</h2>
            <table class="form-table">
                <tr>
                    <th><label>Jumlah Anggota</label></th>
                    <td><input type="text" name="dprd_hero_stats_anggota" class="regular-text"></td>
                </tr>
            </table>

            <?php submit_button('Simpan Pengaturan'); ?>
        </form>
    </div>
    <?php
}

// Helper ambil di template — mirip get_field() untuk options page
function dprd_get_option_json($key) {
    return json_decode(get_option($key, '[]'), true);
}
```

**Checklist Fase 0.5**
- [ ] `DPRD_Repeater_Field` class jalan, bisa tambah/hapus baris di admin
- [ ] Data repeater tersimpan sebagai JSON valid di `wp_postmeta`
- [ ] Options page "Pengaturan Situs" muncul di sidebar admin
- [ ] `get_dprd_repeater()` dan `dprd_get_option_json()` bisa dipanggil dari template PHP

---

## Fase 1 — Design System & Asset Dasar

*(Tidak berubah dari rencana sebelumnya)*

1. Salin token warna dari `tailwind.config.js` project Next.js.
2. Salin override `@layer utilities` dari `globals.css` apa adanya.
3. Font (Fraunces, Plus Jakarta Sans, JetBrains Mono, Montserrat) — self-host atau via Google Fonts `<link>` di `header.php`.
4. Pindahkan aset dari `public/images/` ke `wp-content/themes/dprd-purbalingga/assets/images/`.
5. Vite + Tailwind v4 build pipeline — sudah disiapkan (`vite.config.js`, `tailwind.config.js`, `src/css/main.css`, `src/js/main.js`).

**Checklist Fase 1**
- [ ] Token warna & 4 font identik dengan versi Next.js
- [ ] `npm run build` menghasilkan `assets/dist/main.css` & `main.js` yang ter-enqueue

---

## Fase 2 — Content Model: CPT + Custom Meta Box (Native)

| # | Sumber (`data/` + `lib/api/`) | CPT / Struktur WP | Jenis field | Cara implementasi |
|---|---|---|---|---|
| 1 | `berita.data.js` | CPT `berita` | excerpt, content (editor native), day, time, author, category (taxonomy), tags (taxonomy), featuredImage (native), isFeatured | Field sederhana → `add_meta_box()` biasa |
| 2 | `galeri.data.js` | CPT `galeri` | caption, tanggal, kategori (taxonomy) | Field sederhana |
| 3 | `agenda.data.js` | CPT `agenda` | tanggal, waktu, lokasi, deskripsi | Field sederhana |
| 4 | `banner.data.js` | Options page (bagian dari "Pengaturan Situs") | repeater: gambar, judul, link | `DPRD_Repeater_Field` |
| 5 | `video.data.js` | CPT `video` | url/embed, judul, thumbnail | Field sederhana |
| 6 | `pimpinan.data.js` | CPT `anggota` + taksonomi `jabatan` | nama, foto, jabatan, periode | Field sederhana |
| 7 | 14 file komisi/fraksi/badan | **1 CPT `alat-kelengkapan`** + taksonomi `jenis` | title, subtitle, **members (repeater)**, dasarPembentukanContent, **tugasList (repeater)** | Field sederhana + 2x `DPRD_Repeater_Field` |
| 8 | `navigation.data.js` | Options page, field `dprd_navigation_json` | repeater bercabang (nested) | `DPRD_Repeater_Field` versi nested (title, href, children) |
| 9 | `ppid.data.js`, `propemperda.data.js`, `sakip.data.js` | CPT masing-masing | campuran field sederhana + repeater sesuai isi `PpidClient.jsx` dkk. | Kombinasi |
| 10 | `sekilasPurbalingga.data.js` | Page statis + beberapa custom meta box per sub-section (Hidrologi, Kepegawaian, dst.) | field sederhana per section | Field sederhana (pengganti Flexible Content — karena strukturnya per-halaman sudah tetap, tidak perlu layout dinamis) |
| 11 | `tokohSejarah.data.js` | CPT `tokoh-sejarah` | nama, foto, deskripsi, periode | Field sederhana |
| 12 | `beranda.data.js` | Options page (stats hero) | anggota, fraksi, komisi, periode | Field sederhana di options page |
| 13 | `search.js` (fitur baru) | Tidak perlu CPT baru | — | Lihat Fase 6 |

**Struktur file baru di theme:**
```
inc/
├── class-repeater-field.php     # dari Fase 0.5
├── post-types.php               # register_post_type() semua CPT
├── taxonomies.php                # register_taxonomy() semua taksonomi
├── meta-boxes/
│   ├── berita.php
│   ├── galeri.php
│   ├── alat-kelengkapan.php      # pakai DPRD_Repeater_Field 2x (members, tugasList)
│   └── ...
└── options-pages.php             # dari Fase 0.5
```

`functions.php` tinggal require semua file ini:
```php
require get_template_directory() . '/inc/class-repeater-field.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/taxonomies.php';
require get_template_directory() . '/inc/options-pages.php';
foreach (glob(get_template_directory() . '/inc/meta-boxes/*.php') as $file) {
    require $file;
}
```

**Checklist Fase 2**
- [ ] Semua CPT & taksonomi terdaftar
- [ ] Field sederhana tiap CPT berfungsi (input & simpan)
- [ ] Repeater `members` & `tugasList` di CPT `alat-kelengkapan` berfungsi
- [ ] Options page navigasi & stats beranda berfungsi

---

## Fase 3 — Pemetaan Halaman → Template Hierarchy

*(Tidak berubah dari rencana sebelumnya — lihat tabel lengkap di dokumen migrasi awal)*

Ringkasan: `front-page.php`, `archive-berita.php`, `single-berita.php`, `page-galeri.php`, `page-pencarian.php`, `page-reservasi.php`, `single-alat-kelengkapan.php` (1 template untuk 14 halaman komisi/fraksi/badan), dst. — total 35 halaman dipetakan 1:1 ke template hierarchy WordPress, permalink dijaga identik dengan Next.js untuk SEO.

**Checklist Fase 3**
- [ ] Semua 35 halaman punya template
- [ ] Permalink structure sesuai slug asli

---

## Fase 4 — Convert Komponen → PHP Template Parts

*(Tidak berubah dari rencana sebelumnya)*

- `components/ui/*` → `template-parts/ui/*.php`
- `Navbar`/`Footer` → `header.php`/`footer.php`
- `components/sections/<halaman>/*` → `template-parts/sections/<halaman>/*.php`
- `AlatKelengkapanLayout.jsx` → 1 fungsi PHP reusable, ambil data lewat `get_dprd_repeater()` untuk `members` & `tugasList`

**Checklist Fase 4**
- [ ] Semua section ter-render lewat `get_template_part()`
- [ ] Template `single-alat-kelengkapan.php` menampilkan data dari repeater dengan benar

---

## Fase 5 — GSAP & Interaktivitas Client-Side

*(Tidak berubah dari rencana sebelumnya)*

`FadeIn`, `AnimatedCounter`, `Navbar` (shrink-on-scroll) → vanilla JS di `main.js`. Komponen client lain (`GaleriClient`, `PpidClient`, `SakipClient`, `PropemperdaClient`, `AccordionItem`, `Marquee`, `PencarianClient`) → vanilla JS/event listener, semuanya kecil dan ringan dikonversi.

**Checklist Fase 5**
- [ ] Semua animasi scroll-trigger identik dengan Next.js
- [ ] Semua filter/interaksi client-side berfungsi tanpa React

---

## Fase 6 — Fitur Pencarian (Custom, Native)

*(Tidak berubah dari rencana sebelumnya — tidak butuh plugin apa pun)*

1. Port `normalizeText.js` → PHP (`strip_tags()`, `mb_strtolower()`, `preg_replace()`).
2. `dprd_search_berita_galeri($query)` — AND-logic antar kata, scan `berita` (title+content+excerpt+tags) & `galeri` (meta `caption`).
3. `page-pencarian.php` render Berita → divider → Galeri sesuai `PRD-Halaman-Pencarian-DPRD-Purbalingga.md`.

**Checklist Fase 6**
- [ ] Search AND-logic bekerja
- [ ] Urutan & divider sesuai state matrix PRD

---

## Fase 7 — Form Reservasi Kunjungan

*(Tidak berubah dari rencana sebelumnya)*

Markup + stepper + drag-drop file → vanilla JS. Submit handler baru: `admin-post.php` + nonce + `wp_handle_upload()`, simpan sebagai CPT `reservasi` (field sederhana, tidak perlu repeater).

**Checklist Fase 7**
- [ ] Form tampil & submit berhasil
- [ ] Validasi file upload aman

---

## Fase 8 — Migrasi Data & QA Akhir

1. Migrasi data dummy → CMS asli (manual atau script `wp_insert_post()` + `update_post_meta()`/JSON repeater).
2. Bandingkan tiap halaman vs Next.js live & Figma.
3. Cek permalink, SEO meta, optimasi gambar.
4. Production: pindah `wp-config.php` dari Aiven → MySQL cPanel, hanya migrasi struktur tabel kosong (bukan data dummy).

**Checklist Fase 8**
- [ ] Semua halaman termigrasi & QA lolos
- [ ] Siap cutover ke production

---

## Ringkasan Biaya

| Komponen | Biaya |
|---|---|
| XAMPP | Gratis |
| Database dev (Aiven free tier) | Gratis |
| Custom theme + build tool (Vite, Tailwind v4) | Gratis (open source) |
| Field custom & repeater (native, tanpa ACF Pro/Meta Box) | Gratis (dibangun sendiri) |
| Options page (native `add_menu_page()`) | Gratis |
| GSAP (sudah dipakai di Next.js, tetap dipakai di WP) | Gratis (open source) |
| **Total** | **Rp 0** |

---

## Ringkasan Urutan Eksekusi

```
Fase 0:   Setup WP lokal + database Aiven + theme skeleton
Fase 0.5: Bangun sistem repeater & options page native (pengganti ACF Pro/Meta Box)
Fase 1:   Design system (Tailwind + font)
Fase 2:   CPT + custom meta box (field sederhana & repeater)
Fase 3:   Template hierarchy per halaman
Fase 4:   Convert komponen → template parts
Fase 5:   GSAP & interaktivitas vanilla JS
Fase 6:   Fitur pencarian (custom search logic)
Fase 7:   Form reservasi + backend handler
Fase 8:   Migrasi data + QA + go-live
```