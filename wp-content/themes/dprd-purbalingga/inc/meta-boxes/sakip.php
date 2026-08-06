<?php
/**
 * Meta Box for SAKIP (description & documents_json dengan upload PDF & Drag and Drop Reorder)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_sakip_meta',
        'Pengaturan Dokumen SAKIP',
        'dprd_render_sakip_meta_box',
        'sakip',
        'normal',
        'high'
    );
});

// Enqueue WP Media Uploader & jQuery UI Sortable hanya di halaman edit CPT sakip
add_action('admin_enqueue_scripts', function ($hook) {
    global $post;
    if (($hook === 'post.php' || $hook === 'post-new.php') && isset($post) && $post->post_type === 'sakip') {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
    }
});

function dprd_render_sakip_meta_box($post) {
    wp_nonce_field('dprd_save_sakip_meta', 'dprd_sakip_meta_nonce');
    $description    = get_post_meta($post->ID, 'description', true);
    $documents_json = get_post_meta($post->ID, 'documents_json', true);
    $old_file_url   = get_post_meta($post->ID, 'file_url', true);

    $documents = json_decode($documents_json, true);
    if (!is_array($documents) || empty($documents)) {
        if (!empty($old_file_url)) {
            $documents = [['title' => $post->post_title, 'url' => $old_file_url]];
        } else {
            $documents = [['title' => '', 'url' => '']];
        }
    }
    ?>
    <style>
        .sakip-doc-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            background: #ffffff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 8px 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            transition: background 0.15s ease;
        }
        .sakip-doc-row:hover {
            background: #f8fafc;
        }
        .sakip-drag-handle {
            cursor: grab;
            font-size: 18px;
            color: #788c9e;
            padding: 2px 6px;
            user-select: none;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        .sakip-drag-handle:hover {
            color: #2271b1;
        }
        .sakip-doc-row .sakip-doc-title {
            flex: 1;
            min-width: 0;
        }
        .sakip-doc-row .sakip-doc-file {
            flex: 1.5;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
        }
        .sakip-doc-row .sakip-file-name {
            font-size: 12px;
            color: #555;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
            display: inline-block;
        }
        .sakip-doc-row .sakip-file-name.no-file {
            color: #aaa;
            font-style: italic;
        }
        .sakip-upload-btn {
            white-space: nowrap;
            flex-shrink: 0;
        }
        .sakip-remove-file-btn {
            flex-shrink: 0;
            color: #d63638;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            background: none;
            border: none;
            padding: 0 2px;
            display: none;
        }
        .sakip-remove-file-btn.visible {
            display: inline;
        }
        .sakip-move-btn {
            padding: 0 6px !important;
            min-height: 28px !important;
            line-height: 26px !important;
            font-size: 11px !important;
            color: #50575e !important;
        }
        .sakip-doc-placeholder {
            border: 2px dashed #2271b1;
            background: #f0f6fc;
            height: 48px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
    </style>

    <p>
        <label for="dprd_sakip_description"><strong>Deskripsi Singkat / Subtitle:</strong></label><br>
        <input type="text" name="sakip_description" id="dprd_sakip_description"
               value="<?php echo esc_attr($description); ?>"
               class="large-text"
               placeholder="Contoh: Rencana Strategis Sekretariat DPRD Kabupaten Purbalingga">
    </p>

    <hr style="margin: 15px 0;">

    <label><strong>Daftar Dokumen PDF SAKIP / Upload:</strong></label>
    <p style="font-size:12px; color:#646970; margin:4px 0 10px; display:flex; align-items:center; gap:4px;">
        <span class="dashicons dashicons-lightbulb" style="font-size:16px; width:16px; height:16px; color:#f0b849; flex-shrink:0;"></span>
        <span><strong>Tips:</strong> Tahan ikon <span style="font-size:14px; font-weight:bold;">☰</span> di sebelah kiri untuk menggeser (drag & drop) urutan dokumen, atau gunakan tombol panah <span style="font-weight:bold;">▲ / ▼</span>.</span>
    </p>

    <div id="sakip-documents-container">
        <?php foreach ($documents as $index => $doc) :
            $has_file = !empty($doc['url']) && $doc['url'] !== '#';
            $file_name = $has_file ? basename($doc['url']) : '';
        ?>
            <div class="sakip-doc-row">
                <!-- Drag Handle -->
                <span class="sakip-drag-handle" title="Tahan dan geser ke atas/bawah untuk mengurutkan">☰</span>

                <!-- Tombol Naik / Turun Cepat -->
                <button type="button" class="button sakip-move-btn sakip-move-up-btn" title="Pindah ke Atas">▲</button>
                <button type="button" class="button sakip-move-btn sakip-move-down-btn" title="Pindah ke Bawah">▼</button>

                <!-- Judul Dokumen -->
                <div class="sakip-doc-title">
                    <input type="text"
                           name="sakip_doc_title[]"
                           value="<?php echo esc_attr($doc['title'] ?? ''); ?>"
                           placeholder="Judul Dokumen (misal: Renstra 2025 - 2029)"
                           class="widefat">
                </div>

                <!-- Hidden URL -->
                <input type="hidden" name="sakip_doc_url[]" value="<?php echo esc_url($doc['url'] ?? '#'); ?>" class="sakip-doc-url-field">

                <!-- File info + tombol upload -->
                <div class="sakip-doc-file">
                    <span class="sakip-file-name <?php echo $has_file ? '' : 'no-file'; ?>">
                        <?php echo $has_file ? esc_html($file_name) : 'Belum ada file'; ?>
                    </span>
                    <button type="button"
                            class="button sakip-upload-btn"
                            title="Pilih atau unggah file PDF">
                        📎 Pilih PDF
                    </button>
                    <button type="button"
                            class="sakip-remove-file-btn <?php echo $has_file ? 'visible' : ''; ?>"
                            title="Hapus file">✕</button>
                </div>

                <!-- Hapus baris -->
                <button type="button" class="button remove-doc-row-btn" style="flex-shrink:0;">Hapus</button>
            </div>
        <?php endforeach; ?>
    </div>

    <p>
        <button type="button" id="add-sakip-doc-btn" class="button button-secondary">
            + Tambah Dokumen SAKIP
        </button>
    </p>

    <script>
    jQuery(document).ready(function ($) {

        // ── Inisialisasi Drag & Drop (jQuery UI Sortable) ───────────────
        $('#sakip-documents-container').sortable({
            handle: '.sakip-drag-handle',
            placeholder: 'sakip-doc-placeholder',
            axis: 'y',
            opacity: 0.75,
            cursor: 'grabbing'
        });

        // ── Template baris baru ──────────────────────────────────────────
        function newDocRow() {
            return $(
                '<div class="sakip-doc-row">' +
                    '<span class="sakip-drag-handle" title="Tahan dan geser ke atas/bawah untuk mengurutkan">☰</span>' +
                    '<button type="button" class="button sakip-move-btn sakip-move-up-btn" title="Pindah ke Atas">▲</button>' +
                    '<button type="button" class="button sakip-move-btn sakip-move-down-btn" title="Pindah ke Bawah">▼</button>' +
                    '<div class="sakip-doc-title">' +
                        '<input type="text" name="sakip_doc_title[]" value="" placeholder="Judul Dokumen" class="widefat">' +
                    '</div>' +
                    '<input type="hidden" name="sakip_doc_url[]" value="#" class="sakip-doc-url-field">' +
                    '<div class="sakip-doc-file">' +
                        '<span class="sakip-file-name no-file">Belum ada file</span>' +
                        '<button type="button" class="button sakip-upload-btn">📎 Pilih PDF</button>' +
                        '<button type="button" class="sakip-remove-file-btn" title="Hapus file">✕</button>' +
                    '</div>' +
                    '<button type="button" class="button remove-doc-row-btn" style="flex-shrink:0;">Hapus</button>' +
                '</div>'
            );
        }

        // ── Tambah baris ─────────────────────────────────────────────────
        $('#add-sakip-doc-btn').on('click', function () {
            $('#sakip-documents-container').append(newDocRow());
        });

        // ── Hapus baris ──────────────────────────────────────────────────
        $(document).on('click', '.remove-doc-row-btn', function () {
            $(this).closest('.sakip-doc-row').remove();
        });

        // ── Tombol Geser Ke Atas (▲) ─────────────────────────────────────
        $(document).on('click', '.sakip-move-up-btn', function () {
            var $row = $(this).closest('.sakip-doc-row');
            var $prev = $row.prev('.sakip-doc-row');
            if ($prev.length) {
                $row.insertBefore($prev);
            }
        });

        // ── Tombol Geser Ke Bawah (▼) ────────────────────────────────────
        $(document).on('click', '.sakip-move-down-btn', function () {
            var $row = $(this).closest('.sakip-doc-row');
            var $next = $row.next('.sakip-doc-row');
            if ($next.length) {
                $row.insertAfter($next);
            }
        });

        // ── Hapus file dari baris (reset ke #) ───────────────────────────
        $(document).on('click', '.sakip-remove-file-btn', function () {
            var $row  = $(this).closest('.sakip-doc-row');
            $row.find('.sakip-doc-url-field').val('#');
            $row.find('.sakip-file-name').text('Belum ada file').addClass('no-file');
            $(this).removeClass('visible');
        });

        // ── WordPress Media Uploader (hanya PDF) ─────────────────────────
        var mediaUploader = null;
        var $activeRow    = null;

        $(document).on('click', '.sakip-upload-btn', function (e) {
            e.preventDefault();
            $activeRow = $(this).closest('.sakip-doc-row');

            mediaUploader = wp.media({
                title   : 'Pilih atau Unggah File PDF SAKIP',
                button  : { text: 'Gunakan File Ini' },
                library : { type: ['application/pdf'] },
                multiple: false
            });

            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var url        = attachment.url;
                var name       = attachment.filename || attachment.url.split('/').pop();

                $activeRow.find('.sakip-doc-url-field').val(url);
                $activeRow.find('.sakip-file-name').text(name).removeClass('no-file');
                $activeRow.find('.sakip-remove-file-btn').addClass('visible');
            });

            mediaUploader.open();
        });
    });
    </script>
    <?php
}

// ── Simpan Meta Box SAKIP ─────────────────────────────────────────────────────
add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_sakip_meta_nonce']) || !wp_verify_nonce($_POST['dprd_sakip_meta_nonce'], 'dprd_save_sakip_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['sakip_description'])) {
        update_post_meta($post_id, 'description', sanitize_text_field($_POST['sakip_description']));
    }

    if (isset($_POST['sakip_doc_title']) && is_array($_POST['sakip_doc_title'])) {
        $docs   = [];
        $titles = $_POST['sakip_doc_title'];
        $urls   = $_POST['sakip_doc_url'] ?? [];

        foreach ($titles as $i => $title) {
            $t = sanitize_text_field($title);
            $u = isset($urls[$i]) ? esc_url_raw($urls[$i]) : '#';
            if (!empty($t)) {
                $docs[] = [
                    'title' => $t,
                    'url'   => !empty($u) ? $u : '#',
                ];
            }
        }
        update_post_meta($post_id, 'documents_json', wp_json_encode($docs));
    } else {
        update_post_meta($post_id, 'documents_json', wp_json_encode([]));
    }
});
