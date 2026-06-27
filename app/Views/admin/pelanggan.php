<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$admin = $admin ?? [];
$adminName = (string) ($admin['name'] ?? 'Admin Laundry');
$adminRole = (string) ($admin['role'] ?? 'Administrator');

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan', 'active' => true],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$stats = $stats ?? [
    ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => '87', 'meta' => 'Orang'],
    ['tone' => 'green', 'icon' => '&#128100;', 'label' => 'Pelanggan Bulan Ini', 'value' => '28', 'meta' => 'Orang'],
    ['tone' => 'purple', 'icon' => '&#128100;&#43;', 'label' => 'Pelanggan Baru', 'value' => '12', 'meta' => 'Orang'],
    ['tone' => 'orange', 'icon' => '&#128706;', 'label' => 'Cucian Berjalan', 'value' => '34', 'meta' => 'Orang'],
];

$customers = $customers ?? [
    ['no' => 1, 'id' => 'PLG-250521-001', 'name' => 'Budi Santoso', 'phone' => '0812-3456-7890', 'address' => 'Jl. Melati No. 12, Jakarta', 'transactions' => 15, 'active' => 2, 'last' => '21 Mei 2026'],
    ['no' => 2, 'id' => 'PLG-250521-002', 'name' => 'Siti Aisyah', 'phone' => '0813-1122-3344', 'address' => 'Jl. Anggrek No. 5, Jakarta', 'transactions' => 11, 'active' => 1, 'last' => '21 Mei 2026'],
    ['no' => 3, 'id' => 'PLG-250520-003', 'name' => 'Andi Wijaya', 'phone' => '0812-8877-6655', 'address' => 'Jl. Kenanga No. 8, Jakarta', 'transactions' => 9, 'active' => 1, 'last' => '20 Mei 2026'],
    ['no' => 4, 'id' => 'PLG-250520-004', 'name' => 'Rina Marlina', 'phone' => '0813-5566-7788', 'address' => 'Jl. Mawar No. 23, Jakarta', 'transactions' => 8, 'active' => 3, 'last' => '20 Mei 2026'],
    ['no' => 5, 'id' => 'PLG-250520-005', 'name' => 'Dewi Lestari', 'phone' => '0812-9988-7766', 'address' => 'Jl. Dahlia No. 7, Jakarta', 'transactions' => 7, 'active' => 0, 'last' => '19 Mei 2026'],
    ['no' => 6, 'id' => 'PLG-250519-006', 'name' => 'Ahmad Fauzi', 'phone' => '0813-6677-8899', 'address' => 'Jl. Tulip No. 14, Jakarta', 'transactions' => 6, 'active' => 1, 'last' => '19 Mei 2026'],
    ['no' => 7, 'id' => 'PLG-250519-007', 'name' => 'Nurlaila', 'phone' => '0812-1234-5678', 'address' => 'Jl. Flamboyan No. 3, Jakarta', 'transactions' => 6, 'active' => 0, 'last' => '18 Mei 2026'],
    ['no' => 8, 'id' => 'PLG-250518-008', 'name' => 'Hendra Kurniawan', 'phone' => '0813-5468-1357', 'address' => 'Jl. Cempaka No. 10, Jakarta', 'transactions' => 5, 'active' => 1, 'last' => '18 Mei 2026'],
    ['no' => 9, 'id' => 'PLG-250518-009', 'name' => 'Putri Amanda', 'phone' => '0812-7654-3210', 'address' => 'Jl. Bougenville No. 9, Jakarta', 'transactions' => 5, 'active' => 0, 'last' => '17 Mei 2026'],
    ['no' => 10, 'id' => 'PLG-250517-010', 'name' => 'Yoga Pratama', 'phone' => '0813-9090-1122', 'address' => 'Jl. Kamboja No. 6, Jakarta', 'transactions' => 4, 'active' => 1, 'last' => '17 Mei 2026'],
];

$customerSummary = $customerSummary ?? [
    ['label' => 'Pelanggan Aktif', 'value' => 62, 'percent' => '71,3%', 'tone' => 'blue'],
    ['label' => 'Pelanggan Baru', 'value' => 12, 'percent' => '13,8%', 'tone' => 'green'],
    ['label' => 'Pelanggan Tidak Aktif', 'value' => 13, 'percent' => '14,9%', 'tone' => 'orange'],
];

$activities = $activities ?? [
    ['tone' => 'blue', 'name' => 'Budi Santoso', 'detail' => 'Membuat pesanan baru', 'time' => '2 jam lalu'],
    ['tone' => 'green', 'name' => 'Rina Marlina', 'detail' => 'Pesanan selesai diambil', 'time' => '4 jam lalu'],
    ['tone' => 'purple', 'name' => 'Siti Aisyah', 'detail' => 'Update status cucian', 'time' => '5 jam lalu'],
    ['tone' => 'blue', 'name' => 'Ahmad Fauzi', 'detail' => 'Membuat pesanan baru', 'time' => '1 hari lalu'],
    ['tone' => 'blue', 'name' => 'Dewi Lestari', 'detail' => 'Pesanan selesai diambil', 'time' => '1 hari lalu'],
];
$totalRows = $totalRows ?? count($customers);

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page customer-admin-page">
    <aside class="dashboard-sidebar" data-dashboard-sidebar>
        <a class="brand-logo dashboard-logo" href="<?= $safeBaseUrl ?>/admin" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="dashboard-menu" aria-label="Menu admin">
            <?php foreach ($sidebarItems as $item): ?>
                <a class="<?= !empty($item['active']) ? 'active' : '' ?>" href="<?= $safeBaseUrl . htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>">
                    <span aria-hidden="true"><?= $item['icon'] ?></span>
                    <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <a class="dashboard-logout" href="<?= $safeBaseUrl ?>/admin/logout">
            <span aria-hidden="true">&#8617;</span>
            Logout
        </a>
    </aside>

    <div class="dashboard-content">
        <header class="dashboard-topbar laundry-topbar">
            <button class="dashboard-icon-button" type="button" aria-label="Buka menu" data-dashboard-menu-toggle>
                <span aria-hidden="true">&#9776;</span>
            </button>

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
                    <p><strong><?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($adminRole, ENT_QUOTES, 'UTF-8') ?></small></p>
                    <span aria-hidden="true">&#8964;</span>
                </div>
            </div>
        </header>

        <main class="dashboard-main laundry-main customer-main">
            <section class="laundry-heading customer-heading">
                <div>
                    <h1>Pelanggan</h1>
                    <p>Kelola data pelanggan dan lihat riwayat aktivitas laundry mereka.</p>
                </div>
                <button class="add-laundry-button customer-add-button" type="button">
                    <span aria-hidden="true">+</span>
                    Tambah Pelanggan
                </button>
            </section>

            <section class="laundry-stat-grid customer-stat-grid" aria-label="Ringkasan pelanggan">
                <?php foreach ($stats as $stat): ?>
                    <article class="laundry-stat-card <?= htmlspecialchars($stat['tone'], ENT_QUOTES, 'UTF-8') ?>">
                        <span aria-hidden="true"><?= $stat['icon'] ?></span>
                        <div>
                            <p><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8') ?></p>
                            <strong><?= htmlspecialchars($stat['value'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($stat['meta'], ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="laundry-workspace customer-workspace">
                <div class="laundry-data-panel customer-data-panel">
                    <form class="laundry-filter-bar customer-filter-bar" action="#" method="get">
                        <label class="laundry-search-field" for="customerSearch">
                            <span aria-hidden="true">&#128269;</span>
                            <input id="customerSearch" type="search" placeholder="Cari nama pelanggan atau no. telepon..." autocomplete="off">
                        </label>
                        <select aria-label="Filter pelanggan">
                            <option>Semua Pelanggan</option>
                            <option>Pelanggan Aktif</option>
                            <option>Pelanggan Baru</option>
                            <option>Pelanggan Tidak Aktif</option>
                        </select>
                        <button class="date-filter" type="button">
                            <span aria-hidden="true">&#128197;</span>
                            Bulan Ini (1 - 31 Mei 2026)
                            <span aria-hidden="true">&#8964;</span>
                        </button>
                        <button class="filter-primary" type="button">
                            <span aria-hidden="true">&#9661;</span>
                            Filter
                        </button>
                        <button class="filter-reset" type="button">
                            <span aria-hidden="true">&#8635;</span>
                            Reset Filter
                        </button>
                    </form>

                    <div class="laundry-table-wrap">
                        <table class="laundry-table customer-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>No. Telp</th>
                                    <th>Alamat</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Cucian Berjalan</th>
                                    <th>Transaksi Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td><?= $customer['no'] ?></td>
                                        <td><?= htmlspecialchars($customer['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($customer['address'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= $customer['transactions'] ?></td>
                                        <td><?= $customer['active'] ?></td>
                                        <td><?= htmlspecialchars($customer['last'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <div class="customer-actions" aria-label="Aksi pelanggan">
                                                <button class="view" type="button" aria-label="Lihat pelanggan">&#128065;</button>
                                                <button class="edit" type="button" aria-label="Ubah pelanggan">&#9998;</button>
                                                <button class="delete" type="button" aria-label="Hapus pelanggan">&#128465;</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="laundry-pagination customer-pagination">
                        <p>Menampilkan <?= count($customers) > 0 ? '1' : '0' ?> - <?= count($customers) ?> dari <?= (int) $totalRows ?> pelanggan</p>
                        <select aria-label="Jumlah pelanggan per halaman">
                            <option>10 per halaman</option>
                        </select>
                        <div class="page-buttons">
                            <button type="button" aria-label="Sebelumnya">&#8249;</button>
                            <button class="active" type="button">1</button>
                            <button type="button">2</button>
                            <button type="button">3</button>
                            <span>...</span>
                            <button type="button">9</button>
                            <button type="button" aria-label="Berikutnya">&#8250;</button>
                        </div>
                    </div>
                </div>

                <aside class="laundry-side-panel customer-side-panel">
                    <article class="laundry-widget customer-summary-widget">
                        <h2>Ringkasan Pelanggan</h2>
                        <div class="customer-summary-wrap">
                            <div class="customer-donut" aria-label="Total <?= (int) $totalRows ?> pelanggan">
                                <div><strong><?= (int) $totalRows ?></strong><span>Total</span></div>
                            </div>
                            <div class="customer-summary-list">
                                <?php foreach ($customerSummary as $summary): ?>
                                    <p>
                                        <span class="<?= htmlspecialchars($summary['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
                                        <?= htmlspecialchars($summary['label'], ENT_QUOTES, 'UTF-8') ?>
                                        <strong><?= $summary['value'] ?></strong>
                                        <small>(<?= htmlspecialchars($summary['percent'], ENT_QUOTES, 'UTF-8') ?>)</small>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>

                    <article class="laundry-widget customer-activity-widget">
                        <h2>Aktivitas Pelanggan</h2>
                        <div class="customer-activity-list">
                            <?php foreach ($activities as $activity): ?>
                                <article class="customer-activity-item">
                                    <span class="<?= htmlspecialchars($activity['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
                                    <p>
                                        <strong><?= htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <small><?= htmlspecialchars($activity['detail'], ENT_QUOTES, 'UTF-8') ?></small>
                                    </p>
                                    <time><?= htmlspecialchars($activity['time'], ENT_QUOTES, 'UTF-8') ?></time>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <a class="customer-view-all" href="#">Lihat Semua Aktivitas <span aria-hidden="true">&#8594;</span></a>
                    </article>
                </aside>
            </section>
        </main>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
