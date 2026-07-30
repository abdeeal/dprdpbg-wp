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
 * Helper rekursif untuk mengumpulkan node pejabat per depth level
 */
function dprd_flatten_pejabat_tree($nodes, $depth = 0, &$levels = []) {
    if (!is_array($nodes)) return;
    foreach ($nodes as $node) {
        $anggota_id = isset($node['anggota_id']) ? intval($node['anggota_id']) : 0;
        $name = $anggota_id ? get_the_title($anggota_id) : '—';
        $position = isset($node['jabatan']) ? $node['jabatan'] : '';

        $foto_diri = $anggota_id ? get_post_meta($anggota_id, 'foto_diri', true) : 0;
        $image_url = $foto_diri ? wp_get_attachment_image_url(intval($foto_diri), 'large') : '';
        if (empty($image_url)) {
            $image_url = get_template_directory_uri() . '/assets/images/placeholder/avatar.png';
        }

        if (!isset($levels[$depth])) {
            $levels[$depth] = [];
        }

        $levels[$depth][] = [
            'name'     => $name,
            'position' => $position,
            'image'    => $image_url,
        ];

        if (!empty($node['children']) && is_array($node['children'])) {
            dprd_flatten_pejabat_tree($node['children'], $depth + 1, $levels);
        }
    }
}

$grouped_levels = [];
dprd_flatten_pejabat_tree($pejabat_tree, 0, $grouped_levels);
ksort($grouped_levels);
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

        <!-- Members Grid Section (Fraksi-Fraksi Style) -->
        <?php if (!empty($grouped_levels)) :
            $sorted_levels = array_keys($grouped_levels);
            ?>
            <div class="flex flex-col mt-12 mb-16 max-w-4xl mx-auto w-full dprd-fade-in" data-direction="up" data-duration="0.8">
                <?php
                foreach ($sorted_levels as $index => $level_idx) :
                    $lvl_members = $grouped_levels[$level_idx];
                    $is_last_lvl = ($index === count($sorted_levels) - 1);
                    ?>
                    <div class="flex flex-col w-full">
                        <!-- Grid Container -->
                        <div class="flex flex-wrap justify-center gap-x-6 gap-y-10 sm:gap-x-8 md:gap-x-12 w-full">
                            <?php foreach ($lvl_members as $member) : ?>
                                <div class="w-full sm:w-[calc(50%-1.5rem)] md:w-[calc(33.333%-2.5rem)] max-w-[280px] flex justify-center">
                                    <div class="flex flex-col items-center w-full">
                                        <!-- Nama Anggota/Pejabat -->
                                        <h3 class="font-display text-lg md:text-[19px] font-normal text-body text-center mb-3 leading-snug h-[2.6em] min-h-[2.6em] flex items-center justify-center">
                                            <?php echo esc_html($member['name']); ?>
                                        </h3>

                                        <!-- Divider line -->
                                        <div class="w-full h-px bg-body/30 mb-3"></div>

                                        <!-- Jabatan -->
                                        <span class="font-display text-sm md:text-[15px] mb-4 text-center text-body-secondary">
                                            <?php echo esc_html($member['position']); ?>
                                        </span>

                                        <!-- Foto Diri (3:4 Ratio) -->
                                        <div class="relative w-full aspect-[3/4] rounded-sm overflow-hidden bg-surface shadow-sm border border-line/40">
                                            <?php if ($member['image']) : ?>
                                                <img src="<?php echo esc_url($member['image']); ?>" alt="<?php echo esc_attr($member['name']); ?>" class="object-cover w-full h-full" loading="lazy">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Subtle separator line between levels -->
                        <?php if (!$is_last_lvl) : ?>
                            <div class="w-full flex justify-center my-12">
                                <div class="w-3/4 max-w-2xl h-px bg-line/60"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
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
