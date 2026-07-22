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

    // ── Elemen Pencarian ────────────────────────────────────────────────────
    const searchToggle    = document.getElementById('dprd-search-toggle');
    const searchClose     = document.getElementById('dprd-search-close');
    const searchContainer = document.getElementById('dprd-search-container');
    const searchInput     = document.getElementById('dprd-search-input');
    const normalActions   = document.getElementById('dprd-normal-actions');
    const logoWrapper     = document.getElementById('dprd-logo-wrapper');
    const searchBackdrop  = document.getElementById('dprd-search-backdrop');
    
    let isSearchOpen = false;

    function openSearch() {
        if (isOpen) closeMenu(); // Tutup menu jika terbuka
        isSearchOpen = true;
        
        // Disable scroll
        const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = `${scrollBarWidth}px`;

        // Tampilkan backdrop
        if (searchBackdrop) {
            searchBackdrop.classList.remove('invisible', 'opacity-0');
            searchBackdrop.classList.add('visible', 'opacity-100');
        }

        // Tampilkan search container
        if (searchContainer) {
            searchContainer.classList.remove('w-0', 'opacity-0', 'pointer-events-none', 'scale-x-0');
            searchContainer.classList.add('w-[calc(100vw-6rem)]', 'sm:w-[calc(100vw-12rem)]', 'lg:w-[400px]', 'opacity-100', 'pointer-events-auto', 'scale-x-100');
        }

        // Sembunyikan normal actions
        if (normalActions) {
            normalActions.classList.remove('opacity-100', 'visible', 'scale-100', 'translate-x-0');
            normalActions.classList.add('opacity-0', 'invisible', 'scale-90', 'translate-x-4');
        }

        // Sembunyikan logo di mobile
        if (logoWrapper) {
            logoWrapper.classList.remove('opacity-100');
            logoWrapper.classList.add('opacity-0', 'pointer-events-none', 'lg:opacity-100', 'lg:pointer-events-auto');
        }

        // Auto focus input
        if (searchInput) {
            setTimeout(() => {
                searchInput.focus();
            }, 100);
        }
    }

    function closeSearch() {
        isSearchOpen = false;
        
        // Restore scroll
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Sembunyikan backdrop
        if (searchBackdrop) {
            searchBackdrop.classList.add('invisible', 'opacity-0');
            searchBackdrop.classList.remove('visible', 'opacity-100');
        }

        // Sembunyikan search container
        if (searchContainer) {
            searchContainer.classList.add('w-0', 'opacity-0', 'pointer-events-none', 'scale-x-0');
            searchContainer.classList.remove('w-[calc(100vw-6rem)]', 'sm:w-[calc(100vw-12rem)]', 'lg:w-[400px]', 'opacity-100', 'pointer-events-auto', 'scale-x-100');
        }

        // Tampilkan normal actions
        if (normalActions) {
            normalActions.classList.add('opacity-100', 'visible', 'scale-100', 'translate-x-0');
            normalActions.classList.remove('opacity-0', 'invisible', 'scale-90', 'translate-x-4');
        }

        // Tampilkan logo kembali
        if (logoWrapper) {
            logoWrapper.classList.add('opacity-100');
            logoWrapper.classList.remove('opacity-0', 'pointer-events-none', 'lg:opacity-100', 'lg:pointer-events-auto');
        }

        // Reset input
        if (searchInput) {
            searchInput.value = '';
        }
    }

    if (searchToggle) searchToggle.addEventListener('click', openSearch);
    if (searchClose) searchClose.addEventListener('click', closeSearch);
    if (searchBackdrop) searchBackdrop.addEventListener('click', closeSearch);

    // ── Buka / Tutup Menu (1:1 GSAP di NavbarDropdown.jsx) ───────────────────
    function openMenu() {
        if (isSearchOpen) closeSearch(); // Tutup search jika terbuka
        isOpen = true;
        toggle.setAttribute('aria-expanded', 'true');
        megamenu.setAttribute('aria-hidden', 'false');

        // Kompensasi hilangnya scrollbar (Persis Vercel Navbar.jsx line 41-43)
        const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = `${scrollBarWidth}px`;

        // Tampilkan overlay
        overlay.classList.remove('hidden');
        megamenu.classList.remove('invisible', 'opacity-0', '-translate-y-[15px]');
        megamenu.classList.add('opacity-100', 'translate-y-0');

        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');
        });

        // Toggle ikon
        iconMenu.classList.add('hidden');     iconMenu.classList.remove('flex');
        iconClose.classList.remove('hidden'); iconClose.classList.add('flex');
    }

    function closeMenu() {
        isOpen = false;
        toggle.setAttribute('aria-expanded', 'false');
        megamenu.setAttribute('aria-hidden', 'true');

        // Restore scrollbar & padding (Persis Vercel Navbar.jsx line 45-46)
        if (!isSearchOpen) { // Jangan kembalikan scroll jika search sedang terbuka
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        megamenu.classList.add('opacity-0', '-translate-y-[15px]');
        megamenu.classList.remove('opacity-100', 'translate-y-0');
        overlay.classList.add('opacity-0');
        overlay.classList.remove('opacity-100');

        setTimeout(() => {
            megamenu.classList.add('invisible');
            overlay.classList.add('hidden');
        }, 700);

        iconMenu.classList.remove('hidden'); iconMenu.classList.add('flex');
        iconClose.classList.add('hidden');   iconClose.classList.remove('flex');
    }

    toggle.addEventListener('click', () => isOpen ? closeMenu() : openMenu());
    overlay.addEventListener('click', closeMenu);

    // Tutup dengan Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (isOpen) closeMenu();
            if (isSearchOpen) closeSearch();
        }
    });

    // ── Helper: set active state ────────────────────────────────────────────
    function setActive(items, btn) {
        items.forEach(el => {
            el.classList.remove('dprd-active', 'text-primary', 'font-bold');
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

        // Sembunyikan semua L2 & L3
        l2Panels.forEach(p => p.classList.add('hidden'));
        l3Panels.forEach(p => p.classList.add('hidden'));

        // Tampilkan L2 yang sesuai dengan efek fade-in (0.5s ease-out Vercel GSAP)
        const l2 = document.getElementById('dprd-l2-' + idx);
        if (l2) {
            l2.classList.remove('hidden');
            l2.classList.add('animate-fade-in');
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
        const parentId = btn.dataset.parent;
        const siblingPanel = document.getElementById('dprd-l2-' + parentId);
        const siblings = siblingPanel ? siblingPanel.querySelectorAll('.dprd-l2-item') : [];
        setActive(siblings, btn);

        // Sembunyikan semua L3
        l3Panels.forEach(p => p.classList.add('hidden'));

        // Tampilkan L3 yang sesuai dengan efek fade-in (0.5s ease-out Vercel GSAP)
        const key = btn.dataset.index;
        const l3 = document.getElementById('dprd-l3-' + key);
        if (l3) {
            l3.classList.remove('hidden');
            l3.classList.add('animate-fade-in');
        }
    }

    // ── Sticky header shrink on scroll (Persis Vercel Navbar.jsx) ──────────
    const header       = document.getElementById('dprd-header');
    const navContainer = document.getElementById('dprd-nav-container');
    // logoWrapper sudah di-declare di bagian Search Toggle

    if (header && navContainer && logoWrapper) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navContainer.classList.remove('h-20');
                navContainer.classList.add('h-16');
                logoWrapper.classList.add('scale-85', 'scale-[0.85]');
                header.classList.add('shadow-[0_4px_20px_rgba(0,0,0,0.05)]', 'border-transparent');
                header.classList.remove('border-line/50');
            } else {
                navContainer.classList.remove('h-16');
                navContainer.classList.add('h-20');
                logoWrapper.classList.remove('scale-85', 'scale-[0.85]');
                header.classList.remove('shadow-[0_4px_20px_rgba(0,0,0,0.05)]', 'border-transparent');
                header.classList.add('border-line/50');
            }
        }, { passive: true });
    }

    // ── Interaksi Halaman Reservasi ────────────────────────────────────────
    const btnMinus = document.getElementById('dprd-jumlah-minus');
    const btnPlus  = document.getElementById('dprd-jumlah-plus');
    const inputJumlah = document.getElementById('res_jumlah_peserta');

    if (btnMinus && btnPlus && inputJumlah) {
        btnMinus.addEventListener('click', () => {
            let val = parseInt(inputJumlah.value) || 1;
            if (val > 1) {
                inputJumlah.value = val - 1;
            }
        });
        btnPlus.addEventListener('click', () => {
            let val = parseInt(inputJumlah.value) || 1;
            inputJumlah.value = val + 1;
        });
    }

    const uploadBox = document.getElementById('dprd-upload-box');
    const fileInput = document.getElementById('res_file_surat');
    const uploadText = document.getElementById('dprd-upload-text');
    const uploadSubtext = document.getElementById('dprd-upload-subtext');

    if (uploadBox && fileInput) {
        uploadBox.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                uploadText.textContent = file.name;
                uploadText.classList.add('font-bold', 'text-primary');
                uploadSubtext.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadBox.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadBox.classList.add('bg-primary-light');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadBox.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadBox.classList.remove('bg-primary-light');
            }, false);
        });

        uploadBox.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files[0]) {
                fileInput.files = files;
                const file = files[0];
                uploadText.textContent = file.name;
                uploadText.classList.add('font-bold', 'text-primary');
                uploadSubtext.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            }
        });
    }

    // ── Interaksi Mobile Menu Accordion (1:1 Vercel NavbarDropdown.jsx) ───
    const mobL0Headers = document.querySelectorAll('.dprd-mobile-level0-header');
    const mobL1Headers = document.querySelectorAll('.dprd-mobile-level1-header');

    mobL0Headers.forEach(header => {
        header.addEventListener('click', (e) => {
            const index = header.dataset.index;
            const body  = document.getElementById('dprd-mobile-level0-body-' + index);
            const icon  = header.querySelector('.dprd-mobile-level0-icon');
            const link  = header.querySelector('.dprd-mobile-level0-link');

            if (!body) return;

            // Jika link memiliki anak, cegah navigasi langsung
            if (link && link.classList.contains('dprd-has-children')) {
                e.preventDefault();
            }

            const isExpanded = !body.classList.contains('hidden');

            // Tutup semua level 0 lain
            document.querySelectorAll('.dprd-mobile-level0-body').forEach(b => {
                b.classList.add('hidden', 'max-h-0', 'opacity-0');
            });
            document.querySelectorAll('.dprd-mobile-level0-icon').forEach(ic => {
                ic.classList.remove('rotate-90', 'text-primary');
                ic.classList.add('text-body/60');
            });
            document.querySelectorAll('.dprd-mobile-level0-link').forEach(l => {
                l.classList.remove('text-primary', 'font-bold');
            });

            // Toggle item yang diklik jika sebelumnya tertutup
            if (!isExpanded) {
                body.classList.remove('hidden');
                requestAnimationFrame(() => {
                    body.classList.remove('max-h-0', 'opacity-0');
                    body.classList.add('max-h-[1000px]', 'opacity-100', 'mb-3');
                });
                if (icon) {
                    icon.classList.add('rotate-90', 'text-primary');
                    icon.classList.remove('text-body/60');
                }
                if (link) {
                    link.classList.add('text-primary', 'font-bold');
                }
            }
        });
    });

    mobL1Headers.forEach(header => {
        header.addEventListener('click', (e) => {
            e.stopPropagation();
            const key  = header.dataset.key;
            const body = document.getElementById('dprd-mobile-level1-body-' + key);
            const icon = header.querySelector('.dprd-mobile-level1-icon');
            const link = header.querySelector('.dprd-mobile-level1-link');

            if (!body) return;

            if (link && link.classList.contains('dprd-has-children')) {
                e.preventDefault();
            }

            const isExpanded = !body.classList.contains('hidden');

            // Tutup semua level 1 di kontainer yang sama
            const parentContainer = header.closest('.dprd-mobile-level0-body');
            if (parentContainer) {
                parentContainer.querySelectorAll('.dprd-mobile-level1-body').forEach(b => {
                    b.classList.add('hidden', 'max-h-0', 'opacity-0');
                });
                parentContainer.querySelectorAll('.dprd-mobile-level1-icon').forEach(ic => {
                    ic.classList.remove('rotate-90', 'text-primary');
                    ic.classList.add('text-body/60');
                });
                parentContainer.querySelectorAll('.dprd-mobile-level1-link').forEach(l => {
                    l.classList.remove('text-primary', 'font-bold');
                });
            }

            if (!isExpanded) {
                body.classList.remove('hidden');
                requestAnimationFrame(() => {
                    body.classList.remove('max-h-0', 'opacity-0');
                    body.classList.add('max-h-[500px]', 'opacity-100', 'mb-2');
                });
                if (icon) {
                    icon.classList.add('rotate-90', 'text-primary');
                    icon.classList.remove('text-body/60');
                }
                if (link) {
                    link.classList.add('text-primary', 'font-bold');
                }
            }
        });
    });

    // ── Interaksi Accordion PPID (Exclusive Accordion / Dynamic ScrollHeight Smooth) ──
    const ppidItems = document.querySelectorAll('.dprd-accordion-item');

    ppidItems.forEach((item, index) => {
        const btn = item.querySelector('.dprd-accordion-btn');
        const content = item.querySelector('.dprd-accordion-content');

        if (btn && content) {
            content.style.transition = 'max-height 0.4s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.4s cubic-bezier(0.25, 1, 0.5, 1)';

            if (index === 0) {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                content.classList.add('is-open');
            } else {
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
            }

            btn.addEventListener('click', () => {
                const isOpen = content.classList.contains('is-open');

                // Tutup semua item PPID terlebih dahulu
                ppidItems.forEach(otherItem => {
                    const otherContent = otherItem.querySelector('.dprd-accordion-content');
                    const otherOpen = otherItem.querySelector('.dprd-icon-open');
                    const otherClosed = otherItem.querySelector('.dprd-icon-closed');

                    if (otherContent) {
                        otherContent.style.maxHeight = '0px';
                        otherContent.style.opacity = '0';
                        otherContent.classList.remove('is-open');
                    }
                    if (otherOpen) otherOpen.classList.add('hidden');
                    if (otherClosed) otherClosed.classList.remove('hidden');
                });

                // Jika sebelumnya tertutup, buka item yang diklik
                if (!isOpen) {
                    const iconOpen = item.querySelector('.dprd-icon-open');
                    const iconClosed = item.querySelector('.dprd-icon-closed');

                    content.style.maxHeight = content.scrollHeight + 'px';
                    content.style.opacity = '1';
                    content.classList.add('is-open');
                    if (iconOpen) iconOpen.classList.remove('hidden');
                    if (iconClosed) iconClosed.classList.add('hidden');
                }
            });
        }
    });

    // ── Interaksi Banner Carousel ──────────────────────────────────────────
    const carouselContainer = document.querySelector('.dprd-banner-carousel');
    if (carouselContainer) {
        const track = carouselContainer.querySelector('.dprd-carousel-track');
        const prevBtn = carouselContainer.querySelector('.dprd-carousel-prev');
        const nextBtn = carouselContainer.querySelector('.dprd-carousel-next');
        const dots = carouselContainer.querySelectorAll('.dprd-carousel-dot');
        const slidesCount = dots.length;
        
        if (slidesCount > 1) {
            let currentIndex = 0;
            let timer;
            let touchStartX = 0;
            let touchEndX = 0;

            const updateCarousel = () => {
                if (track) {
                    track.style.transform = `translateX(-${currentIndex * 100}%)`;
                }
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('bg-white', 'w-4');
                        dot.classList.remove('bg-white/50', 'hover:bg-white/75');
                    } else {
                        dot.classList.remove('bg-white', 'w-4');
                        dot.classList.add('bg-white/50', 'hover:bg-white/75');
                    }
                });
            };

            const nextSlide = () => {
                currentIndex = (currentIndex === slidesCount - 1) ? 0 : currentIndex + 1;
                updateCarousel();
            };

            const prevSlide = () => {
                currentIndex = (currentIndex === 0) ? slidesCount - 1 : currentIndex - 1;
                updateCarousel();
            };

            const startTimer = () => {
                timer = setInterval(nextSlide, 3000);
            };

            const stopTimer = () => {
                clearInterval(timer);
            };

            if (prevBtn) prevBtn.addEventListener('click', () => { stopTimer(); prevSlide(); startTimer(); });
            if (nextBtn) nextBtn.addEventListener('click', () => { stopTimer(); nextSlide(); startTimer(); });
            
            dots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    stopTimer();
                    currentIndex = parseInt(e.target.dataset.index);
                    updateCarousel();
                    startTimer();
                });
            });

            carouselContainer.addEventListener('mouseenter', stopTimer);
            carouselContainer.addEventListener('mouseleave', startTimer);

            carouselContainer.addEventListener('touchstart', e => {
                stopTimer();
                touchStartX = e.changedTouches[0].screenX;
            }, {passive: true});

            carouselContainer.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) nextSlide();
                if (touchStartX - touchEndX < -50) prevSlide();
                startTimer();
            }, {passive: true});

            updateCarousel();
            startTimer();
        }
    }

    // ── Animated Counter (1:1 Vercel GSAP ScrollTrigger) ───────────────────
    const counters = document.querySelectorAll('.dprd-animated-counter');
    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    obs.unobserve(el); // Hanya jalankan sekali

                    const value = el.getAttribute('data-value');
                    const duration = 2000; // 2 seconds
                    const startTime = performance.now();
                    
                    // Equivalent to GSAP "power2.out"
                    const easePower2Out = (t) => 1 - Math.pow(1 - t, 2);

                    const isPeriod = value.includes('-');
                    if (!isPeriod) {
                        const target = parseInt(value, 10);
                        if (!isNaN(target)) {
                            const animate = (currentTime) => {
                                const elapsed = currentTime - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                const easedProgress = easePower2Out(progress);
                                
                                const currentVal = Math.floor(1 + (target - 1) * easedProgress);
                                el.textContent = currentVal;
                                
                                if (progress < 1) {
                                    requestAnimationFrame(animate);
                                } else {
                                    el.textContent = target;
                                }
                            };
                            requestAnimationFrame(animate);
                        }
                    } else {
                        const parts = value.split('-');
                        const targetStart = parseInt(parts[0], 10);
                        const targetEnd = parseInt(parts[1], 10);
                        if (!isNaN(targetStart) && !isNaN(targetEnd)) {
                            const gap = targetEnd - targetStart;
                            const start1 = 1945;
                            const start2 = 1945 + gap;
                            
                            const animate = (currentTime) => {
                                const elapsed = currentTime - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                const easedProgress = easePower2Out(progress);
                                
                                const currentVal1 = Math.floor(start1 + (targetStart - start1) * easedProgress);
                                const currentVal2 = Math.floor(start2 + (targetEnd - start2) * easedProgress);
                                
                                el.textContent = currentVal1 + '-' + currentVal2;
                                
                                if (progress < 1) {
                                    requestAnimationFrame(animate);
                                } else {
                                    el.textContent = targetStart + '-' + targetEnd;
                                }
                            };
                            requestAnimationFrame(animate);
                        }
                    }
                }
            });
        }, {
            threshold: 0,
            rootMargin: '0px 0px -10% 0px' // Memicu saat elemen berada 10% dari bawah viewport (mirip GSAP top 90%)
        });

        counters.forEach(counter => observer.observe(counter));
    }

});





