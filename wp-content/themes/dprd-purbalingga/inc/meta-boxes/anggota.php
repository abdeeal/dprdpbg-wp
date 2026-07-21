<?php
/**
 * Meta Box for Anggota (Foto Diri dengan Cropper 3/4)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_anggota_meta',
        'Foto Diri (Rasio 3:4)',
        'dprd_render_anggota_meta_box',
        'anggota',
        'normal',
        'high'
    );
});

function dprd_render_anggota_meta_box($post) {
    wp_nonce_field('dprd_save_anggota_meta', 'dprd_anggota_meta_nonce');
    $foto_diri = get_post_meta($post->ID, 'foto_diri', true);
    $image_url = $foto_diri ? wp_get_attachment_image_url((int)$foto_diri, 'large') : '';
    ?>
    <div class="dprd-image-field" style="padding: 10px 0;">
        <input type="hidden" class="dprd-image-id" name="foto_diri" value="<?php echo esc_attr($foto_diri); ?>">
        <div class="dprd-image-preview">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width:200px; display:block; margin-bottom:15px; border:1px solid #ccc; border-radius:4px;">
            <?php endif; ?>
        </div>
        <button type="button" class="button dprd-select-image-single" data-crop="3/4">
            <?php echo $foto_diri ? 'Ganti Foto Diri' : 'Pilih Foto Diri'; ?>
        </button>
        <button type="button" class="button-link dprd-remove-image-single" style="<?php echo $foto_diri ? '' : 'display:none;'; ?> color:#a00;">
            Hapus Foto
        </button>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('.dprd-select-image-single');
        const removeBtn = document.querySelector('.dprd-remove-image-single');
        if (!btn) return;

        btn.addEventListener('click', function() {
            const field = btn.closest('.dprd-image-field');
            const frame = wp.media({ title: 'Pilih Foto Diri', multiple: false, library: { type: 'image' } });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                
                if (typeof Cropper === 'undefined') {
                    alert('Cropper.js belum dimuat. Mohon muat ulang halaman.');
                    return;
                }

                // Buat Modal Cropper
                const modal = document.createElement('div');
                modal.style.cssText = 'position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:999999; display:flex; flex-direction:column; align-items:center; justify-content:center;';
                
                const container = document.createElement('div');
                container.style.cssText = 'width:90%; height:90%; max-width:800px; background:#fff; padding:20px; box-sizing:border-box; border-radius:8px; display:flex; flex-direction:column;';
                
                const title = document.createElement('h2');
                title.textContent = 'Sesuaikan Pas Foto (3:4)';
                title.style.cssText = 'margin-top:0;';
                
                const imgContainer = document.createElement('div');
                imgContainer.style.cssText = 'flex:1; overflow:hidden; background:#333; margin-bottom:20px; display:flex; align-items:center; justify-content:center;';
                
                const img = document.createElement('img');
                img.src = attachment.url;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                imgContainer.appendChild(img);
                
                const actions = document.createElement('div');
                actions.style.cssText = 'text-align:right; flex-shrink:0;';
                
                const cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.className = 'button';
                cancelBtn.textContent = 'Batal';
                cancelBtn.style.marginRight = '10px';
                
                const cropBtn = document.createElement('button');
                cropBtn.type = 'button';
                cropBtn.className = 'button button-primary';
                cropBtn.textContent = 'Crop & Simpan';
                
                actions.appendChild(cancelBtn);
                actions.appendChild(cropBtn);
                
                container.appendChild(title);
                container.appendChild(imgContainer);
                container.appendChild(actions);
                modal.appendChild(container);
                document.body.appendChild(modal);

                const cropper = new Cropper(img, { aspectRatio: 3/4, viewMode: 2 });

                cancelBtn.addEventListener('click', function() {
                    cropper.destroy();
                    modal.remove();
                });

                cropBtn.addEventListener('click', function() {
                    cropBtn.textContent = 'Memproses...';
                    cropBtn.disabled = true;
                    
                    cropper.getCroppedCanvas({ maxWidth: 1200, maxHeight: 1600 }).toBlob(function(blob) {
                        const formData = new FormData();
                        formData.append('action', 'dprd_upload_cropped_image');
                        formData.append('image', blob, 'foto-diri-' + attachment.id + '.jpg');
                        formData.append('_ajax_nonce', dprd_repeater_vars.nonce);

                        fetch(dprd_repeater_vars.ajax_url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                field.querySelector('.dprd-image-id').value = res.data.id;
                                field.querySelector('.dprd-image-preview').innerHTML = '<img src="' + res.data.url + '" style="max-width:200px;display:block;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;">';
                                btn.textContent = 'Ganti Foto Diri';
                                removeBtn.style.display = '';
                                cropper.destroy();
                                modal.remove();
                            } else {
                                alert('Gagal crop gambar');
                                cropBtn.textContent = 'Crop & Simpan';
                                cropBtn.disabled = false;
                            }
                        })
                        .catch(err => {
                            alert('Terjadi kesalahan jaringan.');
                            cropBtn.textContent = 'Crop & Simpan';
                            cropBtn.disabled = false;
                        });
                    }, 'image/jpeg', 0.9);
                });
            });
            frame.open();
        });

        removeBtn.addEventListener('click', function() {
            const field = this.closest('.dprd-image-field');
            field.querySelector('.dprd-image-id').value = '';
            field.querySelector('.dprd-image-preview').innerHTML = '';
            btn.textContent = 'Pilih Foto Diri';
            this.style.display = 'none';
        });
    });
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_anggota_meta_nonce']) || !wp_verify_nonce($_POST['dprd_anggota_meta_nonce'], 'dprd_save_anggota_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['foto_diri'])) {
        update_post_meta($post_id, 'foto_diri', absint($_POST['foto_diri']));
    }
});
