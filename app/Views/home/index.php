<?php ob_start(); ?>
<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');

$trackingSteps = [
    ['icon' => '&#10003;', 'label' => 'Antrean', 'state' => 'done'],
    ['icon' => '&#10003;', 'label' => 'Diproses', 'state' => 'done'],
    ['icon' => '&#128703;', 'label' => 'Dicuci', 'state' => 'current'],
    ['icon' => '&#9832;', 'label' => 'Dikeringkan', 'state' => ''],
    ['icon' => '&#128085;', 'label' => 'Disetrika', 'state' => ''],
    ['icon' => '&#9745;', 'label' => 'Selesai', 'state' => ''],
    ['icon' => '&#128722;', 'label' => 'Diambil', 'state' => ''],
];

$kiloanServices = [
    ['icon' => '&#128703;', 'title' => 'Cuci Kering'],
    ['icon' => '&#128230;', 'title' => 'Cuci Lipat'],
    ['icon' => '&#128085;', 'title' => 'Cuci Setrika Lipat'],
    ['icon' => '&#128451;', 'title' => 'Setrika Saja'],
    ['icon' => '&#9832;', 'title' => 'Pengering & Lipat'],
    ['icon' => '&#128118;', 'title' => 'Baju Bayi'],
];

$specialServices = [
    ['icon' => '&#128090;', 'title' => 'Satuan'],
    ['icon' => '&#9201;', 'title' => 'Express'],
    ['icon' => '&#128737;', 'title' => 'Treatment'],
];

$contactItems = [
    ['icon' => 'WA', 'label' => 'WhatsApp', 'title' => '081242910340', 'text' => 'Terhubung langsung ke admin.'],
    ['icon' => '&#9719;', 'label' => 'Jam Operasional', 'title' => '07.00 - 21.00 WITA', 'text' => 'Setiap hari termasuk hari libur.'],
    ['icon' => '&#9679;', 'label' => 'Status Operasional Hari Ini', 'title' => 'Buka', 'text' => 'Status dapat berubah menjadi istirahat, tutup, atau libur.'],
    ['icon' => '&#9906;', 'label' => 'Alamat', 'title' => 'Jl. Poros Hartaco Indah', 'text' => 'Kelurahan Sudiang Raya, Biringkanaya, Kota Makassar, Sulawesi Selatan 90242.'],
];
?>
<div class="site-shell home-landing">
    <header class="site-header">
        <a class="brand-logo site-logo" href="<?= $safeBaseUrl ?>/" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="main-nav" aria-label="Navigasi utama">
            <a class="active" href="#beranda">Beranda</a>
            <a href="<?= $safeBaseUrl ?>/tracking-cucian">Tracking Cucian</a>
            <a href="<?= $safeBaseUrl ?>/layanan">Layanan</a>
            <a href="<?= $safeBaseUrl ?>/kontak">Kontak</a>
        </nav>

        <a class="admin-button" href="<?= $safeBaseUrl ?>/admin/login">
            <span aria-hidden="true">&#128274;</span>
            Login Admin
        </a>
    </header>

    <main class="home-main">
        <section class="home-hero" id="beranda">
            <div class="home-hero-copy">
                <h1>Pantau Status Cucian Anda dengan <span>Mudah</span></h1>
                <p>
                    Pelanggan dapat mengecek progress cucian secara online menggunakan nomor nota
                    tanpa perlu datang ke outlet.
                </p>

                <figure class="home-hero-visual">
                    <img src="<?= $safeBaseUrl ?>/assets/img/ghava-home-hero.png" alt="Keranjang laundry, deterjen, dan tumpukan handuk biru putih">
                </figure>
            </div>

            <div class="home-side" id="tracking">
                <form class="home-tracking-card" id="trackingForm" action="#tracking">
                    <h2>Cek Status Cucian</h2>
                    <label class="home-input-wrap" for="nomorNota">
                        <span aria-hidden="true">&#128196;</span>
                        <input id="nomorNota" name="nota" type="text" inputmode="text" placeholder="Masukkan No. Nota" aria-label="Nomor nota">
                    </label>
                    <button class="primary-button" type="submit">
                        <span aria-hidden="true">&#128269;</span>
                        Cek Status
                    </button>
                    <a class="whatsapp-button" href="https://wa.me/6281242910340">
                        <span aria-hidden="true">WA</span>
                        Hubungi Admin WhatsApp
                    </a>
                </form>

                <section class="tracking-example" aria-labelledby="trackingExampleTitle">
                    <h2 id="trackingExampleTitle">Contoh Hasil Tracking</h2>
                    <div class="tracking-example-body">
                        <dl class="tracking-detail-list">
                            <div>
                                <dt>No. Nota</dt>
                                <dd>GHV-240518-0012</dd>
                            </div>
                            <div>
                                <dt>Nama Pelanggan</dt>
                                <dd>Budi Santoso</dd>
                            </div>
                            <div>
                                <dt>Layanan</dt>
                                <dd>Cuci Setrika Lipat</dd>
                            </div>
                            <div>
                                <dt>Tanggal Masuk</dt>
                                <dd>18 Mei 2024 09:30</dd>
                            </div>
                            <div>
                                <dt>Estimasi Selesai</dt>
                                <dd>20 Mei 2024 17:00</dd>
                            </div>
                            <div>
                                <dt>Status Saat Ini</dt>
                                <dd><strong>Dicuci</strong></dd>
                            </div>
                        </dl>

                        <div class="tracking-progress" aria-label="Progress cucian">
                            <?php foreach ($trackingSteps as $step): ?>
                                <article class="tracking-step <?= $step['state'] ? 'is-' . htmlspecialchars($step['state'], ENT_QUOTES, 'UTF-8') : '' ?>">
                                    <span aria-hidden="true"><?= $step['icon'] ?></span>
                                    <strong><?= htmlspecialchars($step['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                                </article>
                            <?php endforeach; ?>
                            <p class="tracking-status-note">
                                <span aria-hidden="true">&#9432;</span>
                                Cucian Anda sedang dicuci. Terima kasih telah menggunakan layanan Ghava Laundry.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <section class="home-services" id="layanan">
            <div class="services-panel kiloan-panel">
                <h2>Layanan Ghava Laundry</h2>
                <div class="service-panel-toolbar">
                    <span class="service-badge">
                        <span aria-hidden="true">&#128092;</span>
                        Kategori Kiloan (Minimal 3 kg)
                    </span>
                    <small>Perhitungan kiloan minimal 3 kg berat cucian.</small>
                </div>
                <div class="compact-service-grid">
                    <?php foreach ($kiloanServices as $service): ?>
                        <article class="compact-service-card">
                            <span aria-hidden="true"><?= $service['icon'] ?></span>
                            <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="services-panel special-panel">
                <h2>
                    <span aria-hidden="true">&#9733;</span>
                    Layanan Khusus
                </h2>
                <div class="compact-service-grid special-service-grid">
                    <?php foreach ($specialServices as $service): ?>
                        <article class="compact-service-card">
                            <span aria-hidden="true"><?= $service['icon'] ?></span>
                            <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="home-info-strip" id="kontak" aria-label="Informasi kontak dan operasional">
            <?php foreach ($contactItems as $item): ?>
                <article class="home-info-item">
                    <span class="home-info-icon" aria-hidden="true"><?= $item['icon'] ?></span>
                    <div>
                        <small><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></small>
                        <strong class="<?= $item['title'] === 'Buka' ? 'open-status' : '' ?>"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <p><?= htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <footer class="home-footer">
        <small>&copy; 2024 Ghava Laundry. All rights reserved.</small>
    </footer>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
