<?php
/**
 * Template part untuk InfoStrip dan Banner Carousel
 */
if (!defined('ABSPATH')) exit;

$pengumuman_text = get_option('dprd_pengumuman_strip');
if (empty($pengumuman_text)) {
    $pengumuman_text = "Sidang Paripurna DPRD Kabupaten Purbalingga berlangsung transparan, akuntabel, dan profesional.";
}
?>

<div class="sticky top-[64px] z-20">
    <div class="w-full bg-[#11230e] text-white py-3 overflow-hidden whitespace-nowrap flex items-center border-t border-b border-primary/20">
        <div class="flex items-center dprd-marquee-container animate-marquee">
            <?php for ($i = 0; $i < 4; $i++) : ?>
            <div class="flex items-center gap-3 pr-16 md:pr-32">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-white">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="font-mono text-xs sm:text-sm tracking-wide">
                    <?php echo esc_html($pengumuman_text); ?>
                </p>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<?php
$raw_banners = get_option('dprd_banner_json', '[]');
$banners = json_decode($raw_banners, true);

// Filter valid banners
$valid_banners = [];
if (is_array($banners)) {
    foreach ($banners as $banner) {
        $img_id = isset($banner['image']) ? absint($banner['image']) : 0;
        if ($img_id) {
            $valid_banners[] = $img_id;
        }
    }
}

if (!empty($valid_banners)) :
?>
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 mt-8 group dprd-banner-carousel">
    <div class="relative w-full overflow-hidden rounded-card">
        <div class="flex transition-transform duration-500 ease-in-out h-full dprd-carousel-track">
            <?php foreach ($valid_banners as $index => $img_id) : 
                $img_url = wp_get_attachment_image_url($img_id, 'full');
            ?>
            <div class="min-w-full relative aspect-[3/1] overflow-hidden dprd-carousel-slide">
                <a href="#">
                    <img 
                        src="<?php echo esc_url($img_url); ?>" 
                        alt="Banner <?php echo $index + 1; ?>" 
                        class="w-full h-full object-cover"
                    />
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($valid_banners) > 1) : ?>
        <!-- Navigation Buttons -->
        <button class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 bg-white/70 hover:bg-white text-ink rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-sm dprd-carousel-prev" aria-label="Previous slide">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>

        <button class="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 bg-white/70 hover:bg-white text-ink rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-sm dprd-carousel-next" aria-label="Next slide">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>

        <!-- Dots Indicator -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10 dprd-carousel-dots">
            <?php foreach ($valid_banners as $index => $img_id) : ?>
            <button class="w-2 h-2 rounded-full transition-all duration-300 dprd-carousel-dot <?php echo $index === 0 ? 'bg-white w-4' : 'bg-white/50 hover:bg-white/75'; ?>" data-index="<?php echo $index; ?>" aria-label="Go to slide <?php echo $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
