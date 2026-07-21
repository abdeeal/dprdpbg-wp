<?php
header('Content-Type: text/plain');
echo "=== WEB PHP GD CHECK ===\n";
if (function_exists('gd_info')) {
    print_r(gd_info());
} else {
    echo "GD is NOT loaded in Web PHP!\n";
}

echo "\n=== IMAGICK CHECK ===\n";
if (class_exists('Imagick')) {
    echo "Imagick is LOADED in Web PHP.\n";
    $im = new Imagick();
    $formats = $im->queryFormats('WEBP');
    echo "Imagick WebP support: " . (in_array('WEBP', $formats) ? "YES" : "NO") . "\n";
} else {
    echo "Imagick is NOT loaded in Web PHP.\n";
}
