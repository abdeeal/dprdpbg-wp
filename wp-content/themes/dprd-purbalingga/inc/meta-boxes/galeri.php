<?php
/**
 * Meta Box for Galeri (caption, tanggal)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_galeri_meta',
        'Informasi Galeri',
        'dprd_render_galeri_meta_box',
        'galeri',
        'normal',
        'default'
    );
});

function dprd_render_galeri_meta_box($post) {
    wp_nonce_field('dprd_save_galeri_meta', 'dprd_galeri_meta_nonce');
    $caption = get_post_meta($post->ID, 'caption', true);
    $tanggal = get_post_meta($post->ID, 'tanggal', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_caption">Caption / Deskripsi</label></th>
            <td>
                <textarea name="caption" id="dprd_caption" rows="3" class="large-text"><?php echo esc_textarea($caption); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_tanggal">Tanggal Kegiatan</label></th>
            <td>
                <input type="date" name="tanggal" id="dprd_tanggal" value="<?php echo esc_attr($tanggal); ?>" class="regular-text">
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_galeri_meta_nonce']) || !wp_verify_nonce($_POST['dprd_galeri_meta_nonce'], 'dprd_save_galeri_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['caption'])) {
        update_post_meta($post_id, 'caption', sanitize_textarea_field($_POST['caption']));
    }
    if (isset($_POST['tanggal'])) {
        update_post_meta($post_id, 'tanggal', sanitize_text_field($_POST['tanggal']));
    }
});
