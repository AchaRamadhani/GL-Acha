<?php ob_start(); ?>
<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$settings = $settings ?? [];
$trackingResult = $trackingResult ?? null;
$trackingSearch = trim((string) ($trackingSearch ?? ''));
$trackingHasSearch = $trackingSearch !== '';
$trackingFound = is_array($trackingResult);
$setting = static fn (string $key, string $fallback = ''): string => (string) ($settings[$key] ?? $fallback);
$whatsappDisplay = $setting('whatsapp', '081242910340') ?: '081242910340';
$whatsappNumber = preg_replace('/\D+/', '', $whatsappDisplay);
$whatsappNumber = str_starts_with($whatsappNumber, '0') ? '62' . substr($whatsappNumber, 1) : $whatsappNumber;
$whatsappMessage = $trackingHasSearch
    ? 'Halo admin Ghava Laundry, saya ingin menanyakan status cucian dengan nomor nota ' . $trackingSearch . '.'
    : 'Halo admin Ghava Laundry, saya ingin menanyakan status cucian.';
$whatsappHref = $whatsappNumber !== ''
    ? 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode($whatsappMessage)
    : 'https://web.whatsapp.com/';
$whatsappLogo = '<img class="whatsapp-logo-image" src="' . $safeBaseUrl . '/assets/img/logo-wa.jpg?v=1" alt="">';
$operationalStatus = $operationalStatus ?? ['status' => 'Buka', 'text' => 'Buka sampai ' . str_replace(':', '.', $setting('close_time', '21:00')) . ' WITA.', 'tone' => 'open'];
$operationalTone = preg_replace('/[^a-z]/', '', (string) ($operationalStatus['tone'] ?? 'open')) ?: 'open';
$operationalStatusClass = 'operational-status-badge is-' . $operationalTone;
$weekdayLabels = [
    '1' => 'Senin',
    '2' => 'Selasa',
    '3' => 'Rabu',
    '4' => 'Kamis',
    '5' => 'Jumat',
    '6' => 'Sabtu',
    '0' => 'Minggu',
];
$closedWeekdayLabels = [];

foreach (array_filter(explode(',', $setting('closed_weekdays', '')), static fn (string $day): bool => $day !== '') as $day) {
    if (isset($weekdayLabels[$day])) {
        $closedWeekdayLabels[] = $weekdayLabels[$day];
    }
}

$weeklyOperationalText = $closedWeekdayLabels === [] ? 'Setiap Hari' : 'Libur: ' . implode(', ', $closedWeekdayLabels);
$ishomaText = $setting('ishoma_enabled', '1') === '1'
    ? 'Ishoma ' . str_replace(':', '.', $setting('ishoma_start_time', '12:00')) . ' - ' . str_replace(':', '.', $setting('ishoma_end_time', '13:00')) . ' WITA'
    : $weeklyOperationalText;

if (($operationalStatus['status'] ?? '') === 'Buka') {
    $operationalStatusClass .= ' open-status';
}

if (!function_exists('homeIcon')) {
    function homeIcon(string $name): string
    {
        $icons = [
            'basket' => '<svg viewBox="0 0 24 24"><path d="M5 10h14l-1.5 9h-11L5 10Z"/><path d="M8 10 12 4l4 6"/><path d="M9 14v2"/><path d="M12 14v2"/><path d="M15 14v2"/></svg>',
            'bag' => '<svg viewBox="0 0 24 24"><path d="M6 8h12l1 13H5Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/><path d="m9 14 2 2 4-5"/></svg>',
            'check' => '<svg viewBox="0 0 24 24"><path d="m20 6-11 11-5-5"/></svg>',
            'clipboard' => '<svg viewBox="0 0 24 24"><path d="M8 5H6a2 2 0 0 0-2 2v13h16V7a2 2 0 0 0-2-2h-2"/><rect x="8" y="3" width="8" height="4" rx="1"/><path d="M8 12h8"/><path d="M8 16h5"/></svg>',
            'clock' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
            'dryer' => '<svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="M7 9h6"/><path d="M7 13c2-2 4 2 6 0s4-2 6 0"/></svg>',
            'facebook' => '<svg viewBox="0 0 24 24"><path d="M14 8h3V4h-3a5 5 0 0 0-5 5v3H6v4h3v5h4v-5h3l1-4h-4V9a1 1 0 0 1 1-1Z"/></svg>',
            'hanger' => '<svg viewBox="0 0 24 24"><path d="M12 10V9a2.5 2.5 0 1 0-2.5-2.5"/><path d="m4 19 8-6 8 6Z"/></svg>',
            'info' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>',
            'instagram' => '<svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="3"/><path d="M17 7h.01"/></svg>',
            'iron' => '<svg viewBox="0 0 24 24"><path d="M6 15h11c2 0 3 1 3 3H4c0-1.7.8-3 2-3Z"/><path d="M7 15V9h6c2.8 0 5.2 2 5.8 4.7"/><path d="M9 12h4"/></svg>',
            'lock' => '<svg viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v2"/></svg>',
            'map' => '<svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-11a7 7 0 0 0-14 0c0 5.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>',
            'monitor' => '<svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/><circle cx="11" cy="10" r="3"/><path d="m14 13 2 2"/></svg>',
            'phone' => '<svg viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2.1Z"/></svg>',
            'receipt' => '<svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7Z"/><path d="M15 3v5h4"/><path d="M9 13h6"/><path d="M9 17h4"/></svg>',
            'search' => '<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4.5-4.5"/></svg>',
            'shield' => '<svg viewBox="0 0 24 24"><path d="M12 3 20 6v6c0 5-3.4 8.2-8 9-4.6-.8-8-4-8-9V6Z"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>',
            'shirt' => '<svg viewBox="0 0 24 24"><path d="M9 4h6l2 2 4 2-2 5-3-1v8H8v-8l-3 1-2-5 4-2Z"/><path d="M9 4c.7 1.4 1.7 2 3 2s2.3-.6 3-2"/></svg>',
            'store' => '<svg viewBox="0 0 24 24"><path d="M4 10h16l-1-5H5Z"/><path d="M5 10v10h14V10"/><path d="M8 20v-6h8v6"/><path d="M4 10a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"/></svg>',
            'towels' => '<svg viewBox="0 0 24 24"><path d="M6 7h10a3 3 0 0 1 0 6H6a3 3 0 0 0 0 6h12"/><path d="M6 7a3 3 0 0 0 0 6h10"/><path d="M6 13a3 3 0 0 0 0 6"/></svg>',
            'washer' => '<svg viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 7h.01"/><path d="M11 7h5"/><circle cx="12" cy="14" r="4"/><path d="M9.5 14.5c1.3-1.1 3.7 1.1 5 0"/></svg>',
            'zap' => '<svg viewBox="0 0 24 24"><path d="m13 2-9 12h8l-1 8 9-12h-8Z"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
}

$trackingSteps = [
    ['icon' => homeIcon('check'), 'label' => 'Antrean', 'time' => '21 Mei 2024 10:30', 'note' => 'Cucian diterima', 'state' => 'done'],
    ['icon' => homeIcon('check'), 'label' => 'Diproses', 'time' => '21 Mei 2024 11:15', 'note' => 'Sedang diproses', 'state' => 'done'],
    ['icon' => homeIcon('washer'), 'label' => 'Dicuci', 'time' => '21 Mei 2024 12:40', 'note' => 'Sedang dicuci', 'state' => 'current'],
    ['icon' => homeIcon('dryer'), 'label' => 'Dikeringkan', 'time' => 'Menunggu proses', 'note' => 'berikutnya.', 'state' => ''],
    ['icon' => homeIcon('iron'), 'label' => 'Disetrika', 'time' => 'Menunggu proses', 'note' => 'berikutnya.', 'state' => ''],
    ['icon' => homeIcon('bag'), 'label' => 'Selesai', 'time' => 'Menunggu proses', 'note' => 'berikutnya.', 'state' => ''],
    ['icon' => homeIcon('basket'), 'label' => 'Diambil', 'time' => 'Cucian siap', 'note' => 'diambil.', 'state' => ''],
];
$trackingDetail = [
    'nota' => '20240521001',
    'tanggal_masuk' => '21 Mei 2024 10:30',
    'nama_pelanggan' => 'Andi Pratama',
    'estimasi_selesai' => '23 Mei 2024 16:30',
    'layanan' => 'Cuci Kering',
    'status' => 'Dicuci',
];

if ($trackingFound) {
    $trackingDetail = [
        'nota' => $trackingResult['nota'],
        'tanggal_masuk' => $trackingResult['tanggal_masuk'],
        'nama_pelanggan' => $trackingResult['nama_pelanggan'],
        'estimasi_selesai' => $trackingResult['estimasi_selesai'],
        'layanan' => $trackingResult['layanan'],
        'status' => $trackingResult['status'],
    ];
    $trackingIconMap = [
        'Antrean' => 'check',
        'Diproses' => 'clipboard',
        'Dicuci' => 'washer',
        'Dikeringkan' => 'dryer',
        'Disetrika' => 'iron',
        'Selesai' => 'bag',
        'Diambil' => 'basket',
    ];
    $trackingSteps = array_map(static function (array $step) use ($trackingIconMap): array {
        return [
            'icon' => homeIcon($trackingIconMap[$step['label']] ?? 'check'),
            'label' => $step['label'],
            'time' => $step['time'],
            'note' => $step['note'],
            'state' => $step['state'],
        ];
    }, $trackingResult['steps']);
}

$trackingCurrentIndex = 0;

foreach ($trackingSteps as $index => $step) {
    if (($step['state'] ?? '') === 'current') {
        $trackingCurrentIndex = $index;
        break;
    }
}

$trackingProgressWidth = count($trackingSteps) > 1
    ? min(100, max(0, ($trackingCurrentIndex / (count($trackingSteps) - 1)) * 100))
    : 0;
$trackingHeading = $trackingHasSearch ? 'Hasil Tracking Cucian' : 'Contoh Hasil Tracking';

$kiloanServices = [
    ['icon' => homeIcon('washer'), 'title' => 'Cuci Kering', 'text' => 'Cucian dicuci dan dikeringkan hingga bersih dan wangi.'],
    ['icon' => homeIcon('towels'), 'title' => 'Cuci Lipat', 'text' => 'Cuci bersih kemudian dilipat rapi siap pakai.'],
    ['icon' => homeIcon('shirt'), 'title' => 'Cuci Setrika Lipat', 'text' => 'Cuci bersih, setrika rapi dan dilipat kembali.'],
    ['icon' => homeIcon('iron'), 'title' => 'Setrika Saja', 'text' => 'Hanya setrika tanpa cucian, rapi dan wangi.'],
    ['icon' => homeIcon('dryer'), 'title' => 'Pengering & Lipat', 'text' => 'Pengeringan profesional lalu dilipat rapi dan bersih.'],
    ['icon' => homeIcon('shirt'), 'title' => 'Baju Bayi', 'text' => 'Perlakuan khusus untuk baju bayi, lembut dan aman.'],
];

$specialServices = [
    ['icon' => homeIcon('shirt'), 'title' => 'Satuan', 'text' => 'Cucian satuan sesuai jenis pakaian Anda, fleksibel dan praktis.'],
    ['icon' => homeIcon('zap'), 'title' => 'Express', 'text' => 'Layanan cepat untuk kebutuhan mendesak dengan waktu singkat.'],
    ['icon' => homeIcon('shield'), 'title' => 'Treatment', 'text' => 'Perawatan khusus untuk noda membandel dan bahan tertentu.'],
];

$howToSteps = [
    ['icon' => homeIcon('clipboard'), 'title' => 'Serahkan / Input Nota', 'text' => 'Serahkan cucian Anda dan dapatkan nomor nota dari kami.'],
    ['icon' => homeIcon('monitor'), 'title' => 'Cek Status Cucian', 'text' => 'Masukkan nomor nota di website untuk melihat progres cucian Anda.'],
    ['icon' => homeIcon('washer'), 'title' => 'Proses Laundry', 'text' => 'Cucian diproses sesuai tahap: cuci, kering, setrika dengan standar terbaik.'],
    ['icon' => homeIcon('bag'), 'title' => 'Ambil Cucian', 'text' => 'Cucian selesai sesuai estimasi, ambil dan nikmati hasilnya.'],
];

$contactItems = [
    ['icon' => $whatsappLogo, 'iconClass' => 'whatsapp-logo-icon', 'label' => 'WhatsApp', 'title' => $whatsappDisplay, 'text' => 'Fast response selama jam operasional'],
    ['icon' => homeIcon('clock'), 'label' => 'Jam Operasional', 'title' => str_replace(':', '.', $setting('open_time', '07:00')) . ' - ' . str_replace(':', '.', $setting('close_time', '21:00')) . ' WITA', 'text' => $weeklyOperationalText],
    ['icon' => homeIcon('store'), 'label' => 'Status Operasional Hari Ini', 'title' => (string) ($operationalStatus['status'] ?? 'Buka'), 'text' => (string) ($operationalStatus['text'] ?? $ishomaText), 'titleClass' => $operationalStatusClass],
    ['icon' => homeIcon('map'), 'label' => 'Alamat', 'title' => $setting('address', 'Jl. Poros Hartaco Indah, Kelurahan Sudiang Raya, Kecamatan Biringkanaya, Kota Makassar, Sulawesi Selatan 90242'), 'text' => ''],
];

$faqItems = [
    ['question' => 'Berapa lama proses cucian selesai?', 'answer' => 'Estimasi umum 1-2 hari kerja, menyesuaikan jenis layanan dan antrean cucian.'],
    ['question' => 'Apakah bisa hubungi admin lewat WhatsApp?', 'answer' => 'Bisa. Gunakan tombol WhatsApp untuk menghubungi admin selama jam operasional.'],
    ['question' => 'Bagaimana cara tracking cucian?', 'answer' => 'Masukkan nomor nota pada form cek status, lalu lihat tahapan cucian yang sedang berjalan.'],
    ['question' => 'Apakah layanan kiloan minimal 3 kg?', 'answer' => 'Ya, layanan kiloan dihitung dengan minimal berat cucian 3 kg.'],
];
?>
<div class="site-shell home-landing">
    <header class="site-header">
        <a class="brand-logo site-logo" href="<?= $safeBaseUrl ?>/" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="main-nav" aria-label="Navigasi utama" data-home-nav>
            <a class="active" href="#beranda">Beranda</a>
            <a href="#tracking">Tracking Cucian</a>
            <a href="#layanan">Layanan</a>
            <a href="#kontak">Kontak</a>
        </nav>

        <a class="admin-button" href="<?= $safeBaseUrl ?>/admin/login">
            <span aria-hidden="true"><?= homeIcon('lock') ?></span>
            Login Admin
        </a>
    </header>

    <main class="home-main">
        <section class="home-hero" id="beranda">
            <div class="home-hero-copy">
                <h1>Pantau Status Cucian<br>Anda dengan <span>Mudah</span></h1>
                <p>
                    Pelanggan dapat mengecek progres cucian secara online menggunakan nomor nota
                    untuk memastikan cucian Anda ditangani dengan baik.
                </p>
            </div>

            <div class="home-side" id="tracking">
                <form class="home-tracking-card" id="trackingForm" action="<?= $safeBaseUrl ?>/#hasil-tracking" method="get">
                    <h2>Cek Status Cucian</h2>
                    <label class="home-input-wrap" for="nomorNota">
                        <span aria-hidden="true"><?= homeIcon('receipt') ?></span>
                        <input id="nomorNota" name="nota" type="text" inputmode="text" placeholder="Masukkan No. Nota" aria-label="Nomor nota" value="<?= htmlspecialchars($trackingSearch, ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" required>
                    </label>
                    <button class="primary-button" type="submit">
                        <span aria-hidden="true"><?= homeIcon('search') ?></span>
                        Cek Status
                    </button>
                    <a class="whatsapp-button" href="<?= htmlspecialchars($whatsappHref, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                        <span class="whatsapp-button-icon" aria-hidden="true"><?= $whatsappLogo ?></span>
                        Hubungi Admin WhatsApp
                    </a>
                </form>
            </div>
        </section>

        <section class="tracking-example home-panel" id="hasil-tracking" aria-labelledby="trackingExampleTitle">
            <h2 id="trackingExampleTitle"><?= htmlspecialchars($trackingHeading, ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if ($trackingHasSearch && !$trackingFound): ?>
                <div class="admin-flash error">Nomor nota <?= htmlspecialchars($trackingSearch, ENT_QUOTES, 'UTF-8') ?> belum ditemukan.</div>
            <?php endif; ?>
            <?php if ($trackingFound || !$trackingHasSearch): ?>
            <div class="tracking-example-body">
                <dl class="tracking-detail-list">
                    <div>
                        <dt>No. Nota</dt>
                        <dd><?= htmlspecialchars($trackingDetail['nota'], ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Tanggal Masuk</dt>
                        <dd><?= htmlspecialchars($trackingDetail['tanggal_masuk'], ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Nama Pelanggan</dt>
                        <dd><?= htmlspecialchars($trackingDetail['nama_pelanggan'], ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Estimasi Selesai</dt>
                        <dd><?= htmlspecialchars($trackingDetail['estimasi_selesai'], ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Layanan</dt>
                        <dd><?= htmlspecialchars($trackingDetail['layanan'], ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Status Saat Ini</dt>
                        <dd><strong><?= htmlspecialchars($trackingDetail['status'], ENT_QUOTES, 'UTF-8') ?></strong></dd>
                    </div>
                </dl>

                <div class="tracking-progress" style="--tracking-progress-width: <?= htmlspecialchars(number_format($trackingProgressWidth, 4, '.', ''), ENT_QUOTES, 'UTF-8') ?>%;" aria-label="Progress cucian">
                    <?php foreach ($trackingSteps as $step): ?>
                        <article class="tracking-step <?= $step['state'] ? 'is-' . htmlspecialchars($step['state'], ENT_QUOTES, 'UTF-8') : '' ?>">
                            <span aria-hidden="true"><?= $step['icon'] ?></span>
                            <strong><?= htmlspecialchars($step['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($step['time'], ENT_QUOTES, 'UTF-8') ?></small>
                            <p><?= htmlspecialchars($step['note'], ENT_QUOTES, 'UTF-8') ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>

        <section class="home-services home-panel" id="layanan">
            <h2>Layanan Ghava Laundry</h2>
            <div class="service-panel-toolbar">
                <span class="service-badge">Kategori Kiloan (Minimal 3 kg)</span>
                <small>Perhitungan kiloan minimal 3 kg berat cucian.</small>
            </div>
            <div class="compact-service-grid">
                <?php foreach ($kiloanServices as $service): ?>
                    <article class="compact-service-card">
                        <span aria-hidden="true"><?= $service['icon'] ?></span>
                        <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($service['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="special-services">
                <span class="service-badge">Layanan Khusus</span>
                <div class="special-service-grid">
                    <?php foreach ($specialServices as $service): ?>
                        <article class="special-service-card">
                            <span aria-hidden="true"><?= $service['icon'] ?></span>
                            <div>
                                <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p><?= htmlspecialchars($service['text'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="home-process home-panel" aria-labelledby="processTitle">
            <h2 id="processTitle">Cara Menggunakan Ghava Laundry</h2>
            <div class="process-step-grid">
                <?php foreach ($howToSteps as $index => $step): ?>
                    <article class="process-step-card">
                        <span class="process-number"><?= $index + 1 ?></span>
                        <span class="process-icon" aria-hidden="true"><?= $step['icon'] ?></span>
                        <h3><?= htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($step['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="home-operational home-panel" id="kontak" aria-labelledby="operationalTitle">
            <h2 id="operationalTitle">Informasi Operasional</h2>
            <div class="home-info-strip">
                <?php foreach ($contactItems as $item): ?>
                    <article class="home-info-item">
                        <span class="home-info-icon <?= htmlspecialchars($item['iconClass'] ?? '', ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $item['icon'] ?></span>
                        <div>
                            <small><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></small>
                            <strong class="<?= htmlspecialchars($item['titleClass'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <p><?= htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="home-faq home-panel" aria-labelledby="faqTitle">
            <h2 id="faqTitle">Pertanyaan yang Sering Diajukan</h2>
            <div class="faq-grid">
                <?php foreach ($faqItems as $item): ?>
                    <details class="faq-item">
                        <summary><?= htmlspecialchars($item['question'], ENT_QUOTES, 'UTF-8') ?></summary>
                        <p><?= htmlspecialchars($item['answer'], ENT_QUOTES, 'UTF-8') ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="home-cta">
            <img class="home-cta-image home-cta-image-left" src="<?= $safeBaseUrl ?>/assets/img/ghava-home-hero.png" alt="">
            <div class="home-cta-copy">
                <h2>Cek status cucian Anda sekarang<br>atau hubungi admin untuk informasi lebih lanjut.</h2>
                <div class="home-cta-actions">
                    <a class="cta-light-button" href="#tracking">
                        Cek Status Cucian
                        <span aria-hidden="true">&rsaquo;</span>
                    </a>
                    <a class="cta-whatsapp-button" href="<?= htmlspecialchars($whatsappHref, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                        <span aria-hidden="true"><?= $whatsappLogo ?></span>
                        Hubungi Admin WhatsApp
                    </a>
                </div>
            </div>
            <img class="home-cta-image home-cta-image-right" src="<?= $safeBaseUrl ?>/assets/img/ghava-home-hero.png" alt="">
        </section>
    </main>

    <footer class="home-footer">
        <div class="home-footer-main">
            <section class="home-footer-brand" aria-label="Ghava Laundry">
                <a class="brand-logo footer-logo" href="<?= $safeBaseUrl ?>/">
                    <span class="footer-logo-icon" aria-hidden="true">
                        <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo-mark.svg" alt="">
                    </span>
                    <span class="footer-logo-text">Ghava<br>Laundry</span>
                </a>
                <p>Layanan laundry terpercaya dengan proses jelas, cepat, dan hasil terbaik untuk Anda.</p>
                <div class="footer-socials" aria-label="Media sosial">
                    <a href="<?= htmlspecialchars($whatsappHref, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?= $whatsappLogo ?></a>
                    <a href="#" aria-label="Instagram"><?= homeIcon('instagram') ?></a>
                    <a href="#" aria-label="Facebook"><?= homeIcon('facebook') ?></a>
                </div>
            </section>

            <section>
                <h2>Quick Links</h2>
                <a href="#beranda">Beranda</a>
                <a href="#tracking">Tracking Cucian</a>
                <a href="#layanan">Layanan</a>
                <a href="#kontak">Kontak</a>
            </section>

            <section>
                <h2>Layanan Kami</h2>
                <a href="#layanan">Kategori Kiloan</a>
                <a href="#layanan">Layanan Khusus</a>
                <a href="#processTitle">Cara Menggunakan</a>
                <a href="#kontak">Informasi Operasional</a>
            </section>

            <section class="footer-contact">
                <h2>Kontak</h2>
                <p><span aria-hidden="true"><?= homeIcon('phone') ?></span><?= htmlspecialchars($whatsappDisplay, ENT_QUOTES, 'UTF-8') ?></p>
                <p><span aria-hidden="true"><?= homeIcon('clock') ?></span><?= htmlspecialchars(str_replace(':', '.', $setting('open_time', '07:00')) . ' - ' . str_replace(':', '.', $setting('close_time', '21:00')) . ' WITA', ENT_QUOTES, 'UTF-8') ?></p>
                <p><span aria-hidden="true"><?= homeIcon('map') ?></span><?= htmlspecialchars($setting('address', 'Jl. Poros Hartaco Indah, Kel. Sudiang Raya, Kec. Biringkanaya, Kota Makassar, Sulawesi Selatan 90242'), ENT_QUOTES, 'UTF-8') ?></p>
            </section>
        </div>
        <small>&copy; 2026 Ghava Laundry. All rights reserved.</small>
    </footer>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
