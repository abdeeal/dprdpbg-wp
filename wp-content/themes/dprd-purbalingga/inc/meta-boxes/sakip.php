<?php
/**
 * Meta Box for SAKIP (single document file uploader)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_sakip_meta',
        'Unggah Berkas Laporan SAKIP (PDF)',
        'dprd_render_sakip_meta_box',
        'sakip',
        'normal',
        'default'
    );
});

function dprd_render_sakip_meta_box($post) {
    wp_nonce_field('dprd_save_sakip_meta', 'dprd_sakip_meta_nonce');
    $file_url = get_post_meta($post->ID, 'file_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_file_url">Berkas Laporan (PDF)</label></th>
            <td>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" name="file_url" id="dprd_file_url" value="<?php echo esc_url($file_url); ?>" class="large-text" placeholder="https://..." style="flex-grow: 1;">
                    <button type="button" id="dprd_select_pdf_btn" class="button button-secondary">Pilih Berkas PDF</button>
                </div>
                <p class="description">Gunakan tombol "Pilih Berkas PDF" untuk mengunggah dokumen baru atau memilih dari Media Library, atau langsung tempel URL berkas PDF di sini.</p>
            </td>
        </tr>
    </table>
    <script>
    jQuery(document).ready(function($){
        $('#dprd_select_pdf_btn').click(function(e) {
            e.preventDefault();
            var pdfFrame = wp.media({
                title: 'Pilih Berkas PDF SAKIP',
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
                $('#dprd_file_url').val(attachment.url);
            });
            pdfFrame.open();
        });
    });
    </script>
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

    if (isset($_POST['file_url'])) {
        update_post_meta($post_id, 'file_url', esc_url_raw($_POST['file_url']));
    }
});

// Enqueue WP Media scripts
add_action('admin_enqueue_scripts', function ($hook) {
    global $post;
    if (in_array($hook, ['post.php', 'post-new.php'], true) && $post && $post->post_type === 'sakip') {
        wp_enqueue_media();
    }
});
