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

    document.querySelector('[data-dashboard-mark-read]')?.addEventListener('click', () => {
        document.querySelectorAll('.dashboard-notification-popover .is-unread').forEach((item) => {
            item.classList.remove('is-unread');
        });

        document.querySelectorAll('.dashboard-notification-popover .dashboard-popover-dot').forEach((dot) => {
            dot.hidden = true;
        });

        const notificationTrigger = dashboardPopoverTriggers.find((trigger) => trigger.dataset.dashboardPopoverTrigger === 'notifications');
        const notificationBadge = notificationTrigger?.querySelector('i');

        if (notificationBadge) {
            notificationBadge.hidden = true;
        }
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

    const writeStorage = (key, value) => {
        try {
            window.localStorage.setItem(key, value);
        } catch (error) {
            return false;
        }

        return true;
    };

    const readStorage = (key) => {
        try {
            return window.localStorage.getItem(key);
        } catch (error) {
            return null;
        }
    };

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
        setFeedback(`Mengedit ${label}. Klik Simpan Perubahan setelah selesai.`);
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
                setFeedback(`${label} diperbarui. Jangan lupa simpan perubahan.`);
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
        const storedLogoutPreference = readStorage('ghavaLogoutConfirm');
        if (storedLogoutPreference !== null) {
            logoutConfirmToggle.checked = storedLogoutPreference !== 'off';
        }
        logoutConfirmToggle.addEventListener('change', () => {
            writeStorage('ghavaLogoutConfirm', logoutConfirmToggle.checked ? 'on' : 'off');
            showSettingsToast(logoutConfirmToggle.checked ? 'Konfirmasi logout diaktifkan.' : 'Konfirmasi logout dinonaktifkan.');
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
        try {
            return window.localStorage.getItem('ghavaLogoutConfirm');
        } catch (error) {
            return null;
        }
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
