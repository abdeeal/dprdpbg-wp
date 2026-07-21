<?php
/**
 * Luas Wilayah Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['luasWilayah'];
?>
<section id="luas-wilayah" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Luas Wilayah
  </h2>

  <!-- Cards -->
  <div class="flex flex-col sm:flex-row gap-4 mb-10">
    <div class="bg-[#F0EEE7] rounded-card p-6 flex-1 relative overflow-hidden">
      <div class="font-sans text-xs text-body-secondary uppercase tracking-wider mb-4">
        TOTAL LUAS WILAYAH
      </div>
      <div class="flex items-baseline">
        <span class="font-mono text-[32px] font-bold text-primary tracking-tight">
          <?php echo esc_html($data['luasTotal']); ?>
        </span>
        <span class="font-mono text-[15px] font-bold text-primary ml-1">
          Ha
        </span>
      </div>
    </div>
    <div class="bg-[#F0EEE7] rounded-card p-6 flex-1 relative overflow-hidden">
      <div class="font-sans text-xs text-body-secondary uppercase tracking-wider mb-4">
        PERSENTASE LUAS JATENG
      </div>
      <div class="flex items-baseline">
        <span class="font-mono text-[32px] font-bold text-primary tracking-tight">
          <?php echo esc_html($data['persentaseJateng']); ?>
        </span>
        <span class="font-mono text-[18px] font-bold text-primary ml-0.5">
          %
        </span>
      </div>
    </div>
  </div>

  <!-- Table Luas Wilayah -->
  <div>
    <h3 class="font-sans font-bold text-xs text-body-secondary uppercase tracking-wider mb-3">
      LUAS WILAYAH PER KECAMATAN
    </h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-[#F0EEE7]">
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Kecamatan</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Luas (Ha)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['luasPerKecamatan'] as $item) : ?>
            <tr class="border-b border-line last:border-b">
              <td class="py-3.5 px-4 font-sans text-body text-[14px]"><?php echo esc_html($item['kecamatan']); ?></td>
              <td class="py-3.5 px-4 font-mono text-[14px] text-right <?php echo ($item['kecamatan'] === 'Purbalingga' || $item['kecamatan'] === 'Rembang') ? 'text-primary font-bold' : 'text-body'; ?>">
                <?php echo esc_html($item['luas']); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
