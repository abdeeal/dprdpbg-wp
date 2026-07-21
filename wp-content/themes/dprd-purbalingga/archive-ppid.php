<?php
/**
 * The template for displaying PPID archive page
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <?php
        get_template_part('template-parts/ui/breadcrumbs');
        ?>
        
        <header class="mb-12 mt-6">
            <h1 class="font-display text-4xl text-primary font-bold mb-4">Pejabat Pengelola Informasi dan Dokumentasi (PPID)</h1>
            <p class="font-sans text-body-secondary text-[15px] max-w-2xl">
                Layanan informasi publik DPRD Kabupaten Purbalingga. Temukan regulasi, dokumen publik, dan tata cara permohonan informasi di sini.
            </p>
        </header>

        <?php
        get_template_part('template-parts/sections/ppid/archive-list');
        ?>
    </div>
</main>

<?php
get_footer();
