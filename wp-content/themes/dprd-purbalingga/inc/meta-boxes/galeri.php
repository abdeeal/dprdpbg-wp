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

    // Ambil term kategori yang saat ini terpilih
    $terms = wp_get_object_terms($post->ID, 'kategori-galeri');
    $current_term_id = !empty($terms) && !is_wp_error($terms) ? $terms[0]->term_id : 0;

    // Daftar Kategori Galeri yang ada di website live
    $categories = [
        'Rapat Paripurna',
        'Rapat Komisi',
        'Kunjungan Kerja',
        'Reses',
        'Audiensi & Kunjungan Tamu'
    ];

    // Pastikan kategori-kategori ini terdaftar di database
    $options = [];
    foreach ($categories as $cat_name) {
        $term = get_term_by('name', $cat_name, 'kategori-galeri');
        if (!$term) {
            $inserted = wp_insert_term($cat_name, 'kategori-galeri');
            if (!is_wp_error($inserted)) {
                $options[$inserted['term_id']] = $cat_name;
            }
        } else {
            $options[$term->term_id] = $cat_name;
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
        <tr>
            <th><label for="dprd_kategori">Kategori Kegiatan</label></th>
            <td>
                <select name="kategori_galeri" id="dprd_kategori" class="postform">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($options as $id => $name): ?>
                        <option value="<?php echo esc_attr($id); ?>" <?php selected($current_term_id, $id); ?>><?php echo esc_html($name); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="description">Pilih kategori untuk memfilter foto ini di halaman website Galeri.</p>
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
    
    if (isset($_POST['kategori_galeri'])) {
        $term_id = absint($_POST['kategori_galeri']);
        if ($term_id > 0) {
            wp_set_object_terms($post_id, $term_id, 'kategori-galeri');
        } else {
            wp_set_object_terms($post_id, [], 'kategori-galeri');
        }
    }
});

// Ubah placeholder "Tambahkan judul" khusus untuk CPT Galeri
add_filter('enter_title_here', function ($title, $post) {
    if (is_object($post) && isset($post->post_type) && $post->post_type === 'galeri') {
        return 'Tambahkan judul galeri';
    }
    return $title;
}, 10, 2);
