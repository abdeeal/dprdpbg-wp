<?php
/**
 * Template part untuk menampilkan daftar galeri dengan filter kategori dan pencarian (Fase 4 & 5)
 */
if (!defined('ABSPATH')) exit;

// Ambil semua data galeri dari database
$galeri_posts = get_posts([
    'post_type'      => 'galeri',
    'posts_per_page' => -1, // Ambil semua untuk disaring client-side
]);

$galeri_data = [];
foreach ($galeri_posts as $gp) {
    $image_id = get_post_meta($gp->ID, 'image_id', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
    if (empty($image_url)) {
        $image_url = get_the_post_thumbnail_url($gp->ID, 'large');
    }
    if (empty($image_url)) {
        $image_url = get_template_directory_uri() . '/assets/images/default-galeri.jpg'; // fallback
    }

    $terms = wp_get_object_terms($gp->ID, 'kategori-galeri');
    $category = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Lainnya';

    $galeri_data[] = [
        'id'       => $gp->ID,
        'title'    => $gp->post_title,
        'category' => $category,
        'image'    => $image_url
    ];
}

// Kategori dari Next.js untuk filter tabs
$categories = [
    'Semua',
    'Rapat Paripurna',
    'Rapat Komisi',
    'Kunjungan Kerja',
    'Reses',
    'Audiensi & Kunjungan Tamu'
];
?>

<div>
    <!-- Search Bar -->
    <div class="flex justify-center my-10 md:my-14">
        <div class="relative w-full max-w-2xl">
            <input 
                type="text" 
                id="dprd-galeri-search"
                placeholder="Cari Galeri..." 
                class="w-full border border-line rounded-none px-5 py-3.5 text-sm md:text-base outline-none focus:border-primary transition-colors text-body" 
            />
            <svg class="absolute right-4 top-1/2 -translate-y-1/2 text-body-secondary pointer-events-none w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap justify-center gap-2 md:gap-4 mb-10" id="dprd-galeri-filters">
        <?php foreach ($categories as $index => $cat) : ?>
            <button 
                data-category="<?php echo esc_attr($cat); ?>"
                class="dprd-filter-btn px-4 py-2 text-xs md:text-sm uppercase tracking-wider transition-all font-mono <?php echo $index === 0 ? 'font-bold bg-primary text-white' : 'font-medium text-body hover:text-primary'; ?>"
            >
                <?php echo esc_html($cat); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="dprd-galeri-grid">
        <!-- Konten diisi secara dinamis oleh JS -->
    </div>
    
    <!-- No Results State -->
    <div id="dprd-galeri-no-results" class="hidden py-16 text-center text-body-secondary font-sans">
        Tidak ditemukan galeri kegiatan yang cocok.
    </div>

    <!-- Pagination (Dinamis) -->
    <div class="flex items-center justify-center gap-2 py-8 mt-10 border-t border-line/40" id="dprd-galeri-pagination">
        <!-- Diisi oleh JS -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var allItems = <?php echo json_encode($galeri_data); ?>;
    var activeCategory = 'Semua';
    var searchQuery = '';
    var currentPage = 1;
    var itemsPerPage = 8;

    var grid = document.getElementById('dprd-galeri-grid');
    var searchInput = document.getElementById('dprd-galeri-search');
    var filterBtns = document.querySelectorAll('.dprd-filter-btn');
    var pagination = document.getElementById('dprd-galeri-pagination');
    var noResults = document.getElementById('dprd-galeri-no-results');

    function render() {
        // Filter items
        var filtered = allItems.filter(function(item) {
            var matchesCategory = activeCategory === 'Semua' || item.category.toLowerCase() === activeCategory.toLowerCase();
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

        // Render Grid
        grid.innerHTML = pageItems.map(function(item) {
            return `
                <div class="relative w-full aspect-[3/2] overflow-hidden bg-[#F0EEE7] cursor-pointer group">
                    <img 
                        src="${item.image}" 
                        alt="${item.title}" 
                        class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-6 text-center">
                        <h3 class="text-white font-display text-sm md:text-base leading-snug">
                            ${item.title}
                        </h3>
                    </div>
                </div>
            `;
        }).join('');

        // Render Pagination
        var pagHtml = '';
        if (totalPages > 1) {
            // Prev Button
            pagHtml += `
                <button class="p-1 text-body-secondary hover:text-body transition-colors ${currentPage === 1 ? 'opacity-30 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''} onclick="window.setGaleriPage(${currentPage - 1})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </button>
            `;

            for (var i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    pagHtml += `
                        <button class="px-2 py-1 text-sm font-mono ${i === currentPage ? 'font-bold text-primary border-b-2 border-primary' : 'text-body-secondary hover:text-body'}" onclick="window.setGaleriPage(${i})">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    pagHtml += `<span class="text-body-secondary text-sm px-1 font-mono">...</span>`;
                }
            }

            // Next Button
            pagHtml += `
                <button class="p-1 text-body-secondary hover:text-body transition-colors ${currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''} onclick="window.setGaleriPage(${currentPage + 1})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </button>
            `;
        }
        pagination.innerHTML = pagHtml;
    }

    // Expose pagination handler to window
    window.setGaleriPage = function(page) {
        currentPage = page;
        render();
        // Scroll back to top of filter/search smoothly
        var filterContainer = document.getElementById('dprd-galeri-filters');
        if (filterContainer) {
            filterContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    // Filter Buttons click handler
    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) {
                b.classList.remove('font-bold', 'bg-primary', 'text-white');
                b.classList.add('font-medium', 'text-body');
            });
            this.classList.add('font-bold', 'bg-primary', 'text-white');
            this.classList.remove('font-medium', 'text-body');
            
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
