const homeLanding = document.querySelector('.home-landing');
const homeHeader = document.querySelector('.home-landing .site-header');
const homeNav = document.querySelector('[data-home-nav]');
const homeNavLinks = homeNav ? Array.from(homeNav.querySelectorAll('a[href^="#"]')) : [];
const homeNavigationEntry = window.performance?.getEntriesByType?.('navigation')?.[0];
const isHomeReload = Boolean(
    homeLanding && (
        homeNavigationEntry?.type === 'reload'
        || window.performance?.navigation?.type === 1
    )
);

if (homeLanding && 'scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual';
}

const syncHomeHeaderHeight = () => {
    if (!homeLanding || !homeHeader) {
        return;
    }

    homeLanding.style.setProperty('--home-header-height', `${Math.ceil(homeHeader.getBoundingClientRect().height)}px`);
};

syncHomeHeaderHeight();
window.addEventListener('load', syncHomeHeaderHeight);
window.addEventListener('resize', syncHomeHeaderHeight);

const setHomeActiveLink = (hash) => {
    if (!homeNavLinks.length) {
        return;
    }

    const hasMatchingLink = homeNavLinks.some((link) => link.getAttribute('href') === hash);

    if (!hasMatchingLink) {
        return;
    }

    homeNavLinks.forEach((link) => {
        link.classList.toggle('active', link.getAttribute('href') === hash);
    });
};

const resetHomeToTop = () => {
    if (!homeLanding) {
        return;
    }

    syncHomeHeaderHeight();
    setHomeActiveLink('#beranda');

    if (window.location.hash && window.history?.replaceState) {
        window.history.replaceState(null, '', `${window.location.pathname}${window.location.search}`);
    }

    window.scrollTo(0, 0);
};

const scrollToHomeHash = (hash, updateHistory = true) => {
    if (!hash || !hash.startsWith('#')) {
        return false;
    }

    const target = document.getElementById(hash.slice(1));

    if (!target) {
        return false;
    }

    syncHomeHeaderHeight();
    const headerOffset = (homeHeader?.offsetHeight || 0) + 18;
    const top = Math.max(0, target.getBoundingClientRect().top + window.scrollY - headerOffset);

    setHomeActiveLink(hash);
    window.scrollTo({ top, behavior: 'smooth' });

    if (updateHistory && window.history?.pushState) {
        window.history.pushState(null, '', hash);
    }

    return true;
};

const homeHashLinks = document.querySelectorAll('.home-landing a[href^="#"]');

if (homeHashLinks.length) {
    homeHashLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            scrollToHomeHash(link.getAttribute('href'));
        });
    });

    if (isHomeReload) {
        resetHomeToTop();
        window.addEventListener('load', () => {
            window.requestAnimationFrame(resetHomeToTop);
        });
        window.addEventListener('pageshow', resetHomeToTop);
    } else if (window.location.hash) {
        window.requestAnimationFrame(() => {
            scrollToHomeHash(window.location.hash, false);
        });
    }
}

window.addEventListener('hashchange', () => {
    setHomeActiveLink(window.location.hash || '#beranda');
});

const trackingForm = document.querySelector('#trackingForm');
const nomorNotaInput = document.querySelector('#nomorNota');

if (trackingForm && nomorNotaInput) {
    trackingForm.addEventListener('submit', (event) => {
        const nomorNota = nomorNotaInput.value.trim();

        if (!nomorNota) {
            event.preventDefault();
            nomorNotaInput.focus();
            nomorNotaInput.setAttribute('aria-invalid', 'true');
            return;
        }

        nomorNotaInput.removeAttribute('aria-invalid');
    });
}

const passwordToggle = document.querySelector('[data-password-toggle]');
const adminPasswordInput = document.querySelector('#adminPassword');

if (passwordToggle && adminPasswordInput) {
    passwordToggle.addEventListener('click', () => {
        const isHidden = adminPasswordInput.type === 'password';
        adminPasswordInput.type = isHidden ? 'text' : 'password';
        passwordToggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
    });
}

const dashboardMenuToggle = document.querySelector('[data-dashboard-menu-toggle]');
const dashboardSidebar = document.querySelector('[data-dashboard-sidebar]');

if (dashboardMenuToggle && dashboardSidebar) {
    dashboardMenuToggle.addEventListener('click', () => {
        document.body.classList.toggle('dashboard-sidebar-open');
    });

    dashboardSidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            document.body.classList.remove('dashboard-sidebar-open');
        });
    });
}

const dashboardPopoverTriggers = Array.from(document.querySelectorAll('[data-dashboard-popover-trigger]'));
const dashboardPopovers = Array.from(document.querySelectorAll('[data-dashboard-popover]'));
const dashboardUserbar = document.querySelector('[data-dashboard-userbar]');

if (dashboardPopoverTriggers.length && dashboardPopovers.length) {
    let activeDashboardPopover = null;
    let activeDashboardPopoverTrigger = null;

    const findDashboardPopover = (name) => dashboardPopovers.find((popover) => popover.dataset.dashboardPopover === name);

    const syncDashboardPopoverArrow = () => {
        if (!activeDashboardPopover || !activeDashboardPopoverTrigger || activeDashboardPopover.hidden) {
            return;
        }

        const popoverRect = activeDashboardPopover.getBoundingClientRect();
        const triggerRect = activeDashboardPopoverTrigger.getBoundingClientRect();
        const triggerCenter = triggerRect.left + (triggerRect.width / 2);
        const arrowLeft = Math.max(24, Math.min(popoverRect.width - 24, triggerCenter - popoverRect.left));

        activeDashboardPopover.style.setProperty('--dashboard-popover-arrow-left', `${Math.round(arrowLeft)}px`);
    };

    const closeDashboardPopovers = (restoreFocus = false) => {
        const triggerToFocus = activeDashboardPopoverTrigger;

        dashboardPopovers.forEach((popover) => {
            popover.hidden = true;
        });

        dashboardPopoverTriggers.forEach((trigger) => {
            trigger.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        });

        activeDashboardPopover = null;
        activeDashboardPopoverTrigger = null;

        if (restoreFocus) {
            triggerToFocus?.focus();
        }
    };

    const openDashboardPopover = (trigger) => {
        const popover = findDashboardPopover(trigger.dataset.dashboardPopoverTrigger);

        if (!popover) {
            return;
        }

        if (activeDashboardPopover === popover && !popover.hidden) {
            closeDashboardPopovers();
            return;
        }

        closeDashboardPopovers();
        activeDashboardPopover = popover;
        activeDashboardPopoverTrigger = trigger;
        popover.hidden = false;
        trigger.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        window.requestAnimationFrame(syncDashboardPopoverArrow);
    };

    dashboardPopoverTriggers.forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.stopPropagation();
            openDashboardPopover(trigger);
        });
    });

    const setDashboardPopoverRead = (type) => {
        const popover = findDashboardPopover(type);

        popover?.querySelectorAll('.is-unread').forEach((item) => {
            item.classList.remove('is-unread');
        });

        popover?.querySelectorAll('.dashboard-popover-dot').forEach((dot) => {
            dot.hidden = true;
        });

        const trigger = dashboardPopoverTriggers.find((item) => item.dataset.dashboardPopoverTrigger === type);
        const badge = trigger?.querySelector('[data-dashboard-badge]');

        if (badge) {
            badge.textContent = '0';
            badge.hidden = true;
        }
    };

    const persistDashboardPopoverRead = (type) => {
        const url = dashboardUserbar?.dataset.dashboardReadUrl;
        const csrf = dashboardUserbar?.dataset.dashboardCsrf;

        if (!url || !csrf || !window.fetch) {
            return;
        }

        const formData = new FormData();
        formData.append('type', type);
        formData.append('_token', csrf);

        window.fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).catch(() => {});
    };

    const markDashboardPopoverRead = (type) => {
        if (!type) {
            return;
        }

        setDashboardPopoverRead(type);
        persistDashboardPopoverRead(type);
    };

    document.querySelectorAll('[data-dashboard-mark-read]').forEach((button) => {
        button.addEventListener('click', () => {
            markDashboardPopoverRead(button.dataset.dashboardMarkRead);
        });
    });

    document.querySelectorAll('[data-dashboard-mark-read-link]').forEach((link) => {
        link.addEventListener('click', () => {
            markDashboardPopoverRead(link.dataset.dashboardMarkReadLink);
        });
    });

    document.addEventListener('click', (event) => {
        if (
            event.target.closest('[data-dashboard-popover]')
            || event.target.closest('[data-dashboard-popover-trigger]')
        ) {
            return;
        }

        closeDashboardPopovers();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && activeDashboardPopover) {
            closeDashboardPopovers(true);
        }
    });

    window.addEventListener('resize', syncDashboardPopoverArrow);
    window.addEventListener('scroll', syncDashboardPopoverArrow, true);
}

const dashboardPeriodSelect = document.querySelector('[data-dashboard-period-select]');

if (dashboardPeriodSelect) {
    dashboardPeriodSelect.addEventListener('change', () => {
        dashboardPeriodSelect.form?.submit();
    });
}

document.querySelectorAll('[data-dashboard-period-form]').forEach((periodForm) => {
    const periodToggle = periodForm.querySelector('[data-dashboard-period-toggle]');
    const periodMenu = periodForm.querySelector('[data-dashboard-period-menu]');
    const periodInput = periodForm.querySelector('[data-dashboard-period-input]');
    const periodOptions = Array.from(periodForm.querySelectorAll('[data-dashboard-period-option]'));

    if (!periodToggle || !periodMenu || !periodOptions.length) {
        return;
    }

    let activePeriodIndex = Math.max(0, periodOptions.findIndex((option) => option.getAttribute('aria-selected') === 'true'));

    const closePeriodMenu = (restoreFocus = false) => {
        periodMenu.hidden = true;
        periodForm.classList.remove('is-open');
        periodToggle.setAttribute('aria-expanded', 'false');

        if (restoreFocus) {
            periodToggle.focus();
        }
    };

    const focusPeriodOption = (index) => {
        activePeriodIndex = (index + periodOptions.length) % periodOptions.length;
        periodOptions[activePeriodIndex].focus();
    };

    const openPeriodMenu = (focusIndex = activePeriodIndex) => {
        periodMenu.hidden = false;
        periodForm.classList.add('is-open');
        periodToggle.setAttribute('aria-expanded', 'true');
        window.requestAnimationFrame(() => focusPeriodOption(focusIndex));
    };

    periodToggle.addEventListener('click', (event) => {
        event.stopPropagation();

        if (periodMenu.hidden) {
            openPeriodMenu();
            return;
        }

        closePeriodMenu();
    });

    periodOptions.forEach((option, index) => {
        option.addEventListener('focus', () => {
            activePeriodIndex = index;
        });

        option.addEventListener('click', () => {
            if (periodInput) {
                periodInput.value = option.value;
            }
        });
    });

    periodForm.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown' && periodMenu.hidden) {
            event.preventDefault();
            openPeriodMenu();
            return;
        }

        if (event.key === 'ArrowUp' && periodMenu.hidden) {
            event.preventDefault();
            openPeriodMenu(periodOptions.length - 1);
            return;
        }

        if (periodMenu.hidden) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            closePeriodMenu(true);
            return;
        }

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            focusPeriodOption(activePeriodIndex + 1);
            return;
        }

        if (event.key === 'ArrowUp') {
            event.preventDefault();
            focusPeriodOption(activePeriodIndex - 1);
            return;
        }

        if (event.key === 'Home') {
            event.preventDefault();
            focusPeriodOption(0);
            return;
        }

        if (event.key === 'End') {
            event.preventDefault();
            focusPeriodOption(periodOptions.length - 1);
        }
    });

    document.addEventListener('click', (event) => {
        if (!periodForm.contains(event.target)) {
            closePeriodMenu();
        }
    });
});

const laundryModalOpenButtons = Array.from(document.querySelectorAll('[data-laundry-modal-open]'));
const laundryModals = Array.from(document.querySelectorAll('[data-laundry-modal]'));

if (laundryModalOpenButtons.length && laundryModals.length) {
    const previousLaundryFocus = new WeakMap();

    const updateCharacterCounter = (source) => {
        const key = source.dataset.characterCounterSource;

        if (!key) {
            return;
        }

        const maxLength = source.getAttribute('maxlength') || source.dataset.characterCounterMax || '200';

        document.querySelectorAll('[data-character-counter]').forEach((counter) => {
            if (counter.dataset.characterCounter === key) {
                counter.textContent = `${source.value.length} / ${maxLength}`;
            }
        });
    };

    const updateCharacterCounters = (root = document) => {
        root.querySelectorAll('[data-character-counter-source]').forEach(updateCharacterCounter);
    };

    const findLaundryModal = (target) => {
        if (!target) {
            return laundryModals[0];
        }

        return laundryModals.find((modal) => modal.dataset.laundryModal === target) || laundryModals[0];
    };

    const findFirstLaundryField = (modal) => modal.querySelector(
        '[data-laundry-first-field], input:not([type="hidden"]):not([readonly]), select, textarea'
    );

    const closeLaundryModal = (modal) => {
        modal.hidden = true;

        if (!laundryModals.some((item) => !item.hidden)) {
            document.body.classList.remove('laundry-modal-open');
        }

        const previousFocus = previousLaundryFocus.get(modal);

        if (previousFocus) {
            previousFocus.focus();
        }
    };

    const openLaundryModal = (modal) => {
        previousLaundryFocus.set(modal, document.activeElement);
        modal.hidden = false;
        document.body.classList.add('laundry-modal-open');
        window.requestAnimationFrame(() => findFirstLaundryField(modal)?.focus());
    };

    laundryModalOpenButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openLaundryModal(findLaundryModal(button.dataset.laundryModalOpen));
        });
    });

    laundryModals.forEach((modal) => {
        const laundryForm = modal.querySelector('[data-laundry-form]');
        const laundryCloseButtons = modal.querySelectorAll('[data-laundry-modal-close]');
        const laundryResetButton = modal.querySelector('[data-laundry-form-reset]');
        const laundryDateInputs = modal.querySelectorAll('[data-laundry-date-input]');

        laundryCloseButtons.forEach((button) => {
            button.addEventListener('click', () => closeLaundryModal(modal));
        });

        laundryResetButton?.addEventListener('click', () => {
            laundryForm?.reset();
            laundryDateInputs.forEach((input) => {
                input.type = 'text';
            });
            window.requestAnimationFrame(() => {
                updateCharacterCounters(modal);
                findFirstLaundryField(modal)?.focus();
            });
        });

        modal.querySelectorAll('[data-character-counter-source]').forEach((source) => {
            source.addEventListener('input', () => updateCharacterCounter(source));
            updateCharacterCounter(source);
        });

        laundryDateInputs.forEach((input) => {
            input.addEventListener('focus', () => {
                input.type = 'date';
            });

            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.type = 'text';
                }
            });
        });

        laundryForm?.addEventListener('submit', () => {
            closeLaundryModal(modal);
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeLaundryModal(modal);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        const openModal = laundryModals.find((modal) => !modal.hidden);

        if (openModal) {
            closeLaundryModal(openModal);
        }
    });
}

const laundryCrudDataElement = document.querySelector('#laundryCrudData');
const laundryCrudModal = document.querySelector('[data-laundry-modal="cucian"]');
const laundryCrudForm = document.querySelector('[data-laundry-crud-form]');

if (laundryCrudDataElement && laundryCrudModal && laundryCrudForm) {
    const payload = JSON.parse(laundryCrudDataElement.textContent || '{"orders":[]}');
    const laundryOrders = new Map((payload.orders || []).map((order) => [String(order.nota), order]));
    const laundryHeader = laundryCrudModal.querySelector('[data-laundry-modal-header]');
    const laundryTitle = laundryHeader?.querySelector('h2');
    const laundryIntro = laundryHeader?.querySelector('p');
    const laundryNotaField = laundryCrudForm.querySelector('[data-laundry-nota-field]');
    const laundryNotaDisplay = laundryCrudForm.querySelector('[data-laundry-nota-display]');
    const laundrySaveButton = laundryCrudForm.querySelector('[data-laundry-save-button]');
    const laundrySubmitLabel = laundryCrudForm.querySelector('[data-laundry-submit-label]');
    const laundryResetButton = laundryCrudForm.querySelector('[data-laundry-form-reset]');
    const laundryCancelButton = laundryCrudForm.querySelector('.laundry-cancel-button');
    const laundryDeleteForm = document.querySelector('[data-laundry-delete-form]');
    const laundryControls = Array.from(laundryCrudForm.querySelectorAll('input:not([type="hidden"]), select, textarea'));
    const laundryDateInputs = Array.from(laundryCrudForm.querySelectorAll('[data-laundry-date-input]'));

    const ensureSelectOption = (select, value, label = value) => {
        if (!select || !value) {
            return;
        }

        const hasOption = Array.from(select.options).some((option) => option.value === String(value));

        if (!hasOption) {
            select.add(new Option(label, value));
        }
    };

    const openLaundryCrudModal = () => {
        laundryCrudModal.hidden = false;
        document.body.classList.add('laundry-modal-open');
        window.requestAnimationFrame(() => {
            laundryCrudForm.querySelector('input:not([type="hidden"]):not([readonly]):not([disabled]), select:not([disabled]), textarea:not([disabled])')?.focus();
        });
    };

    const setLaundryMode = (mode) => {
        const isView = mode === 'view';

        laundryControls.forEach((control) => {
            if (control === laundryNotaDisplay) {
                control.disabled = false;
                control.readOnly = true;
                return;
            }

            control.disabled = isView;

            if (control.matches('input, textarea')) {
                control.readOnly = isView;
            }
        });

        if (laundrySaveButton) {
            laundrySaveButton.hidden = isView;
        }

        if (laundryResetButton) {
            laundryResetButton.hidden = mode !== 'create';
        }

        if (laundryCancelButton) {
            laundryCancelButton.textContent = mode === 'create' ? 'Batal' : 'Tutup';
        }
    };

    const resetLaundryForm = () => {
        laundryCrudForm.reset();
        laundryCrudForm.action = laundryCrudForm.dataset.createAction || laundryCrudForm.action;

        if (laundryNotaField) {
            laundryNotaField.value = '';
        }

        if (laundryNotaDisplay) {
            laundryNotaDisplay.value = 'Otomatis';
        }

        laundryDateInputs.forEach((input) => {
            input.type = 'text';
        });

        if (laundryTitle) {
            laundryTitle.textContent = 'Tambah Data Cucian';
        }

        if (laundryIntro) {
            laundryIntro.textContent = 'Masukkan data cucian pelanggan dengan lengkap.';
        }

        if (laundrySubmitLabel) {
            laundrySubmitLabel.textContent = 'Simpan Data';
        }

        setLaundryMode('create');
    };

    const fillLaundryForm = (order) => {
        const fields = laundryCrudForm.elements;
        const serviceValue = order.serviceId || order.service || '';

        if (laundryNotaField) {
            laundryNotaField.value = order.nota || '';
        }

        if (laundryNotaDisplay) {
            laundryNotaDisplay.value = order.nota || 'Otomatis';
        }

        ensureSelectOption(fields.service, serviceValue, order.service || serviceValue);
        ensureSelectOption(fields.unit, order.unit || 'kg');
        ensureSelectOption(fields.initial_status, order.status || 'Antrean');

        fields.customer_name.value = order.name || '';
        fields.phone.value = order.phone || '';
        fields.service.value = serviceValue;
        fields.weight.value = order.weight || '';
        fields.unit.value = order.unit || 'kg';
        fields.date_in.value = order.dateIn || '';
        fields.eta.value = order.eta || '';
        fields.initial_status.value = order.status || 'Antrean';
        fields.total.value = order.total || '';
        fields.notes.value = order.notes || '';

        laundryDateInputs.forEach((input) => {
            input.type = input.value ? 'date' : 'text';
        });
    };

    const showLaundryOrder = (nota, mode) => {
        const order = laundryOrders.get(String(nota));

        if (!order) {
            return;
        }

        laundryCrudForm.action = mode === 'edit'
            ? laundryCrudForm.dataset.updateAction || laundryCrudForm.action
            : laundryCrudForm.dataset.createAction || laundryCrudForm.action;

        fillLaundryForm(order);

        if (laundryTitle) {
            laundryTitle.textContent = mode === 'edit' ? 'Edit Data Cucian' : 'Detail Data Cucian';
        }

        if (laundryIntro) {
            laundryIntro.textContent = `${order.nota || 'No. nota'} - ${order.name || 'Pelanggan'}`;
        }

        if (laundrySubmitLabel) {
            laundrySubmitLabel.textContent = 'Simpan Perubahan';
        }

        setLaundryMode(mode);
        openLaundryCrudModal();
    };

    const normalizeWhatsappNumber = (value) => {
        let phone = String(value || '').replace(/\D/g, '');

        if (phone.startsWith('0')) {
            phone = `62${phone.slice(1)}`;
        } else if (phone.startsWith('8')) {
            phone = `62${phone}`;
        }

        return phone;
    };

    const whatsappMessage = (order) => {
        const fallback = [
            `Halo ${order.name || 'Pelanggan'}, berikut informasi cucian Anda.`,
            `No. Nota: ${order.nota || '-'}`,
            `Status: ${order.status || '-'}`,
            `Estimasi selesai: ${order.etaText || order.eta || '-'}`,
            `Total: ${order.totalText || '-'}`
        ].join('\n');

        if (!['Selesai', 'Diambil'].includes(order.status || '')) {
            return fallback;
        }

        const template = String(payload.whatsappTemplate || '');
        const message = template.replaceAll('{nama_pelanggan}', order.name || '')
            .replaceAll('{kode_pesanan}', order.nota || '')
            .replaceAll('{no_nota}', order.nota || '')
            .replaceAll('{status_cucian}', order.status || '')
            .replaceAll('{estimasi_selesai}', order.etaText || order.eta || '')
            .replaceAll('{total_harga}', order.totalText || '');

        return message.trim() || fallback;
    };

    document.querySelectorAll('[data-laundry-create]').forEach((button) => {
        button.addEventListener('click', () => {
            resetLaundryForm();
        });
    });

    laundryResetButton?.addEventListener('click', () => {
        window.requestAnimationFrame(resetLaundryForm);
    });

    document.querySelectorAll('[data-laundry-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.laundryAction;
            const nota = button.dataset.laundryNota;
            const order = laundryOrders.get(String(nota));

            if (!order) {
                return;
            }

            if (action === 'view' || action === 'edit') {
                showLaundryOrder(nota, action);
                return;
            }

            if (action === 'whatsapp') {
                const phone = normalizeWhatsappNumber(order.phone);

                if (!phone) {
                    window.alert('Nomor WhatsApp pelanggan belum tersedia.');
                    return;
                }

                window.open(`https://wa.me/${phone}?text=${encodeURIComponent(whatsappMessage(order))}`, '_blank', 'noopener');
                return;
            }

            if (action !== 'delete' || !laundryDeleteForm) {
                return;
            }

            if (!window.confirm(`Hapus data cucian "${order.nota}" atas nama ${order.name || 'pelanggan ini'}? Tindakan ini tidak bisa dibatalkan.`)) {
                return;
            }

            laundryDeleteForm.elements.nota.value = order.nota;
            laundryDeleteForm.submit();
        });
    });

    resetLaundryForm();
}

const packageCrudDataElement = document.querySelector('#packageCrudData');
const packageModal = document.querySelector('[data-laundry-modal="package"]');
const packageForm = document.querySelector('[data-package-form]');

if (packageCrudDataElement && packageModal && packageForm) {
    const packageRows = JSON.parse(packageCrudDataElement.textContent || '[]');
    const packages = new Map(packageRows.map((item) => [String(item.id), item]));
    const packageHeader = packageModal.querySelector('[data-package-modal-header]');
    const packageTitle = packageHeader?.querySelector('h2');
    const packageIntro = packageHeader?.querySelector('p');
    const packageIdField = packageForm.querySelector('[data-package-id-field]');
    const packageCodeField = packageForm.querySelector('[data-package-code-field]');
    const packageActions = packageForm.querySelector('.laundry-modal-actions');
    const packageSaveButton = packageForm.querySelector('[data-package-save-button]');
    const packageSubmitLabel = packageForm.querySelector('[data-package-submit-label]');
    const packageResetButton = packageForm.querySelector('[data-laundry-form-reset]');
    const packageCancelButton = packageForm.querySelector('.laundry-cancel-button');
    const packageDeleteForm = document.querySelector('[data-package-delete-form]');
    const packageControls = Array.from(packageForm.querySelectorAll('input:not([type="hidden"]), select, textarea'));
    let previousPackageFocus = null;

    const ensureSelectOption = (select, value, label = value) => {
        if (!select || !value) {
            return;
        }

        const hasOption = Array.from(select.options).some((option) => option.value === String(value));

        if (!hasOption) {
            select.add(new Option(label, value));
        }
    };

    const updatePackageCounter = () => {
        packageForm.querySelectorAll('[data-character-counter-source]').forEach((source) => {
            const key = source.dataset.characterCounterSource;
            const maxLength = source.getAttribute('maxlength') || source.dataset.characterCounterMax || '200';

            document.querySelectorAll('[data-character-counter]').forEach((counter) => {
                if (counter.dataset.characterCounter === key) {
                    counter.textContent = `${source.value.length} / ${maxLength}`;
                }
            });
        });
    };

    const openPackageModal = () => {
        previousPackageFocus = document.activeElement;
        packageModal.hidden = false;
        document.body.classList.add('laundry-modal-open');
        window.requestAnimationFrame(() => {
            packageForm.querySelector('[data-laundry-first-field], input:not([type="hidden"]):not([readonly]):not([disabled]), select:not([disabled]), textarea:not([disabled])')?.focus();
        });
    };

    const setPackageControlsState = (mode) => {
        const isView = mode === 'view';

        packageControls.forEach((control) => {
            if (control === packageCodeField) {
                control.disabled = false;
                control.readOnly = true;
                return;
            }

            control.disabled = isView;

            if (control.matches('input, textarea')) {
                control.readOnly = isView;
            }
        });

        if (packageActions) {
            packageActions.hidden = isView;
        }

        if (packageSaveButton) {
            packageSaveButton.hidden = isView;
        }

        if (packageResetButton) {
            packageResetButton.hidden = isView;
        }

        if (packageCancelButton) {
            packageCancelButton.textContent = mode === 'create' ? 'Batal' : 'Tutup';
        }
    };

    const fillPackageForm = (item) => {
        const fields = packageForm.elements;

        if (packageIdField) {
            packageIdField.value = item.id || '';
        }

        if (packageCodeField) {
            packageCodeField.value = item.code || packageCodeField.dataset.nextPackageCode || '';
        }

        fields.package_name.value = item.name || '';
        fields.price.value = item.price || '';
        fields.description.value = item.description || '';

        ensureSelectOption(fields.category, item.category);
        ensureSelectOption(fields.unit_label, item.unit);
        ensureSelectOption(fields.duration, item.duration, `${item.duration} hari`);
        ensureSelectOption(fields.status, item.status);

        fields.category.value = item.category || '';
        fields.unit_label.value = item.unit || '';
        fields.duration.value = item.duration || '';
        fields.status.value = item.status || 'Aktif';
        updatePackageCounter();
    };

    const resetPackageForm = () => {
        packageForm.reset();
        packageForm.action = packageForm.dataset.createAction || packageForm.action;

        if (packageIdField) {
            packageIdField.value = '';
        }

        if (packageCodeField) {
            packageCodeField.value = packageCodeField.dataset.nextPackageCode || packageCodeField.value;
        }

        if (packageTitle) {
            packageTitle.textContent = 'Tambah Paket Laundry';
        }

        if (packageIntro) {
            packageIntro.textContent = 'Masukkan data paket laundry dengan lengkap.';
        }

        if (packageSubmitLabel) {
            packageSubmitLabel.textContent = 'Simpan Paket';
        }

        setPackageControlsState('create');
        updatePackageCounter();
    };

    const showPackage = (id, mode) => {
        const item = packages.get(String(id));

        if (!item) {
            return;
        }

        packageForm.action = mode === 'edit'
            ? packageForm.dataset.updateAction || packageForm.action
            : packageForm.dataset.createAction || packageForm.action;

        fillPackageForm(item);

        if (packageTitle) {
            packageTitle.textContent = mode === 'edit' ? 'Edit Paket Laundry' : 'Detail Paket Laundry';
        }

        if (packageIntro) {
            packageIntro.textContent = `${item.code || 'Paket'} - ${item.name || 'Paket Laundry'}`;
        }

        if (packageSubmitLabel) {
            packageSubmitLabel.textContent = 'Simpan Perubahan';
        }

        setPackageControlsState(mode);
        openPackageModal();
    };

    document.querySelectorAll('[data-package-create]').forEach((button) => {
        button.addEventListener('click', () => {
            resetPackageForm();
            openPackageModal();
        });
    });

    document.querySelectorAll('[data-package-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.packageAction;
            const id = button.dataset.packageId;

            if (action === 'view' || action === 'edit') {
                showPackage(id, action);
                return;
            }

            if (action !== 'delete' || !packageDeleteForm) {
                return;
            }

            const item = packages.get(String(id));
            const name = item?.name || 'paket ini';

            if (!window.confirm(`Hapus paket "${name}"? Tindakan ini tidak bisa dibatalkan.`)) {
                return;
            }

            packageDeleteForm.elements.package_id.value = id;
            packageDeleteForm.submit();
        });
    });

    packageModal.addEventListener('click', (event) => {
        if (event.target === packageModal && previousPackageFocus) {
            previousPackageFocus.focus();
        }
    });

    resetPackageForm();
}

const customerCrudDataElement = document.querySelector('#customerCrudData');
const customerModal = document.querySelector('[data-laundry-modal="customer"]');
const customerForm = document.querySelector('[data-customer-form]');

if (customerCrudDataElement && customerModal && customerForm) {
    const customerRows = JSON.parse(customerCrudDataElement.textContent || '[]');
    const customers = new Map(customerRows.map((item) => [String(item.id), item]));
    const customerHeader = customerModal.querySelector('[data-customer-modal-header]');
    const customerTitle = customerHeader?.querySelector('h2');
    const customerIntro = customerHeader?.querySelector('p');
    const customerIdField = customerForm.querySelector('[data-customer-id-field]');
    const customerCodeField = customerForm.querySelector('[data-customer-code-field]');
    const customerSaveButton = customerForm.querySelector('[data-customer-save-button]');
    const customerSubmitLabel = customerForm.querySelector('[data-customer-submit-label]');
    const customerResetButton = customerForm.querySelector('[data-laundry-form-reset]');
    const customerCancelButton = customerForm.querySelector('.laundry-cancel-button');
    const customerDeleteForm = document.querySelector('[data-customer-delete-form]');
    const customerControls = Array.from(customerForm.querySelectorAll('input:not([type="hidden"]), select, textarea'));
    let previousCustomerFocus = null;

    const openCustomerModal = () => {
        previousCustomerFocus = document.activeElement;
        customerModal.hidden = false;
        document.body.classList.add('laundry-modal-open');
        window.requestAnimationFrame(() => {
            customerForm.querySelector('[data-laundry-first-field], input:not([type="hidden"]):not([readonly]):not([disabled]), select:not([disabled]), textarea:not([disabled])')?.focus();
        });
    };

    const setCustomerControlsState = (mode) => {
        const isView = mode === 'view';

        customerControls.forEach((control) => {
            if (control === customerCodeField) {
                control.disabled = false;
                control.readOnly = true;
                return;
            }

            control.disabled = isView;

            if (control.matches('input, textarea')) {
                control.readOnly = isView;
            }
        });

        if (customerSaveButton) {
            customerSaveButton.hidden = isView;
        }

        if (customerResetButton) {
            customerResetButton.hidden = mode !== 'create';
        }

        if (customerCancelButton) {
            customerCancelButton.textContent = mode === 'create' ? 'Batal' : 'Tutup';
        }
    };

    const resetCustomerForm = () => {
        customerForm.reset();
        customerForm.action = customerForm.dataset.createAction || customerForm.action;

        if (customerIdField) {
            customerIdField.value = '';
        }

        if (customerCodeField) {
            customerCodeField.value = customerCodeField.dataset.nextCustomerCode || customerCodeField.value;
        }

        if (customerTitle) {
            customerTitle.textContent = 'Tambah Data Pelanggan';
        }

        if (customerIntro) {
            customerIntro.textContent = 'Masukkan data pelanggan dengan lengkap.';
        }

        if (customerSubmitLabel) {
            customerSubmitLabel.textContent = 'Simpan Data';
        }

        setCustomerControlsState('create');
    };

    const fillCustomerForm = (customer) => {
        const fields = customerForm.elements;

        if (customerIdField) {
            customerIdField.value = customer.id || '';
        }

        if (customerCodeField) {
            customerCodeField.value = customer.code || customerCodeField.dataset.nextCustomerCode || '';
        }

        fields.phone.value = customer.phone || '';
        fields.customer_name.value = customer.name || '';
        fields.address.value = customer.address || '';
    };

    const showCustomer = (id, mode) => {
        const customer = customers.get(String(id));

        if (!customer) {
            return;
        }

        customerForm.action = mode === 'edit'
            ? customerForm.dataset.updateAction || customerForm.action
            : customerForm.dataset.createAction || customerForm.action;

        fillCustomerForm(customer);

        if (customerTitle) {
            customerTitle.textContent = mode === 'edit' ? 'Edit Data Pelanggan' : 'Detail Data Pelanggan';
        }

        if (customerIntro) {
            const meta = [
                customer.transactions ? `${customer.transactions} transaksi` : 'Belum ada transaksi',
                customer.active ? `${customer.active} cucian berjalan` : 'Tidak ada cucian berjalan',
                `Terakhir: ${customer.last || '-'}`
            ].join(' | ');
            customerIntro.textContent = `${customer.code || 'Pelanggan'} - ${customer.name || 'Pelanggan'} (${meta})`;
        }

        if (customerSubmitLabel) {
            customerSubmitLabel.textContent = 'Simpan Perubahan';
        }

        setCustomerControlsState(mode);
        openCustomerModal();
    };

    document.querySelectorAll('[data-customer-create]').forEach((button) => {
        button.addEventListener('click', () => {
            resetCustomerForm();
        });
    });

    customerResetButton?.addEventListener('click', () => {
        window.requestAnimationFrame(resetCustomerForm);
    });

    document.querySelectorAll('[data-customer-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.customerAction;
            const id = button.dataset.customerId;

            if (action === 'view' || action === 'edit') {
                showCustomer(id, action);
                return;
            }

            if (action !== 'delete' || !customerDeleteForm) {
                return;
            }

            const customer = customers.get(String(id));

            if (!customer) {
                return;
            }

            if (customer.transactions > 0) {
                window.alert('Pelanggan yang sudah memiliki transaksi tidak bisa dihapus agar riwayat laundry tetap tersimpan.');
                return;
            }

            if (!window.confirm(`Hapus pelanggan "${customer.name || customer.code || 'ini'}"? Tindakan ini tidak bisa dibatalkan.`)) {
                return;
            }

            customerDeleteForm.elements.customer_id.value = id;
            customerDeleteForm.submit();
        });
    });

    customerModal.addEventListener('click', (event) => {
        if (event.target === customerModal && previousCustomerFocus) {
            previousCustomerFocus.focus();
        }
    });

    resetCustomerForm();
}

const transactionDataElement = document.querySelector('#transactionData');
const transactionReceiptModal = document.querySelector('[data-transaction-receipt-modal]');

if (transactionDataElement && transactionReceiptModal) {
    const payload = JSON.parse(transactionDataElement.textContent || '{"transactions":[]}');
    const transactions = new Map((payload.transactions || []).map((item) => [String(item.nota), item]));
    let previousTransactionFocus = null;

    const transactionReceiptFields = {
        title: transactionReceiptModal.querySelector('#transactionReceiptTitle'),
        subtitle: transactionReceiptModal.querySelector('[data-transaction-receipt-subtitle]'),
        nota: transactionReceiptModal.querySelector('[data-transaction-receipt-nota]'),
        customer: transactionReceiptModal.querySelector('[data-transaction-receipt-customer]'),
        phone: transactionReceiptModal.querySelector('[data-transaction-receipt-phone]'),
        service: transactionReceiptModal.querySelector('[data-transaction-receipt-service]'),
        weight: transactionReceiptModal.querySelector('[data-transaction-receipt-weight]'),
        inDate: transactionReceiptModal.querySelector('[data-transaction-receipt-in]'),
        eta: transactionReceiptModal.querySelector('[data-transaction-receipt-eta]'),
        status: transactionReceiptModal.querySelector('[data-transaction-receipt-status]'),
        notesRow: transactionReceiptModal.querySelector('[data-transaction-receipt-notes-row]'),
        notes: transactionReceiptModal.querySelector('[data-transaction-receipt-notes]'),
        total: transactionReceiptModal.querySelector('[data-transaction-receipt-total]'),
        whatsapp: transactionReceiptModal.querySelector('[data-transaction-receipt-wa]'),
    };

    const setTransactionText = (field, value) => {
        if (field) {
            field.textContent = value || '-';
        }
    };

    const closeTransactionReceipt = () => {
        transactionReceiptModal.hidden = true;
        document.body.classList.remove('laundry-modal-open');

        if (previousTransactionFocus) {
            previousTransactionFocus.focus();
        }
    };

    const openTransactionReceipt = (nota, trigger) => {
        const transaction = transactions.get(String(nota));

        if (!transaction) {
            return;
        }

        previousTransactionFocus = trigger || document.activeElement;
        setTransactionText(transactionReceiptFields.title, `Detail Nota ${transaction.nota || ''}`.trim());
        setTransactionText(transactionReceiptFields.subtitle, `${transaction.name || 'Pelanggan'} - ${transaction.service || 'Layanan'}`);
        setTransactionText(transactionReceiptFields.nota, transaction.nota);
        setTransactionText(transactionReceiptFields.customer, transaction.name);
        setTransactionText(transactionReceiptFields.phone, transaction.phone);
        setTransactionText(transactionReceiptFields.service, transaction.service);
        setTransactionText(transactionReceiptFields.weight, transaction.weight);
        setTransactionText(transactionReceiptFields.inDate, transaction.in);
        setTransactionText(transactionReceiptFields.eta, transaction.eta);
        setTransactionText(transactionReceiptFields.total, transaction.total);

        if (transactionReceiptFields.status) {
            transactionReceiptFields.status.className = `status-pill ${transaction.tone || 'blue'}`;
            transactionReceiptFields.status.textContent = transaction.status || '-';
        }

        if (transactionReceiptFields.notesRow && transactionReceiptFields.notes) {
            const notes = String(transaction.notes || '').trim();
            transactionReceiptFields.notesRow.hidden = notes === '';
            transactionReceiptFields.notes.textContent = notes;
        }

        if (transactionReceiptFields.whatsapp) {
            if (transaction.whatsappUrl) {
                transactionReceiptFields.whatsapp.href = transaction.whatsappUrl;
                transactionReceiptFields.whatsapp.hidden = false;
            } else {
                transactionReceiptFields.whatsapp.hidden = true;
                transactionReceiptFields.whatsapp.removeAttribute('href');
            }
        }

        transactionReceiptModal.hidden = false;
        document.body.classList.add('laundry-modal-open');
        window.requestAnimationFrame(() => {
            transactionReceiptModal.querySelector('[data-transaction-receipt-close]')?.focus();
        });
    };

    document.querySelectorAll('[data-transaction-action="view"]').forEach((button) => {
        button.addEventListener('click', () => {
            openTransactionReceipt(button.dataset.transactionNota, button);
        });
    });

    transactionReceiptModal.querySelectorAll('[data-transaction-receipt-close]').forEach((button) => {
        button.addEventListener('click', closeTransactionReceipt);
    });

    transactionReceiptModal.addEventListener('click', (event) => {
        if (event.target === transactionReceiptModal) {
            closeTransactionReceipt();
        }
    });

    transactionReceiptModal.querySelector('[data-transaction-receipt-print]')?.addEventListener('click', () => {
        document.body.classList.add('transaction-receipt-printing');
        window.print();
        window.setTimeout(() => {
            document.body.classList.remove('transaction-receipt-printing');
        }, 300);
    });

    document.querySelector('.print-recap-button')?.addEventListener('click', () => {
        window.print();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !transactionReceiptModal.hidden) {
            closeTransactionReceipt();
        }
    });
}

const statusOrdersData = document.querySelector('#statusOrdersData');

if (statusOrdersData) {
    const payload = JSON.parse(statusOrdersData.textContent);
    const orders = new Map(payload.orders.map((order) => [order.key, order]));
    const steps = payload.steps;
    const statusTone = Object.fromEntries(steps.map((step) => [step.label, step.tone]));
    const statusIcon = Object.fromEntries(steps.map((step) => [step.label, step.icon]));
    let activeKey = payload.orders[2]?.key || payload.orders[0]?.key;

    const detailFields = {
        nota: document.querySelector('[data-detail-nota]'),
        customer: document.querySelector('[data-detail-customer]'),
        service: document.querySelector('[data-detail-service]'),
        in: document.querySelector('[data-detail-in]'),
        eta: document.querySelector('[data-detail-eta]'),
    };
    const progressSteps = document.querySelector('[data-progress-steps]');
    const historyList = document.querySelector('[data-history-list]');

    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const getStatusIndex = (status) => Math.max(0, steps.findIndex((step) => step.label === status));

    const renderDetail = (key) => {
        const order = orders.get(key);

        if (!order) {
            return;
        }

        activeKey = key;
        detailFields.nota.textContent = order.nota;
        detailFields.customer.textContent = order.customer;
        detailFields.service.textContent = order.service;
        detailFields.in.textContent = order.in;
        detailFields.eta.textContent = order.eta;

        const currentIndex = getStatusIndex(order.currentStatus);
        const progress = steps.length > 1 ? currentIndex / (steps.length - 1) : 0;
        progressSteps.style.setProperty('--progress', progress);
        progressSteps.innerHTML = steps.map((step) => {
            const stepTime = order.steps[step.label];
            const isDone = Boolean(stepTime);
            const isCurrent = step.label === order.currentStatus;
            const dateText = stepTime ? `${stepTime.date} ${stepTime.time}` : '-';

            return `
                <article class="status-progress-step ${isDone ? 'is-done' : ''} ${isCurrent ? 'is-current' : ''}">
                    <span class="${escapeHtml(step.tone)}" aria-hidden="true">${statusIcon[step.label]}</span>
                    <strong>${escapeHtml(step.label)}</strong>
                    <small>${escapeHtml(dateText)}</small>
                </article>
            `;
        }).join('');

        historyList.innerHTML = order.history.map((history) => `
            <article class="status-history-item">
                <span class="${escapeHtml(history.tone)}" aria-hidden="true"></span>
                <p>
                    <strong>${escapeHtml(history.status)}</strong>
                    <small>${escapeHtml(history.detail)}</small>
                </p>
                <time><span>${escapeHtml(history.staff)}</span>${escapeHtml(history.time)}</time>
            </article>
        `).join('');

        document.querySelectorAll('[data-status-row]').forEach((row) => {
            row.classList.toggle('is-active', row.dataset.statusRow === key);
        });

        document.querySelectorAll('[data-order-key]').forEach((button) => {
            button.setAttribute('aria-pressed', button.dataset.orderKey === key ? 'true' : 'false');
        });
    };

    document.querySelectorAll('[data-order-key]').forEach((button) => {
        button.addEventListener('click', () => renderDetail(button.dataset.orderKey));
    });

    document.querySelectorAll('[data-status-row]').forEach((row) => {
        row.addEventListener('click', (event) => {
            if (event.target.closest('select, input, button')) {
                return;
            }

            renderDetail(row.dataset.statusRow);
        });
    });

    document.querySelectorAll('[data-save-status]').forEach((button) => {
        button.addEventListener('click', () => {
            const key = button.dataset.saveStatus;
            const order = orders.get(key);
            const select = document.querySelector(`[data-status-select="${key}"]`);
            const noteInput = document.querySelector(`[data-status-note="${key}"]`);
            const statusBadge = document.querySelector(`[data-current-status="${key}"]`);
            const newStatus = select?.value;

            if (!order || !newStatus) {
                return;
            }

            const tone = statusTone[newStatus] || 'blue';
            const now = 'Baru saja';
            const note = noteInput?.value.trim();
            const newStatusIndex = getStatusIndex(newStatus);
            order.currentStatus = newStatus;
            order.tone = tone;
            steps.slice(0, newStatusIndex + 1).forEach((step) => {
                order.steps[step.label] = order.steps[step.label] || { date: 'Hari ini', time: 'Baru saja' };
            });
            order.history.unshift({
                status: newStatus,
                tone,
                detail: note || `Status cucian diperbarui menjadi ${newStatus}.`,
                staff: 'Admin Laundry',
                time: now,
            });

            if (statusBadge) {
                statusBadge.className = `status-pill ${tone}`;
                statusBadge.textContent = newStatus;
            }

            if (noteInput) {
                noteInput.value = '';
            }

            renderDetail(key);
        });
    });

    renderDetail(activeKey);
}

const settingsForm = document.querySelector('[data-settings-form]');

if (settingsForm) {
    const settingsFields = settingsForm.querySelectorAll('[data-settings-field]');
    const settingsFeedback = settingsForm.querySelector('[data-settings-feedback]');
    const settingsToast = document.querySelector('[data-settings-toast]');
    const messageSource = settingsForm.querySelector('[data-message-counter-source]');
    const messageCounter = settingsForm.querySelector('[data-message-counter]');
    const logoutConfirmToggle = settingsForm.querySelector('[data-logout-confirm-toggle]');
    let toastTimer;

    const showSettingsToast = (message) => {
        if (!settingsToast) {
            return;
        }

        window.clearTimeout(toastTimer);
        settingsToast.textContent = message;
        settingsToast.classList.add('show');
        toastTimer = window.setTimeout(() => {
            settingsToast.classList.remove('show');
        }, 2600);
    };

    const setFeedback = (message) => {
        if (!settingsFeedback) {
            return;
        }

        settingsFeedback.textContent = message;
        settingsFeedback.classList.add('is-dirty');
    };

    const activateField = (field) => {
        settingsFields.forEach((item) => item.classList.toggle('is-active', item === field));
        const label = field.dataset.settingLabel || 'pengaturan';
        setFeedback(`${label} sedang diedit.`);
    };

    const updateCounter = () => {
        if (!messageSource || !messageCounter) {
            return;
        }

        const maxLength = messageSource.getAttribute('maxlength') || '300';
        messageCounter.textContent = `${messageSource.value.length} / ${maxLength}`;
    };

    settingsFields.forEach((field) => {
        field.addEventListener('click', () => activateField(field));
        field.addEventListener('focusin', () => activateField(field));
    });

    settingsForm.querySelectorAll('input, textarea, select').forEach((control) => {
        control.addEventListener('input', () => {
            const field = control.closest('[data-settings-field]');

            if (field) {
                activateField(field);
            }

            if (control === messageSource) {
                updateCounter();
            }
        });

        control.addEventListener('change', () => {
            const field = control.closest('[data-settings-field]');

            if (field) {
                const label = field.dataset.settingLabel || 'pengaturan';
                setFeedback(`${label} diperbarui.`);
            }
        });
    });

    settingsForm.querySelectorAll('[data-settings-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.querySelector(`#${button.dataset.settingsPasswordToggle}`);

            if (!input) {
                return;
            }

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });

    if (logoutConfirmToggle) {
        logoutConfirmToggle.addEventListener('change', () => {
            showSettingsToast(logoutConfirmToggle.checked ? 'Konfirmasi logout akan diaktifkan setelah disimpan.' : 'Konfirmasi logout akan dinonaktifkan setelah disimpan.');
            setFeedback('Preferensi logout diperbarui. Simpan perubahan untuk menerapkan.');
        });
    }

    settingsForm.addEventListener('submit', (event) => {
        const newPassword = settingsForm.querySelector('#settingNewPassword');
        const confirmPassword = settingsForm.querySelector('#settingConfirmPassword');

        if (newPassword?.value && confirmPassword?.value && newPassword.value !== confirmPassword.value) {
            event.preventDefault();
            confirmPassword.setAttribute('aria-invalid', 'true');
            confirmPassword.focus();
            showSettingsToast('Konfirmasi password belum sama.');
            setFeedback('Periksa kembali konfirmasi password.');
            return;
        }

        confirmPassword?.removeAttribute('aria-invalid');
        setFeedback('Menyimpan perubahan ke database...');
    });

    updateCounter();
}

const dashboardLogoutLinks = document.querySelectorAll('.dashboard-logout');

if (dashboardLogoutLinks.length) {
    const readLogoutPreference = () => {
        const source = document.querySelector('[data-logout-confirm-default]');
        const preference = source?.dataset.logoutConfirmDefault;

        return preference === 'off' ? 'off' : 'on';
    };

    const modal = document.createElement('div');
    const logoSource = document.querySelector('.dashboard-logo img, .brand-logo img')?.getAttribute('src') || '';
    let activeLogoutHref = dashboardLogoutLinks[0].href;
    let previousFocus = null;

    modal.className = 'logout-modal-backdrop';
    modal.hidden = true;
    modal.innerHTML = `
        <section class="logout-dialog" role="dialog" aria-modal="true" aria-labelledby="logoutDialogTitle">
            <span class="logout-modal-logo"><img data-logout-logo alt="Ghava Laundry"></span>
            <h2 id="logoutDialogTitle">Keluar dari Sistem?</h2>
            <p>Apakah Anda yakin ingin logout dari akun admin Ghava Laundry?</p>
            <div class="logout-actions">
                <button class="logout-cancel" type="button" data-logout-cancel>Batal</button>
                <button class="logout-confirm" type="button" data-logout-confirm>Ya, Logout</button>
            </div>
            <div class="logout-note">
                <span aria-hidden="true">&#128737;</span>
                Perubahan data yang belum disimpan akan tetap berada di halaman sampai Anda menyimpannya.
            </div>
        </section>
    `;

    const logoImage = modal.querySelector('[data-logout-logo]');

    if (logoSource && logoImage) {
        logoImage.src = logoSource;
    } else {
        modal.querySelector('.logout-modal-logo')?.remove();
    }

    document.body.appendChild(modal);

    const cancelButton = modal.querySelector('[data-logout-cancel]');
    const confirmButton = modal.querySelector('[data-logout-confirm]');

    const closeLogoutModal = () => {
        modal.hidden = true;
        document.body.classList.remove('logout-modal-open');

        if (previousFocus) {
            previousFocus.focus();
        }
    };

    const openLogoutModal = (href) => {
        activeLogoutHref = href;
        previousFocus = document.activeElement;
        modal.hidden = false;
        document.body.classList.add('logout-modal-open');
        cancelButton?.focus();
    };

    dashboardLogoutLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            if (readLogoutPreference() === 'off') {
                return;
            }

            event.preventDefault();
            openLogoutModal(link.href);
        });
    });

    cancelButton?.addEventListener('click', closeLogoutModal);

    confirmButton?.addEventListener('click', () => {
        confirmButton.textContent = 'Keluar...';
        window.location.href = activeLogoutHref;
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeLogoutModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) {
            closeLogoutModal();
        }
    });
}
