<?php
$safeBaseUrl = htmlspecialchars($baseUrl ?? '', ENT_QUOTES, 'UTF-8');

$sidebarItems = [
    ['icon' => '&#8962;', 'label' => 'Dashboard', 'href' => '/admin'],
    ['icon' => '&#128203;', 'label' => 'Data Cucian', 'href' => '/admin/cucian'],
    ['icon' => '&#128722;', 'label' => 'Transaksi', 'href' => '/admin/transaksi'],
    ['icon' => '&#8635;', 'label' => 'Update Status', 'href' => '/admin/update-status'],
    ['icon' => '&#128101;', 'label' => 'Pelanggan', 'href' => '/admin/pelanggan'],
    ['icon' => '&#9672;', 'label' => 'Paket Laundry', 'href' => '/admin/paket-laundry'],
    ['icon' => '&#9881;', 'label' => 'Pengaturan', 'href' => '/admin/pengaturan', 'active' => true],
];

ob_start();
?>
<div class="admin-dashboard-page laundry-admin-page settings-admin-page">
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

        <main class="dashboard-main laundry-main settings-main">
            <section class="laundry-heading settings-heading">
                <div>
                    <h1>Pengaturan</h1>
                    <p>Atur informasi akun admin dan identitas Ghava Laundry.</p>
                </div>
            </section>

            <form class="settings-form" action="#" method="post" data-settings-form>
                <section class="settings-top-grid">
                    <article class="settings-card settings-profile-card">
                        <h2>Profil Admin</h2>

                        <div class="settings-profile-hero">
                            <span class="settings-profile-avatar" aria-hidden="true"></span>
                            <strong data-settings-admin-name>Admin Laundry</strong>
                            <small>Administrator</small>
                        </div>

                        <div class="settings-card-divider"></div>

                        <h3>Informasi Akun</h3>
                        <label class="settings-field" for="settingUsername" data-settings-field data-setting-label="Username">
                            <span>Username</span>
                            <span class="settings-input-shell">
                                <i aria-hidden="true">&#128100;</i>
                                <input id="settingUsername" name="username" type="text" value="admin.laundry" autocomplete="username">
                            </span>
                        </label>
                        <label class="settings-field" for="settingEmail" data-settings-field data-setting-label="Email">
                            <span>Email</span>
                            <span class="settings-input-shell">
                                <i aria-hidden="true">&#9993;</i>
                                <input id="settingEmail" name="email" type="email" value="admin@ghavalaundry.com" autocomplete="email">
                            </span>
                        </label>
                    </article>

                    <article class="settings-card settings-info-card">
                        <h2>Informasi Laundry</h2>

                        <div class="settings-field-grid">
                            <label class="settings-field" for="settingLaundryName" data-settings-field data-setting-label="Nama Laundry">
                                <span>Nama Laundry</span>
                                <input id="settingLaundryName" name="laundry_name" type="text" value="Ghava Laundry" data-laundry-name-input>
                            </label>
                            <label class="settings-field" for="settingWhatsapp" data-settings-field data-setting-label="Nomor WhatsApp">
                                <span>Nomor WhatsApp</span>
                                <span class="settings-input-shell">
                                    <i aria-hidden="true">&#128222;</i>
                                    <input id="settingWhatsapp" name="whatsapp" type="tel" value="0812-3456-7890">
                                </span>
                            </label>
                        </div>

                        <label class="settings-field" for="settingAddress" data-settings-field data-setting-label="Alamat">
                            <span>Alamat</span>
                            <input id="settingAddress" name="address" type="text" value="Jl. Sudirman No. 123, Kec. Sukmajaya, Kota Depok, Jawa Barat 16412">
                        </label>

                        <div class="settings-field settings-time-field" data-settings-field data-setting-label="Jam Operasional">
                            <span>Jam Operasional</span>
                            <div class="settings-time-row">
                                <label class="settings-select-shell" for="settingOpenTime">
                                    <i aria-hidden="true">&#128337;</i>
                                    <select id="settingOpenTime" name="open_time">
                                        <option>07:00</option>
                                        <option selected>08:00</option>
                                        <option>09:00</option>
                                    </select>
                                </label>
                                <strong>s/d</strong>
                                <label class="settings-select-shell" for="settingCloseTime">
                                    <i aria-hidden="true">&#128337;</i>
                                    <select id="settingCloseTime" name="close_time">
                                        <option>18:00</option>
                                        <option>19:00</option>
                                        <option selected>20:00</option>
                                        <option>21:00</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <label class="settings-field" for="settingMessage" data-settings-field data-setting-label="Pesan Otomatis WhatsApp">
                            <span>Pesan Otomatis WhatsApp saat status selesai</span>
                            <textarea id="settingMessage" name="message" maxlength="300" data-message-counter-source>Halo {nama_pelanggan}, cucian Anda dengan nomor pesanan {kode_pesanan} sudah selesai.
Silakan datang kapan saja. Terima kasih telah mempercayakan cucian Anda kepada Ghava Laundry! &#128522;</textarea>
                        </label>
                        <div class="settings-helper-row">
                            <small>Gunakan {nama_pelanggan} dan {kode_pesanan} sebagai placeholder dinamis.</small>
                            <small data-message-counter>0 / 300</small>
                        </div>
                    </article>
                </section>

                <section class="settings-bottom-grid">
                    <article class="settings-card settings-preference-card">
                        <h2>Preferensi Sistem</h2>

                        <label class="settings-preference-row" data-settings-field data-setting-label="Notifikasi Browser">
                            <span class="settings-preference-icon" aria-hidden="true">&#128276;</span>
                            <span>
                                <strong>Notifikasi Browser</strong>
                                <small>Tampilkan notifikasi pada browser</small>
                            </span>
                            <span class="settings-toggle">
                                <input type="checkbox" name="browser_notification" checked>
                                <i aria-hidden="true"></i>
                            </span>
                        </label>

                        <label class="settings-preference-row" data-settings-field data-setting-label="Notifikasi Pesan">
                            <span class="settings-preference-icon" aria-hidden="true">&#128172;</span>
                            <span>
                                <strong>Notifikasi Pesan</strong>
                                <small>Terima notifikasi pesan dari sistem</small>
                            </span>
                            <span class="settings-toggle">
                                <input type="checkbox" name="message_notification" checked>
                                <i aria-hidden="true"></i>
                            </span>
                        </label>

                        <label class="settings-preference-row" for="settingDateFormat" data-settings-field data-setting-label="Format Tanggal">
                            <span class="settings-preference-icon" aria-hidden="true">&#128197;</span>
                            <span>
                                <strong>Format Tanggal</strong>
                                <small>Pilih format tanggal yang digunakan</small>
                            </span>
                            <select id="settingDateFormat" name="date_format">
                                <option selected>DD MMMM YYYY</option>
                                <option>DD/MM/YYYY</option>
                                <option>YYYY-MM-DD</option>
                            </select>
                        </label>

                        <label class="settings-preference-row" data-settings-field data-setting-label="Konfirmasi Logout">
                            <span class="settings-preference-icon" aria-hidden="true">&#128737;</span>
                            <span>
                                <strong>Tampilkan Konfirmasi Logout</strong>
                                <small>Tampilkan dialog konfirmasi saat logout</small>
                            </span>
                            <span class="settings-toggle">
                                <input type="checkbox" name="logout_confirmation" checked data-logout-confirm-toggle>
                                <i aria-hidden="true"></i>
                            </span>
                        </label>
                    </article>

                    <article class="settings-card settings-security-card">
                        <h2>Keamanan Akun</h2>
                        <p><strong>Ubah Password</strong><small>Kosongkan jika tidak ingin mengubah password.</small></p>

                        <label class="settings-field" for="settingNewPassword" data-settings-field data-setting-label="Password Baru">
                            <span>Password Baru</span>
                            <span class="settings-password-shell">
                                <input id="settingNewPassword" name="new_password" type="password" placeholder="Masukkan password baru" autocomplete="new-password">
                                <button type="button" aria-label="Tampilkan password" data-settings-password-toggle="settingNewPassword">&#128065;</button>
                            </span>
                        </label>

                        <label class="settings-field" for="settingConfirmPassword" data-settings-field data-setting-label="Konfirmasi Password">
                            <span>Konfirmasi Password</span>
                            <span class="settings-password-shell">
                                <input id="settingConfirmPassword" name="confirm_password" type="password" placeholder="Konfirmasi password baru" autocomplete="new-password">
                                <button type="button" aria-label="Tampilkan password" data-settings-password-toggle="settingConfirmPassword">&#128065;</button>
                            </span>
                        </label>
                    </article>
                </section>

                <div class="settings-action-bar">
                    <p data-settings-feedback>Klik bagian form untuk mengedit data pengaturan.</p>
                    <button class="settings-save-button" type="submit">
                        <span aria-hidden="true">&#128190;</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <div class="settings-toast" role="status" aria-live="polite" data-settings-toast></div>
        </main>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
