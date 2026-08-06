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
    <style>
    .dprd-member-opt:hover {
        background-color: #0073aa !important;
        color: #ffffff !important;
    }
    </style>
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

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderMemberSelect(pathStr, lIdx, mIdx, selectedId) {
            let currentTitle = anggotaOptions[selectedId] || '';
            let optionsHtml = `<div class="dprd-member-opt" data-id="" style="padding:6px 10px; cursor:pointer; color:#888; border-bottom:1px solid #eee;" onclick="selectMemberOpt(this, '${pathStr}', ${lIdx}, ${mIdx})">-- Tanpa Anggota / Kosongkan --</div>`;
            
            for (const id in anggotaOptions) {
                let title = anggotaOptions[id];
                let isSel = (id == selectedId) ? 'background:#e7f3f9; font-weight:bold;' : '';
                optionsHtml += `<div class="dprd-member-opt" data-id="${id}" data-search="${escapeHtml(title.toLowerCase())}" style="padding:6px 10px; cursor:pointer; border-bottom:1px solid #f9f9f9; ${isSel}" onclick="selectMemberOpt(this, '${pathStr}', ${lIdx}, ${mIdx})">${escapeHtml(title)}</div>`;
            }

            return `<div class="dprd-member-select-wrapper" style="position:relative; width:100%;">
                <div style="position:relative; display:flex; align-items:center;">
                    <input type="text" 
                           class="widefat dprd-member-search-input" 
                           value="${escapeHtml(currentTitle)}" 
                           placeholder="-- Cari & Pilih Anggota Dewan --"
                           onfocus="openMemberDropdown(this)"
                           oninput="filterMemberDropdown(this)"
                           autocomplete="off"
                           style="padding-right:24px;">
                    <span class="dashicons dashicons-arrow-down-alt2" style="position:absolute; right:6px; color:#666; pointer-events:none;"></span>
                </div>
                <div class="dprd-member-dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; max-height:220px; overflow-y:auto; background:#fff; border:1px solid #0073aa; border-radius:0 0 4px 4px; box-shadow:0 6px 16px rgba(0,0,0,0.15); z-index:99999;">
                    ${optionsHtml}
                </div>
            </div>`;
        }

        window.openMemberDropdown = function(input) {
            document.querySelectorAll('.dprd-member-dropdown').forEach(d => d.style.display = 'none');
            let dropdown = input.closest('.dprd-member-select-wrapper').querySelector('.dprd-member-dropdown');
            dropdown.style.display = 'block';
            filterMemberDropdown(input);
        };

        window.filterMemberDropdown = function(input) {
            let term = input.value.toLowerCase().trim();
            let wrapper = input.closest('.dprd-member-select-wrapper');
            let dropdown = wrapper.querySelector('.dprd-member-dropdown');
            dropdown.style.display = 'block';
            let opts = dropdown.querySelectorAll('.dprd-member-opt');
            
            opts.forEach(opt => {
                if (!opt.dataset.id) {
                    opt.style.display = 'block';
                    return;
                }
                let search = opt.dataset.search || '';
                if (search.indexOf(term) !== -1) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
        };

        window.selectMemberOpt = function(optEl, pathStr, lIdx, mIdx) {
            let id = optEl.dataset.id || '';
            let title = id ? (anggotaOptions[id] || '') : '';
            let wrapper = optEl.closest('.dprd-member-select-wrapper');
            let input = wrapper.querySelector('.dprd-member-search-input');
            input.value = title;
            wrapper.querySelector('.dprd-member-dropdown').style.display = 'none';
            updateMember(pathStr, lIdx, mIdx, 'anggota_id', id);
        };

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dprd-member-select-wrapper')) {
                document.querySelectorAll('.dprd-member-dropdown').forEach(d => d.style.display = 'none');
            }
        });

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

        window.addMitra = function(pathStr) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            if (!node.mitra_kerja) node.mitra_kerja = [];
            node.mitra_kerja.push('');
            render();
        };

        window.removeMitra = function(pathStr, mIdx) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.mitra_kerja.splice(mIdx, 1);
            render();
        };

        window.updateMitra = function(pathStr, mIdx, val) {
            let path = JSON.parse(pathStr);
            let node = getNode(path);
            node.mitra_kerja[mIdx] = val;
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
                                <thead><tr><th style="width:35%;">Jabatan</th><th style="width:55%;">Nama Anggota (Cari & Pilih)</th><th style="width:10%; text-align:center;">Aksi</th></tr></thead>
                                <tbody>`;
                        if (level.members) {
                            level.members.forEach((m, mIdx) => {
                                let selectHtml = renderMemberSelect(pathStr, lIdx, mIdx, m.anggota_id);
                                html += `<tr>
                                    <td style="vertical-align:top;"><input type="text" class="widefat" value="${m.jabatan||''}" onchange="updateMember('${pathStr}', ${lIdx}, ${mIdx}, 'jabatan', this.value)" placeholder="mis. Ketua"></td>
                                    <td style="vertical-align:top;">${selectHtml}</td>
                                    <td style="vertical-align:top; text-align:center;"><button type="button" class="button" onclick="removeMember('${pathStr}', ${lIdx}, ${mIdx})">Hapus</button></td>
                                </tr>`;
                            });
                        }
                        html += `</tbody></table>
                            <button type="button" class="button" onclick="addMember('${pathStr}', ${lIdx})">+ Tambah Anggota di Level ${lIdx}</button>
                        </div>`;
                    });
                }

                // Render Mitra Kerja list
                let mitras = node.mitra_kerja || [];
                html += `<div style="border:1px solid #ccd0d4; padding:15px; margin-top:15px; background:#fff; border-radius:4px; margin-bottom:15px;">
                    <h4 style="margin-top:0; margin-bottom:10px;">Daftar Mitra Kerja</h4>
                    <div style="display:flex; flex-direction:column; gap:5px; margin-bottom:10px;">`;
                mitras.forEach((m, mIdx) => {
                    html += `<div style="display:flex; gap:5px; align-items:center;">
                        <input type="text" class="widefat" value="${m || ''}" onchange="updateMitra('${pathStr}', ${mIdx}, this.value)" placeholder="Nama Instansi/Dinas Mitra Kerja" style="flex:1;">
                        <button type="button" class="button" onclick="removeMitra('${pathStr}', ${mIdx})" style="color:#a00;">Hapus</button>
                    </div>`;
                });
                html += `</div>
                    <button type="button" class="button button-small" onclick="addMitra('${pathStr}')">+ Tambah Mitra Kerja</button>
                </div>`;

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
