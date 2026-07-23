<?php
/**
 * Template Name: Sejarah Kabupaten Purbalingga
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 pb-16">
        <?php get_template_part('template-parts/ui/breadcrumbs'); ?>

        <div class="mb-0 dprd-fade-in" data-direction="up" data-duration="0.6">
            <h1 class="font-display text-3xl md:text-[36px] font-black tracking-tight text-primary mt-8 mb-6">Sejarah Kabupaten Purbalingga</h1>
        </div>

        <?php get_template_part('template-parts/sections/sejarah/content'); ?>
    </div>

    <!-- Banner CTA: full-width, di luar container -->
    <div class="w-full bg-[#A32B2E] py-12 dprd-fade-in" data-direction="up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white text-left">
                <h3 class="font-sans font-bold text-[22px] md:text-2xl mb-1.5">Ingin Mengetahui Lebih Lanjut?</h3>
                <p class="font-sans text-[15px] md:text-base text-white/90">Kunjungi Perpustakaan Daerah atau Sekretariat DPRD untuk dokumen sejarah lengkap.</p>
            </div>
            <button class="flex items-center gap-4 bg-white text-[#82111A] font-sans font-bold text-[15px] px-8 py-3.5 hover:bg-main transition-colors shrink-0">
                <div class="flex flex-col items-center leading-snug">
                    <span>Perpustakaan Daerah</span>
                    <span>Purbalingga</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </button>
        </div>
    </div>
</main>

<?php get_footer();

