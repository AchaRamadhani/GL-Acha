<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$csrfTokenSafe = htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8');
$nextCustomerCodeSafe = htmlspecialchars($nextCustomerCode ?? 'Otomatis', ENT_QUOTES, 'UTF-8');
$admin = $admin ?? [];
$adminName = (string) ($admin['name'] ?? 'Admin Laundry');
$adminRole = (string) ($admin['role'] ?? 'Administrator');

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
$customerStatusOptions = $customerStatusOptions ?? [
    ['value' => 'active', 'label' => 'Pelanggan Aktif'],
    ['value' => 'new', 'label' => 'Pelanggan Baru'],
    ['value' => 'inactive', 'label' => 'Pelanggan Tidak Aktif'],
];
$filters = array_merge([
    'search' => '',
    'status' => '',
    'date_from' => '',
    'date_to' => '',
], is_array($filters ?? null) ? $filters : []);
$filterSearchSafe = htmlspecialchars((string) $filters['search'], ENT_QUOTES, 'UTF-8');
$filterStatus = (string) $filters['status'];
$filterDateFromSafe = htmlspecialchars((string) $filters['date_from'], ENT_QUOTES, 'UTF-8');
$filterDateToSafe = htmlspecialchars((string) $filters['date_to'], ENT_QUOTES, 'UTF-8');
$pagination = array_merge([
    'page' => 1,
    'perPage' => 10,
    'perPageOptions' => [10],
    'totalPages' => 1,
    'from' => count($customers) > 0 ? 1 : 0,
    'to' => count($customers),
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

    return $safeBaseUrl . '/admin/pelanggan' . ($queryString !== '' ? '?' . htmlspecialchars($queryString, ENT_QUOTES, 'UTF-8') : '');
};
$customerCrudData = array_map(static fn (array $row): array => [
    'id' => (int) ($row['database_id'] ?? 0),
    'code' => (string) ($row['id'] ?? ''),
    'name' => (string) ($row['name'] ?? ''),
    'phone' => (string) ($row['phone'] ?? ''),
    'address' => (string) ($row['address_value'] ?? (($row['address'] ?? '') === '-' ? '' : ($row['address'] ?? ''))),
    'transactions' => (int) ($row['transactions'] ?? 0),
    'active' => (int) ($row['active'] ?? 0),
    'last' => (string) ($row['last'] ?? '-'),
    'created' => (string) ($row['created'] ?? '-'),
], $customers);
$customerCrudJson = json_encode($customerCrudData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

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

            <?php require __DIR__ . '/partials/topbar-userbar.php'; ?>
        </header>

        <main class="dashboard-main laundry-main customer-main">
            <section class="laundry-heading customer-heading">
                <div>
                    <h1>Pelanggan</h1>
                    <p>Kelola data pelanggan dan lihat riwayat aktivitas laundry mereka.</p>
                </div>
                <button class="add-laundry-button customer-add-button" type="button" data-laundry-modal-open="customer" data-customer-create>
                    <span aria-hidden="true">+</span>
                    Tambah Pelanggan
                </button>
            </section>

            <?php if (!empty($successMessage)): ?>
                <div class="admin-flash success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="admin-flash error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

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
                    <form class="laundry-filter-bar customer-filter-bar" action="<?= $safeBaseUrl ?>/admin/pelanggan" method="get">
                        <label class="laundry-search-field" for="customerSearch">
                            <span aria-hidden="true">&#128269;</span>
                            <input id="customerSearch" name="q" type="search" placeholder="Cari nama pelanggan atau no. telepon..." value="<?= $filterSearchSafe ?>" autocomplete="off">
                        </label>
                        <select name="status" aria-label="Filter pelanggan">
                            <option value="">Semua Pelanggan</option>
                            <?php foreach ($customerStatusOptions as $option): ?>
                                <?php
                                $optionValue = (string) ($option['value'] ?? '');
                                $optionLabel = (string) ($option['label'] ?? $optionValue);
                                ?>
                                <option value="<?= htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8') ?>" <?= $filterStatus === $optionValue ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($optionLabel, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="date-filter date-filter-range" aria-label="Filter tanggal daftar pelanggan">
                            <span aria-hidden="true">&#128197;</span>
                            <input id="customerDateFrom" name="date_from" type="date" value="<?= $filterDateFromSafe ?>" aria-label="Tanggal mulai">
                            <span class="date-filter-separator" aria-hidden="true">-</span>
                            <input id="customerDateTo" name="date_to" type="date" value="<?= $filterDateToSafe ?>" aria-label="Tanggal akhir">
                        </div>
                        <button class="filter-primary" type="submit">
                            <span class="filter-button-icon" aria-hidden="true"></span>
                            Filter
                        </button>
                        <a class="filter-reset" href="<?= $safeBaseUrl ?>/admin/pelanggan">
                            <span aria-hidden="true">&#8635;</span>
                            Reset Filter
                        </a>
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
                                <?php if ($customers === []): ?>
                                    <tr>
                                        <td class="laundry-empty-row" colspan="9">Data pelanggan tidak ditemukan. Coba ubah atau reset filter.</td>
                                    </tr>
                                <?php else: ?>
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
                                                    <button class="view" type="button" aria-label="Lihat pelanggan" data-customer-action="view" data-customer-id="<?= (int) ($customer['database_id'] ?? 0) ?>"><?= laundryActionIcon('view') ?></button>
                                                    <button class="edit" type="button" aria-label="Ubah pelanggan" data-customer-action="edit" data-customer-id="<?= (int) ($customer['database_id'] ?? 0) ?>"><?= laundryActionIcon('edit') ?></button>
                                                    <button class="delete" type="button" aria-label="Hapus pelanggan" data-customer-action="delete" data-customer-id="<?= (int) ($customer['database_id'] ?? 0) ?>"><?= laundryActionIcon('delete') ?></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="laundry-pagination customer-pagination">
                        <p>Menampilkan <?= (int) $paginationFrom ?> - <?= (int) $paginationTo ?> dari <?= (int) $totalRows ?> pelanggan</p>
                        <form class="pagination-size-form" action="<?= $safeBaseUrl ?>/admin/pelanggan" method="get">
                            <input type="hidden" name="q" value="<?= $filterSearchSafe ?>">
                            <input type="hidden" name="status" value="<?= htmlspecialchars($filterStatus, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="date_from" value="<?= $filterDateFromSafe ?>">
                            <input type="hidden" name="date_to" value="<?= $filterDateToSafe ?>">
                            <input type="hidden" name="page" value="1">
                            <select name="per_page" aria-label="Jumlah pelanggan per halaman" onchange="this.form.submit()">
                                <?php foreach ($perPageOptions as $perPageOption): ?>
                                    <option value="<?= (int) $perPageOption ?>" <?= (int) $perPageOption === $currentPerPage ? 'selected' : '' ?>>
                                        <?= (int) $perPageOption ?> per halaman
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
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
                                        <span class="customer-summary-dot <?= htmlspecialchars($summary['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
                                        <span class="customer-summary-copy">
                                            <span class="customer-summary-label"><?= htmlspecialchars($summary['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                            <span class="customer-summary-meta">
                                                <strong><?= $summary['value'] ?></strong>
                                                <small><?= htmlspecialchars($summary['percent'], ENT_QUOTES, 'UTF-8') ?></small>
                                            </span>
                                        </span>
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

        <script id="customerCrudData" type="application/json"><?= $customerCrudJson ?: '[]' ?></script>

        <form action="<?= $safeBaseUrl ?>/admin/pelanggan/delete" method="post" data-customer-delete-form hidden>
            <input type="hidden" name="_token" value="<?= $csrfTokenSafe ?>">
            <input type="hidden" name="customer_id" value="">
        </form>

        <div class="laundry-modal-backdrop customer-modal-backdrop" data-laundry-modal="customer" hidden>
            <section class="laundry-dialog customer-dialog" role="dialog" aria-modal="true" aria-labelledby="customerModalTitle">
                <button class="laundry-modal-close" type="button" aria-label="Tutup form tambah pelanggan" data-laundry-modal-close>&times;</button>

                <header class="laundry-modal-header" data-customer-modal-header>
                    <h2 id="customerModalTitle">Tambah Data Pelanggan</h2>
                    <p>Masukkan data pelanggan dengan lengkap.</p>
                </header>

                <form class="laundry-modal-form customer-modal-form" action="<?= $safeBaseUrl ?>/admin/pelanggan" method="post" data-laundry-form data-customer-form data-create-action="<?= $safeBaseUrl ?>/admin/pelanggan" data-update-action="<?= $safeBaseUrl ?>/admin/pelanggan/update">
                    <input type="hidden" name="_token" value="<?= $csrfTokenSafe ?>">
                    <input type="hidden" name="customer_id" value="" data-customer-id-field>

                    <div class="laundry-modal-field customer-id-field">
                        <label for="customerCode">ID Pelanggan</label>
                        <input id="customerCode" type="text" value="<?= $nextCustomerCodeSafe ?>" readonly data-customer-code-field data-next-customer-code="<?= $nextCustomerCodeSafe ?>">
                        <small>ID pelanggan dibuat otomatis</small>
                    </div>

                    <div class="laundry-modal-field customer-phone-field">
                        <label for="customerPhone">No. Telepon <span>*</span></label>
                        <input id="customerPhone" type="tel" name="phone" placeholder="0812-3456-7890" autocomplete="tel" required data-laundry-first-field>
                    </div>

                    <div class="laundry-modal-field customer-name-field">
                        <label for="customerName">Nama Pelanggan <span>*</span></label>
                        <input id="customerName" type="text" name="customer_name" placeholder="Masukkan nama pelanggan" autocomplete="name" required>
                    </div>

                    <div class="laundry-modal-field customer-address-field">
                        <label for="customerAddress">Alamat</label>
                        <textarea id="customerAddress" name="address" rows="4" placeholder="Masukkan alamat pelanggan"></textarea>
                        <small>Alamat pelanggan (opsional)</small>
                    </div>

                    <div class="laundry-modal-actions">
                        <button class="laundry-clear-button" type="button" data-laundry-form-reset>
                            <span aria-hidden="true">&#8635;</span>
                            Bersihkan
                        </button>
                        <div>
                            <button class="laundry-cancel-button" type="button" data-laundry-modal-close>Batal</button>
                            <button class="laundry-save-button" type="submit" data-customer-save-button>
                                <span aria-hidden="true">&#128190;</span>
                                <span data-customer-submit-label>Simpan Data</span>
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
