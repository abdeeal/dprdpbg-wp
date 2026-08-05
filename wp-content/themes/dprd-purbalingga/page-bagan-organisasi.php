<?php
/**
 * Template Name: Bagan Organisasi
 * 
 * Halaman statis khusus untuk menampilkan bagan organisasi.
 */
get_header(); ?>

<!-- Decorative Top Background -->
<div class="absolute top-0 left-0 w-full h-[50vh] bg-gradient-to-b from-[#f8fafc] to-white -z-10"></div>
<div class="absolute top-0 right-0 w-1/2 h-[50vh] bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent -z-10"></div>

<main id="primary" class="w-full bg-main min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        
        <?php get_template_part('template-parts/ui/breadcrumbs'); ?>
        
        <header class="mb-12 mt-6">
            <h1 class="font-display font-black text-3xl md:text-[36px] text-primary mb-2">Bagan Organisasi</h1>
        </header>

        <!-- Chart Container: Overflow X Auto for Mobile Responsiveness -->
        <div class="w-full overflow-x-auto hide-scrollbar pb-8 mb-8">
            <div class="min-w-[950px] w-full flex flex-col items-center pt-2 pb-12 font-sans text-body">
                
                <!-- Header Title di atas Bagan -->
                <div class="text-center font-bold text-ink text-base sm:text-lg mb-10 uppercase leading-snug tracking-wider">
                    BAGAN ORGANISASI SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH<br/>
                    KABUPATEN PURBALINGGA
                </div>
                
                <!-- Chart Canvas (Scaled to 920px width x 520px height) -->
                <div class="relative w-[920px] h-[520px] mx-auto text-body font-sans select-none">
                    
                    <!-- SVG CONNECTOR LINES -->
                    <svg class="absolute inset-0 w-full h-full pointer-events-none z-0" xmlns="http://www.w3.org/2000/svg">
                        <!-- 1. Main vertical line from SEKRETARIS DPRD -->
                        <line x1="460" y1="56" x2="460" y2="190" stroke="#64748b" stroke-width="1.5" />
                        
                        <!-- 2. Horizontal branch to KELOMPOK JABATAN FUNGSIONAL -->
                        <line x1="460" y1="85" x2="130" y2="85" stroke="#64748b" stroke-width="1.5" />
                        <line x1="130" y1="85" x2="130" y2="105" stroke="#64748b" stroke-width="1.5" />
                        
                        <!-- 3. T-Split Bar for BAGIAN UMUM & BAGIAN PERSIDANGAN -->
                        <line x1="280" y1="190" x2="650" y2="190" stroke="#64748b" stroke-width="1.5" />
                        <line x1="280" y1="190" x2="280" y2="210" stroke="#64748b" stroke-width="1.5" />
                        <line x1="650" y1="190" x2="650" y2="210" stroke="#64748b" stroke-width="1.5" />
                        
                        <!-- 4. BAGIAN UMUM Vertical Line & Horizontal Branches to Subbagian -->
                        <line x1="170" y1="270" x2="170" y2="458" stroke="#64748b" stroke-width="1.5" />
                        <line x1="170" y1="308" x2="195" y2="308" stroke="#64748b" stroke-width="1.5" />
                        <line x1="170" y1="383" x2="195" y2="383" stroke="#64748b" stroke-width="1.5" />
                        <line x1="170" y1="458" x2="195" y2="458" stroke="#64748b" stroke-width="1.5" />
                        
                        <!-- 5. BAGIAN PERSIDANGAN Vertical Line & Horizontal Branches to Subbagian -->
                        <line x1="535" y1="270" x2="535" y2="458" stroke="#64748b" stroke-width="1.5" />
                        <line x1="535" y1="308" x2="560" y2="308" stroke="#64748b" stroke-width="1.5" />
                        <line x1="535" y1="383" x2="560" y2="383" stroke="#64748b" stroke-width="1.5" />
                        <line x1="535" y1="458" x2="560" y2="458" stroke="#64748b" stroke-width="1.5" />
                    </svg>

                    <!-- BOX 1: SEKRETARIS DPRD -->
                    <div class="absolute left-[300px] top-[0px] w-[320px] h-[56px] border border-slate-400 bg-white rounded-none shadow-xs flex items-center justify-center font-bold text-base tracking-wide text-ink z-10">
                        SEKRETARIS DPRD
                    </div>

                    <!-- BOX 2: KELOMPOK JABATAN FUNGSIONAL -->
                    <div class="absolute left-[30px] top-[105px] w-[200px] border border-slate-400 bg-white rounded-none shadow-xs flex flex-col items-center justify-between p-1 z-10">
                        <div class="text-center font-semibold text-xs leading-tight py-2 text-body">
                            KELOMPOK JABATAN<br/>FUNGSIONAL
                        </div>
                        <!-- 2x7 Grid -->
                        <div class="w-full border-t border-slate-300 grid grid-cols-7 grid-rows-2 h-[26px] bg-slate-50/50">
                            <div class="border-r border-b border-slate-300"></div>
                            <div class="border-r border-b border-slate-300"></div>
                            <div class="border-r border-b border-slate-300"></div>
                            <div class="border-r border-b border-slate-300"></div>
                            <div class="border-r border-b border-slate-300"></div>
                            <div class="border-r border-b border-slate-300"></div>
                            <div class="border-b border-slate-300"></div>
                            <div class="border-r border-slate-300"></div>
                            <div class="border-r border-slate-300"></div>
                            <div class="border-r border-slate-300"></div>
                            <div class="border-r border-slate-300"></div>
                            <div class="border-r border-slate-300"></div>
                            <div class="border-r border-slate-300"></div>
                            <div></div>
                        </div>
                    </div>

                    <!-- BOX 3: BAGIAN UMUM -->
                    <div class="absolute left-[145px] top-[210px] w-[270px] h-[60px] border border-slate-400 bg-white rounded-none shadow-xs flex items-center justify-center font-bold text-xs sm:text-sm tracking-wide text-ink z-10">
                        BAGIAN UMUM
                    </div>

                    <!-- SUBBAGIAN BAGIAN UMUM -->
                    <div class="absolute left-[195px] top-[285px] w-[230px] h-[46px] border border-slate-300 bg-white rounded-none shadow-xs flex items-center justify-center font-medium text-xs text-center px-2 text-body hover:border-primary/50 transition-colors z-10">
                        SUBBAGIAN PERENCANAAN
                    </div>
                    <div class="absolute left-[195px] top-[360px] w-[230px] h-[46px] border border-slate-300 bg-white rounded-none shadow-xs flex items-center justify-center font-medium text-xs text-center px-2 text-body hover:border-primary/50 transition-colors z-10">
                        SUBBAGIAN KEUANGAN
                    </div>
                    <div class="absolute left-[195px] top-[435px] w-[230px] h-[46px] border border-slate-300 bg-white rounded-none shadow-xs flex items-center justify-center font-medium text-xs text-center px-1 leading-tight text-body hover:border-primary/50 transition-colors z-10">
                        SUBBAGIAN TATA USAHA DAN PERLENGKAPAN
                    </div>

                    <!-- BOX 4: BAGIAN PERSIDANGAN DAN PERUNDANGAN- UNDANGAN -->
                    <div class="absolute left-[505px] top-[210px] w-[290px] h-[60px] border border-slate-400 bg-white rounded-none shadow-xs flex items-center justify-center font-bold text-xs sm:text-sm text-center leading-tight px-2 text-ink z-10">
                        BAGIAN PERSIDANGAN DAN<br/>PERUNDANGAN- UNDANGAN
                    </div>

                    <!-- SUBBAGIAN BAGIAN PERSIDANGAN -->
                    <div class="absolute left-[560px] top-[285px] w-[245px] h-[46px] border border-slate-300 bg-white rounded-none shadow-xs flex items-center justify-center font-medium text-xs text-center px-2 text-body hover:border-primary/50 transition-colors z-10">
                        SUBBAGIAN RAPAT DAN RISALAH
                    </div>
                    <div class="absolute left-[560px] top-[360px] w-[245px] h-[46px] border border-slate-300 bg-white rounded-none shadow-xs flex items-center justify-center font-medium text-xs text-center px-1 leading-tight text-body hover:border-primary/50 transition-colors z-10">
                        SUBBAGIAN PRODUK DAN<br/>DOKUMENTASI HUKUM
                    </div>
                    <div class="absolute left-[560px] top-[435px] w-[245px] h-[46px] border border-slate-300 bg-white rounded-none shadow-xs flex items-center justify-center font-medium text-xs text-center px-2 text-body hover:border-primary/50 transition-colors z-10">
                        SUBBAGIAN HUMAS DAN PROTOKOL
                    </div>

                </div>
            </div>
        </div>

            </div>
        </div>

        <!-- Download Action -->
        <?php 
        $download_url = get_post_meta(get_the_ID(), 'unduhan_bagan', true);
        if ($download_url) : 
        ?>
        <div class="flex justify-center mt-6">
            <a href="<?php echo esc_url($download_url); ?>" download target="_blank" class="inline-flex items-center text-sm font-semibold text-primary hover:underline transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 group-hover:translate-y-0.5 transition-transform"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Unduh Bagan Organisasi (Gambar)
            </a>
        </div>
        <?php else : ?>
        <div class="text-center text-sm text-slate-500 mt-8 italic">
            File unduhan gambar bagan belum tersedia.
        </div>
        <?php endif; ?>

    </div>
</main>

<style>
/* Utilities for horizontal scrolling if needed */
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<?php get_footer(); ?>
