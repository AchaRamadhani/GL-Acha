<?php ob_start(); ?>
<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$whatsappDisplay = '0812-4291-0340';
$whatsappUrl = 'https://wa.me/6281242910340';
$whatsappLogo = '<img src="' . $safeBaseUrl . '/assets/img/whatsapp-logo.svg?v=6" alt="">';

$contactIcons = [
    'whatsapp' => $whatsappLogo,
    'user' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="32" cy="24" r="11"></circle><path d="M14 55c3-12 11-18 18-18s15 6 18 18"></path></svg>',
    'chat' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M13 18h38v27H26l-12 8V18z"></path><line x1="22" y1="29" x2="43" y2="29"></line><line x1="22" y1="37" x2="36" y2="37"></line></svg>',
    'clock' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="32" cy="32" r="23"></circle><path d="M32 17v16l11 7"></path></svg>',
    'pin' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M32 58s19-17 19-33a19 19 0 0 0-38 0c0 16 19 33 19 33z"></path><circle cx="32" cy="25" r="7"></circle></svg>',
    'question' => '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle cx="32" cy="32" r="22"></circle><path d="M25 25a8 8 0 0 1 15 4c0 6-8 7-8 13"></path><circle cx="32" cy="49" r="1.8"></circle></svg>',
];

$adminItems = [
    ['icon' => 'user', 'title' => 'Admin Ghava Laundry', 'text' => 'Customer Service'],
    ['icon' => 'whatsapp', 'title' => $whatsappDisplay, 'text' => 'WhatsApp', 'tone' => 'green'],
    ['icon' => 'chat', 'title' => 'Fast response', 'text' => 'selama jam operasional'],
    ['icon' => 'clock', 'title' => '07.00 - 21.00 WITA', 'text' => 'Setiap Hari (Termasuk Hari Libur)'],
];

$faqItems = [
    ['question' => 'Berapa lama proses cucian selesai?', 'answer' => 'Estimasi standar 1-2 hari kerja, sedangkan layanan express mengikuti antrean dan jenis cucian.'],
    ['question' => 'Kapan saya bisa mengambil cucian?', 'answer' => 'Cucian bisa diambil setelah status selesai atau setelah admin mengirim konfirmasi melalui WhatsApp.'],
    ['question' => 'Bagaimana jika ada cucian yang rusak atau hilang?', 'answer' => 'Segera hubungi admin melalui WhatsApp dengan nomor nota agar kami dapat membantu pengecekan.'],
];
?>
<div class="site-shell home-landing contact-page">
    <header class="site-header">
        <a class="brand-logo site-logo" href="<?= $safeBaseUrl ?>/" aria-label="Ghava Laundry">
            <img src="<?= $safeBaseUrl ?>/assets/img/ghava-logo.svg" alt="Ghava Laundry">
        </a>

        <nav class="main-nav" aria-label="Navigasi utama">
            <a href="<?= $safeBaseUrl ?>/">Beranda</a>
            <a href="<?= $safeBaseUrl ?>/tracking-cucian">Tracking Cucian</a>
            <a href="<?= $safeBaseUrl ?>/layanan">Layanan</a>
            <a class="active" href="<?= $safeBaseUrl ?>/kontak">Kontak</a>
        </nav>

        <a class="admin-button" href="<?= $safeBaseUrl ?>/admin/login">
            <span aria-hidden="true">&#128274;</span>
            Login Admin
        </a>
    </header>

    <main class="contact-main">
        <section class="contact-hero" aria-labelledby="contactPageTitle">
            <div class="contact-intro">
                <h1 id="contactPageTitle">Hubungi <span>Admin</span></h1>
                <p>Pelanggan dapat menghubungi admin melalui WhatsApp untuk bantuan tracking cucian, pertanyaan pickup, atau informasi layanan lainnya.</p>

                <article class="contact-admin-card" aria-labelledby="adminInfoTitle">
                    <h2 id="adminInfoTitle">Informasi Admin</h2>
                    <div class="contact-admin-body">
                        <figure class="contact-admin-avatar" aria-label="Admin Ghava Laundry">
                            <span class="avatar-headset" aria-hidden="true"></span>
                            <figcaption>Ghava Laundry</figcaption>
                        </figure>

                        <div class="contact-admin-list">
                            <?php foreach ($adminItems as $item): ?>
                                <article class="contact-admin-item">
                                    <span class="contact-admin-icon <?= htmlspecialchars($item['tone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= $contactIcons[$item['icon']] ?></span>
                                    <div>
                                        <strong><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <p><?= htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>
            </div>

            <div class="contact-action-stack">
                <article class="contact-whatsapp-card" aria-labelledby="whatsappTitle">
                    <h2 id="whatsappTitle">Hubungi via WhatsApp</h2>
                    <p>Kirim pesan langsung ke admin melalui WhatsApp.</p>
                    <div class="contact-message-row">
                        <span class="contact-whatsapp-logo"><?= $contactIcons['whatsapp'] ?></span>
                        <blockquote>Halo Admin, saya ingin menanyakan status cucian saya dengan no nota GHV-240518-0012.</blockquote>
                    </div>
                    <a class="contact-whatsapp-button" href="<?= $whatsappUrl ?>" target="_blank" rel="noopener">
                        <span><?= $contactIcons['whatsapp'] ?></span>
                        Buka WhatsApp
                    </a>
                </article>

                <section class="contact-faq-card" aria-labelledby="faqTitle">
                    <h2 id="faqTitle">FAQ - Pertanyaan yang Sering Diajukan</h2>
                    <div class="contact-faq-list">
                        <?php foreach ($faqItems as $item): ?>
                            <details class="contact-faq-item">
                                <summary>
                                    <span class="contact-question-icon"><?= $contactIcons['question'] ?></span>
                                    <strong><?= htmlspecialchars($item['question'], ENT_QUOTES, 'UTF-8') ?></strong>
                                </summary>
                                <p><?= htmlspecialchars($item['answer'], ENT_QUOTES, 'UTF-8') ?></p>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </section>

        <section class="contact-info-strip" aria-label="Informasi kontak dan operasional">
            <a class="contact-info-item" href="<?= $whatsappUrl ?>" target="_blank" rel="noopener">
                <span class="contact-info-icon green"><?= $contactIcons['whatsapp'] ?></span>
                <div>
                    <small>Hubungi Kami via WhatsApp</small>
                    <strong><?= htmlspecialchars($whatsappDisplay, ENT_QUOTES, 'UTF-8') ?></strong>
                    <p>Fast response selama jam operasional</p>
                </div>
            </a>

            <article class="contact-info-item">
                <span class="contact-info-icon blue"><?= $contactIcons['clock'] ?></span>
                <div>
                    <small>Jam Operasional</small>
                    <strong>07.00 - 21.00 WITA</strong>
                    <p>Setiap Hari (Termasuk Hari Libur)</p>
                </div>
            </article>

            <article class="contact-info-item contact-info-address">
                <span class="contact-info-icon blue"><?= $contactIcons['pin'] ?></span>
                <div>
                    <small>Alamat Kami</small>
                    <strong>Jl. Poros Hartaco Indah</strong>
                    <p>Kelurahan Sudiang Raya, Biringkanaya, Kota Makassar, Sulawesi Selatan 90242</p>
                </div>
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
