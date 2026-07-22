<?php
/**
 * Template Part: Hero Section (Beranda)
 * 100% Identik dengan Next.js / TailwindCSS Reference
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

$hero_img_id = get_option('dprd_hero_image');
$hero_bg_url = $hero_img_id ? wp_get_attachment_image_url($hero_img_id, 'full') : get_template_directory_uri() . '/assets/images/default-hero.jpg';

$stats_anggota = get_option('dprd_hero_stats_anggota', '50');
$stats_fraksi  = get_option('dprd_hero_stats_fraksi', '7');
$stats_komisi  = get_option('dprd_hero_stats_komisi', '4');
$stats_periode_mulai = get_option('dprd_hero_stats_periode_mulai', '2024');
$stats_periode_akhir = get_option('dprd_hero_stats_periode_akhir', '2029');
?>

<section class="relative w-full">
    <!-- Hero Banner -->
    <div class="relative w-full h-[75vh] md:h-[80vh] lg:h-[85vh] min-h-[500px] flex items-end">
        
        <!-- Parallax Background Image -->
        <div class="fixed top-0 left-0 w-full h-screen z-0 pointer-events-none">
            <img 
                src="<?php echo esc_url($hero_bg_url); ?>"
                alt="Gedung DPRD Purbalingga"
                class="w-full h-full object-cover object-bottom lg:object-center"
            />
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#251818]/95 to-[#251818]/10 via-[#251818]/50 z-10 pointer-events-none"></div>
        </div>

        <!-- Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 w-full text-white pb-16 md:pb-24">
            <div class="">
                <h1 class="font-montserrat text-4xl sm:text-5xl font-bold leading-tight">
                    DPRD Kabupaten Purbalingga
                </h1>
                <div class="max-w-xl">
                    <h2 class="font-montserrat text-2xl sm:text-3xl font-semibold mb-6">
                        Wadah Aspirasi dan Pengawasan Rakyat
                    </h2>
                </div>
                <div class="max-w-lg">
                    <p class="font-sans text-sm sm:text-base opacity-90 leading-relaxed max-w-xl">
                        Mewujudkan lembaga perwakilan rakyat yang transparan, akuntabel, dan profesional dalam memperjuangkan kesejahteraan masyarakat Purbalingga.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Bar --> 
    <div class="relative z-10 w-full bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-8">
                <div class="flex flex-col justify-center border-l-2 border-primary pl-4">
                    <span class="font-mono text-[10px] sm:text-xs text-body-secondary uppercase tracking-[0.2em] font-semibold mb-2">Anggota Dewan</span>
                    <span class="font-mono text-3xl sm:text-4xl font-bold text-primary leading-none dprd-animated-counter" data-value="<?php echo esc_attr($stats_anggota); ?>">1</span>
                </div>
                <div class="flex flex-col justify-center border-l-2 border-primary pl-4">
                    <span class="font-mono text-[10px] sm:text-xs text-body-secondary uppercase tracking-[0.2em] font-semibold mb-2">Fraksi</span>
                    <span class="font-mono text-3xl sm:text-4xl font-bold text-primary leading-none dprd-animated-counter" data-value="<?php echo esc_attr($stats_fraksi); ?>">1</span>
                </div>
                <div class="flex flex-col justify-center border-l-2 border-primary pl-4">
                    <span class="font-mono text-[10px] sm:text-xs text-body-secondary uppercase tracking-[0.2em] font-semibold mb-2">Komisi</span>
                    <span class="font-mono text-3xl sm:text-4xl font-bold text-primary leading-none dprd-animated-counter" data-value="<?php echo esc_attr($stats_komisi); ?>">1</span>
                </div>
                <div class="flex flex-col justify-center border-l-2 border-primary pl-4">
                    <span class="font-mono text-[10px] sm:text-xs text-body-secondary uppercase tracking-[0.2em] font-semibold mb-2">Periode Jabatan</span>
                    <span class="font-mono text-3xl sm:text-4xl font-bold text-primary leading-none dprd-animated-counter" data-value="<?php echo esc_attr($stats_periode_mulai . '-' . $stats_periode_akhir); ?>">
                        1945-<?php echo esc_html(1945 + ((int)$stats_periode_akhir - (int)$stats_periode_mulai)); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
