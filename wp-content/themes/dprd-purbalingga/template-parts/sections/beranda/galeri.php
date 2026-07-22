<?php
/**
 * Template part untuk menampilkan Galeri Preview di Beranda
 */
if (!defined('ABSPATH')) exit;

// Query 4 Galeri Kegiatan terbaru
$galeri_query = new WP_Query([
    'post_type'      => 'galeri',
    'posts_per_page' => 4,
]);
?>

<section class="w-full py-16">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="flex justify-between items-end mb-8">
            <h2 class="font-montserrat text-3xl sm:text-4xl font-bold text-body">Galeri Kegiatan</h2>
            <a href="<?php echo esc_url(home_url('/galeri')); ?>" class="hidden sm:flex items-center text-primary font-mono font-semibold text-sm hover:underline">
                Lihat Semua Galeri
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <?php if ($galeri_query->have_posts()) : 
                while ($galeri_query->have_posts()) : $galeri_query->the_post();
                    $post_id = get_the_ID();
                    $image_id = get_post_meta($post_id, 'image_id', true);
                    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
                    if (empty($image_url)) {
                        $image_url = get_the_post_thumbnail_url($post_id, 'large');
                    }
                    if (empty($image_url)) {
                        $image_url = get_template_directory_uri() . '/assets/images/default-galeri.jpg'; // fallback
                    }
                    ?>
                    <div class="relative w-full aspect-square rounded-none overflow-hidden group cursor-pointer">
                        <img 
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php the_title_attribute(); ?>"
                            class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-700"
                        />
                        <div class="absolute inset-0 bg-ink/0 group-hover:bg-ink/10 transition-colors duration-300"></div>
                    </div>
                <?php 
                endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="col-span-4 py-12 text-center text-body-secondary font-sans">Belum ada foto galeri kegiatan.</div>
            <?php endif; ?>
        </div>
        
        <div class="mt-8 sm:hidden text-center">
            <a href="<?php echo esc_url(home_url('/galeri')); ?>" class="inline-flex items-center text-primary font-sans font-semibold text-sm hover:underline">
                Lihat Semua Galeri
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>
