<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\LaundryRepository;
use DateTimeImmutable;
use DateTimeZone;
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
        $period = $this->dashboardPeriod();
        $summary = $this->laundry()->dashboardSummary($period['range']);
        $revenueStats = $this->laundry()->revenueSummary($period['range']);
        $periodMeta = $period['short_label'];

        $this->adminView('admin/dashboard', [
            'title' => 'Dashboard Admin - Ghava Laundry',
            'admin' => Auth::user(),
            'dashboardPeriod' => $period,
            'revenueStats' => $revenueStats,
            'summaryCards' => [
                ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => (string) $summary['customers'], 'meta' => $periodMeta],
                ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Pesanan', 'value' => (string) $summary['orders'], 'meta' => $periodMeta],
                ['tone' => 'purple', 'icon' => '&#128179;', 'label' => 'Total Pendapatan', 'value' => $this->formatCurrency((float) $summary['revenue']), 'meta' => $periodMeta],
                ['tone' => 'orange', 'icon' => '&#128203;', 'label' => 'Pesanan Selesai', 'value' => (string) $summary['completed'], 'meta' => $periodMeta],
            ],
            'orders' => $this->laundry()->orderRows(5, $period['range']),
            'services' => $this->laundry()->serviceSummary($period['range']),
            'statusSummary' => $this->laundry()->statusSummary($period['range']),
            'statusOptions' => $this->laundryStatusOptions(),
        ]);
    }

    public function adminCucian(): void
    {
        Auth::requireAdmin();
        $stats = $this->laundry()->transactionStats();
        $packages = $this->laundry()->packages();
        $filters = $this->laundryFilters();
        $dateRange = $filters['range'];
        $totalRows = $this->laundry()->countOrders($dateRange, $filters);
        $pagination = $this->pagination($totalRows);

        $this->adminView('admin/cucian', [
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
            'laundryRows' => $this->laundry()->orderRows($pagination['perPage'], $dateRange, $filters, $pagination['offset']),
            'statusSummary' => $this->laundry()->statusSummary($dateRange, $filters),
            'activities' => $this->laundry()->activities(5, ['cucian', 'status', 'pengaturan', 'login', 'logout']),
            'packages' => $packages,
            'serviceOptions' => $this->serviceOptions($packages),
            'statusOptions' => $this->laundryStatusOptions(),
            'filters' => $filters,
            'totalRows' => $totalRows,
            'pagination' => $pagination,
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

    public function adminUpdateCucian(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/cucian');
        }

        try {
            $nota = $this->laundry()->updateLaundry($_POST, Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Data cucian ' . $nota . ' berhasil diperbarui.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/cucian');
    }

    public function adminDeleteCucian(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/cucian');
        }

        try {
            $nota = $this->laundry()->deleteLaundry((string) ($_POST['nota'] ?? ''), Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Data cucian ' . $nota . ' berhasil dihapus.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/cucian');
    }

    public function adminTransaksi(): void
    {
        Auth::requireAdmin();
        $stats = $this->laundry()->transactionStats();
        $packages = $this->laundry()->packages();
        $filters = $this->laundryFilters();
        $dateRange = $filters['range'];
        $totalRows = $this->laundry()->countOrders($dateRange, $filters);
        $pagination = $this->pagination($totalRows);

        $this->adminView('admin/transaksi', [
            'title' => 'Transaksi - Ghava Laundry',
            'admin' => Auth::user(),
            'stats' => [
                ['tone' => 'green', 'icon' => '&#128722;', 'label' => 'Total Transaksi', 'value' => (string) $stats['total'], 'meta' => 'Transaksi'],
                ['tone' => 'blue', 'icon' => '&#128197;', 'label' => 'Transaksi Hari Ini', 'value' => (string) $stats['today'], 'meta' => 'Transaksi'],
                ['tone' => 'orange', 'icon' => '&#9719;', 'label' => 'Belum Selesai', 'value' => (string) $stats['open'], 'meta' => 'Transaksi'],
                ['tone' => 'green', 'icon' => '&#10003;', 'label' => 'Selesai', 'value' => (string) $stats['completed'], 'meta' => 'Transaksi'],
            ],
            'transactions' => $this->laundry()->orderRows($pagination['perPage'], $dateRange, $filters, $pagination['offset']),
            'statusSummary' => $this->laundry()->statusSummary($dateRange, $filters),
            'noteActivities' => $this->laundry()->activities(5, ['cucian', 'status', 'tracking']),
            'transactionSummary' => $this->laundry()->transactionSummary($dateRange, $filters),
            'serviceOptions' => $this->serviceOptions($packages),
            'statusOptions' => $this->laundryStatusOptions(),
            'filters' => $filters,
            'totalRows' => $totalRows,
            'pagination' => $pagination,
        ]);
    }

    public function adminUpdateStatus(): void
    {
        Auth::requireAdmin();
        $packages = $this->laundry()->packages();
        $filters = $this->laundryFilters();
        $dateRange = $filters['range'];

        $this->adminView('admin/update-status', [
            'title' => 'Update Status - Ghava Laundry',
            'admin' => Auth::user(),
            'csrfToken' => Auth::csrfToken(),
            'successMessage' => Auth::pullFlash('admin_success'),
            'errorMessage' => Auth::pullFlash('admin_error'),
            'orders' => $this->laundry()->statusOrders(10, $dateRange, $filters),
            'stats' => $this->statusCards(),
            'serviceOptions' => $this->serviceOptions($packages),
            'statusOptions' => $this->laundryStatusOptions(),
            'filters' => $filters,
            'totalRows' => $this->laundry()->countOrders($dateRange, $filters),
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
        $filters = $this->customerFilters();
        $dateRange = $filters['range'];
        $totalRows = $this->laundry()->countCustomers($dateRange, $filters);
        $pagination = $this->pagination($totalRows);

        $this->adminView('admin/pelanggan', [
            'title' => 'Pelanggan - Ghava Laundry',
            'admin' => Auth::user(),
            'csrfToken' => Auth::csrfToken(),
            'successMessage' => Auth::pullFlash('admin_success'),
            'errorMessage' => Auth::pullFlash('admin_error'),
            'nextCustomerCode' => $this->laundry()->nextCustomerCode(),
            'stats' => [
                ['tone' => 'blue', 'icon' => '&#128101;', 'label' => 'Total Pelanggan', 'value' => (string) $stats['total'], 'meta' => 'Orang'],
                ['tone' => 'green', 'icon' => '&#128100;', 'label' => 'Pelanggan Bulan Ini', 'value' => (string) $stats['month'], 'meta' => 'Orang'],
                ['tone' => 'purple', 'icon' => '&#128100;&#43;', 'label' => 'Pelanggan Baru', 'value' => (string) $stats['new'], 'meta' => 'Orang'],
                ['tone' => 'orange', 'icon' => '&#128706;', 'label' => 'Cucian Berjalan', 'value' => (string) $stats['active'], 'meta' => 'Orang'],
            ],
            'customers' => $this->laundry()->customerRows($pagination['perPage'], $dateRange, $filters, $pagination['offset']),
            'customerSummary' => [
                ['label' => 'Pelanggan Aktif', 'value' => $stats['active'], 'percent' => $this->percent($stats['active'], $total), 'tone' => 'blue'],
                ['label' => 'Pelanggan Baru', 'value' => $stats['new'], 'percent' => $this->percent($stats['new'], $total), 'tone' => 'green'],
                ['label' => 'Pelanggan Lainnya', 'value' => max(0, $stats['total'] - $stats['active'] - $stats['new']), 'percent' => $this->percent(max(0, $stats['total'] - $stats['active'] - $stats['new']), $total), 'tone' => 'orange'],
            ],
            'activities' => $this->laundry()->activities(5, ['cucian', 'status', 'tracking', 'pelanggan']),
            'filters' => $filters,
            'customerStatusOptions' => $this->customerStatusOptions(),
            'totalRows' => $totalRows,
            'pagination' => $pagination,
        ]);
    }

    public function adminStorePelanggan(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/pelanggan');
        }

        try {
            $customerCode = $this->laundry()->createCustomer($_POST, Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Data pelanggan berhasil disimpan dengan ID ' . $customerCode . '.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/pelanggan');
    }

    public function adminUpdatePelanggan(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/pelanggan');
        }

        try {
            $customerCode = $this->laundry()->updateCustomer($_POST, Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Data pelanggan ' . $customerCode . ' berhasil diperbarui.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/pelanggan');
    }

    public function adminDeletePelanggan(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/pelanggan');
        }

        try {
            $customerCode = $this->laundry()->deleteCustomer((int) ($_POST['customer_id'] ?? 0), Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Data pelanggan ' . $customerCode . ' berhasil dihapus.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/pelanggan');
    }

    public function adminPaketLaundry(): void
    {
        Auth::requireAdmin();

        $this->adminView('admin/paket-laundry', [
            'title' => 'Paket Laundry - Ghava Laundry',
            'admin' => Auth::user(),
            'csrfToken' => Auth::csrfToken(),
            'successMessage' => Auth::pullFlash('admin_success'),
            'errorMessage' => Auth::pullFlash('admin_error'),
            'nextPackageCode' => $this->laundry()->nextPackageCode(),
            'packages' => $this->laundry()->packages(),
        ]);
    }

    public function adminStorePaketLaundry(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/paket-laundry');
        }

        try {
            $packageCode = $this->laundry()->createPackage($_POST, Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Paket laundry berhasil disimpan dengan ID ' . $packageCode . '.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/paket-laundry');
    }

    public function adminUpdatePaketLaundry(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/paket-laundry');
        }

        try {
            $packageCode = $this->laundry()->updatePackage($_POST, Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Paket laundry ' . $packageCode . ' berhasil diperbarui.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/paket-laundry');
    }

    public function adminDeletePaketLaundry(): void
    {
        Auth::requireAdmin();

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('admin_error', 'Sesi form sudah kedaluwarsa. Silakan coba lagi.');
            Auth::redirect('/admin/paket-laundry');
        }

        try {
            $packageCode = $this->laundry()->deletePackage((int) ($_POST['package_id'] ?? 0), Auth::currentAdminId() ?? 0);
            Auth::flash('admin_success', 'Paket laundry ' . $packageCode . ' berhasil dihapus.');
        } catch (Throwable $error) {
            Auth::flash('admin_error', $error->getMessage());
        }

        Auth::redirect('/admin/paket-laundry');
    }

    public function adminPengaturan(): void
    {
        Auth::requireAdmin();

        $this->adminView('admin/pengaturan', [
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

    public function adminMarkTopbarRead(): void
    {
        Auth::requireAdmin();
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            http_response_code(419);
            echo json_encode(['ok' => false, 'message' => 'Sesi sudah kedaluwarsa.']);
            return;
        }

        try {
            $this->laundry()->markTopbarRead((string) ($_POST['type'] ?? ''), Auth::currentAdminId());
            echo json_encode(['ok' => true]);
        } catch (Throwable $error) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'message' => $error->getMessage()]);
        }
    }

    private function laundry(): LaundryRepository
    {
        if ($this->laundry === null) {
            $this->laundry = new LaundryRepository();
        }

        return $this->laundry;
    }

    private function adminView(string $view, array $data): void
    {
        $adminId = Auth::currentAdminId();

        $data += [
            'admin' => Auth::user(),
            'settings' => $this->laundry()->settings(),
            'csrfToken' => Auth::csrfToken(),
            'topbarNotifications' => $this->laundry()->topbarNotifications($adminId),
            'topbarMessages' => $this->laundry()->topbarMessages($adminId),
        ];

        $this->view($view, $data);
    }

    private function laundryFilters(): array
    {
        $statusOptions = $this->laundryStatusOptions();
        $status = trim((string) ($_GET['status'] ?? ''));
        $status = in_array($status, $statusOptions, true) ? $status : '';
        $dateFrom = $this->normalizeDateValue($_GET['date_from'] ?? '');
        $dateTo = $this->normalizeDateValue($_GET['date_to'] ?? '');

        if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $range = null;

        if ($dateFrom !== '' || $dateTo !== '') {
            $startDate = $dateFrom !== '' ? $dateFrom : $dateTo;
            $endDate = $dateTo !== '' ? $dateTo : $dateFrom;
            $timezone = new DateTimeZone('Asia/Makassar');
            $start = new DateTimeImmutable($startDate . ' 00:00:00', $timezone);
            $end = (new DateTimeImmutable($endDate . ' 00:00:00', $timezone))->modify('+1 day');

            $range = [
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'search' => trim((string) ($_GET['q'] ?? '')),
            'status' => $status,
            'service' => trim((string) ($_GET['service'] ?? '')),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'range' => $range,
        ];
    }

    private function pagination(int $totalRows, int $defaultPerPage = 10): array
    {
        $perPageOptions = [10, 25, 50];
        $perPage = (int) ($_GET['per_page'] ?? $defaultPerPage);

        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = $defaultPerPage;
        }

        $totalPages = max(1, (int) ceil(max(0, $totalRows) / $perPage));
        $page = (int) ($_GET['page'] ?? 1);
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        return [
            'page' => $page,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'totalPages' => $totalPages,
            'offset' => $offset,
            'from' => $totalRows > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, max(0, $totalRows)),
        ];
    }

    private function customerFilters(): array
    {
        $statusOptions = array_column($this->customerStatusOptions(), 'value');
        $status = trim((string) ($_GET['status'] ?? ''));
        $status = in_array($status, $statusOptions, true) ? $status : '';
        $dateFrom = $this->normalizeDateValue($_GET['date_from'] ?? '');
        $dateTo = $this->normalizeDateValue($_GET['date_to'] ?? '');

        if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $range = null;

        if ($dateFrom !== '' || $dateTo !== '') {
            $startDate = $dateFrom !== '' ? $dateFrom : $dateTo;
            $endDate = $dateTo !== '' ? $dateTo : $dateFrom;
            $timezone = new DateTimeZone('Asia/Makassar');
            $start = new DateTimeImmutable($startDate . ' 00:00:00', $timezone);
            $end = (new DateTimeImmutable($endDate . ' 00:00:00', $timezone))->modify('+1 day');

            $range = [
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'search' => trim((string) ($_GET['q'] ?? '')),
            'status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'range' => $range,
        ];
    }

    private function normalizeDateValue($value): string
    {
        $value = trim((string) $value);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return '';
        }

        try {
            return (new DateTimeImmutable($value))->format('Y-m-d');
        } catch (Throwable) {
            return '';
        }
    }

    private function laundryStatusOptions(): array
    {
        return ['Antrean', 'Diproses', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
    }

    private function customerStatusOptions(): array
    {
        return [
            ['value' => 'active', 'label' => 'Pelanggan Aktif'],
            ['value' => 'new', 'label' => 'Pelanggan Baru'],
            ['value' => 'inactive', 'label' => 'Pelanggan Tidak Aktif'],
        ];
    }

    private function dashboardPeriod(): array
    {
        $timezone = new DateTimeZone('Asia/Makassar');
        $today = new DateTimeImmutable('now', $timezone);
        $currentStart = $today->modify('first day of this month')->setTime(0, 0);
        $requested = trim((string) ($_GET['month'] ?? $currentStart->format('Y-m')));

        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $requested)) {
            $requested = $currentStart->format('Y-m');
        }

        try {
            $start = (new DateTimeImmutable($requested . '-01 00:00:00', $timezone))->setTime(0, 0);
        } catch (Throwable) {
            $start = $currentStart;
        }

        $end = $start->modify('first day of next month')->setTime(0, 0);
        $shortLabel = $this->dashboardMonthShortLabel($start);
        $rangeLabel = $this->dashboardMonthRangeLabel($start);

        return [
            'selected_month' => $start->format('Y-m'),
            'short_label' => $shortLabel,
            'range_label' => $rangeLabel,
            'label' => $this->dashboardMonthPickerLabel($start),
            'range' => [
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
            ],
            'options' => $this->dashboardMonthOptions($start),
        ];
    }

    private function dashboardMonthOptions(DateTimeImmutable $selectedStart): array
    {
        $options = [];
        $year = (int) $selectedStart->format('Y');

        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $month = $selectedStart->setDate($year, $monthNumber, 1)->setTime(0, 0);
            $value = $month->format('Y-m');

            $options[] = [
                'value' => $value,
                'label' => $this->dashboardMonthPickerLabel($month),
            ];
        }

        return $options;
    }

    private function dashboardMonthPickerLabel(DateTimeImmutable $monthStart): string
    {
        return $this->monthName((int) $monthStart->format('n')) . ' '
            . $monthStart->format('Y') . ' (' . $this->dashboardMonthRangeLabel($monthStart) . ')';
    }

    private function dashboardMonthShortLabel(DateTimeImmutable $monthStart): string
    {
        return $this->monthName((int) $monthStart->format('n')) . ' ' . $monthStart->format('Y');
    }

    private function dashboardMonthRangeLabel(DateTimeImmutable $monthStart): string
    {
        return sprintf(
            '1 - %d %s %s',
            (int) $monthStart->format('t'),
            $this->monthName((int) $monthStart->format('n')),
            $monthStart->format('Y')
        );
    }

    private function monthName(int $month): string
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ][$month] ?? 'Januari';
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

    private function serviceOptions(array $packages): array
    {
        $options = [];

        foreach ($packages as $package) {
            if (($package['status'] ?? 'Aktif') === 'Nonaktif') {
                continue;
            }

            $name = trim((string) ($package['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $options[] = [
                'id' => (string) ($package['id'] ?? $name),
                'name' => $name,
                'label' => $name,
            ];
        }

        if ($options !== []) {
            return $options;
        }

        return array_map(static fn (string $name): array => [
            'id' => $name,
            'name' => $name,
            'label' => $name,
        ], [
            'Cuci Kering',
            'Cuci Lipat',
            'Cuci Setrika Lipat',
            'Setrika Saja',
            'Pengering & Lipat',
            'Baju Bayi',
            'Satuan',
            'Express',
            'Treatment',
        ]);
    }

    private function formatCurrency(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
