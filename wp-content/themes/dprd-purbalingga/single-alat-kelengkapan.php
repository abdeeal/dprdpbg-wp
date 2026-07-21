<?php
/**
 * The template for displaying a single Alat Kelengkapan post
 * (Komisi, Fraksi, Badan Anggaran, dll. — total 14 halaman dinamis)
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <?php
        if (have_posts()) : while (have_posts()) : the_post();
            get_template_part('template-parts/sections/alat-kelengkapan/single-content');
        endwhile; endif;
        ?>
    </div>
</main>

<?php
get_footer();
