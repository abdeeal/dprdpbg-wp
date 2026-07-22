<?php
/**
 * The template for displaying the Profil DPRD NavigationIndex landing page
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;



$children_links = [
    [
        'title' => 'Pimpinan DPRD',
        'href'  => home_url('/profil-dprd/pimpinan-dprd/')
    ],
    [
        'title' => 'Badan Musyawarah',
        'href'  => home_url('/profil-dprd/badan-musyawarah/')
    ],
    [
        'title' => 'Badan Anggaran',
        'href'  => home_url('/profil-dprd/badan-anggaran/')
    ],
    [
        'title' => 'Badan Pembentukan Peraturan Daerah',
        'href'  => home_url('/profil-dprd/badan-pembentukan-peraturan-daerah/')
    ],
    [
        'title' => 'Badan Kehormatan',
        'href'  => home_url('/profil-dprd/badan-kehormatan/')
    ],
    [
        'title' => 'Komisi',
        'href'  => home_url('/profil-dprd/komisi/')
    ],
    [
        'title' => 'Fraksi',
        'href'  => home_url('/profil-dprd/fraksi/')
    ]
];

get_header();
?>
<main class="w-full bg-main min-h-screen pt-10 pb-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
    
    <!-- Breadcrumbs -->
    <?php get_template_part('template-parts/ui/breadcrumbs'); ?>

    <!-- Page Header -->
    <div class="text-left mb-10">
      <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2">
        Profil DPRD
      </h1>
    </div>

    <!-- List Links -->
    <div class="w-full flex flex-col border-t border-[#A32B2E]/40 mt-12">
        <?php foreach ($children_links as $child) : ?>
          <div class="border-b border-[#A32B2E]/40 last:border-b-0 py-6 md:py-8">
            <a href="<?php echo esc_url($child['href']); ?>" class="w-full flex items-start justify-between text-left group cursor-pointer">
              <div class="flex flex-col gap-1.5">
                <h3 class="font-display text-xl md:text-[22px] text-body group-hover:text-primary transition-colors">
                  <?php echo esc_html($child['title']); ?>
                </h3>
              </div>
              <div class="text-primary shrink-0 ml-4 pt-1 opacity-0 group-hover:opacity-100 transition-all duration-300 md:opacity-100 group-hover:rotate-45">
                <?php echo dprd_get_lucide_svg('arrow-up-right', 24); ?>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
    </div>

  </div>
</main>
<?php
get_footer();
