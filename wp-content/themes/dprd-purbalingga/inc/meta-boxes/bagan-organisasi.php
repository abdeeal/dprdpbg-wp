<?php
/**
 * Meta Box untuk Halaman Bagan Organisasi
 */

// 1. Register Meta Box
function dprd_bagan_register_meta_boxes() {
    global $post;
    
    // Pastikan kita hanya menampilkannya di halaman dengan slug 'bagan-organisasi'
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } elseif (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    } else {
        return;
    }

    $current_post = get_post($post_id);
    if ($current_post && $current_post->post_name === 'bagan-organisasi') {
        add_meta_box(
            'dprd_bagan_meta_box',
            'File Unduhan Bagan Organisasi (Fase 2)',
            'dprd_bagan_meta_box_html',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'dprd_bagan_register_meta_boxes');

// 2. Tampilan Meta Box (HTML)
function dprd_bagan_meta_box_html($post) {
    wp_nonce_field('dprd_bagan_meta_box_nonce', 'dprd_bagan_nonce');

    $unduhan_bagan = get_post_meta($post->ID, 'unduhan_bagan', true);
    
    // Enqueue script media uploader bawaan WordPress
    wp_enqueue_media();
    ?>
    <style>
        .dprd-bagan-wrapper { padding: 10px 0; }
        .dprd-bagan-wrapper p { margin-bottom: 10px; }
    </style>
    <div class="dprd-bagan-wrapper">
        <p>
            <label for="unduhan_bagan"><strong>URL File / Gambar Bagan Organisasi Asli:</strong></label><br>
            <em>File ini akan diunduh oleh pengunjung jika mereka menekan tombol "Unduh Bagan Organisasi" di bagian bawah halaman statis Bagan Organisasi. Kosongkan jika belum ada.</em>
        </p>
        <p>
            <input type="text" id="unduhan_bagan" name="unduhan_bagan" value="<?php echo esc_attr($unduhan_bagan); ?>" style="width: 70%;" />
            <button type="button" class="button button-secondary" id="dprd_bagan_upload_btn">Upload / Pilih Media</button>
        </p>
        
        <?php if ($unduhan_bagan) : ?>
            <p style="margin-top: 15px;">
                <strong>Preview File:</strong><br>
                <a href="<?php echo esc_attr($unduhan_bagan); ?>" target="_blank">Lihat File</a>
            </p>
        <?php endif; ?>
    </div>

    <script>
    jQuery(document).ready(function($){
        var mediaUploader;
        $('#dprd_bagan_upload_btn').click(function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Pilih File Bagan Organisasi',
                button: { text: 'Gunakan File Ini' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#unduhan_bagan').val(attachment.url);
            });
            mediaUploader.open();
        });
    });
    </script>
    <?php
}

// 3. Simpan Data Meta Box
function dprd_bagan_save_meta_box($post_id) {
    if (!isset($_POST['dprd_bagan_nonce']) || !wp_verify_nonce($_POST['dprd_bagan_nonce'], 'dprd_bagan_meta_box_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    if (isset($_POST['unduhan_bagan'])) {
        update_post_meta($post_id, 'unduhan_bagan', sanitize_text_field($_POST['unduhan_bagan']));
    }
}
add_action('save_post', 'dprd_bagan_save_meta_box');
