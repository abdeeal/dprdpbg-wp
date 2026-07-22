<?php
/**
 * Template part untuk menampilkan Agenda & Transparansi Kinerja di Beranda
 */
if (!defined('ABSPATH')) exit;

// Ambil parameter halaman dari URL (untuk pagination Agenda)
$agenda_paged = isset($_GET['agenda_paged']) ? max(1, intval($_GET['agenda_paged'])) : 1;

// Tanggal hari ini (WP timezone lokal atau GMT+7)
$today = current_time('Y-m-d');

// Query Agenda (3 per halaman, mulai dari hari ini)
$agenda_query = new WP_Query([
    'post_type'      => 'agenda',
    'posts_per_page' => 3,
    'paged'          => $agenda_paged,
    'meta_key'       => 'tanggal',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [
        [
            'key'     => 'tanggal',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE'
        ]
    ]
]);

// Helper format bulan Indonesia
function dprd_get_indo_month($date_str) {
    if (empty($date_str)) return '';
    $parts = explode('-', $date_str);
    if (count($parts) < 3) return '';
    $month = (int)$parts[1];
    $months = [
        1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR',
        5 => 'MEI', 6 => 'JUN', 7 => 'JUL', 8 => 'AGS',
        9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'
    ];
    return isset($months[$month]) ? $months[$month] : '';
}
?>

<section id="agenda-section" class="bg-[#7D1A1D] text-white w-full py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.2fr] gap-12 lg:gap-16">
            
            <!-- Agenda Section -->
            <div class="flex flex-col">
                <div class="bg-ink p-5 flex justify-between items-center text-white">
                    <h3 class="font-mono font-bold text-sm sm:text-base">Agenda & Jadwal Sidang</h3>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="bg-white p-0 text-body h-full flex flex-col">
                    <div class="flex flex-col flex-grow">
                        <?php if ($agenda_query->have_posts()) : 
                            $count = $agenda_query->post_count;
                            $i = 0;
                            while ($agenda_query->have_posts()) : $agenda_query->the_post();
                                $tanggal = get_post_meta(get_the_ID(), 'tanggal', true);
                                $waktu = get_post_meta(get_the_ID(), 'waktu', true);
                                $day_num = !empty($tanggal) ? explode('-', $tanggal)[2] : '';
                                $month_name = dprd_get_indo_month($tanggal);
                                $i++;
                                ?>
                                <div class="flex flex-col px-6">
                                    <div class="flex py-6 max-w-7/8">
                                        <div class="flex flex-col items-center justify-start pr-6 shrink-0 w-[60px] sm:w-[70px]">
                                            <span class="font-mono text-[12px] font-bold text-body-secondary uppercase mb-0.5 tracking-wider"><?php echo esc_html($month_name); ?></span>
                                            <span class="font-mono text-[28px] sm:text-3xl text-primary font-bold leading-none"><?php echo esc_html($day_num); ?></span>
                                        </div>
                                        <div class="flex flex-col justify-start pt-0.5">
                                            <h4 class="font-sans font-bold text-sm sm:text-[16px] mb-1.5 leading-snug"><?php the_title(); ?></h4>
                                            <p class="font-sans text-[12px] text-body-secondary"><?php echo esc_html($waktu); ?></p>
                                        </div>
                                    </div>
                                    <?php if ($i !== $count) : ?>
                                        <div class="w-full h-px bg-line/60"></div>
                                    <?php endif; ?>
                                </div>
                            <?php 
                            endwhile;
                            wp_reset_postdata();
                        else : ?>
                            <div class="flex-grow flex flex-col items-center justify-center p-8 text-center min-h-[250px]">
                                <h4 class="font-sans font-bold text-body text-[15px] mb-1">Tidak Ada Agenda</h4>
                                <p class="font-sans text-[13px] text-body-secondary">Belum ada jadwal sidang atau kegiatan dalam waktu dekat.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php
                    $total_pages = $agenda_query->max_num_pages;
                    if ($total_pages > 1) :
                        $prev_url = $agenda_paged > 1 ? esc_url(add_query_arg('agenda_paged', $agenda_paged - 1) . '#agenda-section') : '';
                        $next_url = $agenda_paged < $total_pages ? esc_url(add_query_arg('agenda_paged', $agenda_paged + 1) . '#agenda-section') : '';
                    ?>
                    <div class="flex items-center justify-center gap-2 py-5 mt-auto border-t border-line/40">
                        <?php if ($prev_url) : ?>
                            <a href="<?php echo $prev_url; ?>" class="text-body-secondary hover:text-body transition-colors p-1" aria-label="Previous page">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                            </a>
                        <?php else : ?>
                            <span class="text-line p-1 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                            </span>
                        <?php endif; ?>
                        
                        <?php
                        $links = paginate_links([
                            'base'      => add_query_arg('agenda_paged', '%#%') . '#agenda-section',
                            'format'    => '',
                            'current'   => $agenda_paged,
                            'total'     => $total_pages,
                            'prev_next' => false,
                            'type'      => 'array',
                            'mid_size'  => 1
                        ]);
                        
                        if (is_array($links)) {
                            foreach ($links as $link) {
                                if (strpos($link, 'current') !== false) {
                                    $num = strip_tags($link);
                                    echo '<span class="text-body font-bold text-sm px-2 font-mono">' . $num . '</span>';
                                } elseif (strpos($link, 'dots') !== false) {
                                    echo '<span class="text-body-secondary text-sm px-1 font-mono">...</span>';
                                } else {
                                    preg_match('/href=[\'"]([^\'"]+)[\'"]/', $link, $matches);
                                    $url = !empty($matches[1]) ? $matches[1] : '#';
                                    $num = strip_tags($link);
                                    echo '<a href="' . esc_url($url) . '" class="text-body-secondary hover:text-body transition-colors text-sm px-2 font-mono font-medium">' . $num . '</a>';
                                }
                            }
                        }
                        ?>

                        <?php if ($next_url) : ?>
                            <a href="<?php echo $next_url; ?>" class="text-body-secondary hover:text-body transition-colors p-1" aria-label="Next page">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        <?php else : ?>
                            <span class="text-line p-1 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Transparansi Section -->
            <div class="flex flex-col">
                <h2 class="font-montserrat text-3xl font-bold mb-3">Transparansi & Kinerja</h2>
                <p class="font-sans text-white/90 text-sm sm:text-[15px] mb-8">
                    Wujud komitmen akuntabilitas publik DPRD Kabupaten Purbalingga.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 h-full">
                    <!-- Box 1 - Propemperda -->
                    <div class="bg-white p-6 text-body flex flex-col border-l-[6px] border-secondary">
                        <h4 class="font-mono font-bold text-sm sm:text-[15px] mb-6">Propemperda</h4>
                        <div class="flex flex-col gap-3 mb-6">
                            <?php
                            // Query propemperda dari database
                            $propemperda_query = new WP_Query([
                                'post_type'      => 'propemperda',
                                'posts_per_page' => 4, // Ambil 4 terbaru untuk di beranda
                                'meta_key'       => 'tahun',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC'
                            ]);

                            if ($propemperda_query->have_posts()) :
                                while ($propemperda_query->have_posts()) : $propemperda_query->the_post();
                                    $tahun = get_post_meta(get_the_ID(), 'tahun', true);
                                    if (!$tahun) continue;
                            ?>
                                    <a href="<?php echo esc_url(home_url('/propemperda?id=' . $tahun)); ?>" class="bg-[#F4F4F4] rounded-[6px] px-4 py-3 text-sm font-sans font-bold text-body hover:bg-line transition-colors cursor-pointer block">
                                        Tahun <?php echo esc_html($tahun); ?>
                                    </a>
                            <?php 
                                endwhile;
                                wp_reset_postdata();
                            else :
                            ?>
                                <p class="text-sm text-body-secondary font-sans italic">Belum ada data Propemperda.</p>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url(home_url('/propemperda')); ?>" class="mt-auto text-primary text-xs font-bold flex items-center hover:underline self-end">
                            Lihat Semua
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Box 2 - SAKIP -->
                    <div class="bg-white p-6 text-body flex flex-col border-l-[6px] border-accent">
                        <h4 class="font-mono font-bold text-sm sm:text-[15px] mb-6">SAKIP</h4>
                        <div class="flex flex-col gap-3 mb-6">
                            <?php
                            // Ambil term dari kategori-sakip
                            $sakip_terms = get_terms([
                                'taxonomy'   => 'kategori-sakip',
                                'hide_empty' => true, // hanya tampilkan yang ada isinya
                                'number'     => 4     // batasi 4 untuk beranda
                            ]);

                            if (!empty($sakip_terms) && !is_wp_error($sakip_terms)) :
                                foreach ($sakip_terms as $term) :
                            ?>
                                    <a href="<?php echo esc_url(home_url('/sakip?id=' . $term->slug)); ?>" class="bg-[#F4F4F4] rounded-[6px] px-4 py-3 text-sm font-sans font-bold text-body hover:bg-line transition-colors cursor-pointer flex justify-between items-center">
                                        <?php echo esc_html($term->name); ?>
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                            <?php 
                                endforeach;
                            else :
                            ?>
                                <p class="text-sm text-body-secondary font-sans italic">Belum ada data SAKIP.</p>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url(home_url('/sakip')); ?>" class="mt-auto text-primary text-xs font-bold flex items-center hover:underline self-end">
                            Lihat Semua
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
