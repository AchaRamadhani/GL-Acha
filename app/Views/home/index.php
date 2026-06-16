<?php ob_start(); ?>
<div class="site-shell">
    <header class="site-header">
        <a class="brand-logo site-logo" href="#beranda" aria-label="Ghava Laundry">
            <img src="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="main-nav" aria-label="Navigasi utama">
            <a class="active" href="#beranda">Beranda</a>
            <a href="#tracking">Tracking Cucian</a>
            <a href="#layanan">Layanan</a>
            <a href="#kontak">Kontak</a>
        </nav>

        <a class="admin-button" href="<?= htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8') ?>/admin/login">
            <span aria-hidden="true">&#9817;</span>
            Login Admin
        </a>
    </header>

    <section class="hero" id="beranda">
        <div class="hero-content">
            <p class="eyebrow">Laundry tracking online</p>
            <h1>Pantau Status Cucian Anda</h1>
            <p class="hero-copy">
                Cek perkembangan cucian Anda secara online kapan saja dan di mana saja hanya
                <strong>dengan nomor nota.</strong>
            </p>

            <form class="tracking-card" id="trackingForm" action="#tracking">
                <label for="nomorNota">Masukkan No. Nota Anda</label>
                <div class="input-wrap">
                    <input id="nomorNota" name="nota" type="text" inputmode="numeric" placeholder="Contoh: 12345" aria-label="Nomor nota">
                    <span aria-hidden="true">&#128196;</span>
                </div>
                <button class="primary-button" type="submit">
                    <span aria-hidden="true">&#128269;</span>
                    Cek Status Cucian
                </button>
                <a class="whatsapp-button" href="https://wa.me/6281234567890">
                    <span aria-hidden="true">WA</span>
                    Hubungi Admin via WhatsApp
                </a>
            </form>

            <p class="login-note">
                <span aria-hidden="true">&#9432;</span>
                Tidak perlu login untuk cek status cucian. Fitur ini khusus untuk pelanggan.
            </p>
        </div>

        <div class="hero-visual" aria-label="Ilustrasi tracking cucian Ghava Laundry">
            <div class="desktop-mockup">
                <div class="screen-top">
                    <strong>Ghava Laundry</strong>
                    <span></span>
                </div>
                <div class="status-panel">
                    <p>Status Cucian</p>
                    <small>No. Nota: 12345</small>
                    <div class="status-message">
                        <span class="gear" aria-hidden="true">&#9881;</span>
                        <div>
                            <strong>Diproses</strong>
                            <small>Cucian Anda sedang diproses</small>
                        </div>
                    </div>
                    <div class="progress-line">
                        <span class="filled"></span>
                        <i></i><i></i><i></i><i></i><i></i><i></i>
                    </div>
                    <div class="laundry-detail">
                        <div>
                            <span>Layanan</span><strong>Cuci Setrika</strong>
                            <span>Berat</span><strong>5 Kg</strong>
                            <span>Estimasi</span><strong>22 Mei 2024</strong>
                        </div>
                        <div class="folded-stack" aria-hidden="true">
                            <span></span><span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phone-mockup">
                <div class="phone-speaker"></div>
                <strong>Ghava Laundry</strong>
                <small>No. Nota: 12345</small>
                <div class="phone-status">
                    <span aria-hidden="true">&#9881;</span>
                    <p><b>Diproses</b><small>Cucian Anda sedang diproses</small></p>
                </div>
                <ul class="phone-steps">
                    <li>Antrean</li>
                    <li class="current">Diproses</li>
                    <li>Dicuci</li>
                    <li>Dikeringkan</li>
                    <li>Disetrika</li>
                    <li>Selesai</li>
                    <li>Diambil</li>
                </ul>
            </div>

            <div class="laundry-basket" aria-hidden="true">
                <div class="clothes">
                    <span></span><span></span><span></span>
                </div>
                <div class="basket-grid">
                    <span></span><span></span><span></span><span></span>
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>

            <div class="plant" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
        </div>
    </section>

    <main>
        <section class="process-section" id="tracking">
            <div class="section-heading">
                <h2>Status Proses Laundry</h2>
            </div>
            <div class="process-track">
                <?php
                $processes = [
                    ['icon' => '&#128101;', 'label' => 'Antrean'],
                    ['icon' => '&#9881;', 'label' => 'Diproses'],
                    ['icon' => '&#128703;', 'label' => 'Dicuci'],
                    ['icon' => '&#9832;', 'label' => 'Dikeringkan'],
                    ['icon' => '&#128085;', 'label' => 'Disetrika'],
                    ['icon' => '&#10003;', 'label' => 'Selesai'],
                    ['icon' => '&#128722;', 'label' => 'Diambil'],
                ];
                foreach ($processes as $index => $process): ?>
                    <div class="process-item">
                        <div class="process-icon" aria-hidden="true"><?= $process['icon'] ?></div>
                        <strong><?= htmlspecialchars($process['label'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <span><?= $index + 1 ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="steps-section">
            <div class="section-heading">
                <h2>Cara Cek Status Cucian</h2>
            </div>
            <div class="steps-grid">
                <?php
                $steps = [
                    ['icon' => '&#128196;', 'title' => 'Masukkan No. Nota', 'text' => 'Input nomor nota Anda pada kolom yang tersedia.'],
                    ['icon' => '&#128269;', 'title' => 'Klik Cek Status', 'text' => 'Tekan tombol cek status cucian untuk melihat perkembangan.'],
                    ['icon' => '&#128200;', 'title' => 'Lihat Perkembangan Cucian', 'text' => 'Status cucian Anda akan tampil sesuai proses yang sedang berjalan.'],
                    ['icon' => 'WA', 'title' => 'Hubungi Admin', 'text' => 'Jika ada pertanyaan, hubungi admin kami melalui WhatsApp.'],
                ];
                foreach ($steps as $index => $step): ?>
                    <article class="step-card">
                        <span class="step-number"><?= $index + 1 ?></span>
                        <div class="step-icon" aria-hidden="true"><?= $step['icon'] ?></div>
                        <h3><?= htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($step['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="services-section" id="layanan">
            <div class="section-heading">
                <h2>Layanan Ghava Laundry</h2>
            </div>
            <div class="services-grid">
                <?php
                $services = [
                    ['icon' => '&#128703;', 'title' => 'Cuci Kering', 'text' => 'Cucian dibersihkan tanpa setrika, wangi dan rapi, siap pakai.'],
                    ['icon' => '&#128230;', 'title' => 'Cuci Lipat', 'text' => 'Cucian dicuci bersih, dilipat rapi, dan siap disimpan.'],
                    ['icon' => '&#128085;', 'title' => 'Cuci Setrika Lipat', 'text' => 'Cucian dicuci bersih, disetrika rapi, lalu dilipat dengan rapi.'],
                    ['icon' => '&#128451;', 'title' => 'Setrika Saja', 'text' => 'Layanan setrika untuk pakaian Anda yang sudah dicuci sendiri.'],
                    ['icon' => '&#9832;', 'title' => 'Pengering & Lipat', 'text' => 'Cucian dikeringkan menggunakan mesin pengering, lalu dilipat rapi.'],
                    ['icon' => '&#128118;', 'title' => 'Baju Bayi', 'subtitle' => '(Khusus Kiloan)', 'text' => 'Perawatan khusus untuk pakaian bayi, ekstra bersih dan lembut.'],
                    ['icon' => '&#128090;', 'title' => 'Satuan', 'text' => 'Layanan cuci per item seperti bed cover, selimut, gorden, dan pakaian khusus lainnya.'],
                    ['icon' => '&#9201;', 'title' => 'Express', 'text' => 'Layanan cepat selesai dalam waktu singkat, cocok untuk kebutuhan mendesak Anda.'],
                    ['icon' => '&#128737;', 'title' => 'Treatment', 'text' => 'Perawatan khusus untuk noda membandel agar pakaian kembali bersih dan segar.'],
                ];
                foreach ($services as $service): ?>
                    <article class="service-card">
                        <div class="service-art" aria-hidden="true">
                            <span><?= $service['icon'] ?></span>
                        </div>
                        <div class="service-copy">
                            <h3>
                                <?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?>
                                <?php if (!empty($service['subtitle'])): ?>
                                    <small><?= htmlspecialchars($service['subtitle'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>
                            </h3>
                            <p><?= htmlspecialchars($service['text'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="benefits-section">
            <div class="section-heading">
                <h2>Mengapa Memilih Ghava Laundry?</h2>
            </div>
            <div class="benefits-grid">
                <?php
                $benefits = [
                    ['icon' => '&#128269;', 'title' => 'Tracking Mudah', 'text' => 'Cek status cucian kapan saja cukup dengan nomor nota.'],
                    ['icon' => '&#9776;', 'title' => 'Status Jelas', 'text' => 'Informasi proses cucian akurat dan transparan.'],
                    ['icon' => '&#128337;', 'title' => 'Hemat Waktu', 'text' => 'Pantau tanpa harus datang, lebih praktis dan efisien.'],
                    ['icon' => '&#128203;', 'title' => 'Riwayat Proses', 'text' => 'Lihat riwayat dan detail setiap proses cucian Anda.'],
                    ['icon' => 'WA', 'title' => 'Komunikasi Cepat', 'text' => 'Tanya jawab mudah via WhatsApp.'],
                ];
                foreach ($benefits as $benefit): ?>
                    <article class="benefit-card">
                        <div class="benefit-icon" aria-hidden="true"><?= $benefit['icon'] ?></div>
                        <h3><?= htmlspecialchars($benefit['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($benefit['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="contact-banner" id="kontak">
            <div class="folded-towels" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </div>
            <div class="contact-content">
                <h2>Hubungi Ghava Laundry</h2>
                <p>Ada pertanyaan tentang cucian Anda? Kami siap membantu Anda.</p>
                <a class="contact-button" href="https://wa.me/6281234567890">
                    <span aria-hidden="true">WA</span>
                    Hubungi Admin via WhatsApp
                </a>
            </div>
            <div class="washer-scene" aria-hidden="true">
                <div class="washer"><span></span></div>
                <div class="small-basket"></div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div>
            <span aria-hidden="true">&#9906;</span>
            <p><strong>Alamat</strong>Jl. Kebersihan No. 123, Kel. Sejahtera<br>Kec. Bersih, Kota Nyaman 12345</p>
        </div>
        <div>
            <span aria-hidden="true">&#9719;</span>
            <p><strong>Jam Operasional</strong>Senin - Sabtu: 08.00 - 20.00<br>Minggu & Hari Libur: 09.00 - 17.00</p>
        </div>
        <div>
            <span aria-hidden="true">&#9742;</span>
            <p><strong>Hubungi Kami</strong>0812-3456-7890<br>WhatsApp: 0812-3456-7890</p>
        </div>
        <small>&copy; 2024 Ghava Laundry. Semua hak dilindungi.</small>
    </footer>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
