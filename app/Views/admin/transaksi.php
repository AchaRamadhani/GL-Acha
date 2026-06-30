<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$settings = $settings ?? [];
$setting = static fn (string $key, string $fallback = ''): string => (string) ($settings[$key] ?? $fallback);
$admin = $admin ?? [];
$adminName = (string) ($admin['name'] ?? 'Admin Laundry');
$adminRole = (string) ($admin['role'] ?? 'Administrator');

if (!function_exists('transactionActionIcon')) {
    function transactionActionIcon(string $name): string
    {
        $attributes = 'class="transaction-action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"';

        return match ($name) {
            'view' => '<svg ' . $attributes . '><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.6"/></svg>',
            default => '',
        };
    }
}

if (!function_exists('transactionWhatsappLogo')) {
    function transactionWhatsappLogo(): string
    {
        return '<svg class="whatsapp-mark-icon" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><circle cx="16" cy="16" r="15" fill="#25d366"/><path fill="#fff" transform="translate(4 4)" d="M12.04 0C5.39 0 0 5.39 0 12.04c0 2.12.55 4.19 1.6 6.02L0 24l6.09-1.6a12 12 0 0 0 5.95 1.52h.01c6.65 0 12.04-5.39 12.04-12.04C24.09 5.39 18.69 0 12.04 0Zm0 21.9h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.63.95.97-3.53-.24-.36a9.86 9.86 0 0 1-1.51-5.26c0-5.44 4.43-9.87 9.87-9.87 2.64 0 5.11 1.03 6.98 2.89a9.82 9.82 0 0 1 2.89 6.98c-.01 5.44-4.43 9.79-9.93 9.79Zm5.42-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.16-.17.2-.35.22-.64.08-.3-.15-1.26-.46-2.39-1.48-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.06 2.88 1.21 3.07c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35Z"/></svg>';
    }
}

$whatsappLogo = transactionWhatsappLogo();

$normalizeWhatsappNumber = static function (string $value): string {
    $phone = preg_replace('/\D+/', '', $value) ?: '';

    if ($phone === '') {
        return '';
    }

    if (substr($phone, 0, 1) === '0') {
        return '62' . substr($phone, 1);
    }

    if (substr($phone, 0, 1) === '8') {
        return '62' . $phone;
    }

    return $phone;
};

$transactionMessage = static function (array $row) use ($settings): string {
    $laundryName = trim((string) ($settings['laundry_name'] ?? 'Ghava Laundry')) ?: 'Ghava Laundry';
    $notes = trim((string) ($row['notes'] ?? ''));
    $lines = [
        'Halo ' . ((string) ($row['name'] ?? '') ?: 'Pelanggan') . ', berikut nota ' . $laundryName . '.',
        'No. Nota: ' . ((string) ($row['nota'] ?? '') ?: '-'),
        'Layanan: ' . ((string) ($row['service'] ?? '') ?: '-'),
        'Berat/Qty: ' . ((string) ($row['weight'] ?? '') ?: '-'),
        'Tanggal Masuk: ' . ((string) ($row['in_long'] ?? $row['in'] ?? '') ?: '-'),
        'Estimasi Selesai: ' . ((string) ($row['eta_long'] ?? $row['eta'] ?? '') ?: '-'),
        'Status: ' . ((string) ($row['status'] ?? '') ?: '-'),
        'Total: ' . ((string) ($row['total'] ?? '') ?: '-'),
    ];

    if ($notes !== '') {
        $lines[] = 'Catatan: ' . $notes;
    }

    return implode("\n", $lines);
};

$transactionWhatsappUrl = static function (array $row) use ($normalizeWhatsappNumber, $transactionMessage): string {
    $phone = $normalizeWhatsappNumber((string) ($row['phone'] ?? ''));

    if ($phone === '') {
        return '';
    }

    return 'https://wa.me/' . $phone . '?text=' . rawurlencode($transactionMessage($row));
};

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi', 'active' => true],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$stats = $stats ?? [
    ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Transaksi', 'value' => '132', 'meta' => 'Transaksi'],
    ['tone' => 'blue', 'icon' => '&#128197;', 'label' => 'Transaksi Hari Ini', 'value' => '18', 'meta' => 'Transaksi'],
    ['tone' => 'orange', 'icon' => '&#9719;', 'label' => 'Belum Selesai', 'value' => '34', 'meta' => 'Transaksi'],
    ['tone' => 'green', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => '98', 'meta' => 'Transaksi'],
];

$transactions = $transactions ?? [
    ['no' => 1, 'nota' => 'INV-250521-001', 'name' => 'Budi Santoso', 'service' => 'Cuci Kering', 'weight' => '3,5 kg', 'in' => "21 Mei 2026\n10:15", 'eta' => "23 Mei 2026\n15:00", 'total' => 'Rp 45.000', 'status' => 'Selesai', 'tone' => 'purple'],
    ['no' => 2, 'nota' => 'INV-250521-002', 'name' => 'Siti Aisyah', 'service' => 'Cuci Lipat', 'weight' => '5,0 kg', 'in' => "21 Mei 2026\n11:20", 'eta' => "21 Mei 2026\n18:00", 'total' => 'Rp 35.000', 'status' => 'Diambil', 'tone' => 'green'],
    ['no' => 3, 'nota' => 'INV-250520-015', 'name' => 'Andi Wijaya', 'service' => 'Cuci Setrika Lipat', 'weight' => '4,0 kg', 'in' => "20 Mei 2026\n09:30", 'eta' => "21 Mei 2026\n15:00", 'total' => 'Rp 40.000', 'status' => 'Dicuci', 'tone' => 'teal'],
    ['no' => 4, 'nota' => 'INV-250520-014', 'name' => 'Rina Marlina', 'service' => 'Setrika Saja', 'weight' => '3,0 kg', 'in' => "20 Mei 2026\n10:45", 'eta' => "20 Mei 2026\n17:00", 'total' => 'Rp 30.000', 'status' => 'Disetrika', 'tone' => 'orange'],
    ['no' => 5, 'nota' => 'INV-250520-013', 'name' => 'Dewi Lestari', 'service' => 'Pengering & Lipat', 'weight' => '6,0 kg', 'in' => "20 Mei 2026\n14:00", 'eta' => "21 Mei 2026\n15:00", 'total' => 'Rp 50.000', 'status' => 'Dikeringkan', 'tone' => 'cyan'],
    ['no' => 6, 'nota' => 'INV-250519-012', 'name' => 'Hendra Pratama', 'service' => 'Baju Bayi', 'weight' => '3,0 kg', 'in' => "19 Mei 2026\n09:10", 'eta' => "20 Mei 2026\n12:00", 'total' => 'Rp 28.000', 'status' => 'Diproses', 'tone' => 'green'],
    ['no' => 7, 'nota' => 'INV-250519-011', 'name' => 'Maya Rahma', 'service' => 'Satuan', 'weight' => '1 pcs', 'in' => "19 Mei 2026\n13:25", 'eta' => "19 Mei 2026\n16:00", 'total' => 'Rp 8.000', 'status' => 'Diambil', 'tone' => 'green'],
    ['no' => 8, 'nota' => 'INV-250518-010', 'name' => 'Fajar Nugroho', 'service' => 'Express', 'weight' => '3,0 kg', 'in' => "18 Mei 2026\n08:50", 'eta' => "18 Mei 2026\n15:00", 'total' => 'Rp 60.000', 'status' => 'Selesai', 'tone' => 'purple'],
    ['no' => 9, 'nota' => 'INV-250518-009', 'name' => 'Nurul Hidayah', 'service' => 'Treatment', 'weight' => '2,0 kg', 'in' => "18 Mei 2026\n11:30", 'eta' => "20 Mei 2026\n15:00", 'total' => 'Rp 55.000', 'status' => 'Antrean', 'tone' => 'blue'],
    ['no' => 10, 'nota' => 'INV-250517-008', 'name' => 'Agus Setiawan', 'service' => 'Cuci Kering', 'weight' => '6,5 kg', 'in' => "17 Mei 2026\n16:40", 'eta' => "19 Mei 2026\n15:00", 'total' => 'Rp 75.000', 'status' => 'Antrean', 'tone' => 'blue'],
];

$statusSummary = $statusSummary ?? [
    ['label' => 'Antrean', 'value' => 18, 'percent' => '13,6%', 'tone' => 'blue'],
    ['label' => 'Diproses', 'value' => 12, 'percent' => '9,1%', 'tone' => 'green'],
    ['label' => 'Dicuci', 'value' => 20, 'percent' => '15,2%', 'tone' => 'teal'],
    ['label' => 'Dikeringkan', 'value' => 10, 'percent' => '7,6%', 'tone' => 'cyan'],
    ['label' => 'Disetrika', 'value' => 8, 'percent' => '6,1%', 'tone' => 'orange'],
    ['label' => 'Selesai', 'value' => 40, 'percent' => '30,3%', 'tone' => 'purple'],
    ['label' => 'Diambil', 'value' => 24, 'percent' => '18,2%', 'tone' => 'green'],
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

$noteActivities = $noteActivities ?? [
    ['icon' => '&#128065;', 'tone' => 'blue', 'title' => 'Nota dilihat', 'detail' => 'INV-250521-001 - Budi Santoso', 'time' => '5 mnt lalu'],
    ['icon' => $whatsappLogo, 'tone' => 'green whatsapp-activity-icon', 'title' => 'Nota dikirim ulang via WhatsApp', 'detail' => 'INV-250521-002 - Siti Aisyah', 'time' => '12 mnt lalu'],
    ['icon' => '&#128424;', 'tone' => 'blue', 'title' => 'Nota dicetak', 'detail' => 'INV-250520-015 - Andi Wijaya', 'time' => '25 mnt lalu'],
    ['icon' => '&#128065;', 'tone' => 'blue', 'title' => 'Nota dilihat', 'detail' => 'INV-250520-014 - Rina Marlina', 'time' => '32 mnt lalu'],
    ['icon' => $whatsappLogo, 'tone' => 'green whatsapp-activity-icon', 'title' => 'Nota dikirim ulang via WhatsApp', 'detail' => 'INV-250520-013 - Dewi Lestari', 'time' => '45 mnt lalu'],
];
$transactionSummary = $transactionSummary ?? [
    'total' => count($transactions),
    'revenue' => 0,
];
$serviceOptions = $serviceOptions ?? [];

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
$totalRows = $totalRows ?? count($transactions);
$averageTransaction = (int) ($transactionSummary['total'] ?? 0) > 0
    ? ((float) ($transactionSummary['revenue'] ?? 0) / (int) $transactionSummary['total'])
    : 0;
$pagination = array_merge([
    'page' => 1,
    'perPage' => 10,
    'perPageOptions' => [10],
    'totalPages' => 1,
    'from' => count($transactions) > 0 ? 1 : 0,
    'to' => count($transactions),
], is_array($pagination ?? null) ? $pagination : []);
$currentPage = max(1, (int) $pagination['page']);
$currentPerPage = max(1, (int) $pagination['perPage']);
$totalPages = max(1, (int) $pagination['totalPages']);
$paginationFrom = max(0, (int) $pagination['from']);
$paginationTo = max(0, (int) $pagination['to']);
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

$paginationUrl = static function (int $page) use ($safeBaseUrl, $currentPerPage): string {
    $query = $_GET;
    $query['page'] = max(1, $page);
    $query['per_page'] = $currentPerPage;
    $queryString = http_build_query($query);

    return $safeBaseUrl . '/admin/transaksi' . ($queryString !== '' ? '?' . htmlspecialchars($queryString, ENT_QUOTES, 'UTF-8') : '');
};
$transactionReceiptData = array_map(static function (array $row) use ($transactionWhatsappUrl): array {
    return [
        'nota' => (string) ($row['nota'] ?? ''),
        'name' => (string) ($row['name'] ?? ''),
        'phone' => (string) ($row['phone'] ?? ''),
        'service' => (string) ($row['service'] ?? ''),
        'weight' => (string) ($row['weight'] ?? ''),
        'in' => (string) ($row['in_long'] ?? $row['in'] ?? ''),
        'eta' => (string) ($row['eta_long'] ?? $row['eta'] ?? ''),
        'status' => (string) ($row['status'] ?? ''),
        'tone' => (string) ($row['tone'] ?? 'blue'),
        'total' => (string) ($row['total'] ?? ''),
        'notes' => (string) ($row['notes'] ?? ''),
        'whatsappUrl' => $transactionWhatsappUrl($row),
    ];
}, $transactions);
$transactionJson = json_encode([
    'transactions' => $transactionReceiptData,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page transaction-admin-page">
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
                    <h1>Transaksi</h1>
                    <p>Lihat riwayat transaksi dan nota pelanggan dengan rapi.</p>
                </div>
                <button class="add-laundry-button print-recap-button" type="button">
                    <span aria-hidden="true">&#128424;</span>
                    Cetak Rekap
                </button>
            </section>

            <div class="laundry-note">
                <span aria-hidden="true">&#8505;</span>
                <p>Nota digital dibuat otomatis saat data cucian disimpan. Kirim ulang nota akan membuka WhatsApp dengan pesan nota yang sudah terisi otomatis.</p>
            </div>

            <section class="laundry-stat-grid" aria-label="Ringkasan transaksi">
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

            <section class="laundry-workspace transaction-workspace">
                <div class="laundry-data-panel">
                    <form class="laundry-filter-bar transaction-filter-bar" action="<?= $safeBaseUrl ?>/admin/transaksi" method="get">
                        <label class="laundry-search-field" for="transactionSearch">
                            <span aria-hidden="true">&#128269;</span>
                            <input id="transactionSearch" name="q" type="search" placeholder="Cari no nota atau nama pelanggan..." value="<?= $filterSearchSafe ?>" autocomplete="off">
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
                        <div class="date-filter date-filter-range" aria-label="Filter tanggal transaksi">
                            <span aria-hidden="true">&#128197;</span>
                            <input id="transactionDateFrom" name="date_from" type="date" value="<?= $filterDateFromSafe ?>" aria-label="Tanggal mulai">
                            <span class="date-filter-separator" aria-hidden="true">-</span>
                            <input id="transactionDateTo" name="date_to" type="date" value="<?= $filterDateToSafe ?>" aria-label="Tanggal akhir">
                        </div>
                        <button class="filter-primary" type="submit">
                            <span aria-hidden="true">&#9661;</span>
                            Filter
                        </button>
                        <a class="filter-reset" href="<?= $safeBaseUrl ?>/admin/transaksi">
                            <span aria-hidden="true">&#8635;</span>
                            Reset Filter
                        </a>
                    </form>

                    <div class="laundry-table-wrap">
                        <table class="laundry-table transaction-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Nota</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Berat/Qty</th>
                                    <th>Tgl Masuk</th>
                                    <th>Estimasi Selesai</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($transactions === []): ?>
                                    <tr>
                                        <td class="laundry-empty-row" colspan="10">Transaksi tidak ditemukan. Coba ubah atau reset filter.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($transactions as $row): ?>
                                    <?php $whatsappUrl = $transactionWhatsappUrl($row); ?>
                                    <tr>
                                        <td><?= $row['no'] ?></td>
                                        <td><?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['service'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['in'], ENT_QUOTES, 'UTF-8')) ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['eta'], ENT_QUOTES, 'UTF-8')) ?></td>
                                        <td><?= htmlspecialchars($row['total'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><span class="status-pill <?= htmlspecialchars($row['tone'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td>
                                            <div class="transaction-actions" aria-label="Aksi transaksi">
                                                <button class="view" type="button" aria-label="Lihat nota <?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>" data-transaction-action="view" data-transaction-nota="<?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>"><?= transactionActionIcon('view') ?></button>
                                                <?php if ($whatsappUrl !== ''): ?>
                                                    <a class="wa" href="<?= htmlspecialchars($whatsappUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="Kirim nota WhatsApp <?= htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8') ?>"><?= $whatsappLogo ?></a>
                                                <?php else: ?>
                                                    <button class="wa disabled" type="button" aria-label="Nomor WhatsApp belum tersedia" disabled><?= $whatsappLogo ?></button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="laundry-pagination transaction-pagination">
                        <p>Menampilkan <?= (int) $paginationFrom ?> - <?= (int) $paginationTo ?> dari <?= (int) $totalRows ?> transaksi</p>
                        <div class="page-buttons">
                            <?php if ($currentPage > 1): ?>
                                <a class="pagination-page" href="<?= $paginationUrl($currentPage - 1) ?>" aria-label="Sebelumnya">&#8592;</a>
                            <?php else: ?>
                                <span class="pagination-page disabled" aria-hidden="true">&#8592;</span>
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
                                <a class="pagination-page" href="<?= $paginationUrl($currentPage + 1) ?>" aria-label="Berikutnya">&#8594;</a>
                            <?php else: ?>
                                <span class="pagination-page disabled" aria-hidden="true">&#8594;</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <aside class="laundry-side-panel transaction-side-panel">
                    <article class="laundry-widget transaction-summary-widget">
                        <h2>Ringkasan Transaksi</h2>
                        <div class="transaction-metric-list">
                            <article>
                                <span class="blue" aria-hidden="true">&#128188;</span>
                                <p>Total Transaksi<strong><?= (int) ($transactionSummary['total'] ?? 0) ?> <small>Transaksi</small></strong></p>
                            </article>
                            <article>
                                <span class="green" aria-hidden="true">&#128181;</span>
                                <p>Total Pendapatan<strong>Rp <?= number_format((float) ($transactionSummary['revenue'] ?? 0), 0, ',', '.') ?></strong></p>
                            </article>
                            <article>
                                <span class="orange" aria-hidden="true">&#128200;</span>
                                <p>Rata-rata per Transaksi<strong>Rp <?= number_format($averageTransaction, 0, ',', '.') ?></strong></p>
                            </article>
                        </div>

                        <div class="transaction-status-summary">
                            <h3>Rekap Status</h3>
                            <div class="laundry-status-list">
                                <?php foreach ($statusSummary as $status): ?>
                                    <?php
                                    $statusLabel = (string) ($status['label'] ?? '');
                                    $statusColor = $statusColors[$statusLabel] ?? '#2f80ed';
                                    ?>
                                    <p style="--status-color: <?= htmlspecialchars($statusColor, ENT_QUOTES, 'UTF-8') ?>;"><span class="<?= htmlspecialchars($status['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?><strong><?= $status['value'] ?></strong><small>(<?= htmlspecialchars($status['percent'], ENT_QUOTES, 'UTF-8') ?>)</small></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>

                    <article class="laundry-widget">
                        <h2>Aktivitas Nota</h2>
                        <div class="activity-list transaction-activity-list">
                            <?php foreach ($noteActivities as $activity): ?>
                                <article class="activity-item">
                                    <span class="<?= htmlspecialchars($activity['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $activity['icon'] ?></span>
                                    <p><strong><?= htmlspecialchars($activity['title'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($activity['detail'], ENT_QUOTES, 'UTF-8') ?></small></p>
                                    <time><?= htmlspecialchars($activity['time'], ENT_QUOTES, 'UTF-8') ?></time>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <a class="view-all-activity" href="#">Lihat semua aktivitas</a>
                    </article>
                </aside>
            </section>
        </main>

        <script id="transactionData" type="application/json"><?= $transactionJson ?: '{"transactions":[]}' ?></script>

        <div class="laundry-modal-backdrop transaction-receipt-modal" data-transaction-receipt-modal hidden>
            <section class="laundry-dialog transaction-receipt-dialog" role="dialog" aria-modal="true" aria-labelledby="transactionReceiptTitle">
                <button class="laundry-modal-close transaction-print-hidden" type="button" aria-label="Tutup nota transaksi" data-transaction-receipt-close>&times;</button>

                <header class="laundry-modal-header transaction-print-hidden">
                    <h2 id="transactionReceiptTitle">Detail Nota</h2>
                    <p data-transaction-receipt-subtitle>Pilih transaksi untuk melihat rincian nota.</p>
                </header>

                <div class="transaction-receipt-body">
                    <div class="transaction-receipt-brand">
                        <span><img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt=""></span>
                        <div>
                            <strong><?= htmlspecialchars($setting('laundry_name', 'Ghava Laundry'), ENT_QUOTES, 'UTF-8') ?></strong>
                            <small data-transaction-receipt-nota>-</small>
                        </div>
                    </div>

                    <dl class="transaction-receipt-grid">
                        <div>
                            <dt>Pelanggan</dt>
                            <dd data-transaction-receipt-customer>-</dd>
                        </div>
                        <div>
                            <dt>No. Telepon</dt>
                            <dd data-transaction-receipt-phone>-</dd>
                        </div>
                        <div>
                            <dt>Layanan</dt>
                            <dd data-transaction-receipt-service>-</dd>
                        </div>
                        <div>
                            <dt>Berat/Qty</dt>
                            <dd data-transaction-receipt-weight>-</dd>
                        </div>
                        <div>
                            <dt>Tgl Masuk</dt>
                            <dd data-transaction-receipt-in>-</dd>
                        </div>
                        <div>
                            <dt>Estimasi Selesai</dt>
                            <dd data-transaction-receipt-eta>-</dd>
                        </div>
                        <div>
                            <dt>Status</dt>
                            <dd><span class="status-pill blue" data-transaction-receipt-status>-</span></dd>
                        </div>
                    </dl>

                    <p class="transaction-receipt-note" data-transaction-receipt-notes-row hidden>
                        <span>Catatan</span>
                        <strong data-transaction-receipt-notes></strong>
                    </p>

                    <div class="transaction-receipt-total">
                        <span>Total Harga</span>
                        <strong data-transaction-receipt-total>-</strong>
                    </div>
                </div>

                <div class="laundry-modal-actions transaction-receipt-actions transaction-print-hidden">
                    <a class="transaction-receipt-wa" href="#" target="_blank" rel="noopener" data-transaction-receipt-wa>
                        <?= $whatsappLogo ?>
                        Kirim WhatsApp
                    </a>
                    <div>
                        <button class="laundry-cancel-button" type="button" data-transaction-receipt-close>Tutup</button>
                        <button class="laundry-save-button" type="button" data-transaction-receipt-print>
                            <span aria-hidden="true">&#128424;</span>
                            Cetak Nota
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
