<?php
/**
 * Template Name: PPID Page
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();

// Ambil semua pos CPT PPID terurut kronologis (sesuai urutan di Vercel: SK PPID paling atas)
$ppid_query = new WP_Query([
    'post_type'      => 'ppid',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
]);
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        
        <!-- Breadcrumbs -->
        <div class="mb-6 md:mb-8">
            <?php get_template_part('template-parts/ui/breadcrumbs'); ?>
        </div>

        <!-- Page Header -->
        <div class="text-left">
            <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2">
                PPID
            </h1>
            <p class="font-mono text-sm md:text-[15px] text-body-secondary tracking-wide">
                Pejabat Pengelola Informasi Dan Dokumentasi
            </p>
        </div>

        <!-- Accordion Container -->
        <div class="w-full flex flex-col border-t border-primary/40 mt-12">
            <?php if ($ppid_query->have_posts()) : 
                $count = 0;
                while ($ppid_query->have_posts()) : $ppid_query->the_post();
                    $count++;
                    $post_id     = get_the_ID();
                    $title       = get_the_title();
                    $description = get_post_meta($post_id, 'description', true);
                    $docs_json   = get_post_meta($post_id, 'documents_json', true);
                    $documents   = json_decode($docs_json, true) ?: [];
                    
                    // Default open item pertama (sk-ppid)
                    $is_open = ($count === 1);
                    ?>
                    <div class="dprd-accordion-item border-b border-primary/40 last:border-b-0 py-6 md:py-8" data-id="<?php echo esc_attr($post_id); ?>">
                        <button 
                            type="button"
                            class="dprd-accordion-btn w-full flex items-start justify-between text-left group cursor-pointer border-0 bg-transparent p-0 outline-none focus:outline-none"
                        >
                            <div class="flex flex-col gap-1.5">
                                <h3 class="dprd-accordion-title font-display font-bold text-xl md:text-[22px] text-body group-hover:text-primary transition-colors">
                                    <?php echo esc_html($title); ?>
                                </h3>
                                <?php if ($description) : ?>
                                    <p class="font-sans text-[14px] md:text-[15px] text-body-secondary">
                                        <?php echo esc_html($description); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="text-primary shrink-0 ml-4 pt-1">
                                <!-- Arrow Up Right (saat terbuka) -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="dprd-icon-open <?php echo $is_open ? '' : 'hidden'; ?>" aria-hidden="true">
                                    <path d="M7 17L17 7"/><path d="M7 7h10v10"/>
                                </svg>
                                <!-- Arrow Down Left (saat tertutup) -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="dprd-icon-closed <?php echo $is_open ? 'hidden' : ''; ?>" aria-hidden="true">
                                    <path d="M17 7L7 17"/><path d="M17 17H7V7"/>
                                </svg>
                            </div>
                        </button>

                        <!-- Accordion Content -->
                        <div class="dprd-accordion-content overflow-hidden">
                            <div class="pt-6 flex flex-col gap-3">
                                <?php if (!empty($documents)) : ?>
                                    <?php foreach ($documents as $doc) :
                                        $doc_url   = $doc['url'] ?? '#';
                                        $doc_title = $doc['title'] ?? 'dokumen';
                                        $href      = dprd_proxy_url($post_id, $doc_url, $doc_title);
                                        ?>
                                        <a
                                            href="<?php echo esc_url($href); ?>"
                                            class="font-mono text-[13px] md:text-sm text-primary hover:text-primary/80 underline underline-offset-4 decoration-primary/40 hover:decoration-primary transition-all w-fit"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <?php echo esc_html($doc_title); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="font-mono text-xs text-body-secondary italic">Belum ada dokumen yang diunggah.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            endif; ?>
        </div>

    </div>
</main>

<?php
get_footer();
