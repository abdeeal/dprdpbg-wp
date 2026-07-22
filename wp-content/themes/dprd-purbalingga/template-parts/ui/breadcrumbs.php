<?php
/**
 * Breadcrumbs UI template part
 */
if (!defined('ABSPATH')) exit;

// Fungsi pembantu untuk merender chevron separator
if (!function_exists('dprd_breadcrumb_separator')) {
    function dprd_breadcrumb_separator() {
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right text-body-secondary opacity-60 mt-[1px] shrink-0" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>';
    }
}

// 1. Jika custom items dikirimkan via $args['items']
if (!empty($args['items']) && is_array($args['items'])) {
    $items = $args['items'];
} else {
    // 2. Otomatisasi breadcrumbs berdasarkan konteks WordPress
    $items = [
        ['label' => 'Beranda', 'href' => home_url('/')]
    ];

    if (is_post_type_archive('alat-kelengkapan')) {
        $items[] = ['label' => 'Profil DPRD'];
    } elseif (is_archive()) {
        $items[] = ['label' => post_type_archive_title('', false)];
    } elseif (is_single()) {
        $post_type     = get_post_type();
        $post_type_obj = get_post_type_object($post_type);

        if ($post_type === 'alat-kelengkapan') {
            $items[] = ['label' => 'Profil DPRD', 'href' => home_url('/profil-dprd/')];
        } elseif ($post_type_obj && get_post_type_archive_link($post_type)) {
            $items[] = ['label' => $post_type_obj->labels->name, 'href' => get_post_type_archive_link($post_type)];
        }
        $items[] = ['label' => get_the_title()];
    } elseif (is_page()) {
        $ancestors = [];
        $post_id   = get_the_ID();
        $ancestor  = wp_get_post_parent_id($post_id);
        while ($ancestor) {
            array_unshift($ancestors, $ancestor);
            $ancestor = wp_get_post_parent_id($ancestor);
        }
        foreach ($ancestors as $ancestor_id) {
            $items[] = ['label' => get_the_title($ancestor_id), 'href' => get_permalink($ancestor_id)];
        }
        $items[] = ['label' => get_the_title()];
    }
}
?>
<nav class="flex items-center gap-2 font-mono text-[13px] text-body-secondary mb-6 flex-nowrap w-full overflow-hidden" aria-label="Breadcrumb">
    <?php foreach ($items as $i => $item) : 
        $is_last = ($i === count($items) - 1);
        if ($i > 0) {
            dprd_breadcrumb_separator();
        }
        if (!$is_last && !empty($item['href'])) : ?>
            <a class="hover:text-primary transition-colors truncate shrink min-w-0" href="<?php echo esc_url($item['href']); ?>">
                <?php echo esc_html($item['label']); ?>
            </a>
        <?php else : ?>
            <span class="truncate shrink min-w-0 font-bold text-primary">
                <?php echo esc_html($item['label']); ?>
            </span>
        <?php endif;
    endforeach; ?>
</nav>
