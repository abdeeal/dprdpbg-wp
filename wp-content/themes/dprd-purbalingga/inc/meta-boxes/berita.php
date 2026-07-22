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
            'Foto-Foto Tambahan Berita (Disisipkan di Tengah Artikel)',
            null,
            [
                'image_id'  => ['label' => 'Foto Tambahan', 'type' => 'image'],
                'caption'   => ['label' => 'Keterangan Foto (Caption)', 'type' => 'textarea'],
                'paragraph' => ['label' => 'Disisipkan Setelah Paragraf Ke- (Angka)', 'type' => 'text'],
            ]
        );
    }
    return $instance;
}

/**
 * Singleton untuk instance repeater Kutipan Tambahan Berita
 */
function dprd_get_berita_quotes_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_berita_quotes_json',
            'Kutipan-Kutipan Berita (Blockquote di Tengah Artikel)',
            null,
            [
                'quote_text' => ['label' => 'Isi Teks Kutipan (Blockquote)', 'type' => 'textarea'],
                'paragraph'  => ['label' => 'Disisipkan Setelah Paragraf Ke- (Angka)', 'type' => 'text'],
            ]
        );
    }
    return $instance;
}

// Inisialisasi early agar asset di-enqueue
add_action('admin_init', function() {
    dprd_get_berita_images_repeater();
    dprd_get_berita_quotes_repeater();
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
        '📸 Foto & Caption Tambahan Berita (Disisipkan di Tengah Artikel)',
        'dprd_render_berita_images_meta_box',
        'berita',
        'normal',
        'default'
    );

    // Meta box untuk kutipan / blockquote tambahan di berita (repeater)
    add_meta_box(
        'dprd_berita_quotes_meta',
        '💬 Kutipan / Blockquote Berita (Disisipkan di Tengah Artikel)',
        'dprd_render_berita_quotes_meta_box',
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
            <td colspan="2">
                <p class="description" style="padding: 8px; background: #f0f7ff; border-left: 4px solid #2271b1; margin: 0;">
                    <strong>💡 Ringkasan Berita</strong> diisi melalui kolom <strong>"Kutipan"</strong> di sidebar kanan editor (gulir ke bawah di panel Berita). Teks tersebut otomatis tampil di halaman depan website sebagai ringkasan berita.
                </p>
            </td>
        </tr>
        <tr>
            <th>
                <label for="dprd_image_caption">Keterangan Foto Utama (Caption & Sumber Foto) <span style="color: #d63638;">*</span></label>
            </th>
            <td>
                <textarea name="imageCaption" id="dprd_image_caption" rows="2" class="large-text" required placeholder="Contoh: Suasana Rapat Paripurna DPRD Purbalingga bersama Bupati (Foto: Humas DPRD)"><?php echo esc_textarea($image_caption); ?></textarea>
                <p class="description">Teks keterangan atau sumber foto (caption) yang akan tampil tepat di bawah foto utama di halaman detail berita. <strong>Wajib diisi.</strong></p>
            </td>
        </tr>
    </table>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var captionField = document.getElementById('dprd_image_caption');
        if (!captionField) return;

        // Validasi untuk Editor Gutenberg
        if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch && wp.data.select) {
            function validateCaption() {
                var val = captionField.value.trim();
                if (val === '') {
                    wp.data.dispatch('core/editor').lockPostSaving('empty_caption_lock');
                } else {
                    wp.data.dispatch('core/editor').unlockPostSaving('empty_caption_lock');
                }
            }
            
            // Cek saat pertama kali dimuat (diberi jeda agar Gutenberg siap)
            setTimeout(validateCaption, 1000);
            
            // Cek setiap kali diketik
            captionField.addEventListener('input', validateCaption);
            captionField.addEventListener('change', validateCaption);
        }

        // Validasi untuk Classic Editor / Form standar
        var form = captionField.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (captionField.value.trim() === '') {
                    alert('Keterangan Foto Utama pada Berita harus diisi!');
                    captionField.focus();
                    e.preventDefault();
                }
            });
        }
    });
    </script>
    <?php
}

function dprd_render_berita_images_meta_box($post) {
    wp_nonce_field('dprd_save_berita_images', 'dprd_berita_images_nonce');
    $raw = get_post_meta($post->ID, 'dprd_berita_images_json', true);
    $rows = $raw ? json_decode($raw, true) : [];

    // Fallback pre-fill jika repeater kosong tapi ada data dari field tunggal lama
    if (empty($rows)) {
        $old_img_id = get_post_meta($post->ID, 'additional_image_id', true);
        $old_caption = get_post_meta($post->ID, 'additional_image_caption', true);
        $old_para = get_post_meta($post->ID, 'additional_image_paragraph', true);
        if ($old_img_id && $old_para > 0) {
            $rows[] = [
                'image_id'  => $old_img_id,
                'caption'   => $old_caption,
                'paragraph' => $old_para
            ];
        }
    }

    echo '<p class="description" style="margin-bottom: 12px; font-size: 13px; line-height: 1.6;">' .
         'Tambahkan satu atau lebih <strong>Foto Tambahan</strong> beserta <strong>Keterangan Foto (Caption)</strong> untuk disisipkan di tengah-tengah artikel berita.<br>' .
         '💡 Pada kolom <strong>"Disisipkan Setelah Paragraf Ke- (Angka)"</strong>, ketik angka urutan paragraf tempat foto akan muncul (Contoh: ketik <code>2</code> agar foto tampil tepat di bawah paragraf ke-2).</p>';
    dprd_get_berita_images_repeater()->render_field_only($rows);
}

function dprd_render_berita_quotes_meta_box($post) {
    wp_nonce_field('dprd_save_berita_quotes', 'dprd_berita_quotes_nonce');
    $raw = get_post_meta($post->ID, 'dprd_berita_quotes_json', true);
    $rows = $raw ? json_decode($raw, true) : [];

    // Fallback pre-fill jika repeater kosong tapi ada data kutipan tunggal lama
    if (empty($rows)) {
        $old_quote = get_post_meta($post->ID, 'dprd_quote_text', true);
        $old_para = get_post_meta($post->ID, 'dprd_quote_paragraph', true);
        if (!empty($old_quote) && $old_para > 0) {
            $rows[] = [
                'quote_text' => $old_quote,
                'paragraph'  => $old_para
            ];
        }
    }

    echo '<p class="description" style="margin-bottom: 12px; font-size: 13px; line-height: 1.6;">' .
         'Tambahkan satu atau lebih <strong>Teks Kutipan (Blockquote)</strong> dari narasumber atau hasil persidangan untuk disisipkan di tengah-tengah artikel.<br>' .
         '💡 Pada kolom <strong>"Disisipkan Setelah Paragraf Ke- (Angka)"</strong>, ketik angka urutan paragraf tempat kutipan akan muncul (Contoh: ketik <code>3</code> agar kutipan tampil tepat di bawah paragraf ke-3).</p>';
    dprd_get_berita_quotes_repeater()->render_field_only($rows);
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
                if (isset($_POST['imageCaption'])) {
                    update_post_meta($post_id, 'imageCaption', sanitize_textarea_field($_POST['imageCaption']));
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

    // 4. Simpan Repeater Kutipan Tambahan Berita
    if (isset($_POST['dprd_berita_quotes_nonce']) && wp_verify_nonce($_POST['dprd_berita_quotes_nonce'], 'dprd_save_berita_quotes')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['dprd_berita_quotes_json'])) {
                    $repeater = dprd_get_berita_quotes_repeater();
                    $clean_json = $repeater->sanitize_from_post($_POST['dprd_berita_quotes_json']);
                    update_post_meta($post_id, 'dprd_berita_quotes_json', $clean_json);
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
