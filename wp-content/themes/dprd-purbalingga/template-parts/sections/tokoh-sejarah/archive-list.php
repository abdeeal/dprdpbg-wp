<?php
/**
 * Tokoh Sejarah Grid List template part
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

$query = new WP_Query([
    'post_type'      => 'tokoh-sejarah',
    'posts_per_page' => -1,
    'order'          => 'ASC'
]);

if (!$query->have_posts()) {
    echo '<p class="text-body-secondary font-sans">Belum ada profil tokoh sejarah yang terdaftar.</p>';
    return;
}
?>

<div class="w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <h2 class="font-display font-bold text-2xl md:text-[28px] text-body">
            Tokoh Purbalingga
        </h2>
        <span class="bg-primary-light text-primary font-mono font-bold text-xs px-3 py-1.5 rounded-badge border border-primary/10 w-max">
            <?php echo esc_html($query->found_posts); ?> Profil Terpilih
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php while ($query->have_posts()) : $query->the_post(); 
            $initials = get_post_meta(get_the_ID(), 'initials', true);
            $deskripsi = get_post_meta(get_the_ID(), 'deskripsi', true);
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium'); // or medium_large
            $name = get_the_title();
            
            // Fallback inisial jika kosong
            if (empty($initials)) {
                $words = explode(' ', $name);
                $initials = '';
                foreach ($words as $w) {
                    $initials .= strtoupper(substr($w, 0, 1));
                }
                $initials = substr($initials, 0, 2);
            }
            ?>
            <div class="flex gap-4 md:gap-5 p-5 bg-white border border-line rounded-card hover:shadow-sm transition-shadow">
                <!-- Image / Initials -->
                <div class="w-[80px] h-[80px] shrink-0 rounded-md overflow-hidden relative flex items-center justify-center bg-gray-100 text-primary font-display font-bold text-3xl">
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" class="object-cover w-full h-full">
                    <?php else : ?>
                        <?php echo esc_html($initials); ?>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="flex flex-col justify-center">
                    <h3 class="font-display font-bold text-[17px] text-primary mb-1">
                        <?php echo esc_html($name); ?>
                    </h3>
                    <?php if ($deskripsi) : ?>
                        <p class="font-sans text-[13px] text-body-secondary leading-snug">
                            <?php echo esc_html($deskripsi); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</div>
