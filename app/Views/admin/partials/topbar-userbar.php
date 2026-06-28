<?php
$topbarSafeBaseUrl = $safeBaseUrl ?? htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');
$topbarAdminName = htmlspecialchars($adminName ?? 'Admin Laundry', ENT_QUOTES, 'UTF-8');
$topbarAdminRole = htmlspecialchars($adminRole ?? 'Administrator', ENT_QUOTES, 'UTF-8');
$topbarWhatsappUrl = 'https://web.whatsapp.com/';
?>
<div class="dashboard-userbar" data-dashboard-userbar>
    <button class="dashboard-icon-button badge-button dashboard-popover-trigger" type="button" aria-label="Buka notifikasi" aria-haspopup="dialog" aria-expanded="false" aria-controls="dashboardNotificationsPanel" data-dashboard-popover-trigger="notifications">
        <span class="dashboard-action-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                <path d="M13.7 21a2 2 0 0 1-3.4 0"></path>
            </svg>
        </span>
        <i>3</i>
    </button>
    <button class="dashboard-icon-button badge-button dashboard-popover-trigger" type="button" aria-label="Buka pesan WhatsApp" aria-haspopup="dialog" aria-expanded="false" aria-controls="dashboardMessagesPanel" data-dashboard-popover-trigger="messages">
        <span class="dashboard-action-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="M21 12a8.5 8.5 0 0 1-9.9 8.4 8 8 0 0 1-3.5-1.5L3 20l1.2-4.1A8.3 8.3 0 1 1 21 12Z"></path>
            </svg>
        </span>
        <i>2</i>
    </button>
    <div class="dashboard-user">
        <span class="dashboard-avatar" aria-hidden="true"></span>
        <p><strong><?= $topbarAdminName ?></strong><small><?= $topbarAdminRole ?></small></p>
        <span aria-hidden="true">&#8964;</span>
    </div>

    <section class="dashboard-popover dashboard-notification-popover" id="dashboardNotificationsPanel" role="dialog" aria-labelledby="dashboardNotificationsTitle" data-dashboard-popover="notifications" hidden>
        <div class="dashboard-popover-header">
            <h2 id="dashboardNotificationsTitle">Notifikasi</h2>
            <button class="dashboard-popover-link" type="button" data-dashboard-mark-read>Tandai semua sebagai dibaca</button>
        </div>
        <div class="dashboard-popover-list">
            <article class="dashboard-notification-item is-unread">
                <span class="dashboard-popover-icon success" aria-hidden="true">&#10003;</span>
                <div>
                    <h3>Status cucian selesai</h3>
                    <p>Cucian dengan nomor nota GL-220526-003 atas nama Rifky Putra telah berubah menjadi status Selesai.</p>
                </div>
                <time>Baru saja</time>
                <span class="dashboard-popover-dot" aria-hidden="true"></span>
            </article>
            <article class="dashboard-notification-item is-unread">
                <span class="dashboard-popover-icon warning" aria-hidden="true">!</span>
                <div>
                    <h3>Cucian melewati estimasi selesai</h3>
                    <p>Cucian dengan nomor nota GL-210526-002 perlu diperiksa karena telah melewati estimasi waktu selesai.</p>
                </div>
                <time>10 menit lalu</time>
                <span class="dashboard-popover-dot" aria-hidden="true"></span>
            </article>
            <article class="dashboard-notification-item is-unread">
                <span class="dashboard-popover-icon info" aria-hidden="true">&#128196;</span>
                <div>
                    <h3>Transaksi baru ditambahkan</h3>
                    <p>Data cucian baru atas nama Alesha Nur berhasil ditambahkan ke dalam sistem.</p>
                </div>
                <time>30 menit lalu</time>
                <span class="dashboard-popover-dot" aria-hidden="true"></span>
            </article>
        </div>
        <div class="dashboard-popover-footer">
            <span class="dashboard-popover-mini-icon" aria-hidden="true">i</span>
            <p>Notifikasi membantu admin mengetahui aktivitas penting pada sistem tanpa harus membuka setiap halaman satu per satu.</p>
        </div>
    </section>

    <section class="dashboard-popover dashboard-message-popover" id="dashboardMessagesPanel" role="dialog" aria-labelledby="dashboardMessagesTitle" data-dashboard-popover="messages" hidden>
        <div class="dashboard-popover-header">
            <h2 id="dashboardMessagesTitle">Pesan WhatsApp</h2>
        </div>
        <div class="dashboard-message-intro">
            <span class="dashboard-popover-mini-icon" aria-hidden="true">i</span>
            <p>Ini bukan chat internal. Panel ini berfungsi sebagai pengingat pesan WhatsApp dari pelanggan agar admin dapat segera membuka WhatsApp untuk membalas.</p>
        </div>
        <div class="dashboard-popover-list">
            <article class="dashboard-message-item">
                <span class="dashboard-whatsapp-mark" aria-hidden="true">
                    <img src="<?= $topbarSafeBaseUrl ?>/assets/img/logo-wa.jpg?v=1" alt="">
                </span>
                <div>
                    <h3>Pesan dari Rifky Putra</h3>
                    <p>Pelanggan menanyakan status cucian dengan nomor nota GL-220526-003 melalui WhatsApp.</p>
                </div>
                <div class="dashboard-message-actions">
                    <time>10 menit lalu</time>
                    <a class="dashboard-whatsapp-button" href="<?= $topbarWhatsappUrl ?>" target="_blank" rel="noopener">
                        <span aria-hidden="true">&#9990;</span>
                        Buka WhatsApp
                    </a>
                </div>
            </article>
            <article class="dashboard-message-item">
                <span class="dashboard-whatsapp-mark" aria-hidden="true">
                    <img src="<?= $topbarSafeBaseUrl ?>/assets/img/logo-wa.jpg?v=1" alt="">
                </span>
                <div>
                    <h3>Pesan dari Risca Yus</h3>
                    <p>Pelanggan menanyakan apakah cucian sudah dapat diambil.</p>
                </div>
                <div class="dashboard-message-actions">
                    <time>30 menit lalu</time>
                    <a class="dashboard-whatsapp-button" href="<?= $topbarWhatsappUrl ?>" target="_blank" rel="noopener">
                        <span aria-hidden="true">&#9990;</span>
                        Buka WhatsApp
                    </a>
                </div>
            </article>
        </div>
        <a class="dashboard-popover-action-row" href="<?= $topbarWhatsappUrl ?>" target="_blank" rel="noopener">
            <span class="dashboard-popover-icon monitor" aria-hidden="true">&#128187;</span>
            <strong>Buka WhatsApp Web</strong>
            <span aria-hidden="true">&#8250;</span>
        </a>
        <div class="dashboard-popover-footer">
            <span class="dashboard-popover-mini-icon muted" aria-hidden="true">i</span>
            <p>Fitur pesan berfungsi sebagai pengingat bahwa terdapat pelanggan yang menghubungi admin melalui WhatsApp, sehingga admin dapat segera membuka WhatsApp untuk membalas pesan tersebut.</p>
        </div>
    </section>
</div>
