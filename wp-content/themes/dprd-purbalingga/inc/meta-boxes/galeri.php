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
    $image_id = get_post_meta($post->ID, 'image_id', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';

    // Daftar Kategori Galeri yang ada di website live (pastikan selalu terdaftar di database)
    $categories = [
        'Rapat Paripurna',
        'Rapat Komisi',
        'Kunjungan Kerja',
        'Reses',
        'Audiensi & Kunjungan Tamu'
    ];

    foreach ($categories as $cat_name) {
        $term = get_term_by('name', $cat_name, 'kategori-galeri');
        if (!$term) {
            wp_insert_term($cat_name, 'kategori-galeri');
        }
    }

    // Enqueue media uploader bawaan WordPress
    wp_enqueue_media();
    ?>
    <table class="form-table">
        <tr>
            <th><label>Foto Kegiatan</label></th>
            <td>
                <div class="dprd-meta-image-uploader">
                    <input type="hidden" name="image_id" id="dprd_image_id" value="<?php echo esc_attr($image_id); ?>">
                    <div id="dprd_image_preview" style="margin-bottom: 10px;">
                        <?php if ($image_url): ?>
                            <img src="<?php echo esc_url($image_url); ?>" style="max-width: 250px; max-height: 250px; display: block; border: 1px solid #ccc; padding: 4px; border-radius: 4px;">
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button button-secondary" id="dprd_upload_button">Pilih / Unggah Foto</button>
                    <button type="button" class="button-link" id="dprd_remove_button" style="<?php echo $image_id ? '' : 'display:none;'; ?> margin-left: 10px; color: #b32d2e; text-decoration: none;">Hapus Foto</button>
                </div>

                <script>
                jQuery(document).ready(function($){
                    var file_frame;
                    $('#dprd_upload_button').on('click', function(e){
                        e.preventDefault();
                        if (file_frame) {
                            file_frame.open();
                            return;
                        }
                        file_frame = wp.media.frames.file_frame = wp.media({
                            title: 'Pilih atau Unggah Foto Kegiatan',
                            button: {
                                text: 'Gunakan Foto Ini'
                            },
                            multiple: false
                        });
                        file_frame.on('select', function() {
                            var attachment = file_frame.state().get('selection').first().toJSON();
                            $('#dprd_image_id').val(attachment.id);
                            $('#dprd_image_preview').html('<img src="'+attachment.url+'" style="max-width: 250px; max-height: 250px; display: block; border: 1px solid #ccc; padding: 4px; border-radius: 4px;">');
                            $('#dprd_remove_button').show();
                        });
                        file_frame.open();
                    });

                    $('#dprd_remove_button').on('click', function(e){
                        e.preventDefault();
                        $('#dprd_image_id').val('');
                        $('#dprd_image_preview').html('');
                        $(this).hide();
                    });
                });
                </script>
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

    if (isset($_POST['image_id'])) {
        update_post_meta($post_id, 'image_id', absint($_POST['image_id']));
    }
});

// Ubah placeholder "Tambahkan judul" khusus untuk CPT Galeri
add_filter('enter_title_here', function ($title, $post) {
    if (is_object($post) && isset($post->post_type) && $post->post_type === 'galeri') {
        return 'Tambahkan judul galeri';
    }
    return $title;
}, 10, 2);
