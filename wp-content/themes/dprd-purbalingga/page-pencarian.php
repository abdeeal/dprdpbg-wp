<?php
/**
 * Template Name: Halaman Pencarian
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <?php get_template_part('template-parts/ui/breadcrumbs'); ?>
    </div>
        
    <?php
    get_template_part('template-parts/sections/pencarian/search-bar');
    ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        get_template_part('template-parts/sections/pencarian/results');
        ?>
    </div>
</main>

<?php
get_footer();
