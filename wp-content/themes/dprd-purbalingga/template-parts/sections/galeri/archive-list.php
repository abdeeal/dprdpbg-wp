<?php
/**
 * Template part untuk menampilkan daftar galeri dengan filter kategori dan pencarian (Fase 4 & 5)
 * Disesuaikan dengan referensi Next.js: GaleriClient, GaleriGrid, FilterTab, Pagination
 */
if (!defined('ABSPATH')) exit;

// Ambil semua data galeri dari database
$galeri_posts = get_posts([
    'post_type'      => 'galeri',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$galeri_data = [];
foreach ($galeri_posts as $gp) {
    $image_id = get_post_meta($gp->ID, 'image_id', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
    if (empty($image_url)) {
        $image_url = get_the_post_thumbnail_url($gp->ID, 'full');
    }
    if (empty($image_url)) {
        $image_url = get_template_directory_uri() . '/assets/images/default-galeri.jpg';
    }

    $terms = wp_get_object_terms($gp->ID, 'kategori-galeri');
    $category_names = [];
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $t) {
            $category_names[] = wp_specialchars_decode($t->name);
        }
    }
    $category = !empty($category_names) ? $category_names[0] : 'Lainnya';

    $galeri_data[] = [
        'id'         => $gp->ID,
        'title'      => $gp->post_title,
        'category'   => $category,
        'categories' => $category_names,
        'image'      => $image_url
    ];
}

// Ambil daftar Kategori Galeri dari database secara dinamis
$db_terms = get_terms([
    'taxonomy'   => 'kategori-galeri',
    'hide_empty' => false,
]);

$categories = ['Semua Kategori'];
if (!empty($db_terms) && !is_wp_error($db_terms)) {
    foreach ($db_terms as $term) {
        $cat_name = wp_specialchars_decode(trim($term->name));
        if (!in_array($cat_name, $categories)) {
            $categories[] = $cat_name;
        }
    }
}
?>

<div>
    <!-- Search & Filter Bar (Kedudukan: Searching -> Button Filter Kecil di Kanan) -->
    <div class="mt-6 mb-10 md:mb-14 w-full flex items-center justify-between gap-3">
        <!-- Search Bar (Kiri - Full Panjang, Tinggi h-12) -->
        <div class="relative flex-1 min-w-0">
            <input 
                type="text" 
                id="dprd-galeri-search"
                placeholder="Cari Galeri Kegiatan..." 
                class="w-full border border-gray-300 focus:border-[#82111A] rounded-none px-5 py-3 text-sm md:text-base outline-none transition-colors text-body pr-12 bg-white shadow-sm h-12" 
            />
            <svg class="absolute right-4 top-1/2 -translate-y-1/2 text-body-secondary pointer-events-none w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <!-- Tombol Filter Kecil (Kanan - Kotak Kecil Ukuran Presisi h-12 w-12) -->
        <div class="relative flex-shrink-0">
            <button 
                type="button" 
                id="dprd-galeri-filter-btn"
                title="Filter Kategori Galeri"
                class="w-12 h-12 bg-white border border-gray-300 hover:border-[#82111A] focus:border-[#82111A] flex items-center justify-center text-body-secondary hover:text-[#82111A] transition-all duration-200 shadow-sm cursor-pointer"
            >
                <!-- Icon filter-3 (3 garis horizontal berkurang) -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M6 12h12M9 18h6" />
                </svg>
            </button>

            <!-- Dropdown Popover Menu (Lebar Menyesuaikan Kategori Paling Panjang, Tanpa Centang) -->
            <div 
                id="dprd-galeri-filter-menu" 
                class="hidden absolute right-0 top-full mt-2 w-max min-w-[220px] max-w-sm bg-white border border-gray-200 shadow-xl z-50 py-1 max-h-80 overflow-y-auto"
            >
                <div class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-body-secondary border-b border-gray-100 bg-gray-50 whitespace-nowrap">
                    Pilih Kategori Galeri
                </div>
                <?php foreach ($categories as $cat) : ?>
                    <button 
                        type="button" 
                        data-category="<?php echo esc_attr($cat); ?>"
                        class="dprd-galeri-cat-item w-full text-left px-4 py-2.5 text-sm hover:bg-[#82111A]/10 hover:text-[#82111A] transition-colors whitespace-nowrap block <?php echo $cat === 'Semua Kategori' ? 'font-semibold text-[#82111A] bg-[#82111A]/5' : 'text-body'; ?>"
                    >
                        <?php echo esc_html($cat); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Grid — 2 kolom ke kanan (2 cards per baris) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6" id="dprd-galeri-grid">
        <!-- Diisi secara dinamis oleh JS -->
    </div>
    
    <!-- No Results State -->
    <div id="dprd-galeri-no-results" class="hidden py-16 text-center text-body-secondary font-sans">
        Tidak ditemukan galeri kegiatan yang cocok.
    </div>

    <!-- Pagination — kotak w-8 h-8, aktif bg-[#82111A] text-white -->
    <div class="flex items-center justify-center gap-2 mt-12 md:mt-16" id="dprd-galeri-pagination">
        <!-- Diisi oleh JS -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var allItems = <?php echo json_encode($galeri_data); ?>;
    var activeCategory = 'Semua Kategori';
    var searchQuery = '';
    var currentPage = 1;

    function getItemsPerPage() {
        return window.innerWidth < 640 ? 10 : 20; // Mobile: 10 card ke bawah; Desktop: 20 card (10 baris x 2 kolom)
    }

    var grid = document.getElementById('dprd-galeri-grid');
    var searchInput = document.getElementById('dprd-galeri-search');
    var filterBtn = document.getElementById('dprd-galeri-filter-btn');
    var filterMenu = document.getElementById('dprd-galeri-filter-menu');
    var catItems = document.querySelectorAll('.dprd-galeri-cat-item');
    var pagination = document.getElementById('dprd-galeri-pagination');
    var noResults = document.getElementById('dprd-galeri-no-results');

    function render() {
        var itemsPerPage = getItemsPerPage();

        // Filter items
        var filtered = allItems.filter(function(item) {
            var targetCat = activeCategory.toUpperCase();
            var isAll = (targetCat === 'SEMUA KATEGORI' || targetCat === 'SEMUA');
            var matchesCategory = isAll || 
                (Array.isArray(item.categories) && item.categories.some(function(c){ return c.toUpperCase() === targetCat; })) || 
                (item.category && item.category.toUpperCase() === targetCat);
            var matchesSearch = item.title.toLowerCase().includes(searchQuery.toLowerCase());
            return matchesCategory && matchesSearch;
        });

        // Toggle no results
        if (filtered.length === 0) {
            grid.innerHTML = '';
            pagination.innerHTML = '';
            noResults.classList.remove('hidden');
            return;
        }
        noResults.classList.add('hidden');

        // Pagination calculations
        var totalPages = Math.ceil(filtered.length / itemsPerPage);
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        var start = (currentPage - 1) * itemsPerPage;
        var end = start + itemsPerPage;
        var pageItems = filtered.slice(start, end);

        // Render Grid — kartu sesuai GaleriCard.jsx
        grid.innerHTML = pageItems.map(function(item) {
            return `
                <div class="relative w-full aspect-[3/2] group overflow-hidden bg-surface cursor-pointer dprd-galeri-card">
                    <img 
                        src="${item.image}" 
                        alt="${item.title}" 
                        class="object-cover w-full h-full transition-transform duration-500"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-black/50 opacity-0 lg:group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-6 md:p-8 card-overlay">
                        <h3 class="text-white font-display text-lg md:text-xl text-center leading-snug">
                            ${item.title}
                        </h3>
                    </div>
                </div>
            `;
        }).join('');

        // Attach click listener for mobile tap support like GaleriCard.jsx
        var cards = grid.querySelectorAll('.dprd-galeri-card');
        cards.forEach(function(card) {
            card.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    var overlay = this.querySelector('.card-overlay');
                    var isCurrentlyActive = overlay.classList.contains('opacity-100');
                    
                    // Reset all
                    document.querySelectorAll('.card-overlay').forEach(function(el) {
                        el.classList.remove('opacity-100');
                        el.classList.add('opacity-0');
                    });

                    // Toggle current
                    if (!isCurrentlyActive) {
                        overlay.classList.remove('opacity-0');
                        overlay.classList.add('opacity-100');
                    }
                }
            });
        });

        // Render Pagination — kotak w-8 h-8 sesuai Pagination.jsx
        var pagHtml = '';
        if (totalPages > 1) {
            // Prev Button
            pagHtml += `
                <button 
                    class="w-8 h-8 flex items-center justify-center text-body-secondary hover:text-primary transition-colors ${currentPage === 1 ? 'opacity-30 cursor-not-allowed' : ''}"
                    ${currentPage === 1 ? 'disabled' : ''}
                    onclick="window.setGaleriPage(${currentPage - 1})"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
            `;

            for (var i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    pagHtml += `
                        <button 
                            class="w-8 h-8 flex items-center justify-center text-sm font-sans transition-colors ${i === currentPage ? 'bg-[#82111A] text-white font-medium hover:text-white' : 'text-body-secondary hover:text-primary'}"
                            onclick="window.setGaleriPage(${i})"
                        >
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    pagHtml += `<span class="w-8 h-8 flex items-center justify-center text-body-secondary text-sm font-sans tracking-widest">...</span>`;
                }
            }

            // Next Button
            pagHtml += `
                <button 
                    class="w-8 h-8 flex items-center justify-center text-body-secondary hover:text-primary transition-colors ${currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : ''}"
                    ${currentPage === totalPages ? 'disabled' : ''}
                    onclick="window.setGaleriPage(${currentPage + 1})"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            `;
        }
        pagination.innerHTML = pagHtml;
    }

    // Expose pagination handler
    window.setGaleriPage = function(page) {
        currentPage = page;
        render();
        var searchEl = document.getElementById('dprd-galeri-search');
        if (searchEl) {
            searchEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    // Filter Popover Menu handler
    if (filterBtn && filterMenu) {
        filterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!filterMenu.contains(e.target) && !filterBtn.contains(e.target)) {
                filterMenu.classList.add('hidden');
            }
        });

        catItems.forEach(function(item) {
            item.addEventListener('click', function() {
                activeCategory = this.getAttribute('data-category');
                
                catItems.forEach(function(el) {
                    if (el.getAttribute('data-category') === activeCategory) {
                        el.classList.add('font-semibold', 'text-[#82111A]', 'bg-[#82111A]/5');
                    } else {
                        el.classList.remove('font-semibold', 'text-[#82111A]', 'bg-[#82111A]/5');
                    }
                });

                filterMenu.classList.add('hidden');
                currentPage = 1;
                render();
            });
        });
    }

    // Search input handler
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value;
            currentPage = 1;
            render();
        });
    }

    // Window resize handler
    var resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(render, 150);
    });

    // Initial render
    render();
});
</script>
