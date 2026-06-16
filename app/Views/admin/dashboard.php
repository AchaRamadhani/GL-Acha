<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin', 'active' => true],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin'],
];

$summaryCards = [
    ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => '87', 'meta' => 'Bulan Ini'],
    ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Pesanan', 'value' => '132', 'meta' => 'Bulan Ini'],
    ['tone' => 'purple', 'icon' => '&#128179;', 'label' => 'Total Pendapatan', 'value' => 'Rp 8.450.000', 'meta' => 'Bulan Ini'],
    ['tone' => 'orange', 'icon' => '&#128203;', 'label' => 'Pesanan Selesai', 'value' => '98', 'meta' => 'Bulan Ini'],
];

$orders = [
    ['nota' => 'INV-250521-001', 'customer' => 'Budi Santoso', 'date' => '21 Mei 2026', 'service' => 'Cuci Setrika', 'status' => 'Dicuci', 'tone' => 'blue', 'total' => 'Rp 45.000'],
    ['nota' => 'INV-250521-002', 'customer' => 'Siti Aisyah', 'date' => '21 Mei 2026', 'service' => 'Cuci Kering', 'status' => 'Antrean', 'tone' => 'cyan', 'total' => 'Rp 35.000'],
    ['nota' => 'INV-250520-015', 'customer' => 'Andi Wijaya', 'date' => '20 Mei 2026', 'service' => 'Cuci Setrika', 'status' => 'Disetrika', 'tone' => 'orange', 'total' => 'Rp 40.000'],
    ['nota' => 'INV-250520-014', 'customer' => 'Rina Marlina', 'date' => '20 Mei 2026', 'service' => 'Cuci Kering', 'status' => 'Selesai', 'tone' => 'purple', 'total' => 'Rp 30.000'],
    ['nota' => 'INV-250520-013', 'customer' => 'Dewi Lestari', 'date' => '20 Mei 2026', 'service' => 'Cuci Setrika', 'status' => 'Diambil', 'tone' => 'green', 'total' => 'Rp 50.000'],
];

$services = [
    ['name' => 'Cuci Kering', 'count' => 38, 'percent' => '18,1%', 'width' => '60%'],
    ['name' => 'Cuci Lipat', 'count' => 31, 'percent' => '14,8%', 'width' => '47%'],
    ['name' => 'Cuci Setrika Lipat', 'count' => 29, 'percent' => '13,8%', 'width' => '41%'],
    ['name' => 'Setrika Saja', 'count' => 24, 'percent' => '11,4%', 'width' => '31%'],
    ['name' => 'Pengering & Lipat', 'count' => 22, 'percent' => '10,5%', 'width' => '28%'],
    ['name' => 'Baju Bayi', 'count' => 18, 'percent' => '8,6%', 'width' => '20%'],
    ['name' => 'Satuan', 'count' => 15, 'percent' => '7,1%', 'width' => '15%'],
    ['name' => 'Express', 'count' => 10, 'percent' => '4,8%', 'width' => '10%'],
    ['name' => 'Treatment', 'count' => 5, 'percent' => '2,4%', 'width' => '5%'],
];

ob_start();
?>
<div class="admin-dashboard-page">
    <aside class="dashboard-sidebar" data-dashboard-sidebar>
        <a class="brand-logo dashboard-logo" href="<?= $safeBaseUrl ?>/admin" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <p class="dashboard-menu-label">MENU</p>
        <nav class="dashboard-menu" aria-label="Menu admin">
            <?php foreach ($sidebarItems as $item): ?>
                <a class="<?= !empty($item['active']) ? 'active' : '' ?>" href="<?= $safeBaseUrl . htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>">
                    <span aria-hidden="true"><?= $item['icon'] ?></span>
                    <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <a class="dashboard-logout" href="<?= $safeBaseUrl ?>/admin/login">
            <span aria-hidden="true">&#8617;</span>
            Logout
        </a>
    </aside>

    <div class="dashboard-content">
        <header class="dashboard-topbar">
            <button class="dashboard-icon-button" type="button" aria-label="Buka menu" data-dashboard-menu-toggle>
                <span aria-hidden="true">&#9776;</span>
            </button>

            <label class="dashboard-search" for="dashboardSearch">
                <span aria-hidden="true">&#128269;</span>
                <input id="dashboardSearch" type="search" placeholder="Cari pelanggan, nota, atau layanan..." autocomplete="off">
            </label>

            <div class="dashboard-userbar">
                <button class="dashboard-icon-button badge-button" type="button" aria-label="Notifikasi">
                    <span aria-hidden="true">&#128276;</span>
                    <i>3</i>
                </button>
                <button class="dashboard-icon-button badge-button" type="button" aria-label="Pesan">
                    <span aria-hidden="true">&#128172;</span>
                    <i>2</i>
                </button>
                <div class="dashboard-user">
                    <span class="dashboard-avatar" aria-hidden="true"></span>
                    <p><strong>Admin Laundry</strong><small>Administrator</small></p>
                    <span aria-hidden="true">&#8964;</span>
                </div>
            </div>
        </header>

        <main class="dashboard-main">
            <section class="dashboard-heading">
                <div>
                    <h1>Dashboard</h1>
                    <p><strong>Selamat datang kembali, Admin!</strong> Berikut adalah ringkasan data operasional Ghava Laundry.</p>
                </div>
                <button class="dashboard-date-button" type="button">
                    <span aria-hidden="true">&#128197;</span>
                    Bulan Ini (1 - 31 Mei 2026)
                    <span aria-hidden="true">&#8964;</span>
                </button>
            </section>

            <section class="summary-grid" aria-label="Ringkasan admin">
                <?php foreach ($summaryCards as $card): ?>
                    <article class="summary-card <?= htmlspecialchars($card['tone'], ENT_QUOTES, 'UTF-8') ?>">
                        <span class="summary-icon" aria-hidden="true"><?= $card['icon'] ?></span>
                        <div>
                            <p><?= htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') ?></p>
                            <strong><?= htmlspecialchars($card['value'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($card['meta'], ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                        <span class="sparkline" aria-hidden="true"></span>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="dashboard-grid">
                <article class="dashboard-panel revenue-panel">
                    <div class="panel-header">
                        <h2>Grafik Pendapatan</h2>
                        <button type="button">Bulan Ini <span aria-hidden="true">&#8964;</span></button>
                    </div>
                    <div class="revenue-chart" aria-label="Grafik pendapatan bulan Mei">
                        <span>4 jt</span><span>3 jt</span><span>2 jt</span><span>1 jt</span><span>0</span>
                        <i class="revenue-line"></i>
                        <b class="revenue-point"></b>
                        <div class="chart-tooltip"><small>15 Mei 2026</small><strong>Rp 2.850.000</strong></div>
                    </div>
                    <div class="chart-dates" aria-hidden="true">
                        <span>1 Mei</span><span>8 Mei</span><span>15 Mei</span><span>22 Mei</span><span>31 Mei</span>
                    </div>
                    <div class="revenue-footer">
                        <span aria-hidden="true">&#128181;</span>
                        <p>Total Pendapatan<strong>Rp 8.450.000</strong></p>
                        <p>Rata-rata Per Hari<strong>Rp 272.581</strong></p>
                    </div>
                </article>

                <article class="dashboard-panel status-panel-admin">
                    <div class="panel-header">
                        <h2>Status Cucian</h2>
                        <button type="button">Semua Status <span aria-hidden="true">&#8964;</span></button>
                    </div>
                    <div class="status-content">
                        <div class="status-donut" aria-label="Total 64 pesanan">
                            <div><span>Total</span><strong>64</strong><small>Pesanan</small></div>
                        </div>
                        <div class="status-legend">
                            <?php
                            $statuses = [
                                ['Antrean', '12', '(18,8%)', 'blue-light'],
                                ['Dicuci', '16', '(25,0%)', 'blue'],
                                ['Dikeringkan', '10', '(15,6%)', 'teal'],
                                ['Disetrika', '8', '(12,5%)', 'amber'],
                                ['Selesai', '14', '(21,9%)', 'purple'],
                                ['Diambil', '4', '(6,2%)', 'green'],
                            ];
                            foreach ($statuses as $status): ?>
                                <p><span class="<?= $status[3] ?>" aria-hidden="true"></span><?= $status[0] ?><strong><?= $status[1] ?></strong><small><?= $status[2] ?></small></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <p class="status-total">Total Pesanan<strong>64</strong></p>
                </article>
            </section>

            <section class="dashboard-grid lower-grid">
                <article class="dashboard-panel orders-panel">
                    <div class="panel-header">
                        <h2>Pesanan Terbaru</h2>
                        <button type="button">Lihat Semua</button>
                    </div>
                    <div class="table-wrap">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['nota'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($order['customer'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($order['date'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($order['service'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><span class="status-pill <?= htmlspecialchars($order['tone'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td><?= htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="dashboard-panel service-panel">
                    <div class="panel-header">
                        <h2>Transaksi Per Layanan</h2>
                        <button type="button">Bulan Ini <span aria-hidden="true">&#8964;</span></button>
                    </div>
                    <div class="service-table">
                        <div class="service-row header"><span>Layanan</span><span>Jumlah Transaksi</span><span>Persentase</span></div>
                        <?php foreach ($services as $service): ?>
                            <div class="service-row">
                                <span><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span><?= htmlspecialchars((string) $service['count'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span><i><b style="width: <?= htmlspecialchars($service['width'], ENT_QUOTES, 'UTF-8') ?>"></b></i><?= htmlspecialchars($service['percent'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            </section>
        </main>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
