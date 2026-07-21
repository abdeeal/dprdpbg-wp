<?php
/**
 * The template for displaying Propemperda archive page
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
            <div class="text-left">
                <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2">PROPEMPERDA</h1>
                <p class="font-mono text-sm md:text-[15px] text-body-secondary tracking-wide">Program Pembentukan Peraturan Daerah</p>
            </div>
        </header>

        <?php
        get_template_part('template-parts/sections/propemperda/archive-list');
        ?>
    </div>
</main>

<?php
get_footer();
