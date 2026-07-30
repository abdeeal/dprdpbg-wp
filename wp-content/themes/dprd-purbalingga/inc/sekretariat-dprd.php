<?php
/**
 * Options Page / Admin Menu: Sekretariat DPRD
 * Pengaturan data & dokumen Sekretariat DPRD Purbalingga (Bagan Organisasi, Pejabat Struktural, dll.)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_menu_page(
        'Sekretariat DPRD',
        'Sekretariat DPRD',
        'manage_options',
        'sekretariat-dprd',
        'dprd_render_sekretariat_dprd_page',
        'dashicons-businessperson',
        25
    );
});

// Enqueue WP Media Uploader di halaman Sekretariat DPRD
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_sekretariat-dprd') {
        wp_enqueue_media();
    }
});

function dprd_render_sekretariat_dprd_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle submit form
    if (
        isset($_POST['dprd_sekretariat_nonce'])
        && wp_verify_nonce($_POST['dprd_sekretariat_nonce'], 'dprd_save_sekretariat')
    ) {
        $bagan_id  = absint($_POST['dprd_bagan_organisasi_id'] ?? 0);
        $bagan_url = esc_url_raw($_POST['dprd_bagan_organisasi_url'] ?? '');

        update_option('dprd_bagan_organisasi_id', $bagan_id);
        update_option('dprd_bagan_organisasi_url', $bagan_url);

        if (isset($_POST['dprd_pejabat_struktural_json'])) {
            $json = wp_unslash($_POST['dprd_pejabat_struktural_json']);
            update_option('dprd_pejabat_struktural_json', $json);
        }

        echo '<div class="notice notice-success is-dismissible"><p>Pengaturan Sekretariat DPRD berhasil disimpan.</p></div>';
    }

    $bagan_id  = get_option('dprd_bagan_organisasi_id', 0);
    $bagan_url = get_option('dprd_bagan_organisasi_url', '');
    $is_pdf    = (bool) preg_match('/\.pdf$/i', $bagan_url);

    $raw_pejabat_json = get_option('dprd_pejabat_struktural_json', '[]');

    // Ambil daftar anggota sekretariat dari DB post type 'anggota-sekretariat'
    $anggota_posts = get_posts([
        'post_type'        => 'anggota-sekretariat',
        'posts_per_page'   => -1,
        'post_status'      => 'publish',
        'orderby'          => 'title',
        'order'            => 'ASC',
        'suppress_filters' => true
    ]);

    $anggota_options = [];
    foreach ($anggota_posts as $a) {
        $anggota_options[$a->ID] = esc_html($a->post_title);
    }
    ?>
    <div class="wrap">
        <h1>Pengaturan Sekretariat DPRD</h1>
        <form method="post">
            <?php wp_nonce_field('dprd_save_sekretariat', 'dprd_sekretariat_nonce'); ?>

            <!-- SECTION 1: BAGAN ORGANISASI -->
            <h2>Bagan Organisasi</h2>
            <table class="form-table">
                <tr>
                    <th><label for="dprd_bagan_organisasi_url">Berkas Bagan Organisasi</label></th>
                    <td>
                        <div class="dprd-bagan-wrapper" style="max-width: 600px;">
                            <input type="hidden" name="dprd_bagan_organisasi_id" id="dprd_bagan_organisasi_id" value="<?php echo esc_attr($bagan_id); ?>">
                            <input type="text" name="dprd_bagan_organisasi_url" id="dprd_bagan_organisasi_url" value="<?php echo esc_url($bagan_url); ?>" class="large-text" placeholder="https://..." readonly style="margin-bottom: 10px;">

                            <div id="dprd_bagan_preview" style="margin-bottom: 12px; border: 1px dashed #ccc; padding: 10px; background: #fafafa; border-radius: 4px; text-align: center;">
                                <?php if ($bagan_url): ?>
                                    <?php if ($is_pdf): ?>
                                        <p><strong>Dokumen PDF Terpilih:</strong> <a href="<?php echo esc_url($bagan_url); ?>" target="_blank"><?php echo esc_html(basename($bagan_url)); ?></a></p>
                                    <?php else: ?>
                                        <img src="<?php echo esc_url($bagan_url); ?>" style="max-width: 100%; max-height: 350px; display: block; margin: 0 auto;">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p style="color: #888; margin: 10px 0;">Belum ada file Bagan Organisasi diunggah.</p>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; gap: 8px;">
                                <button type="button" class="button button-secondary" id="dprd_upload_bagan_btn">
                                    <?php echo $bagan_url ? 'Ganti File Bagan' : 'Upload / Pilih File Bagan'; ?>
                                </button>
                                <button type="button" class="button-link" id="dprd_remove_bagan_btn" style="<?php echo $bagan_url ? '' : 'display:none;'; ?> color: #a00; text-decoration: none;">
                                    Hapus File
                                </button>
                            </div>
                            <p class="description" style="margin-top: 8px;">Unggah file gambar (JPG, PNG, WEBP) atau berkas PDF Bagan Struktur Organisasi Sekretariat DPRD.</p>
                        </div>
                    </td>
                </tr>
            </table>

            <hr style="margin: 30px 0;">

            <!-- SECTION 2: PEJABAT STRUKTURAL (MULTI LEVEL) -->
            <h2>Pejabat Struktural</h2>
            <p class="description">Susun struktur hierarki Pejabat Struktural secara bertingkat (multi-level). Pilih nama pejabat dari database Anggota Dewan / Pegawai.</p>

            <div id="pejabat-builder-wrapper" style="margin-top: 15px; max-width: 950px;">
                <input type="hidden" name="dprd_pejabat_struktural_json" id="dprd_pejabat_struktural_json" value="<?php echo esc_attr($raw_pejabat_json); ?>">
                <div id="pejabat-builder-container"></div>
                <button type="button" class="button button-primary" id="add-root-pejabat-btn" style="margin-top: 10px;">+ Tambah Pejabat Utama</button>
            </div>

            <br>
            <?php submit_button('Simpan Pengaturan'); ?>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($){
        // ── MEDIA UPLOADER ─────────────────────────────────────────────
        var baganFrame;

        $('#dprd_upload_bagan_btn').on('click', function(e) {
            e.preventDefault();
            if (baganFrame) {
                baganFrame.open();
                return;
            }
            baganFrame = wp.media({
                title: 'Pilih / Unggah File Bagan Organisasi',
                button: { text: 'Gunakan File Ini' },
                multiple: false
            });
            baganFrame.on('select', function() {
                var attachment = baganFrame.state().get('selection').first().toJSON();
                $('#dprd_bagan_organisasi_id').val(attachment.id);
                $('#dprd_bagan_organisasi_url').val(attachment.url);

                var isPdf = attachment.url.match(/\.pdf$/i);
                if (isPdf) {
                    $('#dprd_bagan_preview').html('<p><strong>Dokumen PDF Terpilih:</strong> <a href="' + attachment.url + '" target="_blank">' + attachment.filename + '</a></p>');
                } else {
                    $('#dprd_bagan_preview').html('<img src="' + attachment.url + '" style="max-width:100%; max-height:350px; display:block; margin:0 auto;">');
                }

                $('#dprd_upload_bagan_btn').text('Ganti File Bagan');
                $('#dprd_remove_bagan_btn').show();
            });
            baganFrame.open();
        });

        $('#dprd_remove_bagan_btn').on('click', function(e) {
            e.preventDefault();
            $('#dprd_bagan_organisasi_id').val('');
            $('#dprd_bagan_organisasi_url').val('');
            $('#dprd_bagan_preview').html('<p style="color: #888; margin: 10px 0;">Belum ada file Bagan Organisasi diunggah.</p>');
            $('#dprd_upload_bagan_btn').text('Upload / Pilih File Bagan');
            $(this).hide();
        });

        // ── MULTI-LEVEL PEJABAT STRUKTURAL BUILDER ─────────────────────
        const container = document.getElementById('pejabat-builder-container');
        const hiddenInput = document.getElementById('dprd_pejabat_struktural_json');
        const anggotaOptions = <?php echo json_encode($anggota_options); ?>;

        let treeData = [];
        try {
            if (hiddenInput.value) {
                treeData = JSON.parse(hiddenInput.value);
            }
        } catch(e) {
            treeData = [];
        }
        if (!Array.isArray(treeData)) treeData = [];

        function getNodeByPath(path) {
            let current = treeData;
            for (let i = 0; i < path.length; i++) {
                if (i === 0) {
                    current = current[path[i]];
                } else {
                    current = current.children[path[i]];
                }
            }
            return current;
        }

        window.updatePejabatField = function(pathStr, field, value) {
            let path = JSON.parse(pathStr);
            let node = getNodeByPath(path);
            if (node) {
                node[field] = value;
                saveTree();
            }
        };

        window.addSubPejabat = function(pathStr) {
            let path = JSON.parse(pathStr);
            let node = getNodeByPath(path);
            if (node) {
                if (!node.children) node.children = [];
                node.children.push({ jabatan: '', anggota_id: '', children: [] });
                renderTree();
            }
        };

        window.removePejabatNode = function(pathStr) {
            if (!confirm('Yakin ingin menghapus pejabat ini beserta semua sub-pejabat di bawahnya?')) return;
            let path = JSON.parse(pathStr);
            let indexToRemove = path.pop();
            if (path.length === 0) {
                treeData.splice(indexToRemove, 1);
            } else {
                let parentNode = getNodeByPath(path);
                if (parentNode && parentNode.children) {
                    parentNode.children.splice(indexToRemove, 1);
                }
            }
            renderTree();
        };

        $('#add-root-pejabat-btn').on('click', function(e) {
            e.preventDefault();
            treeData.push({ jabatan: '', anggota_id: '', children: [] });
            renderTree();
        });

        function saveTree() {
            hiddenInput.value = JSON.stringify(treeData);
        }

        function renderNodeList(nodes, pathPrefix) {
            if (!nodes || nodes.length === 0) return '';
            let html = '';
            nodes.forEach((node, idx) => {
                let currentPath = [...pathPrefix, idx];
                let pathStr = JSON.stringify(currentPath);
                let level = currentPath.length - 1;

                let borderColors = ['#0073aa', '#2271b1', '#4f94d4', '#72aee6', '#9ec2e6'];
                let borderColor = borderColors[Math.min(level, borderColors.length - 1)];

                html += `<div style="border-left: 4px solid ${borderColor}; background: #fff; border-top: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0; border-radius: 4px; padding: 12px 15px; margin-bottom: 10px; margin-left: ${level * 20}px;">`;

                html += `<div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">`;

                // Input Jabatan
                html += `<div style="flex: 1; min-width: 220px;">`;
                html += `<input type="text" class="widefat" value="${node.jabatan || ''}" onchange="updatePejabatField('${pathStr}', 'jabatan', this.value)" placeholder="Nama Jabatan (mis. Sekretaris DPRD / Kasubag)">`;
                html += `</div>`;

                // Select Anggota
                html += `<div style="flex: 1; min-width: 220px;">`;
                let opts = '<option value="">-- Pilih Nama Anggota / Pejabat --</option>';
                for (const id in anggotaOptions) {
                    opts += `<option value="${id}" ${node.anggota_id == id ? 'selected' : ''}>${anggotaOptions[id]}</option>`;
                }
                html += `<select class="widefat" onchange="updatePejabatField('${pathStr}', 'anggota_id', this.value)">${opts}</select>`;
                html += `</div>`;

                // Actions
                html += `<div style="display: flex; gap: 5px;">`;
                html += `<button type="button" class="button button-small" onclick="addSubPejabat('${pathStr}')">+ Sub-Jabatan</button>`;
                html += `<button type="button" class="button button-small" onclick="removePejabatNode('${pathStr}')" style="color:#a00;">Hapus</button>`;
                html += `</div>`;

                html += `</div>`; // end flex container

                // Recursive render sub-pejabat / children
                if (node.children && node.children.length > 0) {
                    html += `<div style="margin-top: 10px;">`;
                    html += renderNodeList(node.children, currentPath);
                    html += `</div>`;
                }

                html += `</div>`;
            });
            return html;
        }

        function renderTree() {
            if (treeData.length === 0) {
                container.innerHTML = '<div style="background: #fafafa; border: 1px dashed #ccc; padding: 20px; text-align: center; color: #666; border-radius: 4px; margin-bottom: 10px;">Belum ada data Pejabat Struktural. Klik tombol di bawah untuk menambah Pejabat Utama.</div>';
            } else {
                container.innerHTML = renderNodeList(treeData, []);
            }
            saveTree();
        }

        renderTree();
    });
    </script>
    <?php
}
