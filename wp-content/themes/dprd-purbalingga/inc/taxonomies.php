<?php
/**
 * Register Custom Taxonomies for DPRD Purbalingga Theme
 */

if (!defined('ABSPATH')) exit;

function dprd_register_taxonomies() {
    // 1. Taksonomi Jenis untuk CPT Alat Kelengkapan (Komisi, Fraksi, Badan)
    register_taxonomy('jenis', ['alat-kelengkapan'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Jenis Alat Kelengkapan',
            'singular_name'     => 'Jenis',
            'search_items'      => 'Cari Jenis',
            'all_items'         => 'Semua Jenis',
            'edit_item'         => 'Edit Jenis',
            'update_item'       => 'Perbarui Jenis',
            'add_new_item'      => 'Tambah Jenis Baru',
            'new_item_name'     => 'Nama Jenis Baru',
            'menu_name'         => 'Kelola Jenis',
        ],
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'jenis-alat-kelengkapan'],
    ]);

    // 2. Taksonomi Kategori untuk CPT Galeri (Filter Tab)
    register_taxonomy('kategori-galeri', ['galeri'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Kategori Galeri',
            'singular_name'     => 'Kategori Galeri',
            'search_items'      => 'Cari Kategori Galeri',
            'all_items'         => 'Semua Kategori Galeri',
            'edit_item'         => 'Edit Kategori Galeri',
            'update_item'       => 'Perbarui Kategori Galeri',
            'add_new_item'      => 'Tambah Kategori Baru',
            'new_item_name'     => 'Nama Kategori Baru',
            'menu_name'         => 'Kelola Kategori',
        ],
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kategori-galeri'],
    ]);

    // 3. Taksonomi Kategori SAKIP
    register_taxonomy('kategori-sakip', ['sakip'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Kategori SAKIP',
            'singular_name'     => 'Kategori SAKIP',
            'search_items'      => 'Cari Kategori SAKIP',
            'all_items'         => 'Semua Kategori SAKIP',
            'edit_item'         => 'Edit Kategori SAKIP',
            'update_item'       => 'Perbarui Kategori SAKIP',
            'add_new_item'      => 'Tambah Kategori Baru',
            'new_item_name'     => 'Nama Kategori Baru',
            'menu_name'         => 'Kelola Kategori SAKIP',
        ],
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kategori-sakip'],
    ]);

    // 4. Taksonomi Kategori PPID
    register_taxonomy('kategori-ppid', ['ppid'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Kategori PPID',
            'singular_name'     => 'Kategori PPID',
            'search_items'      => 'Cari Kategori PPID',
            'all_items'         => 'Semua Kategori PPID',
            'edit_item'         => 'Edit Kategori PPID',
            'update_item'       => 'Perbarui Kategori PPID',
            'add_new_item'      => 'Tambah Kategori Baru',
            'new_item_name'     => 'Nama Kategori Baru',
            'menu_name'         => 'Kelola Kategori PPID',
        ],
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kategori-ppid'],
    ]);

    // 5. Taksonomi Kategori Propemperda
    register_taxonomy('kategori-propemperda', ['propemperda'], [
        'hierarchical'      => true,
        'labels'            => [
            'name'              => 'Kategori Propemperda',
            'singular_name'     => 'Kategori Propemperda',
            'search_items'      => 'Cari Kategori Propemperda',
            'all_items'         => 'Semua Kategori Propemperda',
            'edit_item'         => 'Edit Kategori Propemperda',
            'update_item'       => 'Perbarui Kategori Propemperda',
            'add_new_item'      => 'Tambah Kategori Baru',
            'new_item_name'     => 'Nama Kategori Baru',
            'menu_name'         => 'Kelola Kategori Propemperda',
        ],
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'kategori-propemperda'],
    ]);
}
add_action('init', 'dprd_register_taxonomies');
