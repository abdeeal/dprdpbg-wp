<?php
/**
 * Fallback template.
 * Halaman spesifik nanti punya template sendiri (front-page.php, single-berita.php, dst.)
 * sesuai Fase 3 di dokumen alur migrasi.
 */
get_header();
?>

<main class="w-full bg-main min-h-screen pt-10 pb-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <h1 class="font-display text-3xl text-primary"><?php the_title(); ?></h1>
      <div class="font-sans text-body"><?php the_content(); ?></div>
    <?php endwhile; else : ?>
      <p>Konten tidak ditemukan.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>