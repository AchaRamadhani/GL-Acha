<?php ob_start(); ?>
<div class="admin-login-page">
    <header class="admin-login-header">
        <a class="brand-logo" href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/" aria-label="Ghava Laundry">
            <img src="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="admin-login-nav" aria-label="Navigasi utama">
            <a href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/">Beranda</a>
            <a href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/#tracking">Tracking Cucian</a>
            <a href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/#kontak">Kontak</a>
        </nav>

        <a class="admin-login-top-button" href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/admin/login" aria-current="page">
            <span aria-hidden="true">&#128274;</span>
            Login Admin
        </a>
    </header>

    <main class="admin-login-main">
        <section class="admin-login-hero" aria-labelledby="adminLoginTitle">
            <span class="soap-bubble bubble-a" aria-hidden="true"></span>
            <span class="soap-bubble bubble-b" aria-hidden="true"></span>
            <span class="soap-bubble bubble-c" aria-hidden="true"></span>
            <span class="soap-bubble bubble-d" aria-hidden="true"></span>
            <span class="soap-bubble bubble-e" aria-hidden="true"></span>

            <div class="admin-login-copy">
                <h1 id="adminLoginTitle">Login Admin Ghava Laundry</h1>
                <p>
                    Halaman ini khusus untuk pemilik/admin laundry. Silakan login untuk
                    mengelola data cucian, update status laundry, dan memantau transaksi
                    dengan mudah.
                </p>

                <div class="admin-feature-list">
                    <article>
                        <span aria-hidden="true">&#128203;</span>
                        <div>
                            <h2>Kelola Data Cucian</h2>
                            <p>Kelola data pelanggan, layanan, dan detail cucian dengan terorganisir.</p>
                        </div>
                    </article>
                    <article>
                        <span aria-hidden="true">&#8635;</span>
                        <div>
                            <h2>Update Status Laundry</h2>
                            <p>Perbarui status cucian secara real-time agar pelanggan selalu terinformasi.</p>
                        </div>
                    </article>
                    <article>
                        <span aria-hidden="true">&#128202;</span>
                        <div>
                            <h2>Monitoring Transaksi</h2>
                            <p>Pantau seluruh transaksi dan laporan keuangan dengan akurat.</p>
                        </div>
                    </article>
                </div>

                <div class="admin-login-scene" aria-hidden="true">
                    <div class="admin-scene-plant"><span></span><span></span><span></span></div>
                    <div class="admin-towel-stack"><span></span><span></span><span></span><span></span></div>
                    <div class="admin-basket"></div>
                    <div class="admin-monitor">
                        <div class="admin-monitor-sidebar"></div>
                        <div class="admin-monitor-content">
                            <strong>Dashboard</strong>
                            <div class="admin-stat-row"><span></span><span></span><span></span></div>
                            <div class="admin-chart-row">
                                <span class="admin-line-chart"></span>
                                <span class="admin-donut-chart"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="admin-login-card" aria-label="Form login admin">
                <div class="admin-login-card-brand brand-logo brand-logo-large">
                    <img src="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
                </div>

                <h2>Masuk ke Akun Admin</h2>
                <p>Silakan login untuk mengakses dashboard</p>

                <form class="admin-login-form" action="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/admin" method="get">
                    <label class="admin-field" for="adminUsername">
                        <span aria-hidden="true">&#128100;</span>
                        <input id="adminUsername" name="username" type="text" placeholder="Username" autocomplete="username" required>
                    </label>

                    <label class="admin-field" for="adminPassword">
                        <span aria-hidden="true">&#128274;</span>
                        <input id="adminPassword" name="password" type="password" placeholder="Password" autocomplete="current-password" required>
                        <button class="admin-password-toggle" type="button" aria-label="Tampilkan password" data-password-toggle>
                            &#128065;
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
                        <span aria-hidden="true">&#128274;</span>
                        Login Admin
                    </button>
                </form>

                <div class="admin-access-note">
                    <span aria-hidden="true">&#8505;</span>
                    <p>Halaman ini hanya dapat diakses oleh admin/pemilik laundry.</p>
                </div>

                <a class="admin-back-button" href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/">
                    <span aria-hidden="true">&#8592;</span>
                    Kembali ke Beranda
                </a>
            </aside>
        </section>
    </main>

    <footer class="admin-login-footer">
        <div>
            <span aria-hidden="true">WA</span>
            <p><strong>WhatsApp</strong>0812-3456-7890<br>Chat dengan kami</p>
        </div>
        <div>
            <span aria-hidden="true">&#9719;</span>
            <p><strong>Jam Operasional</strong>Senin - Minggu<br>07.00 - 21.00 WIB</p>
        </div>
        <div>
            <span aria-hidden="true">&#9906;</span>
            <p><strong>Alamat</strong>Jl. Kebersihan No. 88<br>Bandung, Jawa Barat</p>
        </div>
        <div>
            <span aria-hidden="true">&#9742;</span>
            <p><strong>Telepon</strong>(022) 1234-5678<br>Hubungi kami</p>
        </div>
        <small>&copy; 2024 Ghava Laundry. Semua hak dilindungi.</small>
    </footer>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
