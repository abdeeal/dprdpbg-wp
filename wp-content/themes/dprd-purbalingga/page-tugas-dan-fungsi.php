<?php
/**
 * Template Name: Tugas dan Fungsi Sekretariat DPRD
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();

$breadcrumbs = [
    ['label' => 'Beranda', 'href' => home_url('/')],
    ['label' => 'Sekretariat DPRD', 'href' => home_url('/sekretariat-dprd')],
    ['label' => 'Tugas dan Fungsi'],
];

$fungsi_list = [
    "Penyelenggaraan administrasi kesekretariatan DPRD.",
    "Penyelenggaraan administrasi keuangan DPRD.",
    "Penyelenggaraan rapat-rapat DPRD.",
    "Penyediaan dan pengoordinasian tenaga ahli yang diperlukan oleh DPRD.",
    "Pelaksanaan fungsi lain yang diberikan oleh atasan sesuai dengan tugas dan fungsinya."
];
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <!-- Top Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 mb-12 md:mb-16">

        <!-- Breadcrumbs -->
        <div class="mb-6 md:mb-8">
            <?php get_template_part('template-parts/ui/breadcrumbs', null, ['items' => $breadcrumbs]); ?>
        </div>

        <!-- Page Header dengan Animasi FadeIn -->
        <div class="text-left dprd-fade-in" data-direction="up" data-duration="0.6" data-delay="0">
            <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2">
                Tugas dan Fungsi Sekretariat DPRD
            </h1>
            <p class="font-mono text-xs md:text-[13px] text-body-secondary tracking-widest uppercase">
                Tugas Pokok Serta Fungsi Sekretariat DPRD
            </p>
        </div>
    </div>

    <!-- Full-width Highlight Banner (TugasPokok) dengan Animasi FadeIn -->
    <section class="w-full bg-[#82111A] text-white py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center dprd-fade-in" data-direction="up" data-delay="0.1" data-duration="0.8">
            <h2 class="font-mono text-sm md:text-[15px] font-semibold tracking-[0.2em] uppercase mb-8 opacity-90">
                Tugas Pokok
            </h2>
            <p class="font-sans text-xl md:text-[22px] italic leading-[1.8] opacity-95">
                "Sekretariat DPRD mempunyai tugas menyelenggarakan administrasi kesekretariatan, administrasi keuangan, mendukung pelaksanaan tugas dan fungsi DPRD, serta menyediakan dan mengoordinasikan tenaga ahli yang diperlukan oleh DPRD sesuai dengan kemampuan keuangan daerah."
            </p>
        </div>
    </section>

    <!-- Bottom Container: Fungsi Grid dengan Animasi FadeIn -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-16 dprd-fade-in" data-direction="up" data-duration="0.8" data-delay="0">
        <div class="py-16 md:py-20">
            <h2 class="font-display font-bold text-[32px] md:text-[40px] text-body mb-12">
                Fungsi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 gap-x-16">
                <?php foreach ($fungsi_list as $index => $fungsi) : ?>
                    <div class="flex flex-col items-start gap-4">
                        <div class="w-12 h-14 flex items-center justify-center bg-[#82111A] text-white font-sans font-semibold text-xl md:text-2xl shadow-sm">
                            <?php echo esc_html($index + 1); ?>
                        </div>
                        <p class="font-sans text-[15px] md:text-[16px] text-body-secondary leading-relaxed">
                            <?php echo esc_html($fungsi); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
