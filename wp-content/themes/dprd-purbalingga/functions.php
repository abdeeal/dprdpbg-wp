<?php
/**
 * DPRD Purbalingga Theme — Core Setup
 */

if (!defined('ABSPATH')) exit; // Keamanan: cegah akses langsung

// --- Theme Support ---
function dprd_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);
    add_theme_support('align-wide');

    register_nav_menus([
        'primary' => __('Menu Utama', 'dprd-purbalingga'),
    ]);
}
add_action('after_setup_theme', 'dprd_theme_setup');

// --- Enqueue CSS & JS hasil build Vite ---
function dprd_enqueue_assets() {
    // Fonts
    wp_enqueue_style(
        'dprd-fonts',
        'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
        [],
        null
    );

    // Default style
    wp_enqueue_style('dprd-style', get_stylesheet_uri());

    $dist_path = get_template_directory() . '/assets/dist';
    $dist_uri  = get_template_directory_uri() . '/assets/dist';

    $css_file = $dist_path . '/main.css';
    $js_file  = $dist_path . '/main.js';

    if (file_exists($css_file)) {
        wp_enqueue_style(
            'dprd-main-style',
            $dist_uri . '/main.css',
            ['dprd-fonts'],
            filemtime($css_file)
        );
    }

    if (file_exists($js_file)) {
        wp_enqueue_script(
            'dprd-main-script',
            $dist_uri . '/main.js',
            [],
            filemtime($js_file),
            true // load di footer
        );
    }
}
add_action('wp_enqueue_scripts', 'dprd_enqueue_assets');

// --- Load CPT, Taxonomies, and Custom Systems ---
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/taxonomies.php';
require get_template_directory() . '/inc/class-repeater-field.php';
require get_template_directory() . '/inc/options-pages.php';
require get_template_directory() . '/inc/sekilas-data.php';
require get_template_directory() . '/inc/insert-default-data.php';

// Autoload all Meta Box controllers
foreach (glob(get_template_directory() . '/inc/meta-boxes/*.php') as $file) {
    require $file;
}

// --- Kompresi Gambar Otomatis & Konversi ke WEBP saat Upload ---
add_filter('wp_handle_upload', 'dprd_convert_upload_to_webp');
function dprd_convert_upload_to_webp($upload) {
    if ($upload['type'] == 'image/jpeg' || $upload['type'] == 'image/png') {
        $file_path = $upload['file'];
        $image = null;
        
        // Load gambar berdasarkan tipenya
        if ($upload['type'] == 'image/jpeg') {
            if (function_exists('imagecreatefromjpeg')) {
                $image = imagecreatefromjpeg($file_path);
            }
        } elseif ($upload['type'] == 'image/png') {
            if (function_exists('imagecreatefrompng')) {
                $image = imagecreatefrompng($file_path);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
            }
        }

        if ($image) {
            // Ubah ekstensi file asli menjadi .webp
            $webp_path = preg_replace('/\\.(jpg|jpeg|png)$/i', '.webp', $file_path);
            
            // Simpan gambar sebagai WebP dengan kualitas 80% (optimal kompresi)
            if (function_exists('imagewebp') && imagewebp($image, $webp_path, 80)) {
                // Hapus file JPG/PNG asli agar hemat kapasitas disk
                unlink($file_path);
                
                // Perbarui informasi file upload di WordPress
                $upload['file'] = $webp_path;
                $upload['url'] = preg_replace('/\\.(jpg|jpeg|png)$/i', '.webp', $upload['url']);
                $upload['type'] = 'image/webp';
            }
            imagedestroy($image);
        }
    }
    return $upload;
}

// Set kualitas kompresi WebP bawaan WordPress menjadi 80%
add_filter('wp_editor_set_quality', function($quality, $mime_type) {
    if ('image/webp' === $mime_type) {
        return 80;
    }
    return $quality;
}, 10, 2);