<?php
/**
 * Breadcrumbs UI template part
 */
if (!defined('ABSPATH')) exit;

$post_type = get_post_type();
$post_type_obj = get_post_type_object($post_type);
$current_title = '';

if (is_archive()) {
    $current_title = post_type_archive_title('', false);
} elseif (is_single()) {
    $current_title = get_the_title();
} elseif (is_page()) {
    $current_title = get_the_title();
}
?>
<nav class="flex items-center gap-2 font-mono text-[13px] text-body-secondary mb-6 flex-nowrap w-full overflow-hidden">
    <a class="hover:text-primary transition-colors truncate shrink min-w-0" href="<?php echo esc_url(home_url('/')); ?>">Beranda</a>
    
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right text-body-secondary opacity-60 mt-[1px] shrink-0" aria-hidden="true">
        <path d="m9 18 6-6-6-6"></path>
    </svg>
    
    <?php if (is_single() && $post_type_obj) : ?>
        <a class="hover:text-primary transition-colors truncate shrink min-w-0" href="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>">
            <?php echo esc_html($post_type_obj->labels->name); ?>
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right text-body-secondary opacity-60 mt-[1px] shrink-0" aria-hidden="true">
            <path d="m9 18 6-6-6-6"></path>
        </svg>
    <?php endif; ?>
    
    <span class="truncate shrink min-w-0 font-bold text-primary"><?php echo esc_html($current_title); ?></span>
</nav>
