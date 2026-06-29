<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$admin = $admin ?? [];
$adminName = (string) ($admin['name'] ?? 'Admin Laundry');
$adminRole = (string) ($admin['role'] ?? 'Administrator');

$dashboardTimezone = new DateTimeZone('Asia/Makassar');
$dashboardToday = new DateTimeImmutable('now', $dashboardTimezone);
$monthNames = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];

$dashboardPeriod = $dashboardPeriod ?? [];
$selectedMonthValue = (string) ($dashboardPeriod['selected_month'] ?? $dashboardToday->format('Y-m'));

try {
    $dashboardMonthStart = new DateTimeImmutable($selectedMonthValue . '-01 00:00:00', $dashboardTimezone);
} catch (Throwable) {
    $dashboardMonthStart = $dashboardToday->modify('first day of this month')->setTime(0, 0);
}

$currentMonthName = $monthNames[(int) $dashboardMonthStart->format('n')];
$currentYear = $dashboardMonthStart->format('Y');
$daysInCurrentMonth = (int) $dashboardMonthStart->format('t');
$currentPeriodLabel = (string) ($dashboardPeriod['range_label'] ?? sprintf('1 - %d %s %s', $daysInCurrentMonth, $currentMonthName, $currentYear));
$currentPeriodShortLabel = (string) ($dashboardPeriod['short_label'] ?? ($currentMonthName . ' ' . $currentYear));
$currentPeriodFullLabel = (string) ($dashboardPeriod['label'] ?? sprintf('%s (%s)', $currentPeriodShortLabel, $currentPeriodLabel));
$periodOptions = $dashboardPeriod['options'] ?? [
    ['value' => $dashboardMonthStart->format('Y-m'), 'label' => $currentPeriodFullLabel],
];
$chartDays = [1, 8, 15, 22, $daysInCurrentMonth];
$dashboardStatusOptions = $statusOptions ?? ['Antrean', 'Diproses', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
$dashboardStatusFilterUrl = static function (string $status = '') use ($safeBaseUrl, $dashboardMonthStart): string {
    $params = [
        'date_from' => $dashboardMonthStart->format('Y-m-d'),
        'date_to' => $dashboardMonthStart->modify('last day of this month')->format('Y-m-d'),
    ];

    if ($status !== '') {
        $params['status'] = $status;
    }

    return $safeBaseUrl . '/admin/cucian?' . htmlspecialchars(http_build_query($params), ENT_QUOTES, 'UTF-8');
};

$formatShortAmount = static function (float $value): string {
    if ($value >= 1000000) {
        return rtrim(rtrim(number_format($value / 1000000, 1, ',', '.'), '0'), ',') . ' jt';
    }

    if ($value >= 1000) {
        return rtrim(rtrim(number_format($value / 1000, 1, ',', '.'), '0'), ',') . ' rb';
    }

    return number_format($value, 0, ',', '.');
};
$formatRupiah = static fn (float $value): string => 'Rp ' . number_format($value, 0, ',', '.');
$revenueStats = $revenueStats ?? ['total' => 0, 'average' => 0, 'peak' => 0, 'peak_date' => null, 'max' => 1, 'daily' => []];
$dailyRevenueByDate = [];

foreach (($revenueStats['daily'] ?? []) as $dailyRevenue) {
    $dailyRevenueByDate[(string) $dailyRevenue['date']] = (float) $dailyRevenue['value'];
}

$chartMaxValue = max(1, (float) ($revenueStats['max'] ?? 1));
$chartScaleStep = $chartMaxValue >= 1000000 ? 1000000 : ($chartMaxValue >= 100000 ? 100000 : 10000);
$chartScaleMax = max((float) $chartScaleStep, (float) (ceil($chartMaxValue / $chartScaleStep) * $chartScaleStep));
$chartAxisLabels = [
    $formatShortAmount($chartScaleMax),
    $formatShortAmount($chartScaleMax * 0.75),
    $formatShortAmount($chartScaleMax * 0.5),
    $formatShortAmount($chartScaleMax * 0.25),
    '0',
];
$chartBars = [];
$peakValue = 0.0;
$peakDay = min(15, $daysInCurrentMonth);

for ($day = 1; $day <= $daysInCurrentMonth; $day++) {
    $date = $dashboardMonthStart->setDate((int) $currentYear, (int) $dashboardMonthStart->format('n'), $day);
    $dateKey = $date->format('Y-m-d');
    $value = $dailyRevenueByDate[$dateKey] ?? 0.0;
    $height = $value > 0 ? max(5, min(100, ($value / $chartScaleMax) * 100)) : 2;
    $chartBars[] = [
        'date' => $dateKey,
        'height' => $height,
    ];

    if ($value > $peakValue) {
        $peakValue = $value;
        $peakDay = $day;
    }
}

$peakDateValue = (string) ($revenueStats['peak_date'] ?? '');

if ($peakDateValue !== '') {
    try {
        $peakDate = new DateTimeImmutable($peakDateValue, $dashboardTimezone);
        $peakDay = (int) $peakDate->format('j');
    } catch (Throwable) {
        $peakDate = $dashboardMonthStart->setDate((int) $currentYear, (int) $dashboardMonthStart->format('n'), $peakDay);
    }
} else {
    $peakDate = $dashboardMonthStart->setDate((int) $currentYear, (int) $dashboardMonthStart->format('n'), $peakDay);
}

$pointLeft = 10 + (($peakDay - 1) / max(1, $daysInCurrentMonth - 1)) * 84;
$pointTop = 18 + (1 - min(1, $peakValue / $chartScaleMax)) * 142;
$tooltipLeft = max(24, min(82, $pointLeft));
$tooltipTop = max(18, $pointTop - 56);
$chartTooltipDate = $peakDate->format('j') . ' ' . $monthNames[(int) $peakDate->format('n')] . ' ' . $peakDate->format('Y');
$chartTooltipAmount = $formatRupiah((float) ($revenueStats['peak'] ?? $peakValue));

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin', 'active' => true],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$summaryCards = $summaryCards ?? [
    ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => '87', 'meta' => $currentPeriodShortLabel],
    ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Pesanan', 'value' => '132', 'meta' => $currentPeriodShortLabel],
    ['tone' => 'purple', 'icon' => '&#128179;', 'label' => 'Total Pendapatan', 'value' => 'Rp 8.450.000', 'meta' => $currentPeriodShortLabel],
    ['tone' => 'orange', 'icon' => '&#128203;', 'label' => 'Pesanan Selesai', 'value' => '98', 'meta' => $currentPeriodShortLabel],
];

$orders = $orders ?? [
    ['nota' => 'INV-250521-001', 'customer' => 'Budi Santoso', 'date' => '21 Mei 2026', 'service' => 'Cuci Setrika', 'status' => 'Dicuci', 'tone' => 'blue', 'total' => 'Rp 45.000'],
    ['nota' => 'INV-250521-002', 'customer' => 'Siti Aisyah', 'date' => '21 Mei 2026', 'service' => 'Cuci Kering', 'status' => 'Antrean', 'tone' => 'cyan', 'total' => 'Rp 35.000'],
    ['nota' => 'INV-250520-015', 'customer' => 'Andi Wijaya', 'date' => '20 Mei 2026', 'service' => 'Cuci Setrika', 'status' => 'Disetrika', 'tone' => 'orange', 'total' => 'Rp 40.000'],
    ['nota' => 'INV-250520-014', 'customer' => 'Rina Marlina', 'date' => '20 Mei 2026', 'service' => 'Cuci Kering', 'status' => 'Selesai', 'tone' => 'purple', 'total' => 'Rp 30.000'],
    ['nota' => 'INV-250520-013', 'customer' => 'Dewi Lestari', 'date' => '20 Mei 2026', 'service' => 'Cuci Setrika', 'status' => 'Diambil', 'tone' => 'green', 'total' => 'Rp 50.000'],
];

$services = $services ?? [
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

        <a class="dashboard-logout" href="<?= $safeBaseUrl ?>/admin/logout">
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

            <?php require __DIR__ . '/partials/topbar-userbar.php'; ?>
        </header>

        <main class="dashboard-main">
            <section class="dashboard-heading">
                <div>
                    <h1>Dashboard</h1>
                    <p><strong>Selamat datang kembali, Admin!</strong> Berikut adalah ringkasan data operasional Ghava Laundry.</p>
                </div>
                <form class="dashboard-period-form" action="<?= $safeBaseUrl ?>/admin" method="get" data-dashboard-period-form>
                    <input type="hidden" name="month" value="<?= htmlspecialchars($dashboardMonthStart->format('Y-m'), ENT_QUOTES, 'UTF-8') ?>" data-dashboard-period-input>
                    <button class="dashboard-date-button dashboard-period-toggle" type="button" aria-label="Pilih periode dashboard" aria-haspopup="listbox" aria-expanded="false" aria-controls="dashboardPeriodMenu" data-dashboard-period-toggle>
                        <span class="dashboard-period-icon" aria-hidden="true">&#128197;</span>
                        <span class="dashboard-period-label"><?= htmlspecialchars($currentPeriodFullLabel, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="dashboard-period-chevron" aria-hidden="true">&#8964;</span>
                    </button>
                    <div class="dashboard-period-menu" id="dashboardPeriodMenu" role="listbox" aria-label="Pilih periode dashboard" data-dashboard-period-menu hidden>
                        <?php foreach ($periodOptions as $option): ?>
                            <?php $isSelectedPeriod = (string) $option['value'] === $dashboardMonthStart->format('Y-m'); ?>
                            <button class="dashboard-period-option <?= $isSelectedPeriod ? 'is-selected' : '' ?>" type="submit" name="month" value="<?= htmlspecialchars((string) $option['value'], ENT_QUOTES, 'UTF-8') ?>" role="option" aria-selected="<?= $isSelectedPeriod ? 'true' : 'false' ?>" data-dashboard-period-option>
                                <?= htmlspecialchars((string) $option['label'], ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <noscript><button class="dashboard-period-submit" type="submit">Terapkan</button></noscript>
                </form>
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
                        <span class="panel-period-label"><?= htmlspecialchars($currentPeriodShortLabel, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="revenue-chart" aria-label="Grafik pendapatan bulan <?= htmlspecialchars($currentMonthName, ENT_QUOTES, 'UTF-8') ?>">
                        <?php foreach ($chartAxisLabels as $axisLabel): ?><span><?= htmlspecialchars($axisLabel, ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; ?>
                        <div class="revenue-bars" aria-hidden="true">
                            <?php foreach ($chartBars as $chartBar): ?>
                                <i style="height: <?= htmlspecialchars(number_format((float) $chartBar['height'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>%"></i>
                            <?php endforeach; ?>
                        </div>
                        <b class="revenue-point" style="left: <?= htmlspecialchars(number_format($pointLeft, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>%; top: <?= htmlspecialchars(number_format($pointTop, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>px;"></b>
                        <div class="chart-tooltip" style="left: <?= htmlspecialchars(number_format($tooltipLeft, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>%; top: <?= htmlspecialchars(number_format($tooltipTop, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>px;"><small><?= htmlspecialchars($chartTooltipDate, ENT_QUOTES, 'UTF-8') ?></small><strong><?= htmlspecialchars($chartTooltipAmount, ENT_QUOTES, 'UTF-8') ?></strong></div>
                    </div>
                    <div class="chart-dates" aria-hidden="true">
                        <?php foreach ($chartDays as $chartDay): ?>
                            <span><?= htmlspecialchars(sprintf('%d %s', $chartDay, $currentMonthName), ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="revenue-footer">
                        <span aria-hidden="true">&#128181;</span>
                        <p>Total Pendapatan<strong><?= htmlspecialchars($formatRupiah((float) ($revenueStats['total'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong></p>
                        <p>Rata-rata Per Hari<strong><?= htmlspecialchars($formatRupiah((float) ($revenueStats['average'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong></p>
                    </div>
                </article>

                <article class="dashboard-panel status-panel-admin">
                    <div class="panel-header">
                        <h2>Status Cucian</h2>
                        <details class="panel-status-menu">
                            <summary class="panel-status-summary">Semua Status <span aria-hidden="true">&#8964;</span></summary>
                            <div class="panel-status-options">
                                <a href="<?= $dashboardStatusFilterUrl() ?>">Semua Status</a>
                                <?php foreach ($dashboardStatusOptions as $statusOption): ?>
                                    <a href="<?= $dashboardStatusFilterUrl((string) $statusOption) ?>"><?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?></a>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    </div>
                    <?php
                    $statuses = $statusSummary ?? [
                        ['label' => 'Antrean', 'value' => '12', 'percent' => '15,8%', 'tone' => 'blue-light'],
                        ['label' => 'Diproses', 'value' => '12', 'percent' => '15,8%', 'tone' => 'green'],
                        ['label' => 'Dicuci', 'value' => '16', 'percent' => '21,1%', 'tone' => 'teal'],
                        ['label' => 'Dikeringkan', 'value' => '10', 'percent' => '13,2%', 'tone' => 'cyan'],
                        ['label' => 'Disetrika', 'value' => '8', 'percent' => '10,5%', 'tone' => 'orange'],
                        ['label' => 'Selesai', 'value' => '14', 'percent' => '18,4%', 'tone' => 'purple'],
                        ['label' => 'Diambil', 'value' => '4', 'percent' => '5,3%', 'tone' => 'green'],
                    ];
                    $statusTotal = array_sum(array_map(static fn ($status) => (int) $status['value'], $statuses));
                    $statusColors = [
                        'Antrean' => '#2f80ed',
                        'Diproses' => '#28a765',
                        'Dicuci' => '#32b7c7',
                        'Dikeringkan' => '#0ea5d8',
                        'Disetrika' => '#f59e0b',
                        'Selesai' => '#7047d9',
                        'Diambil' => '#3dbb4f',
                    ];
                    $statusToneColors = [
                        'blue-light' => '#5bb4ef',
                        'blue' => '#2f80ed',
                        'green' => '#28a765',
                        'teal' => '#32b7c7',
                        'cyan' => '#0ea5d8',
                        'amber' => '#f59e0b',
                        'orange' => '#f59e0b',
                        'purple' => '#7047d9',
                        'red' => '#ef4a66',
                    ];
                    $statusSegments = [];
                    $statusOffset = 0.0;

                    foreach ($statuses as $status) {
                        $statusLabel = (string) ($status['label'] ?? '');
                        $statusValue = max(0, (int) ($status['value'] ?? 0));

                        if ($statusTotal <= 0 || $statusValue <= 0) {
                            continue;
                        }

                        $statusPercent = ($statusValue / $statusTotal) * 100;
                        $statusEnd = $statusOffset + $statusPercent;
                        $statusColor = $statusColors[$statusLabel] ?? $statusToneColors[(string) ($status['tone'] ?? '')] ?? '#2f80ed';
                        $statusSegments[] = $statusColor . ' '
                            . number_format($statusOffset, 4, '.', '') . '% '
                            . number_format($statusEnd, 4, '.', '') . '%';
                        $statusOffset = $statusEnd;
                    }

                    $statusDonutGradient = $statusSegments === [] ? '#eef4fb' : 'conic-gradient(' . implode(', ', $statusSegments) . ')';
                    ?>
                    <div class="status-content">
                        <div class="status-donut" style="--status-donut: <?= htmlspecialchars($statusDonutGradient, ENT_QUOTES, 'UTF-8') ?>;" aria-label="Total <?= (int) $statusTotal ?> pesanan">
                            <div><span>Total</span><strong><?= (int) $statusTotal ?></strong><small>Pesanan</small></div>
                        </div>
                        <div class="status-legend">
                            <?php foreach ($statuses as $status): ?>
                                <?php
                                $legendLabel = (string) ($status['label'] ?? '');
                                $legendColor = $statusColors[$legendLabel] ?? $statusToneColors[(string) ($status['tone'] ?? '')] ?? '#2f80ed';
                                ?>
                                <p style="--status-color: <?= htmlspecialchars($legendColor, ENT_QUOTES, 'UTF-8') ?>;">
                                    <span class="status-legend-dot <?= htmlspecialchars($status['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
                                    <span class="status-legend-label"><?= htmlspecialchars($legendLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                    <strong><?= (int) $status['value'] ?></strong>
                                    <small><?= htmlspecialchars($status['percent'], ENT_QUOTES, 'UTF-8') ?></small>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>
            </section>

            <section class="dashboard-grid lower-grid">
                <article class="dashboard-panel orders-panel">
                    <div class="panel-header">
                        <h2>Pesanan Terbaru</h2>
                        <a class="panel-action-link" href="<?= $safeBaseUrl ?>/admin/transaksi">Lihat Semua</a>
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
                                <?php if ($orders === []): ?>
                                    <tr>
                                        <td class="dashboard-empty-row" colspan="6">Belum ada pesanan pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['nota'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($order['customer'] ?? $order['name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($order['date'] ?? $order['in'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
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
                        <span class="panel-period-label"><?= htmlspecialchars($currentPeriodShortLabel, ENT_QUOTES, 'UTF-8') ?></span>
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
