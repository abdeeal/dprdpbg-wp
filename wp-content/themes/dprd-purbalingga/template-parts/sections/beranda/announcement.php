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
                <svg width="18" height="18" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-white">
                    <path d="M12 6.75V5.25H15V6.75H12ZM12.9 12L10.5 10.2L11.4 9L13.8 10.8L12.9 12ZM11.4 3L10.5 1.8L12.9 0L13.8 1.2L11.4 3ZM2.25 11.25V8.25H1.5C1.0875 8.25 0.734375 8.10312 0.440625 7.80937C0.146875 7.51562 0 7.1625 0 6.75V5.25C0 4.8375 0.146875 4.48438 0.440625 4.19063C0.734375 3.89688 1.0875 3.75 1.5 3.75H4.5L8.25 1.5V10.5L4.5 8.25H3.75V11.25H2.25ZM6.75 7.8375V4.1625L4.9125 5.25H1.5V6.75H4.9125L6.75 7.8375ZM9 8.5125V3.4875C9.3375 3.7875 9.60938 4.15312 9.81563 4.58437C10.0219 5.01562 10.125 5.4875 10.125 6C10.125 6.5125 10.0219 6.98438 9.81563 7.41563C9.60938 7.84688 9.3375 8.2125 9 8.5125Z" fill="currentColor"/>
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
