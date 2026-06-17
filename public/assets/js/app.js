const trackingForm = document.querySelector('#trackingForm');
const nomorNotaInput = document.querySelector('#nomorNota');

if (trackingForm && nomorNotaInput) {
    trackingForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const nomorNota = nomorNotaInput.value.trim();

        if (!nomorNota) {
            nomorNotaInput.focus();
            nomorNotaInput.setAttribute('aria-invalid', 'true');
            return;
        }

        nomorNotaInput.removeAttribute('aria-invalid');
        document.querySelector('#tracking')?.scrollIntoView({ behavior: 'smooth' });
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
        logoutConfirmToggle.checked = readStorage('ghavaLogoutConfirm') !== 'off';
        logoutConfirmToggle.addEventListener('change', () => {
            writeStorage('ghavaLogoutConfirm', logoutConfirmToggle.checked ? 'on' : 'off');
            showSettingsToast(logoutConfirmToggle.checked ? 'Konfirmasi logout diaktifkan.' : 'Konfirmasi logout dinonaktifkan.');
        });
    }

    settingsForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const newPassword = settingsForm.querySelector('#settingNewPassword');
        const confirmPassword = settingsForm.querySelector('#settingConfirmPassword');

        if (newPassword?.value && confirmPassword?.value && newPassword.value !== confirmPassword.value) {
            confirmPassword.setAttribute('aria-invalid', 'true');
            confirmPassword.focus();
            showSettingsToast('Konfirmasi password belum sama.');
            setFeedback('Periksa kembali konfirmasi password.');
            return;
        }

        confirmPassword?.removeAttribute('aria-invalid');
        settingsFields.forEach((field) => field.classList.remove('is-active'));

        if (settingsFeedback) {
            settingsFeedback.textContent = 'Perubahan pengaturan berhasil disimpan.';
            settingsFeedback.classList.remove('is-dirty');
        }

        showSettingsToast('Pengaturan berhasil disimpan.');
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
