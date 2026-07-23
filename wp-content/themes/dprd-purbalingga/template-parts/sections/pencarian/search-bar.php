<?php
/**
 * Search Hero Section
 */
$query = isset($_GET['q']) ? sanitize_text_field(stripslashes($_GET['q'])) : '';
?>
<section class="bg-primary w-full py-10 md:py-20 mt-6 mb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        
        <label class="font-mono text-xs uppercase tracking-[0.2em] text-white/80 mb-4 block text-center">
            Hasil Pencarian Untuk:
        </label>
        
        <form method="GET" action="<?php echo esc_url(home_url('/pencarian')); ?>" class="w-full max-w-2xl mb-8">
            <div class="relative flex items-center w-full">
                <input
                    type="text"
                    name="q"
                    value="<?php echo esc_attr($query); ?>"
                    placeholder="Cari berita, galeri, anggota, atau dokumen..."
                    class="w-full bg-white text-body font-sans text-base md:text-lg py-4 pl-6 pr-14 focus:outline-none focus:ring-2 focus:ring-secondary transition-shadow shadow-md"
                />
                <button 
                    type="submit" 
                    class="absolute right-4 text-primary hover:text-[#82111A] transition-colors"
                    aria-label="Cari"
                >
                    <?php dprd_icon('search', 'w-6 h-6'); ?>
                </button>
            </div>
        </form>

        <div class="flex flex-row flex-wrap items-center justify-center gap-4 sm:gap-6">
            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="w-5 h-5 rounded-[4px] border flex items-center justify-center transition-colors bg-white border-white dprd-filter-checkbox" id="checkbox-berita">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-sans text-sm text-white select-none">Berita</span>
                <input type="checkbox" class="hidden" id="toggle-berita" checked />
            </label>

            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="w-5 h-5 rounded-[4px] border flex items-center justify-center transition-colors bg-white border-white dprd-filter-checkbox" id="checkbox-galeri">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-sans text-sm text-white select-none">Galeri</span>
                <input type="checkbox" class="hidden" id="toggle-galeri" checked />
            </label>

            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="w-5 h-5 rounded-[4px] border flex items-center justify-center transition-colors bg-white border-white dprd-filter-checkbox" id="checkbox-anggota">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-sans text-sm text-white select-none">Anggota</span>
                <input type="checkbox" class="hidden" id="toggle-anggota" checked />
            </label>

            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="w-5 h-5 rounded-[4px] border flex items-center justify-center transition-colors bg-white border-white dprd-filter-checkbox" id="checkbox-dokumen">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="font-sans text-sm text-white select-none">Dokumen</span>
                <input type="checkbox" class="hidden" id="toggle-dokumen" checked />
            </label>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filters = ['berita', 'galeri', 'anggota', 'dokumen'];
    const resultsContainer = document.getElementById('dprd-results-container');
    const emptyState = document.getElementById('dprd-results-empty');
    
    function updateFilters() {
        let hasAnyVisible = false;
        
        filters.forEach(filter => {
            const toggle = document.getElementById(`toggle-${filter}`);
            const check = document.getElementById(`checkbox-${filter}`);
            const section = document.getElementById(`dprd-results-${filter}`);
            const divider = document.getElementById(`dprd-divider-${filter}`);
            
            if (toggle && check) {
                const isChecked = toggle.checked;
                
                // Update Checkbox UI
                if (isChecked) {
                    check.classList.add('bg-white', 'border-white');
                    check.classList.remove('border-white', 'bg-transparent', 'group-hover:bg-white/10');
                    check.innerHTML = '<svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                } else {
                    check.classList.remove('bg-white', 'border-white');
                    check.classList.add('border-white', 'bg-transparent', 'group-hover:bg-white/10');
                    check.innerHTML = '';
                }
                
                // Toggle Section Visibility
                if (section) {
                    if (isChecked) {
                        section.style.display = 'block';
                        hasAnyVisible = true;
                    } else {
                        section.style.display = 'none';
                    }
                }
                
                // Note: The dividers need special logic since they depend on BOTH surrounding elements.
                // It's easier to just handle the display block/none of the sections, and let CSS handle gap or borders.
                // But since we are using dividers manually, we can re-evaluate which sections are visible and show dividers between them.
            }
        });
        
        // Handle Dividers dynamically
        const visibleSections = [];
        filters.forEach(f => {
            const sec = document.getElementById(`dprd-results-${f}`);
            const toggle = document.getElementById(`toggle-${f}`);
            if (sec && toggle && toggle.checked) {
                visibleSections.push(f);
            }
        });
        
        // Hide all dividers first
        document.querySelectorAll('.dprd-results-divider').forEach(el => el.style.display = 'none');
        
        // Show dividers between visible sections
        for (let i = 0; i < visibleSections.length - 1; i++) {
            // For example, if visible is ['berita', 'anggota'], we need a divider after 'berita'
            const current = visibleSections[i];
            const divEl = document.getElementById(`dprd-divider-${current}`);
            if (divEl) {
                divEl.style.display = 'block';
            }
        }
        
        // Handle empty state (if all results hidden by filters)
        if (!hasAnyVisible && emptyState) {
            if (resultsContainer) resultsContainer.style.display = 'none';
            emptyState.style.display = 'flex';
        } else if (hasAnyVisible && emptyState) {
            if (resultsContainer) resultsContainer.style.display = 'flex';
            emptyState.style.display = 'none';
        }
    }

    filters.forEach(filter => {
        const toggle = document.getElementById(`toggle-${filter}`);
        if (toggle) {
            toggle.addEventListener('change', updateFilters);
        }
    });
    
    // Initial run to setup dividers properly
    updateFilters();
});
</script>
