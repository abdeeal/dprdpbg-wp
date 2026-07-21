<?php
/**
 * Meta Box for Pimpinan DPRD Page Template (page-pimpinan-dprd.php)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

/**
 * Singleton untuk instance repeater Tugas Pimpinan
 */
function dprd_get_pimpinan_tugas_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_pimpinan_tugas_json',
            'Tugas Pimpinan DPRD',
            null, // set null agar tidak auto-register di semua post
            [
                'kategori' => ['label' => 'Nama Kategori', 'type' => 'text'],
                'icon'     => ['label' => 'Ikon Lucide (contoh: gavel, users, file-text)', 'type' => 'text'],
                'poin'     => ['label' => 'Poin-Poin Tugas', 'type' => 'points'],
            ]
        );
    }
    return $instance;
}

// Inisialisasi awal agar aset JS/CSS repeater di-enqueue
add_action('admin_init', function() {
    dprd_get_pimpinan_tugas_repeater();
});

/**
 * Register meta box khusus untuk pos CPT alat-kelengkapan dengan slug pimpinan-dprd
 */
add_action('add_meta_boxes', function () {
    global $post;
    if (!$post || $post->post_type !== 'alat-kelengkapan') return;

    if ($post->post_name === 'pimpinan-dprd') {
        add_meta_box(
            'dprd_pimpinan_page_details',
            'Dasar Penetapan Pimpinan',
            'dprd_render_pimpinan_page_details',
            'alat-kelengkapan',
            'normal',
            'high'
        );

        add_meta_box(
            'dprd_pimpinan_tugas_meta',
            'Tugas Pimpinan DPRD',
            'dprd_render_pimpinan_tugas_meta_box',
            'alat-kelengkapan',
            'normal',
            'default'
        );
    }
});

/**
 * Render Form Dasar Penetapan & Note
 */
function dprd_render_pimpinan_page_details($post) {
    wp_nonce_field('dprd_save_pimpinan_page', 'dprd_pimpinan_page_nonce');
    $dasar_penetapan = get_post_meta($post->ID, 'dprd_pimpinan_dasar_penetapan', true);
    $note = get_post_meta($post->ID, 'dprd_pimpinan_note', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_pimpinan_dasar_penetapan">Dasar Penetapan</label></th>
            <td>
                <textarea id="dprd_pimpinan_dasar_penetapan" name="dprd_pimpinan_dasar_penetapan" class="large-text" rows="4" placeholder="Berdasarkan Undang-Undang Republik Indonesia, Pimpinan DPRD terdiri dari satu orang Ketua..."><?php echo esc_textarea($dasar_penetapan); ?></textarea>
                <p class="description">Teks dasar hukum yang menjelaskan komposisi pimpinan berdasarkan kursi partai terbanyak.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_pimpinan_note">Catatan Kaki (Note)</label></th>
            <td>
                <textarea id="dprd_pimpinan_note" name="dprd_pimpinan_note" class="large-text" rows="3" placeholder="Penetapan ini diatur dalam Keputusan Gubernur Jawa Tengah..."><?php echo esc_textarea($note); ?></textarea>
                <p class="description">Informasi regulasi tata tertib tambahan.</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Render Repeater Tugas Pimpinan
 */
function dprd_render_pimpinan_tugas_meta_box($post) {
    wp_nonce_field('dprd_save_pimpinan_tugas', 'dprd_pimpinan_tugas_nonce');
    $raw = get_post_meta($post->ID, 'dprd_pimpinan_tugas_json', true);
    $rows = $raw ? json_decode($raw, true) : [];
    dprd_get_pimpinan_tugas_repeater()->render_field_only($rows);
}

/**
 * Simpan Data Meta
 */
add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Simpan Dasar Penetapan & Note
    if (isset($_POST['dprd_pimpinan_page_nonce']) && wp_verify_nonce($_POST['dprd_pimpinan_page_nonce'], 'dprd_save_pimpinan_page')) {
        if (isset($_POST['dprd_pimpinan_dasar_penetapan'])) {
            update_post_meta($post_id, 'dprd_pimpinan_dasar_penetapan', sanitize_textarea_field($_POST['dprd_pimpinan_dasar_penetapan']));
        }
        if (isset($_POST['dprd_pimpinan_note'])) {
            update_post_meta($post_id, 'dprd_pimpinan_note', sanitize_textarea_field($_POST['dprd_pimpinan_note']));
        }
    }

    // Simpan Repeater Tugas Pimpinan
    if (isset($_POST['dprd_pimpinan_tugas_nonce']) && wp_verify_nonce($_POST['dprd_pimpinan_tugas_nonce'], 'dprd_save_pimpinan_tugas')) {
        if (isset($_POST['dprd_pimpinan_tugas_json'])) {
            $repeater = dprd_get_pimpinan_tugas_repeater();
            $clean_json = $repeater->sanitize_from_post($_POST['dprd_pimpinan_tugas_json']);
            update_post_meta($post_id, 'dprd_pimpinan_tugas_json', $clean_json);
        }
    }
});
