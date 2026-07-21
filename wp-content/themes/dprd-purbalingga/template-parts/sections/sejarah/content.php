<?php
/**
 * Sejarah Kabupaten Purbalingga Content template part
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;
?>

<article class="prose max-w-none mb-16 font-sans text-body-secondary leading-relaxed">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    else :
        echo '<p>Konten sejarah belum diisi di halaman ini.</p>';
    endif;
    ?>
</article>

<hr class="border-line border-t my-16">

<?php
// Tampilkan Grid Tokoh Purbalingga
get_template_part('template-parts/sections/tokoh-sejarah/archive-list');
?>
