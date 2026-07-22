<?php
/**
 * Propemperda Archive List template part
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

$query = new WP_Query([
    'post_type'      => 'propemperda',
    'posts_per_page' => -1,
    'meta_key'       => 'tahun',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC'
]);

if (!$query->have_posts()) {
    // Fallback jika belum ada meta_key tahun terisi
    $query = new WP_Query([
        'post_type'      => 'propemperda',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);
}

if (!$query->have_posts()) {
    echo '<p class="text-body-secondary font-sans">Belum ada dokumen Propemperda yang terdaftar.</p>';
    return;
}
?>

<div class="w-full flex flex-col border-t border-[#A32B2E]/40 mt-12">
    <?php $index = 0; ?>
    <?php while ($query->have_posts()) : $query->the_post(); 
        $tahun = get_post_meta(get_the_ID(), 'tahun', true);
        $deskripsi = get_post_meta(get_the_ID(), 'deskripsi', true);
        $propemperda_file = get_post_meta(get_the_ID(), 'propemperda_file', true);
        $sk_penetapan_file = get_post_meta(get_the_ID(), 'sk_penetapan_file', true);
        
        $post_title = get_the_title();
        $display_title = $post_title ? $post_title : 'Tahun ' . $tahun;
        ?>
        <div class="border-b border-[#A32B2E]/40 last:border-b-0 py-6 md:py-8 dprd-accordion-item" data-id="<?php echo esc_attr($tahun); ?>">
            <button class="w-full flex items-start justify-between text-left group cursor-pointer dprd-accordion-toggle">
                <div class="flex flex-col gap-1.5">
                    <h3 class="font-display font-bold text-xl md:text-[22px] text-body group-hover:text-primary transition-colors">
                        <?php echo esc_html($display_title); ?>
                    </h3>
                    <?php if ($deskripsi) : ?>
                        <p class="font-sans text-[14px] md:text-[15px] text-body-secondary">
                            <?php echo esc_html($deskripsi); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="text-primary shrink-0 ml-4 pt-1 dprd-accordion-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-left transition-transform" aria-hidden="true"><path d="M17 7 7 17"></path><path d="M17 17H7V7"></path></svg>
                </div>
            </button>
            <div class="dprd-accordion-content overflow-hidden">
                <div class="pt-6 flex flex-col gap-3">
                    <?php
                    $has_file = false;

                    if ($propemperda_file) :
                        $has_file = true;
                        $tahun_label = $tahun ? $tahun : date('Y');
                        $label_perda = 'Propemperda Kabupaten Purbalingga Tahun ' . $tahun_label;
                        $proxy_perda = dprd_proxy_url(get_the_ID(), $propemperda_file, $label_perda);
                    ?>
                        <a class="font-mono text-[13px] md:text-sm text-primary hover:text-primary/80 underline underline-offset-4 decoration-primary/40 hover:decoration-primary transition-all w-fit"
                           href="<?php echo esc_url($proxy_perda); ?>"
                           target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($label_perda); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($sk_penetapan_file) :
                        $has_file = true;
                        $tahun_label = $tahun ? $tahun : date('Y');
                        $label_sk   = 'SK Penetapan Propemperda Tahun ' . $tahun_label;
                        $proxy_sk   = dprd_proxy_url(get_the_ID(), $sk_penetapan_file, $label_sk);
                    ?>
                        <a class="font-mono text-[13px] md:text-sm text-primary hover:text-primary/80 underline underline-offset-4 decoration-primary/40 hover:decoration-primary transition-all w-fit"
                           href="<?php echo esc_url($proxy_sk); ?>"
                           target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($label_sk); ?>
                        </a>
                    <?php endif; ?>

                    <?php if (!$has_file) : ?>
                        <p class="font-mono text-xs text-body-secondary italic">Belum ada berkas PDF yang diunggah untuk tahun ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php $index++; ?>
    <?php endwhile; wp_reset_postdata(); ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.dprd-accordion-item');
    const urlParams = new URLSearchParams(window.location.search);
    const activeId = urlParams.get('id');
    
    let isAnyOpened = false;

    items.forEach((item, index) => {
        const toggle = item.querySelector('.dprd-accordion-toggle');
        const content = item.querySelector('.dprd-accordion-content');
        const iconContainer = item.querySelector('.dprd-accordion-icon');
        const itemId = item.getAttribute('data-id');
        
        content.style.transition = 'max-height 0.4s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.4s cubic-bezier(0.25, 1, 0.5, 1)';

        let shouldOpen = false;
        if (activeId && activeId === itemId) {
            shouldOpen = true;
            isAnyOpened = true;
        } else if (!activeId && index === 0) {
            shouldOpen = true;
        }

        if (shouldOpen) {
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
            content.classList.add('is-open');
            iconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true"><path d="M7 17 17 7"></path><path d="M7 7h10v10"></path></svg>`;
            
            if (activeId) {
                setTimeout(() => {
                    item.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        } else {
            content.style.maxHeight = '0px';
            content.style.opacity = '0';
        }

        toggle.addEventListener('click', () => {
            const isOpen = content.classList.contains('is-open');
            
            // Tutup semua accordion
            items.forEach(otherItem => {
                const otherContent = otherItem.querySelector('.dprd-accordion-content');
                const otherIcon = otherItem.querySelector('.dprd-accordion-icon');
                if (otherContent) {
                    otherContent.style.maxHeight = '0px';
                    otherContent.style.opacity = '0';
                    otherContent.classList.remove('is-open');
                }
                if (otherIcon) {
                    otherIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-left" aria-hidden="true"><path d="M17 7 7 17"></path><path d="M17 17H7V7"></path></svg>`;
                }
            });
            
            // Buka yang di-klik jika sebelumnya tertutup
            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                content.classList.add('is-open');
                iconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true"><path d="M7 17 17 7"></path><path d="M7 7h10v10"></path></svg>`;
            }
        });
    });
    
    // Fallback jika ID dari URL tidak ditemukan di item manapun
    if (activeId && !isAnyOpened && items.length > 0) {
        const firstContent = items[0].querySelector('.dprd-accordion-content');
        const firstIconContainer = items[0].querySelector('.dprd-accordion-icon');
        firstContent.style.maxHeight = firstContent.scrollHeight + 'px';
        firstContent.style.opacity = '1';
        firstContent.classList.add('is-open');
        firstIconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true"><path d="M7 17 17 7"></path><path d="M7 7h10v10"></path></svg>`;
    }
});
</script>
