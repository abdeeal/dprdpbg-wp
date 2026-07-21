<?php
/**
 * Meta Boxes for Badan Pages (Bamus, Banggar, Bapemperda, Badan Kehormatan)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

/**
 * Singleton untuk instance repeater Tugas Badan
 */
function dprd_get_badan_tugas_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_badan_tugas_json',
            'Daftar Tugas Badan',
            null,
            [
                'kategori' => ['label' => 'Nama Kategori', 'type' => 'text'],
                'icon'     => ['label' => 'Ikon Lucide (contoh: calendar, users, file-check)', 'type' => 'text'],
                'poin'     => ['label' => 'Butir Tugas', 'type' => 'points'],
            ]
        );
    }
    return $instance;
}

/**
 * Singleton untuk instance repeater Sanksi Badan Kehormatan
 */
function dprd_get_bk_sanksi_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_bk_sanksi_json',
            'Jenis Sanksi yang Dapat Dijatuhkan',
            null,
            [
                'sanksi'     => ['label' => 'Sanksi', 'type' => 'text'],
                'keterangan' => ['label' => 'Keterangan Tambahan (opsional)', 'type' => 'text'],
            ]
        );
    }
    return $instance;
}

// Inisialisasi early agar asset di-enqueue
add_action('admin_init', function() {
    dprd_get_badan_tugas_repeater();
    dprd_get_bk_sanksi_repeater();
});

/**
 * Register meta boxes untuk template halaman badan
 */
add_action('add_meta_boxes_page', function ($post) {
    $template = get_post_meta($post->ID, '_wp_page_template', true);

    // 1. Badan Kehormatan
    if ($template === 'page-badan-kehormatan.php') {
        add_meta_box(
            'dprd_bk_page_details',
            'Pengaturan Konten Badan Kehormatan',
            'dprd_render_bk_page_details',
            'page',
            'normal',
            'high'
        );
        add_meta_box(
            'dprd_bk_sanksi_meta',
            'Jenis Sanksi yang Dapat Dijatuhkan',
            'dprd_render_bk_sanksi_meta_box',
            'page',
            'normal',
            'default'
        );
    }

    // 2. Badan Musyawarah, Badan Anggaran, Bapemperda
    if (in_array($template, ['page-badan-musyawarah.php', 'page-badan-anggaran.php', 'page-bapemperda.php'], true)) {
        add_meta_box(
            'dprd_badan_generic_details',
            'Pengaturan Dasar Pembentukan Badan',
            'dprd_render_badan_generic_details',
            'page',
            'normal',
            'high'
        );
        add_meta_box(
            'dprd_badan_generic_tugas',
            'Tugas Alat Kelengkapan',
            'dprd_render_badan_generic_tugas_meta_box',
            'page',
            'normal',
            'default'
        );
    }
});

/**
 * Renders Badan Kehormatan details metabox
 */
function dprd_render_bk_page_details($post) {
    wp_nonce_field('dprd_save_bk_page', 'dprd_bk_page_nonce');
    $dasar_pembentukan = get_post_meta($post->ID, 'dprd_bk_dasar_pembentukan', true);
    $jumlah_anggota = get_post_meta($post->ID, 'dprd_bk_jumlah_anggota', true) ?: '5 Orang';
    $jumlah_anggota_desc = get_post_meta($post->ID, 'dprd_bk_jumlah_anggota_desc', true) ?: "JUMLAH ANGGOTA\nDipilih dari dan oleh anggota DPRD";
    $masa_tugas = get_post_meta($post->ID, 'dprd_bk_masa_tugas', true) ?: '2,5 Tahun';
    $masa_tugas_desc = get_post_meta($post->ID, 'dprd_bk_masa_tugas_desc', true) ?: 'MASA TUGAS MAKSIMAL';
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_bk_dasar_pembentukan">Dasar Pembentukan</label></th>
            <td>
                <textarea id="dprd_bk_dasar_pembentukan" name="dprd_bk_dasar_pembentukan" class="large-text" rows="4" placeholder="Badan Kehormatan dibentuk oleh DPRD dan merupakan..."><?php echo esc_textarea($dasar_pembentukan); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_bk_jumlah_anggota">Jumlah Anggota (Statistik)</label></th>
            <td>
                <input type="text" id="dprd_bk_jumlah_anggota" name="dprd_bk_jumlah_anggota" class="regular-text" value="<?php echo esc_attr($jumlah_anggota); ?>">
                <p class="description">Angka/teks utama (contoh: 5 Orang).</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_bk_jumlah_anggota_desc">Keterangan Jumlah Anggota</label></th>
            <td>
                <textarea id="dprd_bk_jumlah_anggota_desc" name="dprd_bk_jumlah_anggota_desc" class="large-text" rows="2"><?php echo esc_textarea($jumlah_anggota_desc); ?></textarea>
                <p class="description">Keterangan di bawah angka statistik. Gunakan baris baru untuk memisahkan baris.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_bk_masa_tugas">Masa Tugas (Statistik)</label></th>
            <td>
                <input type="text" id="dprd_bk_masa_tugas" name="dprd_bk_masa_tugas" class="regular-text" value="<?php echo esc_attr($masa_tugas); ?>">
                <p class="description">Angka/teks utama (contoh: 2,5 Tahun).</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_bk_masa_tugas_desc">Keterangan Masa Tugas</label></th>
            <td>
                <input type="text" id="dprd_bk_masa_tugas_desc" name="dprd_bk_masa_tugas_desc" class="regular-text" value="<?php echo esc_attr($masa_tugas_desc); ?>">
                <p class="description">Label keterangan masa tugas.</p>
            </td>
        </tr>
    </table>
    <?php
}

function dprd_render_bk_sanksi_meta_box($post) {
    wp_nonce_field('dprd_save_bk_sanksi', 'dprd_bk_sanksi_nonce');
    $raw = get_post_meta($post->ID, 'dprd_bk_sanksi_json', true);
    $rows = $raw ? json_decode($raw, true) : [];
    dprd_get_bk_sanksi_repeater()->render_field_only($rows);
}

/**
 * Renders Bamus, Banggar, Bapemperda details metabox
 */
function dprd_render_badan_generic_details($post) {
    wp_nonce_field('dprd_save_badan_generic', 'dprd_badan_generic_nonce');
    $dasar_pembentukan = get_post_meta($post->ID, 'dprd_badan_dasar_pembentukan', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_badan_dasar_pembentukan">Dasar Pembentukan</label></th>
            <td>
                <textarea id="dprd_badan_dasar_pembentukan" name="dprd_badan_dasar_pembentukan" class="large-text" rows="4" placeholder="Masukkan dasar pembentukan badan ini..."><?php echo esc_textarea($dasar_pembentukan); ?></textarea>
            </td>
        </tr>
    </table>
    <?php
}

function dprd_render_badan_generic_tugas_meta_box($post) {
    wp_nonce_field('dprd_save_badan_generic_tugas', 'dprd_badan_generic_tugas_nonce');
    $raw = get_post_meta($post->ID, 'dprd_badan_tugas_json', true);
    $rows = $raw ? json_decode($raw, true) : [];
    dprd_get_badan_tugas_repeater()->render_field_only($rows);
}

/**
 * Save Handler
 */
add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save Badan Kehormatan Details
    if (isset($_POST['dprd_bk_page_nonce']) && wp_verify_nonce($_POST['dprd_bk_page_nonce'], 'dprd_save_bk_page')) {
        if (isset($_POST['dprd_bk_dasar_pembentukan'])) {
            update_post_meta($post_id, 'dprd_bk_dasar_pembentukan', sanitize_textarea_field($_POST['dprd_bk_dasar_pembentukan']));
        }
        if (isset($_POST['dprd_bk_jumlah_anggota'])) {
            update_post_meta($post_id, 'dprd_bk_jumlah_anggota', sanitize_text_field($_POST['dprd_bk_jumlah_anggota']));
        }
        if (isset($_POST['dprd_bk_jumlah_anggota_desc'])) {
            update_post_meta($post_id, 'dprd_bk_jumlah_anggota_desc', sanitize_textarea_field($_POST['dprd_bk_jumlah_anggota_desc']));
        }
        if (isset($_POST['dprd_bk_masa_tugas'])) {
            update_post_meta($post_id, 'dprd_bk_masa_tugas', sanitize_text_field($_POST['dprd_bk_masa_tugas']));
        }
        if (isset($_POST['dprd_bk_masa_tugas_desc'])) {
            update_post_meta($post_id, 'dprd_bk_masa_tugas_desc', sanitize_text_field($_POST['dprd_bk_masa_tugas_desc']));
        }
    }

    // Save BK Sanctions Repeater
    if (isset($_POST['dprd_bk_sanksi_nonce']) && wp_verify_nonce($_POST['dprd_bk_sanksi_nonce'], 'dprd_save_bk_sanksi')) {
        if (isset($_POST['dprd_bk_sanksi_json'])) {
            $repeater = dprd_get_bk_sanksi_repeater();
            $clean_json = $repeater->sanitize_from_post($_POST['dprd_bk_sanksi_json']);
            update_post_meta($post_id, 'dprd_bk_sanksi_json', $clean_json);
        }
    }

    // Save Generic Badan Details
    if (isset($_POST['dprd_badan_generic_nonce']) && wp_verify_nonce($_POST['dprd_badan_generic_nonce'], 'dprd_save_badan_generic')) {
        if (isset($_POST['dprd_badan_dasar_pembentukan'])) {
            update_post_meta($post_id, 'dprd_badan_dasar_pembentukan', sanitize_textarea_field($_POST['dprd_badan_dasar_pembentukan']));
        }
    }

    // Save Generic Badan Tasks Repeater
    if (isset($_POST['dprd_badan_generic_tugas_nonce']) && wp_verify_nonce($_POST['dprd_badan_generic_tugas_nonce'], 'dprd_save_badan_generic_tugas')) {
        if (isset($_POST['dprd_badan_tugas_json'])) {
            $repeater = dprd_get_badan_tugas_repeater();
            $clean_json = $repeater->sanitize_from_post($_POST['dprd_badan_tugas_json']);
            update_post_meta($post_id, 'dprd_badan_tugas_json', $clean_json);
        }
    }
});
