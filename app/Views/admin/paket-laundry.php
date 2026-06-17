<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry', 'active' => true],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan'],
];

$packages = [
    ['name' => 'Cuci Kering', 'description' => 'Pakaian dicuci dan dikeringkan', 'price' => 'Rp 45.000', 'duration' => '2 hari', 'category' => 'Kiloan', 'tone' => 'blue', 'icon' => '&#128085;', 'unit' => 'Minimal 3 kg'],
    ['name' => 'Cuci Lipat', 'description' => 'Pakaian dicuci dan dilipat rapi', 'price' => 'Rp 35.000', 'duration' => '1 hari', 'category' => 'Kiloan', 'tone' => 'green', 'icon' => '&#9636;', 'unit' => 'Minimal 3 kg'],
    ['name' => 'Cuci Setrika Lipat', 'description' => 'Pakaian dicuci, disetrika, dan dilipat', 'price' => 'Rp 40.000', 'duration' => '2 hari', 'category' => 'Kiloan', 'tone' => 'purple', 'icon' => '&#128239;', 'unit' => 'Minimal 3 kg'],
    ['name' => 'Setrika Saja', 'description' => 'Pakaian hanya disetrika', 'price' => 'Rp 30.000', 'duration' => '1 hari', 'category' => 'Kiloan', 'tone' => 'orange', 'icon' => '&#128239;', 'unit' => 'Minimal 3 kg'],
    ['name' => 'Pengering & Lipat', 'description' => 'Pakaian dikeringkan dan dilipat', 'price' => 'Rp 30.000', 'duration' => '1 hari', 'category' => 'Kiloan', 'tone' => 'purple', 'icon' => '&#128260;', 'unit' => 'Minimal 3 kg'],
    ['name' => 'Baju Bayi', 'description' => 'Pencucian khusus pakaian bayi', 'price' => 'Rp 50.000', 'duration' => '2 hari', 'category' => 'Kiloan', 'tone' => 'pink', 'icon' => '&#128087;', 'unit' => 'Minimal 3 kg'],
    ['name' => 'Satuan', 'description' => 'Cucian berdasarkan jumlah item', 'price' => 'Rp 5.000', 'duration' => 'Per item', 'category' => 'Khusus', 'tone' => 'teal', 'icon' => '&#128717;', 'unit' => 'Per item'],
    ['name' => 'Express', 'description' => 'Layanan cepat selesai di hari yang sama', 'price' => 'Rp 75.000', 'duration' => '6-8 jam', 'category' => 'Khusus', 'tone' => 'amber', 'icon' => '&#9889;', 'unit' => 'Layanan cepat'],
    ['name' => 'Treatment', 'description' => 'Perawatan khusus noda dan bahan', 'price' => 'Rp 60.000', 'duration' => '1-2 hari', 'category' => 'Khusus', 'tone' => 'blue', 'icon' => '&#128737;', 'unit' => 'Perawatan khusus'],
];

$kiloanPackages = array_filter($packages, static fn ($package) => $package['category'] === 'Kiloan');
$specialPackages = array_filter($packages, static fn ($package) => $package['category'] === 'Khusus');

$stats = [
    ['tone' => 'blue', 'icon' => '&#128230;', 'label' => 'Total Paket', 'value' => count($packages) . ' Paket', 'meta' => 'Semua paket tersedia'],
    ['tone' => 'green', 'icon' => '&#128737;', 'label' => 'Paket Aktif', 'value' => count($packages) . ' Paket', 'meta' => '100% dari total paket'],
    ['tone' => 'purple', 'icon' => '&#9878;', 'label' => 'Layanan Kiloan', 'value' => count($kiloanPackages) . ' Paket', 'meta' => 'Minimal berat 3 kg'],
    ['tone' => 'orange', 'icon' => '&#9734;', 'label' => 'Layanan Khusus', 'value' => count($specialPackages) . ' Paket', 'meta' => 'Satuan, Express, Treatment'],
];

$categorySummary = [
    ['label' => 'Layanan Kiloan', 'value' => count($kiloanPackages), 'percent' => '66,7%', 'width' => '66.7%', 'tone' => 'blue'],
    ['label' => 'Layanan Khusus', 'value' => count($specialPackages), 'percent' => '33,3%', 'width' => '33.3%', 'tone' => 'purple'],
];

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page package-admin-page">
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

        <a class="dashboard-logout" href="<?= $safeBaseUrl ?>/admin/login">
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

        <main class="dashboard-main laundry-main package-main">
            <section class="laundry-heading package-heading">
                <div>
                    <h1>Paket Laundry</h1>
                    <p>Kelola daftar layanan dan paket laundry yang tersedia di Ghava Laundry.</p>
                </div>
                <button class="add-laundry-button package-add-button" type="button">
                    <span aria-hidden="true">+</span>
                    Tambah Paket
                </button>
            </section>

            <div class="laundry-note package-note">
                <span aria-hidden="true">&#8505;</span>
                <p><strong>Keterangan:</strong> Cuci Kering, Cuci Lipat, Cuci Setrika Lipat, Setrika Saja, Pengering &amp; Lipat, dan Baju Bayi masuk kategori <strong>Kiloan</strong>. Perhitungan kiloan menggunakan minimal berat cucian 3 kg.</p>
            </div>

            <section class="laundry-stat-grid package-stat-grid" aria-label="Ringkasan paket laundry">
                <?php foreach ($stats as $stat): ?>
                    <article class="laundry-stat-card <?= htmlspecialchars($stat['tone'], ENT_QUOTES, 'UTF-8') ?>">
                        <span aria-hidden="true"><?= $stat['icon'] ?></span>
                        <div>
                            <p><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8') ?></p>
                            <strong><?= htmlspecialchars((string) $stat['value'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($stat['meta'], ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="laundry-workspace package-workspace">
                <div class="package-list-panel">
                    <form class="package-filter-bar" action="#" method="get">
                        <label class="laundry-search-field" for="packageSearch">
                            <span aria-hidden="true">&#128269;</span>
                            <input id="packageSearch" type="search" placeholder="Cari nama paket..." autocomplete="off">
                        </label>
                        <select aria-label="Filter kategori paket">
                            <option>Semua Kategori</option>
                            <option>Kiloan</option>
                            <option>Khusus</option>
                        </select>
                    </form>

                    <div class="package-card-grid">
                        <?php foreach ($packages as $package): ?>
                            <article class="package-card <?= htmlspecialchars($package['tone'], ENT_QUOTES, 'UTF-8') ?>">
                                <span class="package-icon" aria-hidden="true"><?= $package['icon'] ?></span>
                                <div class="package-card-body">
                                    <div class="package-title-row">
                                        <h2><?= htmlspecialchars($package['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                                        <span class="package-status">Aktif</span>
                                    </div>
                                    <p><?= htmlspecialchars($package['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <div class="package-meta-grid">
                                        <p><span aria-hidden="true">&#9672;</span>Harga mulai<strong><?= htmlspecialchars($package['price'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                                        <p><span aria-hidden="true">&#128337;</span>Durasi<strong><?= htmlspecialchars($package['duration'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                                    </div>
                                    <div class="package-tags">
                                        <span class="<?= $package['category'] === 'Kiloan' ? 'is-kiloan' : 'is-special' ?>"><?= htmlspecialchars($package['category'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php if ($package['category'] === 'Kiloan'): ?>
                                            <span>Min. 3 kg</span>
                                        <?php else: ?>
                                            <span><?= htmlspecialchars($package['unit'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="package-actions" aria-label="Aksi paket <?= htmlspecialchars($package['name'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button class="view" type="button"><span aria-hidden="true">&#128065;</span>Lihat</button>
                                        <button class="edit" type="button"><span aria-hidden="true">&#9998;</span>Edit</button>
                                        <button class="delete" type="button"><span aria-hidden="true">&#128465;</span>Hapus</button>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <aside class="laundry-side-panel package-side-panel">
                    <article class="laundry-widget package-summary-widget">
                        <h2>Ringkasan Kategori Paket</h2>
                        <div class="package-summary-list">
                            <div class="package-summary-row package-summary-head">
                                <span>Kategori</span>
                                <span>Jumlah</span>
                                <span>Persentase</span>
                            </div>
                            <?php foreach ($categorySummary as $summary): ?>
                                <div class="package-summary-row">
                                    <span><?= htmlspecialchars($summary['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <strong><?= $summary['value'] ?></strong>
                                    <span><i><b class="<?= htmlspecialchars($summary['tone'], ENT_QUOTES, 'UTF-8') ?>" style="width: <?= htmlspecialchars($summary['width'], ENT_QUOTES, 'UTF-8') ?>"></b></i><?= htmlspecialchars($summary['percent'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="package-summary-row package-summary-total">
                                <span>Total</span>
                                <strong><?= count($packages) ?></strong>
                                <span>100%</span>
                            </div>
                        </div>
                    </article>

                    <article class="laundry-widget package-rule-widget">
                        <h2>Aturan Kiloan</h2>
                        <div class="package-rule-card">
                            <span aria-hidden="true">&#9878;</span>
                            <p><strong>Minimal 3 kg</strong>Jika cucian kategori kiloan kurang dari 3 kg, sistem tetap menghitung harga berdasarkan 3 kg.</p>
                        </div>
                        <ul>
                            <?php foreach ($kiloanPackages as $package): ?>
                                <li><?= htmlspecialchars($package['name'], ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                </aside>
            </section>
        </main>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
