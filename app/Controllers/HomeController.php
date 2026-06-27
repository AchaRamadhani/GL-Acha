<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\LaundryRepository;
use Throwable;

class HomeController extends Controller
{
    private ?LaundryRepository $laundry = null;

    public function index(): void
    {
        $nota = trim((string) ($_GET['nota'] ?? ''));
        $trackingResult = null;

        if ($nota !== '') {
            $trackingResult = $this->laundry()->trackingResult($nota);
            $this->laundry()->recordActivity(null, 'tracking', 'Tracking cucian dicek', 'Nomor nota: ' . $nota, $nota);
        }

        $this->view('home/index', [
            'title' => 'Ghava Laundry',
            'settings' => $this->laundry()->settings(),
            'packages' => $this->laundry()->packages(),
            'trackingResult' => $trackingResult,
            'trackingSearch' => $nota,
        ]);
    }

    public function adminLogin(): void
    {
        if (Auth::check()) {
            Auth::redirect('/admin');
        }

        $this->view('admin/login', [
            'title' => 'Login Admin - Ghava Laundry',
            'csrfToken' => Auth::csrfToken(),
            'loginError' => Auth::pullFlash('login_error'),
            'oldUsername' => Auth::pullFlash('old_username'),
        ]);
    }

    public function adminAuthenticate(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('login_error', 'Sesi login sudah kedaluwarsa. Silakan coba lagi.');
            Auth::flash('old_username', $username);
            Auth::redirect('/admin/login');
        }

        if (Auth::attempt($username, $password, $remember)) {
            Auth::redirect('/admin');
        }

        Auth::flash('login_error', 'Username atau password tidak sesuai.');
        Auth::flash('old_username', $username);
        Auth::redirect('/admin/login');
    }

    public function adminLogout(): void
    {
        Auth::logout();
        Auth::redirect('/admin/login');
    }

    public function adminDashboard(): void
    {
        Auth::requireAdmin();
        $summary = $this->laundry()->dashboardSummary();

        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin - Ghava Laundry',
            'admin' => Auth::user(),
            'summaryCards' => [
                ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => (string) $summary['customers'], 'meta' => 'Database'],
                ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Pesanan', 'value' => (string) $summary['orders'], 'meta' => 'Database'],
                ['tone' => 'purple', 'icon' => '&#128179;', 'label' => 'Total Pendapatan', 'value' => $this->formatCurrency((float) $summary['revenue']), 'meta' => 'Database'],
                ['tone' => 'orange', 'icon' => '&#128203;', 'label' => 'Pesanan Selesai', 'value' => (string) $summary['completed'], 'meta' => 'Database'],
            ],
            'orders' => $this->laundry()->orderRows(5),
            'services' => $this->laundry()->serviceSummary(),
            'statusSummary' => $this->laundry()->statusSummary(),
        ]);
    }

    public function adminCucian(): void
    {
        Auth::requireAdmin();
        $stats = $this->laundry()->transactionStats();

        $this->view('admin/cucian', [
            'title' => 'Data Cucian - Ghava Laundry',
            'admin' => Auth::user(),
            'csrfToken' => Auth::csrfToken(),
            'successMessage' => Auth::pullFlash('admin_success'),
            'errorMessage' => Auth::pullFlash('admin_error'),
            'stats' => [
                ['tone' => 'blue', 'icon' => '&#128722;', 'label' => 'Total Data Cucian', 'value' => (string) $stats['total'], 'meta' => 'Cucian'],
                ['tone' => 'orange', 'icon' => '&#9719;', 'label' => 'Sedang Diproses', 'value' => (string) $stats['open'], 'meta' => 'Cucian'],
                ['tone' => 'green', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => (string) $stats['completed'], 'meta' => 'Cucian'],
                ['tone' => 'purple', 'icon' => '&#128717;', 'label' => 'Diambil', 'value' => $this->statusValue('Diambil'), 'meta' => 'Cucian'],
            ],
            'laundryRows' => $this->laundry()->orderRows(10),
            'statusSummary' => $this->laundry()->statusSummary(),
            'activities' => $this->laundry()->activities(5, ['cucian', 'status', 'pengaturan', 'login', 'logout']),
            'packages' => $this->laundry()->packages(),
            'totalRows' => $this->laundry()->countOrders(),
        ]);
    }

    public function adminStoreCucian(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/cucian');
        }

        try {
            $nota = $this->laundry()->createLaundry($_POST, Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Data cucian berhasil disimpan dengan nomor nota ' . $nota . '.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/cucian');
    }

    public function adminTransaksi(): void
    {
        Auth::requireAdmin();
        $stats = $this->laundry()->transactionStats();

        $this->view('admin/transaksi', [
            'title' => 'Transaksi - Ghava Laundry',
            'admin' => Auth::user(),
            'stats' => [
                ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Transaksi', 'value' => (string) $stats['total'], 'meta' => 'Transaksi'],
                ['tone' => 'blue', 'icon' => '&#128197;', 'label' => 'Transaksi Hari Ini', 'value' => (string) $stats['today'], 'meta' => 'Transaksi'],
                ['tone' => 'orange', 'icon' => '&#9719;', 'label' => 'Belum Selesai', 'value' => (string) $stats['open'], 'meta' => 'Transaksi'],
                ['tone' => 'green', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => (string) $stats['completed'], 'meta' => 'Transaksi'],
            ],
            'transactions' => $this->laundry()->orderRows(10),
            'statusSummary' => $this->laundry()->statusSummary(),
            'noteActivities' => $this->laundry()->activities(5, ['cucian', 'status', 'tracking']),
            'transactionSummary' => $stats,
            'totalRows' => $this->laundry()->countOrders(),
        ]);
    }

    public function adminUpdateStatus(): void
    {
        Auth::requireAdmin();

        $this->view('admin/update-status', [
            'title' => 'Update Status - Ghava Laundry',
            'admin' => Auth::user(),
            'csrfToken' => Auth::csrfToken(),
            'successMessage' => Auth::pullFlash('admin_success'),
            'errorMessage' => Auth::pullFlash('admin_error'),
            'orders' => $this->laundry()->statusOrders(10),
            'stats' => $this->statusCards(),
        ]);
    }

    public function adminStoreStatus(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/update-status');
        }

        try {
            $this->laundry()->updateStatus(
                (string) ($_POST['no_nota'] ?? ''),
                (string) ($_POST['status'] ?? ''),
                (string) ($_POST['note'] ?? ''),
                Auth::currentAdminId() ?? 0
            );
            Auth::flash('admin_success', 'Status cucian berhasil diperbarui.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/update-status');
    }

    public function adminPelanggan(): void
    {
        Auth::requireAdmin();
        $stats = $this->laundry()->customerStats();
        $total = max(1, $stats['total']);

        $this->view('admin/pelanggan', [
            'title' => 'Pelanggan - Ghava Laundry',
            'admin' => Auth::user(),
            'stats' => [
                ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => (string) $stats['total'], 'meta' => 'Orang'],
                ['tone' => 'green', 'icon' => '&#128100;', 'label' => 'Pelanggan Bulan Ini', 'value' => (string) $stats['month'], 'meta' => 'Orang'],
                ['tone' => 'purple', 'icon' => '&#128100;&#43;', 'label' => 'Pelanggan Baru', 'value' => (string) $stats['new'], 'meta' => 'Orang'],
                ['tone' => 'orange', 'icon' => '&#128706;', 'label' => 'Cucian Berjalan', 'value' => (string) $stats['active'], 'meta' => 'Orang'],
            ],
            'customers' => $this->laundry()->customerRows(10),
            'customerSummary' => [
                ['label' => 'Pelanggan Aktif', 'value' => $stats['active'], 'percent' => $this->percent($stats['active'], $total), 'tone' => 'blue'],
                ['label' => 'Pelanggan Baru', 'value' => $stats['new'], 'percent' => $this->percent($stats['new'], $total), 'tone' => 'green'],
                ['label' => 'Pelanggan Lainnya', 'value' => max(0, $stats['total'] - $stats['active'] - $stats['new']), 'percent' => $this->percent(max(0, $stats['total'] - $stats['active'] - $stats['new']), $total), 'tone' => 'orange'],
            ],
            'activities' => $this->laundry()->activities(5, ['cucian', 'status', 'tracking']),
            'totalRows' => $this->laundry()->countCustomers(),
        ]);
    }

    public function adminPaketLaundry(): void
    {
        Auth::requireAdmin();

        $this->view('admin/paket-laundry', [
            'title' => 'Paket Laundry - Ghava Laundry',
            'admin' => Auth::user(),
            'packages' => $this->laundry()->packages(),
        ]);
    }

    public function adminPengaturan(): void
    {
        Auth::requireAdmin();

        $this->view('admin/pengaturan', [
            'title' => 'Pengaturan - Ghava Laundry',
            'admin' => Auth::user(),
            'settings' => $this->laundry()->settings(),
            'csrfToken' => Auth::csrfToken(),
            'successMessage' => Auth::pullFlash('admin_success'),
            'errorMessage' => Auth::pullFlash('admin_error'),
        ]);
    }

    public function adminStorePengaturan(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/pengaturan');
        }

        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($newPassword !== '' && $newPassword !== $confirmPassword) {
            Auth::flash('admin_error', 'Konfirmasi password belum sama.');
            Auth::redirect('/admin/pengaturan');
        }

        try {
            $admin = $this->laundry()->saveSettings($_POST, Auth::currentAdminId() ?? 0);
            Auth::syncUser($admin);
            Auth::flash('admin_success', 'Pengaturan berhasil disimpan ke database.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/pengaturan');
    }

    private function laundry(): LaundryRepository
    {
        if ($this->laundry === null) {
            $this->laundry = new LaundryRepository();
        }

        return $this->laundry;
    }

    private function statusCards(): array
    {
        $summary = $this->laundry()->statusSummary();
        $icons = [
            'Antrean' => '&#128101;',
            'Diproses' => '&#9881;',
            'Dicuci' => '&#128705;',
            'Dikeringkan' => '&#10043;',
            'Disetrika' => '&#9876;',
            'Selesai' => '&#10003;',
            'Diambil' => '&#128717;',
        ];

        return array_map(static fn (array $status): array => [
            'tone' => $status['tone'],
            'icon' => $icons[$status['label']] ?? '&#9672;',
            'label' => $status['label'],
            'value' => (string) $status['value'],
            'meta' => 'Cucian',
        ], array_slice($summary, 0, 4));
    }

    private function statusValue(string $status): string
    {
        foreach ($this->laundry()->statusSummary() as $item) {
            if ($item['label'] === $status) {
                return (string) $item['value'];
            }
        }

        return '0';
    }

    private function percent(int $value, int $total): string
    {
        return $total > 0 ? number_format(($value / $total) * 100, 1, ',', '.') . '%' : '0%';
    }

    private function formatCurrency(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
