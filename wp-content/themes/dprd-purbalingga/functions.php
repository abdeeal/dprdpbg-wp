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

// --- Placeholder: CPT & ACF Field Groups akan didaftarkan di sini (Fase 2) ---
// require get_template_directory() . '/inc/post-types.php';
// require get_template_directory() . '/inc/acf-fields.php';

require get_template_directory() . '/inc/class-repeater-field.php';
require get_template_directory() . '/inc/options-pages.php';