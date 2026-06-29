<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$csrfTokenSafe = htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8');
$settings = $settings ?? [];
$setting = static fn (string $key, string $fallback = ''): string => (string) ($settings[$key] ?? $fallback);
$admin = $admin ?? [];
$adminName = (string) ($admin['name'] ?? 'Admin Laundry');
$adminRole = (string) ($admin['role'] ?? 'Administrator');
$whatsappLogo = '<img src="' . $safeBaseUrl . '/assets/img/logo-wa.jpg?v=1" alt="">';
$whatsappMessageTemplate = $setting('message', 'Halo {nama_pelanggan}, cucian Anda dengan nomor pesanan {kode_pesanan} sudah selesai. Silakan datang kapan saja. Terima kasih telah mempercayakan cucian Anda kepada Ghava Laundry!');

if (!function_exists('laundryActionIcon')) {
    function laundryActionIcon(string $name): string
    {
        $attributes = 'class="laundry-action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"';

        return match ($name) {
            'view' => '<svg ' . $attributes . '><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.6"/></svg>',
            'edit' => '<svg ' . $attributes . '><path d="m14.4 5.4 4.2 4.2"/><path d="M4.6 19.4 6 14l9.8-9.8a2.1 2.1 0 0 1 3 3L9 17l-5.4 1.4Z"/><path d="M13.6 6.4 17.6 10.4"/></svg>',
            'delete' => '<svg ' . $attributes . '><path d="M4.5 7h15"/><path d="M9.5 7V5.4c0-.8.6-1.4 1.4-1.4h2.2c.8 0 1.4.6 1.4 1.4V7"/><path d="M17.5 7 16.7 19a1.6 1.6 0 0 1-1.6 1.5H8.9A1.6 1.6 0 0 1 7.3 19L6.5 7"/><path d="M10 11v5"/><path d="M14 11v5"/></svg>',
            default => '',
        };
    }
}

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian', 'active' => true],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$stats = $stats ?? [
    ['tone' => 'blue', 'icon' => '&#128722;', 'label' => 'Total Data Cucian', 'value' => '186', 'meta' => 'Cucian'],
    ['tone' => 'orange', 'icon' => '&#9719;', 'label' => 'Sedang Diproses', 'value' => '58', 'meta' => 'Cucian'],
    ['tone' => 'green', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => '32', 'meta' => 'Cucian'],
    ['tone' => 'purple', 'icon' => '&#128717;', 'label' => 'Diambil', 'value' => '24', 'meta' => 'Cucian'],
];

$laundryRows = $laundryRows ?? [
    ['no' => 1, 'nota' => 'INV-250521-001', 'name' => 'Budi Santoso', 'phone' => '0812-3456-7890', 'service' => 'Cuci Kering', 'weight' => '5,0 kg', 'in' => '21 Mei 2026', 'eta' => '23 Mei 2026', 'status' => 'Antrean', 'tone' => 'blue', 'total' => 'Rp 45.000'],
    ['no' => 2, 'nota' => 'INV-250521-002', 'name' => 'Siti Aisyah', 'phone' => '0813-2345-6789', 'service' => 'Cuci Lipat', 'weight' => '4,2 kg', 'in' => '21 Mei 2026', 'eta' => '22 Mei 2026', 'status' => 'Diproses', 'tone' => 'green', 'total' => 'Rp 35.000'],
    ['no' => 3, 'nota' => 'INV-250521-003', 'name' => 'Andi Wijaya', 'phone' => '0811-2233-4455', 'service' => 'Cuci Setrika Lipat', 'weight' => '6,0 kg', 'in' => '21 Mei 2026', 'eta' => '23 Mei 2026', 'status' => 'Dicuci', 'tone' => 'teal', 'total' => 'Rp 60.000'],
    ['no' => 4, 'nota' => 'INV-250520-015', 'name' => 'Dewi Lestari', 'phone' => '0812-1122-3344', 'service' => 'Setrika Saja', 'weight' => '3,0 kg', 'in' => '20 Mei 2026', 'eta' => '21 Mei 2026', 'status' => 'Disetrika', 'tone' => 'orange', 'total' => 'Rp 30.000'],
    ['no' => 5, 'nota' => 'INV-250520-014', 'name' => 'Rina Marlina', 'phone' => '0813-9988-7766', 'service' => 'Pengering & Lipat', 'weight' => '5,5 kg', 'in' => '20 Mei 2026', 'eta' => '21 Mei 2026', 'status' => 'Dikeringkan', 'tone' => 'cyan', 'total' => 'Rp 40.000'],
    ['no' => 6, 'nota' => 'INV-250520-013', 'name' => 'Ahmad Fauzi', 'phone' => '0811-5566-7788', 'service' => 'Baju Bayi', 'weight' => '3,0 kg', 'in' => '20 Mei 2026', 'eta' => '21 Mei 2026', 'status' => 'Dicuci', 'tone' => 'teal', 'total' => 'Rp 50.000'],
    ['no' => 7, 'nota' => 'INV-250519-012', 'name' => 'Maya Putri', 'phone' => '0812-6677-8899', 'service' => 'Satuan', 'weight' => '10 pcs', 'in' => '19 Mei 2026', 'eta' => '19 Mei 2026', 'status' => 'Selesai', 'tone' => 'purple', 'total' => 'Rp 25.000'],
    ['no' => 8, 'nota' => 'INV-250519-011', 'name' => 'Rizky Pratama', 'phone' => '0813-7788-9900', 'service' => 'Express', 'weight' => '7,0 kg', 'in' => '19 Mei 2026', 'eta' => '20 Mei 2026', 'status' => 'Selesai', 'tone' => 'purple', 'total' => 'Rp 85.000'],
    ['no' => 9, 'nota' => 'INV-250518-010', 'name' => 'Nurul Hidayah', 'phone' => '0811-8999-2211', 'service' => 'Treatment', 'weight' => '3,5 kg', 'in' => '18 Mei 2026', 'eta' => '20 Mei 2026', 'status' => 'Selesai', 'tone' => 'purple', 'total' => 'Rp 70.000'],
    ['no' => 10, 'nota' => 'INV-250518-009', 'name' => 'Fajar Nugroho', 'phone' => '0812-9900-1122', 'service' => 'Cuci Kering', 'weight' => '8,0 kg', 'in' => '18 Mei 2026', 'eta' => '20 Mei 2026', 'status' => 'Diambil', 'tone' => 'green', 'total' => 'Rp 75.000'],
];

$statusSummary = $statusSummary ?? [
    ['label' => 'Antrean', 'value' => 46, 'percent' => '24,7%', 'tone' => 'blue'],
    ['label' => 'Diproses', 'value' => 58, 'percent' => '31,2%', 'tone' => 'green'],
    ['label' => 'Dicuci', 'value' => 32, 'percent' => '17,2%', 'tone' => 'teal'],
    ['label' => 'Dikeringkan', 'value' => 20, 'percent' => '10,8%', 'tone' => 'cyan'],
    ['label' => 'Disetrika', 'value' => 12, 'percent' => '6,5%', 'tone' => 'orange'],
    ['label' => 'Selesai', 'value' => 18, 'percent' => '9,7%', 'tone' => 'purple'],
    ['label' => 'Diambil', 'value' => 0, 'percent' => '0%', 'tone' => 'green'],
];

$statusColors = [
    'Antrean' => '#2f80ed',
    'Diproses' => '#28a765',
    'Dicuci' => '#32b7c7',
    'Dikeringkan' => '#0ea5d8',
    'Disetrika' => '#f59e0b',
    'Selesai' => '#7047d9',
    'Diambil' => '#3dbb4f',
];

$statusTotal = array_sum(array_map(static fn (array $status): int => max(0, (int) ($status['value'] ?? 0)), $statusSummary));
$statusSegments = [];
$statusOffset = 0.0;

foreach ($statusSummary as $status) {
    $statusLabel = (string) ($status['label'] ?? '');
    $statusValue = max(0, (int) ($status['value'] ?? 0));

    if ($statusTotal <= 0 || $statusValue <= 0) {
        continue;
    }

    $statusPercent = ($statusValue / $statusTotal) * 100;
    $statusEnd = $statusOffset + $statusPercent;
    $statusSegments[] = ($statusColors[$statusLabel] ?? '#2f80ed') . ' '
        . number_format($statusOffset, 4, '.', '') . '% '
        . number_format($statusEnd, 4, '.', '') . '%';
    $statusOffset = $statusEnd;
}

$statusDonutGradient = $statusSegments === [] ? '#eef4fb' : 'conic-gradient(' . implode(', ', $statusSegments) . ')';

$activities = $activities ?? [
    ['icon' => '+', 'tone' => 'blue', 'title' => 'Data cucian baru ditambahkan', 'detail' => 'INV-250521-006 - Lina Wati', 'time' => '09:45'],
    ['icon' => '&#9998;', 'tone' => 'blue', 'title' => 'Data cucian diperbarui', 'detail' => 'INV-250521-002 - Siti Aisyah', 'time' => '09:20'],
    ['icon' => '&#10003;', 'tone' => 'green', 'title' => 'Status cucian selesai', 'detail' => 'INV-250519-012 - Maya Putri', 'time' => '08:55'],
    ['icon' => '&#128717;', 'tone' => 'purple', 'title' => 'Cucian diambil pelanggan', 'detail' => 'INV-250518-009 - Fajar Nugroho', 'time' => '08:30'],
    ['icon' => '&#128465;', 'tone' => 'red', 'title' => 'Data cucian dihapus', 'detail' => 'INV-250517-008 - Dedi Kurniawan', 'time' => '08:10'],
];
$packages = $packages ?? [];
$serviceOptions = $serviceOptions ?? [];

if ($serviceOptions === [] && $packages !== []) {
    $serviceOptions = array_map(static function (array $package): array {
        $name = (string) ($package['name'] ?? '');

        return [
            'id' => (string) ($package['id'] ?? $name),
            'name' => $name,
            'label' => $name,
        ];
    }, $packages);
}

if ($serviceOptions === []) {
    $serviceOptions = array_map(static fn (string $name): array => [
        'id' => $name,
        'name' => $name,
        'label' => $name,
    ], ['Cuci Kering', 'Cuci Lipat', 'Cuci Setrika Lipat', 'Setrika Saja', 'Pengering & Lipat', 'Baju Bayi', 'Satuan', 'Express', 'Treatment']);
}

$statusOptions = $statusOptions ?? ['Antrean', 'Diproses', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
$filters = array_merge([
    'search' => '',
    'status' => '',
    'service' => '',
    'date_from' => '',
    'date_to' => '',
], is_array($filters ?? null) ? $filters : []);
$filterSearchSafe = htmlspecialchars((string) $filters['search'], ENT_QUOTES, 'UTF-8');
$filterStatus = (string) $filters['status'];
$filterService = (string) $filters['service'];
$filterDateFromSafe = htmlspecialchars((string) $filters['date_from'], ENT_QUOTES, 'UTF-8');
$filterDateToSafe = htmlspecialchars((string) $filters['date_to'], ENT_QUOTES, 'UTF-8');
$totalRows = $totalRows ?? count($laundryRows);
$pagination = array_merge([
    'page' => 1,
    'perPage' => 10,
    'perPageOptions' => [10],
    'totalPages' => 1,
    'from' => count($laundryRows) > 0 ? 1 : 0,
    'to' => count($laundryRows),
], is_array($pagination ?? null) ? $pagination : []);
$currentPage = max(1, (int) $pagination['page']);
$currentPerPage = max(1, (int) $pagination['perPage']);
$totalPages = max(1, (int) $pagination['totalPages']);
$paginationFrom = max(0, (int) $pagination['from']);
$paginationTo = max(0, (int) $pagination['to']);
$perPageOptions = array_values(array_filter(array_map('intval', (array) $pagination['perPageOptions'])));
$perPageOptions = $perPageOptions === [] ? [$currentPerPage] : $perPageOptions;
$paginationTokens = [];
$lastPaginationPage = 0;

for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
    if ($pageNumber === 1 || $pageNumber === $totalPages || abs($pageNumber - $currentPage) <= 2) {
        if ($lastPaginationPage > 0 && $pageNumber - $lastPaginationPage > 1) {
            $paginationTokens[] = '...';
        }

        $paginationTokens[] = $pageNumber;
        $lastPaginationPage = $pageNumber;
    }
}

$paginationUrl = static function (int $page, ?int $perPage = null) use ($safeBaseUrl, $currentPerPage): string {
    $query = $_GET;
    $query['page'] = max(1, $page);
    $query['per_page'] = $perPage ?? $currentPerPage;
    $queryString = http_build_query($query);

    return $safeBaseUrl . '/admin/cucian' . ($queryString !== '' ? '?' . htmlspecialchars($queryString, ENT_QUOTES, 'UTF-8') : '');
};
$laundryCrudData = array_map(static fn (array $row): array => [
    'nota' => (string) ($row['nota'] ?? ''),
    'name' => (string) ($row['name'] ?? ''),
    'phone' => (string) ($row['phone'] ?? ''),
    'service' => (string) ($row['service'] ?? ''),
    'serviceId' => (string) ($row['service_id'] ?? ''),
    'weight' => (float) ($row['weight_value'] ?? 0),
    'unit' => (string) ($row['unit'] ?? 'kg'),
    'dateIn' => (string) ($row['date_in_value'] ?? ''),
    'eta' => (string) ($row['eta_value'] ?? ''),
    'dateInText' => (string) ($row['in'] ?? ''),
    'etaText' => (string) ($row['eta'] ?? ''),
    'status' => (string) ($row['status'] ?? ''),
    'total' => (float) ($row['total_value'] ?? 0),
    'totalText' => (string) ($row['total'] ?? ''),
    'notes' => (string) ($row['notes'] ?? ''),
], $laundryRows);
$laundryCrudJson = json_encode([
    'orders' => $laundryCrudData,
    'whatsappTemplate' => $whatsappMessageTemplate,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page laundry-cucian-page">
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

            <label class="dashboard-search" for="laundryTopSearch">
                <span aria-hidden="true">&#128269;</span>
                <input id="laundryTopSearch" type="search" placeholder="Cari pelanggan, nota, atau layanan..." autocomplete="off">
            </label>

            <?php require __DIR__ . '/partials/topbar-userbar.php'; ?>
        </header>

        <main class="dashboard-main laundry-main">
            <section class="laundry-heading">
                <div>
                    <h1>Data Cucian</h1>
                    <p>Kelola seluruh data cucian pelanggan: tambah, lihat, ubah, dan hapus data cucian.</p>
                </div>
                <button class="add-laundry-button" type="button" data-laundry-modal-open data-laundry-create>
                    <span aria-hidden="true">+</span>
                    Tambah Data Cucian
                </button>
            </section>

            <div class="laundry-note">
                <span aria-hidden="true">&#8505;</span>
                <p><strong>Catatan:</strong> Halaman ini untuk pengelolaan data cucian (CRUD). Update status cucian dilakukan melalui menu <a href="<?= $safeBaseUrl ?>/admin/update-status">Update Status</a>.</p>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="admin-flash success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="admin-flash error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <section class="laundry-stat-grid" aria-label="Ringkasan data cucian">
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

            <section class="laundry-workspace">
                <div class="laundry-data-panel">
                    <form class="laundry-filter-bar" action="<?= $safeBaseUrl ?>/admin/cucian" method="get">
                        <label class="laundry-search-field" for="laundrySearch">
                            <span aria-hidden="true">&#128269;</span>
                            <input id="laundrySearch" name="q" type="search" placeholder="Cari no nota atau nama pelanggan..." value="<?= $filterSearchSafe ?>" autocomplete="off">
                        </label>
                        <select name="status" aria-label="Filter status">
                            <option value="">Semua Status</option>
                            <?php foreach ($statusOptions as $statusOption): ?>
                                <option value="<?= htmlspecialchars($statusOption, ENT_QUOTES, 'UTF-8') ?>" <?= $filterStatus === $statusOption ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($statusOption, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label class="service-select-shell" aria-label="Filter layanan">
                            <span class="service-select-icon" aria-hidden="true">&#9672;</span>
                            <select class="service-type-select" name="service" aria-label="Filter layanan" data-service-type-select>
                                <option value="">Semua Layanan</option>
                                <?php foreach ($serviceOptions as $option): ?>
                                    <?php $serviceName = (string) ($option['name'] ?? ''); ?>
                                    <option value="<?= htmlspecialchars($serviceName, ENT_QUOTES, 'UTF-8') ?>" <?= $filterService === $serviceName ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($serviceName, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="service-select-chevron" aria-hidden="true">&#8964;</span>
                        </label>
                        <div class="date-filter date-filter-range" aria-label="Filter tanggal masuk">
                            <span aria-hidden="true">&#128197;</span>
                            <input id="laundryDateFrom" name="date_from" type="date" value="<?= $filterDateFromSafe ?>" aria-label="Tanggal mulai">
                            <span class="date-filter-separator" aria-hidden="true">-</span>
                            <input id="laundryDateTo" name="date_to" type="date" value="<?= $filterDateToSafe ?>" aria-label="Tanggal akhir">
                        </div>
                        <button class="filter-primary" type="submit">
                            <span aria-hidden="true">&#9661;</span>
                            Filter
                        </button>
                        <a class="filter-reset" href="<?= $safeBaseUrl ?>/admin/cucian">
                            <span aria-hidden="true">&#8635;</span>
                            Reset Filter
                        </a>
                    </form>

                    <div class="laundry-table-wrap">
                        <table class="laundry-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Nota</th>
                                    <th>Pelanggan</th>
                                    <th>No. Telp</th>
                                    <th>Layanan</th>
                                    <th>Berat/Qty</th>
                                    <th>Tgl Masuk</th>
                                    <th>Estimasi Selesai</th>
                                    <th>Status Cucian</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($laundryRows === []): ?>
                                    <tr>
                                        <td class="laundry-empty-row" colspan="11">Data cucian tidak ditemukan. Coba ubah atau reset filter.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($laundryRows as $row): ?>
                                    <tr>
                                        <td><?= $row['no'] ?></td>
                                        <td><?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['service'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['in'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['eta'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><span class="status-pill <?= htmlspecialchars($row['tone'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td><?= htmlspecialchars($row['total'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <div class="laundry-actions" aria-label="Aksi data cucian">
                                                <button class="view" type="button" aria-label="Lihat detail" data-laundry-action="view" data-laundry-nota="<?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>"><?= laundryActionIcon('view') ?></button>
                                                <button class="edit" type="button" aria-label="Ubah data" data-laundry-action="edit" data-laundry-nota="<?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>"><?= laundryActionIcon('edit') ?></button>
                                                <button class="delete" type="button" aria-label="Hapus data" data-laundry-action="delete" data-laundry-nota="<?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>"><?= laundryActionIcon('delete') ?></button>
                                                <button class="wa" type="button" aria-label="Hubungi WhatsApp" data-laundry-action="whatsapp" data-laundry-nota="<?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>"><?= $whatsappLogo ?></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="laundry-pagination">
                        <p>Menampilkan <?= (int) $paginationFrom ?> - <?= (int) $paginationTo ?> dari <?= (int) $totalRows ?> data</p>
                        <div class="page-buttons">
                            <?php if ($currentPage > 1): ?>
                                <a class="pagination-page" href="<?= $paginationUrl($currentPage - 1) ?>" aria-label="Sebelumnya">&#8249;</a>
                            <?php else: ?>
                                <span class="pagination-page disabled" aria-hidden="true">&#8249;</span>
                            <?php endif; ?>

                            <?php foreach ($paginationTokens as $paginationToken): ?>
                                <?php if ($paginationToken === '...'): ?>
                                    <span class="pagination-ellipsis" aria-hidden="true">...</span>
                                <?php elseif ((int) $paginationToken === $currentPage): ?>
                                    <span class="pagination-page active" aria-current="page"><?= (int) $paginationToken ?></span>
                                <?php else: ?>
                                    <a class="pagination-page" href="<?= $paginationUrl((int) $paginationToken) ?>"><?= (int) $paginationToken ?></a>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a class="pagination-page" href="<?= $paginationUrl($currentPage + 1) ?>" aria-label="Berikutnya">&#8250;</a>
                            <?php else: ?>
                                <span class="pagination-page disabled" aria-hidden="true">&#8250;</span>
                            <?php endif; ?>
                        </div>
                        <form class="pagination-size-form" action="<?= $safeBaseUrl ?>/admin/cucian" method="get">
                            <input type="hidden" name="q" value="<?= $filterSearchSafe ?>">
                            <input type="hidden" name="status" value="<?= htmlspecialchars($filterStatus, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="service" value="<?= htmlspecialchars($filterService, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="date_from" value="<?= $filterDateFromSafe ?>">
                            <input type="hidden" name="date_to" value="<?= $filterDateToSafe ?>">
                            <input type="hidden" name="page" value="1">
                            <select name="per_page" aria-label="Jumlah data per halaman" onchange="this.form.submit()">
                                <?php foreach ($perPageOptions as $perPageOption): ?>
                                    <option value="<?= (int) $perPageOption ?>" <?= (int) $perPageOption === $currentPerPage ? 'selected' : '' ?>>
                                        <?= (int) $perPageOption ?> / halaman
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>

                <aside class="laundry-side-panel">
                    <article class="laundry-widget">
                        <h2>Ringkasan Status</h2>
                        <div class="laundry-status-wrap">
                            <div class="laundry-status-donut" style="--laundry-status-donut: <?= htmlspecialchars($statusDonutGradient, ENT_QUOTES, 'UTF-8') ?>;" aria-label="Total <?= (int) $statusTotal ?> data cucian">
                                <div><strong><?= (int) $statusTotal ?></strong><span>Total</span></div>
                            </div>
                            <div class="laundry-status-list">
                                <?php foreach ($statusSummary as $status): ?>
                                    <?php
                                    $statusLabel = (string) ($status['label'] ?? '');
                                    $statusColor = $statusColors[$statusLabel] ?? '#2f80ed';
                                    ?>
                                    <p style="--status-color: <?= htmlspecialchars($statusColor, ENT_QUOTES, 'UTF-8') ?>;">
                                        <span class="laundry-status-dot <?= htmlspecialchars($status['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
                                        <span class="laundry-status-label"><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                        <strong><?= (int) ($status['value'] ?? 0) ?></strong>
                                        <small>(<?= htmlspecialchars($status['percent'], ENT_QUOTES, 'UTF-8') ?>)</small>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>

                    <article class="laundry-widget">
                        <h2>Aktivitas Terbaru</h2>
                        <div class="activity-list">
                            <?php foreach ($activities as $activity): ?>
                                <article class="activity-item">
                                    <span class="<?= htmlspecialchars($activity['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $activity['icon'] ?></span>
                                    <p><strong><?= htmlspecialchars($activity['title'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($activity['detail'], ENT_QUOTES, 'UTF-8') ?></small></p>
                                    <time><?= htmlspecialchars($activity['time'], ENT_QUOTES, 'UTF-8') ?></time>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </article>
                </aside>
            </section>
        </main>

        <script id="laundryCrudData" type="application/json"><?= $laundryCrudJson ?: '{"orders":[],"whatsappTemplate":""}' ?></script>

        <form action="<?= $safeBaseUrl ?>/admin/cucian/delete" method="post" data-laundry-delete-form hidden>
            <input type="hidden" name="_token" value="<?= $csrfTokenSafe ?>">
            <input type="hidden" name="nota" value="">
        </form>

        <div class="laundry-modal-backdrop" data-laundry-modal="cucian" hidden>
            <section class="laundry-dialog" role="dialog" aria-modal="true" aria-labelledby="laundryModalTitle">
                <button class="laundry-modal-close" type="button" aria-label="Tutup form tambah data cucian" data-laundry-modal-close>&times;</button>

                <header class="laundry-modal-header" data-laundry-modal-header>
                    <h2 id="laundryModalTitle">Tambah Data Cucian</h2>
                    <p>Masukkan data cucian pelanggan dengan lengkap.</p>
                </header>

                <form class="laundry-modal-form" action="<?= $safeBaseUrl ?>/admin/cucian" method="post" data-laundry-form data-laundry-crud-form data-create-action="<?= $safeBaseUrl ?>/admin/cucian" data-update-action="<?= $safeBaseUrl ?>/admin/cucian/update">
                    <input type="hidden" name="_token" value="<?= $csrfTokenSafe ?>">
                    <input type="hidden" name="nota" value="" data-laundry-nota-field>
                    <div class="laundry-modal-field">
                        <label for="laundryNota">No. Nota</label>
                        <input id="laundryNota" type="text" value="Otomatis" readonly data-laundry-nota-display>
                        <small>Nomor nota akan digenerate otomatis</small>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryCustomerName">Nama Pelanggan <span>*</span></label>
                        <input id="laundryCustomerName" type="text" name="customer_name" placeholder="Masukkan nama pelanggan" autocomplete="name" required>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryPhone">No. Telepon <span>*</span></label>
                        <input id="laundryPhone" type="tel" name="phone" placeholder="Contoh: 0812-3456-7890" autocomplete="tel" required>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryService">Jenis Layanan <span>*</span></label>
                        <span class="service-select-shell service-select-shell-large">
                            <span class="service-select-icon" aria-hidden="true">&#9672;</span>
                            <select id="laundryService" class="service-type-select" name="service" required data-service-type-select>
                                <option value="">Pilih jenis layanan</option>
                                <?php foreach ($serviceOptions as $option): ?>
                                    <option value="<?= htmlspecialchars((string) ($option['id'] ?? $option['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars((string) ($option['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="service-select-chevron" aria-hidden="true">&#8964;</span>
                        </span>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryWeight">Berat / Qty <span>*</span></label>
                        <div class="laundry-split-input">
                            <input id="laundryWeight" type="number" name="weight" min="0" step="0.1" placeholder="Contoh: 5" required>
                            <select name="unit" aria-label="Satuan berat atau jumlah">
                                <option>kg</option>
                                <option>pcs</option>
                            </select>
                        </div>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryDateIn">Tanggal Masuk <span>*</span></label>
                        <div class="laundry-date-input">
                            <input id="laundryDateIn" type="text" name="date_in" placeholder="Pilih tanggal masuk" data-laundry-date-input required>
                            <span aria-hidden="true">&#128197;</span>
                        </div>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryEta">Estimasi Selesai <span>*</span></label>
                        <div class="laundry-date-input">
                            <input id="laundryEta" type="text" name="eta" placeholder="Pilih tanggal estimasi selesai" data-laundry-date-input required>
                            <span aria-hidden="true">&#128197;</span>
                        </div>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryInitialStatus">Status Awal <span>*</span></label>
                        <select id="laundryInitialStatus" name="initial_status" required>
                            <?php foreach ($statusOptions as $statusOption): ?>
                                <option value="<?= htmlspecialchars($statusOption, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($statusOption, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>Status awal cucian saat pertama masuk</small>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryTotal">Total Harga <span>*</span></label>
                        <div class="laundry-price-input">
                            <span>Rp</span>
                            <input id="laundryTotal" type="number" name="total" min="0" step="500" placeholder="Contoh: 45000" required>
                        </div>
                        <small>Masukkan total harga cucian</small>
                    </div>

                    <div class="laundry-modal-field">
                        <label for="laundryNotes">Catatan</label>
                        <textarea id="laundryNotes" name="notes" rows="3" placeholder="Contoh: noda membandel di kerah, dll"></textarea>
                        <small>Catatan tambahan untuk cucian (opsional)</small>
                    </div>

                    <div class="laundry-modal-actions">
                        <button class="laundry-clear-button" type="button" data-laundry-form-reset>
                            <span aria-hidden="true">&#8635;</span>
                            Bersihkan
                        </button>
                        <div>
                            <button class="laundry-cancel-button" type="button" data-laundry-modal-close>Batal</button>
                            <button class="laundry-save-button" type="submit" data-laundry-save-button>
                                <span aria-hidden="true">&#128190;</span>
                                <span data-laundry-submit-label>Simpan Data</span>
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
