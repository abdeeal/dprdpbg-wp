<?php
/**
 * Meta Box for Berita (isFeatured)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_berita_meta',
        'Pengaturan Berita',
        'dprd_render_berita_meta_box',
        'berita',
        'side',
        'default'
    );
});

function dprd_render_berita_meta_box($post) {
    wp_nonce_field('dprd_save_berita_meta', 'dprd_berita_meta_nonce');
    $is_featured = get_post_meta($post->ID, 'isFeatured', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="isFeatured" value="1" <?php checked($is_featured, '1'); ?>>
            Jadikan Berita Utama (Featured)
        </label>
    </p>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_berita_meta_nonce']) || !wp_verify_nonce($_POST['dprd_berita_meta_nonce'], 'dprd_save_berita_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $is_featured = isset($_POST['isFeatured']) ? '1' : '0';
    update_post_meta($post_id, 'isFeatured', $is_featured);
});
