<?php
/**
 * Template part for rendering single Alat Kelengkapan post content
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

/**
 * Flat members list resolved from a 2D hierarchical array
 */
function dprd_get_flat_members_from_hierarki($hierarki) {
    $members_list = [];
    if (!is_array($hierarki)) return [];
    
    foreach ($hierarki as $level_index => $level_data) {
        if (!isset($level_data['members']) || !is_array($level_data['members'])) continue;
        foreach ($level_data['members'] as $item) {
            $anggota_id = isset($item['anggota_id']) ? intval($item['anggota_id']) : 0;
            if (!$anggota_id) continue;
            
            $name = get_the_title($anggota_id);
            $position = isset($item['jabatan']) ? $item['jabatan'] : '';
            
            // Resolve image
            $foto_diri = get_post_meta($anggota_id, 'foto_diri', true);
            $image_url = '';
            if ($foto_diri) {
                $image_url = wp_get_attachment_image_url(intval($foto_diri), 'large');
            }
            
            // Fallback for default leaders if image is empty
            if (empty($image_url)) {
                $name_clean = trim(strtolower($name));
                if (strpos($name_clean, 'bambang irawan') !== false) {
                    $image_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/1.png';
                } elseif (strpos($name_clean, 'aris widiarso') !== false) {
                    $image_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/2.png';
                } elseif (strpos($name_clean, 'aman waliyudin') !== false) {
                    $image_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/3.png';
                } elseif (strpos($name_clean, 'tenny juliawaty') !== false) {
                    $image_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/4.png';
                } else {
                    $image_url = get_template_directory_uri() . '/assets/images/placeholder/avatar.png';
                }
            }
            
            $members_list[] = [
                'name'     => $name,
                'position' => $position,
                'image'    => $image_url,
                'level'    => $level_index
            ];
        }
    }
    return $members_list;
}

$post_id = get_the_ID();
$slug = get_post_field('post_name', $post_id);

// Resolve Breadcrumbs
$breadcrumbs = [
    ['label' => 'Beranda', 'href' => home_url('/')],
    ['label' => 'Profil DPRD', 'href' => '#'],
];

// Flat members list resolved from dprd_ak_struktur_json
$members_list = [];
$raw_json = get_post_meta($post_id, 'dprd_ak_struktur_json', true);
$ak_data = $raw_json ? json_decode($raw_json, true) : [];

$is_group = isset($ak_data['tipe']) && $ak_data['tipe'] === 'grup';
$title = get_the_title();
$subtitle = 'Periode 2024-2029';

// Custom parameters for layout
$dasar_title = 'Dasar Pembentukan';
$dasar_content = '';
$tugas_title = 'Tugas';
$tugas_list = [];
$custom_html = '';

// Check which page we are on
if ($slug === 'pimpinan-dprd') {
    // --- PIMPINAN DPRD PAGE ---
    $breadcrumbs[] = ['label' => 'Pimpinan DPRD'];
    $title = 'Pimpinan DPRD Kabupaten Purbalingga';
    
    // Query members
    $leaders_query = new WP_Query([
        'post_type'      => 'anggota',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'ID',
        'order'          => 'ASC'
    ]);
    
    $pimpinan_list = [];
    if ($leaders_query->have_posts()) {
        while ($leaders_query->have_posts()) {
            $leaders_query->the_post();
            $leader_id = get_the_ID();
            $l_name = get_the_title();
            $l_content = get_the_content();
            $l_position = ($l_name === 'H.R Bambang Irawan, S.H., S.Sos., M.M.') ? 'Ketua DPRD Kabupaten Purbalingga' : 'Wakil Ketua DPRD Kabupaten Purbalingga';
            
            $foto_diri = get_post_meta($leader_id, 'foto_diri', true);
            $l_image = '';
            if ($foto_diri) {
                $l_image = wp_get_attachment_image_url(intval($foto_diri), 'large');
            }
            if (empty($l_image)) {
                $name_clean = trim(strtolower($l_name));
                if (strpos($name_clean, 'bambang irawan') !== false) {
                    $l_image = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/1.png';
                } elseif (strpos($name_clean, 'aris widiarso') !== false) {
                    $l_image = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/2.png';
                } elseif (strpos($name_clean, 'aman waliyudin') !== false) {
                    $l_image = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/3.png';
                } elseif (strpos($name_clean, 'tenny juliawaty') !== false) {
                    $l_image = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/4.png';
                } else {
                    $l_image = get_template_directory_uri() . '/assets/images/placeholder/avatar.png';
                }
            }
            
            $pimpinan_list[] = [
                'name'        => $l_name,
                'position'    => $l_position,
                'description' => $l_content,
                'image'       => $l_image
            ];
        }
        wp_reset_postdata();
    }
    
    // Dasar Hukum & Tugas
    $dasar_penetapan = get_post_meta($post_id, 'dprd_pimpinan_dasar_penetapan', true);
    $note = get_post_meta($post_id, 'dprd_pimpinan_note', true);
    $tugas_raw = get_post_meta($post_id, 'dprd_pimpinan_tugas_json', true);
    $tugas_data = $tugas_raw ? json_decode($tugas_raw, true) : [];
    
    // Build Pimpinan Custom HTML
    ob_start();
    ?>
    <!-- Pimpinan Grid -->
    <div class="flex flex-col gap-10 md:gap-14 mt-12 mb-16 max-w-4xl mx-auto w-full">
        <?php foreach ($pimpinan_list as $p) : ?>
            <div class="flex flex-col md:flex-row gap-8 md:gap-10 items-start mb-12 last:mb-0">
                <div class="relative w-full md:w-[220px] shrink-0 aspect-[3/4] rounded-md overflow-hidden bg-surface border border-line/10">
                    <?php if ($p['image']) : ?>
                        <img src="<?php echo esc_url($p['image']); ?>" alt="<?php echo esc_attr($p['name']); ?>" class="object-cover w-full h-full">
                    <?php endif; ?>
                </div>
                <div class="flex flex-col flex-grow pt-2">
                    <h3 class="font-display text-[26px] md:text-[28px] font-bold text-body mb-1 leading-tight">
                        <?php echo esc_html($p['name']); ?>
                    </h3>
                    <span class="font-sans text-[13px] md:text-sm text-body-secondary mb-4 block">
                        <?php echo esc_html($p['position']); ?>
                    </span>
                    <p class="font-sans text-[15px] md:text-[16px] text-body leading-[1.8]">
                        <?php echo esc_html($p['description']); ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Dasar Hukum -->
    <?php if ($dasar_penetapan) : ?>
        <div class="mt-16 mb-12 max-w-4xl mx-auto w-full">
            <h2 class="font-display font-bold text-[22px] md:text-[28px] text-body mb-5">
                Dasar Penetapan Pimpinan DPRD
            </h2>
            <p class="font-sans text-[15px] md:text-base text-body leading-[1.8] mb-4">
                <?php echo esc_html($dasar_penetapan); ?>
            </p>
            <?php if ($note) : ?>
                <p class="font-sans text-[15px] md:text-[16px] text-body-secondary leading-relaxed italic">
                    <?php echo esc_html($note); ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tugas Pimpinan -->
    <?php if (!empty($tugas_data)) : ?>
        <div class="mt-16 max-w-4xl mx-auto w-full">
            <h2 class="font-display font-bold text-[22px] md:text-[28px] text-body mb-8">
                Tugas Pimpinan DPRD
            </h2>
            <div class="flex flex-col">
                <?php foreach ($tugas_data as $idx => $t) : 
                    $is_last = ($idx === count($tugas_data) - 1);
                    $icon = isset($t['icon']) ? $t['icon'] : 'gavel';
                    ?>
                    <div class="flex flex-col gap-4 <?php echo $is_last ? '' : 'border-b border-line/80 pb-8 mb-8'; ?>">
                        <div class="flex flex-col items-start gap-4">
                            <div class="bg-primary-light text-primary p-2.5 rounded-md shrink-0">
                                <?php echo dprd_get_lucide_svg($icon, 20); ?>
                            </div>
                            <div class="w-full">
                                <h3 class="font-sans font-bold text-[17px] md:text-lg text-body mb-4">
                                    <?php echo esc_html($t['kategori']); ?>
                                </h3>
                                <ul class="flex flex-col gap-2.5">
                                    <?php 
                                    $points = is_array($t['poin']) ? $t['poin'] : json_decode($t['poin'], true);
                                    if (is_array($points)) :
                                        foreach ($points as $pt) : ?>
                                            <li class="flex gap-3 items-start">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2" />
                                                <span class="font-sans text-[14px] md:text-[15px] text-body-secondary leading-relaxed">
                                                    <?php echo esc_html($pt); ?>
                                                </span>
                                            </li>
                                        <?php endforeach;
                                    endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php
    $custom_html = ob_get_clean();

} elseif ($slug === 'badan-musyawarah' || $slug === 'badan-anggaran' || $slug === 'badan-pembentukan-peraturan-daerah' || $slug === 'badan-kehormatan') {
    // --- STANDALONE BOARD PAGES ---
    $breadcrumbs[] = ['label' => get_the_title()];
    $members_list = dprd_get_flat_members_from_hierarki(isset($ak_data['hierarki']) ? $ak_data['hierarki'] : []);
    
    if ($slug === 'badan-kehormatan') {
        // --- BADAN KEHORMATAN SPECIFIC ---
        $dasar_penetapan = get_post_meta($post_id, 'dprd_bk_dasar_pembentukan', true);
        $jumlah_anggota = get_post_meta($post_id, 'dprd_bk_jumlah_anggota', true) ?: '5 Orang';
        $jumlah_anggota_desc = get_post_meta($post_id, 'dprd_bk_jumlah_anggota_desc', true) ?: "JUMLAH ANGGOTA\nDipilih dari dan oleh anggota DPRD";
        $masa_tugas = get_post_meta($post_id, 'dprd_bk_masa_tugas', true) ?: '2,5 Tahun';
        $masa_tugas_desc = get_post_meta($post_id, 'dprd_bk_masa_tugas_desc', true) ?: 'MASA TUGAS MAKSIMAL';
        
        $sanksi_raw = get_post_meta($post_id, 'dprd_bk_sanksi_json', true);
        $sanksi_data = $sanksi_raw ? json_decode($sanksi_raw, true) : [];
        
        // Build dasar pembentukan content with stats
        ob_start();
        ?>
        <div class="flex flex-col w-full">
            <p class="font-sans text-[15px] md:text-base text-body leading-[1.8] mb-8">
                <?php echo esc_html($dasar_penetapan); ?>
            </p>
            
            <!-- Red Banner Stats -->
            <div class="bg-primary text-main flex flex-col md:flex-row p-8 md:p-12 gap-10 md:gap-0 rounded-sm shadow-sm">
                <div class="flex flex-col md:w-3/5">
                    <h3 class="font-mono text-2xl md:text-3xl font-bold mb-3"><?php echo esc_html($jumlah_anggota); ?></h3>
                    <span class="font-sans text-xs md:text-sm font-bold tracking-widest uppercase mb-1.5 opacity-90">
                        JUMLAH ANGGOTA
                    </span>
                    <span class="font-sans text-[16px] opacity-75 whitespace-pre-line">
                        <?php echo esc_html(str_replace("JUMLAH ANGGOTA\n", "", $jumlah_anggota_desc)); ?>
                    </span>
                </div>
                <div class="flex flex-col">
                    <h3 class="font-mono text-2xl md:text-3xl font-bold mb-3"><?php echo esc_html($masa_tugas); ?></h3>
                    <span class="font-sans text-xs md:text-sm font-bold tracking-widest uppercase mb-1.5 opacity-90">
                        <?php echo esc_html($masa_tugas_desc); ?>
                    </span>
                </div>
            </div>
        </div>
        <?php
        $dasar_content = ob_get_clean();
        
        // Build BK Sanksi HTML (instead of tasks list)
        if (!empty($sanksi_data)) {
            ob_start();
            ?>
            <div class="mt-16 max-w-4xl mx-auto w-full">
                <h2 class="font-display font-bold text-[22px] md:text-[32px] text-body mb-8">
                    Jenis Sanksi yang Dapat Dijatuhkan
                </h2>
                <div class="flex flex-col gap-4">
                    <?php foreach ($sanksi_data as $s) :
                        $severe = (strpos(strtolower($s['sanksi']), 'pemberhentian') !== false);
                        ?>
                        <div class="p-6 border-l-4 <?php echo $severe ? 'border-primary bg-primary-light/50' : 'border-gray-600/20 border-b'; ?> flex items-center transition-all">
                            <div class="flex flex-col">
                                <span class="font-sans font-semibold text-base md:text-base <?php echo $severe ? 'text-primary' : 'text-body'; ?>">
                                    <?php echo esc_html($s['sanksi']); ?>
                                </span>
                                <?php if (!empty($s['keterangan'])) : ?>
                                    <span class="font-sans text-sm mt-1.5 <?php echo $severe ? 'text-primary/70' : 'text-body-secondary'; ?>">
                                        <?php echo esc_html($s['keterangan']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $custom_html = ob_get_clean();
        }
    } else {
        // --- BAMUS, BANGGAR, BAPEMPERDA ---
        $dasar_content = get_post_meta($post_id, 'dprd_badan_dasar_pembentukan', true);
        $tugas_raw = get_post_meta($post_id, 'dprd_badan_tugas_json', true);
        $tugas_data = $tugas_raw ? json_decode($tugas_raw, true) : [];
        
        $tugas_list = [];
        if (!empty($tugas_data)) {
            foreach ($tugas_data as $t) {
                $icon = isset($t['icon']) ? $t['icon'] : 'calendar';
                $points = is_array($t['poin']) ? $t['poin'] : json_decode($t['poin'], true);
                $tugas_list[] = [
                    'icon'  => $icon,
                    'title' => $t['kategori'],
                    'items' => is_array($points) ? $points : []
                ];
            }
        }
    }

} elseif ($slug === 'komisi') {
    // --- GROUP MEMBER "KOMISI" ---
    $komisi_num = get_query_var('komisi_num');
    
    if (empty($komisi_num)) {
        // Render Komisi Index (Directory)
        $breadcrumbs[] = ['label' => 'Komisi'];
        $title = 'Komisi';
        $children_links = [
            ['title' => 'Komisi I', 'href' => home_url('/profil-dprd/komisi/1/')],
            ['title' => 'Komisi II', 'href' => home_url('/profil-dprd/komisi/2/')],
            ['title' => 'Komisi III', 'href' => home_url('/profil-dprd/komisi/3/')],
            ['title' => 'Komisi IV', 'href' => home_url('/profil-dprd/komisi/4/')],
        ];
        
        ob_start();
        ?>
        <!-- List Links -->
        <div class="w-full flex flex-col border-t border-[#A32B2E]/40 mt-12">
            <?php foreach ($children_links as $child) : ?>
              <div class="border-b border-[#A32B2E]/40 last:border-b-0 py-6 md:py-8">
                <a href="<?php echo esc_url($child['href']); ?>" class="w-full flex items-start justify-between text-left group cursor-pointer">
                  <div class="flex flex-col gap-1.5">
                    <h3 class="font-display text-xl md:text-[22px] text-body group-hover:text-primary transition-colors">
                      <?php echo esc_html($child['title']); ?>
                    </h3>
                  </div>
                  <div class="text-primary shrink-0 ml-4 pt-1 opacity-0 group-hover:opacity-100 transition-all duration-300 md:opacity-100 group-hover:rotate-45">
                    <?php echo dprd_get_lucide_svg('arrow-up-right', 24); ?>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
        </div>
        <?php
        $custom_html = ob_get_clean();
    } else {
        $slug_num_map = [
            '1' => 'Komisi I',
            '2' => 'Komisi II',
            '3' => 'Komisi III',
            '4' => 'Komisi IV'
        ];
        $c_title = isset($slug_num_map[$komisi_num]) ? $slug_num_map[$komisi_num] : 'Komisi I';
        
        $breadcrumbs[] = ['label' => 'Komisi', 'href' => home_url('/profil-dprd/komisi/')];
        $breadcrumbs[] = ['label' => $c_title];
        
        $title = $c_title;
        
        // Find target child
        $target_child = null;
        $children = isset($ak_data['children']) ? $ak_data['children'] : [];
        foreach ($children as $c) {
            if ($c['nama'] === $c_title) {
                $target_child = $c;
                break;
            }
        }
        
        if ($target_child) {
            $members_list = dprd_get_flat_members_from_hierarki(isset($target_child['hierarki']) ? $target_child['hierarki'] : []);
            $mitra_kerja = isset($target_child['mitra_kerja']) ? $target_child['mitra_kerja'] : [];
            
            // Build Mitra Kerja Columnized Layout HTML
            if (!empty($mitra_kerja)) {
                $half = ceil(count($mitra_kerja) / 2);
                $left_col = array_slice($mitra_kerja, 0, $half);
                $right_col = array_slice($mitra_kerja, $half);
                
                ob_start();
                ?>
                <div class="mt-16 max-w-4xl mx-auto w-full">
                    <h2 class="font-display font-bold text-[22px] md:text-2xl text-body mb-8">
                        Mitra Kerja
                    </h2>
                    <div class="flex flex-col md:flex-row">
                        <!-- Left Column -->
                        <div class="flex-1 flex flex-col">
                            <?php foreach ($left_col as $idx => $mitra) : 
                                $num = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                                ?>
                                <div class="flex items-start border border-line/50 -mt-[1px]">
                                    <div class="px-4 py-3 md:px-5 md:py-4 border-r border-line/50 text-body-secondary/60 font-mono text-sm md:text-[15px] shrink-0">
                                        <?php echo esc_html($num); ?>
                                    </div>
                                    <div class="px-4 py-3 md:px-5 md:py-4 font-sans text-[14px] md:text-[15px] text-body">
                                        <?php echo esc_html($mitra); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Right Column -->
                        <div class="flex-1 flex flex-col md:-ml-[1px] -mt-[1px] md:mt-0">
                            <?php foreach ($right_col as $idx => $mitra) : 
                                $num = str_pad($idx + 1 + $half, 2, '0', STR_PAD_LEFT);
                                ?>
                                <div class="flex items-start border border-line/50 -mt-[1px]">
                                    <div class="px-4 py-3 md:px-5 md:py-4 border-r border-line/50 text-body-secondary/60 font-mono text-sm md:text-[15px] shrink-0">
                                        <?php echo esc_html($num); ?>
                                    </div>
                                    <div class="px-4 py-3 md:px-5 md:py-4 font-sans text-[14px] md:text-[15px] text-body">
                                        <?php echo esc_html($mitra); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php
                $custom_html = ob_get_clean();
            }
        }
    }

} elseif ($slug === 'fraksi') {
    // --- GROUP MEMBER "FRAKSI" ---
    $fraksi_slug = get_query_var('fraksi_slug');
    
    if (empty($fraksi_slug)) {
        // Render Fraksi Index (Directory)
        $breadcrumbs[] = ['label' => 'Fraksi'];
        $title = 'Fraksi';
        $children_links = [
            ['title' => 'Fraksi PDI Perjuangan', 'href' => home_url('/profil-dprd/fraksi/pdi-perjuangan/')],
            ['title' => 'Fraksi Partai Golongan Karya', 'href' => home_url('/profil-dprd/fraksi/golkar/')],
            ['title' => 'Fraksi Partai Gerindra', 'href' => home_url('/profil-dprd/fraksi/gerindra/')],
            ['title' => 'Fraksi PKB', 'href' => home_url('/profil-dprd/fraksi/pkb/')],
            ['title' => 'Fraksi PKS', 'href' => home_url('/profil-dprd/fraksi/pks/')],
            ['title' => 'Fraksi Amanat Demokrat (PAN)', 'href' => home_url('/profil-dprd/fraksi/pan/')],
        ];
        
        ob_start();
        ?>
        <!-- List Links -->
        <div class="w-full flex flex-col border-t border-[#A32B2E]/40 mt-12">
            <?php foreach ($children_links as $child) : ?>
              <div class="border-b border-[#A32B2E]/40 last:border-b-0 py-6 md:py-8">
                <a href="<?php echo esc_url($child['href']); ?>" class="w-full flex items-start justify-between text-left group cursor-pointer">
                  <div class="flex flex-col gap-1.5">
                    <h3 class="font-display text-xl md:text-[22px] text-body group-hover:text-primary transition-colors">
                      <?php echo esc_html($child['title']); ?>
                    </h3>
                  </div>
                  <div class="text-primary shrink-0 ml-4 pt-1 opacity-0 group-hover:opacity-100 transition-all duration-300 md:opacity-100 group-hover:rotate-45">
                    <?php echo dprd_get_lucide_svg('arrow-up-right', 24); ?>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
        </div>
        <?php
        $custom_html = ob_get_clean();
    } else {
        $slug_name_map = [
            'pdi-perjuangan' => 'Fraksi PDI Perjuangan',
            'golkar'         => 'Fraksi Partai Golkar',
            'gerindra'       => 'Fraksi Partai Gerindra',
            'pkb'            => 'Fraksi PKB',
            'pks'            => 'Fraksi PKS',
            'pan'            => 'Fraksi PAN'
        ];
        $c_title = isset($slug_name_map[$fraksi_slug]) ? $slug_name_map[$fraksi_slug] : 'Fraksi PDI Perjuangan';
        
        $breadcrumbs[] = ['label' => 'Fraksi', 'href' => home_url('/profil-dprd/fraksi/')];
        $breadcrumbs[] = ['label' => $c_title];
        
        $title = $c_title;
        
        // Find target child
        $target_child = null;
        $children = isset($ak_data['children']) ? $ak_data['children'] : [];
        foreach ($children as $c) {
            if ($c['nama'] === $c_title) {
                $target_child = $c;
                break;
            }
        }
        
        if ($target_child) {
            $members_list = dprd_get_flat_members_from_hierarki(isset($target_child['hierarki']) ? $target_child['hierarki'] : []);
        }
    }
}
?>

<!-- Breadcrumbs -->
<div class="mb-6 md:mb-8">
    <div class="flex items-center gap-1.5 flex-wrap font-sans text-xs md:text-sm text-body-secondary font-medium">
        <?php foreach ($breadcrumbs as $i => $bc) : 
            $is_last = ($i === count($breadcrumbs) - 1);
            ?>
            <?php if (!$is_last && isset($bc['href'])) : ?>
                <a href="<?php echo esc_url($bc['href']); ?>" class="hover:text-primary transition-colors"><?php echo esc_html($bc['label']); ?></a>
                <span class="text-body-secondary/60 text-xs mt-[1px] font-normal mx-0.5">›</span>
            <?php else : ?>
                <span class="text-body font-semibold"><?php echo esc_html($bc['label']); ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Page Header -->
<div class="mb-10 max-w-4xl mx-auto w-full">
    <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2 leading-tight">
        <?php echo esc_html($title); ?>
    </h1>
    <?php if ($subtitle) : ?>
        <p class="font-mono text-xs md:text-sm text-body-secondary tracking-widest uppercase">
            <?php echo esc_html($subtitle); ?>
        </p>
    <?php endif; ?>
</div>

<!-- Members Grid Section -->
<?php if (!empty($members_list)) : 
    // Group members by level
    $groups = [];
    foreach ($members_list as $m) {
        $lvl = $m['level'];
        if (!isset($groups[$lvl])) {
            $groups[$lvl] = [];
        }
        $groups[$lvl][] = $m;
    }
    ksort($groups);
    ?>
    <div class="flex flex-col mt-12 mb-16 max-w-4xl mx-auto w-full">
        <?php 
        $sorted_levels = array_keys($groups);
        foreach ($sorted_levels as $index => $level) :
            $lvl_members = $groups[$level];
            $is_last_lvl = ($index === count($sorted_levels) - 1);
            ?>
            <div class="flex flex-col w-full">
                <!-- Grid Container -->
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-10 sm:gap-x-8 md:gap-x-12 w-full">
                    <?php foreach ($lvl_members as $member) : ?>
                        <div class="w-full sm:w-[calc(50%-1.5rem)] md:w-[calc(33.333%-2.5rem)] max-w-[280px] flex justify-center">
                            <div class="flex flex-col items-center w-full">
                                <h3 class="font-display text-lg md:text-[19px] font-bold text-body text-center mb-3 leading-snug">
                                    <?php echo esc_html($member['name']); ?>
                                </h3>
                                
                                <!-- Divider line -->
                                <div class="w-full h-px bg-body/30 mb-3" />
                                
                                <span class="font-sans text-sm md:text-[15px] font-semibold text-primary mb-4 text-center">
                                    <?php echo esc_html($member['position']); ?>
                                </span>
                                
                                <div class="relative w-full aspect-[3/4] rounded-sm overflow-hidden bg-surface shadow-sm border border-line/40">
                                    <?php if ($member['image']) : ?>
                                        <img src="<?php echo esc_url($member['image']); ?>" alt="<?php echo esc_attr($member['name']); ?>" class="object-cover w-full h-full">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Subtle separator line between levels -->
                <?php if (!$is_last_lvl) : ?>
                    <div class="w-full flex justify-center my-12">
                        <div class="w-3/4 max-w-2xl h-px bg-line/60" />
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Dasar Pembentukan Content -->
<?php if (!empty($dasar_content)) : ?>
    <div class="mb-12 max-w-4xl mx-auto w-full">
        <h2 class="font-display font-bold text-[22px] md:text-[32px] text-body mb-4">
            <?php echo esc_html($dasar_title); ?>
        </h2>
        <?php if (is_string($dasar_content)) : ?>
            <p class="font-sans text-[15px] md:text-base text-body leading-[1.8] mb-4">
                <?php echo esc_html($dasar_content); ?>
            </p>
        <?php else : ?>
            <?php echo $dasar_content; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Tugas Section -->
<?php if (!empty($tugas_list)) : ?>
    <div class="mt-16 max-w-4xl mx-auto w-full">
        <h2 class="font-display font-bold text-[22px] md:text-2xl text-body mb-8">
            <?php echo esc_html($tugas_title); ?>
        </h2>
        
        <div class="flex flex-col">
            <?php foreach ($tugas_list as $idx => $tugas) : 
                $is_last = ($idx === count($tugas_list) - 1);
                ?>
                <div class="flex flex-col gap-4 <?php echo $is_last ? '' : 'border-b border-[#82111A] pb-8 mb-8'; ?>">
                    <div class="flex flex-col items-start gap-4">
                        <div class="bg-primary-light text-primary p-3 rounded-md shrink-0">
                            <?php echo dprd_get_lucide_svg($tugas['icon'], 24); ?>
                        </div>
                        
                        <div class="w-full">
                            <h3 class="font-sans font-bold text-lg md:text-xl text-body mb-4">
                                <?php echo esc_html($tugas['title']); ?>
                            </h3>
                            <ul class="flex flex-col gap-2">
                                <?php foreach ($tugas['items'] as $item) : ?>
                                    <li class="flex gap-3 items-start">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2" />
                                        <span class="font-sans text-[14px] md:text-[16px] text-body-secondary leading-relaxed">
                                            <?php echo esc_html($item); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Custom Content / Children (BK Sanksi or Mitra Kerja Grid) -->
<?php if (!empty($custom_html)) : ?>
    <?php echo $custom_html; ?>
<?php endif; ?>
