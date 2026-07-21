<?php
/**
 * Meta Box for Berita (isFeatured)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    // Meta box di sidebar untuk status featured
    add_meta_box(
        'dprd_berita_meta',
        'Pengaturan Berita',
        'dprd_render_berita_meta_box',
        'berita',
        'side',
        'default'
    );

    // Meta box di bagian utama untuk metadata tambahan
    add_meta_box(
        'dprd_berita_additional_meta',
        'Informasi Tambahan Berita',
        'dprd_render_berita_additional_meta_box',
        'berita',
        'normal',
        'default'
    );
});

function dprd_render_berita_meta_box($post) {
    wp_nonce_field('dprd_save_berita_meta', 'dprd_berita_meta_nonce');
    $is_featured = get_post_meta($post->ID, 'isFeatured', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="isFeatured" value="1" <?php checked($is_featured, '1'); ?>>
            Tampilkan di Slide Utama (Featured)
        </label>
    </p>
    <?php
}

function dprd_render_berita_additional_meta_box($post) {
    wp_nonce_field('dprd_save_berita_additional_meta', 'dprd_save_berita_additional_meta_nonce');
    $day = get_post_meta($post->ID, 'day', true);
    $time = get_post_meta($post->ID, 'time', true);
    $author = get_post_meta($post->ID, 'author', true);
    $excerpt = get_post_meta($post->ID, 'excerpt', true);
    $image_caption = get_post_meta($post->ID, 'imageCaption', true);
    
    // Fields untuk Foto Tambahan di Tengah Paragraf
    $additional_image_id = get_post_meta($post->ID, 'additional_image_id', true);
    $additional_image_caption = get_post_meta($post->ID, 'additional_image_caption', true);
    $additional_image_paragraph = get_post_meta($post->ID, 'additional_image_paragraph', true);
    
    $additional_image_url = $additional_image_id ? wp_get_attachment_image_url($additional_image_id, 'medium') : '';

    wp_enqueue_media();
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_day">Hari & Tanggal Rilis</label></th>
            <td>
                <input type="text" name="day" id="dprd_day" value="<?php echo esc_attr($day); ?>" placeholder="Contoh: Senin, 14 Okt 2024" class="regular-text">
                <p class="description">Bisa dikosongkan. Isi jika ingin menentukan tanggal rilis sendiri.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_time">Jam / Waktu Rilis</label></th>
            <td>
                <input type="text" name="time" id="dprd_time" value="<?php echo esc_attr($time); ?>" placeholder="Contoh: 18.43 WIB" class="regular-text">
                <p class="description">Bisa dikosongkan. Isi jika ingin menentukan jam rilis sendiri.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_author">Nama Penulis / Sumber</label></th>
            <td>
                <input type="text" name="author" id="dprd_author" value="<?php echo esc_attr($author); ?>" placeholder="Contoh: Sekretariat DPRD" class="regular-text">
                <p class="description">Bisa dikosongkan. Isi jika ditulis oleh pihak lain selain akun Anda.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_excerpt">Ringkasan Berita (Tampil di Halaman Depan)</label></th>
            <td>
                <textarea name="excerpt" id="dprd_excerpt" rows="3" class="large-text" placeholder="Tulis 1-2 kalimat ringkasan singkat berita untuk ditampilkan di halaman utama..."><?php echo esc_textarea($excerpt); ?></textarea>
                <p class="description">Teks ini akan muncul di bawah judul berita pada slide/daftar berita halaman utama.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_image_caption">Keterangan Foto Utama</label></th>
            <td>
                <textarea name="imageCaption" id="dprd_image_caption" rows="2" class="large-text" placeholder="Tulis keterangan foto atau sumber gambar utama di sini..."><?php echo esc_textarea($image_caption); ?></textarea>
            </td>
        </tr>

        <!-- Pembatas Sisi Foto Tambahan -->
        <tr>
            <td colspan="2"><hr style="border:0; border-top:1px solid #ccc; margin: 10px 0;"></td>
        </tr>

        <tr>
            <th><label>Foto Tambahan (Di Tengah Paragraf)</label></th>
            <td>
                <div class="dprd-meta-image-uploader">
                    <input type="hidden" name="additional_image_id" id="dprd_additional_image_id" value="<?php echo esc_attr($additional_image_id); ?>">
                    <div id="dprd_additional_image_preview" style="margin-bottom: 10px;">
                        <?php if ($additional_image_url): ?>
                            <img src="<?php echo esc_url($additional_image_url); ?>" style="max-width: 200px; max-height: 200px; display: block; border: 1px solid #ccc; padding: 4px; border-radius: 4px;">
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button button-secondary" id="dprd_additional_upload_button">Pilih / Unggah Foto Tambahan</button>
                    <button type="button" class="button-link" id="dprd_additional_remove_button" style="<?php echo $additional_image_id ? '' : 'display:none;'; ?> margin-left: 10px; color: #b32d2e; text-decoration: none;">Hapus Foto</button>
                </div>

                <script>
                jQuery(document).ready(function($){
                    var file_frame;
                    $('#dprd_additional_upload_button').on('click', function(e){
                        e.preventDefault();
                        if (file_frame) {
                            file_frame.open();
                            return;
                        }
                        file_frame = wp.media.frames.file_frame = wp.media({
                            title: 'Pilih atau Unggah Foto Tambahan',
                            button: {
                                text: 'Gunakan Foto Ini'
                            },
                            multiple: false
                        });
                        file_frame.on('select', function() {
                            var attachment = file_frame.state().get('selection').first().toJSON();
                            $('#dprd_additional_image_id').val(attachment.id);
                            $('#dprd_additional_image_preview').html('<img src="'+attachment.url+'" style="max-width: 200px; max-height: 200px; display: block; border: 1px solid #ccc; padding: 4px; border-radius: 4px;">');
                            $('#dprd_additional_remove_button').show();
                        });
                        file_frame.open();
                    });

                    $('#dprd_additional_remove_button').on('click', function(e){
                        e.preventDefault();
                        $('#dprd_additional_image_id').val('');
                        $('#dprd_additional_image_preview').html('');
                        $(this).hide();
                    });
                });
                </script>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_additional_image_caption">Keterangan Foto Tambahan</label></th>
            <td>
                <textarea name="additional_image_caption" id="dprd_additional_image_caption" rows="2" class="large-text" placeholder="Tulis keterangan foto tambahan di sini..."><?php echo esc_textarea($additional_image_caption); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_additional_image_paragraph">Disisipkan pada Paragraf Ke-</label></th>
            <td>
                <input type="number" name="additional_image_paragraph" id="dprd_additional_image_paragraph" value="<?php echo esc_attr($additional_image_paragraph); ?>" min="1" step="1" style="width: 80px;">
                <p class="description">Masukkan nomor paragraf (contoh: 2 untuk disisipkan setelah paragraf ke-2).</p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    // 1. Simpan metadata Featured
    if (isset($_POST['dprd_berita_meta_nonce']) && wp_verify_nonce($_POST['dprd_berita_meta_nonce'], 'dprd_save_berita_meta')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                $is_featured = isset($_POST['isFeatured']) ? '1' : '0';
                update_post_meta($post_id, 'isFeatured', $is_featured);
            }
        }
    }

    // 2. Simpan metadata tambahan
    if (isset($_POST['dprd_save_berita_additional_meta_nonce']) && wp_verify_nonce($_POST['dprd_save_berita_additional_meta_nonce'], 'dprd_save_berita_additional_meta')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['day'])) {
                    update_post_meta($post_id, 'day', sanitize_text_field($_POST['day']));
                }
                if (isset($_POST['time'])) {
                    update_post_meta($post_id, 'time', sanitize_text_field($_POST['time']));
                }
                if (isset($_POST['author'])) {
                    update_post_meta($post_id, 'author', sanitize_text_field($_POST['author']));
                }
                if (isset($_POST['excerpt'])) {
                    update_post_meta($post_id, 'excerpt', sanitize_textarea_field($_POST['excerpt']));
                }
                if (isset($_POST['imageCaption'])) {
                    update_post_meta($post_id, 'imageCaption', sanitize_textarea_field($_POST['imageCaption']));
                }
                if (isset($_POST['additional_image_id'])) {
                    update_post_meta($post_id, 'additional_image_id', absint($_POST['additional_image_id']));
                }
                if (isset($_POST['additional_image_caption'])) {
                    update_post_meta($post_id, 'additional_image_caption', sanitize_textarea_field($_POST['additional_image_caption']));
                }
                if (isset($_POST['additional_image_paragraph'])) {
                    update_post_meta($post_id, 'additional_image_paragraph', absint($_POST['additional_image_paragraph']));
                }
            }
        }
    }
});

// Ubah placeholder "Tambahkan judul" khusus untuk CPT Berita
add_filter('enter_title_here', function ($title, $post) {
    if (is_object($post) && isset($post->post_type) && $post->post_type === 'berita') {
        return 'Tambahkan judul berita';
    }
    return $title;
}, 10, 2);
