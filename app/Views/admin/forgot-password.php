<?php
$baseUrlSafe = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$recoveryEmailValue = trim((string) ($recoveryEmail ?? ''));
$recoveryPhoneValue = trim((string) ($recoveryPhone ?? ''));
$whatsappUrlValue = trim((string) ($recoveryWhatsappUrl ?? ''));
$emailHref = filter_var($recoveryEmailValue, FILTER_VALIDATE_EMAIL)
    ? 'mailto:' . $recoveryEmailValue . '?subject=' . rawurlencode('Reset password admin Ghava Laundry')
    : '';
$emailSafe = htmlspecialchars($recoveryEmailValue, ENT_QUOTES, 'UTF-8');
$phoneSafe = htmlspecialchars($recoveryPhoneValue, ENT_QUOTES, 'UTF-8');
$whatsappUrlSafe = htmlspecialchars($whatsappUrlValue, ENT_QUOTES, 'UTF-8');
$emailHrefSafe = htmlspecialchars($emailHref, ENT_QUOTES, 'UTF-8');
ob_start();
?>
<div class="admin-login-page admin-login-redesign admin-forgot-page">
    <main class="admin-login-main" aria-labelledby="adminForgotTitle">
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

        <section class="admin-login-card admin-forgot-card" aria-label="Bantuan reset password admin">
            <div class="admin-login-card-brand brand-logo brand-logo-large">
                <img src="<?= $baseUrlSafe ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
            </div>

            <h1 id="adminForgotTitle">Bantuan Reset Password Admin</h1>
            <p>Hubungi kontak Ghava Laundry untuk meminta reset akses admin. Sertakan username admin agar proses verifikasi lebih cepat.</p>

            <div class="admin-recovery-actions">
                <?php if ($whatsappUrlSafe !== ''): ?>
                    <a class="admin-submit-button admin-recovery-button" href="<?= $whatsappUrlSafe ?>" target="_blank" rel="noopener">
                        <span aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M21 11.5a8.4 8.4 0 0 1-12.2 7.5L3 21l2-5.6A8.4 8.4 0 1 1 21 11.5Z"></path>
                                <path d="M9.1 8.8c.2 3 2 4.8 5 5.4l1.4-1.2"></path>
                            </svg>
                        </span>
                        Kirim WhatsApp
                    </a>
                <?php endif; ?>

                <?php if ($emailHrefSafe !== ''): ?>
                    <a class="admin-back-button admin-recovery-button" href="<?= $emailHrefSafe ?>">
                        <span aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="m3 7 9 6 9-6"></path>
                            </svg>
                        </span>
                        Kirim Email
                    </a>
                <?php endif; ?>
            </div>

            <dl class="admin-recovery-contact">
                <?php if ($phoneSafe !== ''): ?>
                    <div>
                        <dt>WhatsApp</dt>
                        <dd><?= $phoneSafe ?></dd>
                    </div>
                <?php endif; ?>

                <?php if ($emailSafe !== ''): ?>
                    <div>
                        <dt>Email</dt>
                        <dd><?= $emailSafe ?></dd>
                    </div>
                <?php endif; ?>
            </dl>

            <a class="admin-back-button" href="<?= $baseUrlSafe ?>/admin/login">
                <span aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path d="M19 12H5"></path>
                        <path d="m12 19-7-7 7-7"></path>
                    </svg>
                </span>
                Kembali ke Login
            </a>

            <div class="admin-access-note">
                <span aria-hidden="true">i</span>
                <p>Password tidak ditampilkan ulang. Reset akses hanya diproses setelah pemilik/admin memverifikasi permintaan.</p>
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
