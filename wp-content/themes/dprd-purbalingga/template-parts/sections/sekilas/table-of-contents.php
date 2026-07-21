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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tocLinks = document.querySelectorAll('.dprd-toc-link');
    var sections = Array.from(tocLinks).map(function(link) {
        return document.getElementById(link.getAttribute('data-target'));
    }).filter(Boolean);

    function updateActiveToc() {
        var current = '';
        var scrollPos = window.scrollY || window.pageYOffset;
        
        // Cari section yang aktif di layar
        for (var i = sections.length - 1; i >= 0; i--) {
            var section = sections[i];
            var rect = section.getBoundingClientRect();
            if (rect.top <= 150) {
                current = section.getAttribute('id');
                break;
            }
        }
        if (!current && sections.length > 0) {
            current = sections[0].getAttribute('id');
        }

        tocLinks.forEach(function(link) {
            link.classList.remove('font-bold', 'text-primary');
            if (link.getAttribute('data-target') === current) {
                link.classList.add('font-bold', 'text-primary');
                link.classList.remove('text-body', 'hover:text-primary');
            } else {
                link.classList.add('text-body', 'hover:text-primary');
            }
        });
    }

    // Scroll click handler
    tocLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var targetId = this.getAttribute('data-target');
            var element = document.getElementById(targetId);
            if (element) {
                var y = element.getBoundingClientRect().top + window.pageYOffset - 100;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        });
    });

    window.addEventListener('scroll', updateActiveToc, { passive: true });
    updateActiveToc();
});
</script>
