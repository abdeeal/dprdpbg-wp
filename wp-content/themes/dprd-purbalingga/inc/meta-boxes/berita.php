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
    wp_nonce_field('dprd_save_berita_additional_meta', 'dprd_berita_additional_meta_nonce');
    $day = get_post_meta($post->ID, 'day', true);
    $time = get_post_meta($post->ID, 'time', true);
    $author = get_post_meta($post->ID, 'author', true);
    $image_caption = get_post_meta($post->ID, 'imageCaption', true);
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
            <th><label for="dprd_image_caption">Keterangan Foto Utama</label></th>
            <td>
                <textarea name="imageCaption" id="dprd_image_caption" rows="2" class="large-text" placeholder="Tulis keterangan foto atau sumber gambar utama di sini..."><?php echo esc_textarea($image_caption); ?></textarea>
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
    if (isset($_POST['dprd_berita_additional_meta_nonce']) && wp_verify_nonce($_POST['dprd_berita_additional_meta_nonce'], 'dprd_save_berita_additional_meta')) {
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
                if (isset($_POST['imageCaption'])) {
                    update_post_meta($post_id, 'imageCaption', sanitize_textarea_field($_POST['imageCaption']));
                }
            }
        }
    }
});

// Ubah placeholder "Tambahkan judul" khusus untuk CPT Berita
add_filter('enter_title_here', function ($title, $post) {
    if ($post->post_type === 'berita') {
        return 'Tambahkan judul berita';
    }
    return $title;
}, 10, 2);
