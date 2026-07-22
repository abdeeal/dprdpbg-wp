<?php
/**
 * Kepegawaian Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['kepegawaian'];
?>
<section data-fade id="kepegawaian" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Kepegawaian
  </h2>

  <!-- Main Card -->
  <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8 mb-10 relative overflow-hidden flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
        TOTAL APARATUR SIPIL NEGARA
      </div>
      <div class="flex items-baseline">
        <span class="font-mono text-[32px] md:text-[36px] font-bold text-primary tracking-tight" data-counter>
          <?php echo esc_html($data['totalAsn']); ?>
        </span>
        <span class="font-sans text-[15px] font-bold text-primary ml-1">
          Jiwa
        </span>
      </div>
    </div>
    <div class="font-mono text-[14px] text-body-secondary tracking-widest pb-1.5 md:pb-2">
      L : <?php echo esc_html($data['lakiLaki']); ?> | P : <?php echo esc_html($data['perempuan']); ?>
    </div>
  </div>

  <!-- Table Distribusi Golongan -->
  <div>
    <h3 class="font-sans font-bold text-[13px] text-body-secondary uppercase tracking-wider mb-4">
      DISTRIBUSI BERDASARKAN GOLONGAN
    </h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-[#F0EEE7]">
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Golongan</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Jumlah Personel</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['distribusiGolongan'] as $item) : ?>
            <tr class="border-b border-line last:border-b">
              <td class="py-4 px-4 font-sans text-body text-[14px]"><?php echo esc_html($item['golongan']); ?></td>
              <td class="py-4 px-4 font-mono text-body text-[14px] text-right" data-counter><?php echo esc_html($item['jumlah']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
