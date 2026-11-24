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
const galleryImg = galleryModal?.querySelector('[data-gallery-img]');
const galleryTitle = galleryModal?.querySelector('[data-gallery-title]');
const galleryCaption = galleryModal?.querySelector('[data-gallery-caption]');
const galleryClose = galleryModal?.querySelector('[data-gallery-close]');

if (galleryModal && galleryImg) {
    const closeGalleryModal = () => {
        galleryModal.classList.add('hidden');
        galleryModal.classList.remove('flex');
        galleryModal.setAttribute('aria-hidden', 'true');
        galleryImg.removeAttribute('src');
        galleryImg.alt = '';
        if (galleryTitle) galleryTitle.textContent = '';
        if (galleryCaption) galleryCaption.textContent = '';
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-gallery-item]').forEach((item) => {
        item.addEventListener('click', () => {
            const innerImg = item.querySelector('img');
            const imgUrl = item.getAttribute('data-gallery-item') || innerImg?.currentSrc || innerImg?.src;
            if (!imgUrl) return;
            item.classList.add('revealed');
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

document.querySelectorAll('[data-password-toggle]').forEach((passwordToggle) => {
    const passwordInput = passwordToggle.parentElement?.querySelector('[data-password-input]');
    if (!passwordInput) return;

    const eyeOpen = passwordToggle.querySelector('[data-eye-open]');
    const eyeClosed = passwordToggle.querySelector('[data-eye-closed]');

    passwordToggle.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeOpen?.classList.toggle('hidden', isPassword);
        eyeClosed?.classList.toggle('hidden', !isPassword);
        passwordToggle.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
});

const recoveryVerifyModal = document.querySelector('[data-recovery-verify-modal]');
const recoveryResetModal = document.querySelector('[data-recovery-reset-modal]');

const setRecoveryModalOpen = (modal, isOpen) => {
    if (!modal) return;
    modal.classList.toggle('hidden', !isOpen);
    modal.classList.toggle('flex', isOpen);
    modal.setAttribute('aria-hidden', String(!isOpen));

    const anyOpen =
        (recoveryVerifyModal && !recoveryVerifyModal.classList.contains('hidden')) ||
        (recoveryResetModal && !recoveryResetModal.classList.contains('hidden'));
    document.body.classList.toggle('overflow-hidden', Boolean(anyOpen));

    if (isOpen) {
        const firstInput = modal.querySelector('input:not([type="hidden"])');
        firstInput?.focus();
    }
};

if (recoveryVerifyModal) {
    document.querySelectorAll('[data-open-recovery-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            setRecoveryModalOpen(recoveryResetModal, false);
            setRecoveryModalOpen(recoveryVerifyModal, true);
        });
    });

    recoveryVerifyModal.querySelectorAll('[data-close-recovery-modal]').forEach((btn) => {
        btn.addEventListener('click', () => setRecoveryModalOpen(recoveryVerifyModal, false));
    });

    recoveryVerifyModal.addEventListener('click', (event) => {
        if (event.target === recoveryVerifyModal) {
            setRecoveryModalOpen(recoveryVerifyModal, false);
        }
    });
}

if (recoveryResetModal) {
    recoveryResetModal.querySelectorAll('[data-close-reset-modal]').forEach((btn) => {
        btn.addEventListener('click', () => setRecoveryModalOpen(recoveryResetModal, false));
    });

    recoveryResetModal.addEventListener('click', (event) => {
        if (event.target === recoveryResetModal) {
            setRecoveryModalOpen(recoveryResetModal, false);
        }
    });
}

if (recoveryVerifyModal || recoveryResetModal) {
    if (
        (recoveryVerifyModal && !recoveryVerifyModal.classList.contains('hidden')) ||
        (recoveryResetModal && !recoveryResetModal.classList.contains('hidden'))
    ) {
        document.body.classList.add('overflow-hidden');
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (recoveryResetModal && !recoveryResetModal.classList.contains('hidden')) {
                setRecoveryModalOpen(recoveryResetModal, false);
            } else if (recoveryVerifyModal && !recoveryVerifyModal.classList.contains('hidden')) {
                setRecoveryModalOpen(recoveryVerifyModal, false);
            }
        }
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
                    } else if (!document.body.classList.contains('overflow-hidden')) {
                        // Reset saat elemen keluar dari viewport (scroll lewat atau balik ke atas), kecuali saat modal sedang terbuka
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

// Dashboard Modals (Konfirmasi Perubahan & Hapus) serta Validasi Real-Time Form Dashboard
const confirmModal = document.querySelector('[data-dash-confirm-modal]');
const confirmIconBox = confirmModal?.querySelector('[data-dash-confirm-icon-box]');
const confirmTitle = confirmModal?.querySelector('[data-dash-confirm-title]');
const confirmDesc = confirmModal?.querySelector('[data-dash-confirm-desc]');
const confirmChangesWrap = confirmModal?.querySelector('[data-dash-confirm-changes-wrap]');
const confirmChangesList = confirmModal?.querySelector('[data-dash-confirm-changes-list]');
const confirmCancelBtn = confirmModal?.querySelector('[data-dash-confirm-cancel]');
const confirmAcceptBtn = confirmModal?.querySelector('[data-dash-confirm-accept]');

let pendingConfirmForm = null;

const closeConfirmModal = () => {
    if (!confirmModal) return;
    confirmModal.classList.add('hidden');
    confirmModal.classList.remove('flex');
    confirmModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
    pendingConfirmForm = null;
};

const openConfirmModal = (form, changedLabels = [], mode = 'edit') => {
    if (!confirmModal) {
        HTMLFormElement.prototype.submit.call(form);
        return;
    }

    pendingConfirmForm = form;
    const isDelete = mode === 'delete';

    if (confirmIconBox) {
        confirmIconBox.classList.toggle('bg-gold/25', !isDelete);
        confirmIconBox.classList.toggle('text-brand', !isDelete);
        confirmIconBox.classList.toggle('bg-[#fce8e6]', isDelete);
        confirmIconBox.classList.toggle('text-[#c5221f]', isDelete);
    }

    if (confirmAcceptBtn) {
        confirmAcceptBtn.disabled = false;
        confirmAcceptBtn.textContent = isDelete ? 'Ya, Hapus Permanen' : 'Ya, Simpan Perubahan';
        confirmAcceptBtn.classList.toggle('bg-gold', !isDelete);
        confirmAcceptBtn.classList.toggle('text-brand', !isDelete);
        confirmAcceptBtn.classList.toggle('hover:brightness-95', !isDelete);
        confirmAcceptBtn.classList.toggle('bg-[#dc2626]', isDelete);
        confirmAcceptBtn.classList.toggle('text-white', isDelete);
        confirmAcceptBtn.classList.toggle('hover:bg-[#b91c1c]', isDelete);
    }

    if (confirmTitle) {
        confirmTitle.textContent = form.dataset.confirmTitle || (isDelete ? 'Konfirmasi Penghapusan' : 'Konfirmasi Perubahan');
    }
    if (confirmDesc) {
        confirmDesc.textContent = form.dataset.confirmMessage || (isDelete ? 'Apakah Anda yakin ingin menghapus data ini?' : 'Apakah Anda yakin ingin menyimpan perubahan data ini?');
    }

    if (confirmChangesWrap && confirmChangesList) {
        confirmChangesList.innerHTML = '';
        const uniqueLabels = Array.from(new Set(changedLabels));
        if (!isDelete && uniqueLabels.length > 0) {
            uniqueLabels.forEach((labelText) => {
                const li = document.createElement('li');
                li.textContent = labelText;
                confirmChangesList.appendChild(li);
            });
            confirmChangesWrap.classList.remove('hidden');
        } else {
            confirmChangesWrap.classList.add('hidden');
        }
    }

    confirmModal.classList.remove('hidden');
    confirmModal.classList.add('flex');
    confirmModal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    confirmAcceptBtn?.focus();
};

if (confirmModal) {
    confirmCancelBtn?.addEventListener('click', closeConfirmModal);
    confirmModal.addEventListener('click', (event) => {
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
    });

    confirmAcceptBtn?.addEventListener('click', () => {
        if (!pendingConfirmForm) return;
        const formToSubmit = pendingConfirmForm;
        formToSubmit.dataset.confirmed = 'true';
        confirmAcceptBtn.disabled = true;
        closeConfirmModal();
        HTMLFormElement.prototype.submit.call(formToSubmit);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !confirmModal.classList.contains('hidden')) {
            closeConfirmModal();
        }
    });
}

document.querySelectorAll('form[data-delete-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.confirmed === 'true') {
            return;
        }
        event.preventDefault();
        openConfirmModal(form, [], 'delete');
    });
});

const getDashFieldLabel = (field) => {
    if (field.dataset.fieldLabel) return field.dataset.fieldLabel;
    const closestLabel = field.closest('label');
    if (closestLabel) {
        return closestLabel.textContent.replace(/\*/g, '').replace(/\s+/g, ' ').trim();
    }
    const parentLabel = field.parentElement?.querySelector('label');
    if (parentLabel) {
        return parentLabel.textContent.replace(/\*/g, '').replace(/\s+/g, ' ').trim();
    }
    return field.name || 'Kolom';
};

const renderFieldInlineError = (field, message) => {
    const parent = field.parentElement;
    if (!parent) return;

    let errorContainer = parent;
    if (parent.tagName === 'LABEL' && parent.parentElement) {
        errorContainer = parent.parentElement;
    } else if (parent.tagName === 'FORM' && parent.classList.contains('flex') && !parent.classList.contains('flex-col') && parent.parentElement) {
        errorContainer = parent.parentElement;
    }

    const fieldKey = field.name || field.id || 'field';
    let errorEl = errorContainer.querySelector(`[data-field-error-for="${fieldKey}"]`);

    if (message) {
        field.classList.remove('border-brand/15');
        field.classList.add('border-[#dc2626]');

        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.setAttribute('data-field-error-for', fieldKey);
            errorEl.className = 'mt-1.5 text-xs font-medium text-[#c5221f]';
            if (errorContainer === parent && parent.tagName !== 'FORM') {
                parent.appendChild(errorEl);
            } else {
                const anchor = parent.tagName === 'LABEL' || parent.tagName === 'FORM' ? parent : field;
                anchor.insertAdjacentElement('afterend', errorEl);
            }
        }
        errorEl.textContent = message;
    } else {
        field.classList.remove('border-[#dc2626]');
        if (!field.classList.contains('border-gold')) {
            field.classList.add('border-brand/15');
        }
        errorEl?.remove();
    }
};

const collectDashFormErrors = (form, trackedFields, initialValues = null) => {
    const fieldErrors = new Map();
    const fileFields = trackedFields.filter((f) => f.type === 'file');
    const hasUploadedFile = fileFields.some((f) => f.files && f.files.length > 0);
    const hasExistingMedia = form.dataset.hasExistingMedia === 'true';
    const isEditMode = form.hasAttribute('data-edit-form');

    const pwdField = trackedFields.find((f) => f.name === 'password');
    const pwdConfirmField = trackedFields.find((f) => f.name === 'password_confirmation');

    if (pwdField || pwdConfirmField) {
        const pwdVal = String(pwdField?.value ?? '');
        const pwdConfirmVal = String(pwdConfirmField?.value ?? '');
        const pwdRequired = Boolean(pwdField?.required);

        if (pwdRequired && pwdVal === '') {
            fieldErrors.set(pwdField, 'Kata sandi wajib diisi.');
        } else if (pwdVal !== '' && pwdVal.length < 8) {
            fieldErrors.set(pwdField, `Kata sandi minimal 8 karakter (saat ini ${pwdVal.length} karakter).`);
        }

        if (pwdConfirmField) {
            if ((pwdVal !== '' || pwdRequired) && pwdConfirmVal === '') {
                fieldErrors.set(pwdConfirmField, 'Silakan ketik ulang konfirmasi kata sandi.');
            } else if ((pwdVal !== '' || pwdConfirmVal !== '') && pwdVal !== pwdConfirmVal) {
                fieldErrors.set(pwdConfirmField, 'Konfirmasi kata sandi tidak sama dengan kata sandi baru.');
            }
        }
    }

    trackedFields.forEach((field) => {
        if (fieldErrors.has(field)) return;
        if (field.name === 'password' || field.name === 'password_confirmation') return;

        const val = String(field.value ?? '').trim();
        const label = getDashFieldLabel(field);

        if (field.hasAttribute('data-used-orders')) {
            let usedOrders = [];
            try {
                usedOrders = JSON.parse(field.getAttribute('data-used-orders') || '[]');
            } catch {
                usedOrders = [];
            }
            const numericVal = Number(val);
            if (val === '' || !Number.isInteger(numericVal) || numericVal < 1 || numericVal > 999) {
                fieldErrors.set(field, 'Urutan tampil wajib berupa angka antara 1 hingga 999.');
                return;
            }
            if (usedOrders.includes(numericVal)) {
                fieldErrors.set(field, `Nomor urutan #${numericVal} sudah digunakan. Silakan pilih nomor urutan lain.`);
                return;
            }
        }

        if (field.name === 'image_url' || field.name === 'thumbnail_url') {
            const initialVal = initialValues ? String(initialValues.get(field) ?? '') : '';
            if (isEditMode && hasExistingMedia && val === '' && initialVal !== '' && !hasUploadedFile) {
                fieldErrors.set(field, 'Masukkan URL gambar atau unggah file gambar baru.');
                return;
            }
            if (val !== '' && !/^(https?:\/\/|images\/)/.test(val)) {
                fieldErrors.set(field, 'URL gambar harus diawali https://, http://, atau images/.');
                return;
            }
        }

        if (field.type === 'email') {
            if (field.required && val === '') {
                fieldErrors.set(field, 'Alamat email wajib diisi.');
                return;
            }
            if (val !== '' && !field.validity.valid) {
                fieldErrors.set(field, 'Format alamat email belum valid (contoh: nama@domain.com).');
                return;
            }
        }

        if (field.required && field.type !== 'checkbox' && field.type !== 'radio' && field.type !== 'file' && val === '') {
            fieldErrors.set(field, `Kolom ${label} wajib diisi.`);
            return;
        }

        if (!field.validity.valid) {
            fieldErrors.set(field, field.validationMessage || `Nilai pada kolom ${label} belum valid.`);
        }
    });

    return fieldErrors;
};

// Form Edit: Dirty-check, validasi real-time, banner status, dan modal konfirmasi
const editForms = document.querySelectorAll('form[data-edit-form]');
if (editForms.length > 0) {
    const disabledBtnClasses = ['bg-slate-200', 'text-navy-900/40', 'cursor-not-allowed'];
    const managedActiveClasses = [
        'bg-gold',
        'bg-brand',
        'text-brand',
        'text-white',
        'cursor-pointer',
        'hover:brightness-95',
        'hover:bg-navy-900',
    ];

    editForms.forEach((form) => {
        const externalSubmitBtns = form.id
            ? Array.from(document.querySelectorAll(`button[type="submit"][form="${form.id}"], input[type="submit"][form="${form.id}"]`))
            : [];
        const internalSubmitBtns = Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'));
        const submitBtns = Array.from(new Set([...internalSubmitBtns, ...externalSubmitBtns]));

        submitBtns.forEach((btn) => {
            const activeClasses = managedActiveClasses.filter((cls) => btn.classList.contains(cls));
            btn.dataset.activeClasses = JSON.stringify(activeClasses);
        });

        const trackedFields = Array.from(form.querySelectorAll('input, textarea, select')).filter((field) => {
            if (!field.name || field.name === '_token' || field.name === '_method') return false;
            return field.type !== 'hidden' && field.type !== 'submit' && field.type !== 'button';
        });

        const initialValues = new Map();
        const touchedFields = new Set();

        trackedFields.forEach((field) => {
            if (field.type === 'checkbox' || field.type === 'radio') {
                initialValues.set(field, Boolean(field.checked));
            } else if (field.type === 'file') {
                initialValues.set(field, '');
            } else {
                initialValues.set(field, String(field.value ?? '').trim());
            }
        });

        const evaluateEditFormState = (showAllErrors = false) => {
            const changedLabels = [];
            const fileFields = trackedFields.filter((f) => f.type === 'file');
            const hasUploadedFile = fileFields.some((f) => f.files && f.files.length > 0);
            const hasExistingMedia = form.dataset.hasExistingMedia === 'true';

            trackedFields.forEach((field) => {
                const initial = initialValues.get(field);
                if (field.type === 'checkbox' || field.type === 'radio') {
                    if (Boolean(field.checked) !== initial) {
                        changedLabels.push(getDashFieldLabel(field));
                    }
                    return;
                }

                if (field.type === 'file') {
                    if (field.files && field.files.length > 0) {
                        changedLabels.push(getDashFieldLabel(field));
                    }
                    return;
                }

                const currentVal = String(field.value ?? '').trim();
                if ((field.name === 'image_url' || field.name === 'thumbnail_url') && hasExistingMedia && currentVal === '' && !hasUploadedFile) {
                    return;
                }

                if (currentVal !== initial) {
                    changedLabels.push(getDashFieldLabel(field));
                }
            });

            const fieldErrors = collectDashFormErrors(form, trackedFields, initialValues);
            const isValid = fieldErrors.size === 0 && form.checkValidity();
            const canSubmit = changedLabels.length > 0 && isValid;

            trackedFields.forEach((field) => {
                const errMessage = fieldErrors.get(field) || '';
                const shouldShow = Boolean(errMessage) && (showAllErrors || touchedFields.has(field) || String(field.value ?? '') !== String(initialValues.get(field) ?? ''));
                renderFieldInlineError(field, shouldShow ? errMessage : '');
            });

            const firstErrorMessage = fieldErrors.values().next().value || '';

            submitBtns.forEach((btn) => {
                btn.disabled = !canSubmit;
                btn.title = firstErrorMessage || (changedLabels.length === 0 ? 'Belum ada perubahan data.' : '');
                const activeClasses = JSON.parse(btn.dataset.activeClasses || '[]');
                if (canSubmit) {
                    btn.classList.remove(...disabledBtnClasses);
                    btn.classList.add(...activeClasses);
                } else {
                    btn.classList.remove(...activeClasses);
                    btn.classList.add(...disabledBtnClasses);
                }
            });

            return { canSubmit, changedLabels };
        };

        trackedFields.forEach((field) => {
            const onFieldChange = () => {
                touchedFields.add(field);
                if (field.name === 'password') {
                    const confirmField = trackedFields.find((f) => f.name === 'password_confirmation');
                    if (confirmField && (String(field.value).length >= 8 || String(confirmField.value) !== '')) {
                        touchedFields.add(confirmField);
                    }
                }
                evaluateEditFormState(false);
            };
            field.addEventListener('input', onFieldChange);
            field.addEventListener('change', onFieldChange);
        });

        evaluateEditFormState(false);

        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'true') {
                return;
            }
            event.preventDefault();

            const { canSubmit, changedLabels } = evaluateEditFormState(true);
            if (!canSubmit) {
                form.reportValidity();
                return;
            }

            openConfirmModal(form, changedLabels, 'edit');
        });
    });
}

// Form Tambah (Create) di Dashboard: Validasi real-time urutan unik & kecocokan kata sandi
document.querySelectorAll('main form').forEach((form) => {
    if (form.hasAttribute('data-edit-form') || form.hasAttribute('data-delete-form')) return;
    if ((form.method || '').toLowerCase() !== 'post') return;

    const hasCreateValidators = Boolean(
        form.querySelector('input[data-used-orders], input[name="password_confirmation"]')
    );
    if (!hasCreateValidators) return;

    const trackedFields = Array.from(form.querySelectorAll('input, textarea, select')).filter((field) => {
        if (!field.name || field.name === '_token' || field.name === '_method') return false;
        return field.type !== 'hidden' && field.type !== 'submit' && field.type !== 'button';
    });

    const touchedCreateFields = new Set();

    const evaluateCreateForm = (showAll = false) => {
        const fieldErrors = collectDashFormErrors(form, trackedFields, null);
        trackedFields.forEach((field) => {
            const msg = fieldErrors.get(field) || '';
            const shouldShow = Boolean(msg) && (showAll || touchedCreateFields.has(field));
            renderFieldInlineError(field, shouldShow ? msg : '');
        });
        return fieldErrors.size === 0 && form.checkValidity();
    };

    trackedFields.forEach((field) => {
        const onInput = () => {
            touchedCreateFields.add(field);
            if (field.name === 'password') {
                const confirmField = trackedFields.find((f) => f.name === 'password_confirmation');
                if (confirmField && String(confirmField.value) !== '') {
                    touchedCreateFields.add(confirmField);
                }
            }
            evaluateCreateForm(false);
        };
        field.addEventListener('input', onInput);
        field.addEventListener('change', onInput);
    });

    form.addEventListener('submit', (event) => {
        if (!evaluateCreateForm(true)) {
            event.preventDefault();
            const firstInvalidField = form.querySelector('.border-\\[\\#dc2626\\]');
            firstInvalidField?.focus();
        }
    });
});

// Dashboard Preview Modal: Pratinjau tampilan website sebelum unggah/simpan
const dashPreviewModal = document.querySelector('[data-dash-preview-modal]');
const dashPreviewTitle = dashPreviewModal?.querySelector('[data-dash-preview-title]');
const dashPreviewSubtitle = dashPreviewModal?.querySelector('[data-dash-preview-subtitle]');
const dashPreviewBody = dashPreviewModal?.querySelector('[data-dash-preview-body]');

if (dashPreviewModal && dashPreviewBody) {
    let activePreviewObjectUrls = [];

    const revokePreviewObjectUrls = () => {
        activePreviewObjectUrls.forEach((url) => URL.revokeObjectURL(url));
        activePreviewObjectUrls = [];
    };

    const escapePreviewHtml = (value) =>
        String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

    const sanitizePreviewImageUrl = (rawUrl) => {
        const trimmed = String(rawUrl ?? '').trim();
        if (!trimmed) return '';
        if (/^(https?:\/\/|blob:|\/?images\/)/i.test(trimmed)) {
            if (/^images\//i.test(trimmed)) {
                return `/${trimmed}`;
            }
            return trimmed;
        }
        return '';
    };

    const resolvePreviewImage = (form, fileFieldName, urlFieldName) => {
        const fileInput = form.querySelector(`input[type="file"][name="${fileFieldName}"]`);
        if (fileInput?.files && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (file && file.type.startsWith('image/')) {
                const blobUrl = URL.createObjectURL(file);
                activePreviewObjectUrls.push(blobUrl);
                return blobUrl;
            }
        }
        const urlInput = form.querySelector(`input[name="${urlFieldName}"]`);
        const typedUrl = sanitizePreviewImageUrl(urlInput?.value || '');
        if (typedUrl) return typedUrl;

        return sanitizePreviewImageUrl(form.dataset.existingImage || '');
    };

    const getCategoryBadgeClass = (slug) => {
        switch (slug) {
            case 'prestasi':
                return 'bg-gold';
            case 'rekrutmen':
                return 'bg-sky';
            case 'pelatihan':
                return 'bg-[#4ade80]';
            case 'fasilitas':
                return 'bg-[#f87171]';
            case 'komunitas':
                return 'bg-[#c084fc]';
            default:
                return 'bg-white/80';
        }
    };

    const slugifyPreview = (text) =>
        String(text ?? '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

    const formatPreviewDateWib = () => {
        try {
            return (
                new Intl.DateTimeFormat('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'Asia/Jakarta',
                }).format(new Date()) + ' WIB'
            );
        } catch {
            return 'Baru saja (WIB)';
        }
    };

    const closeDashPreviewModal = () => {
        dashPreviewModal.classList.add('hidden');
        dashPreviewModal.classList.remove('flex');
        dashPreviewModal.setAttribute('aria-hidden', 'true');
        dashPreviewBody.innerHTML = '';
        revokePreviewObjectUrls();
        document.body.classList.remove('overflow-hidden');
    };

    const renderPlaceholderImg = (heightClass, label = 'Belum ada gambar dipilih') => `
        <div class="flex ${heightClass} w-full flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/20 bg-[#001A33] text-center text-xs text-white/45">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
            <span>${escapePreviewHtml(label)}</span>
        </div>
    `;

    const buildNewsPreview = (form) => {
        const title = (form.querySelector('input[name="title"]')?.value || '').trim() || 'Judul Berita Belum Diisi';
        const lead = (form.querySelector('textarea[name="lead_paragraph"]')?.value || '').trim() || 'Paragraf pembuka berita akan tampil di bagian ini.';
        const content = (form.querySelector('textarea[name="content"]')?.value || '').trim() || 'Isi lengkap artikel berita akan tampil di sini setelah Anda menuliskannya.';
        const catSelect = form.querySelector('select[name="category_id"]');
        const selectedOption = catSelect?.options?.[catSelect.selectedIndex];
        const catName = selectedOption && selectedOption.value ? selectedOption.textContent.trim() : 'Kategori';
        const catSlug = selectedOption?.dataset?.slug || '';
        const badgeColor = getCategoryBadgeClass(catSlug);
        const isFeatured = Boolean(form.querySelector('input[name="is_featured"]')?.checked);
        const isPublished = Boolean(form.querySelector('input[name="is_published"]')?.checked);
        const authorName = form.dataset.authorName || 'Tim Redaksi Bee Bold';
        const dateLabel = formatPreviewDateWib();
        const imgUrl = resolvePreviewImage(form, 'thumbnail_file', 'thumbnail_url');

        const paragraphsHtml = content
            .split(/\r?\n\s*\r?\n/)
            .map((p) => p.trim())
            .filter(Boolean)
            .map((p) => `<p>${escapePreviewHtml(p).replace(/\r?\n/g, '<br>')}</p>`)
            .join('');

        const homeCardHtml = isFeatured
            ? `
                <div class="group relative block min-h-[320px] overflow-hidden rounded-2xl bg-[#001A33]">
                    ${imgUrl ? `<img src="${escapePreviewHtml(imgUrl)}" alt="${escapePreviewHtml(title)}" referrerpolicy="no-referrer" class="absolute inset-0 size-full object-cover opacity-70">` : ''}
                    <div class="absolute inset-0 bg-gradient-to-t from-[#001428] via-[#001428]/50 to-transparent"></div>
                    <span class="${badgeColor} absolute left-4 top-[18px] rounded-full px-3 py-1 text-xs font-bold text-brand">${escapePreviewHtml(catName)}</span>
                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                        <p class="text-xs text-white/40">${escapePreviewHtml(dateLabel)}</p>
                        <h3 class="pt-2 font-oswald text-2xl font-bold uppercase leading-tight text-white sm:text-3xl">${escapePreviewHtml(title)}</h3>
                        <p class="pt-2 text-sm leading-[1.625] text-white/60">${escapePreviewHtml(lead)}</p>
                    </div>
                </div>
            `
            : `
                <div class="flex min-h-36 overflow-hidden rounded-2xl border border-white/10 bg-[#001A33]">
                    ${imgUrl ? `<img src="${escapePreviewHtml(imgUrl)}" alt="${escapePreviewHtml(title)}" referrerpolicy="no-referrer" class="w-32 shrink-0 object-cover sm:w-40">` : `<div class="flex w-32 shrink-0 items-center justify-center bg-navy-900 text-[11px] text-white/35 sm:w-40">No Foto</div>`}
                    <div class="flex flex-1 flex-col justify-center px-5 py-4">
                        <div class="pb-2"><span class="rounded bg-sky/15 px-2 py-0.5 text-xs font-medium text-sky">${escapePreviewHtml(catName)}</span></div>
                        <h3 class="font-oswald text-lg font-bold uppercase leading-snug text-white">${escapePreviewHtml(title)}</h3>
                        <p class="pt-1 text-xs text-white/40">${escapePreviewHtml(dateLabel)}</p>
                    </div>
                </div>
            `;

        return {
            title: 'Preview Berita & Artikel',
            subtitle: `Status: ${isPublished ? 'Publik' : 'Draft'} • ${isFeatured ? 'Highlight Berita Utama' : 'Kartu Berita Reguler'}`,
            html: `
                <div class="space-y-6">
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-[1.2px] text-gold">— Tampilan Kartu di Beranda</p>
                        ${homeCardHtml}
                    </div>
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-[1.2px] text-sky">— Tampilan Halaman Detail Berita</p>
                        <div class="overflow-hidden rounded-2xl border border-white/10 bg-navy-950">
                            <div class="relative flex min-h-[260px] items-end overflow-hidden px-6 pb-8 pt-16 sm:min-h-[320px]">
                                ${imgUrl ? `<img src="${escapePreviewHtml(imgUrl)}" alt="${escapePreviewHtml(title)}" referrerpolicy="no-referrer" class="absolute inset-0 size-full object-cover">` : ''}
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/65 to-navy-950/20"></div>
                                <div class="relative w-full">
                                    <span class="${badgeColor} inline-block rounded-full px-3 py-1 font-oswald text-xs font-bold text-brand">${escapePreviewHtml(catName)}</span>
                                    <h3 class="max-w-3xl pt-3 font-oswald text-2xl font-bold uppercase leading-tight text-white sm:text-4xl">${escapePreviewHtml(title)}</h3>
                                    <div class="flex items-center gap-2.5 pt-4 text-xs text-white/50">
                                        <span class="flex size-5 items-center justify-center rounded-full bg-gold text-[8px] font-bold text-brand">BB</span>
                                        <span>${escapePreviewHtml(authorName)}</span><span>·</span><span>${escapePreviewHtml(dateLabel)}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-5 p-6 sm:p-8">
                                <p class="border-l-2 border-gold py-1 pl-4 text-base leading-7 text-white/85 sm:text-lg">${escapePreviewHtml(lead)}</p>
                                <div class="space-y-4 text-sm leading-7 text-white/70 sm:text-[15px]">${paragraphsHtml}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
        };
    };

    const buildGalleryPreview = (form) => {
        const title = (form.querySelector('input[name="title"]')?.value || '').trim() || 'Judul Dokumentasi Galeri';
        const caption = (form.querySelector('textarea[name="caption"]')?.value || '').trim();
        const ratio = form.querySelector('select[name="grid_ratio"]')?.value || 'landscape';
        const isHome = Boolean(form.querySelector('input[name="is_highlighted_on_home"]')?.checked);
        const imgUrl = resolvePreviewImage(form, 'image_file', 'image_url');

        const ratioHeight = ratio === 'portrait' ? 'h-[340px]' : ratio === 'square' ? 'h-[260px]' : 'h-[210px]';
        const ratioLabel = ratio === 'portrait' ? 'Portrait (Tegak)' : ratio === 'square' ? 'Square (Persegi)' : 'Landscape (Mendatar)';

        return {
            title: 'Preview Foto Galeri',
            subtitle: `Rasio: ${ratioLabel} • ${isHome ? 'Tampil di 6 Momen Terbaik Beranda' : 'Hanya di Halaman Semua Galeri'}`,
            html: `
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-[1.2px] text-gold">— Tampilan Kartu di Website (${escapePreviewHtml(ratioLabel)})</p>
                        <div class="group relative block ${ratioHeight} w-full overflow-hidden rounded-2xl bg-navy-900">
                            ${imgUrl ? `<img src="${escapePreviewHtml(imgUrl)}" alt="${escapePreviewHtml(title)}" referrerpolicy="no-referrer" class="absolute inset-0 size-full object-cover">` : renderPlaceholderImg(ratioHeight)}
                            <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/20 to-transparent"></div>
                            <div class="absolute inset-x-0 bottom-0 p-5">
                                <p class="font-oswald text-base font-bold uppercase tracking-wide text-white">${escapePreviewHtml(title)}</p>
                                ${caption ? `<p class="line-clamp-2 pt-1 text-xs leading-relaxed text-white/75">${escapePreviewHtml(caption)}</p>` : ''}
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-[1.2px] text-sky">— Tampilan Lightbox Saat Diklik</p>
                        <div class="flex flex-col items-center rounded-2xl border border-white/10 bg-[#071326] p-4 text-center">
                            ${imgUrl ? `<img src="${escapePreviewHtml(imgUrl)}" alt="${escapePreviewHtml(title)}" referrerpolicy="no-referrer" class="max-h-[280px] w-auto max-w-full rounded-xl object-contain shadow-xl ring-1 ring-white/15">` : renderPlaceholderImg('h-[210px]')}
                            <p class="pt-3.5 font-oswald text-lg font-bold uppercase tracking-wide text-white">${escapePreviewHtml(title)}</p>
                            ${caption ? `<p class="pt-1 text-xs leading-relaxed text-white/65">${escapePreviewHtml(caption)}</p>` : '<p class="pt-1 text-xs italic text-white/35">Tanpa caption</p>'}
                        </div>
                    </div>
                </div>
            `,
        };
    };

    const buildProgramPreview = (form) => {
        const title = (form.querySelector('input[name="title"]')?.value || '').trim() || 'Nama Program Usia';
        const ageRange = (form.querySelector('input[name="age_range"]')?.value || '').trim() || 'U-7 – U-9';
        const group = form.querySelector('select[name="category_group"]')?.value || 'Grassroot';
        const coach = (form.querySelector('input[name="coach_name"]')?.value || '').trim() || 'Nama Pelatih';
        const desc = (form.querySelector('textarea[name="description"]')?.value || '').trim();
        const sortOrder = (form.querySelector('input[name="sort_order"]')?.value || '').trim() || '1';
        const isActive = Boolean(form.querySelector('input[name="is_active"]')?.checked);
        const imgUrl = resolvePreviewImage(form, 'image_file', 'image_url');
        const badgeBg = group === 'Youth' ? 'bg-sky' : 'bg-gold';
        const subtitleLine = desc ? `${coach} • ${desc}` : coach;

        return {
            title: 'Preview Kartu Program Usia',
            subtitle: `Kelompok ${group} • Urutan #${sortOrder} • ${isActive ? 'Aktif di Beranda' : 'Nonaktif'}`,
            html: `
                <div class="flex flex-col items-center">
                    <p class="mb-3 text-[11px] font-bold uppercase tracking-[1.2px] text-gold">— Tampilan Kartu di Section Program Beranda</p>
                    <div class="relative h-[440px] w-full max-w-[320px] overflow-hidden rounded-2xl bg-navy-900 shadow-2xl ring-1 ring-white/10">
                        ${imgUrl ? `<img src="${escapePreviewHtml(imgUrl)}" alt="${escapePreviewHtml(title)}" referrerpolicy="no-referrer" class="absolute inset-0 size-full object-cover">` : ''}
                        <div class="absolute inset-0" style="background: linear-gradient(0deg, #001428 0%, rgba(0,20,40,.45) 52%, rgba(0,20,40,0) 100%)"></div>
                        <span class="${badgeBg} absolute left-4 top-4 rounded-full px-3 py-1 font-oswald text-xs font-bold tracking-[1.2px] text-brand">${escapePreviewHtml(ageRange)}</span>
                        <div class="absolute inset-x-0 bottom-0 p-6">
                            <h3 class="font-oswald text-2xl font-bold uppercase text-white">${escapePreviewHtml(title)}</h3>
                            <p class="pt-2 text-sm leading-[1.625] text-white/65">${escapePreviewHtml(subtitleLine)}</p>
                        </div>
                    </div>
                </div>
            `,
        };
    };

    const buildSchedulePreview = (form) => {
        const day = form.querySelector('select[name="day_of_week"]')?.value || 'Senin';
        const timeSlot = (form.querySelector('input[name="time_slot"]')?.value || '').trim() || '16:00 – 18:00';
        const target = (form.querySelector('input[name="target_category"]')?.value || '').trim() || 'Kelompok Usia';
        const activityType = form.querySelector('select[name="activity_type"]')?.value || 'Grassroot';
        const coach = (form.querySelector('input[name="coach_name"]')?.value || '').trim() || 'Nama Pelatih';
        const badgeClass =
            activityType === 'Grassroot'
                ? 'bg-gold/20 text-gold'
                : activityType === 'Youth'
                  ? 'bg-sky/20 text-sky'
                  : 'bg-white/15 text-white';

        return {
            title: 'Preview Jadwal Latihan Mingguan',
            subtitle: `Tab Hari ${day} • Section Jadwal Beranda`,
            html: `
                <div class="space-y-4 rounded-2xl border border-white/10 bg-navy-950 p-5 sm:p-6">
                    <div class="flex items-center gap-2">
                        <span class="rounded-xl border border-gold bg-gold px-4 py-2 font-oswald text-sm font-semibold tracking-[.35px] text-brand">${escapePreviewHtml(day)}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-[1px] text-sky">
                        <span class="size-2 rounded-full bg-gold"></span>
                        <span>Jadwal Latihan Hari ${escapePreviewHtml(day)}</span>
                    </div>
                    <div class="flex flex-col justify-between gap-4 rounded-xl border border-white/10 bg-white/[.08] p-5 sm:flex-row sm:items-center">
                        <div class="min-w-[160px] font-oswald text-lg font-bold text-gold">${escapePreviewHtml(timeSlot)}</div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <p class="font-oswald text-lg font-bold text-white">${escapePreviewHtml(target)}</p>
                                <span class="rounded-full ${badgeClass} px-2.5 py-0.5 text-xs font-semibold">${escapePreviewHtml(activityType)}</span>
                            </div>
                            <p class="pt-1 text-sm font-medium text-white/80">${escapePreviewHtml(coach)}</p>
                        </div>
                    </div>
                </div>
            `,
        };
    };

    const buildFaqPreview = (form) => {
        const question = (form.querySelector('input[name="question"]')?.value || '').trim() || 'Pertanyaan FAQ belum diisi';
        const answer = (form.querySelector('textarea[name="answer"]')?.value || '').trim() || 'Jawaban lengkap FAQ akan tampil di sini saat pengunjung membuka pertanyaan.';
        const sortOrder = String((form.querySelector('input[name="sort_order"]')?.value || '1').trim()).padStart(2, '0');
        const isActive = Boolean(form.querySelector('input[name="is_active"]')?.checked);

        return {
            title: 'Preview Item FAQ',
            subtitle: `Nomor Urutan #${sortOrder} • ${isActive ? 'Ditampilkan di Beranda' : 'Nonaktif'}`,
            html: `
                <div class="rounded-2xl border border-white/10 bg-[#001428] p-5 sm:p-7">
                    <div class="border-y border-white/10">
                        <div class="flex min-h-[72px] w-full items-center gap-5 py-5 text-left">
                            <span class="font-oswald text-xs tracking-[1.2px] text-gold">${escapePreviewHtml(sortOrder)}</span>
                            <span class="flex-1 font-oswald text-lg font-semibold uppercase tracking-[.5px] text-white sm:text-xl">${escapePreviewHtml(question)}</span>
                            <span class="flex size-10 shrink-0 rotate-45 items-center justify-center rounded-full border border-gold bg-gold text-brand">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33v9.33M3.33 8h9.33" stroke="currentColor" stroke-width="1.33" stroke-linecap="round"/></svg>
                            </span>
                        </div>
                        <div class="pb-6 pl-9 pr-4 text-[15px] leading-[26px] text-white/65 sm:pl-10 sm:pr-12">
                            ${escapePreviewHtml(answer).replace(/\r?\n/g, '<br>')}
                        </div>
                    </div>
                </div>
            `,
        };
    };

    const buildFeePreview = (form) => {
        const label = (form.querySelector('input[name="label"]')?.value || '').trim() || 'Label Komponen Biaya';
        const amount = (form.querySelector('input[name="amount_text"]')?.value || '').trim() || 'Rp 0';
        const feeType = form.querySelector('select[name="fee_type"]')?.value || 'monthly';
        const sortOrder = (form.querySelector('input[name="sort_order"]')?.value || '').trim() || '1';
        const isActive = Boolean(form.querySelector('input[name="is_active"]')?.checked);
        const typeLabel = feeType === 'one_time' ? 'Sekali Bayar (Blok Penuh)' : 'Iuran Bulanan (Grid 2 Kolom)';

        return {
            title: 'Preview Biaya Pendaftaran',
            subtitle: `${typeLabel} • Urutan #${sortOrder} • ${isActive ? 'Aktif di Beranda' : 'Nonaktif'}`,
            html: `
                <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-brand/90 via-[#002855] to-[#001428] p-6 sm:p-8">
                    <p class="text-xs font-medium uppercase tracking-[1.2px] text-sky">— Pendaftaran</p>
                    <h3 class="pt-2 font-oswald text-3xl font-bold uppercase leading-none text-white sm:text-4xl">Bergabung <span class="text-gold">Bersama</span> Kami</h3>
                    <div class="mt-6 rounded-xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[.3px] text-white/45">${escapePreviewHtml(label)}</p>
                        <p class="pt-1 text-lg font-semibold text-white">${escapePreviewHtml(amount)}</p>
                    </div>
                </div>
            `,
        };
    };

    const buildCategoryPreview = (form) => {
        const name = (form.querySelector('input[name="name"]')?.value || '').trim() || 'Nama Kategori Baru';
        const slug = slugifyPreview(name) || 'kategori-baru';
        const badgeColor = getCategoryBadgeClass(slug);

        return {
            title: 'Preview Kategori Berita',
            subtitle: `Slug URL otomatis: /berita?kategori=${slug}`,
            html: `
                <div class="space-y-5 rounded-2xl border border-white/10 bg-navy-950 p-6">
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-[1.2px] text-gold">— Tampilan Tombol Filter di Halaman Semua Berita</p>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex min-h-9 items-center rounded-full bg-gold px-4 py-2 font-oswald text-xs font-semibold tracking-[0.3px] text-brand">${escapePreviewHtml(name)}</span>
                            <span class="inline-flex min-h-9 items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 font-oswald text-xs font-semibold tracking-[0.3px] text-white/60">${escapePreviewHtml(name)}</span>
                        </div>
                    </div>
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-[1.2px] text-sky">— Tampilan Badge pada Kartu Berita</p>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="${badgeColor} inline-block rounded-full px-3 py-1 font-oswald text-xs font-bold text-brand">${escapePreviewHtml(name)}</span>
                            <span class="rounded bg-sky/15 px-2.5 py-1 text-xs font-medium text-sky">${escapePreviewHtml(name)}</span>
                        </div>
                    </div>
                </div>
            `,
        };
    };

    const buildEditorPreview = (form) => {
        const name = (form.querySelector('input[name="name"]')?.value || '').trim() || 'Nama Editor Baru';
        const email = (form.querySelector('input[name="email"]')?.value || '').trim() || 'editor@beebold.id';
        const initials = name.slice(0, 2).toUpperCase();

        return {
            title: 'Preview Kartu Akun Editor',
            subtitle: 'Tampilan pada halaman Kelola Editor (Akses Berita & Galeri)',
            html: `
                <div class="mx-auto max-w-md rounded-2xl border border-brand/15 bg-white p-5 text-navy-950 shadow-xl">
                    <div class="flex items-center gap-3.5">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-brand font-oswald text-base font-bold text-gold">${escapePreviewHtml(initials)}</div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-sm font-bold text-navy-950">${escapePreviewHtml(name)}</p>
                                <span class="shrink-0 rounded-full bg-sky/30 px-2.5 py-0.5 font-oswald text-[10px] font-bold uppercase tracking-wider text-brand">Editor</span>
                            </div>
                            <p class="truncate text-xs text-navy-900/60">${escapePreviewHtml(email)}</p>
                        </div>
                    </div>
                    <div class="mt-4 rounded-xl bg-[#f4f7fb] p-3 text-xs text-navy-900/70">
                        Hak Akses: <strong class="text-brand">Berita &amp; Artikel</strong> dan <strong class="text-brand">Galeri Dokumentasi</strong>.
                    </div>
                </div>
            `,
        };
    };

    const previewBuilders = {
        news: buildNewsPreview,
        gallery: buildGalleryPreview,
        program: buildProgramPreview,
        schedule: buildSchedulePreview,
        faq: buildFaqPreview,
        fee: buildFeePreview,
        category: buildCategoryPreview,
        editor: buildEditorPreview,
    };

    document.querySelectorAll('[data-preview-trigger]').forEach((triggerBtn) => {
        triggerBtn.addEventListener('click', () => {
            const form = triggerBtn.closest('form[data-preview-type]');
            if (!form) return;

            const type = form.dataset.previewType;
            const builder = previewBuilders[type];
            if (!builder) return;

            revokePreviewObjectUrls();
            const result = builder(form);

            if (dashPreviewTitle) dashPreviewTitle.textContent = result.title;
            if (dashPreviewSubtitle) dashPreviewSubtitle.textContent = result.subtitle;
            dashPreviewBody.innerHTML = result.html;

            dashPreviewModal.classList.remove('hidden');
            dashPreviewModal.classList.add('flex');
            dashPreviewModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        });
    });

    dashPreviewModal.querySelectorAll('[data-dash-preview-close]').forEach((closeBtn) => {
        closeBtn.addEventListener('click', closeDashPreviewModal);
    });

    dashPreviewModal.addEventListener('click', (event) => {
        if (event.target === dashPreviewModal) {
            closeDashPreviewModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !dashPreviewModal.classList.contains('hidden')) {
            closeDashPreviewModal();
        }
    });
}

// 3D Animated Hero Soccer Ball
import { initHeroBall3D } from './hero-ball-3d.js';
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroBall3D);
} else {
    initHeroBall3D();
}
