<?php
/**
 * Meta Box for PPID (description & documents_json dengan upload PDF)
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

// Enqueue WP Media Uploader & jQuery UI Sortable hanya di halaman edit CPT ppid
add_action('admin_enqueue_scripts', function ($hook) {
    global $post;
    if (($hook === 'post.php' || $hook === 'post-new.php') && isset($post) && $post->post_type === 'ppid') {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
    }
});

function dprd_render_ppid_meta_box($post) {
    wp_nonce_field('dprd_save_ppid_meta', 'dprd_ppid_meta_nonce');
    $description   = get_post_meta($post->ID, 'description', true);
    $documents_json = get_post_meta($post->ID, 'documents_json', true);

    $documents = json_decode($documents_json, true);
    if (!is_array($documents) || empty($documents)) {
        $documents = [['title' => '', 'url' => '']];
    }
    ?>
    <style>
        .ppid-doc-row {
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
        .ppid-doc-row:hover {
            background: #f8fafc;
        }
        .ppid-drag-handle {
            cursor: grab;
            font-size: 18px;
            color: #788c9e;
            padding: 2px 6px;
            user-select: none;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        .ppid-drag-handle:hover {
            color: #2271b1;
        }
        .ppid-doc-row .ppid-doc-title {
            flex: 1;
            min-width: 0;
        }
        .ppid-doc-row .ppid-doc-file {
            flex: 1.5;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
        }
        .ppid-doc-row .ppid-file-name {
            font-size: 12px;
            color: #555;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
            display: inline-block;
        }
        .ppid-doc-row .ppid-file-name.no-file {
            color: #aaa;
            font-style: italic;
        }
        .ppid-upload-btn {
            white-space: nowrap;
            flex-shrink: 0;
        }
        .ppid-remove-file-btn {
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
        .ppid-remove-file-btn.visible {
            display: inline;
        }
        .ppid-move-btn {
            padding: 0 6px !important;
            min-height: 28px !important;
            line-height: 26px !important;
            font-size: 11px !important;
            color: #50575e !important;
        }
        .ppid-doc-placeholder {
            border: 2px dashed #2271b1;
            background: #f0f6fc;
            height: 48px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
    </style>

    <p>
        <label for="dprd_ppid_description"><strong>Deskripsi Singkat / Subtitle:</strong></label><br>
        <input type="text" name="ppid_description" id="dprd_ppid_description"
               value="<?php echo esc_attr($description); ?>"
               class="large-text"
               placeholder="Contoh: SK PPID DPRD Kabupaten Purbalingga">
    </p>

    <hr style="margin: 15px 0;">

    <label><strong>Daftar Dokumen PDF / Upload:</strong></label>
    <p style="font-size:12px; color:#646970; margin:4px 0 10px; display:flex; items-center; gap:4px;">
        <span class="dashicons dashicons-lightbulb" style="font-size:16px; width:16px; height:16px; color:#f0b849; flex-shrink:0;"></span>
        <span><strong>Tips:</strong> Tahan ikon <span style="font-size:14px; font-weight:bold;">☰</span> di sebelah kiri untuk menggeser (drag & drop) urutan dokumen, atau gunakan tombol panah <span style="font-weight:bold;">▲ / ▼</span>.</span>
    </p>

    <div id="ppid-documents-container">
        <?php foreach ($documents as $index => $doc) :
            $has_file = !empty($doc['url']) && $doc['url'] !== '#';
            $file_name = $has_file ? basename($doc['url']) : '';
        ?>
            <div class="ppid-doc-row">
                <!-- Drag Handle -->
                <span class="ppid-drag-handle" title="Tahan dan geser ke atas/bawah untuk mengurutkan">☰</span>

                <!-- Tombol Naik / Turun Cepat -->
                <button type="button" class="button ppid-move-btn ppid-move-up-btn" title="Pindah ke Atas">▲</button>
                <button type="button" class="button ppid-move-btn ppid-move-down-btn" title="Pindah ke Bawah">▼</button>

                <!-- Judul -->
                <div class="ppid-doc-title">
                    <input type="text"
                           name="ppid_doc_title[]"
                           value="<?php echo esc_attr($doc['title'] ?? ''); ?>"
                           placeholder="Judul Dokumen"
                           class="widefat">
                </div>

                <!-- Hidden URL -->
                <input type="hidden" name="ppid_doc_url[]" value="<?php echo esc_url($doc['url'] ?? '#'); ?>" class="ppid-doc-url-field">

                <!-- File info + tombol upload -->
                <div class="ppid-doc-file">
                    <span class="ppid-file-name <?php echo $has_file ? '' : 'no-file'; ?>">
                        <?php echo $has_file ? esc_html($file_name) : 'Belum ada file'; ?>
                    </span>
                    <button type="button"
                            class="button ppid-upload-btn"
                            title="Pilih atau unggah file PDF">
                        📎 Pilih PDF
                    </button>
                    <button type="button"
                            class="ppid-remove-file-btn <?php echo $has_file ? 'visible' : ''; ?>"
                            title="Hapus file">✕</button>
                </div>

                <!-- Hapus baris -->
                <button type="button" class="button remove-doc-row-btn" style="flex-shrink:0;">Hapus</button>
            </div>
        <?php endforeach; ?>
    </div>

    <p>
        <button type="button" id="add-ppid-doc-btn" class="button button-secondary">
            + Tambah Dokumen
        </button>
    </p>

    <script>
    jQuery(document).ready(function ($) {

        // ── Inisialisasi Drag & Drop (jQuery UI Sortable) ───────────────
        $('#ppid-documents-container').sortable({
            handle: '.ppid-drag-handle',
            placeholder: 'ppid-doc-placeholder',
            axis: 'y',
            opacity: 0.75,
            cursor: 'grabbing'
        });

        // ── Template baris baru ──────────────────────────────────────────
        function newDocRow() {
            return $(
                '<div class="ppid-doc-row">' +
                    '<span class="ppid-drag-handle" title="Tahan dan geser ke atas/bawah untuk mengurutkan">☰</span>' +
                    '<button type="button" class="button ppid-move-btn ppid-move-up-btn" title="Pindah ke Atas">▲</button>' +
                    '<button type="button" class="button ppid-move-btn ppid-move-down-btn" title="Pindah ke Bawah">▼</button>' +
                    '<div class="ppid-doc-title">' +
                        '<input type="text" name="ppid_doc_title[]" value="" placeholder="Judul Dokumen" class="widefat">' +
                    '</div>' +
                    '<input type="hidden" name="ppid_doc_url[]" value="#" class="ppid-doc-url-field">' +
                    '<div class="ppid-doc-file">' +
                        '<span class="ppid-file-name no-file">Belum ada file</span>' +
                        '<button type="button" class="button ppid-upload-btn">📎 Pilih PDF</button>' +
                        '<button type="button" class="ppid-remove-file-btn" title="Hapus file">✕</button>' +
                    '</div>' +
                    '<button type="button" class="button remove-doc-row-btn" style="flex-shrink:0;">Hapus</button>' +
                '</div>'
            );
        }

        // ── Tambah baris ─────────────────────────────────────────────────
        $('#add-ppid-doc-btn').on('click', function () {
            $('#ppid-documents-container').append(newDocRow());
        });

        // ── Hapus baris ──────────────────────────────────────────────────
        $(document).on('click', '.remove-doc-row-btn', function () {
            $(this).closest('.ppid-doc-row').remove();
        });

        // ── Tombol Geser Ke Atas (▲) ─────────────────────────────────────
        $(document).on('click', '.ppid-move-up-btn', function () {
            var $row = $(this).closest('.ppid-doc-row');
            var $prev = $row.prev('.ppid-doc-row');
            if ($prev.length) {
                $row.insertBefore($prev);
            }
        });

        // ── Tombol Geser Ke Bawah (▼) ────────────────────────────────────
        $(document).on('click', '.ppid-move-down-btn', function () {
            var $row = $(this).closest('.ppid-doc-row');
            var $next = $row.next('.ppid-doc-row');
            if ($next.length) {
                $row.insertAfter($next);
            }
        });

        // ── Hapus file dari baris (reset ke #) ───────────────────────────
        $(document).on('click', '.ppid-remove-file-btn', function () {
            var $row  = $(this).closest('.ppid-doc-row');
            $row.find('.ppid-doc-url-field').val('#');
            $row.find('.ppid-file-name').text('Belum ada file').addClass('no-file');
            $(this).removeClass('visible');
        });

        // ── WordPress Media Uploader (hanya PDF) ─────────────────────────
        var mediaUploader = null;
        var $activeRow    = null;

        $(document).on('click', '.ppid-upload-btn', function (e) {
            e.preventDefault();
            $activeRow = $(this).closest('.ppid-doc-row');

            // Buat uploader baru setiap klik agar callback row-nya tepat
            mediaUploader = wp.media({
                title   : 'Pilih atau Unggah File PDF',
                button  : { text: 'Gunakan File Ini' },
                library : { type: ['application/pdf'] },
                multiple: false
            });

            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var url        = attachment.url;
                var name       = attachment.filename || attachment.url.split('/').pop();

                $activeRow.find('.ppid-doc-url-field').val(url);
                $activeRow.find('.ppid-file-name').text(name).removeClass('no-file');
                $activeRow.find('.ppid-remove-file-btn').addClass('visible');
            });

            mediaUploader.open();
        });
    });
    </script>
    <?php
}

// ── Simpan Meta Box ───────────────────────────────────────────────────────────
add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_ppid_meta_nonce']) || !wp_verify_nonce($_POST['dprd_ppid_meta_nonce'], 'dprd_save_ppid_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['ppid_description'])) {
        update_post_meta($post_id, 'description', sanitize_text_field($_POST['ppid_description']));
    }

    if (isset($_POST['ppid_doc_title']) && is_array($_POST['ppid_doc_title'])) {
        $docs   = [];
        $titles = $_POST['ppid_doc_title'];
        $urls   = $_POST['ppid_doc_url'] ?? [];

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
