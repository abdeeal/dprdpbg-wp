<?php
/**
 * Footer Template for DPRD Kabupaten Purbalingga
 * Converted 100% 1:1 from Next.js Reference (Footer.jsx)
 */
if (!defined('ABSPATH')) exit;
?>

<footer class="relative z-10 w-full bg-main flex flex-col">
    <div class="w-full bg-white border-t border-primary/20 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
        
            <!-- Kolom 1: Title, Alamat & Icon Buttons -->
            <div class="col-span-1">
                <h2 class="font-montserrat font-bold text-2xl lg:text-3xl text-body mb-6 leading-tight">DPRD<br/>Purbalingga</h2>
                <p class="font-sans text-[13px] text-body-secondary mb-6 leading-relaxed max-w-[250px]">
                    Jl. Oneng Saputra No.1, Purbalingga,<br/>Jawa Tengah 53311.
                </p>
                <div class="flex gap-3">
                    <a href="<?php echo esc_url(home_url('/ppid/')); ?>" class="w-10 h-10 rounded-full bg-surface hover:bg-line transition-colors flex items-center justify-center text-body" aria-label="PPID">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11a9 9 0 0 1 18 0"/><path d="M21 11v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/><path d="M3 11v5a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3Z"/><path d="M21 16a2 2 0 0 1-2 2h-5"/></svg>
                    </a>
                    <a href="<?php echo esc_url(home_url('/galeri/')); ?>" class="w-10 h-10 rounded-full bg-surface hover:bg-line transition-colors flex items-center justify-center text-body" aria-label="Galeri Video">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                    </a>
                    <a href="<?php echo esc_url(home_url('/berita/')); ?>" class="w-10 h-10 rounded-full bg-surface hover:bg-line transition-colors flex items-center justify-center text-body" aria-label="Umpan Berita RSS">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
                    </a>
                </div>
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div class="col-span-1 md:ml-4 lg:ml-8">
                <h3 class="font-sans font-bold text-[15px] text-body mb-6">Tautan Cepat</h3>
                <ul class="space-y-4 font-mono text-[13px] font-medium text-body">
                    <li><a href="<?php echo esc_url(home_url('/profil-dprd/pimpinan-dprd/')); ?>" class="hover:text-primary transition-colors">Profil Pimpinan</a></li>
                    <li><a href="<?php echo esc_url(home_url('/galeri/')); ?>" class="hover:text-primary transition-colors">Galeri</a></li>
                    <li><a href="<?php echo esc_url(home_url('/berita/')); ?>" class="hover:text-primary transition-colors">Berita</a></li>
                    <li><a href="<?php echo esc_url(home_url('/propemperda/')); ?>" class="hover:text-primary transition-colors">Propemperda</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Informasi -->
            <div class="col-span-1">
                <h3 class="font-sans font-bold text-[15px] text-body mb-6">Informasi</h3>
                <ul class="space-y-4 font-mono text-[13px] font-medium text-body">
                    <li><a href="https://jdih.purbalinggakab.go.id/" class="hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer">Produk Hukum (JDIH)</a></li>
                    <li><a href="<?php echo esc_url(home_url('/selayang-pandang/sekilas-tentang-purbalingga/')); ?>" class="hover:text-primary transition-colors">Sekilas Purbalingga</a></li>
                    <li><a href="<?php echo esc_url(home_url('/ppid/')); ?>" class="hover:text-primary transition-colors">PPID</a></li>
                    <li><a href="<?php echo esc_url(home_url('/sakip/')); ?>" class="hover:text-primary transition-colors">SAKIP</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Hubungi Kami -->
            <div class="col-span-1">
                <h3 class="font-sans font-bold text-[15px] text-body mb-6">Hubungi Kami</h3>
                <ul class="space-y-4 font-sans text-[13px] text-body">
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary mt-0.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>(0281) 891000</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary mt-0.5"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <span>info@dprd-purbalingga.go.id</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary mt-0.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Purbalingga, Jawa Tengah</span>
                    </li>
                </ul>
            </div>

        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 mt-16">
            <div class="pt-8 border-t border-line flex flex-col md:flex-row justify-between items-center text-[12px] text-body font-mono">
                <p>© <?php echo date('Y'); ?> DPRD Kabupaten Purbalingga. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
