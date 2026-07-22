<?php
/**
 * Options Page: Pengaturan Situs DPRD Purbalingga
 * Pengganti ACF Options Page — 100% native, gratis.
 *
 * Menyimpan 3 kelompok data ke wp_options:
 * - dprd_navigation_json  → repeater bercabang (nested), lewat DPRD_Repeater_Field
 * - dprd_banner_json      → repeater biasa (gambar, judul, link)
 * - dprd_hero_stats_*     → field sederhana (anggota, fraksi, komisi, periode)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_menu_page(
        'Pengaturan Situs DPRD',
        'Beranda',
        'manage_options',
        'dprd-site-settings',
        'dprd_render_site_settings_page',
        'dashicons-admin-generic',
        60
    );
});

/**
 * Definisikan instance repeater di sini (bukan lewat add_meta_boxes,
 * karena ini bukan post type — kita panggil render_field_only() &
 * sanitize_from_post() secara manual).
 */


function dprd_get_banner_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_banner_json',
            'Banner Beranda',
            null,
            [
                'image' => ['label' => 'Gambar', 'type' => 'image', 'crop' => '3/1'],
            ],
            false
        );
    }
    return $instance;
}

// Ensure the repeater singletons are instantiated early enough so their
// enqueue_scripts hooks are registered before admin_enqueue_scripts fires.
add_action('admin_init', function() {
    dprd_get_banner_repeater();
});

function dprd_render_site_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // ── Handle submit ──────────────────────────────────────────────
    if (
        isset($_POST['dprd_settings_nonce'])
        && wp_verify_nonce($_POST['dprd_settings_nonce'], 'dprd_save_settings')
    ) {
        // Banner (repeater biasa)
        if (isset($_POST['dprd_banner_json'])) {
            $banner = dprd_get_banner_repeater();
            $clean_json = $banner->sanitize_from_post($_POST['dprd_banner_json']);
            update_option('dprd_banner_json', $clean_json);
        }

        // Hero stats (field sederhana)
        update_option('dprd_hero_image', absint($_POST['dprd_hero_image'] ?? 0));
        update_option('dprd_hero_stats_anggota', absint($_POST['dprd_hero_stats_anggota'] ?? 0));
        update_option('dprd_hero_stats_fraksi', absint($_POST['dprd_hero_stats_fraksi'] ?? 0));
        update_option('dprd_hero_stats_komisi', absint($_POST['dprd_hero_stats_komisi'] ?? 0));
        update_option('dprd_hero_stats_periode_mulai', absint($_POST['dprd_hero_stats_periode_mulai'] ?? 0));
        update_option('dprd_hero_stats_periode_akhir', absint($_POST['dprd_hero_stats_periode_akhir'] ?? 0));
        update_option('dprd_pengumuman_strip', sanitize_text_field($_POST['dprd_pengumuman_strip'] ?? ''));

        echo '<div class="notice notice-success is-dismissible"><p>Pengaturan disimpan.</p></div>';
    }

    // ── Ambil data tersimpan ───────────────────────────────────────
    $banner_rows     = dprd_get_option_repeater('dprd_banner_json');

    $hero_image    = get_option('dprd_hero_image', '');
    $stats_anggota = get_option('dprd_hero_stats_anggota', '');
    $stats_fraksi  = get_option('dprd_hero_stats_fraksi', '');
    $stats_komisi  = get_option('dprd_hero_stats_komisi', '');
    $stats_periode_mulai = get_option('dprd_hero_stats_periode_mulai', '');
    $stats_periode_akhir = get_option('dprd_hero_stats_periode_akhir', '');
    $pengumuman_strip    = get_option('dprd_pengumuman_strip', '');
    ?>
    <div class="wrap">
        <h1>Pengaturan Situs DPRD Purbalingga</h1>
        <form method="post">
            <?php wp_nonce_field('dprd_save_settings', 'dprd_settings_nonce'); ?>

            <h2>Navigasi Menu</h2>
            <p class="description">
                Pengaturan Navigasi Menu telah dipindahkan ke fitur bawaan WordPress.<br>
                Silakan atur menu (mendukung multi-level tanpa batas) melalui <strong><a href="<?php echo admin_url('nav-menus.php'); ?>">Appearance &gt; Menus</a></strong>.
            </p>

            <h2>Strip Pengumuman (Teks Berjalan)</h2>
            <table class="form-table">
                <tr>
                    <th><label for="dprd_pengumuman_strip">Teks Pengumuman</label></th>
                    <td>
                        <textarea id="dprd_pengumuman_strip" name="dprd_pengumuman_strip" class="large-text" rows="3" placeholder="mis. Sidang Paripurna berlangsung hari ini, pukul 09.00 WIB di Ruang Rapat Paripurna DPRD."><?php echo esc_textarea($pengumuman_strip); ?></textarea>
                        <p class="description">Teks ini akan muncul sebagai pengumuman berjalan (marquee) di bagian atas website. Kosongkan jika tidak ada pengumuman.</p>
                    </td>
                </tr>
            </table>

            <hr>

            <h2>Banner Beranda</h2>
            <?php dprd_get_banner_repeater()->render_field_only($banner_rows); ?>

            <hr>

            <h2>Statistik Hero Beranda</h2>
            <table class="form-table">
                <tr>
                    <th><label>Gambar Latar Hero (Hero Image)</label></th>
                    <td>
                        <?php $hero_url = $hero_image ? wp_get_attachment_image_url($hero_image, 'large') : ''; ?>
                        <div class="dprd-single-image-wrapper">
                            <input type="hidden" name="dprd_hero_image" id="dprd_hero_image" value="<?php echo esc_attr($hero_image); ?>">
                            <div id="dprd_hero_image_preview" style="margin-bottom:10px;">
                                <?php if ($hero_url): ?>
                                    <img src="<?php echo esc_url($hero_url); ?>" style="max-width:300px; display:block;">
                                <?php endif; ?>
                            </div>
                            <button type="button" class="button" id="dprd_upload_hero_btn"><?php echo $hero_image ? 'Ganti Gambar' : 'Pilih Gambar'; ?></button>
                            <button type="button" class="button-link" id="dprd_remove_hero_btn" style="<?php echo $hero_image ? '' : 'display:none;'; ?> color:#a00; text-decoration:none;">Hapus Gambar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_anggota">Jumlah Anggota Dewan</label></th>
                    <td><input type="number" min="0" id="dprd_hero_stats_anggota" name="dprd_hero_stats_anggota" class="regular-text" value="<?php echo esc_attr($stats_anggota); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_fraksi">Jumlah Fraksi</label></th>
                    <td><input type="number" min="0" id="dprd_hero_stats_fraksi" name="dprd_hero_stats_fraksi" class="regular-text" value="<?php echo esc_attr($stats_fraksi); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_komisi">Jumlah Komisi</label></th>
                    <td><input type="number" min="0" id="dprd_hero_stats_komisi" name="dprd_hero_stats_komisi" class="regular-text" value="<?php echo esc_attr($stats_komisi); ?>"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_periode_mulai">Periode Mulai Jabatan</label></th>
                    <td><input type="number" min="1900" id="dprd_hero_stats_periode_mulai" name="dprd_hero_stats_periode_mulai" class="regular-text" value="<?php echo esc_attr($stats_periode_mulai ?: ''); ?>" placeholder="mis. 2024"></td>
                </tr>
                <tr>
                    <th><label for="dprd_hero_stats_periode_akhir">Periode Berakhir Jabatan</label></th>
                    <td><input type="number" min="1900" id="dprd_hero_stats_periode_akhir" name="dprd_hero_stats_periode_akhir" class="regular-text" value="<?php echo esc_attr($stats_periode_akhir ?: ''); ?>" placeholder="mis. 2029"></td>
                </tr>
            </table>

            <?php submit_button('Simpan Pengaturan'); ?>
        </form>
    </div>
    <script>
    jQuery(document).ready(function($){
        var frame;
        $('#dprd_upload_hero_btn').on('click', function(e) {
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: 'Pilih Gambar Hero',
                button: { text: 'Gunakan Gambar Ini' },
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                if (typeof Cropper !== 'undefined' && typeof dprd_repeater_vars !== 'undefined') {
                    openHeroCropper(attachment, '16/9');
                } else {
                    $('#dprd_hero_image').val(attachment.id);
                    var url = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;
                    $('#dprd_hero_image_preview').html('<img src="' + url + '" style="max-width:300px; display:block;">');
                    $('#dprd_upload_hero_btn').text('Ganti Gambar');
                    $('#dprd_remove_hero_btn').show();
                }
            });
            frame.open();
        });

        function openHeroCropper(attachment, cropRatio) {
            var ratioParts = cropRatio.split('/');
            var ratio = ratioParts.length === 2 ? parseInt(ratioParts[0]) / parseInt(ratioParts[1]) : NaN;

            var modal = document.createElement('div');
            modal.style.cssText = 'position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:999999; display:flex; flex-direction:column; align-items:center; justify-content:center;';
            
            var container = document.createElement('div');
            container.style.cssText = 'width:90%; height:90%; max-width:1000px; background:#fff; padding:20px; box-sizing:border-box; border-radius:8px; display:flex; flex-direction:column;';
            
            var title = document.createElement('h2');
            title.textContent = 'Sesuaikan Crop Hero (' + cropRatio + ')';
            title.style.cssText = 'margin-top:0;';
            
            var imgContainer = document.createElement('div');
            imgContainer.style.cssText = 'flex:1; overflow:hidden; background:#333; margin-bottom:20px; display:flex; align-items:center; justify-content:center;';
            
            var img = document.createElement('img');
            img.src = attachment.url;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '100%';
            imgContainer.appendChild(img);
            
            var actions = document.createElement('div');
            actions.style.cssText = 'text-align:right; flex-shrink:0;';
            
            var cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.className = 'button';
            cancelBtn.textContent = 'Batal';
            cancelBtn.style.marginRight = '10px';
            
            var cropBtn = document.createElement('button');
            cropBtn.type = 'button';
            cropBtn.className = 'button button-primary';
            cropBtn.textContent = 'Crop & Gunakan';
            
            actions.appendChild(cancelBtn);
            actions.appendChild(cropBtn);
            
            container.appendChild(title);
            container.appendChild(imgContainer);
            container.appendChild(actions);
            modal.appendChild(container);
            document.body.appendChild(modal);

            var cropper = new Cropper(img, {
                aspectRatio: ratio || NaN,
                viewMode: 2,
            });

            cancelBtn.addEventListener('click', function() {
                cropper.destroy();
                modal.remove();
            });

            cropBtn.addEventListener('click', function() {
                cropBtn.textContent = 'Memproses...';
                cropBtn.disabled = true;
                
                cropper.getCroppedCanvas({
                    maxWidth: 1920,
                    maxHeight: 1080
                }).toBlob(function(blob) {
                    var formData = new FormData();
                    formData.append('action', 'dprd_upload_cropped_image');
                    formData.append('image', blob, 'cropped-hero-' + attachment.id + '.webp');
                    formData.append('_ajax_nonce', dprd_repeater_vars.nonce);

                    fetch(dprd_repeater_vars.ajax_url, {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(res) {
                        if (res.success) {
                            $('#dprd_hero_image').val(res.data.id);
                            $('#dprd_hero_image_preview').html('<img src="' + res.data.url + '" style="max-width:300px; display:block;">');
                            $('#dprd_upload_hero_btn').text('Ganti Gambar');
                            $('#dprd_remove_hero_btn').show();
                            cropper.destroy();
                            modal.remove();
                        } else {
                            alert('Gagal crop gambar: ' + (res.data || 'Error'));
                            cropBtn.textContent = 'Crop & Gunakan';
                            cropBtn.disabled = false;
                        }
                    })
                    .catch(function(err) {
                        alert('Terjadi kesalahan jaringan.');
                        cropBtn.textContent = 'Crop & Gunakan';
                        cropBtn.disabled = false;
                    });
                }, 'image/webp', 0.75);
            });
        }
        $('#dprd_remove_hero_btn').on('click', function(e) {
            e.preventDefault();
            $('#dprd_hero_image').val('');
            $('#dprd_hero_image_preview').html('');
            $('#dprd_upload_hero_btn').text('Pilih Gambar');
            $(this).hide();
        });
    });
    </script>
    <?php
}
