<?php
/**
 * Meta Box for Komisi (Mitra Kerja)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

/**
 * Singleton untuk instance repeater Mitra Kerja
 */
function dprd_get_komisi_mitra_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_komisi_mitra_kerja_json',
            'Daftar Mitra Kerja Komisi',
            null,
            [
                'mitra' => ['label' => 'Nama Mitra Kerja', 'type' => 'text'],
            ]
        );
    }
    return $instance;
}

add_action('admin_init', function() {
    dprd_get_komisi_mitra_repeater();
});

add_action('add_meta_boxes', function() {
    add_meta_box(
        'dprd_komisi_mitra_meta',
        'Mitra Kerja Komisi (Diisi Khusus Komisi)',
        'dprd_render_komisi_mitra_meta',
        'alat-kelengkapan',
        'normal',
        'default'
    );
});

function dprd_render_komisi_mitra_meta($post) {
    wp_nonce_field('dprd_save_komisi_mitra', 'dprd_komisi_mitra_nonce');
    $raw = get_post_meta($post->ID, 'dprd_komisi_mitra_kerja_json', true);
    $rows = $raw ? json_decode($raw, true) : [];
    
    echo '<p class="description" style="margin-bottom:15px;">Diisi khusus untuk postingan Komisi I - IV saja. Kolom ini akan tampil sebagai daftar mitra kerja di halaman depan.</p>';
    
    dprd_get_komisi_mitra_repeater()->render_field_only($rows);
}

add_action('save_post', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['dprd_komisi_mitra_nonce']) && wp_verify_nonce($_POST['dprd_komisi_mitra_nonce'], 'dprd_save_komisi_mitra')) {
        if (isset($_POST['dprd_komisi_mitra_kerja_json'])) {
            $repeater = dprd_get_komisi_mitra_repeater();
            $clean_json = $repeater->sanitize_from_post($_POST['dprd_komisi_mitra_kerja_json']);
            update_post_meta($post_id, 'dprd_komisi_mitra_kerja_json', $clean_json);
        }
    }
});
