<?php
/**
 * Register Custom Taxonomies for DPRD Purbalingga Theme
 */

if (!defined('ABSPATH')) exit;

function dprd_register_taxonomies() {
    // Taksonomi Jabatan untuk CPT Anggota
    register_taxonomy('jabatan', ['anggota'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Jabatan',
            'singular_name'     => 'Jabatan',
            'search_items'      => 'Cari Jabatan',
            'all_items'         => 'Semua Jabatan',
            'parent_item'       => 'Induk Jabatan',
            'parent_item_colon' => 'Induk Jabatan:',
            'edit_item'         => 'Edit Jabatan',
            'update_item'       => 'Perbarui Jabatan',
            'add_new_item'      => 'Tambah Jabatan Baru',
            'new_item_name'     => 'Nama Jabatan Baru',
            'menu_name'         => 'Jabatan',
        ],
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'jabatan'],
    ]);

    // Taksonomi Jenis untuk CPT Alat Kelengkapan (Komisi, Fraksi, Badan)
    register_taxonomy('jenis', ['alat-kelengkapan'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Jenis Alat Kelengkapan',
            'singular_name'     => 'Jenis',
            'search_items'      => 'Cari Jenis',
            'all_items'         => 'Semua Jenis',
            'edit_item'         => 'Edit Jenis',
            'update_item'         => 'Perbarui Jenis',
            'add_new_item'      => 'Tambah Jenis Baru',
            'new_item_name'     => 'Nama Jenis Baru',
            'menu_name'         => 'Jenis',
        ],
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'jenis-alat-kelengkapan'],
    ]);

    // Taksonomi Kategori untuk CPT Galeri (Filter Tab)
    register_taxonomy('kategori-galeri', ['galeri'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Kategori Galeri',
            'singular_name'     => 'Kategori',
            'search_items'      => 'Cari Kategori',
            'all_items'         => 'Semua Kategori',
            'edit_item'         => 'Edit Kategori',
            'update_item'         => 'Perbarui Kategori',
            'add_new_item'      => 'Tambah Kategori Baru',
            'new_item_name'     => 'Nama Kategori Baru',
            'menu_name'         => 'Kategori',
        ],
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kategori-galeri'],
    ]);
}
add_action('init', 'dprd_register_taxonomies');
