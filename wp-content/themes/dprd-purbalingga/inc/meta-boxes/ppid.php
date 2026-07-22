<?php
/**
 * Meta Box for PPID (deskripsi & dokumen_json)
 * Sesuai data ppid.data.js Vercel & Fase 2 Migrasi
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_ppid_meta',
        'Pengaturan Dokumen PPID',
        'dprd_render_ppid_meta_box',
        'ppid',
        'normal',
        'high'
    );
});

function dprd_render_ppid_meta_box($post) {
    wp_nonce_field('dprd_save_ppid_meta', 'dprd_ppid_meta_nonce');
    $description = get_post_meta($post->ID, 'description', true);
    $documents_json = get_post_meta($post->ID, 'documents_json', true);
    
    // Parse json
    $documents = json_decode($documents_json, true);
    if (!is_array($documents)) {
        $documents = [
            ['title' => '', 'url' => '']
        ];
    }
    ?>
    <style>
        .ppid-doc-row { display: flex; gap: 10px; margin-bottom: 8px; align-items: center; }
        .ppid-doc-row input[type="text"] { flex: 1; }
    </style>

    <p>
        <label for="dprd_ppid_description"><strong>Deskripsi Singkat / Subtitle:</strong></label><br>
        <input type="text" name="ppid_description" id="dprd_ppid_description" value="<?php echo esc_attr($description); ?>" class="large-text" placeholder="Contoh: SK PPID DPRD Kabupaten Purbalingga">
    </p>

    <hr style="margin: 15px 0;">

    <label><strong>Daftar Dokumen PDF / Download:</strong></label>
    <div id="ppid-documents-container" style="margin-top: 10px;">
        <?php foreach ($documents as $index => $doc) : ?>
            <div class="ppid-doc-row">
                <input type="text" name="ppid_doc_title[]" value="<?php echo esc_attr($doc['title'] ?? ''); ?>" placeholder="Judul Dokumen (Contoh: SK 170 Perubahan Fraksi)">
                <input type="text" name="ppid_doc_url[]" value="<?php echo esc_url($doc['url'] ?? '#'); ?>" placeholder="URL / Link File (Contoh: https://... atau #)">
                <button type="button" class="button remove-doc-btn">Hapus</button>
            </div>
        <?php endforeach; ?>
    </div>

    <p><button type="button" id="add-doc-btn" class="button button-secondary">+ Tambah Dokumen</button></p>

    <script>
    jQuery(document).ready(function($) {
        $('#add-doc-btn').on('click', function() {
            var row = '<div class="ppid-doc-row">' +
                '<input type="text" name="ppid_doc_title[]" value="" placeholder="Judul Dokumen">' +
                '<input type="text" name="ppid_doc_url[]" value="#" placeholder="URL / Link File">' +
                '<button type="button" class="button remove-doc-btn">Hapus</button>' +
                '</div>';
            $('#ppid-documents-container').append(row);
        });

        $(document).on('click', '.remove-doc-btn', function() {
            $(this).closest('.ppid-doc-row').remove();
        });
    });
    </script>
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

    if (isset($_POST['ppid_description'])) {
        update_post_meta($post_id, 'description', sanitize_text_field($_POST['ppid_description']));
    }

    if (isset($_POST['ppid_doc_title']) && is_array($_POST['ppid_doc_title'])) {
        $docs = [];
        $titles = $_POST['ppid_doc_title'];
        $urls   = $_POST['ppid_doc_url'] ?? [];

        foreach ($titles as $i => $title) {
            $t = sanitize_text_field($title);
            $u = isset($urls[$i]) ? esc_url_raw($urls[$i]) : '#';
            if (!empty($t)) {
                $docs[] = [
                    'title' => $t,
                    'url'   => !empty($u) ? $u : '#'
                ];
            }
        }
        update_post_meta($post_id, 'documents_json', wp_json_encode($docs));
    } else {
        update_post_meta($post_id, 'documents_json', wp_json_encode([]));
    }
});
