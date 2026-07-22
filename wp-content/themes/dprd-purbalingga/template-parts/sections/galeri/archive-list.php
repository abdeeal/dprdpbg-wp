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
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
    if (empty($image_url)) {
        $image_url = get_the_post_thumbnail_url($gp->ID, 'large');
    }
    if (empty($image_url)) {
        $image_url = get_template_directory_uri() . '/assets/images/default-galeri.jpg';
    }

    $terms = wp_get_object_terms($gp->ID, 'kategori-galeri');
    $category_names = [];
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $t) {
            $category_names[] = strtoupper($t->name);
        }
    }
    $category = !empty($category_names) ? $category_names[0] : 'LAINNYA';

    $galeri_data[] = [
        'id'         => $gp->ID,
        'title'      => $gp->post_title,
        'category'   => $category,
        'categories' => $category_names,
        'image'      => $image_url
    ];
}

// Kategori filter — uppercase sesuai referensi FilterTab.jsx
$categories = [
    'Semua',
    'RAPAT PARIPURNA',
    'RAPAT KOMISI',
    'KUNJUNGAN KERJA',
    'RESES',
    'AUDIENSI & KUNJUNGAN TAMU'
];
?>

<div>
    <!-- Search Bar -->
    <div class="flex justify-center my-10 md:my-14">
        <div class="relative w-full max-w-2xl">
            <input 
                type="text" 
                id="dprd-galeri-search"
                placeholder="Cari Galeri" 
                class="w-full border border-primary/30 rounded-none px-5 py-3.5 text-sm md:text-base outline-none focus:border-primary transition-colors text-body" 
            />
            <svg class="absolute right-4 top-1/2 -translate-y-1/2 text-body-secondary pointer-events-none w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Filter Tabs — layout justify-between, border pada tombol aktif -->
    <div class="flex flex-wrap items-center justify-center md:justify-between gap-2 md:gap-4 mb-10 w-full overflow-x-auto no-scrollbar" id="dprd-galeri-filters">
        <?php foreach ($categories as $cat) : ?>
            <button 
                data-category="<?php echo esc_attr($cat); ?>"
                class="dprd-filter-btn px-6 py-2 text-xs md:text-[13px] tracking-wider uppercase whitespace-nowrap transition-colors border <?php echo $cat === 'Semua' ? 'bg-[#82111A] text-white border-[#82111A] hover:text-white' : 'text-body-secondary border-transparent hover:text-black bg-transparent'; ?>"
            >
                <?php echo esc_html($cat); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Grid — 2 kolom sesuai referensi GaleriGrid.jsx (grid-cols-1 md:grid-cols-2) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6" id="dprd-galeri-grid">
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
    var activeCategory = 'Semua';
    var searchQuery = '';
    var currentPage = 1;
    var itemsPerPage = 4; // Diturunkan sementara ke 4 agar pagination muncul

    var grid = document.getElementById('dprd-galeri-grid');
    var searchInput = document.getElementById('dprd-galeri-search');
    var filterBtns = document.querySelectorAll('.dprd-filter-btn');
    var pagination = document.getElementById('dprd-galeri-pagination');
    var noResults = document.getElementById('dprd-galeri-no-results');

    function render() {
        // Filter items
        var filtered = allItems.filter(function(item) {
            var matchesCategory = activeCategory === 'Semua' || (Array.isArray(item.categories) && item.categories.indexOf(activeCategory) !== -1) || item.category === activeCategory;
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
        var filterContainer = document.getElementById('dprd-galeri-filters');
        if (filterContainer) {
            filterContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    // Filter Buttons click handler
    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) {
                b.classList.remove('bg-[#82111A]', 'text-white', 'border-[#82111A]', 'hover:text-white');
                b.classList.add('text-body-secondary', 'border-transparent', 'bg-transparent', 'hover:text-black');
            });
            this.classList.add('bg-[#82111A]', 'text-white', 'border-[#82111A]', 'hover:text-white');
            this.classList.remove('text-body-secondary', 'border-transparent', 'bg-transparent', 'hover:text-black');
            
            activeCategory = this.getAttribute('data-category');
            currentPage = 1;
            render();
        });
    });

    // Search input handler
    searchInput.addEventListener('input', function() {
        searchQuery = this.value;
        currentPage = 1;
        render();
    });

    // Initial render
    render();
});
</script>
