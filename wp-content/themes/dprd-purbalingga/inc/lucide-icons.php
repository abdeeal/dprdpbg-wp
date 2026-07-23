<?php
/**
 * Lucide Icons Helper for DPRD Purbalingga Theme
 * Renders inline SVG icons with simple helper function
 */

if (!defined('ABSPATH')) exit;

function dprd_get_lucide_svg($icon, $size = 24, $class = '') {
    $icon = strtolower($icon);
    
    $svgs = [
        'gavel' => '<path d="m14 5-3 3 2 2 3-3-2-2z"/><path d="m16 10 3-3 2 2-3 3-2-2z"/><path d="m20 2 2 2-12 12-2-2L20 2z"/><path d="m10 14-5 5-2-2 5-5 2 2z"/><path d="M2 21h12"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'file-text' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
        'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>',
        'calendar-days' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>',
        'file-check' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="m9 15 2 2 4-4"/>',
        'scale' => '<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h18"/>',
        'wallet' => '<path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>',
        'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
        'search' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.34-4.34"/>',
        'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    ];

    $path = isset($svgs[$icon]) ? $svgs[$icon] : '';
    if (!$path) {
        $path = '<circle cx="12" cy="12" r="10"/>';
    }

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-%s %s">%s</svg>',
        $size,
        $size,
        esc_attr($icon),
        esc_attr($class),
        $path
    );
}

/**
 * Echoes the lucide SVG directly.
 */
function dprd_icon($icon, $class = '') {
    echo dprd_get_lucide_svg($icon, 24, $class);
}
