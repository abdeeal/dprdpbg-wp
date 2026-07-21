<?php
/**
 * Register Custom Post Types for DPRD Purbalingga Theme
 */

if (!defined('ABSPATH')) exit;

function dprd_register_post_types() {
    $cpts = [
        'berita' => [
            'singular' => 'Berita',
            'plural'   => 'Berita',
            'supports' => ['title', 'editor', 'thumbnail'],
            'icon'     => 'dashicons-format-aside',
        ],
        'galeri' => [
            'singular' => 'Galeri',
            'plural'   => 'Galeri',
            'supports' => ['title', 'thumbnail'],
            'icon'     => 'dashicons-images-alt2',
        ],
        'agenda' => [
            'singular' => 'Agenda',
            'plural'   => 'Agenda',
            'supports' => ['title', 'editor'],
            'icon'     => 'dashicons-calendar-alt',
        ],
        'anggota' => [
            'singular' => 'Anggota',
            'plural'   => 'Anggota',
            'supports' => ['title', 'editor'],
            'icon'     => 'dashicons-groups',
        ],
        'alat-kelengkapan' => [
            'singular' => 'Alat Kelengkapan',
            'plural'   => 'Alat Kelengkapan',
            'supports' => ['title', 'editor'],
            'icon'     => 'dashicons-awards',
        ],
        'ppid' => [
            'singular' => 'PPID',
            'plural'   => 'PPID',
            'supports' => ['title', 'editor'],
            'icon'     => 'dashicons-portfolio',
        ],
        'propemperda' => [
            'singular' => 'Propemperda',
            'plural'   => 'Propemperda',
            'supports' => ['title'],
            'icon'     => 'dashicons-welcome-write-blog',
        ],
        'sakip' => [
            'singular' => 'SAKIP',
            'plural'   => 'SAKIP',
            'supports' => ['title'],
            'icon'     => 'dashicons-analytics',
        ],
        'tokoh-sejarah' => [
            'singular' => 'Tokoh Sejarah',
            'plural'   => 'Tokoh Sejarah',
            'supports' => ['title', 'thumbnail'],
            'icon'     => 'dashicons-welcome-learn-more',
        ],
    ];

    foreach ($cpts as $slug => $cpt) {
        $labels = [
            'name'               => $cpt['plural'],
            'singular_name'      => $cpt['singular'],
            'menu_name'          => $cpt['plural'],
            'add_new'            => 'Tambah Baru',
            'add_new_item'       => 'Tambah ' . $cpt['singular'] . ' Baru',
            'edit_item'          => 'Edit ' . $cpt['singular'],
            'new_item'           => $cpt['singular'] . ' Baru',
            'view_item'          => 'Lihat ' . $cpt['singular'],
            'search_items'       => 'Cari ' . $cpt['plural'],
            'not_found'          => $cpt['singular'] . ' tidak ditemukan',
            'not_found_in_trash' => $cpt['singular'] . ' tidak ditemukan di tempat sampah',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'show_in_rest'       => true, // Mengaktifkan editor Gutenberg
            'menu_icon'          => $cpt['icon'],
            'supports'           => $cpt['supports'],
            'rewrite'            => ['slug' => $slug],
        ];

        register_post_type($slug, $args);
    }
}
add_action('init', 'dprd_register_post_types');

/**
 * Ubah teks placeholder "Tambahkan judul" agar sesuai dengan konteks masing-masing tipe konten.
 */
function dprd_change_title_placeholder($title, $post) {
    switch ($post->post_type) {
        case 'anggota':
            return 'Nama Anggota';
        case 'alat-kelengkapan':
            return 'Nama Alat Kelengkapan';
        case 'tokoh-sejarah':
            return 'Masukkan Nama Tokoh Sejarah';
        case 'propemperda':
            return 'Masukkan Tahun (Contoh: Tahun 2026)';
        case 'sakip':
            return 'Masukkan Nama Dokumen Laporan (Contoh: Renja Sekretariat DPRD 2024)';
    }
    return $title;
}
add_filter('enter_title_here', 'dprd_change_title_placeholder', 10, 2);
