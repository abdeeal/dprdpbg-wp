<?php
/**
 * Hidrologi Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['hidrologi'];
?>
<section data-fade id="hidrologi" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Hidrologi
  </h2>

  <div class="bg-[#F0EEE7] rounded-card p-8 sm:p-10">
    <div class="flex flex-col md:flex-row gap-8 md:gap-16">
      
      <!-- Column 1: Sungai Melewati -->
      <div class="flex-1">
        <div class="flex items-start gap-4 mb-6">
          <svg class="w-6 h-6 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
          </svg>
          <h3 class="font-sans font-bold text-ink text-[17px] leading-snug">
            Sungai Melewati Kabupaten Purbalingga
          </h3>
        </div>
        <ul class="pl-10 space-y-3 font-sans text-[15px] text-body-secondary">
          <?php foreach ($data['sungaiMelewati'] as $sungai) : ?>
            <li><?php echo esc_html($sungai); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Column 2: Sungai Mengalir -->
      <div class="flex-1">
        <div class="flex items-start gap-4 mb-6">
          <svg class="w-6 h-6 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
          </svg>
          <h3 class="font-sans font-bold text-ink text-[17px] leading-snug">
            Sungai Mengalir di Kabupaten Purbalingga
          </h3>
        </div>
        <ul class="pl-10 space-y-3 font-sans text-[15px] text-body-secondary">
          <?php foreach ($data['sungaiMengalir'] as $sungai) : ?>
            <li><?php echo esc_html($sungai); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>
  </div>
</section>
