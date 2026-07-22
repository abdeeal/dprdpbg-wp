<?php
/**
 * Meta Box for Berita (isFeatured)
 */

if (!defined('ABSPATH')) exit;

/**
 * Singleton untuk instance repeater Foto Tambahan Berita
 */
function dprd_get_berita_images_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_berita_images_json',
            'Foto-Foto Tambahan Berita (Multiple Images)',
            null,
            [
                'image_id'  => ['label' => 'Foto', 'type' => 'image'],
                'caption'   => ['label' => 'Keterangan Foto', 'type' => 'textarea'],
                'paragraph' => ['label' => 'Disisipkan Setelah Paragraf Ke-', 'type' => 'text'],
            ]
        );
    }
    return $instance;
}

// Inisialisasi early agar asset di-enqueue
add_action('admin_init', function() {
    dprd_get_berita_images_repeater();
});

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

    // Meta box untuk galeri foto tambahan di berita (repeater)
    add_meta_box(
        'dprd_berita_images_meta',
        'Foto-Foto Tambahan Berita',
        'dprd_render_berita_images_meta_box',
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
    $quote_text = get_post_meta($post->ID, 'dprd_quote_text', true);
    $quote_paragraph = get_post_meta($post->ID, 'dprd_quote_paragraph', true);
    
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
                <p class="description">Teks ringkasan ini akan tampil di bawah judul berita pada halaman depan website. <em>*Catatan: Huruf pertama pada isi berita Anda otomatis akan diubah menjadi besar dan tebal (Gaya Dropcap) saat dibaca pengunjung, Anda tidak perlu menambahkan format apa pun.*</em></p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_image_caption">Keterangan Foto Utama</label></th>
            <td>
                <textarea name="imageCaption" id="dprd_image_caption" rows="2" class="large-text" placeholder="Tulis keterangan foto atau sumber gambar utama di sini..."><?php echo esc_textarea($image_caption); ?></textarea>
                <p class="description">Teks keterangan/caption singkat yang akan tampil tepat di bawah foto utama berita.</p>
            </td>
        </tr>

        <!-- Pembatas Sisi Kutipan -->
        <tr>
            <td colspan="2"><hr style="border:0; border-top:1px solid #ccc; margin: 10px 0;"></td>
        </tr>

        <tr>
            <th><label for="dprd_quote_text">Kutipan / Blockquote (Di Tengah Paragraf)</label></th>
            <td>
                <textarea name="dprd_quote_text" id="dprd_quote_text" rows="3" class="large-text" placeholder="Tulis kalimat kutipan penting dari narasumber atau sidang di sini..."><?php echo esc_textarea($quote_text); ?></textarea>
                <p class="description">Teks kutipan ini akan otomatis diformat dengan garis vertikal merah di sebelah kiri dan gaya huruf miring.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_quote_paragraph">Disisipkan Setelah Paragraf Ke-</label></th>
            <td>
                <input type="number" name="dprd_quote_paragraph" id="dprd_quote_paragraph" value="<?php echo esc_attr($quote_paragraph); ?>" min="1" step="1" style="width: 80px;">
                <p class="description">Tentukan setelah paragraf ke berapa kutipan ini akan diletakkan (Contoh: tulis 2 agar kutipan muncul tepat setelah paragraf kedua).</p>
            </td>
        </tr>
    </table>
    <?php
}

function dprd_render_berita_images_meta_box($post) {
    wp_nonce_field('dprd_save_berita_images', 'dprd_berita_images_nonce');
    $raw = get_post_meta($post->ID, 'dprd_berita_images_json', true);
    $rows = $raw ? json_decode($raw, true) : [];
    dprd_get_berita_images_repeater()->render_field_only($rows);
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
                if (isset($_POST['dprd_quote_text'])) {
                    update_post_meta($post_id, 'dprd_quote_text', sanitize_textarea_field($_POST['dprd_quote_text']));
                }
                if (isset($_POST['dprd_quote_paragraph'])) {
                    update_post_meta($post_id, 'dprd_quote_paragraph', absint($_POST['dprd_quote_paragraph']));
                }
            }
        }
    }

    // 3. Simpan Repeater Foto Tambahan Berita
    if (isset($_POST['dprd_berita_images_nonce']) && wp_verify_nonce($_POST['dprd_berita_images_nonce'], 'dprd_save_berita_images')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['dprd_berita_images_json'])) {
                    $repeater = dprd_get_berita_images_repeater();
                    $clean_json = $repeater->sanitize_from_post($_POST['dprd_berita_images_json']);
                    update_post_meta($post_id, 'dprd_berita_images_json', $clean_json);
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
