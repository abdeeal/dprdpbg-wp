<?php
/**
 * Logika Pencarian Kustom DPRD Purbalingga
 * (Berita, Galeri, Anggota, Dokumen)
 */

if (!defined('ABSPATH')) exit;

/**
 * Mencari secara menyeluruh menggunakan AND-logic.
 *
 * @param string $query String kueri pencarian dari user
 * @return array Array berisi kunci 'berita', 'galeri', 'anggota', 'dokumen' (berisi array WP_Post)
 */
function dprd_search_all($query) {
    global $wpdb;
    
    $results = [
        'berita'  => [],
        'galeri'  => [],
        'anggota' => [],
        'dokumen' => []
    ];

    $query = sanitize_text_field($query);
    if (empty(trim($query))) return $results;

    // Normalisasi: lower case, strip tags, hilangkan simbol non-alfanumerik khusus, lalu pisah per spasi
    $query_norm = mb_strtolower(strip_tags($query));
    $query_norm = preg_replace('/[^\w\s]/u', ' ', $query_norm);
    $words = array_values(array_filter(explode(' ', $query_norm)));
    
    if (empty($words)) return $results;

    // Helper closure untuk membentuk klausa LIKE
    $generate_like = function($word, $fields) use ($wpdb) {
        $like = '%' . $wpdb->esc_like($word) . '%';
        $clauses = [];
        foreach ($fields as $field) {
            $clauses[] = $wpdb->prepare("{$field} LIKE %s", $like);
        }
        return '(' . implode(' OR ', $clauses) . ')';
    };

    // 1. Pencarian Berita
    $berita_sql = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'post_tag'
        LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'berita' AND p.post_status = 'publish'
    ";
    foreach ($words as $word) {
        $fields = ['p.post_title', 'p.post_content', 'p.post_excerpt', 't.name'];
        $berita_sql .= " AND " . $generate_like($word, $fields);
    }
    $berita_sql .= " GROUP BY p.ID ORDER BY MAX(p.post_date) DESC";
    $berita_ids = $wpdb->get_col($berita_sql);

    // 2. Pencarian Galeri
    $galeri_sql = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'kategori-galeri'
        LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'galeri' AND p.post_status = 'publish'
    ";
    foreach ($words as $word) {
        $fields = ['p.post_title', 't.name'];
        $galeri_sql .= " AND " . $generate_like($word, $fields);
    }
    $galeri_sql .= " GROUP BY p.ID ORDER BY MAX(p.post_date) DESC";
    $galeri_ids = $wpdb->get_col($galeri_sql);

    // 3. Pencarian Anggota & Organisasi
    $anggota_sql = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'dprd_ak_members'
        WHERE p.post_type IN ('anggota', 'alat-kelengkapan', 'tokoh-sejarah') AND p.post_status = 'publish'
    ";
    foreach ($words as $word) {
        $fields = ['p.post_title', 'p.post_content', 'pm.meta_value', 'p.post_type'];
        $anggota_sql .= " AND " . $generate_like($word, $fields);
    }
    $anggota_sql .= " GROUP BY p.ID ORDER BY MAX(p.post_date) DESC";
    $anggota_ids = $wpdb->get_col($anggota_sql);

    // 4. Pencarian Dokumen
    $dokumen_sql = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        WHERE p.post_type IN ('sakip', 'ppid', 'propemperda') AND p.post_status = 'publish'
    ";
    foreach ($words as $word) {
        $fields = ['p.post_title', 'p.post_content', 'p.post_type'];
        $dokumen_sql .= " AND " . $generate_like($word, $fields);
    }
    $dokumen_sql .= " GROUP BY p.ID ORDER BY MAX(p.post_date) DESC";
    $dokumen_ids = $wpdb->get_col($dokumen_sql);

    // 5. Fetch objek WP_Post jika ID ditemukan
    if (!empty($berita_ids)) {
        $results['berita'] = get_posts([
            'post_type'      => 'berita',
            'post__in'       => $berita_ids,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'posts_per_page' => -1
        ]);
    }
    if (!empty($galeri_ids)) {
        $results['galeri'] = get_posts([
            'post_type'      => 'galeri',
            'post__in'       => $galeri_ids,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'posts_per_page' => -1
        ]);
    }
    if (!empty($anggota_ids)) {
        $results['anggota'] = get_posts([
            'post_type'      => ['anggota', 'alat-kelengkapan', 'tokoh-sejarah'],
            'post__in'       => $anggota_ids,
            'posts_per_page' => -1
        ]);

        // Urutkan Anggota: Ketua DPRD / Pimpinan paling atas
        usort($results['anggota'], function($a, $b) {
            $get_rank = function($post) {
                $title = $post->post_title;
                if (stripos($title, 'Bambang Irawan') !== false) return 1; // Ketua DPRD
                
                $positions = dprd_get_member_positions($post->ID);
                $pos_str = implode(' ', $positions);

                if (stripos($pos_str, 'Ketua DPRD') !== false) return 1;
                if (stripos($pos_str, 'Wakil Ketua') !== false) return 2;
                if (stripos($pos_str, 'Ketua') !== false) return 3;
                if (stripos($pos_str, 'Sekretaris') !== false) return 4;
                return 5;
            };

            $rank_a = $get_rank($a);
            $rank_b = $get_rank($b);

            if ($rank_a === $rank_b) {
                return strcmp($a->post_title, $b->post_title);
            }
            return $rank_a <=> $rank_b;
        });
    }
    if (!empty($dokumen_ids)) {
        $results['dokumen'] = get_posts([
            'post_type'      => ['sakip', 'ppid', 'propemperda'],
            'post__in'       => $dokumen_ids,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'posts_per_page' => -1
        ]);
    }
    
    return $results;
}

/**
 * Ambil daftar lengkap seluruh jabatan yang dimiliki oleh seorang anggota/pimpinan.
 */
function dprd_get_member_positions($post_id) {
    $positions = [];
    $post = get_post($post_id);
    if (!$post) return $positions;

    $post_title = $post->post_title;
    
    // 1. Cek Pimpinan DPRD
    if (strpos($post_title, 'Bambang Irawan') !== false) {
        $positions[] = 'Ketua DPRD Kabupaten Purbalingga';
    } elseif (strpos($post_title, 'Aris Widiarso') !== false) {
        $positions[] = 'Wakil Ketua I DPRD Kabupaten Purbalingga';
    } elseif (strpos($post_title, 'Aman Waliyudin') !== false) {
        $positions[] = 'Wakil Ketua II DPRD Kabupaten Purbalingga';
    } elseif (strpos($post_title, 'Tenny Juliawaty') !== false) {
        $positions[] = 'Wakil Ketua III DPRD Kabupaten Purbalingga';
    }

    // 2. Direct meta 'jabatan' pada post anggota
    $direct_jabatan = get_post_meta($post_id, 'jabatan', true);
    if (!empty($direct_jabatan) && !in_array($direct_jabatan, $positions)) {
        $positions[] = $direct_jabatan;
    }

    // 3. Pindai seluruh data CPT 'alat-kelengkapan' (Komisi, Fraksi, Bamus, Banggar, BK, Bapemperda)
    $ak_posts = get_posts([
        'post_type'      => 'alat-kelengkapan',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    ]);

    foreach ($ak_posts as $ak) {
        $ak_title = $ak->post_title;
        $json = get_post_meta($ak->ID, 'dprd_ak_struktur_json', true);
        if ($json) {
            $data = json_decode($json, true);
            if (is_array($data)) {
                $hierarki = $data['hierarki'] ?? [];
                foreach ($hierarki as $level) {
                    $members = $level['members'] ?? [];
                    foreach ($members as $m) {
                        $m_id = $m['anggota_id'] ?? 0;
                        $m_nama = $m['nama'] ?? '';
                        if ($m_id == $post_id || ($m_nama && strpos($post_title, $m_nama) !== false)) {
                            $j = $m['jabatan'] ?? 'Anggota';
                            $pos_name = $j . ' ' . $ak_title;
                            if (!in_array($pos_name, $positions)) {
                                $positions[] = $pos_name;
                            }
                        }
                    }
                }
            }
        }
    }

    // 4. Fallback jika belum ada data jabatan spesifik
    if (empty($positions)) {
        $positions[] = 'Anggota DPRD Kabupaten Purbalingga';
    }

    return $positions;
}
