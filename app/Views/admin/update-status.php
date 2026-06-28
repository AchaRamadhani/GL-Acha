<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$csrfTokenSafe = htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8');
$admin = $admin ?? [];
$adminName = (string) ($admin['name'] ?? 'Admin Laundry');
$adminRole = (string) ($admin['role'] ?? 'Administrator');

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status', 'active' => true],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$statusSteps = [
    ['label' => 'Antrean', 'tone' => 'blue', 'icon' => '&#128101;'],
    ['label' => 'Diproses', 'tone' => 'green', 'icon' => '&#9881;'],
    ['label' => 'Dicuci', 'tone' => 'teal', 'icon' => '&#128705;'],
    ['label' => 'Dikeringkan', 'tone' => 'cyan', 'icon' => '&#10043;'],
    ['label' => 'Disetrika', 'tone' => 'orange', 'icon' => '&#9876;'],
    ['label' => 'Selesai', 'tone' => 'purple', 'icon' => '&#10003;'],
    ['label' => 'Diambil', 'tone' => 'green', 'icon' => '&#128717;'],
];

$orders = $orders ?? [
    [
        'key' => 'budi-santoso',
        'nota' => 'INV-250521-001',
        'customer' => 'Budi Santoso',
        'service' => 'Cuci Kering',
        'currentStatus' => 'Antrean',
        'tone' => 'blue',
        'in' => '21 Mei 2026, 08:30',
        'eta' => '23 Mei 2026, 16:00',
        'steps' => [
            'Antrean' => ['date' => '21 Mei 2026', 'time' => '08:30'],
        ],
        'history' => [
            ['status' => 'Antrean', 'tone' => 'blue', 'detail' => 'Pesanan masuk dan menunggu giliran proses.', 'staff' => 'Petugas Frontdesk', 'time' => '21 Mei 2026, 08:30'],
        ],
    ],
    [
        'key' => 'siti-aisyah',
        'nota' => 'INV-250521-002',
        'customer' => 'Siti Aisyah',
        'service' => 'Cuci Kering',
        'currentStatus' => 'Diproses',
        'tone' => 'green',
        'in' => '21 Mei 2026, 09:05',
        'eta' => '23 Mei 2026, 15:30',
        'steps' => [
            'Antrean' => ['date' => '21 Mei 2026', 'time' => '09:05'],
            'Diproses' => ['date' => '21 Mei 2026', 'time' => '09:45'],
        ],
        'history' => [
            ['status' => 'Diproses', 'tone' => 'green', 'detail' => 'Pesanan diterima dan mulai diproses.', 'staff' => 'Petugas Frontdesk', 'time' => '21 Mei 2026, 09:45'],
            ['status' => 'Antrean', 'tone' => 'blue', 'detail' => 'Pesanan masuk ke daftar antrean.', 'staff' => 'Petugas Frontdesk', 'time' => '21 Mei 2026, 09:05'],
        ],
    ],
    [
        'key' => 'andi-wijaya',
        'nota' => 'INV-250520-015',
        'customer' => 'Andi Wijaya',
        'service' => 'Cuci Setrika',
        'currentStatus' => 'Dicuci',
        'tone' => 'teal',
        'in' => '20 Mei 2026, 09:15',
        'eta' => '22 Mei 2026, 17:00',
        'steps' => [
            'Antrean' => ['date' => '20 Mei 2026', 'time' => '09:15'],
            'Diproses' => ['date' => '20 Mei 2026', 'time' => '10:20'],
            'Dicuci' => ['date' => '20 Mei 2026', 'time' => '11:05'],
        ],
        'history' => [
            ['status' => 'Dicuci', 'tone' => 'teal', 'detail' => 'Pakaian mulai dicuci menggunakan mesin.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 11:05'],
            ['status' => 'Diproses', 'tone' => 'green', 'detail' => 'Pesanan diterima dan mulai diproses.', 'staff' => 'Petugas Frontdesk', 'time' => '20 Mei 2026, 10:20'],
            ['status' => 'Antrean', 'tone' => 'blue', 'detail' => 'Pesanan masuk dan menunggu antrean.', 'staff' => 'Petugas Frontdesk', 'time' => '20 Mei 2026, 09:15'],
        ],
    ],
    [
        'key' => 'rina-marlina',
        'nota' => 'INV-250520-014',
        'customer' => 'Rina Marlina',
        'service' => 'Cuci Kering',
        'currentStatus' => 'Dikeringkan',
        'tone' => 'cyan',
        'in' => '20 Mei 2026, 10:00',
        'eta' => '22 Mei 2026, 13:00',
        'steps' => [
            'Antrean' => ['date' => '20 Mei 2026', 'time' => '10:00'],
            'Diproses' => ['date' => '20 Mei 2026', 'time' => '10:40'],
            'Dicuci' => ['date' => '20 Mei 2026', 'time' => '11:30'],
            'Dikeringkan' => ['date' => '20 Mei 2026', 'time' => '13:15'],
        ],
        'history' => [
            ['status' => 'Dikeringkan', 'tone' => 'cyan', 'detail' => 'Cucian masuk ke proses pengeringan.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 13:15'],
            ['status' => 'Dicuci', 'tone' => 'teal', 'detail' => 'Proses pencucian selesai.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 11:30'],
            ['status' => 'Diproses', 'tone' => 'green', 'detail' => 'Pesanan disiapkan untuk dicuci.', 'staff' => 'Petugas Frontdesk', 'time' => '20 Mei 2026, 10:40'],
        ],
    ],
    [
        'key' => 'dewi-lestari',
        'nota' => 'INV-250520-013',
        'customer' => 'Dewi Lestari',
        'service' => 'Cuci Setrika',
        'currentStatus' => 'Disetrika',
        'tone' => 'orange',
        'in' => '20 Mei 2026, 11:30',
        'eta' => '22 Mei 2026, 16:30',
        'steps' => [
            'Antrean' => ['date' => '20 Mei 2026', 'time' => '11:30'],
            'Diproses' => ['date' => '20 Mei 2026', 'time' => '12:05'],
            'Dicuci' => ['date' => '20 Mei 2026', 'time' => '13:10'],
            'Dikeringkan' => ['date' => '20 Mei 2026', 'time' => '15:20'],
            'Disetrika' => ['date' => '20 Mei 2026', 'time' => '16:40'],
        ],
        'history' => [
            ['status' => 'Disetrika', 'tone' => 'orange', 'detail' => 'Cucian sedang disetrika dan dirapikan.', 'staff' => 'Petugas Setrika', 'time' => '20 Mei 2026, 16:40'],
            ['status' => 'Dikeringkan', 'tone' => 'cyan', 'detail' => 'Cucian selesai dikeringkan.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 15:20'],
            ['status' => 'Dicuci', 'tone' => 'teal', 'detail' => 'Pakaian dicuci sesuai layanan.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 13:10'],
        ],
    ],
    [
        'key' => 'ahmad-fauzi',
        'nota' => 'INV-250519-011',
        'customer' => 'Ahmad Fauzi',
        'service' => 'Cuci Setrika',
        'currentStatus' => 'Selesai',
        'tone' => 'purple',
        'in' => '19 Mei 2026, 14:10',
        'eta' => '21 Mei 2026, 12:00',
        'steps' => [
            'Antrean' => ['date' => '19 Mei 2026', 'time' => '14:10'],
            'Diproses' => ['date' => '19 Mei 2026', 'time' => '14:40'],
            'Dicuci' => ['date' => '19 Mei 2026', 'time' => '15:25'],
            'Dikeringkan' => ['date' => '20 Mei 2026', 'time' => '09:20'],
            'Disetrika' => ['date' => '20 Mei 2026', 'time' => '11:15'],
            'Selesai' => ['date' => '20 Mei 2026', 'time' => '13:05'],
        ],
        'history' => [
            ['status' => 'Selesai', 'tone' => 'purple', 'detail' => 'Cucian selesai dan siap diambil pelanggan.', 'staff' => 'Petugas Frontdesk', 'time' => '20 Mei 2026, 13:05'],
            ['status' => 'Disetrika', 'tone' => 'orange', 'detail' => 'Cucian selesai disetrika.', 'staff' => 'Petugas Setrika', 'time' => '20 Mei 2026, 11:15'],
            ['status' => 'Dikeringkan', 'tone' => 'cyan', 'detail' => 'Cucian dipindahkan ke pengering.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 09:20'],
        ],
    ],
    [
        'key' => 'nadia-putri',
        'nota' => 'INV-250519-010',
        'customer' => 'Nadia Putri',
        'service' => 'Cuci Kering',
        'currentStatus' => 'Diambil',
        'tone' => 'green',
        'in' => '19 Mei 2026, 15:35',
        'eta' => '21 Mei 2026, 14:00',
        'steps' => [
            'Antrean' => ['date' => '19 Mei 2026', 'time' => '15:35'],
            'Diproses' => ['date' => '19 Mei 2026', 'time' => '16:00'],
            'Dicuci' => ['date' => '19 Mei 2026', 'time' => '16:35'],
            'Dikeringkan' => ['date' => '20 Mei 2026', 'time' => '09:45'],
            'Selesai' => ['date' => '20 Mei 2026', 'time' => '12:50'],
            'Diambil' => ['date' => '20 Mei 2026', 'time' => '15:15'],
        ],
        'history' => [
            ['status' => 'Diambil', 'tone' => 'green', 'detail' => 'Cucian sudah diambil pelanggan.', 'staff' => 'Admin Laundry', 'time' => '20 Mei 2026, 15:15'],
            ['status' => 'Selesai', 'tone' => 'purple', 'detail' => 'Cucian siap untuk diambil.', 'staff' => 'Petugas Frontdesk', 'time' => '20 Mei 2026, 12:50'],
            ['status' => 'Dikeringkan', 'tone' => 'cyan', 'detail' => 'Cucian selesai dikeringkan.', 'staff' => 'Petugas Cucian', 'time' => '20 Mei 2026, 09:45'],
        ],
    ],
    [
        'key' => 'yusuf-hidayat',
        'nota' => 'INV-250518-008',
        'customer' => 'Yusuf Hidayat',
        'service' => 'Cuci Kering',
        'currentStatus' => 'Dicuci',
        'tone' => 'teal',
        'in' => '18 Mei 2026, 09:10',
        'eta' => '20 Mei 2026, 17:00',
        'steps' => [
            'Antrean' => ['date' => '18 Mei 2026', 'time' => '09:10'],
            'Diproses' => ['date' => '18 Mei 2026', 'time' => '10:00'],
            'Dicuci' => ['date' => '18 Mei 2026', 'time' => '11:40'],
        ],
        'history' => [
            ['status' => 'Dicuci', 'tone' => 'teal', 'detail' => 'Cucian masuk proses pencucian.', 'staff' => 'Petugas Cucian', 'time' => '18 Mei 2026, 11:40'],
            ['status' => 'Diproses', 'tone' => 'green', 'detail' => 'Pesanan ditimbang dan dipilah.', 'staff' => 'Petugas Frontdesk', 'time' => '18 Mei 2026, 10:00'],
            ['status' => 'Antrean', 'tone' => 'blue', 'detail' => 'Pesanan masuk ke antrean.', 'staff' => 'Petugas Frontdesk', 'time' => '18 Mei 2026, 09:10'],
        ],
    ],
    [
        'key' => 'lina-wati',
        'nota' => 'INV-250518-007',
        'customer' => 'Lina Wati',
        'service' => 'Cuci Setrika',
        'currentStatus' => 'Diproses',
        'tone' => 'green',
        'in' => '18 Mei 2026, 10:20',
        'eta' => '20 Mei 2026, 18:00',
        'steps' => [
            'Antrean' => ['date' => '18 Mei 2026', 'time' => '10:20'],
            'Diproses' => ['date' => '18 Mei 2026', 'time' => '10:50'],
        ],
        'history' => [
            ['status' => 'Diproses', 'tone' => 'green', 'detail' => 'Pesanan sedang diproses oleh petugas.', 'staff' => 'Petugas Frontdesk', 'time' => '18 Mei 2026, 10:50'],
            ['status' => 'Antrean', 'tone' => 'blue', 'detail' => 'Pesanan berhasil dicatat.', 'staff' => 'Petugas Frontdesk', 'time' => '18 Mei 2026, 10:20'],
        ],
    ],
    [
        'key' => 'fajar-ramadhan',
        'nota' => 'INV-250518-006',
        'customer' => 'Fajar Ramadhan',
        'service' => 'Cuci Kering',
        'currentStatus' => 'Antrean',
        'tone' => 'blue',
        'in' => '18 Mei 2026, 11:00',
        'eta' => '20 Mei 2026, 18:30',
        'steps' => [
            'Antrean' => ['date' => '18 Mei 2026', 'time' => '11:00'],
        ],
        'history' => [
            ['status' => 'Antrean', 'tone' => 'blue', 'detail' => 'Pesanan baru masuk dan menunggu proses.', 'staff' => 'Petugas Frontdesk', 'time' => '18 Mei 2026, 11:00'],
        ],
    ],
];

$stats = $stats ?? [
    ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Antrean', 'value' => '18', 'meta' => 'Cucian'],
    ['tone' => 'green', 'icon' => '&#9881;', 'label' => 'Diproses', 'value' => '24', 'meta' => 'Cucian'],
    ['tone' => 'teal', 'icon' => '&#128705;', 'label' => 'Dicuci', 'value' => '31', 'meta' => 'Cucian'],
    ['tone' => 'purple', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => '22', 'meta' => 'Cucian'],
];
$serviceOptions = $serviceOptions ?? [];

if ($serviceOptions === []) {
    $serviceOptions = array_map(static fn (string $name): array => [
        'id' => $name,
        'name' => $name,
        'label' => $name,
    ], ['Cuci Kering', 'Cuci Lipat', 'Cuci Setrika Lipat', 'Setrika Saja', 'Pengering & Lipat', 'Baju Bayi', 'Satuan', 'Express', 'Treatment']);
}

$selectedOrder = $orders[2] ?? $orders[0] ?? [
    'key' => '',
    'nota' => '-',
    'customer' => '-',
    'service' => '-',
    'currentStatus' => 'Antrean',
    'tone' => 'blue',
    'in' => '-',
    'eta' => '-',
    'steps' => [],
    'history' => [],
];
$selectedStatusIndex = array_search($selectedOrder['currentStatus'], array_column($statusSteps, 'label'), true);
$selectedProgress = $selectedStatusIndex === false ? 0 : $selectedStatusIndex / max(count($statusSteps) - 1, 1);

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page status-update-page">
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

            <?php require __DIR__ . '/partials/topbar-userbar.php'; ?>
        </header>

        <main class="dashboard-main laundry-main">
            <section class="laundry-heading">
                <div>
                    <h1>Update Status</h1>
                    <p><strong>Perbarui tahapan proses cucian dan simpan riwayat perubahan status.</strong></p>
                    <p>Perubahan status dilakukan di halaman ini agar riwayat proses cucian tercatat dengan jelas.</p>
                </div>
            </section>

            <?php if (!empty($successMessage)): ?>
                <div class="admin-flash success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="admin-flash error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <section class="laundry-stat-grid status-stat-grid" aria-label="Ringkasan update status">
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

            <form class="status-filter-bar" action="#" method="get">
                <label class="laundry-search-field" for="statusSearch">
                    <span aria-hidden="true">&#128269;</span>
                    <input id="statusSearch" type="search" placeholder="Cari no nota atau nama pelanggan..." autocomplete="off">
                </label>
                <select aria-label="Filter status">
                    <option>Semua Status</option>
                    <?php foreach ($statusSteps as $status): ?>
                        <option><?= htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="service-select-shell" aria-label="Filter layanan">
                    <span class="service-select-icon" aria-hidden="true">&#9672;</span>
                    <select class="service-type-select" aria-label="Filter layanan" data-service-type-select>
                        <option value="">Semua Layanan</option>
                        <?php foreach ($serviceOptions as $option): ?>
                            <option value="<?= htmlspecialchars((string) ($option['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars((string) ($option['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="service-select-chevron" aria-hidden="true">&#8964;</span>
                </label>
                <button class="date-filter" type="button">
                    <span aria-hidden="true">&#128197;</span>
                    Bulan Ini (1 - 31 Mei 2026)
                    <span aria-hidden="true">&#8964;</span>
                </button>
            </form>

            <section class="status-workspace">
                <article class="laundry-data-panel status-list-panel">
                    <h2>Daftar Cucian Aktif</h2>
                    <div class="laundry-table-wrap">
                        <table class="laundry-table status-active-table">
                            <thead>
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Status Saat Ini</th>
                                    <th>Pilih Status Baru</th>
                                    <th>Catatan Singkat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <?php $statusFormId = 'statusForm' . preg_replace('/[^A-Za-z0-9_-]/', '', $order['key']); ?>
                                    <tr class="status-order-row <?= $order['key'] === $selectedOrder['key'] ? 'is-active' : '' ?>" data-status-row="<?= htmlspecialchars($order['key'], ENT_QUOTES, 'UTF-8') ?>">
                                        <td><?= htmlspecialchars($order['nota'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <button class="status-customer-button" type="button" data-order-key="<?= htmlspecialchars($order['key'], ENT_QUOTES, 'UTF-8') ?>" aria-pressed="<?= $order['key'] === $selectedOrder['key'] ? 'true' : 'false' ?>">
                                                <?= htmlspecialchars($order['customer'], ENT_QUOTES, 'UTF-8') ?>
                                            </button>
                                        </td>
                                        <td><?= htmlspecialchars($order['service'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><span class="status-pill <?= htmlspecialchars($order['tone'], ENT_QUOTES, 'UTF-8') ?>" data-current-status="<?= htmlspecialchars($order['key'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($order['currentStatus'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td>
                                            <select name="status" form="<?= htmlspecialchars($statusFormId, ENT_QUOTES, 'UTF-8') ?>" data-status-select="<?= htmlspecialchars($order['key'], ENT_QUOTES, 'UTF-8') ?>" aria-label="Pilih status baru untuk <?= htmlspecialchars($order['customer'], ENT_QUOTES, 'UTF-8') ?>">
                                                <?php foreach ($statusSteps as $status): ?>
                                                    <option value="<?= htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8') ?>" <?= $status['label'] === $order['currentStatus'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input class="status-note-input" type="text" name="note" form="<?= htmlspecialchars($statusFormId, ENT_QUOTES, 'UTF-8') ?>" data-status-note="<?= htmlspecialchars($order['key'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Masukkan catatan...">
                                        </td>
                                        <td>
                                            <form id="<?= htmlspecialchars($statusFormId, ENT_QUOTES, 'UTF-8') ?>" action="<?= $safeBaseUrl ?>/admin/update-status" method="post">
                                                <input type="hidden" name="_token" value="<?= $csrfTokenSafe ?>">
                                                <input type="hidden" name="no_nota" value="<?= htmlspecialchars($order['nota'], ENT_QUOTES, 'UTF-8') ?>">
                                                <button class="status-save-button" type="submit" data-save-status="<?= htmlspecialchars($order['key'], ENT_QUOTES, 'UTF-8') ?>">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <aside class="status-detail-panel" aria-live="polite">
                    <article class="status-detail-card">
                        <h2>Riwayat &amp; Progres Status</h2>
                        <div class="status-order-card">
                            <div class="status-detail-top">
                                <p><span>No. Nota</span><strong data-detail-nota><?= htmlspecialchars($selectedOrder['nota'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                                <p><span>Pelanggan</span><strong data-detail-customer><?= htmlspecialchars($selectedOrder['customer'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                            </div>
                            <div class="status-detail-meta">
                                <p><span>Layanan</span><strong data-detail-service><?= htmlspecialchars($selectedOrder['service'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                                <p><span>Tanggal Masuk</span><strong data-detail-in><?= htmlspecialchars($selectedOrder['in'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                                <p><span>Estimasi Selesai</span><strong data-detail-eta><?= htmlspecialchars($selectedOrder['eta'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                            </div>

                            <div class="status-progress-steps" data-progress-steps style="--progress: <?= htmlspecialchars((string) $selectedProgress, ENT_QUOTES, 'UTF-8') ?>">
                                <?php foreach ($statusSteps as $index => $step): ?>
                                    <?php
                                    $hasStep = isset($selectedOrder['steps'][$step['label']]);
                                    $isCurrent = $step['label'] === $selectedOrder['currentStatus'];
                                    ?>
                                    <article class="status-progress-step <?= $hasStep ? 'is-done' : '' ?> <?= $isCurrent ? 'is-current' : '' ?>">
                                        <span class="<?= htmlspecialchars($step['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $step['icon'] ?></span>
                                        <strong><?= htmlspecialchars($step['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <small><?= $hasStep ? htmlspecialchars($selectedOrder['steps'][$step['label']]['date'] . ' ' . $selectedOrder['steps'][$step['label']]['time'], ENT_QUOTES, 'UTF-8') : '-' ?></small>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="status-history-card">
                            <div class="status-history-heading">
                                <h3>Riwayat Terbaru</h3>
                                <button type="button">Lihat Semua</button>
                            </div>
                            <div class="status-history-list" data-history-list>
                                <?php foreach ($selectedOrder['history'] as $history): ?>
                                    <article class="status-history-item">
                                        <span class="<?= htmlspecialchars($history['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
                                        <p>
                                            <strong><?= htmlspecialchars($history['status'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <small><?= htmlspecialchars($history['detail'], ENT_QUOTES, 'UTF-8') ?></small>
                                        </p>
                                        <time><span><?= htmlspecialchars($history['staff'], ENT_QUOTES, 'UTF-8') ?></span><?= htmlspecialchars($history['time'], ENT_QUOTES, 'UTF-8') ?></time>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <p class="status-info-note">
                            <span aria-hidden="true">&#8505;</span>
                            Pastikan setiap perubahan status disimpan agar riwayat proses cucian tercatat dengan akurat.
                        </p>
                    </article>
                </aside>
            </section>
        </main>
    </div>
</div>

<script id="statusOrdersData" type="application/json"><?= json_encode(['orders' => $orders, 'steps' => $statusSteps], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
