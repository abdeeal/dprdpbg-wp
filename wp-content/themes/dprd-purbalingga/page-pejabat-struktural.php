<?php
/**
 * Template Name: Pejabat Struktural Sekretariat DPRD
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();

$breadcrumbs = [
    ['label' => 'Beranda', 'href' => home_url('/')],
    ['label' => 'Sekretariat DPRD', 'href' => home_url('/sekretariat-dprd')],
    ['label' => 'Struktur Organisasi', 'href' => home_url('/sekretariat-dprd/struktur-organisasi')],
    ['label' => 'Pejabat Struktural'],
];

// Ambil data JSON pejabat struktural
$raw_json = get_option('dprd_pejabat_struktural_json', '[]');
$pejabat_tree = json_decode($raw_json, true);
if (!is_array($pejabat_tree)) $pejabat_tree = [];

/**
 * Helper untuk merender node pejabat dan anak-anaknya secara rekursif (berdasarkan kepemilikan/hierarki)
 */
function dprd_render_pejabat_node($node, $level = 0) {
    if (!is_array($node)) return;

    $anggota_id = isset($node['anggota_id']) ? intval($node['anggota_id']) : 0;
    $name = $anggota_id ? get_the_title($anggota_id) : '—';
    $position = isset($node['jabatan']) ? $node['jabatan'] : '';

    $foto_diri = $anggota_id ? get_post_meta($anggota_id, 'foto_diri', true) : 0;
    $image_url = $foto_diri ? wp_get_attachment_image_url(intval($foto_diri), 'large') : '';
    if (empty($image_url)) {
        $image_url = get_template_directory_uri() . '/assets/images/placeholder/avatar.png';
    }

    $children = isset($node['children']) && is_array($node['children']) ? $node['children'] : [];
    ?>
    <div class="flex flex-col items-center w-full mb-12 last:mb-0">
        <!-- Card Pejabat -->
        <div class="w-full sm:w-[calc(50%-1.5rem)] md:w-[calc(33.333%-2.5rem)] max-w-[280px] flex flex-col items-center">
            <!-- Nama Anggota/Pejabat -->
            <h3 class="font-display text-lg md:text-[19px] font-normal text-body text-center mb-3 leading-snug h-[2.6em] min-h-[2.6em] flex items-center justify-center">
                <?php echo esc_html($name); ?>
            </h3>

            <!-- Divider line -->
            <div class="w-full h-px bg-body/30 mb-3"></div>

            <!-- Jabatan -->
            <span class="font-display text-sm md:text-[15px] mb-4 text-center text-body-secondary">
                <?php echo esc_html($position); ?>
            </span>

            <!-- Foto Diri (3:4 Ratio) -->
            <div class="relative w-full aspect-[3/4] rounded-sm overflow-hidden bg-surface shadow-sm border border-line/40">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" class="object-cover w-full h-full" loading="lazy">
                <?php endif; ?>
            </div>
        </div>

        <!-- Render Sub-Jabatan / Children (Ditaruh sejajar langsung di bawahnya tanpa divider) -->
        <?php if (!empty($children)) : ?>
            <div class="w-full flex flex-wrap justify-center gap-x-6 gap-y-10 sm:gap-x-8 md:gap-x-12 mt-12">
                <?php foreach ($children as $child) {
                    dprd_render_pejabat_node($child, $level + 1);
                } ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">

        <!-- Breadcrumbs -->
        <div class="mb-6 md:mb-8">
            <?php get_template_part('template-parts/ui/breadcrumbs', null, ['items' => $breadcrumbs]); ?>
        </div>

        <!-- Page Header -->
        <div class="mb-10 w-full text-left dprd-fade-in" data-direction="up" data-duration="0.6">
            <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2 leading-tight">
                Pejabat Struktural
            </h1>
            <p class="font-mono text-xs md:text-sm text-body-secondary tracking-widest uppercase">
                Sekretariat DPRD Kabupaten Purbalingga
            </p>
        </div>

        <!-- Members Hierarchical Tree Section -->
        <?php if (!empty($pejabat_tree)) : ?>
            <div class="flex flex-col items-center mt-12 mb-16 max-w-5xl mx-auto w-full dprd-fade-in" data-direction="up" data-duration="0.8">
                <?php foreach ($pejabat_tree as $root_node) {
                    dprd_render_pejabat_node($root_node, 0);
                } ?>
            </div>
        <?php else: ?>
            <div class="max-w-4xl mx-auto py-12 text-center">
                <p class="font-sans text-body-secondary">Belum ada data Pejabat Struktural yang diatur.</p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
