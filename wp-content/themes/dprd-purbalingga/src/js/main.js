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

    // ── Buka / Tutup Menu (1:1 GSAP di NavbarDropdown.jsx) ───────────────────
    function openMenu() {
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
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        megamenu.classList.add('opacity-0', '-translate-y-[15px]');
        megamenu.classList.remove('opacity-100', 'translate-y-0');
        overlay.classList.add('opacity-0');
        overlay.classList.remove('opacity-100');

        setTimeout(() => {
            megamenu.classList.add('invisible');
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
    const logoWrapper  = document.getElementById('dprd-logo-wrapper');

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

    // ── Interaksi Accordion PPID (Exclusive Accordion / Hanya 1 terbuka) ──
    const ppidItems = document.querySelectorAll('.dprd-accordion-item');

    ppidItems.forEach(item => {
        const btn = item.querySelector('.dprd-accordion-btn');
        const content = item.querySelector('.dprd-accordion-content');

        if (btn && content) {
            btn.addEventListener('click', () => {
                const isHidden = content.classList.contains('hidden');

                // Tutup semua item PPID terlebih dahulu
                ppidItems.forEach(otherItem => {
                    const otherContent = otherItem.querySelector('.dprd-accordion-content');
                    const otherOpen = otherItem.querySelector('.dprd-icon-open');
                    const otherClosed = otherItem.querySelector('.dprd-icon-closed');

                    if (otherContent && !otherContent.classList.contains('hidden')) {
                        otherContent.classList.add('max-h-0', 'opacity-0');
                        otherContent.classList.remove('max-h-[1000px]', 'opacity-100');
                        setTimeout(() => {
                            otherContent.classList.add('hidden');
                        }, 300);
                    }
                    if (otherOpen) otherOpen.classList.add('hidden');
                    if (otherClosed) otherClosed.classList.remove('hidden');
                });

                // Jika sebelumnya tertutup, buka item yang diklik
                if (isHidden) {
                    const iconOpen = item.querySelector('.dprd-icon-open');
                    const iconClosed = item.querySelector('.dprd-icon-closed');

                    content.classList.remove('hidden');
                    requestAnimationFrame(() => {
                        content.classList.remove('max-h-0', 'opacity-0');
                        content.classList.add('max-h-[1000px]', 'opacity-100');
                    });
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

    // ── Interaksi Table of Contents (Intersection Observer) ──
    const tocLinks = document.querySelectorAll('.dprd-toc-link');
    if (tocLinks.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '-100px 0px -60% 0px',
            threshold: 0
        };

        const tocObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const activeId = entry.target.id;
                    tocLinks.forEach(link => {
                        link.classList.remove('font-bold', 'text-primary');
                        link.classList.add('text-body', 'hover:text-primary');
                        if (link.getAttribute('data-target') === activeId) {
                            link.classList.add('font-bold', 'text-primary');
                            link.classList.remove('text-body', 'hover:text-primary');
                        }
                    });
                }
            });
        }, observerOptions);

        tocLinks.forEach(link => {
            const targetId = link.getAttribute('data-target');
            const section = document.getElementById(targetId);
            if (section) {
                tocObserver.observe(section);
            }

            link.addEventListener('click', (e) => {
                e.preventDefault();
                if (section) {
                    const y = section.getBoundingClientRect().top + window.pageYOffset - 100;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });
        });
    }

    // ── FadeIn Animasi Scroll-Trigger ([data-fade]) ──
    const fadeElements = document.querySelectorAll('[data-fade]');
    if (fadeElements.length > 0) {
        fadeElements.forEach(el => {
            el.classList.add('opacity-0', 'translate-y-8', 'transition-all', 'duration-700', 'ease-out');
        });

        const fadeObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('opacity-0', 'translate-y-8');
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -50px 0px', threshold: 0.1 });

        fadeElements.forEach(el => fadeObserver.observe(el));
    }

    // ── Animated Counter ([data-counter]) ──
    const counterElements = document.querySelectorAll('[data-counter]');
    if (counterElements.length > 0) {
        const animateCounter = (el) => {
            const targetText = el.dataset.original || el.innerText.trim();
            const targetNumber = parseInt(targetText.replace(/[^0-9]/g, ''));
            if (isNaN(targetNumber)) {
                el.innerText = targetText;
                return;
            }
            
            let startTime = null;
            const duration = 2000;
            
            const step = (currentTime) => {
                if (!startTime) startTime = currentTime;
                const progress = Math.min((currentTime - startTime) / duration, 1);
                
                const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                const currentNumber = Math.floor(easeProgress * targetNumber);
                
                el.innerText = currentNumber.toLocaleString('id-ID');
                
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.innerText = targetText;
                }
            };
            
            window.requestAnimationFrame(step);
        };

        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -50px 0px', threshold: 0.1 });

        counterElements.forEach(el => {
            el.dataset.original = el.innerText.trim();
            el.innerText = '0';
            counterObserver.observe(el);
        });
    }

});


