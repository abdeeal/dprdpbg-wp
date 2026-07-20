<?php
/**
 * Meta Box for PPID (dokumen_url, kategori)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_ppid_meta',
        'Informasi Dokumen PPID',
        'dprd_render_ppid_meta_box',
        'ppid',
        'normal',
        'default'
    );
});

function dprd_render_ppid_meta_box($post) {
    wp_nonce_field('dprd_save_ppid_meta', 'dprd_ppid_meta_nonce');
    $dokumen_url = get_post_meta($post->ID, 'dokumen_url', true);
    $kategori = get_post_meta($post->ID, 'kategori', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_dokumen_url">URL Dokumen / File</label></th>
            <td>
                <input type="text" name="dokumen_url" id="dprd_dokumen_url" value="<?php echo esc_url($dokumen_url); ?>" placeholder="https://..." class="large-text">
                <p class="description">Link tautan file PDF atau dokumen PPID.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_kategori">Kategori</label></th>
            <td>
                <input type="text" name="kategori" id="dprd_kategori" value="<?php echo esc_attr($kategori); ?>" placeholder="Contoh: Dokumen Berkala / Serta Merta" class="regular-text">
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_ppid_meta_nonce']) || !wp_verify_nonce($_POST['dprd_ppid_meta_nonce'], 'dprd_save_ppid_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['dokumen_url'])) {
        update_post_meta($post_id, 'dokumen_url', esc_url_raw($_POST['dokumen_url']));
    }
    if (isset($_POST['kategori'])) {
        update_post_meta($post_id, 'kategori', sanitize_text_field($_POST['kategori']));
    }
});
