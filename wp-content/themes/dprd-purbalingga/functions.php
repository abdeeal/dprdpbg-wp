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
require get_template_directory() . '/inc/lucide-icons.php';
require get_template_directory() . '/inc/insert-default-data.php';

// Autoload all Meta Box controllers
foreach (glob(get_template_directory() . '/inc/meta-boxes/*.php') as $file) {
    require $file;
}

// --- Kompresi Gambar Otomatis & Konversi ke WEBP saat Upload ---
add_filter('wp_handle_upload', 'dprd_convert_upload_to_webp');
function dprd_convert_upload_to_webp($upload) {
    if ($upload['type'] == 'image/jpeg' || $upload['type'] == 'image/png' || $upload['type'] == 'image/webp') {
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
        } elseif ($upload['type'] == 'image/webp') {
            if (function_exists('imagecreatefromwebp')) {
                $image = imagecreatefromwebp($file_path);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
            }
        }

        if ($image) {
            // Resize gambar jika ukurannya terlalu besar untuk membatasi file size di bawah 200KB
            $width = imagesx($image);
            $height = imagesy($image);
            $max_size = 1200; // Maksimal lebar/tinggi 1200px sudah sangat tajam untuk web
            
            if ($width > $max_size || $height > $max_size) {
                if ($width > $height) {
                    $new_width = $max_size;
                    $new_height = floor($height * ($max_size / $width));
                } else {
                    $new_height = $max_size;
                    $new_width = floor($width * ($max_size / $height));
                }
                $resized_image = imagecreatetruecolor($new_width, $new_height);
                
                // Preserve transparency
                imagealphablending($resized_image, false);
                imagesavealpha($resized_image, true);
                
                imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagedestroy($image);
                $image = $resized_image;
            }

            // Tentukan jalur file WebP
            $webp_path = $file_path;
            if ($upload['type'] !== 'image/webp') {
                $webp_path = preg_replace('/\\.(jpg|jpeg|png)$/i', '.webp', $file_path);
            }
            
            // Simpan gambar sebagai WebP dengan kualitas 75%
            if (function_exists('imagewebp') && imagewebp($image, $webp_path, 75)) {
                // Hapus file JPG/PNG asli (jika ekstensinya bukan .webp sejak awal)
                if ($webp_path !== $file_path) {
                    unlink($file_path);
                }
                
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

// Prioritaskan GD library dibanding Imagick untuk memproses gambar.
// Ini memperbaiki error upload WebP di lingkungan local XAMPP/PHP yang Imagick-nya tidak memiliki library WebP.
add_filter('wp_image_editors', function($editors) {
    return ['WP_Image_Editor_GD', 'WP_Image_Editor_Imagick'];
});

/**
 * Auto-fix nav menu item URLs if missing home_url base or containing double slashes
 */
add_action('init', function() {
    if (get_option('dprd_menu_urls_cleaned_v4')) return;

    $locations = get_nav_menu_locations();
    if (isset($locations['primary'])) {
        $menu_items = wp_get_nav_menu_items($locations['primary']);
        if ($menu_items) {
            foreach ($menu_items as $mi) {
                $url = $mi->url;
                if (strpos($url, 'jdih.purbalinggakab.go.id') !== false) continue;

                $parsed = wp_parse_url($url);
                if (isset($parsed['path'])) {
                    $path = $parsed['path'];
                    $site_path = wp_parse_url(home_url(), PHP_URL_PATH) ?: '';
                    if ($site_path && strpos($path, $site_path) === 0) {
                        $path = substr($path, strlen($site_path));
                    }
                    $clean_path = '/' . ltrim(preg_replace('#/+#', '/', $path), '/');
                    $new_url = home_url($clean_path);
                    if ($new_url !== $url) {
                        update_post_meta($mi->ID, '_menu_item_url', $new_url);
                    }
                }
            }
        }
    }
    update_option('dprd_menu_urls_cleaned_v4', true);
});

// =============================================================================
// FILE PROXY — Sembunyikan path wp-content/uploads & perbaiki nama PDF
// =============================================================================
// URL publik: /dprd-purbalingga/?dprd_file=123&judul=nama-dokumen
// → Membaca file dari upload dir, kirim sebagai inline PDF dengan nama judul
// → Menggantikan URL langsung yang bocorkan path server
// =============================================================================

/**
 * Helper: konversi URL upload langsung ke URL proxy yang aman.
 *
 * Digunakan di template SAKIP, Propemperda, PPID, dll.
 *
 * @param int    $post_id   ID post CPT (sakip / ppid / propemperda)
 * @param string $file_url  URL asli file di wp-content/uploads
 * @param string $title     Judul dokumen (untuk nama file yang tampil di browser)
 * @return string URL proxy
 */
function dprd_proxy_url(int $post_id, string $file_url, string $title): string {
    if (empty($file_url) || $file_url === '#') return '#';
    $slug = sanitize_title($title) ?: 'dokumen';
    return add_query_arg([
        'dprd_file' => $post_id,
        'judul'     => urlencode($slug),
        'src'       => urlencode($file_url), // URL asli di-encode, tidak terbaca langsung
    ], home_url('/'));
}

/**
 * Tangani request proxy file: verifikasi, serve PDF dengan header yang benar.
 */
add_action('template_redirect', function () {
    if (!isset($_GET['dprd_file'])) return;

    $post_id  = absint($_GET['dprd_file']);
    $judul    = sanitize_title($_GET['judul'] ?? 'dokumen');
    $src      = isset($_GET['src']) ? esc_url_raw(urldecode($_GET['src'])) : '';

    if (!$post_id || empty($src)) {
        status_header(400);
        wp_die('Parameter tidak valid.', 'Error', ['response' => 400]);
    }

    // Validasi: src harus berasal dari uploads WordPress kita sendiri
    $upload_dir  = wp_upload_dir();
    $uploads_url = trailingslashit($upload_dir['baseurl']);

    if (strpos($src, $uploads_url) !== 0) {
        status_header(403);
        wp_die('Akses ditolak.', 'Forbidden', ['response' => 403]);
    }

    // Konversi URL → path file di server
    $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $src);
    $file_path = wp_normalize_path($file_path);

    // Cegah path traversal — normalize kedua path ke forward-slash (Windows compat)
    $real_dir   = realpath(dirname($file_path));
    $upload_base = wp_normalize_path($upload_dir['basedir']);

    if (!$real_dir || strpos(wp_normalize_path($real_dir), $upload_base) !== 0) {
        status_header(403);
        wp_die('Akses ditolak.', 'Forbidden', ['response' => 403]);
    }

    if (!file_exists($file_path)) {
        status_header(404);
        wp_die('File tidak ditemukan.', 'Not Found', ['response' => 404]);
    }

    // Hanya izinkan PDF
    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mime     = finfo_file($finfo, $file_path);
    finfo_close($finfo);

    if ($mime !== 'application/pdf') {
        status_header(403);
        wp_die('Tipe file tidak didukung.', 'Forbidden', ['response' => 403]);
    }

    $display_name = $judul . '.pdf';

    // Kirim header + file
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $display_name . '"');
    header('Content-Length: ' . filesize($file_path));
    header('Cache-Control: private, max-age=3600');
    header('X-Content-Type-Options: nosniff');
    header('X-Robots-Tag: noindex'); // Jangan di-index search engine

    // Matikan buffer WordPress agar tidak corrupt
    while (ob_get_level()) ob_end_clean();

    readfile($file_path);
    exit;
});