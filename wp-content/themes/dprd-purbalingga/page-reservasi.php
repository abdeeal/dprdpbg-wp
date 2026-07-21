<?php
/**
 * Template Name: Form Reservasi Kunjungan
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <?php
        get_template_part('template-parts/ui/breadcrumbs');
        ?>
        
        <header class="mb-12 mt-6 text-center max-w-3xl mx-auto">
            <h1 class="font-display text-4xl text-primary font-bold mb-4">Formulir Permohonan Kunjungan</h1>
            <p class="font-sans text-body-secondary text-[15px]">
                Silakan isi data permohonan kunjungan dinas, audiensi, atau studi banding Anda dengan lengkap. Kami akan memverifikasi permohonan Anda.
            </p>
        </header>

        <?php
        get_template_part('template-parts/sections/reservasi/form-stepper');
        ?>
    </div>
</main>

<?php
get_footer();
