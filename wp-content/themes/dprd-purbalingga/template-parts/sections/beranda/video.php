<?php
/**
 * Template Part: Berita Video Section (Beranda)
 * Mengambil 6 video terbaru dari channel YouTube DPRD Purbalingga
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

// URL RSS Feed YouTube DPRD Purbalingga
$youtube_rss_url = 'https://www.youtube.com/feeds/videos.xml?channel_id=UCByfFzvHFKwaltuYFvJ1lGQ';
$videos = [];

// WordPress built-in RSS fetcher
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed($youtube_rss_url);

if (!is_wp_error($rss)) {
    // Batasi maksimum 6 item
    $maxitems = $rss->get_item_quantity(6);
    $rss_items = $rss->get_items(0, $maxitems);
    
    foreach ($rss_items as $item) {
        $link = $item->get_link();
        // Ekstrak video ID dari URL
        parse_str(parse_url($link, PHP_URL_QUERY), $query_params);
        $video_id = isset($query_params['v']) ? $query_params['v'] : '';
        
        // Coba ekstrak dari SimplePie item jika parsing link gagal
        if (!$video_id) {
            $yt_id_array = $item->get_item_tags('http://www.youtube.com/xml/schemas/2015', 'videoId');
            if (is_array($yt_id_array) && isset($yt_id_array[0]['data'])) {
                $video_id = $yt_id_array[0]['data'];
            }
        }

        if ($video_id) {
            $videos[] = [
                'id' => $video_id,
                'title' => $item->get_title()
            ];
        }
    }
}

if (empty($videos)) {
    // Fallback jika tidak dapat mengambil dari feed, ambil dari post type 'video' jika ada
    $fallback_query = new WP_Query([
        'post_type' => 'video',
        'posts_per_page' => 6,
        'post_status' => 'publish'
    ]);

    if ($fallback_query->have_posts()) {
        while ($fallback_query->have_posts()) {
            $fallback_query->the_post();
            // Asumsi field sederhana / meta 'youtube_id'
            $video_id = get_post_meta(get_the_ID(), 'youtube_id', true);
            if ($video_id) {
                $videos[] = [
                    'id' => $video_id,
                    'title' => get_the_title()
                ];
            }
        }
        wp_reset_postdata();
    }
}

if (empty($videos)) {
    return; // Sembunyikan section jika tidak ada video sama sekali
}
?>

<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
    <div class="text-center mb-12 flex flex-col items-center">
        <h2 class="font-montserrat text-2xl sm:text-3xl font-bold text-body">
            Berita Video
        </h2>
        <div class="w-24 h-1 bg-primary mt-4"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-12">
        <?php foreach ($videos as $video) : ?>
            <div class="group flex flex-col gap-4">
                <div class="relative w-full aspect-video rounded-card overflow-hidden bg-surface">
                    <iframe
                        src="https://www.youtube.com/embed/<?php echo esc_attr($video['id']); ?>"
                        title="<?php echo esc_attr($video['title']); ?>"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen
                        class="absolute top-0 left-0 w-full h-full border-0"
                    ></iframe>
                </div>
                <div class="flex flex-col gap-1.5 px-1">
                    <span class="text-primary font-sans text-[13px] font-bold">BERITA VIDEO</span>
                    <a href="https://www.youtube.com/watch?v=<?php echo esc_attr($video['id']); ?>" target="_blank" rel="noopener noreferrer">
                        <h3 class="font-display font-medium text-body text-[15px] sm:text-base leading-snug hover:text-primary transition-colors line-clamp-3">
                            <?php echo esc_html($video['title']); ?>
                        </h3>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
