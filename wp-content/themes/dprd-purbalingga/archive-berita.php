<?php
/**
 * The template for displaying Berita archive page (Daftar Berita)
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
            <h1 class="font-display text-primary text-4xl md:text-5xl lg:text-[56px] font-bold mb-4 tracking-tight leading-none">Berita</h1>
            <p class="font-mono text-sm text-body-secondary tracking-wide">
                Arsip Berita Dari DPRD Kabupaten Purbalingga
            </p>
        </header>

        <?php
        get_template_part('template-parts/sections/berita/archive-list');
        ?>
    </div>
</main>

<?php
get_footer();
