<?php
/**
 * Template part for displaying single news content and sidebar (Fase 4 & 5)
 */
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$title = get_the_title();

// Metadata Berita
$day = get_post_meta($post_id, 'day', true);
if (empty($day)) {
    $day = get_the_date('l, d M Y', $post_id);
}
$time = get_post_meta($post_id, 'time', true);
$author = get_post_meta($post_id, 'author', true);
$image_caption = get_post_meta($post_id, 'imageCaption', true);

// Gambar Utama
$img_url = get_the_post_thumbnail_url($post_id, 'large');
if (empty($img_url)) {
    $img_url = get_template_directory_uri() . '/assets/images/default-berita.jpg'; // fallback
}

// Konten Berita dengan Dropcap otomatis pada huruf pertama (Fase 4 - Kustom)
$content = get_the_content();
$content = apply_filters('the_content', $content);
$content = trim($content);
$content = preg_replace('/(<p[^>]*>\s*(?:<[a-zA-Z0-9]+[^>]*>\s*|[“"\'‘\(\[])*)([A-Za-z\p{L}])/u', '$1<span class="dropcap">$2</span>', $content, 1);

// Sisipkan Foto-Foto Tambahan & Kutipan di Tengah Paragraf (Kustom)
$additional_images = get_dprd_repeater($post_id, 'dprd_berita_images_json');
$additional_quotes = get_dprd_repeater($post_id, 'dprd_berita_quotes_json');

// Fallback jika repeater foto kosong tapi field lama berisi data
if (empty($additional_images)) {
    $old_img_id = get_post_meta($post_id, 'additional_image_id', true);
    $old_caption = get_post_meta($post_id, 'additional_image_caption', true);
    $old_para = get_post_meta($post_id, 'additional_image_paragraph', true);
    if ($old_img_id && $old_para > 0) {
        $additional_images[] = [
            'image_id'  => $old_img_id,
            'caption'   => $old_caption,
            'paragraph' => $old_para
        ];
    }
}

// Fallback jika repeater kutipan kosong tapi field lama berisi data
if (empty($additional_quotes)) {
    $quote_text = get_post_meta($post_id, 'dprd_quote_text', true);
    $quote_paragraph = get_post_meta($post_id, 'dprd_quote_paragraph', true);
    if (!empty($quote_text) && $quote_paragraph > 0) {
        $additional_quotes[] = [
            'quote_text' => $quote_text,
            'paragraph'  => $quote_paragraph
        ];
    }
}

if (!empty($additional_images) || !empty($additional_quotes)) {
    // Pisahkan konten berdasarkan tag penutup paragraf </p>
    $paragraphs = explode('</p>', $content);
    $num_paragraphs = count($paragraphs) - 1;

    // Kelompokkan HTML sisipan berdasarkan paragraph target
    $inserts = [];

    // 1. Gambar Tambahan
    foreach ($additional_images as $img) {
        $img_id = isset($img['image_id']) ? intval($img['image_id']) : 0;
        $paragraph_idx = isset($img['paragraph']) ? intval($img['paragraph']) : 0;
        $caption = isset($img['caption']) ? $img['caption'] : '';

        if ($img_id && $paragraph_idx > 0) {
            $add_img_url = wp_get_attachment_image_url($img_id, 'large');
            if ($add_img_url) {
                $image_html = '
                <figure class="my-10 w-full">
                    <div class="relative w-full aspect-[16/9] overflow-hidden rounded-card mb-3">
                        <img src="' . esc_url($add_img_url) . '" class="object-cover w-full h-full" alt="Foto Tambahan" />
                    </div>';
                if (!empty($caption)) {
                    $image_html .= '<figcaption class="text-center font-sans text-xs md:text-[13px] text-body-secondary">' . esc_html($caption) . '</figcaption>';
                }
                $image_html .= '</figure>';

                $inserts[$paragraph_idx][] = $image_html;
            }
        }
    }

    // 2. Kutipan / Blockquote (Repeater)
    foreach ($additional_quotes as $qt) {
        $q_text = isset($qt['quote_text']) ? trim($qt['quote_text']) : '';
        $q_para = isset($qt['paragraph']) ? intval($qt['paragraph']) : 0;
        if (!empty($q_text) && $q_para > 0) {
            $quote_html = '
            <blockquote class="wp-block-quote">
                <p>"' . esc_html($q_text) . '"</p>
            </blockquote>';
            $inserts[$q_para][] = $quote_html;
        }
    }

    // Sisipkan ke array paragraphs
    foreach ($inserts as $p_idx => $html_list) {
        $combined_html = implode('', $html_list);
        if ($p_idx > 0 && $p_idx <= $num_paragraphs) {
            $paragraphs[$p_idx] = $combined_html . $paragraphs[$p_idx];
        } else {
            $last_idx = count($paragraphs) - 1;
            $paragraphs[$last_idx] .= $combined_html;
        }
    }

    $content = implode('</p>', $paragraphs);
}

// Ambil Berita Terbaru untuk Sidebar (mengabaikan berita yang sedang aktif)
$recent_news_posts = get_posts([
    'post_type'      => 'berita',
    'posts_per_page' => 3,
    'post__not_in'   => [$post_id],
    'orderby'        => 'date',
    'order'          => 'DESC'
]);
?>

<div class="flex flex-col lg:flex-row gap-12 mt-8">
    
    <!-- Main Content Column -->
    <article class="lg:w-[70%]">
        
        <!-- Header Artikel -->
        <header class="mb-8 w-full">
            <h1 class="font-display text-3xl md:text-4xl lg:text-[42px] font-bold text-body leading-tight mb-6">
                <?php echo esc_html($title); ?>
            </h1>
            
            <div class="flex flex-wrap items-center gap-4 text-xs md:text-[13px] font-mono text-body-secondary">
                <div class="flex items-center gap-2">
                    <!-- Calendar Icon -->
                    <svg class="w-4 h-4 text-body-secondary" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span><?php echo esc_html($day); ?><?php echo !empty($time) ? ' • ' . esc_html($time) : ''; ?></span>
                </div>
                
                <?php if (!empty($author)) : ?>
                    <span class="opacity-50 text-[10px]">•</span>
                    <div class="flex items-center gap-2">
                        <!-- User Icon -->
                        <svg class="w-4 h-4 text-body-secondary" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span><?php echo esc_html($author); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Gambar Utama & Caption -->
        <figure class="mb-10 w-full">
            <div class="relative w-full aspect-[16/9] overflow-hidden rounded-card mb-3">
                <img 
                    src="<?php echo esc_url($img_url); ?>" 
                    alt="<?php echo esc_attr($title); ?>"
                    class="object-cover w-full h-full"
                />
            </div>
            <?php if (!empty($image_caption)) : ?>
                <figcaption class="text-center font-sans text-xs md:text-[13px] text-body-secondary">
                    <?php echo esc_html($image_caption); ?>
                </figcaption>
            <?php endif; ?>
        </figure>

        <!-- Isi Artikel (Content) -->
        <div class="w-full">
            <div class="artikel-content font-sans text-[15px] md:text-base text-body leading-relaxed md:leading-[1.8]">
                <?php echo $content; ?>
            </div>
            
            <style>
                .artikel-content p {
                    margin-bottom: 1.5rem;
                }
                .artikel-content .dropcap {
                    float: left;
                    font-family: var(--font-fraunces), serif;
                    font-size: 4rem;
                    line-height: 0.8;
                    font-weight: 700;
                    color: #1E211D;
                    margin-right: 0.5rem;
                    margin-top: 0.2rem;
                }
                .artikel-content blockquote {
                    border-left: 4px solid #A32B2E;
                    padding-left: 1.5rem;
                    margin: 2.5rem 0;
                    font-family: var(--font-fraunces), serif;
                    font-size: 1.25rem;
                    font-style: italic;
                    color: #251818;
                    line-height: 1.6;
                }
                .artikel-content blockquote p {
                    margin-bottom: 0 !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-style: inherit !important;
                    color: inherit !important;
                    line-height: inherit !important;
                }
            </style>

            <!-- Footer Artikel: Tags & Share -->
            <div class="mt-12 flex flex-col sm:flex-row sm:items-center justify-between border-t border-line pt-6 gap-6">
                <div class="flex items-center flex-wrap gap-3">
                    <?php
                    $tags = get_the_tags();
                    if ($tags && !is_wp_error($tags)) : ?>
                        <span class="font-bold text-primary text-xs uppercase tracking-widest">Tags:</span>
                        <?php foreach ($tags as $tag) : ?>
                            <span class="bg-surface text-body-secondary text-xs px-3 py-1.5 rounded-badge font-medium">
                                <?php echo esc_html($tag->name); ?>
                            </span>
                        <?php endforeach;
                    endif; ?>
                </div>
                
                <!-- Share Button -->
                <button onclick="window.dprdShareUrl()" class="flex items-center gap-2 text-body hover:text-primary transition-colors text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l4.828-2.414m0 0a3 3 0 10-3-3m3 3a3 3 0 103 5.414l-4.828 2.414m0 0a3 3 0 103 3.414M12 17h.01" />
                    </svg>
                    Bagikan
                </button>
            </div>
        </div>

    </article>

    <!-- Sidebar Column -->
    <aside class="lg:w-[30%]">
        <div class="w-full flex flex-col gap-6">
            
            <!-- Header Sidebar -->
            <div class="border-b border-primary/30 pb-3 mb-2">
                <h2 class="font-montserrat font-bold text-xl text-primary">
                    Update Berita Serupa
                </h2>
            </div>

            <!-- List of News -->
            <div class="flex flex-col gap-6">
                <?php foreach ($recent_news_posts as $r_post) : 
                    $r_post_id = $r_post->ID;
                    $r_title = get_the_title($r_post_id);
                    $r_day = get_post_meta($r_post_id, 'day', true);
                    if (empty($r_day)) {
                        $r_day = get_the_date('d M Y', $r_post_id);
                    }
                    $r_img = get_the_post_thumbnail_url($r_post_id, 'thumbnail');
                    if (empty($r_img)) {
                        $r_img = get_template_directory_uri() . '/assets/images/default-berita.jpg';
                    }
                    $r_url = get_permalink($r_post_id);
                    ?>
                    <a href="<?php echo esc_url($r_url); ?>" class="group flex gap-4 items-center">
                        <div class="relative w-[70px] h-[70px] shrink-0 rounded-md overflow-hidden bg-surface">
                            <img 
                                src="<?php echo esc_url($r_img); ?>" 
                                alt="<?php echo esc_attr($r_title); ?>"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        <div class="flex flex-col justify-center">
                            <h3 class="font-sans font-semibold text-body text-sm leading-snug group-hover:text-primary transition-colors line-clamp-2 mb-1">
                                <?php echo esc_html($r_title); ?>
                            </h3>
                            <span class="font-mono text-[11px] text-body-secondary">
                                <?php echo esc_html($r_day); ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Button -->
            <a href="<?php echo esc_url(home_url('/berita')); ?>" class="mt-4 w-full block text-center text-primary font-mono font-bold text-sm py-3 rounded-button border border-line hover:border-primary hover:bg-primary-light transition-colors">
                Lihat Semua Berita
            </a>
            
        </div>
    </aside>

</div>

<script>
window.dprdShareUrl = function() {
    if (navigator.share) {
        navigator.share({
            title: <?php echo json_encode($title); ?>,
            url: window.location.href
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan berita berhasil disalin ke papan klip (clipboard)!');
    }
};
</script>
