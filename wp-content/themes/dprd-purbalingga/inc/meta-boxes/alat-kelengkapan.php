<?php
/**
 * Meta Box for Alat Kelengkapan
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_ak_struktur_meta',
        'Struktur Alat Kelengkapan (Hierarki & Keanggotaan)',
        'dprd_render_ak_struktur_meta',
        'alat-kelengkapan',
        'normal',
        'default'
    );
});

function dprd_render_ak_struktur_meta($post) {
    wp_nonce_field('dprd_save_ak_struktur', 'dprd_ak_struktur_nonce');
    $raw_json = get_post_meta($post->ID, 'dprd_ak_struktur_json', true);
    $data = $raw_json ? $raw_json : '{"tipe":"badan", "hierarki":[]}';

    $anggota_posts = get_posts([
        'post_type'        => 'anggota',
        'posts_per_page'   => -1,
        'post_status'      => 'any',
        'orderby'          => 'title',
        'order'            => 'ASC',
        'suppress_filters' => true
    ]);
    
    $anggota_options = [];
    foreach ($anggota_posts as $a) {
        $anggota_options[$a->ID] = esc_html($a->post_title);
    }
    ?>
    <div id="ak-builder-wrapper">
        <input type="hidden" name="dprd_ak_struktur_json" id="dprd_ak_struktur_json" value="<?php echo esc_attr($data); ?>">
        <div id="ak-builder-container"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('ak-builder-container');
        const hiddenInput = document.getElementById('dprd_ak_struktur_json');
        const anggotaOptions = <?php echo json_encode($anggota_options); ?>;

        let data = { tipe: 'badan', hierarki: [] };
        try {
            if (hiddenInput.value) data = JSON.parse(hiddenInput.value);
        } catch(e) {}

        function getNode(path) {
            let current = data;
            for (let i = 0; i < path.length; i++) {
                current = current.children[path[i]];
            }
            return current;
        }

        window.updateNodeTipe = function(pathStr, tipe) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.tipe = tipe;
            if (tipe === 'grup' && !node.children) node.children = [];
            if (tipe === 'badan' && !node.hierarki) node.hierarki = [];
            render();
        };

        window.updateNodeNama = function(pathStr, nama) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.nama = nama;
            save();
        };

        window.addChildNode = function(pathStr) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            if (!node.children) node.children = [];
            node.children.push({ tipe: 'badan', nama: '', hierarki: [] });
            render();
        };

        window.removeChildNode = function(pathStr) {
            let path = JSON.parse(pathStr);
            if(confirm('Yakin ingin menghapus sub-alat kelengkapan ini?')) {
                let index = path.pop();
                let parent = getNode(path);
                parent.children.splice(index, 1);
                render();
            }
        };

        window.addHierarki = function(pathStr) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            if (!node.hierarki) node.hierarki = [];
            node.hierarki.push({ members: [] });
            render();
        };

        window.removeHierarki = function(pathStr) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            if (node.hierarki && node.hierarki.length > 0) {
                if(confirm('Yakin ingin menghapus level hierarki terendah beserta isinya?')) {
                    node.hierarki.pop();
                    render();
                }
            }
        };

        window.addMember = function(pathStr, lIdx) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.hierarki[lIdx].members.push({ jabatan: '', anggota_id: '' });
            render();
        };

        window.removeMember = function(pathStr, lIdx, mIdx) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.hierarki[lIdx].members.splice(mIdx, 1);
            render();
        };

        window.updateMember = function(pathStr, lIdx, mIdx, field, val) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.hierarki[lIdx].members[mIdx][field] = val;
            save();
        };

        function save() {
            hiddenInput.value = JSON.stringify(data);
        }

        function renderNode(node, path) {
            let pathStr = JSON.stringify(path);
            let html = `<div style="border:1px solid #ccd0d4; padding:15px; margin-bottom:10px; background:#fff; border-radius:4px;">`;
            
            if (path.length > 0) {
                html += `<div style="display:flex; gap:10px; align-items:center; margin-bottom:15px;">
                    <input type="text" class="widefat" value="${node.nama || ''}" onchange="updateNodeNama('${pathStr}', this.value)" placeholder="Nama Sub-Alat Kelengkapan (mis. Komisi I)" style="flex:1;">
                    <select onchange="updateNodeTipe('${pathStr}', this.value)">
                        <option value="badan" ${node.tipe==='badan'?'selected':''}>Tipe: Badan</option>
                        <option value="grup" ${node.tipe==='grup'?'selected':''}>Tipe: Grup</option>
                    </select>
                    <button type="button" class="button" onclick="removeChildNode('${pathStr}')">Hapus Blok</button>
                </div>`;
            } else {
                html += `<div style="margin-bottom:15px;">
                    <label><strong>Tipe Alat Kelengkapan Utama:</strong></label>
                    <select onchange="updateNodeTipe('${pathStr}', this.value)" style="margin-left:10px;">
                        <option value="badan" ${node.tipe==='badan'?'selected':''}>Badan (Langsung berisi anggota)</option>
                        <option value="grup" ${node.tipe==='grup'?'selected':''}>Grup (Berisi sub-alat kelengkapan)</option>
                    </select>
                </div>`;
            }

            if (node.tipe === 'grup') {
                html += `<div style="margin-left:20px; border-left:3px solid #0073aa; padding-left:15px;">`;
                if (node.children) {
                    node.children.forEach((child, idx) => {
                        html += renderNode(child, [...path, idx]);
                    });
                }
                html += `<button type="button" class="button button-primary" onclick="addChildNode('${pathStr}')">+ Tambah Sub-Alat Kelengkapan</button>`;
                html += `</div>`;
            } else {
                html += `<div>`;
                if (node.hierarki) {
                    node.hierarki.forEach((level, lIdx) => {
                        html += `<div style="border:1px solid #e2e4e7; padding:10px; margin-bottom:10px; background:#f9f9f9;">
                            <h4 style="margin-top:0;">Level Hierarki ${lIdx}</h4>
                            <table class="widefat" style="margin-bottom:10px;">
                                <thead><tr><th>Jabatan</th><th>Nama Anggota</th><th style="width:80px;">Aksi</th></tr></thead>
                                <tbody>`;
                        if (level.members) {
                            level.members.forEach((m, mIdx) => {
                                let opts = '<option value="">-- Pilih Anggota --</option>';
                                for (const id in anggotaOptions) {
                                    opts += `<option value="${id}" ${m.anggota_id==id?'selected':''}>${anggotaOptions[id]}</option>`;
                                }
                                html += `<tr>
                                    <td><input type="text" class="widefat" value="${m.jabatan||''}" onchange="updateMember('${pathStr}', ${lIdx}, ${mIdx}, 'jabatan', this.value)" placeholder="mis. Ketua"></td>
                                    <td><select class="widefat" onchange="updateMember('${pathStr}', ${lIdx}, ${mIdx}, 'anggota_id', this.value)">${opts}</select></td>
                                    <td><button type="button" class="button" onclick="removeMember('${pathStr}', ${lIdx}, ${mIdx})">Hapus</button></td>
                                </tr>`;
                            });
                        }
                        html += `</tbody></table>
                            <button type="button" class="button" onclick="addMember('${pathStr}', ${lIdx})">+ Tambah Anggota di Level ${lIdx}</button>
                        </div>`;
                    });
                }
                html += `<div style="margin-top:10px;">
                    <button type="button" class="button" onclick="addHierarki('${pathStr}')">+ Tambah Level Hierarki</button>
                    <button type="button" class="button" onclick="removeHierarki('${pathStr}')">- Kurangi Level Hierarki</button>
                </div></div>`;
            }
            
            html += `</div>`;
            return html;
        }

        function render() {
            container.innerHTML = renderNode(data, []);
            save();
        }

        render();
    });
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (isset($_POST['dprd_ak_struktur_nonce']) && wp_verify_nonce($_POST['dprd_ak_struktur_nonce'], 'dprd_save_ak_struktur')) {
        if (isset($_POST['dprd_ak_struktur_json'])) {
            $json = wp_unslash($_POST['dprd_ak_struktur_json']);
            update_post_meta($post_id, 'dprd_ak_struktur_json', $json);
        }
    }
});
