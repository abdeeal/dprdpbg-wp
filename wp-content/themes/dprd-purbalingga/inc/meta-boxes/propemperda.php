<?php
/**
 * Meta Box for Propemperda (tahun, deskripsi, propemperda_file, sk_penetapan_file)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_propemperda_meta',
        'Unggah Berkas Dokumen Propemperda',
        'dprd_render_propemperda_meta_box',
        'propemperda',
        'normal',
        'default'
    );
});

function dprd_render_propemperda_meta_box($post) {
    wp_nonce_field('dprd_save_propemperda_meta', 'dprd_propemperda_meta_nonce');
    $tahun             = get_post_meta($post->ID, 'tahun', true);
    $deskripsi         = get_post_meta($post->ID, 'deskripsi', true);
    $propemperda_file  = get_post_meta($post->ID, 'propemperda_file', true);
    $sk_penetapan_file = get_post_meta($post->ID, 'sk_penetapan_file', true);

    $perda_filename = $propemperda_file ? basename(parse_url($propemperda_file, PHP_URL_PATH)) : '';
    $sk_filename    = $sk_penetapan_file ? basename(parse_url($sk_penetapan_file, PHP_URL_PATH)) : '';
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_tahun">Tahun Anggaran</label></th>
            <td>
                <input type="text" name="tahun" id="dprd_tahun" value="<?php echo esc_attr($tahun); ?>" placeholder="Contoh: 2026" class="regular-text">
                <p class="description">Tahun pengelompokan program (contoh: 2026).</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_deskripsi">Deskripsi / Penjelasan Singkat</label></th>
            <td>
                <textarea name="deskripsi" id="dprd_deskripsi" rows="3" class="large-text" placeholder="Contoh: Dokumen Program Pembentukan Peraturan Daerah Tahun 2026"><?php echo esc_textarea($deskripsi); ?></textarea>
                <p class="description">Keterangan singkat yang akan muncul di bawah judul tahun.</p>
            </td>
        </tr>

        <!-- File 1: Dokumen Propemperda -->
        <tr>
            <th><label>Dokumen Propemperda (PDF)</label></th>
            <td>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="hidden" name="propemperda_file" id="propemperda_file" value="<?php echo esc_url($propemperda_file); ?>">
                    <button type="button" class="button button-secondary dprd-upload-pdf-btn" data-target="propemperda_file" data-label="label_propemperda_file">
                        📎 Pilih PDF Propemperda
                    </button>
                    <span id="label_propemperda_file" style="font-size:13px; color:#50575e; font-weight:500;">
                        <?php echo $perda_filename ? esc_html($perda_filename) : '<em>Belum ada file dipilih</em>'; ?>
                    </span>
                    <button type="button" class="button-link dprd-remove-pdf-btn" data-target="propemperda_file" data-label="label_propemperda_file" style="color:#b32d2e; text-decoration:none; <?php echo $propemperda_file ? '' : 'display:none;'; ?>">
                        ✕ Hapus
                    </button>
                </div>
            </td>
        </tr>

        <!-- File 2: SK Penetapan -->
        <tr>
            <th><label>Dokumen SK Penetapan (PDF)</label></th>
            <td>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="hidden" name="sk_penetapan_file" id="sk_penetapan_file" value="<?php echo esc_url($sk_penetapan_file); ?>">
                    <button type="button" class="button button-secondary dprd-upload-pdf-btn" data-target="sk_penetapan_file" data-label="label_sk_penetapan_file">
                        📎 Pilih PDF SK Penetapan
                    </button>
                    <span id="label_sk_penetapan_file" style="font-size:13px; color:#50575e; font-weight:500;">
                        <?php echo $sk_filename ? esc_html($sk_filename) : '<em>Belum ada file dipilih</em>'; ?>
                    </span>
                    <button type="button" class="button-link dprd-remove-pdf-btn" data-target="sk_penetapan_file" data-label="label_sk_penetapan_file" style="color:#b32d2e; text-decoration:none; <?php echo $sk_penetapan_file ? '' : 'display:none;'; ?>">
                        ✕ Hapus
                    </button>
                </div>
            </td>
        </tr>
    </table>

    <script>
    jQuery(document).ready(function($) {
        $('.dprd-upload-pdf-btn').on('click', function(e) {
            e.preventDefault();
            var btn       = $(this);
            var targetId  = btn.data('target');
            var labelId   = btn.data('label');
            var removeBtn = btn.siblings('.dprd-remove-pdf-btn');

            var frame = wp.media({
                title: 'Pilih File PDF',
                button: { text: 'Gunakan File Ini' },
                multiple: false,
                library: { type: ['application/pdf'] }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#' + targetId).val(attachment.url);
                $('#' + labelId).html('<strong>' + attachment.filename + '</strong>');
                removeBtn.show();
            });

            frame.open();
        });

        $('.dprd-remove-pdf-btn').on('click', function(e) {
            e.preventDefault();
            var btn      = $(this);
            var targetId = btn.data('target');
            var labelId  = btn.data('label');

            $('#' + targetId).val('');
            $('#' + labelId).html('<em>Belum ada file dipilih</em>');
            btn.hide();
        });
    });
    </script>
    <?php
}

function dprd_save_propemperda_meta($post_id) {
    if (!isset($_POST['dprd_propemperda_meta_nonce']) || !wp_verify_nonce($_POST['dprd_propemperda_meta_nonce'], 'dprd_save_propemperda_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['tahun'])) {
        $raw_tahun = sanitize_text_field($_POST['tahun']);
        if (preg_match('/\d{4}/', $raw_tahun, $matches)) {
            $clean_tahun = intval($matches[0]);
        } else {
            $clean_tahun = intval(preg_replace('/[^0-9]/', '', $raw_tahun)) ?: date('Y');
        }

        // ── Strict Duplicate Year Handler (Gagal & Notifikasi saat Typo) ──
        $existing_posts = get_posts([
            'post_type'      => 'propemperda',
            'meta_key'       => 'tahun',
            'meta_value'     => $clean_tahun,
            'post__not_in'   => [$post_id],
            'posts_per_page' => 1,
            'post_status'    => 'publish'
        ]);

        if (!empty($existing_posts)) {
            // Unhook agar tidak infinite loop, lalu kembalikan status post ke draft (GAGAL PUBLISH)
            remove_action('save_post', 'dprd_save_propemperda_meta');
            wp_update_post([
                'ID'          => $post_id,
                'post_status' => 'draft'
            ]);
            add_action('save_post', 'dprd_save_propemperda_meta');

            // Set notifikasi error untuk admin
            set_transient('dprd_propemperda_duplicate_error_' . get_current_user_id(), $clean_tahun, 45);
            return;
        }

        update_post_meta($post_id, 'tahun', $clean_tahun);
    }
    if (isset($_POST['deskripsi'])) {
        update_post_meta($post_id, 'deskripsi', sanitize_textarea_field($_POST['deskripsi']));
    }
    if (isset($_POST['propemperda_file'])) {
        update_post_meta($post_id, 'propemperda_file', esc_url_raw($_POST['propemperda_file']));
    }
    if (isset($_POST['sk_penetapan_file'])) {
        update_post_meta($post_id, 'sk_penetapan_file', esc_url_raw($_POST['sk_penetapan_file']));
    }
}
add_action('save_post', 'dprd_save_propemperda_meta');

// Admin Notice jika Terjadi Duplikasi Tahun (Penolakan Publikasi)
add_action('admin_notices', function () {
    $user_id = get_current_user_id();
    $error_tahun = get_transient('dprd_propemperda_duplicate_error_' . $user_id);
    if ($error_tahun) {
        delete_transient('dprd_propemperda_duplicate_error_' . $user_id);
        ?>
        <div class="notice notice-error is-dismissible" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-left-color: #d63638;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d63638" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="shrink: 0; flex-shrink: 0;" aria-hidden="true">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <p style="margin: 0; font-size: 13px; color: #1d2327;">
                <strong>GAGAL MEMPUBLIKASIKAN DOKUMEN:</strong> Dokumen Propemperda untuk <strong>Tahun <?php echo esc_html($error_tahun); ?></strong> sudah terdaftar di database! Dokumen baru ini disimpan sebagai <em>Draft</em> agar tidak menimpa file lama. Silakan periksa kembali tahun atau edit pos tahun <?php echo esc_html($error_tahun); ?> yang sudah ada.
            </p>
        </div>
        <?php
    }
});

// Enqueue WP Media scripts
add_action('admin_enqueue_scripts', function ($hook) {
    global $post;
    if (in_array($hook, ['post.php', 'post-new.php'], true) && $post && $post->post_type === 'propemperda') {
        wp_enqueue_media();
    }
});
