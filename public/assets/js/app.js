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
