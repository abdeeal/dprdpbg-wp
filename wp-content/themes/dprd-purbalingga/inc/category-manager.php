<?php
/**
 * Quick Category Manager & Deleter for DPRD Purbalingga Theme
 * Memberikan fitur hapus & tambah kategori langsung dari Meta Box dan Admin Menu.
 */

if (!defined('ABSPATH')) exit;

/**
 * Peta Tipe Konten ke Taksonomi terkait
 */
function dprd_get_cpt_taxonomy_map() {
    return [
        'galeri'           => 'kategori-galeri',
        'sakip'            => 'kategori-sakip',
        'ppid'             => 'kategori-ppid',
        'propemperda'      => 'kategori-propemperda',
        'berita'           => 'category',
        'alat-kelengkapan' => 'jenis',
    ];
}

/**
 * Register Meta Box "Kelola & Hapus Kategori" di halaman edit post
 */
add_action('add_meta_boxes', function () {
    $map = dprd_get_cpt_taxonomy_map();
    foreach ($map as $post_type => $taxonomy) {
        $tax_obj = get_taxonomy($taxonomy);
        $tax_name = $tax_obj ? $tax_obj->labels->singular_name : 'Kategori';
        
        add_meta_box(
            'dprd_category_manager_box',
            'Kelola & Hapus ' . $tax_name,
            'dprd_render_category_manager_box',
            $post_type,
            'side',
            'default'
        );
    }
});

/**
 * Render tampilan Meta Box Kelola & Hapus Kategori
 */
function dprd_render_category_manager_box($post) {
    $map = dprd_get_cpt_taxonomy_map();
    $taxonomy = $map[$post->post_type] ?? '';
    if (!$taxonomy) return;

    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
    ]);

    wp_nonce_field('dprd_cat_mgr_nonce', 'dprd_cat_mgr_nonce_field');
    ?>
    <div id="dprd-cat-mgr-wrapper" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
        <p style="margin-top:0; font-size:12px; color:#646970;">
            Daftar kategori terdaftar. Klik <strong>Hapus</strong> untuk menghapus kategori yang salah dibuat.
        </p>

        <!-- List Kategori -->
        <ul id="dprd-cat-list" style="margin: 8px 0 15px; padding: 0; list-style: none; max-height: 200px; overflow-y: auto; border: 1px solid #ccd0d4; border-radius: 4px; background: #fff;">
            <?php if (empty($terms) || is_wp_error($terms)) : ?>
                <li style="padding: 8px 10px; font-style: italic; color: #888; font-size: 12px;">Belum ada kategori.</li>
            <?php else : ?>
                <?php foreach ($terms as $t) : ?>
                    <li style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-bottom: 1px solid #f0f0f1; font-size: 13px;" data-term-id="<?php echo esc_attr($t->term_id); ?>">
                        <span style="font-weight: 500; color: #1d2327;"><?php echo esc_html($t->name); ?></span>
                        <button type="button" class="button button-small dprd-del-cat-btn" data-id="<?php echo esc_attr($t->term_id); ?>" data-name="<?php echo esc_attr($t->name); ?>" style="color: #b32d2e; border-color: #b32d2e; background: #fff;">
                            Hapus
                        </button>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Input Tambah Kategori Baru -->
        <div style="display: flex; gap: 6px;">
            <input type="text" id="dprd-new-cat-input" placeholder="Nama kategori baru..." style="flex: 1; font-size: 12px; height: 30px;">
            <button type="button" id="dprd-add-cat-btn" class="button button-secondary button-small" style="height: 30px; white-space: nowrap;">
                + Tambah
            </button>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var wrapper = $('#dprd-cat-mgr-wrapper');
        var taxonomy = wrapper.data('taxonomy');
        var nonce = $('#dprd_cat_mgr_nonce_field').val();

        // Handler Hapus Kategori
        $(document).on('click', '.dprd-del-cat-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var termId = btn.data('id');
            var termName = btn.data('name');

            if (!confirm('Apakah Anda yakin ingin menghapus kategori "' + termName + '"? Kategori ini akan dihapus permanen.')) {
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
                                $('#dprd-cat-list').html('<li style="padding: 8px 10px; font-style: italic; color: #888; font-size: 12px;">Belum ada kategori.</li>');
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
            btn.prop('disabled', true).text('Proses...');

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
                    btn.prop('disabled', false).text('+ Tambah');
                    if (res.success) {
                        input.val('');
                        var t = res.data;
                        var newLi = '<li style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-bottom: 1px solid #f0f0f1; font-size: 13px;" data-term-id="' + t.term_id + '">' +
                            '<span style="font-weight: 500; color: #1d2327;">' + t.name + '</span>' +
                            '<button type="button" class="button button-small dprd-del-cat-btn" data-id="' + t.term_id + '" data-name="' + t.name + '" style="color: #b32d2e; border-color: #b32d2e; background: #fff;">Hapus</button>' +
                            '</li>';
                        
                        if ($('#dprd-cat-list li').first().text().indexOf('Belum ada kategori') !== -1) {
                            $('#dprd-cat-list').empty();
                        }
                        $('#dprd-cat-list').append(newLi);
                    } else {
                        alert('Gagal menambah kategori: ' + (res.data || 'Terjadi kesalahan.'));
                    }
                },
                error: function() {
                    btn.prop('disabled', false).text('+ Tambah');
                    alert('Gagal menambah kategori.');
                }
            });
        });
    });
    </script>
    <?php
}

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
