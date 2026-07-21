<?php
/**
 * Kependudukan Section
 */
if (!defined('ABSPATH')) exit;

$data = dprd_get_sekilas_data_static()['kependudukan'];
?>
<section id="kependudukan" class="mb-16 scroll-mt-24">
  <h2 class="font-display text-2xl md:text-3xl text-ink mb-6">
    Kependudukan
  </h2>

  <div class="flex flex-col gap-4">
    <!-- Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Card Total Penduduk -->
      <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8">
        <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
          TOTAL PENDUDUK
        </div>
        <div class="font-mono text-[32px] font-bold text-primary tracking-tight">
          <?php echo esc_html($data['totalPenduduk']); ?>
        </div>
      </div>

      <!-- Card Kepadatan -->
      <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8">
        <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
          KEPADATAN (/KM²)
        </div>
        <div class="font-mono text-[32px] font-bold text-primary tracking-tight">
          <?php echo esc_html($data['kepadatan']); ?>
        </div>
      </div>

      <!-- Card Laju Pertumbuhan -->
      <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8">
        <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
          LAJU PERTUMBUHAN
        </div>
        <div class="flex items-baseline">
          <span class="font-mono text-[32px] font-bold text-primary tracking-tight">
            <?php echo esc_html($data['lajuPertumbuhan']); ?>
          </span>
          <span class="font-sans text-[18px] font-bold text-primary ml-0.5">
            %
          </span>
        </div>
      </div>
    </div>

    <!-- Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Card Rasio Jenis Kelamin -->
      <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8">
        <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
          RASIO JENIS KELAMIN
        </div>
        <div class="font-mono text-[32px] font-bold text-primary tracking-tight">
          <?php echo esc_html($data['rasioJenisKelamin']); ?>
        </div>
      </div>

      <!-- Card Total Rumah Tangga -->
      <div class="bg-[#F0EEE7] rounded-card p-6 md:p-8">
        <div class="font-sans text-[13px] text-body-secondary uppercase tracking-wider mb-4">
          TOTAL RUMAH TANGGA
        </div>
        <div class="font-mono text-[32px] font-bold text-primary tracking-tight">
          <?php echo esc_html($data['jumlahRumahTangga']); ?>
        </div>
      </div>
    </div>
  </div>
</section>
