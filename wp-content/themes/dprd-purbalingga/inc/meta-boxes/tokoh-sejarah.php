<?php
/**
 * Meta Box for Tokoh Sejarah (initials, deskripsi)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_tokoh_sejarah_meta',
        'Informasi Profil Tokoh',
        'dprd_render_tokoh_sejarah_meta_box',
        'tokoh-sejarah',
        'normal',
        'default'
    );
});

function dprd_render_tokoh_sejarah_meta_box($post) {
    wp_nonce_field('dprd_save_tokoh_sejarah_meta', 'dprd_tokoh_sejarah_meta_nonce');
    $initials = get_post_meta($post->ID, 'initials', true);
    $deskripsi = get_post_meta($post->ID, 'deskripsi', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_initials">Inisial Nama</label></th>
            <td>
                <input type="text" name="initials" id="dprd_initials" value="<?php echo esc_attr($initials); ?>" placeholder="Contoh: JS" class="regular-text" maxlength="3">
                <p class="description">Masukkan 2-3 huruf inisial dari nama tokoh (contoh: JS untuk Jenderal Soedirman) sebagai alternatif bila tidak ada foto.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_deskripsi">Biografi / Deskripsi Tokoh</label></th>
            <td>
                <textarea name="deskripsi" id="dprd_deskripsi" rows="6" class="large-text"><?php echo esc_textarea($deskripsi); ?></textarea>
                <p class="description">Tulis riwayat singkat atau kontribusi tokoh secara ringkas.</p>
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

    if (isset($_POST['initials'])) {
        update_post_meta($post_id, 'initials', sanitize_text_field($_POST['initials']));
    }
    if (isset($_POST['deskripsi'])) {
        update_post_meta($post_id, 'deskripsi', sanitize_textarea_field($_POST['deskripsi']));
    }
});
