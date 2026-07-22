<?php
define('WP_USE_THEMES', false);
require('wp-load.php');

$youtube_rss_url = 'https://www.youtube.com/feeds/videos.xml?channel_id=UCByfFzvHFKwaltuYFvJ1lGQ';
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed($youtube_rss_url);

if (!is_wp_error($rss)) {
    $maxitems = $rss->get_item_quantity(6);
    $rss_items = $rss->get_items(0, $maxitems);
    
    foreach ($rss_items as $item) {
        $link = $item->get_link();
        parse_str(parse_url($link, PHP_URL_QUERY), $query_params);
        $video_id = isset($query_params['v']) ? $query_params['v'] : '';
        
        if (!$video_id) {
            $yt_id_array = $item->get_item_tags('http://www.youtube.com/xml/schemas/2015', 'videoId');
            if (is_array($yt_id_array) && isset($yt_id_array[0]['data'])) {
                $video_id = $yt_id_array[0]['data'];
            }
        }
        echo "Link: " . $link . " | ID: " . $video_id . "\n";
    }
} else {
    echo "RSS Error: " . $rss->get_error_message();
}
