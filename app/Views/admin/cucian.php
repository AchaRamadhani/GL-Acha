<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$whatsappLogo = '<img src="' . $safeBaseUrl . '/assets/img/whatsapp-logo.svg?v=6" alt="">';

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian', 'active' => true],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$stats = [
    ['tone' => 'blue', 'icon' => '&#128722;', 'label' => 'Total Data Cucian', 'value' => '186', 'meta' => 'Cucian'],
    ['tone' => 'orange', 'icon' => '&#9719;', 'label' => 'Sedang Diproses', 'value' => '58', 'meta' => 'Cucian'],
    ['tone' => 'green', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => '32', 'meta' => 'Cucian'],
    ['tone' => 'purple', 'icon' => '&#128717;', 'label' => 'Diambil', 'value' => '24', 'meta' => 'Cucian'],
];

$laundryRows = [
    ['no' => 1, 'nota' => 'INV-250521-001', 'name' => 'Budi Santoso', 'phone' => '0812-3456-7890', 'service' => 'Cuci Kering', 'weight' => '5,0 kg', 'in' => '21 Mei 2026', 'eta' => '23 Mei 2026', 'status' => 'Antrean', 'tone' => 'cyan', 'total' => 'Rp 45.000'],
    ['no' => 2, 'nota' => 'INV-250521-002', 'name' => 'Siti Aisyah', 'phone' => '0813-2345-6789', 'service' => 'Cuci Lipat', 'weight' => '4,2 kg', 'in' => '21 Mei 2026', 'eta' => '22 Mei 2026', 'status' => 'Diproses', 'tone' => 'orange', 'total' => 'Rp 35.000'],
    ['no' => 3, 'nota' => 'INV-250521-003', 'name' => 'Andi Wijaya', 'phone' => '0811-2233-4455', 'service' => 'Cuci Setrika Lipat', 'weight' => '6,0 kg', 'in' => '21 Mei 2026', 'eta' => '23 Mei 2026', 'status' => 'Dicuci', 'tone' => 'blue', 'total' => 'Rp 60.000'],
    ['no' => 4, 'nota' => 'INV-250520-015', 'name' => 'Dewi Lestari', 'phone' => '0812-1122-3344', 'service' => 'Setrika Saja', 'weight' => '3,0 kg', 'in' => '20 Mei 2026', 'eta' => '21 Mei 2026', 'status' => 'Disetrika', 'tone' => 'orange', 'total' => 'Rp 30.000'],
    ['no' => 5, 'nota' => 'INV-250520-014', 'name' => 'Rina Marlina', 'phone' => '0813-9988-7766', 'service' => 'Pengering & Lipat', 'weight' => '5,5 kg', 'in' => '20 Mei 2026', 'eta' => '21 Mei 2026', 'status' => 'Dikeringkan', 'tone' => 'teal', 'total' => 'Rp 40.000'],
    ['no' => 6, 'nota' => 'INV-250520-013', 'name' => 'Ahmad Fauzi', 'phone' => '0811-5566-7788', 'service' => 'Baju Bayi', 'weight' => '3,0 kg', 'in' => '20 Mei 2026', 'eta' => '21 Mei 2026', 'status' => 'Dicuci', 'tone' => 'blue', 'total' => 'Rp 50.000'],
    ['no' => 7, 'nota' => 'INV-250519-012', 'name' => 'Maya Putri', 'phone' => '0812-6677-8899', 'service' => 'Satuan', 'weight' => '10 pcs', 'in' => '19 Mei 2026', 'eta' => '19 Mei 2026', 'status' => 'Selesai', 'tone' => 'green', 'total' => 'Rp 25.000'],
    ['no' => 8, 'nota' => 'INV-250519-011', 'name' => 'Rizky Pratama', 'phone' => '0813-7788-9900', 'service' => 'Express', 'weight' => '7,0 kg', 'in' => '19 Mei 2026', 'eta' => '20 Mei 2026', 'status' => 'Selesai', 'tone' => 'green', 'total' => 'Rp 85.000'],
    ['no' => 9, 'nota' => 'INV-250518-010', 'name' => 'Nurul Hidayah', 'phone' => '0811-8999-2211', 'service' => 'Treatment', 'weight' => '3,5 kg', 'in' => '18 Mei 2026', 'eta' => '20 Mei 2026', 'status' => 'Selesai', 'tone' => 'green', 'total' => 'Rp 70.000'],
    ['no' => 10, 'nota' => 'INV-250518-009', 'name' => 'Fajar Nugroho', 'phone' => '0812-9900-1122', 'service' => 'Cuci Kering', 'weight' => '8,0 kg', 'in' => '18 Mei 2026', 'eta' => '20 Mei 2026', 'status' => 'Diambil', 'tone' => 'purple', 'total' => 'Rp 75.000'],
];

$statusSummary = [
    ['label' => 'Antrean', 'value' => 46, 'percent' => '24,7%', 'tone' => 'blue-light'],
    ['label' => 'Diproses', 'value' => 58, 'percent' => '31,2%', 'tone' => 'orange'],
    ['label' => 'Dicuci', 'value' => 32, 'percent' => '17,2%', 'tone' => 'blue'],
    ['label' => 'Dikeringkan', 'value' => 20, 'percent' => '10,8%', 'tone' => 'teal'],
    ['label' => 'Disetrika', 'value' => 12, 'percent' => '6,5%', 'tone' => 'amber'],
    ['label' => 'Selesai', 'value' => 18, 'percent' => '9,7%', 'tone' => 'green'],
    ['label' => 'Diambil', 'value' => 0, 'percent' => '0%', 'tone' => 'purple'],
];

$activities = [
    ['icon' => '+', 'tone' => 'blue', 'title' => 'Data cucian baru ditambahkan', 'detail' => 'INV-250521-006 - Lina Wati', 'time' => '09:45'],
    ['icon' => '&#9998;', 'tone' => 'blue', 'title' => 'Data cucian diperbarui', 'detail' => 'INV-250521-002 - Siti Aisyah', 'time' => '09:20'],
    ['icon' => '&#10003;', 'tone' => 'green', 'title' => 'Status cucian selesai', 'detail' => 'INV-250519-012 - Maya Putri', 'time' => '08:55'],
    ['icon' => '&#128717;', 'tone' => 'purple', 'title' => 'Cucian diambil pelanggan', 'detail' => 'INV-250518-009 - Fajar Nugroho', 'time' => '08:30'],
    ['icon' => '&#128465;', 'tone' => 'red', 'title' => 'Data cucian dihapus', 'detail' => 'INV-250517-008 - Dedi Kurniawan', 'time' => '08:10'],
];

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page">
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
                    <p><strong>Admin Laundry</strong><small>Administrator</small></p>
                    <span aria-hidden="true">&#8964;</span>
                </div>
            </div>
        </header>

        <main class="dashboard-main laundry-main">
            <section class="laundry-heading">
                <div>
                    <h1>Data Cucian</h1>
                    <p>Kelola seluruh data cucian pelanggan: tambah, lihat, ubah, dan hapus data cucian.</p>
                </div>
                <button class="add-laundry-button" type="button">
                    <span aria-hidden="true">+</span>
                    Tambah Data Cucian
                </button>
            </section>

            <div class="laundry-note">
                <span aria-hidden="true">&#8505;</span>
                <p><strong>Catatan:</strong> Halaman ini untuk pengelolaan data cucian (CRUD). Update status cucian dilakukan melalui menu <a href="<?= $safeBaseUrl ?>/admin/update-status">Update Status</a>.</p>
            </div>

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
                    <form class="laundry-filter-bar" action="#" method="get">
                        <label class="laundry-search-field" for="laundrySearch">
                            <span aria-hidden="true">&#128269;</span>
                            <input id="laundrySearch" type="search" placeholder="Cari no nota atau nama pelanggan..." autocomplete="off">
                        </label>
                        <select aria-label="Filter status">
                            <option>Semua Status</option>
                        </select>
                        <select aria-label="Filter layanan">
                            <option>Semua Layanan</option>
                        </select>
                        <button class="date-filter" type="button">
                            <span aria-hidden="true">&#128197;</span>
                            21 Mei 2026 - 21 Mei 2026
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
                                                <button class="view" type="button" aria-label="Lihat detail">&#128065;</button>
                                                <button class="edit" type="button" aria-label="Ubah data">&#9998;</button>
                                                <button class="delete" type="button" aria-label="Hapus data">&#128465;</button>
                                                <button class="wa" type="button" aria-label="Hubungi WhatsApp"><?= $whatsappLogo ?></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="laundry-pagination">
                        <p>Menampilkan 1 - 10 dari 186 data</p>
                        <div class="page-buttons">
                            <button type="button" aria-label="Sebelumnya">&#8249;</button>
                            <button class="active" type="button">1</button>
                            <button type="button">2</button>
                            <button type="button">3</button>
                            <button type="button">4</button>
                            <button type="button">5</button>
                            <span>...</span>
                            <button type="button">19</button>
                            <button type="button" aria-label="Berikutnya">&#8250;</button>
                        </div>
                        <select aria-label="Jumlah data per halaman">
                            <option>10 / halaman</option>
                        </select>
                    </div>
                </div>

                <aside class="laundry-side-panel">
                    <article class="laundry-widget">
                        <h2>Ringkasan Status</h2>
                        <div class="laundry-status-wrap">
                            <div class="laundry-status-donut" aria-label="Total 186 data cucian">
                                <div><strong>186</strong><span>Total</span></div>
                            </div>
                            <div class="laundry-status-list">
                                <?php foreach ($statusSummary as $status): ?>
                                    <p><span class="<?= htmlspecialchars($status['tone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span><?= htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8') ?><strong><?= $status['value'] ?></strong><small>(<?= htmlspecialchars($status['percent'], ENT_QUOTES, 'UTF-8') ?>)</small></p>
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
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
