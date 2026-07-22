<?php
/**
 * The template for displaying 503 pages (Under Construction / Segera Hadir)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-[70vh] flex items-center justify-center pt-20 pb-32">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <!-- 503 Box -->
        <div class="relative flex items-center justify-center w-48 h-56 border border-line mx-auto mb-10">
            <div class="absolute inset-x-4 top-8 border-t border-line/60"></div>
            <div class="absolute inset-x-4 bottom-8 border-t border-line/60"></div>
            <h1 class="font-display font-black text-[100px] text-primary tracking-tighter leading-none z-10">
                503
            </h1>
        </div>

        <h2 class="font-display text-3xl md:text-4xl text-body mb-4">
            Halaman Akan Segera Hadir
        </h2>
        <p class="font-sans text-[15px] md:text-base text-body-secondary mb-10">
            Halaman yang Anda tuju sedang dalam tahap pengembangan dan akan segera tersedia.
        </p>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 font-sans font-bold text-primary hover:underline transition-colors">
            <span>Kembali ke Beranda</span>
        </a>
    </div>
</main>

<?php
get_footer();
