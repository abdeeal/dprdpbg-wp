<?php
/**
 * Options Page: Pengaturan Situs DPRD Purbalingga
 * Pengganti ACF Options Page — 100% native, gratis.
 *
 * Menyimpan 3 kelompok data ke wp_options:
 * - dprd_navigation_json  → repeater bercabang (nested), lewat DPRD_Repeater_Field
 * - dprd_banner_json      → repeater biasa (gambar, judul, link)
 * - dprd_hero_stats_*     → field sederhana (anggota, fraksi, komisi, periode)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) {
    exit;
}

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

/**
 * Definisikan instance repeater di sini (bukan lewat add_meta_boxes,
 * karena ini bukan post type — kita panggil render_field_only() &
 * sanitize_from_post() secara manual).
 */


function dprd_get_banner_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_banner_json',
            'Banner Beranda',
            null,
            [
                'image' => ['label' => 'Gambar', 'type' => 'image', 'crop' => '16/9'],
            ],
            false
        );
    }
    return $instance;
}

// Ensure the repeater singletons are instantiated early enough so their
// enqueue_scripts hooks are registered before admin_enqueue_scripts fires.
add_action('admin_init', function() {
    dprd_get_banner_repeater();
});

function dprd_render_site_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // ── Handle submit ──────────────────────────────────────────────
    if (
        isset($_POST['dprd_settings_nonce'])
        && wp_verify_nonce($_POST['dprd_settings_nonce'], 'dprd_save_settings')
    ) {
        // Banner (repeater biasa)
        if (isset($_POST['dprd_banner_json'])) {
            $banner = dprd_get_banner_repeater();
            $clean_json = $banner->sanitize_from_post($_POST['dprd_banner_json']);
            update_option('dprd_banner_json', $clean_json);
        }

        // Hero stats (field sederhana)
        update_option('dprd_hero_stats_anggota', absint($_POST['dprd_hero_stats_anggota'] ?? 0));
        update_option('dprd_hero_stats_fraksi', absint($_POST['dprd_hero_stats_fraksi'] ?? 0));
        update_option('dprd_hero_stats_komisi', absint($_POST['dprd_hero_stats_komisi'] ?? 0));
        update_option('dprd_hero_stats_periode_mulai', absint($_POST['dprd_hero_stats_periode_mulai'] ?? 0));
        update_option('dprd_hero_stats_periode_akhir', absint($_POST['dprd_hero_stats_periode_akhir'] ?? 0));
        update_option('dprd_pengumuman_strip', sanitize_text_field($_POST['dprd_pengumuman_strip'] ?? ''));

        echo '<div class="notice notice-success is-dismissible"><p>Pengaturan disimpan.</p></div>';
    }

    // ── Ambil data tersimpan ───────────────────────────────────────
    $banner_rows     = dprd_get_option_repeater('dprd_banner_json');

    $stats_anggota = get_option('dprd_hero_stats_anggota', '');
    $stats_fraksi  = get_option('dprd_hero_stats_fraksi', '');
    $stats_komisi  = get_option('dprd_hero_stats_komisi', '');
    $stats_periode_mulai = get_option('dprd_hero_stats_periode_mulai', '');
    $stats_periode_akhir = get_option('dprd_hero_stats_periode_akhir', '');
    $pengumuman_strip    = get_option('dprd_pengumuman_strip', '');
    ?>
    <div class="wrap">
        <h1>Pengaturan Situs DPRD Purbalingga</h1>
        <form method="post">
            <?php wp_nonce_field('dprd_save_settings', 'dprd_settings_nonce'); ?>

            <h2>Navigasi Menu</h2>
            <p class="description">
                Pengaturan Navigasi Menu telah dipindahkan ke fitur bawaan WordPress.<br>
                Silakan atur menu (mendukung multi-level tanpa batas) melalui <strong><a href="<?php echo admin_url('nav-menus.php'); ?>">Appearance &gt; Menus</a></strong>.
            </p>

            <h2>Strip Pengumuman (Teks Berjalan)</h2>
            <table class="form-table">
                <tr>
                    <th><label for="dprd_pengumuman_strip">Teks Pengumuman</label></th>
                    <td>
                        <textarea id="dprd_pengumuman_strip" name="dprd_pengumuman_strip" class="large-text" rows="3" placeholder="mis. Sidang Paripurna berlangsung hari ini, pukul 09.00 WIB di Ruang Rapat Paripurna DPRD."><?php echo esc_textarea($pengumuman_strip); ?></textarea>
                        <p class="description">Teks ini akan muncul sebagai pengumuman berjalan (marquee) di bagian atas website. Kosongkan jika tidak ada pengumuman.</p>
                    </td>
                </tr>
            </table>

            <hr>

            <h2>Banner Beranda</h2>
            <?php dprd_get_banner_repeater()->render_field_only($banner_rows); ?>

            <hr>

            <h2>Statistik Hero Beranda</h2>
            <table class="form-table">
                <tr>
                    <th><label for="dprd_hero_stats_anggota">Jumlah Anggota Dewan</label></th>
                    <td><input type="number" min="0" id="dprd_hero_stats_anggota" name="dprd_hero_stats_anggota" class="regular-text" value="<?php echo esc_attr($stats_anggota); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_fraksi">Jumlah Fraksi</label></th>
                    <td><input type="number" min="0" id="dprd_hero_stats_fraksi" name="dprd_hero_stats_fraksi" class="regular-text" value="<?php echo esc_attr($stats_fraksi); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_komisi">Jumlah Komisi</label></th>
                    <td><input type="number" min="0" id="dprd_hero_stats_komisi" name="dprd_hero_stats_komisi" class="regular-text" value="<?php echo esc_attr($stats_komisi); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_periode_mulai">Periode Mulai Jabatan</label></th>
                    <td><input type="number" min="1900" id="dprd_hero_stats_periode_mulai" name="dprd_hero_stats_periode_mulai" class="regular-text" value="<?php echo esc_attr($stats_periode_mulai ?: ''); ?>" placeholder="mis. 2024"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_periode_akhir">Periode Berakhir Jabatan</label></th>
                    <td><input type="number" min="1900" id="dprd_hero_stats_periode_akhir" name="dprd_hero_stats_periode_akhir" class="regular-text" value="<?php echo esc_attr($stats_periode_akhir ?: ''); ?>" placeholder="mis. 2029"></td>
                </tr>
            </table>

            <?php submit_button('Simpan Pengaturan'); ?>
        </form>
    </div>
    <?php
}
