<?php
/**
 * Archive List Section for Berita (Fase 3 & 4)
 */
if (!defined('ABSPATH')) exit;

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Build query
$args = [
    'post_type'      => 'berita',
    'posts_per_page' => 9, // 9 posts per page
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC'
];

// Handle search query if present
$search_query = isset($_GET['search_berita']) ? sanitize_text_field($_GET['search_berita']) : '';
if (!empty($search_query)) {
    $args['s'] = $search_query;
}

$query = new WP_Query($args);
?>

<div class="w-full flex flex-col items-center">
    
    <!-- Search Form -->
    <div class="w-full flex justify-center mb-16">
        <form method="get" action="" class="relative w-full max-w-[640px]">
            <input 
                type="text" 
                name="search_berita" 
                value="<?php echo esc_attr($search_query); ?>"
                placeholder="Cari Berita" 
                class="w-full py-4 px-6 pr-14 border border-line bg-white font-sans text-[15px] text-body placeholder:text-body-secondary/80 focus:outline-none focus:border-primary transition-colors"
            />
            <button type="submit" class="absolute right-5 top-1/2 -translate-y-1/2 text-body hover:text-primary transition-colors">
                <!-- Search Icon -->
                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
        </form>
    </div>

    <?php if ($query->have_posts()) : ?>
        <!-- Grid -->
        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-12 mb-16">
            <?php while ($query->have_posts()) : $query->the_post(); 
                $post_id = get_the_ID();
                $title = get_the_title();
                $slug = get_post_field('post_name', $post_id);
                $day = get_post_meta($post_id, 'day', true);
                if (empty($day)) {
                    $day = get_the_date('d M Y', $post_id);
                }
                
                $img_url = get_the_post_thumbnail_url($post_id, 'large');
                if (empty($img_url)) {
                    $img_url = get_template_directory_uri() . '/assets/images/default-berita.jpg';
                }
                
                $permalink = get_permalink($post_id);
            ?>
                <a href="<?php echo esc_url($permalink); ?>" class="group flex flex-col w-full cursor-pointer">
                    <div class="relative w-full aspect-[16/10] overflow-hidden mb-4 rounded-md">
                        <img 
                            src="<?php echo esc_url($img_url); ?>" 
                            alt="<?php echo esc_attr($title); ?>"
                            class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                    <div class="font-mono font-bold text-[13px] text-body-secondary mb-2">
                        <?php echo esc_html($day); ?>
                    </div>
                    <h3 class="font-display font-medium text-[16px] leading-[1.4] text-body group-hover:text-primary transition-colors line-clamp-3">
                        <?php echo esc_html($title); ?>
                    </h3>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="flex items-center gap-2 text-sm font-sans text-body-secondary mt-8">
            <?php
            $big = 999999999;
            $pages = paginate_links([
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => max(1, $paged),
                'total'     => $query->max_num_pages,
                'type'      => 'array',
                'prev_next' => true,
                'prev_text' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>',
                'next_text' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>',
            ]);

            if (is_array($pages)) {
                foreach ($pages as $page) {
                    if (strpos($page, 'current') !== false) {
                        echo str_replace(
                            "class='page-numbers current'",
                            "class='w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-medium'",
                            $page
                        );
                    } else if (strpos($page, 'dots') !== false) {
                        echo '<span class="px-1 tracking-widest">...</span>';
                    } else {
                        echo str_replace(
                            "class='page-numbers'",
                            "class='w-8 h-8 flex items-center justify-center rounded hover:bg-surface transition-colors font-medium hover:text-primary'",
                            $page
                        );
                    }
                }
            }
            ?>
        </div>

    <?php else : ?>
        <p class="text-center text-body-secondary py-12 font-sans">Tidak ada berita yang ditemukan.</p>
    <?php endif; ?>

</div>
<?php
wp_reset_postdata();
?>
