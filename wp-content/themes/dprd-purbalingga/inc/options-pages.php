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
                'image' => ['label' => 'Gambar', 'type' => 'image'],
                'title' => ['label' => 'Judul', 'type' => 'text'],
                'link'  => ['label' => 'Link', 'type' => 'url'],
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
        update_option('dprd_hero_stats_anggota', sanitize_text_field($_POST['dprd_hero_stats_anggota'] ?? ''));
        update_option('dprd_hero_stats_fraksi', sanitize_text_field($_POST['dprd_hero_stats_fraksi'] ?? ''));
        update_option('dprd_hero_stats_komisi', sanitize_text_field($_POST['dprd_hero_stats_komisi'] ?? ''));
        update_option('dprd_hero_stats_periode', sanitize_text_field($_POST['dprd_hero_stats_periode'] ?? ''));

        echo '<div class="notice notice-success is-dismissible"><p>Pengaturan disimpan.</p></div>';
    }

    // ── Ambil data tersimpan ───────────────────────────────────────
    $banner_rows     = dprd_get_option_repeater('dprd_banner_json');

    $stats_anggota = get_option('dprd_hero_stats_anggota', '');
    $stats_fraksi  = get_option('dprd_hero_stats_fraksi', '');
    $stats_komisi  = get_option('dprd_hero_stats_komisi', '');
    $stats_periode = get_option('dprd_hero_stats_periode', '');
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

            <hr>

            <h2>Banner Beranda</h2>
            <?php dprd_get_banner_repeater()->render_field_only($banner_rows); ?>

            <hr>

            <h2>Statistik Hero Beranda</h2>
            <table class="form-table">
                <tr>
                    <th><label for="dprd_hero_stats_anggota">Jumlah Anggota</label></th>
                    <td><input type="text" id="dprd_hero_stats_anggota" name="dprd_hero_stats_anggota" class="regular-text" value="<?php echo esc_attr($stats_anggota); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_fraksi">Jumlah Fraksi</label></th>
                    <td><input type="text" id="dprd_hero_stats_fraksi" name="dprd_hero_stats_fraksi" class="regular-text" value="<?php echo esc_attr($stats_fraksi); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_komisi">Jumlah Komisi</label></th>
                    <td><input type="text" id="dprd_hero_stats_komisi" name="dprd_hero_stats_komisi" class="regular-text" value="<?php echo esc_attr($stats_komisi); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_periode">Periode</label></th>
                    <td><input type="text" id="dprd_hero_stats_periode" name="dprd_hero_stats_periode" class="regular-text" value="<?php echo esc_attr($stats_periode); ?>" placeholder="mis. 2024-2029"></td>
                </tr>
            </table>

            <?php submit_button('Simpan Pengaturan'); ?>
        </form>
    </div>
    <?php
}
