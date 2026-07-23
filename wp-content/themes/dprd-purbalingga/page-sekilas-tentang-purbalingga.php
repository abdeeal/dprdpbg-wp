<?php
/**
 * Template Name: Sekilas Tentang Purbalingga
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main class="w-full bg-main min-h-screen pt-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 pb-20">
    
    <!-- Breadcrumbs -->
    <?php
    get_template_part('template-parts/ui/breadcrumbs', null, [
        'items' => [
            ['label' => 'Beranda', 'href' => home_url('/')],
            ['label' => 'Selayang Pandang', 'href' => home_url('/selayang-pandang')],
            ['label' => 'Sekilas Tentang Purbalingga']
        ]
    ]);
    ?>

    <!-- Page Title -->
    <div class="dprd-fade-in" data-direction="up" data-duration="0.6">
        <h1 class="font-display text-3xl md:text-[36px] font-black tracking-tight text-primary mt-8 mb-6">
          Sekilas Tentang Purbalingga
        </h1>
    </div>

    <!-- Alert Information Box -->
    <div class="dprd-fade-in" data-direction="up" data-delay="0.1">
        <div class="bg-primary-light border border-primary/20 rounded-[4px] p-4 flex gap-4 items-start mb-12">
          <svg class="w-6 h-6 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h3 class="font-sans font-bold text-primary text-[15px] mb-1">Informasi Data</h3>
            <p class="font-sans text-[14px] text-primary/80">
              Data kependudukan dan statistik yang ditampilkan bersumber dari publikasi BPS terbaru tahun 2024, sebagai rujukan resmi tingkat daerah.
            </p>
          </div>
        </div>
    </div>

    <!-- Layout: Main Content (Left) and TOC (Right) -->
    <div class="flex flex-col lg:flex-row gap-12">
      
      <!-- Main Content Area -->
      <div class="lg:w-3/4">
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/letak-geografis'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/luas-wilayah'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/topografi-tanah'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/hidrologi'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/pemerintahan'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/kepegawaian'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/kependudukan'); ?></div>
        <div class="dprd-fade-in" data-direction="up"><?php get_template_part('template-parts/sections/sekilas/sosial-fasilitas'); ?></div>
      </div>

      <!-- Table of Contents Sidebar -->
      <div class="hidden lg:block lg:w-1/4">
        <?php get_template_part('template-parts/sections/sekilas/table-of-contents'); ?>
      </div>

    </div>

  </div>
</main>

<?php
get_footer();
