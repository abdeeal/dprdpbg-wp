<?php
/**
 * Meta Box for Alat Kelengkapan
 */

if (!defined('ABSPATH')) exit;

// Register standard fields
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_alat_kelengkapan_meta',
        'Detail Alat Kelengkapan',
        'dprd_render_alat_kelengkapan_meta',
        'alat-kelengkapan',
        'normal',
        'default'
    );
});

function dprd_render_alat_kelengkapan_meta($post) {
    wp_nonce_field('dprd_save_alat_kelengkapan_meta', 'dprd_alat_kelengkapan_meta_nonce');
    $subtitle = get_post_meta($post->ID, 'subtitle', true);
    $dasar_pembentukan = get_post_meta($post->ID, 'dasarPembentukanContent', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_subtitle">Subtitle / Periode</label></th>
            <td>
                <input type="text" name="subtitle" id="dprd_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="large-text" placeholder="Contoh: Masa Jabatan 2024 - 2029">
            </td>
        </tr>
        <tr>
            <th><label for="dprd_dasar_pembentukan">Dasar Pembentukan</label></th>
            <td>
                <textarea name="dasarPembentukanContent" id="dprd_dasar_pembentukan" rows="4" class="large-text"><?php echo esc_textarea($dasar_pembentukan); ?></textarea>
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_alat_kelengkapan_meta_nonce']) || !wp_verify_nonce($_POST['dprd_alat_kelengkapan_meta_nonce'], 'dprd_save_alat_kelengkapan_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['subtitle'])) {
        update_post_meta($post_id, 'subtitle', sanitize_text_field($_POST['subtitle']));
    }
    if (isset($_POST['dasarPembentukanContent'])) {
        update_post_meta($post_id, 'dasarPembentukanContent', sanitize_textarea_field($_POST['dasarPembentukanContent']));
    }
});

// Register repeaters using DPRD_Repeater_Field
new DPRD_Repeater_Field(
    'members',
    'Daftar Anggota / Members',
    'alat-kelengkapan',
    [
        'nama' => ['label' => 'Nama Anggota', 'type' => 'text'],
        'foto' => ['label' => 'Foto', 'type' => 'image'],
        'jabatan' => ['label' => 'Jabatan', 'type' => 'text'],
    ]
);

new DPRD_Repeater_Field(
    'tugasList',
    'Daftar Tugas / Tugas List',
    'alat-kelengkapan',
    [
        'icon' => ['label' => 'Icon Name (e.g. check, user)', 'type' => 'text'],
        'title' => ['label' => 'Judul Tugas', 'type' => 'text'],
        'items' => ['label' => 'Deskripsi / Detail Tugas (satu per baris)', 'type' => 'textarea'],
    ]
);
