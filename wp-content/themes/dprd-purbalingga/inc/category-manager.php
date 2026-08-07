<?php
/**
 * Unified Category Manager & Deleter for DPRD Purbalingga Theme
 * HANYA aktif pada tipe konten yang membutuhkan kategori (Galeri, Berita, Alat Kelengkapan).
 * Tipe konten SAKIP, PPID, dan Propemperda tidak menggunakan Meta Box Kategori.
 */

if (!defined('ABSPATH')) exit;

/**
 * Peta Tipe Konten ke Taksonomi terkait yang aktif
 */
function dprd_get_cpt_taxonomy_map() {
    return [
        'galeri'           => 'kategori-galeri',
        'berita'           => 'category',
        'alat-kelengkapan' => 'jenis',
    ];
}

/**
 * Hapus Meta Box Bawaan WP (termasuk SAKIP, PPID, Propemperda) dan Daftarkan 1 Meta Box Tunggal untuk CPT Aktif
 */
add_action('add_meta_boxes', function () {
    // 1. Selalu hapus meta box kategori bawaan untuk SAKIP, PPID, dan Propemperda
    remove_meta_box('kategori-sakipdiv', 'sakip', 'side');
    remove_meta_box('tagsdiv-kategori-sakip', 'sakip', 'side');
    remove_meta_box('kategori-ppiddiv', 'ppid', 'side');
    remove_meta_box('tagsdiv-kategori-ppid', 'ppid', 'side');
    remove_meta_box('kategori-propemperdadiv', 'propemperda', 'side');
    remove_meta_box('tagsdiv-kategori-propemperda', 'propemperda', 'side');

    $map = dprd_get_cpt_taxonomy_map();

    foreach ($map as $post_type => $taxonomy) {
        $tax_obj = get_taxonomy($taxonomy);
        if (!$tax_obj) continue;

        // 2. Hapus Meta Box Bawaan WP untuk CPT Aktif agar tidak duplikat
        remove_meta_box($taxonomy . 'div', $post_type, 'side');
        remove_meta_box('tagsdiv-' . $taxonomy, $post_type, 'side');

        // 3. Daftarkan 1 Meta Box Tunggal "Kategori [Nama Tipe Konten]"
        $box_title = $tax_obj->labels->name;
        add_meta_box(
            'dprd_unified_category_box',
            $box_title,
            'dprd_render_unified_category_box',
            $post_type,
            'side',
            'high'
        );
    }
}, 99);

/**
 * Render Tampilan Meta Box Kategori Tunggal (Pilih, Tambah, Hapus)
 */
function dprd_render_unified_category_box($post) {
    $map = dprd_get_cpt_taxonomy_map();
    $taxonomy = $map[$post->post_type] ?? '';
    if (!$taxonomy) return;

    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
    ]);

    // Ambil kategori yang saat ini terpilih pada post
    $assigned_terms = wp_get_object_terms($post->ID, $taxonomy, ['fields' => 'ids']);
    if (is_wp_error($assigned_terms)) {
        $assigned_terms = [];
    }

    wp_nonce_field('dprd_cat_mgr_nonce', 'dprd_cat_mgr_nonce_field');
    ?>
    <div id="dprd-unified-cat-box" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
        <p style="margin:0 0 8px; font-size:12px; color:#646970;">
            Centang untuk memilih kategori. Klik <strong>Hapus</strong> untuk menghapus kategori dari database.
        </p>

        <!-- Daftar Kategori dengan Checkbox + Tombol Hapus -->
        <div id="dprd-cat-list-container" style="max-height: 220px; overflow-y: auto; border: 1px solid #ccd0d4; border-radius: 4px; background: #fff; margin-bottom: 10px;">
            <ul id="dprd-cat-list" style="margin: 0; padding: 0; list-style: none;">
                <?php if (empty($terms) || is_wp_error($terms)) : ?>
                    <li style="padding: 8px 10px; font-style: italic; color: #888; font-size: 12px;" class="no-cat-item">Belum ada kategori.</li>
                <?php else : ?>
                    <?php foreach ($terms as $t) : 
                        $checked = in_array($t->term_id, $assigned_terms) ? 'checked' : '';
                    ?>
                        <li style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-bottom: 1px solid #f0f0f1; font-size: 13px;" data-term-id="<?php echo esc_attr($t->term_id); ?>">
                            <label style="display: flex; align-items: center; gap: 8px; flex: 1; min-width: 0; margin: 0; cursor: pointer; user-select: none;">
                                <input type="checkbox" name="dprd_post_categories[]" value="<?php echo esc_attr($t->term_id); ?>" <?php echo $checked; ?> style="margin: 0; shrink: 0;">
                                <span style="font-weight: 500; color: #1d2327; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo esc_html($t->name); ?></span>
                            </label>
                            <button type="button" class="dprd-del-cat-btn" data-id="<?php echo esc_attr($t->term_id); ?>" data-name="<?php echo esc_attr($t->name); ?>" title="Hapus kategori dari database" style="background: none; border: none; color: #b32d2e; cursor: pointer; font-size: 11px; padding: 2px 6px; border-radius: 3px; font-weight: 600; flex-shrink: 0; margin-left: 6px;">
                                Hapus
                            </button>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Form Tambah Kategori Baru -->
        <div id="dprd-add-cat-toggle-wrapper">
            <button type="button" class="button-link" id="dprd-toggle-add-cat" style="font-size: 12px; text-decoration: none; color: #2271b1;">
                + Tambah Kategori Baru
            </button>

            <div id="dprd-add-cat-form" style="display: none; margin-top: 8px; border-top: 1px solid #f0f0f1; padding-top: 8px;">
                <input type="text" id="dprd-new-cat-input" placeholder="Nama kategori baru..." class="widefat" style="margin-bottom: 6px; font-size: 12px; height: 30px;">
                <button type="button" id="dprd-add-cat-btn" class="button button-secondary button-small" style="width: 100%; height: 30px;">
                    Tambah Kategori
                </button>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var wrapper = $('#dprd-unified-cat-box');
        var taxonomy = wrapper.data('taxonomy');
        var nonce = $('#dprd_cat_mgr_nonce_field').val();

        // Toggle Form Tambah Kategori
        $('#dprd-toggle-add-cat').on('click', function(e) {
            e.preventDefault();
            $('#dprd-add-cat-form').slideToggle(200);
            $('#dprd-new-cat-input').focus();
        });

        // Handler Hapus Kategori
        $(document).on('click', '.dprd-del-cat-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var termId = btn.data('id');
            var termName = btn.data('name');

            if (!confirm('Apakah Anda yakin ingin menghapus kategori "' + termName + '"? Kategori ini akan dihapus permanen dari database.')) {
                return;
            }

            btn.prop('disabled', true).text('...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'dprd_delete_category',
                    term_id: termId,
                    taxonomy: taxonomy,
                    nonce: nonce
                },
                success: function(res) {
                    if (res.success) {
                        btn.closest('li').fadeOut(300, function() {
                            $(this).remove();
                            if ($('#dprd-cat-list li').length === 0) {
                                $('#dprd-cat-list').html('<li style="padding: 8px 10px; font-style: italic; color: #888; font-size: 12px;" class="no-cat-item">Belum ada kategori.</li>');
                            }
                        });
                    } else {
                        alert('Gagal menghapus kategori: ' + (res.data || 'Terjadi kesalahan.'));
                        btn.prop('disabled', false).text('Hapus');
                    }
                },
                error: function() {
                    alert('Gagal menghapus kategori. Silakan coba lagi.');
                    btn.prop('disabled', false).text('Hapus');
                }
            });
        });

        // Handler Tambah Kategori Baru
        $('#dprd-add-cat-btn').on('click', function(e) {
            e.preventDefault();
            var input = $('#dprd-new-cat-input');
            var catName = $.trim(input.val());

            if (!catName) {
                alert('Silakan masukkan nama kategori terlebih dahulu.');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).text('Memproses...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'dprd_add_category',
                    cat_name: catName,
                    taxonomy: taxonomy,
                    nonce: nonce
                },
                success: function(res) {
                    btn.prop('disabled', false).text('Tambah Kategori');
                    if (res.success) {
                        input.val('');
                        var t = res.data;
                        var newLi = '<li style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-bottom: 1px solid #f0f0f1; font-size: 13px;" data-term-id="' + t.term_id + '">' +
                            '<label style="display: flex; align-items: center; gap: 8px; flex: 1; min-width: 0; margin: 0; cursor: pointer; user-select: none;">' +
                            '<input type="checkbox" name="dprd_post_categories[]" value="' + t.term_id + '" checked style="margin: 0; shrink: 0;">' +
                            '<span style="font-weight: 500; color: #1d2327; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + t.name + '</span>' +
                            '</label>' +
                            '<button type="button" class="dprd-del-cat-btn" data-id="' + t.term_id + '" data-name="' + t.name + '" title="Hapus kategori dari database" style="background: none; border: none; color: #b32d2e; cursor: pointer; font-size: 11px; padding: 2px 6px; border-radius: 3px; font-weight: 600; flex-shrink: 0; margin-left: 6px;">Hapus</button>' +
                            '</li>';
                        
                        if ($('#dprd-cat-list .no-cat-item').length > 0) {
                            $('#dprd-cat-list').empty();
                        }
                        $('#dprd-cat-list').append(newLi);
                    } else {
                        alert('Gagal menambah kategori: ' + (res.data || 'Terjadi kesalahan.'));
                    }
                },
                error: function() {
                    btn.prop('disabled', false).text('Tambah Kategori');
                    alert('Gagal menambah kategori.');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Simpan Pilihan Kategori pada Post saat Disimpan
 */
add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_cat_mgr_nonce_field']) || !wp_verify_nonce($_POST['dprd_cat_mgr_nonce_field'], 'dprd_cat_mgr_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $map = dprd_get_cpt_taxonomy_map();
    $post_type = get_post_type($post_id);
    $taxonomy = $map[$post_type] ?? '';

    if ($taxonomy) {
        $selected_cat_ids = isset($_POST['dprd_post_categories']) ? array_map('intval', (array)$_POST['dprd_post_categories']) : [];
        wp_set_object_terms($post_id, $selected_cat_ids, $taxonomy);
    }
});

/**
 * AJAX Handler: Hapus Kategori (wp_delete_term)
 */
add_action('wp_ajax_dprd_delete_category', function () {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Anda tidak memiliki hak akses.');
    }

    $nonce    = $_POST['nonce'] ?? '';
    $term_id  = absint($_POST['term_id'] ?? 0);
    $taxonomy = sanitize_text_field($_POST['taxonomy'] ?? '');

    if (!wp_verify_nonce($nonce, 'dprd_cat_mgr_nonce')) {
        wp_send_json_error('Nonce tidak valid.');
    }

    if (!$term_id || !$taxonomy) {
        wp_send_json_error('Data kategori tidak lengkap.');
    }

    $result = wp_delete_term($term_id, $taxonomy);
    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    } elseif ($result === false || $result === 0) {
        wp_send_json_error('Kategori tidak dapat dihapus.');
    } else {
        wp_send_json_success(['term_id' => $term_id]);
    }
});

/**
 * AJAX Handler: Tambah Kategori (wp_insert_term)
 */
add_action('wp_ajax_dprd_add_category', function () {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Anda tidak memiliki hak akses.');
    }

    $nonce    = $_POST['nonce'] ?? '';
    $cat_name = sanitize_text_field($_POST['cat_name'] ?? '');
    $taxonomy = sanitize_text_field($_POST['taxonomy'] ?? '');

    if (!wp_verify_nonce($nonce, 'dprd_cat_mgr_nonce')) {
        wp_send_json_error('Nonce tidak valid.');
    }

    if (empty($cat_name) || empty($taxonomy)) {
        wp_send_json_error('Nama kategori tidak boleh kosong.');
    }

    $inserted = wp_insert_term($cat_name, $taxonomy);
    if (is_wp_error($inserted)) {
        wp_send_json_error($inserted->get_error_message());
    } else {
        $term = get_term($inserted['term_id'], $taxonomy);
        wp_send_json_success([
            'term_id' => $term->term_id,
            'name'    => $term->name
        ]);
    }
});
