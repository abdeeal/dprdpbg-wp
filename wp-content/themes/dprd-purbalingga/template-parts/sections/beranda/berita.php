<?php
/**
 * Template part untuk menampilkan Berita Terkini di Beranda
 */
if (!defined('ABSPATH')) exit;

// 1. Query Berita Utama (Featured)
$featured_query = new WP_Query([
    'post_type'      => 'berita',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [
        [
            'key'     => 'isFeatured',
            'value'   => '1',
            'compare' => '='
        ]
    ]
]);

$featured_post = null;
if ($featured_query->have_posts()) {
    $featured_query->the_post();
    $featured_post = get_post();
    wp_reset_postdata();
} else {
    // Jika tidak ada yang di-mark Featured, ambil yang paling baru saja
    $backup_query = new WP_Query([
        'post_type'      => 'berita',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    if ($backup_query->have_posts()) {
        $backup_query->the_post();
        $featured_post = get_post();
        wp_reset_postdata();
    }
}

// 2. Query 4 Berita Terbaru (Mengecualikan Berita Utama di atas)
$recent_args = [
    'post_type'      => 'berita',
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if ($featured_post) {
    $recent_args['post__not_in'] = [$featured_post->ID];
}
$recent_query = new WP_Query($recent_args);
?>

<section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="font-montserrat text-2xl sm:text-3xl font-bold text-body mb-2">Berita Terkini</h2>
            <p class="font-sans text-body-secondary text-sm">Informasi terbaru seputar kegiatan dan kebijakan legislatif di Purbalingga.</p>
        </div>
        <a href="<?php echo esc_url(home_url('/berita')); ?>" class="hidden sm:flex items-center text-primary font-mono font-semibold text-sm hover:underline">
            Lihat Semua Berita
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Featured News -->
        <?php if ($featured_post) : 
            $post_id = $featured_post->ID;
            $day_meta = get_post_meta($post_id, 'day', true);
            if (empty($day_meta)) {
                $day_meta = get_the_date('l, d M Y', $post_id);
            }
            $img_url = get_the_post_thumbnail_url($post_id, 'large');
            if (empty($img_url)) {
                $img_url = get_template_directory_uri() . '/assets/images/default-berita.jpg'; // fallback
            }
            $news_url = get_permalink($post_id);
            
            $excerpt = dprd_get_auto_excerpt($featured_post);
            ?>
            <a href="<?php echo esc_url($news_url); ?>" class="lg:col-span-7 group cursor-pointer block">
                <div class="relative w-full aspect-[16/10] overflow-hidden mb-4 rounded-card">
                    <img 
                        src="<?php echo esc_url($img_url); ?>"
                        alt="<?php echo esc_attr($featured_post->post_title); ?>"
                        class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                        loading="lazy"
                    />
                </div>
                <div class="flex gap-4 items-center text-xs sm:text-sm text-body-secondary font-mono mb-3 ">
                    <span><?php echo esc_html($day_meta); ?></span>
                </div>
                <h3 class="font-display text-2xl sm:text-2xl text-body group-hover:text-primary transition-colors mb-3 leading-snug">
                    <?php echo esc_html($featured_post->post_title); ?>
                </h3>
                <p class="font-sans text-sm sm:text-base text-body-secondary line-clamp-2">
                    <?php echo esc_html(wp_strip_all_tags($excerpt)); ?>
                </p>
            </a>
        <?php endif; ?>

        <!-- Recent News List -->
        <div class="lg:col-span-5 flex flex-col justify-between">
            <div class="flex flex-col divide-y divide-line">
                <?php if ($recent_query->have_posts()) : 
                    while ($recent_query->have_posts()) : $recent_query->the_post();
                        $r_post_id = get_the_ID();
                        $r_day_meta = get_post_meta($r_post_id, 'day', true);
                        if (empty($r_day_meta)) {
                            $r_day_meta = get_the_date('l, d M Y', $r_post_id);
                        }
                        $r_img_url = get_the_post_thumbnail_url($r_post_id, 'medium');
                        if (empty($r_img_url)) {
                            $r_img_url = get_template_directory_uri() . '/assets/images/default-berita.jpg'; // fallback
                        }
                        $r_news_url = get_permalink($r_post_id);
                        ?>
                        <a href="<?php echo esc_url($r_news_url); ?>" class="flex gap-4 group cursor-pointer py-7 first:pt-0 last:pb-0 block">
                            <div class="relative w-[120px] h-[80px] sm:w-[160px] sm:h-[100px] overflow-hidden shrink-0 rounded-md">
                                <img 
                                    src="<?php echo esc_url($r_img_url); ?>"
                                    alt="<?php the_title_attribute(); ?>"
                                    class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                                    loading="lazy"
                                />
                            </div>
                            <div class="flex flex-col">
                                <div class="text-[12px] sm:text-xs text-body-secondary font-mono mb-2">
                                    <?php echo esc_html($r_day_meta); ?>
                                </div>
                                <h4 class="font-display font-medium text-body group-hover:text-primary transition-colors text-sm sm:text-[18px] leading-snug line-clamp-3">
                                    <?php the_title(); ?>
                                </h4>
                            </div>
                        </a>
                    <?php 
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="py-6 text-center text-body-secondary font-sans">Belum ada berita lainnya.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="mt-8 sm:hidden text-center">
        <a href="<?php echo esc_url(home_url('/berita')); ?>" class="inline-flex items-center text-primary font-sans font-semibold text-sm hover:underline">
            Lihat Semua Berita
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</section>
