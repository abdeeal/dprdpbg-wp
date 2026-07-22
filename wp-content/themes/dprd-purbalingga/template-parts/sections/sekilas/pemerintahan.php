<?php
/**
 * Pemerintahan Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['pemerintahan'];
?>
<section data-fade id="pemerintahan" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Pemerintahan
  </h2>

  <div class="flex flex-col md:flex-row gap-4 mb-10">
    
    <!-- Card Kecamatan -->
    <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8 flex-1">
      <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
        KECAMATAN
      </div>
      <div class="font-mono text-[32px] font-bold text-primary tracking-tight" data-counter>
        <?php echo esc_html($data['jumlahKecamatan']); ?>
      </div>
    </div>
    
    <!-- Card Desa/Kelurahan -->
    <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8 flex-1">
      <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
        DESA/KELURAHAN
      </div>
      <div class="font-mono text-[32px] font-bold text-primary tracking-tight" data-counter>
        <?php echo esc_html($data['jumlahDesa']); ?>
      </div>
    </div>
    
    <!-- Card RT -->
    <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8 flex-1">
      <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
        RT (RUKUN TETANGGA)
      </div>
      <div class="font-mono text-[32px] font-bold text-primary tracking-tight" data-counter>
        <?php echo esc_html($data['jumlahRT']); ?>
      </div>
    </div>

  </div>
</section>
