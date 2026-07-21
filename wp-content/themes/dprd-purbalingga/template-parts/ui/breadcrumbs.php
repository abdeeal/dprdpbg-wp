<?php
/**
 * Breadcrumbs UI template part
 */
if (!defined('ABSPATH')) exit;

// Fungsi pembantu untuk merender chevron separator
function dprd_breadcrumb_separator() {
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right text-body-secondary opacity-60 mt-[1px] shrink-0" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>';
}

// Kumpulkan ancestors jika halaman punya parent
$ancestors = [];
if (is_page()) {
    $post_id  = get_the_ID();
    $ancestor = wp_get_post_parent_id($post_id);
    while ($ancestor) {
        array_unshift($ancestors, $ancestor);
        $ancestor = wp_get_post_parent_id($ancestor);
    }
}
?>
<nav class="flex items-center gap-2 font-mono text-[13px] text-body-secondary mb-6 flex-nowrap w-full overflow-hidden" aria-label="Breadcrumb">

    <a class="hover:text-primary transition-colors truncate shrink min-w-0" href="<?php echo esc_url(home_url('/')); ?>">Beranda</a>

    <?php if (is_archive()) :
        // Halaman arsip CPT — hanya 2 level: Beranda > Nama Arsip
        dprd_breadcrumb_separator();
        echo '<span class="truncate shrink min-w-0 font-bold text-primary">' . esc_html(post_type_archive_title('', false)) . '</span>';

    elseif (is_single()) :
        // Halaman single post — Beranda > [Arsip CPT] > Judul Post
        $post_type     = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        if ($post_type_obj && get_post_type_archive_link($post_type)) {
            dprd_breadcrumb_separator();
            echo '<a class="hover:text-primary transition-colors truncate shrink min-w-0" href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . esc_html($post_type_obj->labels->name) . '</a>';
        }
        dprd_breadcrumb_separator();
        echo '<span class="truncate shrink min-w-0 font-bold text-primary">' . esc_html(get_the_title()) . '</span>';

    elseif (is_page()) :
        // Halaman statis — tampilkan semua ancestors (parent pages) lalu halaman saat ini
        foreach ($ancestors as $ancestor_id) :
            dprd_breadcrumb_separator();
            echo '<a class="hover:text-primary transition-colors truncate shrink min-w-0" href="' . esc_url(get_permalink($ancestor_id)) . '">' . esc_html(get_the_title($ancestor_id)) . '</a>';
        endforeach;
        dprd_breadcrumb_separator();
        echo '<span class="truncate shrink min-w-0 font-bold text-primary">' . esc_html(get_the_title()) . '</span>';
    endif; ?>

</nav>
