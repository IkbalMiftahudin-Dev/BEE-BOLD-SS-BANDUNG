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

// 3D Animated Hero Soccer Ball
import { initHeroBall3D } from './hero-ball-3d.js';
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroBall3D);
} else {
    initHeroBall3D();
}
