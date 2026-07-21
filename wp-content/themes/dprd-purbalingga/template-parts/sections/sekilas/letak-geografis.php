<?php
/**
 * Letak Geografis Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['letakGeografis'];
// Allow DB override if filled
$db_desc = get_post_meta(get_the_ID(), 'letak_geografis_deskripsi', true);
if (!empty($db_desc)) {
    $data['deskripsi'] = $db_desc;
}
?>
<section id="letak-geografis" className="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-4">
    Letak Geografis
  </h2>
  <p class="font-sans text-[15px] md:text-base text-body leading-relaxed mb-8">
    <?php echo esc_html($data['deskripsi']); ?>
  </p>

  <!-- Compass Visualization -->
  <div class="bg-surface/30 rounded-card p-4 md:p-10 mb-8 border border-line">
    <div class="relative max-w-[600px] mx-auto grid grid-cols-3 gap-2 md:gap-6 items-center">
      
      <!-- Dashed lines (Cross) -->
      <div class="absolute top-1/2 left-0 w-full border-t border-dashed border-line/80 z-0"></div>
      <div class="absolute top-0 left-1/2 h-full border-l border-dashed border-line/80 z-0"></div>

      <!-- Utara - Top Center -->
      <div class="col-start-2 bg-[#F0EEE7] z-10 p-2 md:px-4 md:py-3 rounded-[4px] text-center border border-white/50">
        <span class="font-sans text-[10px] md:text-[11px] text-body-secondary block mb-1 uppercase tracking-widest font-semibold">Utara</span>
        <span class="font-sans font-bold text-ink text-[11px] md:text-[14px] leading-snug"><?php echo esc_html($data['batasWilayah']['utara']); ?></span>
      </div>

      <!-- Spacer -->
      <div class="col-start-3"></div>

      <!-- Barat - Middle Left -->
      <div class="col-start-1 bg-[#F0EEE7] z-10 p-2 md:px-4 md:py-3 rounded-[4px] text-center border border-white/50">
        <span class="font-sans text-[10px] md:text-[11px] text-body-secondary block mb-1 uppercase tracking-widest font-semibold">Barat</span>
        <span class="font-sans font-bold text-ink text-[11px] md:text-[14px] leading-snug"><?php echo esc_html($data['batasWilayah']['barat']); ?></span>
      </div>

      <!-- Center -->
      <div class="col-start-2 z-10 flex items-center justify-center">
        <div class="bg-white/80 backdrop-blur-sm border border-primary/20 px-2 py-1.5 md:px-6 md:py-3 rounded-[4px] text-primary font-bold tracking-widest font-display text-[10px] md:text-base text-center">
          PURBALINGGA
        </div>
      </div>

      <!-- Timur - Middle Right -->
      <div class="col-start-3 bg-[#F0EEE7] z-10 p-2 md:px-4 md:py-3 rounded-[4px] text-center border border-white/50">
        <span class="font-sans text-[10px] md:text-[11px] text-body-secondary block mb-1 uppercase tracking-widest font-semibold">Timur</span>
        <span class="font-sans font-bold text-ink text-[11px] md:text-[14px] leading-snug"><?php echo esc_html($data['batasWilayah']['timur']); ?></span>
      </div>

      <!-- Spacer -->
      <div class="col-start-1"></div>

      <!-- Selatan - Bottom Center -->
      <div class="col-start-2 bg-[#F0EEE7] z-10 p-2 md:px-4 md:py-3 rounded-[4px] text-center border border-white/50">
        <span class="font-sans text-[10px] md:text-[11px] text-body-secondary block mb-1 uppercase tracking-widest font-semibold">Selatan</span>
        <span class="font-sans font-bold text-ink text-[11px] md:text-[14px] leading-snug"><?php echo esc_html($data['batasWilayah']['selatan']); ?></span>
      </div>

    </div>
  </div>

  <!-- Table Jarak Ke Kota Besar -->
  <div>
    <h3 class="font-sans font-bold text-xs text-body-secondary uppercase tracking-wider mb-3">
      JARAK KE KOTA-KOTA BESAR
    </h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-t border-b border-line bg-[#F0EEE7]">
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Nama Kota</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Jarak (KM)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['jarakKotaBesar'] as $item) : ?>
            <tr class="border-b border-line last:border-b">
              <td class="py-3 px-4 font-sans text-body text-[15px]"><?php echo esc_html($item['kota']); ?></td>
              <td class="py-3 px-4 text-right">
                <span class="font-mono font-bold text-ink text-[14px]"><?php echo esc_html($item['jarak']); ?></span>
                <span class="font-mono text-body text-[13px] ml-1.5">km</span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
