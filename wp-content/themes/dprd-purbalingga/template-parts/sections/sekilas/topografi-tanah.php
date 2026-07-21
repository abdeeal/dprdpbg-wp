<?php
/**
 * Topografi & Tanah Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['topografiTanah'];
?>
<section id="topografi-tanah" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Topografi & Tanah
  </h2>

  <!-- Cards -->
  <div class="flex flex-col md:flex-row gap-4 mb-10">
    <div class="bg-[#F0EEE7] rounded-card p-6 flex-1">
      <h3 class="font-sans font-bold text-ink text-[16px] mb-3">Bagian Utara</h3>
      <p class="font-sans text-[15px] text-body leading-relaxed">
        <?php echo esc_html($data['wilayahUtara']); ?>
      </p>
    </div>
    <div class="bg-[#F0EEE7] rounded-card p-6 flex-1">
      <h3 class="font-sans font-bold text-ink text-[16px] mb-3">Bagian Selatan</h3>
      <p class="font-sans text-[15px] text-body leading-relaxed">
        <?php echo esc_html($data['wilayahSelatan']); ?>
      </p>
    </div>
  </div>

  <!-- Tables Row -->
  <div class="flex flex-col md:flex-row gap-8">
    
    <!-- Table Distribusi Ketinggian -->
    <div class="flex-1">
      <h3 class="font-sans font-bold text-xs text-body-secondary uppercase tracking-wider mb-4">
        DISTRIBUSI KETINGGIAN
      </h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-[#F0EEE7]">
              <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Ketinggian (mdpl)</th>
              <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">% Luas</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['distribusiKetinggian'] as $item) : ?>
              <tr class="border-b border-line last:border-b">
                <td class="py-4 px-4 font-sans text-body text-[14px]"><?php echo esc_html($item['ketinggian']); ?></td>
                <td class="py-4 px-4 font-mono text-body text-[14px] text-right"><?php echo esc_html($item['persentase']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Table Persebaran Jenis Tanah -->
    <div class="flex-1">
      <h3 class="font-sans font-bold text-xs text-body-secondary uppercase tracking-wider mb-4">
        PERSEBARAN JENIS TANAH
      </h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-[#F0EEE7]">
              <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Jenis Tanah</th>
              <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Persentase Luas</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['persebaranJenisTanah'] as $item) : ?>
              <tr class="border-b border-line last:border-b">
                <td class="py-4 px-4 font-sans text-body text-[14px]"><?php echo esc_html($item['jenis']); ?></td>
                <td class="py-4 px-4 font-mono text-body text-[14px] text-right"><?php echo esc_html($item['persentase']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
