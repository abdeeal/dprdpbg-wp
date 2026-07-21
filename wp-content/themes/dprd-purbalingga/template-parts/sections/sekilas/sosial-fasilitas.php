<?php
/**
 * Sosial & Fasilitas Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['sosialFasilitas'];
?>
<section id="sosial-fasilitas" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Sosial & Fasilitas
  </h2>

  <!-- Pendidikan -->
  <div class="mb-10">
    <h3 class="font-sans font-bold text-[13px] text-body-secondary uppercase tracking-wider mb-4">
      PENDIDIKAN (SEKOLAH & SISWA)
    </h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-[#F0EEE7]">
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Jenjang</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Jumlah Sekolah</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Total Guru</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Total Siswa</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['pendidikan'] as $item) : ?>
            <tr class="border-b border-line last:border-b">
              <td class="py-4 px-4 font-sans text-body text-[14px]"><?php echo esc_html($item['jenjang']); ?></td>
              <td class="py-4 px-4 font-mono text-body text-[14px] text-right"><?php echo esc_html($item['jumlahSekolah']); ?></td>
              <td class="py-4 px-4 font-mono text-body text-[14px] text-right"><?php echo esc_html($item['totalGuru']); ?></td>
              <td class="py-4 px-4 font-mono font-bold text-primary text-[14px] text-right"><?php echo esc_html($item['totalSiswa']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Kesehatan -->
  <div>
    <h3 class="font-sans font-bold text-[13px] text-body-secondary uppercase tracking-wider mb-4">
      KESEHATAN
    </h3>
    
    <!-- Fasilitas Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
      <?php foreach ($data['kesehatan']['fasilitas'] as $fasilitas) : ?>
        <div class="bg-[#F0EEE7] rounded-card p-5 flex flex-col justify-between h-full">
          <div class="font-sans text-[11px] text-body-secondary uppercase tracking-wider mb-4">
            <?php echo esc_html($fasilitas['label']); ?>
          </div>
          <div class="font-mono text-[24px] md:text-[28px] font-bold text-primary tracking-tight leading-none mt-auto">
            <?php echo esc_html($fasilitas['nilai']); ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Table Tenaga Medis -->
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-[#F0EEE7]">
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px]">Tenaga Medis</th>
            <th class="py-3 px-4 font-sans font-bold text-ink text-[13px] text-right">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['kesehatan']['tenagaMedis'] as $item) : ?>
            <tr class="border-b border-line last:border-b">
              <td class="py-4 px-4 font-sans text-body text-[14px]"><?php echo esc_html($item['tenaga']); ?></td>
              <td class="py-4 px-4 font-mono text-body text-[14px] text-right"><?php echo esc_html($item['jumlah']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
