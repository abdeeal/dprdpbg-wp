<?php
/**
 * DPRD Purbalingga — Open Graph & Meta Tags for Social Media Sharing (WhatsApp, Facebook, Twitter, Telegram, dll)
 */

if (!defined('ABSPATH')) exit;

function dprd_add_open_graph_meta_tags() {
    global $post, $wp;

    $site_name   = get_bloginfo('name');
    $title       = '';
    $description = '';
    $url         = '';
    $image_url   = '';
    $image_width  = 1200;
    $image_height = 630;
    $type        = 'website';

    $default_logo = get_template_directory_uri() . '/assets/images/logo-dprd-purbalingga.png';

    if (is_singular() && isset($post->ID)) {
        $type  = 'article';
        $title = get_the_title($post->ID);
        $url   = get_permalink($post->ID);

        // Auto Excerpt untuk deskripsi ringkasan berita
        if (function_exists('dprd_get_auto_excerpt')) {
            $description = dprd_get_auto_excerpt($post);
        } else {
            $description = wp_strip_all_tags($post->post_excerpt ?: $post->post_content);
        }
        $description = wp_trim_words($description, 35, '...');

        // 1. Gambar Utama (Featured Image / Thumbnail Berita)
        if (has_post_thumbnail($post->ID)) {
            $thumb_id = get_post_thumbnail_id($post->ID);
            $img_data = wp_get_attachment_image_src($thumb_id, 'full');
            if ($img_data) {
                $image_url    = $img_data[0];
                $image_width  = $img_data[1] ?: 1200;
                $image_height = $img_data[2] ?: 630;
            }
        }

        // 2. Fallback 1: Cek repeater foto jika berita memiliki gambar tambahan
        if (empty($image_url) && function_exists('get_dprd_repeater')) {
            $repeater_imgs = get_dprd_repeater($post->ID, 'dprd_berita_images_json');
            if (!empty($repeater_imgs) && !empty($repeater_imgs[0]['image_id'])) {
                $add_img = wp_get_attachment_image_src(intval($repeater_imgs[0]['image_id']), 'full');
                if ($add_img) {
                    $image_url    = $add_img[0];
                    $image_width  = $add_img[1] ?: 1200;
                    $image_height = $add_img[2] ?: 630;
                }
            }
        }

        // 3. Fallback 2: Cek tag <img> di dalam post_content artikel
        if (empty($image_url)) {
            if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $post->post_content, $matches)) {
                $image_url = $matches[1];
            }
        }
    } elseif (is_front_page() || is_home()) {
        $title       = $site_name . ' — ' . (get_bloginfo('description') ?: 'Kabupaten Purbalingga');
        $description = get_bloginfo('description') ?: 'Website Resmi DPRD Kabupaten Purbalingga';
        $url         = home_url('/');
    } else {
        $title       = wp_get_document_title();
        $description = get_bloginfo('description') ?: 'Website Resmi DPRD Kabupaten Purbalingga';
        $url         = home_url(add_query_arg([], $wp->request));
    }

    // Ultimate Fallback jika tidak ada gambar thumbnail
    if (empty($image_url)) {
        $image_url = $default_logo;
    }

    // Pastikan URL gambar absolut
    if ($image_url && strpos($image_url, 'http') !== 0) {
        $image_url = home_url($image_url);
    }

    // Tanggal publikasi & modifikasi untuk article
    $published_time = '';
    $modified_time  = '';
    if ($type === 'article' && isset($post->ID)) {
        $published_time = get_the_date('c', $post->ID);
        $modified_time  = get_the_modified_date('c', $post->ID);
    }

    // Clean html entities for title and description
    $clean_title       = esc_attr(trim(html_entity_decode(wp_strip_all_tags($title), ENT_QUOTES, 'UTF-8')));
    $clean_description = esc_attr(trim(html_entity_decode(wp_strip_all_tags($description), ENT_QUOTES, 'UTF-8')));
    $clean_url         = esc_url($url);
    $clean_image       = esc_url($image_url);

    // Mime type detection for og:image:type
    $image_mime = 'image/jpeg';
    if (preg_match('/\.webp$/i', $clean_image)) {
        $image_mime = 'image/webp';
    } elseif (preg_match('/\.png$/i', $clean_image)) {
        $image_mime = 'image/png';
    }
    ?>

    <!-- Standard SEO Meta Tags -->
    <?php if (!empty($clean_description)) : ?>
    <meta name="description" content="<?php echo $clean_description; ?>">
    <?php endif; ?>

    <!-- Open Graph Meta Tags (WhatsApp, Telegram, Facebook, LinkedIn) -->
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:type" content="<?php echo esc_attr($type); ?>">
    <meta property="og:title" content="<?php echo $clean_title; ?>">
    <meta property="og:description" content="<?php echo $clean_description; ?>">
    <meta property="og:url" content="<?php echo $clean_url; ?>">
    <meta property="og:image" content="<?php echo $clean_image; ?>">
    <meta property="og:image:secure_url" content="<?php echo $clean_image; ?>">
    <meta property="og:image:type" content="<?php echo esc_attr($image_mime); ?>">
    <?php if ($image_width && $image_height) : ?>
    <meta property="og:image:width" content="<?php echo esc_attr($image_width); ?>">
    <meta property="og:image:height" content="<?php echo esc_attr($image_height); ?>">
    <?php endif; ?>

    <?php if ($image_mime === 'image/webp') : ?>
    <!-- PNG Fallback Image for WhatsApp clients that don't support WebP previews -->
    <meta property="og:image" content="<?php echo esc_url($default_logo); ?>">
    <meta property="og:image:secure_url" content="<?php echo esc_url($default_logo); ?>">
    <meta property="og:image:type" content="image/png">
    <?php endif; ?>

    <meta property="og:image:alt" content="<?php echo $clean_title; ?>">
    <meta property="og:locale" content="id_ID">

    <?php if ($type === 'article' && !empty($published_time)) : ?>
    <meta property="article:published_time" content="<?php echo esc_attr($published_time); ?>">
    <meta property="article:modified_time" content="<?php echo esc_attr($modified_time); ?>">
    <?php endif; ?>

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $clean_title; ?>">
    <meta name="twitter:description" content="<?php echo $clean_description; ?>">
    <meta name="twitter:image" content="<?php echo $clean_image; ?>">
    <?php
}
add_action('wp_head', 'dprd_add_open_graph_meta_tags', 1);
