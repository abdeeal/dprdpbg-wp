<?php
/**
 * Search Results Section
 */

$query = isset($_GET['q']) ? sanitize_text_field(stripslashes($_GET['q'])) : '';
$results = dprd_search_all($query);

$berita_items = $results['berita'] ?? [];
$galeri_items = $results['galeri'] ?? [];
$anggota_items = $results['anggota'] ?? [];
$dokumen_items = $results['dokumen'] ?? [];

$has_berita = !empty($berita_items);
$has_galeri = !empty($galeri_items);
$has_anggota = !empty($anggota_items);
$has_dokumen = !empty($dokumen_items);

$has_any = $has_berita || $has_galeri || $has_anggota || $has_dokumen;
?>
<section class="w-full bg-main py-12 md:py-16">
    <?php if (!$has_any): ?>
        <div id="dprd-results-empty" class="flex flex-col items-center justify-center text-center py-20">
            <div class="w-16 h-16 rounded-full bg-line/30 flex items-center justify-center mb-6 text-body-secondary">
                <?php dprd_icon('search', 'w-8 h-8'); ?>
            </div>
            <h3 class="font-display text-2xl md:text-3xl text-body mb-3">
                Tidak ada hasil ditemukan
            </h3>
            <p class="font-sans text-body-secondary max-w-md mx-auto">
                <?php if ($query): ?>
                    Kami tidak menemukan hasil yang cocok dengan kata kunci "<strong><?php echo esc_html($query); ?></strong>". Coba gunakan kata kunci lain.
                <?php else: ?>
                    Silakan masukkan kata kunci pencarian pada kotak di atas.
                <?php endif; ?>
            </p>
        </div>
        <div id="dprd-results-container" class="flex-col" style="display: none;"></div>
    <?php else: ?>
        <div id="dprd-results-empty" class="flex-col items-center justify-center text-center py-20" style="display: none;">
            <div class="w-16 h-16 rounded-full bg-line/30 flex items-center justify-center mb-6 text-body-secondary">
                <?php dprd_icon('search', 'w-8 h-8'); ?>
            </div>
            <h3 class="font-display text-2xl md:text-3xl text-body mb-3">
                Tidak ada hasil ditemukan
            </h3>
            <p class="font-sans text-body-secondary max-w-md mx-auto">
                Silakan ubah filter pencarian Anda.
            </p>
        </div>

        <div id="dprd-results-container" class="flex flex-col">
            
            <!-- BERITA -->
            <?php if ($has_berita): ?>
            <div id="dprd-results-berita" class="w-full">
                <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-body-secondary mb-6">
                    Berita
                </h3>
                <div class="flex flex-col">
                    <?php foreach ($berita_items as $index => $item): 
                        $thumb_url = get_the_post_thumbnail_url($item->ID, 'medium') ?: get_template_directory_uri() . '/assets/images/placeholder.jpg';
                        $permalink = get_permalink($item->ID);
                        $date = get_the_time('d F Y', $item->ID);
                    ?>
                    <div class="flex flex-col sm:flex-row gap-6 py-6 <?php echo ($index !== count($berita_items) - 1) ? 'border-b border-line' : ''; ?>">
                        <div class="relative w-full sm:w-40 md:w-48 aspect-[4/3] rounded-card overflow-hidden shrink-0 bg-line/30">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($item->post_title); ?>" class="absolute inset-0 w-full h-full object-cover" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <a href="<?php echo esc_url($permalink); ?>" class="group">
                                <h4 class="font-display text-base sm:text-xl md:text-2xl text-body group-hover:text-primary transition-colors mb-2">
                                    <?php echo esc_html($item->post_title); ?>
                                </h4>
                            </a>
                            <span class="font-mono text-xs text-body-secondary">
                                <?php echo esc_html($date); ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="dprd-divider-berita" class="dprd-results-divider border-t border-line my-12" style="display: none;"></div>
            </div>
            <?php endif; ?>

            <!-- GALERI -->
            <?php if ($has_galeri): ?>
            <div id="dprd-results-galeri" class="w-full">
                <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-body-secondary mb-6">
                    Galeri
                </h3>
                <div class="flex flex-col">
                    <?php foreach ($galeri_items as $index => $item): 
                        $image_id = get_post_meta($item->ID, 'image_id', true);
                        $thumb_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_template_directory_uri() . '/assets/images/placeholder.jpg';
                        
                        $terms = get_the_terms($item->ID, 'kategori-galeri');
                        $cat_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Galeri';
                        $permalink = get_permalink($item->ID);
                    ?>
                    <a href="<?php echo esc_url($permalink); ?>" class="flex flex-col sm:flex-row gap-6 py-6 group cursor-pointer <?php echo ($index !== count($galeri_items) - 1) ? 'border-b border-line' : ''; ?>">
                        <div class="relative w-full sm:w-40 md:w-48 aspect-[4/3] rounded-card overflow-hidden shrink-0 bg-line/30">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($item->post_title); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="font-display text-base sm:text-xl md:text-2xl text-body group-hover:text-primary transition-colors mb-2">
                                <?php echo esc_html($item->post_title); ?>
                            </p>
                            <span class="font-mono text-xs text-body-secondary">
                                <?php echo esc_html($cat_name); ?>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <div id="dprd-divider-galeri" class="dprd-results-divider border-t border-line my-12" style="display: none;"></div>
            </div>
            <?php endif; ?>

            <!-- ANGGOTA & ORGANISASI -->
            <?php if ($has_anggota): ?>
            <div id="dprd-results-anggota" class="w-full">
                <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-body-secondary mb-6">
                    Anggota & Organisasi
                </h3>
                <div class="flex flex-col">
                    <?php foreach ($anggota_items as $index => $item): 
                        $permalink = get_permalink($item->ID);
                        $is_alat_kelengkapan = ($item->post_type === 'alat-kelengkapan');
                        $is_tokoh_sejarah = ($item->post_type === 'tokoh-sejarah');
                        
                        $thumb_url = get_the_post_thumbnail_url($item->ID, 'medium');
                        if (!$thumb_url) {
                            $foto_diri = get_post_meta($item->ID, 'foto_diri', true);
                            if (!empty($foto_diri)) {
                                $thumb_url = is_numeric($foto_diri) ? wp_get_attachment_image_url($foto_diri, 'medium') : $foto_diri;
                            }
                        }
                        if (!$thumb_url && !$is_alat_kelengkapan) {
                            $name_check = $item->post_title;
                            if (stripos($name_check, 'bambang') !== false) {
                                $thumb_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/1.png';
                            } elseif (stripos($name_check, 'aris') !== false) {
                                $thumb_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/2.png';
                            } elseif (stripos($name_check, 'aman') !== false) {
                                $thumb_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/3.png';
                            } elseif (stripos($name_check, 'tenny') !== false) {
                                $thumb_url = get_template_directory_uri() . '/assets/images/profil-dprd/pimpinan-dprd/4.png';
                            } else {
                                $thumb_url = get_template_directory_uri() . '/assets/images/placeholder/avatar.png';
                            }
                        }

                        if ($is_alat_kelengkapan) {
                            $member_positions = ['Alat Kelengkapan DPRD'];
                        } elseif ($is_tokoh_sejarah) {
                            $member_positions = ['Tokoh Sejarah Purbalingga'];
                        } else {
                            $member_positions = dprd_get_member_positions($item->ID);
                        }
                    ?>
                    <a href="<?php echo esc_url($permalink); ?>" class="flex items-center gap-6 py-5 <?php echo ($index !== count($anggota_items) - 1) ? 'border-b border-line' : ''; ?> group cursor-pointer">
                        <div class="relative w-28 sm:w-36 h-36 sm:h-48 rounded-card overflow-hidden shrink-0 bg-line/20 flex items-center justify-center" style="aspect-ratio: 3/4;">
                            <?php if ($thumb_url): ?>
                                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($item->post_title); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <?php else: ?>
                                <div class="w-full h-full bg-primary/5 flex items-center justify-center text-primary/40 group-hover:text-primary transition-colors">
                                    <?php dprd_icon('user', 'w-10 h-10'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col justify-center">
                            <h4 class="font-display font-medium text-lg sm:text-xl text-body group-hover:text-primary transition-colors mb-2">
                                <?php echo esc_html($item->post_title); ?>
                            </h4>
                            <div class="flex flex-col gap-1.5">
                                <?php foreach ($member_positions as $pos): ?>
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary/60 shrink-0"></span>
                                        <span class="font-mono text-xs sm:text-sm text-body-secondary font-medium">
                                            <?php echo esc_html($pos); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <div id="dprd-divider-anggota" class="dprd-results-divider border-t border-line my-12" style="display: none;"></div>
            </div>
            <?php endif; ?>

            <!-- DOKUMEN -->
            <?php if ($has_dokumen): ?>
            <div id="dprd-results-dokumen" class="w-full">
                <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-body-secondary mb-6">
                    Dokumen & Arsip
                </h3>
                <div class="flex flex-col gap-4">
                    <?php foreach ($dokumen_items as $index => $item): 
                        $permalink = get_permalink($item->ID);
                        
                        $type_label = 'Dokumen';
                        if ($item->post_type === 'sakip') $type_label = 'SAKIP';
                        if ($item->post_type === 'ppid') $type_label = 'PPID';
                        if ($item->post_type === 'propemperda') $type_label = 'Propemperda';
                    ?>
                    <a href="<?php echo esc_url($permalink); ?>" class="flex items-start gap-4 p-5 bg-white border border-line rounded-card hover:border-primary/50 hover:shadow-sm transition-all group">
                        <div class="w-10 h-10 rounded-full bg-main flex items-center justify-center text-body-secondary shrink-0 group-hover:text-primary transition-colors">
                            <?php dprd_icon('file-text', 'w-5 h-5'); ?>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-sans font-medium text-body text-base group-hover:text-primary transition-colors">
                                <?php echo esc_html($item->post_title); ?>
                            </h4>
                            <span class="font-mono text-xs text-body-secondary mt-1.5 flex items-center gap-2">
                                <span class="bg-line/50 px-2 py-0.5 rounded text-[10px] uppercase"><?php echo esc_html($type_label); ?></span>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <div id="dprd-divider-dokumen" class="dprd-results-divider border-t border-line my-12" style="display: none;"></div>
            </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</section>
