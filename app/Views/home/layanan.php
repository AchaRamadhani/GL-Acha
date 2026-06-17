<?php ob_start(); ?>
<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');

$serviceIcons = [
    'washer' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><rect x="14" y="7" width="36" height="50" rx="4"></rect><line x1="14" y1="17" x2="50" y2="17"></line><circle cx="23" cy="12" r="1.8"></circle><circle cx="30" cy="12" r="1.8"></circle><circle cx="32" cy="38" r="13"></circle><path d="M22 39c4 4 9 4 13 0s7-4 9-1"></path></svg>',
    'fold' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><rect x="13" y="15" width="38" height="9" rx="4.5"></rect><rect x="13" y="28" width="38" height="9" rx="4.5"></rect><rect x="13" y="41" width="38" height="9" rx="4.5"></rect><line x1="22" y1="15" x2="47" y2="15"></line><line x1="22" y1="28" x2="47" y2="28"></line><line x1="22" y1="41" x2="47" y2="41"></line></svg>',
    'shirt' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M24 10l8 5 8-5 13 7-7 12-6-3v28H24V26l-6 3-7-12 13-7z"></path><path d="M28 12c1 5 7 5 8 0"></path></svg>',
    'iron' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M17 25h21c10 0 17 7 17 18v5H10v-8c0-8 3-15 7-15z"></path><path d="M17 25v-8h10"></path><path d="M28 25c1-5 5-7 10-7h7"></path><line x1="10" y1="42" x2="55" y2="42"></line><circle cx="35" cy="33" r="2"></circle></svg>',
    'dryer' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><rect x="14" y="7" width="36" height="50" rx="4"></rect><line x1="14" y1="17" x2="50" y2="17"></line><circle cx="23" cy="12" r="1.8"></circle><circle cx="30" cy="12" r="1.8"></circle><circle cx="32" cy="38" r="12"></circle><path d="M27 42l11-11"></path></svg>',
    'baby' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M24 10l8 5 8-5 13 7-7 12-6-3v28H24V26l-6 3-7-12 13-7z"></path><path d="M25 36c2 4 12 4 14 0"></path><circle cx="27" cy="31" r="1.5"></circle><circle cx="37" cy="31" r="1.5"></circle></svg>',
    'hanger' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M32 28v-5c6-2 7-11 0-12-5-1-9 3-8 8"></path><path d="M32 28L9 44v7h46v-7L32 28z"></path></svg>',
    'timer' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="34" cy="36" r="18"></circle><path d="M34 18v-7"></path><path d="M28 11h12"></path><path d="M47 21l5-5"></path><path d="M34 36l8-9"></path><path d="M7 31h10"></path><path d="M4 39h12"></path><path d="M10 47h9"></path></svg>',
    'shield' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M32 8l21 8v15c0 13-8 22-21 27-13-5-21-14-21-27V16l21-8z"></path><line x1="32" y1="22" x2="32" y2="44"></line><line x1="21" y1="33" x2="43" y2="33"></line></svg>',
    'bag' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M18 24h28l4 29H14l4-29z"></path><path d="M24 24v-5a8 8 0 0 1 16 0v5"></path></svg>',
    'info' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="32" cy="32" r="23"></circle><line x1="32" y1="29" x2="32" y2="44"></line><circle cx="32" cy="21" r="1.8"></circle></svg>',
    'star' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M32 8l7 15 17 2-12 12 3 17-15-8-15 8 3-17L8 25l17-2 7-15z"></path></svg>',
    'whatsapp' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="32" cy="32" r="24"></circle><path d="M21 47l2-8a17 17 0 1 1 7 5l-9 3z"></path><path d="M25 25c1 8 7 14 15 16l4-5-5-3-3 2c-3-2-5-4-7-7l2-3-3-5-3 5z"></path></svg>',
    'clock' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="32" cy="32" r="23"></circle><path d="M32 17v16l11 7"></path></svg>',
    'pin' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M32 58s19-17 19-33a19 19 0 0 0-38 0c0 16 19 33 19 33z"></path><circle cx="32" cy="25" r="7"></circle></svg>',
    'shop' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M12 27h40l-4-15H16l-4 15z"></path><path d="M16 27v24h32V27"></path><path d="M23 51V37h18v14"></path><line x1="10" y1="27" x2="54" y2="27"></line></svg>',
];

$kiloanServices = [
    ['icon' => 'washer', 'title' => 'Cuci Kering', 'description' => 'Pencucian dengan mesin menggunakan deterjen berkualitas.'],
    ['icon' => 'fold', 'title' => 'Cuci Lipat', 'description' => 'Cucian bersih wangi, kemudian dilipat rapi siap pakai.'],
    ['icon' => 'shirt', 'title' => 'Cuci Setrika Lipat', 'description' => 'Cuci bersih, setrika rapi, dilipat dan siap disimpan.'],
    ['icon' => 'iron', 'title' => 'Setrika Saja', 'description' => 'Setrika rapi untuk pakaian yang sudah dalam kondisi bersih.'],
    ['icon' => 'dryer', 'title' => 'Pengering & Lipat', 'description' => 'Pengeringan dengan mesin lalu dilipat rapi siap pakai.'],
    ['icon' => 'baby', 'title' => 'Baju Bayi', 'description' => 'Perawatan khusus untuk pakaian bayi, lebih higienis dan lembut.'],
];

$specialServices = [
    ['icon' => 'hanger', 'title' => 'Satuan', 'description' => 'Layanan pencucian berdasarkan jumlah item, dihitung per potong atau per pcs.'],
    ['icon' => 'timer', 'title' => 'Express', 'description' => 'Layanan cepat untuk kebutuhan mendesak dengan waktu pengerjaan lebih singkat.'],
    ['icon' => 'shield', 'title' => 'Treatment', 'description' => 'Perawatan khusus untuk noda membandel, pakaian premium, atau bahan tertentu.'],
];
?>
<div class="site-shell home-landing services-page">
    <header class="site-header">
        <a class="brand-logo site-logo" href="<?= $safeBaseUrl ?>/" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="main-nav" aria-label="Navigasi utama">
            <a href="<?= $safeBaseUrl ?>/">Beranda</a>
            <a href="<?= $safeBaseUrl ?>/tracking-cucian">Tracking Cucian</a>
            <a class="active" href="<?= $safeBaseUrl ?>/layanan">Layanan</a>
            <a href="<?= $safeBaseUrl ?>/kontak">Kontak</a>
        </nav>

        <a class="admin-button" href="<?= $safeBaseUrl ?>/admin/login">
            <span aria-hidden="true">&#128274;</span>
            Login Admin
        </a>
    </header>

    <main class="services-page-main">
        <section class="services-hero" aria-labelledby="servicesPageTitle">
            <div class="services-hero-copy">
                <h1 id="servicesPageTitle">Layanan Lengkap<br>Untuk <span>Kebutuhan Cucian</span> Anda</h1>
                <p>Kami menyediakan berbagai jenis layanan untuk memenuhi kebutuhan cucian Anda dengan hasil bersih, wangi, dan rapi.</p>
            </div>

            <figure class="services-hero-visual">
                <img src="<?= $safeBaseUrl ?>/assets/img/ghava-home-hero.png" alt="Tumpukan handuk, keranjang laundry, dan botol deterjen biru">
            </figure>
        </section>

        <section class="services-category kiloan-category" aria-labelledby="kiloanTitle">
            <header class="services-category-header">
                <h2 id="kiloanTitle">
                    <span class="services-title-icon"><?= $serviceIcons['bag'] ?></span>
                    <span class="services-heading-text">1. Kategori Kiloan (Minimal 3 kg)</span>
                </h2>
                <p>
                    <span class="services-title-icon"><?= $serviceIcons['info'] ?></span>
                    Perhitungan kiloan minimal 3 kg berat cucian.
                </p>
            </header>

            <div class="services-category-body">
                <p class="services-category-note">Semua layanan pada kategori ini dihitung dengan berat cucian minimal 3 kg.</p>
                <div class="services-card-grid">
                    <?php foreach ($kiloanServices as $service): ?>
                        <article class="services-card">
                            <span class="services-card-icon"><?= $serviceIcons[$service['icon']] ?></span>
                            <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <small><span aria-hidden="true">&#10003;</span> Minimal 3 kg</small>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="services-category special-category" aria-labelledby="specialTitle">
            <header class="special-category-header">
                <h2 id="specialTitle">
                    <span class="services-title-icon"><?= $serviceIcons['star'] ?></span>
                    <span class="services-heading-text">2. Layanan Khusus (Non-Kiloan)</span>
                </h2>
                <p>Layanan ini tidak dihitung berdasarkan kiloan dan memiliki ketentuan tersendiri.</p>
            </header>

            <div class="special-card-grid">
                <?php foreach ($specialServices as $service): ?>
                    <article class="special-service-card">
                        <span class="services-card-icon"><?= $serviceIcons[$service['icon']] ?></span>
                        <div>
                            <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="services-contact-strip" id="kontak" aria-label="Informasi kontak dan operasional">
            <article class="services-contact-item">
                <span class="services-contact-icon green"><?= $serviceIcons['whatsapp'] ?></span>
                <div>
                    <small>Hubungi Kami via WhatsApp</small>
                    <strong>081242910340</strong>
                    <p>Fast response selama jam operasional</p>
                </div>
            </article>

            <article class="services-contact-item">
                <span class="services-contact-icon blue"><?= $serviceIcons['clock'] ?></span>
                <div>
                    <small>Jam Operasional</small>
                    <strong>07.00 - 21.00 WITA</strong>
                    <p>Setiap Hari (Termasuk Hari Libur)</p>
                </div>
            </article>

            <article class="services-contact-item services-contact-address">
                <span class="services-contact-icon blue"><?= $serviceIcons['pin'] ?></span>
                <div>
                    <small>Alamat Kami</small>
                    <p>Jl. Poros Hartaco Indah, Kelurahan Sudiang Raya, Kecamatan Biringkanaya, Kota Makassar, Sulawesi Selatan 90242</p>
                </div>
            </article>

            <article class="services-contact-item services-contact-status">
                <small>Status Operasional Hari Ini</small>
                <strong>
                    <span class="services-contact-icon shop"><?= $serviceIcons['shop'] ?></span>
                    Buka
                </strong>
                <p>Status lain: <span>Ishoma</span>, <span>Tutup</span>, <span>Libur</span></p>
            </article>
        </section>
    </main>

    <footer class="home-footer">
        <small>&copy; 2024 Ghava Laundry. All rights reserved.</small>
    </footer>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
