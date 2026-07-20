<?php
/**
 * Meta Box for Video (url_embed, thumbnail)
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_video_meta',
        'Informasi Video',
        'dprd_render_video_meta_box',
        'video',
        'normal',
        'default'
    );
});

function dprd_render_video_meta_box($post) {
    wp_nonce_field('dprd_save_video_meta', 'dprd_video_meta_nonce');
    $url_embed = get_post_meta($post->ID, 'url_embed', true);
    $thumbnail = get_post_meta($post->ID, 'thumbnail', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_url_embed">URL / Embed Video</label></th>
            <td>
                <input type="text" name="url_embed" id="dprd_url_embed" value="<?php echo esc_url($url_embed); ?>" placeholder="Contoh: https://www.youtube.com/embed/xxxxxx" class="large-text">
                <p class="description">Masukkan link embed YouTube atau video source lainnya.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_thumbnail">URL Thumbnail Gambar</label></th>
            <td>
                <input type="text" name="thumbnail" id="dprd_thumbnail" value="<?php echo esc_url($thumbnail); ?>" placeholder="Contoh: https://img.youtube.com/vi/xxxxxx/maxresdefault.jpg" class="large-text">
                <p class="description">Masukkan URL gambar untuk cover/thumbnail video.</p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dprd_video_meta_nonce']) || !wp_verify_nonce($_POST['dprd_video_meta_nonce'], 'dprd_save_video_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['url_embed'])) {
        update_post_meta($post_id, 'url_embed', esc_url_raw($_POST['url_embed']));
    }
    if (isset($_POST['thumbnail'])) {
        update_post_meta($post_id, 'thumbnail', esc_url_raw($_POST['thumbnail']));
    }
});
