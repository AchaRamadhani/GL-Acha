<?php
$topbarBaseUrl = rtrim((string) ($baseUrl ?? ''), '/');
$topbarSafeBaseUrl = $safeBaseUrl ?? htmlspecialchars($topbarBaseUrl, ENT_QUOTES, 'UTF-8');
$topbarAdminName = htmlspecialchars($adminName ?? 'Admin Laundry', ENT_QUOTES, 'UTF-8');
$topbarAdminRole = htmlspecialchars($adminRole ?? 'Administrator', ENT_QUOTES, 'UTF-8');
$topbarSettings = is_array($settings ?? null) ? $settings : [];
$topbarShowBrowserNotifications = ($topbarSettings['browser_notification'] ?? '1') === '1';
$topbarShowMessageNotifications = ($topbarSettings['message_notification'] ?? '1') === '1';
$topbarLogoutConfirm = ($topbarSettings['logout_confirmation'] ?? '1') === '1';
$topbarWhatsappUrl = 'https://web.whatsapp.com/';
$topbarReadUrl = htmlspecialchars($topbarBaseUrl . '/admin/topbar/read', ENT_QUOTES, 'UTF-8');
$topbarCsrfToken = htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8');
$topbarNotifications = is_array($topbarNotifications ?? null) ? $topbarNotifications : ['items' => [], 'unread_count' => 0];
$topbarMessages = is_array($topbarMessages ?? null) ? $topbarMessages : ['items' => [], 'unread_count' => 0];
$topbarNotificationItems = is_array($topbarNotifications['items'] ?? null) ? $topbarNotifications['items'] : [];
$topbarMessageItems = is_array($topbarMessages['items'] ?? null) ? $topbarMessages['items'] : [];
$topbarNotificationUnreadCount = max(0, (int) ($topbarNotifications['unread_count'] ?? 0));
$topbarMessageUnreadCount = max(0, (int) ($topbarMessages['unread_count'] ?? 0));
$topbarBadgeText = static fn (int $count): string => $count > 99 ? '99+' : (string) $count;
?>
<div class="dashboard-userbar" data-dashboard-userbar data-logout-confirm-default="<?= $topbarLogoutConfirm ? 'on' : 'off' ?>" data-dashboard-read-url="<?= $topbarReadUrl ?>" data-dashboard-csrf="<?= $topbarCsrfToken ?>">
    <?php if ($topbarShowBrowserNotifications): ?>
        <button class="dashboard-icon-button badge-button dashboard-popover-trigger" type="button" aria-label="Buka notifikasi" aria-haspopup="dialog" aria-expanded="false" aria-controls="dashboardNotificationsPanel" data-dashboard-popover-trigger="notifications">
            <span class="dashboard-action-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M13.7 21a2 2 0 0 1-3.4 0"></path>
                </svg>
            </span>
            <i data-dashboard-badge <?= $topbarNotificationUnreadCount === 0 ? 'hidden' : '' ?>><?= htmlspecialchars($topbarBadgeText($topbarNotificationUnreadCount), ENT_QUOTES, 'UTF-8') ?></i>
        </button>
    <?php endif; ?>
    <?php if ($topbarShowMessageNotifications): ?>
        <button class="dashboard-icon-button badge-button dashboard-popover-trigger" type="button" aria-label="Buka pesan WhatsApp" aria-haspopup="dialog" aria-expanded="false" aria-controls="dashboardMessagesPanel" data-dashboard-popover-trigger="messages">
            <span class="dashboard-action-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false">
                    <path d="M21 12a8.5 8.5 0 0 1-9.9 8.4 8 8 0 0 1-3.5-1.5L3 20l1.2-4.1A8.3 8.3 0 1 1 21 12Z"></path>
                </svg>
            </span>
            <i data-dashboard-badge <?= $topbarMessageUnreadCount === 0 ? 'hidden' : '' ?>><?= htmlspecialchars($topbarBadgeText($topbarMessageUnreadCount), ENT_QUOTES, 'UTF-8') ?></i>
        </button>
    <?php endif; ?>
    <div class="dashboard-user">
        <span class="dashboard-avatar" aria-hidden="true"></span>
        <p><strong><?= $topbarAdminName ?></strong><small><?= $topbarAdminRole ?></small></p>
        <span aria-hidden="true">&#8964;</span>
    </div>

    <?php if ($topbarShowBrowserNotifications): ?>
        <section class="dashboard-popover dashboard-notification-popover" id="dashboardNotificationsPanel" role="dialog" aria-labelledby="dashboardNotificationsTitle" data-dashboard-popover="notifications" hidden>
        <div class="dashboard-popover-header">
            <h2 id="dashboardNotificationsTitle">Notifikasi</h2>
            <button class="dashboard-popover-link" type="button" data-dashboard-mark-read="notifications">Tandai semua sebagai dibaca</button>
        </div>
        <div class="dashboard-popover-list">
            <?php if ($topbarNotificationItems === []): ?>
                <article class="dashboard-popover-empty">
                    <strong>Belum ada notifikasi</strong>
                    <span>Aktivitas baru akan muncul di sini.</span>
                </article>
            <?php else: ?>
                <?php foreach ($topbarNotificationItems as $item): ?>
                    <?php $isUnread = !empty($item['is_unread']); ?>
                    <article class="dashboard-notification-item<?= $isUnread ? ' is-unread' : '' ?>">
                        <span class="dashboard-popover-icon <?= htmlspecialchars((string) ($item['tone'] ?? 'blue'), ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= $item['icon'] ?? '&#9672;' ?></span>
                        <div>
                            <h3><?= htmlspecialchars((string) ($item['title'] ?? 'Notifikasi'), ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= htmlspecialchars((string) ($item['detail'] ?? 'Aktivitas baru tercatat.'), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <time><?= htmlspecialchars((string) ($item['time'] ?? ''), ENT_QUOTES, 'UTF-8') ?></time>
                        <span class="dashboard-popover-dot" aria-hidden="true" <?= $isUnread ? '' : 'hidden' ?>></span>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="dashboard-popover-footer">
            <span class="dashboard-popover-mini-icon" aria-hidden="true">i</span>
            <p>Notifikasi membantu admin mengetahui aktivitas penting pada sistem tanpa harus membuka setiap halaman satu per satu.</p>
        </div>
        </section>
    <?php endif; ?>

    <?php if ($topbarShowMessageNotifications): ?>
        <section class="dashboard-popover dashboard-message-popover" id="dashboardMessagesPanel" role="dialog" aria-labelledby="dashboardMessagesTitle" data-dashboard-popover="messages" hidden>
        <div class="dashboard-popover-header">
            <h2 id="dashboardMessagesTitle">Pesan WhatsApp</h2>
            <button class="dashboard-popover-link" type="button" data-dashboard-mark-read="messages">Tandai semua sebagai dibaca</button>
        </div>
        <div class="dashboard-message-intro">
            <span class="dashboard-popover-mini-icon" aria-hidden="true">i</span>
            <p>Ini bukan chat internal. Panel ini berfungsi sebagai pengingat pesan WhatsApp dari pelanggan agar admin dapat segera membuka WhatsApp untuk membalas.</p>
        </div>
        <div class="dashboard-popover-list">
            <?php if ($topbarMessageItems === []): ?>
                <article class="dashboard-popover-empty">
                    <strong>Belum ada pesan baru</strong>
                    <span>Pengingat pesan pelanggan akan muncul di sini.</span>
                </article>
            <?php else: ?>
                <?php foreach ($topbarMessageItems as $item): ?>
                    <?php $isUnread = !empty($item['is_unread']); ?>
                    <article class="dashboard-message-item<?= $isUnread ? ' is-unread' : '' ?>">
                        <span class="dashboard-whatsapp-mark" aria-hidden="true">
                            <img src="<?= $topbarSafeBaseUrl ?>/assets/img/logo-wa.jpg?v=1" alt="">
                        </span>
                        <div>
                            <h3><?= htmlspecialchars((string) ($item['title'] ?? 'Pesan dari pelanggan'), ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= htmlspecialchars((string) ($item['detail'] ?? 'Pelanggan menghubungi admin melalui WhatsApp.'), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="dashboard-message-actions">
                            <time><?= htmlspecialchars((string) ($item['time'] ?? ''), ENT_QUOTES, 'UTF-8') ?></time>
                            <a class="dashboard-whatsapp-button" href="<?= htmlspecialchars((string) ($item['url'] ?? $topbarWhatsappUrl), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" data-dashboard-mark-read-link="messages">
                                <span aria-hidden="true">&#9990;</span>
                                Buka WhatsApp
                            </a>
                        </div>
                        <span class="dashboard-popover-dot dashboard-message-dot" aria-hidden="true" <?= $isUnread ? '' : 'hidden' ?>></span>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a class="dashboard-popover-action-row" href="<?= $topbarWhatsappUrl ?>" target="_blank" rel="noopener" data-dashboard-mark-read-link="messages">
            <span class="dashboard-popover-icon monitor" aria-hidden="true">&#128187;</span>
            <strong>Buka WhatsApp Web</strong>
            <span aria-hidden="true">&#8250;</span>
        </a>
        <div class="dashboard-popover-footer">
            <span class="dashboard-popover-mini-icon muted" aria-hidden="true">i</span>
            <p>Fitur pesan berfungsi sebagai pengingat bahwa terdapat pelanggan yang menghubungi admin melalui WhatsApp, sehingga admin dapat segera membuka WhatsApp untuk membalas pesan tersebut.</p>
        </div>
        </section>
    <?php endif; ?>
</div>
