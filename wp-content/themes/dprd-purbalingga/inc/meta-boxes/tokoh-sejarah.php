<?php
/**
 * Meta Box for Tokoh Sejarah (periode, deskripsi)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_tokoh_sejarah_meta',
        'Informasi Tokoh Sejarah',
        'dprd_render_tokoh_sejarah_meta_box',
        'tokoh-sejarah',
        'normal',
        'default'
    );
});

function dprd_render_tokoh_sejarah_meta_box($post) {
    wp_nonce_field('dprd_save_tokoh_sejarah_meta', 'dprd_tokoh_sejarah_meta_nonce');
    $periode = get_post_meta($post->ID, 'periode', true);
    $deskripsi = get_post_meta($post->ID, 'deskripsi', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_periode">Periode Tokoh / Jabatan</label></th>
            <td>
                <input type="text" name="periode" id="dprd_periode" value="<?php echo esc_attr($periode); ?>" placeholder="Contoh: 1945 - 1950" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="dprd_deskripsi">Deskripsi Singkat / Kontribusi</label></th>
            <td>
                <textarea name="deskripsi" id="dprd_deskripsi" rows="4" class="large-text"><?php echo esc_textarea($deskripsi); ?></textarea>
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_tokoh_sejarah_meta_nonce']) || !wp_verify_nonce($_POST['dprd_tokoh_sejarah_meta_nonce'], 'dprd_save_tokoh_sejarah_meta')) {
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
    if (isset($_POST['deskripsi'])) {
        update_post_meta($post_id, 'deskripsi', sanitize_textarea_field($_POST['deskripsi']));
    }
});
