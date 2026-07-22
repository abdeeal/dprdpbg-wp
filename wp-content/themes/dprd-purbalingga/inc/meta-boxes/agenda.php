<?php
/**
 * Meta Box for Agenda (tanggal, waktu, lokasi, deskripsi)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_agenda_meta',
        'Detail Agenda Kegiatan',
        'dprd_render_agenda_meta_box',
        'agenda',
        'normal',
        'default'
    );
});

function dprd_render_agenda_meta_box($post) {
    wp_nonce_field('dprd_save_agenda_meta', 'dprd_agenda_meta_nonce');
    $tanggal = get_post_meta($post->ID, 'tanggal', true);
    $waktu = get_post_meta($post->ID, 'waktu', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_tanggal">Tanggal Agenda</label></th>
            <td>
                <input type="date" name="tanggal" id="dprd_tanggal" value="<?php echo esc_attr($tanggal); ?>" class="regular-text">
                <p class="description">Pilih tanggal dilaksanakannya agenda ini.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_waktu">Waktu / Jam</label></th>
            <td>
                <input type="text" name="waktu" id="dprd_waktu" value="<?php echo esc_attr($waktu); ?>" placeholder="Contoh: 09.00 WIB - Selesai" class="regular-text">
                <p class="description">Tulis jam pelaksanaan (misalnya: 09.00 WIB - Selesai, atau 10.00 - 12.00 WIB).</p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_agenda_meta_nonce']) || !wp_verify_nonce($_POST['dprd_agenda_meta_nonce'], 'dprd_save_agenda_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['tanggal'])) {
        update_post_meta($post_id, 'tanggal', sanitize_text_field($_POST['tanggal']));
    }
    if (isset($_POST['waktu'])) {
        update_post_meta($post_id, 'waktu', sanitize_text_field($_POST['waktu']));
    }
});

