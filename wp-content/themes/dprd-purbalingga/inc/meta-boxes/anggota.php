<?php
/**
 * Meta Box for Anggota (periode)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_anggota_meta',
        'Informasi Anggota',
        'dprd_render_anggota_meta_box',
        'anggota',
        'normal',
        'default'
    );
});

function dprd_render_anggota_meta_box($post) {
    wp_nonce_field('dprd_save_anggota_meta', 'dprd_anggota_meta_nonce');
    $periode = get_post_meta($post->ID, 'periode', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_periode">Periode Jabatan</label></th>
            <td>
                <input type="text" name="periode" id="dprd_periode" value="<?php echo esc_attr($periode); ?>" placeholder="Contoh: 2024 - 2029" class="regular-text">
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_anggota_meta_nonce']) || !wp_verify_nonce($_POST['dprd_anggota_meta_nonce'], 'dprd_save_anggota_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['periode'])) {
        update_post_meta($post_id, 'periode', sanitize_text_field($_POST['periode']));
    }
});
