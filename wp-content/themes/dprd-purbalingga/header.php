<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-main text-ink font-sans antialiased'); ?>>
<?php wp_body_open(); ?>

<?php
// ── Ambil dan parse menu WordPress berdasarkan LOKASI (bukan nama) ───────────
$locations  = get_nav_menu_locations();
$menu_id    = $locations['primary'] ?? 0;
$menu_items = $menu_id ? wp_get_nav_menu_items($menu_id) : [];
$menu_tree  = [];


if ($menu_items) {
    $item_map = [];
    foreach ($menu_items as $item) {
        $item->children = [];
        $item_map[$item->ID] = $item;
    }
    foreach ($menu_items as $item) {
        if ($item->menu_item_parent && isset($item_map[$item->menu_item_parent])) {
            $item_map[$item->menu_item_parent]->children[] = &$item_map[$item->ID];
        } else {
            $menu_tree[] = &$item_map[$item->ID];
        }
    }
}

// Logo
$logo_url = get_template_directory_uri() . '/assets/images/logo-dprd-purbalingga.png';
?>

<header id="dprd-header" class="bg-white border-b border-line/50 sticky top-0 z-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 h-20 flex items-center justify-between">

        <!-- Kiri: Tombol Menu / Tutup -->
        <div class="flex items-center gap-3 flex-1">
            <button id="dprd-menu-toggle" class="flex items-center gap-2 cursor-pointer group border-0 bg-transparent outline-none focus:outline-none p-0" aria-expanded="false" aria-controls="dprd-megamenu" aria-label="Buka menu navigasi">
                <!-- Ikon Menu (hamburger) -->
                <span id="dprd-icon-menu" class="flex items-center gap-2 text-body group-hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/>
                    </svg>
                    <span class="font-mono font-medium text-[15px] hidden sm:block pt-0.5">Menu</span>
                </span>
                <!-- Ikon Tutup (×) -->
                <span id="dprd-icon-close" class="hidden items-center gap-2 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                    <span class="font-mono font-medium text-[15px] hidden sm:block pt-0.5">Tutup</span>
                </span>
            </button>
        </div>

        <!-- Tengah: Logo + Nama -->
        <a class="flex items-center gap-3 justify-center flex-1 transition-all duration-300" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Kembali ke Beranda DPRD Purbalingga">
            <div class="relative w-9 h-11 shrink-0 overflow-hidden" style="width:36px;height:44px;flex-shrink:0">
                <img src="<?php echo esc_url($logo_url); ?>" alt="Logo DPRD Purbalingga" style="width:36px;height:44px;object-fit:contain;display:block">
            </div>
            <span class="font-sans text-[17px] font-medium text-body tracking-wide pt-0.5 whitespace-nowrap">DPRD Purbalingga</span>
        </a>

        <!-- Kanan: Search + Reservasi -->
        <div class="flex items-center justify-end flex-1 gap-4 sm:gap-6">
            <button id="dprd-search-toggle" class="text-body hover:text-primary transition-colors border-0 bg-transparent outline-none focus:outline-none p-0" aria-label="Cari">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
                </svg>
            </button>
            <a class="hidden sm:block bg-primary text-white font-sans text-[13px] font-medium py-2.5 px-5 hover:bg-primary/90 transition-colors" href="<?php echo esc_url(home_url('/reservasi')); ?>">
                Reservasi Kunjungan
            </a>
        </div>
    </div>

    <!-- ── Overlay gelap saat menu terbuka (absolute, mulai tepat di bawah header) ── -->
    <div id="dprd-overlay" class="absolute top-full left-0 w-screen h-screen bg-ink/20 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-500 ease-out"></div>

    <!-- ── Mega Menu Panel (absolute top-full = tepat di bawah header bar) ────── -->
    <div id="dprd-megamenu"
         class="absolute top-full left-0 w-screen bg-white border-t border-b border-line/50 shadow-xl z-40 invisible opacity-0 transition-all duration-300 ease-out"
         aria-hidden="true">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 py-6 flex gap-0 min-h-[220px]">

        <!-- ── Kolom 1: Menu Level 1 ────────────────────────────────────── -->
        <?php
        // Temukan index pertama yang punya anak (untuk default active)
        $default_l1_index = null;
        foreach ($menu_tree as $i => $item) {
            if (!empty($item->children)) { $default_l1_index = $i; break; }
        }
        ?>
        <nav class="w-1/3 border-r border-line/40 pr-8 overflow-y-auto" aria-label="Menu utama">
            <ul class="flex flex-col gap-1 font-mono text-[14px] text-body">
                <?php foreach ($menu_tree as $i => $item) :
                    $has_children = !empty($item->children);
                    $is_default   = ($i === $default_l1_index);
                    ?>
                    <li>
                        <button
                            class="dprd-l1-item w-full flex items-center justify-between py-1.5 px-2 rounded-none text-left transition-colors hover:text-primary border-0 bg-transparent outline-none focus:outline-none cursor-pointer <?php echo $is_default ? 'dprd-active text-primary font-bold' : ''; ?>"
                            data-index="<?php echo esc_attr($i); ?>"
                            data-url="<?php echo esc_url($item->url); ?>"
                            data-has-children="<?php echo $has_children ? 'true' : 'false'; ?>"
                        >
                            <span><?php echo esc_html($item->title); ?></span>
                            <?php if ($has_children) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0 opacity-60" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            <?php endif; ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- ── Kolom 2: Sub-menu Level 2 ───────────────────────────────── -->
        <div class="w-1/3 border-r border-line/40 px-8 overflow-y-auto">
            <?php foreach ($menu_tree as $i => $item) :
                if (empty($item->children)) continue;
                $is_default_panel = ($i === $default_l1_index);
                ?>
                <nav id="dprd-l2-<?php echo esc_attr($i); ?>"
                     class="dprd-l2-panel <?php echo $is_default_panel ? '' : 'hidden'; ?>"
                     aria-label="Sub-menu <?php echo esc_attr($item->title); ?>">
                    <ul class="flex flex-col gap-1 font-mono text-[14px] text-body">
                        <?php foreach ($item->children as $j => $sub) :
                            $has_sub = !empty($sub->children);
                            ?>
                            <li>
                                <button
                                    class="dprd-l2-item w-full flex items-center justify-between py-1.5 px-2 rounded-none text-left transition-colors hover:text-primary border-0 bg-transparent outline-none focus:outline-none cursor-pointer <?php echo ($is_default_panel && $j === 0) ? 'dprd-active text-primary font-bold' : ''; ?>"
                                    data-parent="<?php echo esc_attr($i); ?>"
                                    data-index="<?php echo esc_attr($i . '-' . $j); ?>"
                                    data-url="<?php echo esc_url($sub->url); ?>"
                                    data-has-children="<?php echo $has_sub ? 'true' : 'false'; ?>"
                                >
                                    <span><?php echo esc_html($sub->title); ?></span>
                                    <?php if ($has_sub) : ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0 opacity-60" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    <?php endif; ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endforeach; ?>
        </div>

        <!-- ── Kolom 3: Sub-sub-menu Level 3 ───────────────────────────── -->
        <div class="w-1/3 pl-8 overflow-y-auto">
            <?php foreach ($menu_tree as $i => $item) :
                foreach ($item->children as $j => $sub) :
                    if (empty($sub->children)) continue;
                    $key = $i . '-' . $j;
                    ?>
                    <nav id="dprd-l3-<?php echo esc_attr($key); ?>"
                         class="dprd-l3-panel hidden"
                         aria-label="Sub-menu <?php echo esc_attr($sub->title); ?>">
                        <ul class="flex flex-col gap-1 font-mono text-[14px] text-body">
                            <?php foreach ($sub->children as $child) : ?>
                                <li>
                                    <a href="<?php echo esc_url($child->url); ?>"
                                       class="w-full flex items-center py-1.5 px-2 rounded-none transition-colors hover:text-primary">
                                        <?php echo esc_html($child->title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endforeach;
            endforeach; ?>
        </div>

    </div>
</div>
</header>
