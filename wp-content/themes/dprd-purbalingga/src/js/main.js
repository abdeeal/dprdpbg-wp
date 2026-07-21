import '../css/main.css';

document.addEventListener('DOMContentLoaded', () => {

    // ── Elemen Utama ────────────────────────────────────────────────────────
    const toggle   = document.getElementById('dprd-menu-toggle');
    const megamenu = document.getElementById('dprd-megamenu');
    const overlay  = document.getElementById('dprd-overlay');
    const iconMenu = document.getElementById('dprd-icon-menu');
    const iconClose= document.getElementById('dprd-icon-close');

    if (!toggle || !megamenu) return;

    let isOpen = false;

    // ── Buka / Tutup Menu ───────────────────────────────────────────────────
    function openMenu() {
        isOpen = true;
        toggle.setAttribute('aria-expanded', 'true');
        megamenu.setAttribute('aria-hidden', 'false');

        // Tampilkan elemen
        megamenu.classList.remove('hidden');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => {
            megamenu.classList.remove('opacity-0', 'translate-y-[-8px]');
            megamenu.classList.add('opacity-100', 'translate-y-0');
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');
        });

        // Toggle ikon
        iconMenu.classList.add('hidden');    iconMenu.classList.remove('flex');
        iconClose.classList.remove('hidden'); iconClose.classList.add('flex');
    }

    function closeMenu() {
        isOpen = false;
        toggle.setAttribute('aria-expanded', 'false');
        megamenu.setAttribute('aria-hidden', 'true');

        megamenu.classList.add('opacity-0', 'translate-y-[-8px]');
        megamenu.classList.remove('opacity-100', 'translate-y-0');
        overlay.classList.add('opacity-0');
        overlay.classList.remove('opacity-100');

        setTimeout(() => {
            megamenu.classList.add('hidden');
            overlay.classList.add('hidden');
        }, 300);

        iconMenu.classList.remove('hidden'); iconMenu.classList.add('flex');
        iconClose.classList.add('hidden');   iconClose.classList.remove('flex');
    }

    toggle.addEventListener('click', () => isOpen ? closeMenu() : openMenu());
    overlay.addEventListener('click', closeMenu);

    // Tutup dengan Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) closeMenu();
    });

    // ── Helper: set active state ────────────────────────────────────────────
    function setActive(items, btn) {
        items.forEach(el => {
            el.classList.remove('dprd-active', 'text-primary', 'font-bold', 'bg-line/30');
        });
        if (btn) {
            btn.classList.add('dprd-active', 'text-primary', 'font-bold');
        }
    }

    // ── Interaksi Level 1 (Kolom Kiri) ─────────────────────────────────────
    const l1Items = document.querySelectorAll('.dprd-l1-item');
    const l2Panels= document.querySelectorAll('.dprd-l2-panel');
    const l3Panels= document.querySelectorAll('.dprd-l3-panel');

    l1Items.forEach(btn => {
        btn.addEventListener('mouseenter', () => activateL1(btn));
        btn.addEventListener('click', () => {
            const hasChildren = btn.dataset.hasChildren === 'true';
            if (!hasChildren) {
                window.location.href = btn.dataset.url;
            } else {
                activateL1(btn);
            }
        });
    });

    function activateL1(btn) {
        const idx = btn.dataset.index;
        setActive(l1Items, btn);

        // Sembunyikan semua L2
        l2Panels.forEach(p => p.classList.add('hidden'));
        // Sembunyikan semua L3
        l3Panels.forEach(p => p.classList.add('hidden'));

        // Tampilkan L2 yang sesuai
        const l2 = document.getElementById('dprd-l2-' + idx);
        if (l2) {
            l2.classList.remove('hidden');
            // Auto-activate item pertama di L2
            const firstL2 = l2.querySelector('.dprd-l2-item');
            if (firstL2) activateL2(firstL2);
        }
    }

    // ── Interaksi Level 2 (Kolom Tengah) ───────────────────────────────────
    const l2Items = document.querySelectorAll('.dprd-l2-item');

    l2Items.forEach(btn => {
        btn.addEventListener('mouseenter', () => activateL2(btn));
        btn.addEventListener('click', () => {
            const hasChildren = btn.dataset.hasChildren === 'true';
            if (!hasChildren) {
                window.location.href = btn.dataset.url;
            } else {
                activateL2(btn);
            }
        });
    });

    function activateL2(btn) {
        // Ambil semua L2 items dalam panel yang sama
        const parentId = btn.dataset.parent;
        const siblingPanel = document.getElementById('dprd-l2-' + parentId);
        const siblings = siblingPanel ? siblingPanel.querySelectorAll('.dprd-l2-item') : [];
        setActive(siblings, btn);

        // Sembunyikan semua L3
        l3Panels.forEach(p => p.classList.add('hidden'));

        // Tampilkan L3 yang sesuai
        const key = btn.dataset.index;
        const l3 = document.getElementById('dprd-l3-' + key);
        if (l3) l3.classList.remove('hidden');
    }

    // ── Sticky header shrink on scroll (opsional) ──────────────────────────
    const header = document.getElementById('dprd-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        }, { passive: true });
    }

});
