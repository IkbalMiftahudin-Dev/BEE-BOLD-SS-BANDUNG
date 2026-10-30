const navToggle = document.querySelector('[data-nav-toggle]');
const navMenu = document.querySelector('[data-nav-menu]');

if (navToggle && navMenu) {
    const openIcon = navToggle.querySelector('[data-nav-icon="open"]');
    const closeIcon = navToggle.querySelector('[data-nav-icon="close"]');

    const setMenuOpen = (isOpen) => {
        navMenu.classList.toggle('hidden', !isOpen);
        navToggle.setAttribute('aria-expanded', String(isOpen));
        openIcon?.classList.toggle('hidden', isOpen);
        closeIcon?.classList.toggle('hidden', !isOpen);
    };

    navToggle.addEventListener('click', () => {
        const isOpen = navMenu.classList.contains('hidden');
        setMenuOpen(isOpen);
    });

    navMenu.querySelectorAll('[data-nav-link]').forEach((link) => {
        link.addEventListener('click', () => setMenuOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !navMenu.classList.contains('hidden')) {
            setMenuOpen(false);
        }
    });
}

const navItems = document.querySelectorAll('[data-nav-item]');
const setActiveNav = (targetHref) => {
    navItems.forEach((link) => {
        const isMatch = link.getAttribute('data-nav-item') === targetHref;
        link.classList.toggle('text-gold', isMatch);
        link.classList.toggle('font-semibold', isMatch);
        if (link.hasAttribute('data-nav-link')) {
            link.classList.toggle('bg-white/5', isMatch);
        }
    });
};

navItems.forEach((link) => {
    link.addEventListener('click', () => {
        setActiveNav(link.getAttribute('data-nav-item'));
    });
});

const trackedSections = Array.from(new Set(Array.from(navItems, (l) => l.getAttribute('data-nav-item'))))
    .map((href) => document.querySelector(href))
    .filter(Boolean);
const galeriSection = document.getElementById('galeri');

if (trackedSections.length > 0) {
    const updateNavOnScroll = () => {
        const scrollPos = window.scrollY + 140;
        let currentHref = '#beranda';
        for (const section of trackedSections) {
            if (section.offsetTop <= scrollPos) {
                currentHref = `#${section.id}`;
            }
        }
        if (galeriSection && scrollPos >= galeriSection.offsetTop + galeriSection.offsetHeight) {
            currentHref = '#beranda';
        }
        setActiveNav(currentHref);
    };

    window.addEventListener('scroll', updateNavOnScroll, { passive: true });
    if (window.location.hash) {
        setActiveNav(window.location.hash);
    } else {
        updateNavOnScroll();
    }
}

const programSlider = document.querySelector('[data-program-slider]');
const programPrev = document.querySelector('[data-program-prev]');
const programNext = document.querySelector('[data-program-next]');

if (programSlider) {
    const getStepWidth = () => {
        const card = programSlider.querySelector('[data-program-card]');
        return card ? card.offsetWidth + 16 : 320;
    };

    programPrev?.addEventListener('click', () => {
        programSlider.scrollBy({ left: -getStepWidth(), behavior: 'smooth' });
    });

    programNext?.addEventListener('click', () => {
        programSlider.scrollBy({ left: getStepWidth(), behavior: 'smooth' });
    });
}

document.querySelectorAll('[data-faq-btn]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const item = btn.closest('div');
        const open = !item.querySelector('[data-faq-a]').classList.contains('hidden');
        document.querySelectorAll('[data-faq] > div').forEach((el) => {
            el.querySelector('[data-faq-a]').classList.add('hidden');
            el.querySelector('[data-faq-num]').className = 'font-oswald text-xs tracking-[1.2px] text-white/25';
            el.querySelector('[data-faq-q]').classList.replace('text-white', 'text-white/70');
            el.querySelector('[data-faq-icon]').className = 'flex size-10 shrink-0 items-center justify-center rounded-full border transition-transform border-white/15 text-white/50';
        });
        if (open) return;
        item.querySelector('[data-faq-a]').classList.remove('hidden');
        item.querySelector('[data-faq-num]').className = 'font-oswald text-xs tracking-[1.2px] text-gold';
        item.querySelector('[data-faq-q]').classList.replace('text-white/70', 'text-white');
        item.querySelector('[data-faq-icon]').className = 'flex size-10 shrink-0 items-center justify-center rounded-full border transition-transform rotate-45 border-gold bg-gold text-brand';
    });
});

const active = ['border-gold', 'bg-gold', 'text-brand'];
const idle = ['border-white/10', 'bg-white/5', 'text-white/60'];
document.querySelectorAll('[data-tab]').forEach((tab) => {
    tab.addEventListener('click', () => {
        const dayKey = tab.getAttribute('data-tab');
        document.querySelectorAll('[data-tab]').forEach((t) => {
            t.classList.remove(...active);
            t.classList.add(...idle);
        });
        tab.classList.remove(...idle);
        tab.classList.add(...active);

        document.querySelectorAll('[data-schedule-panel]').forEach((panel) => {
            panel.classList.toggle('hidden', panel.getAttribute('data-schedule-panel') !== dayKey);
        });
    });
});

const regForm = document.getElementById('reg-form');
if (regForm) {
    const regSubmit = regForm.querySelector('[data-reg-submit]');
    const regInputs = Array.from(regForm.querySelectorAll('input[required]'));
    const phoneInput = regForm.querySelector('input[name="phone"]');

    const disabledBtnClasses = ['bg-white/15', 'text-white/40', 'cursor-not-allowed'];
    const enabledBtnClasses = ['bg-gold', 'text-brand', 'cursor-pointer', 'hover:brightness-105'];

    const updateRegFormState = () => {
        if (!regSubmit) return;
        const isComplete = regInputs.every((input) => input.value.trim().length > 0);
        regSubmit.disabled = !isComplete;
        regSubmit.classList.remove(...(isComplete ? disabledBtnClasses : enabledBtnClasses));
        regSubmit.classList.add(...(isComplete ? enabledBtnClasses : disabledBtnClasses));
    };

    phoneInput?.addEventListener('input', () => {
        phoneInput.value = phoneInput.value.replace(/\D+/g, '');
    });

    regInputs.forEach((input) => {
        input.addEventListener('input', updateRegFormState);
    });

    updateRegFormState();

    regForm.addEventListener('submit', (e) => {
        if (regSubmit?.disabled) {
            e.preventDefault();
        }
    });
}

const newsSearch = document.querySelector('[data-news-search]');
const newsCards = document.querySelectorAll('[data-news-card]');
const newsEmpty = document.querySelector('[data-news-empty]');

if (newsCards.length > 0 && newsSearch) {
    newsSearch.addEventListener('input', (e) => {
        const searchQuery = e.target.value.trim().toLowerCase();
        let visibleCount = 0;
        newsCards.forEach((card) => {
            const isVisible = !searchQuery || (card.getAttribute('data-title') || '').includes(searchQuery);
            card.classList.toggle('hidden', !isVisible);
            if (isVisible) visibleCount++;
        });
        newsEmpty?.classList.toggle('hidden', visibleCount > 0);
    });
}

const galleryModal = document.querySelector('[data-gallery-modal]');
const galleryImg = document.querySelector('[data-gallery-img]');
const galleryTitle = document.querySelector('[data-gallery-title]');
const galleryCaption = document.querySelector('[data-gallery-caption]');
const galleryClose = document.querySelector('[data-gallery-close]');

if (galleryModal && galleryImg) {
    const closeGalleryModal = () => {
        galleryModal.classList.add('hidden');
        galleryModal.classList.remove('flex');
        galleryModal.setAttribute('aria-hidden', 'true');
        galleryImg.src = '';
        galleryImg.alt = '';
        if (galleryTitle) galleryTitle.textContent = '';
        if (galleryCaption) galleryCaption.textContent = '';
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-gallery-item]').forEach((item) => {
        item.addEventListener('click', () => {
            const imgUrl = item.getAttribute('data-gallery-item');
            if (!imgUrl) return;
            const titleText = item.getAttribute('data-gallery-title') || 'Dokumentasi Bee Bold Soccer School';
            const captionText = item.getAttribute('data-gallery-caption') || '';
            galleryImg.src = imgUrl;
            galleryImg.alt = titleText;
            if (galleryTitle) galleryTitle.textContent = titleText;
            if (galleryCaption) galleryCaption.textContent = captionText;
            galleryModal.classList.remove('hidden');
            galleryModal.classList.add('flex');
            galleryModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        });
    });

    galleryClose?.addEventListener('click', closeGalleryModal);

    galleryModal.addEventListener('click', (event) => {
        if (event.target === galleryModal) {
            closeGalleryModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !galleryModal.classList.contains('hidden')) {
            closeGalleryModal();
        }
    });
}

const passwordInput = document.querySelector('[data-password-input]');
const passwordToggle = document.querySelector('[data-password-toggle]');

if (passwordInput && passwordToggle) {
    const eyeOpen = passwordToggle.querySelector('[data-eye-open]');
    const eyeClosed = passwordToggle.querySelector('[data-eye-closed]');

    passwordToggle.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeOpen?.classList.toggle('hidden', isPassword);
        eyeClosed?.classList.toggle('hidden', !isPassword);
        passwordToggle.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
}

const dashSidebar = document.querySelector('[data-dash-sidebar]');
const dashBackdrop = document.querySelector('[data-dash-backdrop]');
const dashToggle = document.querySelector('[data-dash-toggle]');
const dashClose = document.querySelector('[data-dash-close]');

if (dashSidebar && dashBackdrop) {
    const setDashDrawer = (open) => {
        dashSidebar.classList.toggle('-translate-x-full', !open);
        dashBackdrop.classList.toggle('hidden', !open);
    };

    dashToggle?.addEventListener('click', () => setDashDrawer(true));
    dashClose?.addEventListener('click', () => setDashDrawer(false));
    dashBackdrop.addEventListener('click', () => setDashDrawer(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setDashDrawer(false);
        }
    });
}

// Scroll Reveal Animation (IntersectionObserver: replay on scroll up and down)
const revealElements = document.querySelectorAll('[data-reveal]');
if (revealElements.length > 0) {
    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        revealElements.forEach((el) => el.classList.add('reveal-init'));

        const revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    } else {
                        // Reset saat elemen keluar dari viewport (scroll lewat atau balik ke atas)
                        entry.target.classList.remove('revealed');
                    }
                });
            },
            {
                root: null,
                threshold: 0.12,
                rootMargin: '0px 0px -40px 0px',
            }
        );

        revealElements.forEach((el) => revealObserver.observe(el));
    }
}
