<?php
/**
 * Meta Box for Propemperda (status, tahun, file_url)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_propemperda_meta',
        'Informasi Propemperda',
        'dprd_render_propemperda_meta_box',
        'propemperda',
        'normal',
        'default'
    );
});

function dprd_render_propemperda_meta_box($post) {
    wp_nonce_field('dprd_save_propemperda_meta', 'dprd_propemperda_meta_nonce');
    $status = get_post_meta($post->ID, 'status', true);
    $tahun = get_post_meta($post->ID, 'tahun', true);
    $file_url = get_post_meta($post->ID, 'file_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_tahun">Tahun</label></th>
            <td>
                <input type="text" name="tahun" id="dprd_tahun" value="<?php echo esc_attr($tahun); ?>" placeholder="Contoh: 2024" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="dprd_status">Status</label></th>
            <td>
                <input type="text" name="status" id="dprd_status" value="<?php echo esc_attr($status); ?>" placeholder="Contoh: Pembahasan / Disahkan" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="dprd_file_url">URL File / PDF</label></th>
            <td>
                <input type="text" name="file_url" id="dprd_file_url" value="<?php echo esc_url($file_url); ?>" placeholder="https://..." class="large-text">
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_propemperda_meta_nonce']) || !wp_verify_nonce($_POST['dprd_propemperda_meta_nonce'], 'dprd_save_propemperda_meta')) {
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
    if (isset($_POST['status'])) {
        update_post_meta($post_id, 'status', sanitize_text_field($_POST['status']));
    }
    if (isset($_POST['file_url'])) {
        update_post_meta($post_id, 'file_url', esc_url_raw($_POST['file_url']));
    }
});
