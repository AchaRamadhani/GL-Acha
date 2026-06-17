<?php
$baseUrlSafe = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
ob_start();
?>
<div class="admin-login-page admin-login-redesign">
    <main class="admin-login-main" aria-labelledby="adminLoginTitle">
        <section class="admin-login-visual" aria-label="Ghava Laundry">
            <a class="brand-logo admin-login-visual-logo" href="<?= $baseUrlSafe ?>/" aria-label="Ghava Laundry">
                <img src="<?= $baseUrlSafe ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
            </a>

            <span class="soap-bubble bubble-a" aria-hidden="true"></span>
            <span class="soap-bubble bubble-b" aria-hidden="true"></span>
            <span class="soap-bubble bubble-c" aria-hidden="true"></span>
            <span class="soap-bubble bubble-d" aria-hidden="true"></span>
            <span class="soap-bubble bubble-e" aria-hidden="true"></span>

            <div class="admin-login-photo-wrap" aria-hidden="true">
                <img src="<?= $baseUrlSafe ?>/assets/img/admin-login-visual.png" alt="">
            </div>
        </section>

        <section class="admin-login-card" aria-label="Form login admin">
            <div class="admin-login-card-brand brand-logo brand-logo-large">
                <img src="<?= $baseUrlSafe ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
            </div>

            <h1 id="adminLoginTitle">Login Admin Ghava Laundry</h1>
            <p>Silakan login untuk mengakses dashboard admin, mengelola data cucian, dan memantau transaksi.</p>

            <form class="admin-login-form" action="<?= $baseUrlSafe ?>/admin" method="get">
                <label class="admin-field" for="adminUsername">
                    <span class="admin-field-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <input id="adminUsername" name="username" type="text" placeholder="Username" autocomplete="username" required>
                </label>

                <label class="admin-field" for="adminPassword">
                    <span class="admin-field-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <rect x="4" y="10" width="16" height="10" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                            <path d="M12 14v2"></path>
                        </svg>
                    </span>
                    <input id="adminPassword" name="password" type="password" placeholder="Password" autocomplete="current-password" required>
                    <button class="admin-password-toggle" type="button" aria-label="Tampilkan password" data-password-toggle>
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </label>

                <div class="admin-form-options">
                    <label>
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#">Lupa password?</a>
                </div>

                <button class="admin-submit-button" type="submit">
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <rect x="4" y="10" width="16" height="10" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                            <path d="M12 14v2"></path>
                        </svg>
                    </span>
                    Login Admin
                </button>
            </form>

            <a class="admin-back-button" href="<?= $baseUrlSafe ?>/">
                <span aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path d="M19 12H5"></path>
                        <path d="m12 19-7-7 7-7"></path>
                    </svg>
                </span>
                Kembali ke Beranda
            </a>

            <div class="admin-access-note">
                <span aria-hidden="true">i</span>
                <p>Halaman ini hanya dapat diakses oleh admin/pemilik laundry.</p>
            </div>
        </section>
    </main>

    <footer class="admin-login-footer">
        <small>&copy; 2024 Ghava Laundry. Semua hak dilindungi.</small>
    </footer>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
