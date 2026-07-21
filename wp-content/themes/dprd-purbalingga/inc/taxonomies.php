<?php
/**
 * Register Custom Taxonomies for DPRD Purbalingga Theme
 */

if (!defined('ABSPATH')) exit;

function dprd_register_taxonomies() {
    // Taksonomi Jabatan dihapus karena dikelola via Alat Kelengkapan

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

    // Taksonomi Kategori untuk CPT SAKIP (Renja, Renstra, Anggaran, dsb.)
    register_taxonomy('kategori-sakip', ['sakip'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Kategori SAKIP',
            'singular_name'     => 'Kategori SAKIP',
            'search_items'      => 'Cari Kategori SAKIP',
            'all_items'         => 'Semua Kategori SAKIP',
            'edit_item'         => 'Edit Kategori SAKIP',
            'update_item'         => 'Perbarui Kategori SAKIP',
            'add_new_item'      => 'Tambah Kategori SAKIP Baru',
            'new_item_name'     => 'Nama Kategori Baru',
            'menu_name'         => 'Kategori SAKIP',
        ],
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kategori-sakip'],
    ]);
}
add_action('init', 'dprd_register_taxonomies');
