<?php
/**
 * Table of Contents Sidebar untuk Sekilas Tentang Purbalingga
 */
if (!defined('ABSPATH')) exit;

$sections = [
  ['id' => 'letak-geografis', 'title' => 'Letak Geografis'],
  ['id' => 'luas-wilayah', 'title' => 'Luas Wilayah'],
  ['id' => 'topografi-tanah', 'title' => 'Topografi & Tanah'],
  ['id' => 'hidrologi', 'title' => 'Hidrologi'],
  ['id' => 'pemerintahan', 'title' => 'Pemerintahan'],
  ['id' => 'kepegawaian', 'title' => 'Kepegawaian'],
  ['id' => 'kependudukan', 'title' => 'Kependudukan'],
  ['id' => 'sosial-fasilitas', 'title' => 'Sosial & Fasilitas']
];
?>
<div class="sticky top-28 w-full">
  <ul class="flex flex-col gap-1 border-l border-line pl-4" id="dprd-toc-list">
    <?php foreach ($sections as $index => $section) : ?>
      <li>
        <a
          href="#<?php echo esc_attr($section['id']); ?>"
          data-target="<?php echo esc_attr($section['id']); ?>"
          class="dprd-toc-link block py-1.5 text-[14px] transition-colors <?php echo $index === 0 ? 'font-bold text-primary' : 'text-body hover:text-primary'; ?>"
        >
          <?php echo esc_html($section['title']); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

