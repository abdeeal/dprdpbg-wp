<?php
/**
 * Template Name: Selayang Pandang Index Page
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <?php
        get_template_part('template-parts/ui/breadcrumbs');
        ?>
        
        <header class="mb-12 mt-6">
            <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2">Selayang Pandang</h1>
        </header>

        <div class="w-full flex flex-col border-t border-[#A32B2E]/20 mt-8">
            <?php
            $current_page_id = get_the_ID();
            $child_pages = get_pages([
                'child_of'    => $current_page_id,
                'sort_column' => 'menu_order',
                'sort_order'  => 'ASC',
            ]);

            if ($child_pages) :
                foreach ($child_pages as $page) :
                    ?>
                    <div class="border-b border-[#A32B2E]/20 last:border-b-0 py-6 md:py-8">
                        <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" class="w-full flex items-center justify-between text-left group cursor-pointer">
                            <h3 class="font-display font-bold text-xl md:text-[22px] text-body group-hover:text-primary transition-colors">
                                <?php echo esc_html($page->post_title); ?>
                            </h3>
                            <div class="text-primary shrink-0 ml-4 pt-1 transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true">
                                    <path d="M7 17 17 7"></path>
                                    <path d="M7 7h10v10"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                    <?php
                endforeach;
            else :
                echo '<p class="text-body-secondary font-sans">Belum ada submenu di bawah Selayang Pandang.</p>';
            endif;
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
