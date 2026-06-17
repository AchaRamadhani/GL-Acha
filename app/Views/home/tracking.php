<?php ob_start(); ?>
<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');

$trackingSteps = [
    ['icon' => '&#10003;', 'label' => 'Antrean', 'state' => 'done'],
    ['icon' => '&#10003;', 'label' => 'Diproses', 'state' => 'done'],
    ['icon' => '&#10003;', 'label' => 'Dicuci', 'state' => 'done'],
    ['icon' => '&#9832;', 'label' => 'Dikeringkan', 'state' => 'current'],
    ['icon' => '&#128451;', 'label' => 'Disetrika', 'state' => ''],
    ['icon' => '&#9745;', 'label' => 'Selesai', 'state' => ''],
    ['icon' => '&#128666;', 'label' => 'Diambil', 'state' => ''],
];

$trackingDetails = [
    'No. Nota' => 'GHV-240518-0012',
    'Nama Pelanggan' => 'Budi Santoso',
    'Layanan' => 'Cuci Setrika Lipat',
    'Tanggal Masuk' => '18 Mei 2024 09:30',
    'Estimasi Selesai' => '20 Mei 2024 17:00',
    'Status Saat Ini' => 'Dikeringkan',
];

$historyItems = [
    ['icon' => '&#10003;', 'label' => 'Antrean', 'date' => '18 Mei 2024 09:30', 'state' => 'done'],
    ['icon' => '&#10003;', 'label' => 'Diproses', 'date' => '18 Mei 2024 10:15', 'state' => 'done'],
    ['icon' => '&#10003;', 'label' => 'Dicuci', 'date' => '18 Mei 2024 14:20', 'state' => 'done'],
    ['icon' => '&#9832;', 'label' => 'Dikeringkan', 'date' => '19 Mei 2024 09:00', 'state' => 'current'],
    ['icon' => '&#128451;', 'label' => 'Disetrika', 'date' => '-', 'state' => ''],
    ['icon' => '&#9745;', 'label' => 'Selesai', 'date' => '-', 'state' => ''],
    ['icon' => '&#128666;', 'label' => 'Diambil', 'date' => '-', 'state' => ''],
];

$contactItems = [
    ['icon' => 'WA', 'label' => 'Hubungi Kami via WhatsApp', 'title' => '081242910340', 'text' => 'Fast response selama jam operasional', 'tone' => 'green'],
    ['icon' => '&#9719;', 'label' => 'Jam Operasional', 'title' => '07.00 - 21.00 WITA', 'text' => 'Setiap Hari (Termasuk Hari Libur)', 'tone' => 'blue'],
    ['icon' => '&#9872;', 'label' => 'Status Operasional Hari Ini', 'title' => 'Buka', 'text' => 'Kami siap melayani Anda hari ini', 'tone' => 'green'],
    ['icon' => '&#9906;', 'label' => 'Alamat Kami', 'title' => 'Jl. Poros Hartaco Indah, Kelurahan Sudiang Raya, Kecamatan Biringkanaya, Kota Makassar, Sulawesi Selatan 90242', 'text' => '', 'tone' => 'blue'],
];
?>
<div class="site-shell home-landing tracking-page">
    <header class="site-header">
        <a class="brand-logo site-logo" href="<?= $safeBaseUrl ?>/" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="main-nav" aria-label="Navigasi utama">
            <a href="<?= $safeBaseUrl ?>/">Beranda</a>
            <a class="active" href="<?= $safeBaseUrl ?>/tracking-cucian">Tracking Cucian</a>
            <a href="<?= $safeBaseUrl ?>/layanan">Layanan</a>
            <a href="<?= $safeBaseUrl ?>/kontak">Kontak</a>
        </nav>

        <a class="admin-button" href="<?= $safeBaseUrl ?>/admin/login">
            <span aria-hidden="true">&#128274;</span>
            Login Admin
        </a>
    </header>

    <main class="tracking-page-main">
        <section class="tracking-top-panel" aria-labelledby="trackingPageTitle">
            <div class="tracking-page-intro">
                <h1 id="trackingPageTitle">Tracking <span>Cucian</span></h1>
                <p>Lacak status cucian Anda secara real-time hanya dengan nomor nota.</p>
                <figure class="tracking-page-visual">
                    <img src="<?= $safeBaseUrl ?>/assets/img/ghava-home-hero.png" alt="Keranjang laundry, deterjen, dan tumpukan handuk biru putih">
                </figure>
            </div>

            <form class="tracking-search-panel" id="trackingForm" action="#tracking">
                <label for="nomorNota">Masukkan No. Nota</label>
                <div class="tracking-search-field">
                    <span aria-hidden="true">&#128196;</span>
                    <input id="nomorNota" name="nota" type="text" inputmode="text" value="GHV-240518-0012" aria-label="Nomor nota">
                </div>
                <button class="primary-button" type="submit">
                    <span aria-hidden="true">&#128269;</span>
                    Cek Status
                </button>
                <a class="whatsapp-button" href="https://wa.me/6281242910340">
                    <span aria-hidden="true">WA</span>
                    Hubungi Admin WhatsApp
                </a>
            </form>
        </section>

        <section class="tracking-layout" id="tracking" aria-label="Hasil tracking cucian">
            <article class="tracking-result-card">
                <h2>Hasil Tracking</h2>
                <div class="tracking-result-body">
                    <dl class="tracking-page-detail-list">
                        <?php foreach ($trackingDetails as $label => $value): ?>
                            <div>
                                <dt><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></dt>
                                <dd class="<?= $label === 'Status Saat Ini' ? 'current-status-text' : '' ?>">
                                    <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>

                    <div class="tracking-page-progress" aria-label="Progress cucian">
                        <?php foreach ($trackingSteps as $step): ?>
                            <article class="tracking-page-step <?= $step['state'] ? 'is-' . htmlspecialchars($step['state'], ENT_QUOTES, 'UTF-8') : '' ?>">
                                <span aria-hidden="true"><?= $step['icon'] ?></span>
                                <strong><?= htmlspecialchars($step['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </article>
                        <?php endforeach; ?>
                        <div class="tracking-page-note">
                            <span aria-hidden="true">&#9432;</span>
                            <p>
                                <strong>Cucian Anda sedang dikeringkan.</strong>
                                Kami sedang memastikan cucian Anda bersih, segar, dan wangi.
                            </p>
                        </div>
                    </div>
                </div>
            </article>

            <aside class="tracking-history-card" aria-labelledby="historyTitle">
                <h2 id="historyTitle">Riwayat Status</h2>
                <ol class="tracking-history-list">
                    <?php foreach ($historyItems as $item): ?>
                        <li class="<?= $item['state'] ? 'is-' . htmlspecialchars($item['state'], ENT_QUOTES, 'UTF-8') : '' ?>">
                            <span aria-hidden="true"><?= $item['icon'] ?></span>
                            <strong><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <time><?= htmlspecialchars($item['date'], ENT_QUOTES, 'UTF-8') ?></time>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </aside>
        </section>

        <section class="tracking-contact-strip" aria-label="Informasi kontak dan operasional">
            <?php foreach ($contactItems as $item): ?>
                <article class="tracking-contact-item">
                    <span class="tracking-contact-icon <?= htmlspecialchars($item['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $item['icon'] ?></span>
                    <div>
                        <small><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></small>
                        <strong class="<?= $item['title'] === 'Buka' ? 'open-status' : '' ?>"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php if ($item['text'] !== ''): ?>
                            <p><?= htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
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
