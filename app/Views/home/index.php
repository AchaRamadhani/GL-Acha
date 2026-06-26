<?php ob_start(); ?>
<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$whatsappLogo = '<img src="' . $safeBaseUrl . '/assets/img/whatsapp-logo.svg?v=6" alt="">';

if (!function_exists('homeIcon')) {
    function homeIcon(string $name): string
    {
        $icons = [
            'basket' => '<svg viewBox="0 0 24 24"><path d="M5 10h14l-1.5 9h-11L5 10Z"/><path d="M8 10 12 4l4 6"/><path d="M9 14v2"/><path d="M12 14v2"/><path d="M15 14v2"/></svg>',
            'check' => '<svg viewBox="0 0 24 24"><path d="m20 6-11 11-5-5"/></svg>',
            'clock' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
            'dryer' => '<svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="M7 9h6"/><path d="M7 13c2-2 4 2 6 0s4-2 6 0"/></svg>',
            'hanger' => '<svg viewBox="0 0 24 24"><path d="M12 10V9a2.5 2.5 0 1 0-2.5-2.5"/><path d="m4 19 8-6 8 6Z"/></svg>',
            'info' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>',
            'iron' => '<svg viewBox="0 0 24 24"><path d="M6 15h11c2 0 3 1 3 3H4c0-1.7.8-3 2-3Z"/><path d="M7 15V9h6c2.8 0 5.2 2 5.8 4.7"/><path d="M9 12h4"/></svg>',
            'lock' => '<svg viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v2"/></svg>',
            'map' => '<svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-11a7 7 0 0 0-14 0c0 5.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>',
            'receipt' => '<svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7Z"/><path d="M15 3v5h4"/><path d="M9 13h6"/><path d="M9 17h4"/></svg>',
            'search' => '<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4.5-4.5"/></svg>',
            'shield' => '<svg viewBox="0 0 24 24"><path d="M12 3 20 6v6c0 5-3.4 8.2-8 9-4.6-.8-8-4-8-9V6Z"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>',
            'shirt' => '<svg viewBox="0 0 24 24"><path d="M9 4h6l2 2 4 2-2 5-3-1v8H8v-8l-3 1-2-5 4-2Z"/><path d="M9 4c.7 1.4 1.7 2 3 2s2.3-.6 3-2"/></svg>',
            'star' => '<svg class="fill-icon" viewBox="0 0 24 24"><path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9Z"/></svg>',
            'stopwatch' => '<svg viewBox="0 0 24 24"><path d="M10 2h4"/><path d="M12 8v5l-3 3"/><circle cx="12" cy="13" r="8"/><path d="m17 5 2 2"/></svg>',
            'towels' => '<svg viewBox="0 0 24 24"><path d="M6 7h10a3 3 0 0 1 0 6H6a3 3 0 0 0 0 6h12"/><path d="M6 7a3 3 0 0 0 0 6h10"/><path d="M6 13a3 3 0 0 0 0 6"/></svg>',
            'washer' => '<svg viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 7h.01"/><path d="M11 7h5"/><circle cx="12" cy="14" r="4"/><path d="M9.5 14.5c1.3-1.1 3.7 1.1 5 0"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
}

$trackingSteps = [
    ['icon' => homeIcon('check'), 'label' => 'Antrean', 'state' => 'done'],
    ['icon' => homeIcon('check'), 'label' => 'Diproses', 'state' => 'done'],
    ['icon' => homeIcon('washer'), 'label' => 'Dicuci', 'state' => 'current'],
    ['icon' => homeIcon('dryer'), 'label' => 'Dikeringkan', 'state' => ''],
    ['icon' => homeIcon('iron'), 'label' => 'Disetrika', 'state' => ''],
    ['icon' => homeIcon('receipt'), 'label' => 'Selesai', 'state' => ''],
    ['icon' => homeIcon('hanger'), 'label' => 'Diambil', 'state' => ''],
];

$kiloanServices = [
    ['icon' => homeIcon('washer'), 'title' => 'Cuci Kering'],
    ['icon' => homeIcon('towels'), 'title' => 'Cuci Lipat'],
    ['icon' => homeIcon('shirt'), 'title' => 'Cuci Setrika Lipat'],
    ['icon' => homeIcon('iron'), 'title' => 'Setrika Saja'],
    ['icon' => homeIcon('dryer'), 'title' => 'Pengering & Lipat'],
    ['icon' => homeIcon('shirt'), 'title' => 'Baju Bayi'],
];

$specialServices = [
    ['icon' => homeIcon('hanger'), 'title' => 'Satuan'],
    ['icon' => homeIcon('stopwatch'), 'title' => 'Express'],
    ['icon' => homeIcon('shield'), 'title' => 'Treatment'],
];

$contactItems = [
    ['icon' => $whatsappLogo, 'iconClass' => 'whatsapp-logo-icon', 'label' => 'WhatsApp', 'title' => '081242910340', 'text' => 'Pesan akan dikirim secara umum'],
    ['icon' => homeIcon('clock'), 'label' => 'Jam Operasional', 'title' => '07.00 - 21.00 WITA', 'text' => 'Setiap Hari (Termasuk Hari Libur)'],
    ['icon' => homeIcon('check'), 'label' => 'Status Operasional Hari Ini', 'title' => 'Buka', 'text' => 'Status dapat Ishoma, Tutup, atau Libur'],
    ['icon' => homeIcon('map'), 'label' => 'Alamat', 'title' => 'Jl. Poros Hartaco Indah, Kelurahan Sudiang Raya,', 'text' => 'Kecamatan Biringkanaya, Kota Makassar, Sulawesi Selatan 90242'],
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
            <span aria-hidden="true"><?= homeIcon('lock') ?></span>
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
                        <span aria-hidden="true"><?= homeIcon('receipt') ?></span>
                        <input id="nomorNota" name="nota" type="text" inputmode="text" placeholder="Masukkan No. Nota" aria-label="Nomor nota">
                    </label>
                    <button class="primary-button" type="submit">
                        <span aria-hidden="true"><?= homeIcon('search') ?></span>
                        Cek Status
                    </button>
                    <a class="whatsapp-button" href="https://wa.me/6281242910340">
                        <span class="whatsapp-button-icon" aria-hidden="true"><?= $whatsappLogo ?></span>
                        Hubungi Admin WhatsApp
                    </a>
                    <small class="tracking-help-text">Pesan umum, belum terkait nomor nota</small>
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
                                <span aria-hidden="true"><?= homeIcon('info') ?></span>
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
                        <span aria-hidden="true"><?= homeIcon('basket') ?></span>
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
                    <span aria-hidden="true"><?= homeIcon('star') ?></span>
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
                    <span class="home-info-icon <?= htmlspecialchars($item['iconClass'] ?? '', ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $item['icon'] ?></span>
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
