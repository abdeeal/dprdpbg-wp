<?php
/**
 * Meta Box for Propemperda (tahun, deskripsi, propemperda_file, sk_penetapan_file)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_propemperda_meta',
        'Unggah Berkas Dokumen Propemperda',
        'dprd_render_propemperda_meta_box',
        'propemperda',
        'normal',
        'default'
    );
});

function dprd_render_propemperda_meta_box($post) {
    wp_nonce_field('dprd_save_propemperda_meta', 'dprd_propemperda_meta_nonce');
    $tahun = get_post_meta($post->ID, 'tahun', true);
    $deskripsi = get_post_meta($post->ID, 'deskripsi', true);
    $propemperda_file = get_post_meta($post->ID, 'propemperda_file', true);
    $sk_penetapan_file = get_post_meta($post->ID, 'sk_penetapan_file', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_tahun">Tahun Anggaran</label></th>
            <td>
                <input type="text" name="tahun" id="dprd_tahun" value="<?php echo esc_attr($tahun); ?>" placeholder="Contoh: 2026" class="regular-text">
                <p class="description">Tahun pengelompokan program (contoh: 2026).</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_deskripsi">Deskripsi / Penjelasan Singkat</label></th>
            <td>
                <textarea name="deskripsi" id="dprd_deskripsi" rows="3" class="large-text" placeholder="Contoh: Dokumen Program Pembentukan Peraturan Daerah Tahun 2026"><?php echo esc_textarea($deskripsi); ?></textarea>
                <p class="description">Keterangan singkat yang akan muncul di bawah judul tahun.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_propemperda_file">Berkas Propemperda Kabupaten Purbalingga (PDF)</label></th>
            <td>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <input type="text" name="propemperda_file" id="dprd_propemperda_file" value="<?php echo esc_url($propemperda_file); ?>" class="large-text" placeholder="https://..." style="flex-grow: 1;">
                    <button type="button" class="button dprd-select-pdf-btn" data-target="dprd_propemperda_file">Pilih Berkas PDF</button>
                </div>
                <p class="description">Unggah berkas PDF untuk Dokumen Propemperda Kabupaten Purbalingga.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_sk_penetapan_file">Berkas SK Penetapan Propemperda (PDF)</label></th>
            <td>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <input type="text" name="sk_penetapan_file" id="dprd_sk_penetapan_file" value="<?php echo esc_url($sk_penetapan_file); ?>" class="large-text" placeholder="https://..." style="flex-grow: 1;">
                    <button type="button" class="button dprd-select-pdf-btn" data-target="dprd_sk_penetapan_file">Pilih Berkas PDF</button>
                </div>
                <p class="description">Unggah berkas PDF untuk Dokumen SK Penetapan Propemperda.</p>
            </td>
        </tr>
    </table>
    <script>
    jQuery(document).ready(function($){
        $('.dprd-select-pdf-btn').click(function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var pdfFrame = wp.media({
                title: 'Pilih Berkas PDF',
                button: {
                    text: 'Gunakan Berkas Ini'
                },
                multiple: false,
                library: {
                    type: 'application/pdf'
                }
            });
            pdfFrame.on('select', function() {
                var attachment = pdfFrame.state().get('selection').first().toJSON();
                $('#' + targetId).val(attachment.url);
            });
            pdfFrame.open();
        });
    });
    </script>
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
    if (isset($_POST['deskripsi'])) {
        update_post_meta($post_id, 'deskripsi', sanitize_textarea_field($_POST['deskripsi']));
    }
    if (isset($_POST['propemperda_file'])) {
        update_post_meta($post_id, 'propemperda_file', esc_url_raw($_POST['propemperda_file']));
    }
    if (isset($_POST['sk_penetapan_file'])) {
        update_post_meta($post_id, 'sk_penetapan_file', esc_url_raw($_POST['sk_penetapan_file']));
    }
});

// Enqueue WP Media scripts
add_action('admin_enqueue_scripts', function ($hook) {
    global $post;
    if (in_array($hook, ['post.php', 'post-new.php'], true) && $post && $post->post_type === 'propemperda') {
        wp_enqueue_media();
    }
});
