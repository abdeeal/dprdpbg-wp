<?php
/**
 * Meta Box for SAKIP (tahun, file_url)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_sakip_meta',
        'Informasi Dokumen SAKIP',
        'dprd_render_sakip_meta_box',
        'sakip',
        'normal',
        'default'
    );
});

function dprd_render_sakip_meta_box($post) {
    wp_nonce_field('dprd_save_sakip_meta', 'dprd_sakip_meta_nonce');
    $tahun = get_post_meta($post->ID, 'tahun', true);
    $file_url = get_post_meta($post->ID, 'file_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_tahun">Tahun</label></th>
            <td>
                <input type="text" name="tahun" id="dprd_tahun" value="<?php echo esc_attr($tahun); ?>" placeholder="Contoh: 2023" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="dprd_file_url">URL File Laporan</label></th>
            <td>
                <input type="text" name="file_url" id="dprd_file_url" value="<?php echo esc_url($file_url); ?>" placeholder="https://..." class="large-text">
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_sakip_meta_nonce']) || !wp_verify_nonce($_POST['dprd_sakip_meta_nonce'], 'dprd_save_sakip_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['tahun'])) {
        update_post_meta($post_id, 'tahun', sanitize_text_field($_POST['tahun']));
    }
    if (isset($_POST['file_url'])) {
        update_post_meta($post_id, 'file_url', esc_url_raw($_POST['file_url']));
    }
});
