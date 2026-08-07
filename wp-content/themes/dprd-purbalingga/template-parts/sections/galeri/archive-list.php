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
    <!-- Search & Filter Bar (Sejajar Kanan-Kiri & Berjarak ke Gambar di Bawah) -->
    <div class="mt-6 mb-10 md:mb-14 w-full flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Search Bar (Kiri - Panjang) -->
        <div class="relative w-full md:flex-[3] min-w-0">
            <input 
                type="text" 
                id="dprd-galeri-search"
                placeholder="Cari Galeri Kegiatan..." 
                class="w-full border border-gray-300 focus:border-[#82111A] rounded-none px-5 py-3 text-sm md:text-base outline-none transition-colors text-body pr-12 bg-white shadow-sm" 
            />
            <svg class="absolute right-4 top-1/2 -translate-y-1/2 text-body-secondary pointer-events-none w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <!-- Tombol Filter Kategori (Kotak Ringkas di Kanan dengan Icon Filter) -->
        <div class="relative w-full md:w-56 lg:w-60 flex-shrink-0">
            <div class="relative flex items-center bg-white border border-gray-300 focus-within:border-[#82111A] shadow-sm">
                <!-- Icon Filter Funnel Kecil -->
                <div class="pl-3.5 pr-1 text-[#82111A] pointer-events-none flex items-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <select 
                    id="dprd-galeri-category-select"
                    class="w-full border-none px-2 py-3 text-sm md:text-base outline-none bg-transparent text-body appearance-none cursor-pointer pr-9 font-medium truncate"
                >
                    <option value="Semua Kategori">Semua Kategori</option>
                    <?php foreach ($categories as $cat) : ?>
                        <?php if ($cat === 'Semua Kategori' || $cat === 'Semua') continue; ?>
                        <option value="<?php echo esc_attr($cat); ?>"><?php echo esc_html($cat); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-body-secondary flex items-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
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
    var itemsPerPage = 20; // 10 baris ke bawah x 2 kolom ke kanan = 20 card per halaman

    var grid = document.getElementById('dprd-galeri-grid');
    var searchInput = document.getElementById('dprd-galeri-search');
    var categorySelect = document.getElementById('dprd-galeri-category-select');
    var pagination = document.getElementById('dprd-galeri-pagination');
    var noResults = document.getElementById('dprd-galeri-no-results');

    function render() {
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

    // Filter Dropdown change handler
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            activeCategory = this.value;
            currentPage = 1;
            render();
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

    // Initial render
    render();
});
</script>
