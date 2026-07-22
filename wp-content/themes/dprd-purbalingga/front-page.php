<?php
/**
 * Template Name: Beranda
 * Halaman depan website DPRD Purbalingga
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen">
    <?php
    // Section components will be rendered here in Phase 4
    get_template_part('template-parts/sections/beranda/hero');
    ?>
    
    <div class="relative z-10 bg-main w-full">
        <?php
        get_template_part('template-parts/sections/beranda/announcement');
        get_template_part('template-parts/sections/beranda/berita');
        get_template_part('template-parts/sections/beranda/agenda');
        get_template_part('template-parts/sections/beranda/video');
        get_template_part('template-parts/sections/beranda/kelembagaan');
        get_template_part('template-parts/sections/beranda/layanan');
        get_template_part('template-parts/sections/beranda/galeri');
        ?>
    </div>
</main>

<?php
get_footer();
