<?php

namespace App\Models;

use App\Core\Model;
use DateTimeImmutable;
use DateTimeZone;
use PDO;
use Throwable;

class LaundryRepository extends Model
{
    private const STATUSES = ['Antrean', 'Diproses', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
    private const DATE_FORMATS = ['DD MMMM YYYY', 'DD/MM/YYYY', 'YYYY-MM-DD'];

    private const STATUS_TONES = [
        'Antrean' => 'blue',
        'Diproses' => 'green',
        'Dicuci' => 'teal',
        'Dikeringkan' => 'cyan',
        'Disetrika' => 'orange',
        'Selesai' => 'purple',
        'Diambil' => 'green',
    ];

    private const TOPBAR_NOTIFICATION_TYPES = ['cucian', 'status', 'pelanggan', 'paket', 'pengaturan', 'sistem'];
    private const TOPBAR_MESSAGE_TYPES = ['tracking'];

    private const DEFAULT_SETTINGS = [
        'admin_email' => 'admin@ghavalaundry.com',
        'laundry_name' => 'Ghava Laundry',
        'whatsapp' => '081242910340',
        'address' => 'Jl. Poros Hartaco Indah, Kelurahan Sudiang Raya, Kecamatan Biringkanaya, Kota Makassar, Sulawesi Selatan 90242',
        'open_time' => '07:00',
        'close_time' => '21:00',
        'message' => 'Halo {nama_pelanggan}, cucian Anda dengan nomor pesanan {kode_pesanan} sudah selesai. Silakan datang kapan saja. Terima kasih telah mempercayakan cucian Anda kepada Ghava Laundry!',
        'browser_notification' => '1',
        'message_notification' => '1',
        'date_format' => 'DD MMMM YYYY',
        'logout_confirmation' => '1',
    ];

    private ?array $settingsCache = null;

    private function pdo(): PDO
    {
        return $this->db->connection();
    }

    public function findAdminByUsername(string $username): ?array
    {
        $statement = $this->pdo()->prepare('SELECT * FROM admin WHERE username = :username LIMIT 1');
        $statement->execute(['username' => $username]);
        $admin = $statement->fetch();

        return is_array($admin) ? $admin : null;
    }

    public function findAdminById(int $id): ?array
    {
        $statement = $this->pdo()->prepare('SELECT * FROM admin WHERE id_admin = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $admin = $statement->fetch();

        return is_array($admin) ? $admin : null;
    }

    public function upgradeAdminPassword(int $adminId, string $password): void
    {
        $statement = $this->pdo()->prepare('UPDATE admin SET password = :password WHERE id_admin = :id');
        $statement->execute([
            'id' => $adminId,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function settings(): array
    {
        if ($this->settingsCache !== null) {
            return $this->settingsCache;
        }

        $settings = self::DEFAULT_SETTINGS;
        $statement = $this->pdo()->query('SELECT setting_key, setting_value FROM pengaturan');

        foreach ($statement->fetchAll() as $row) {
            $settings[$row['setting_key']] = (string) $row['setting_value'];
        }

        $this->settingsCache = $settings;

        return $settings;
    }

    public function saveSettings(array $payload, int $adminId): array
    {
        $admin = $this->findAdminById($adminId);
        $username = trim((string) ($payload['username'] ?? ($admin['username'] ?? 'admin')));
        $newPassword = (string) ($payload['new_password'] ?? '');
        $settings = $this->validatedSettingsPayload($payload);

        if ($username === '') {
            throw new \InvalidArgumentException('Username admin wajib diisi.');
        }

        if ($newPassword !== '' && strlen($newPassword) < 6) {
            throw new \InvalidArgumentException('Password baru minimal 6 karakter.');
        }

        $this->pdo()->beginTransaction();

        try {
            $statement = $this->pdo()->prepare(
                'INSERT INTO pengaturan (setting_key, setting_value)
                 VALUES (:setting_key, :setting_value)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
            );

            foreach ($settings as $key => $value) {
                $statement->execute([
                    'setting_key' => $key,
                    'setting_value' => $value,
                ]);
            }

            if ($username !== '' && $admin !== null) {
                if ($newPassword !== '') {
                    $adminStatement = $this->pdo()->prepare(
                        'UPDATE admin SET username = :username, password = :password WHERE id_admin = :id'
                    );
                    $adminStatement->execute([
                        'id' => $adminId,
                        'username' => $username,
                        'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                    ]);
                } else {
                    $adminStatement = $this->pdo()->prepare('UPDATE admin SET username = :username WHERE id_admin = :id');
                    $adminStatement->execute([
                        'id' => $adminId,
                        'username' => $username,
                    ]);
                }
            }

            $this->pdo()->commit();
            $this->settingsCache = null;
        } catch (Throwable $error) {
            $this->pdo()->rollBack();
            throw $error;
        }

        $this->recordActivity($adminId, 'pengaturan', 'Pengaturan diperbarui', 'Profil admin dan preferensi sistem disimpan.', 'pengaturan');

        return $this->findAdminById($adminId) ?? $admin ?? [];
    }

    public function packages(): array
    {
        $statement = $this->pdo()->query(
            'SELECT *
             FROM paket_laundry
             ORDER BY FIELD(kategori, "Kiloan", "Khusus"), id_paket'
        );

        return array_map(function (array $row): array {
            $category = $row['kategori'] ?: 'Kiloan';

            return [
                'id' => (int) $row['id_paket'],
                'code' => $this->formatPackageCode((int) $row['id_paket']),
                'name' => $row['nama_paket'],
                'description' => $row['deskripsi'] ?: 'Layanan laundry tersedia',
                'price' => $this->formatCurrency((float) $row['harga_per_kg']) . ($category === 'Kiloan' ? ' / kg' : ''),
                'raw_price' => (float) $row['harga_per_kg'],
                'duration' => ((int) $row['estimasi_hari']) . ' hari',
                'duration_days' => (int) $row['estimasi_hari'],
                'category' => $category,
                'tone' => $row['tone'] ?: 'blue',
                'icon' => $row['ikon'] ?: '&#128085;',
                'unit' => $row['unit_label'] ?: ($category === 'Kiloan' ? 'Minimal 3 kg' : 'Per item'),
                'status' => $row['status_aktif'],
            ];
        }, $statement->fetchAll());
    }

    public function nextPackageCode(): string
    {
        $tableStatus = $this->pdo()->query("SHOW TABLE STATUS LIKE 'paket_laundry'")->fetch();
        $nextId = is_array($tableStatus) ? (int) ($tableStatus['Auto_increment'] ?? 0) : 0;

        if ($nextId <= 0) {
            $nextId = (int) $this->pdo()->query('SELECT COALESCE(MAX(id_paket), 0) + 1 FROM paket_laundry')->fetchColumn();
        }

        return $this->formatPackageCode($nextId);
    }

    public function dashboardSummary(?array $range = null): array
    {
        [$customerCondition, $customerParams] = $this->dateRangeCondition('created_at', $range, 'summary_customer');
        [$transactionCondition, $transactionParams] = $this->dateRangeCondition('tanggal_masuk', $range, 'summary_transaction');
        $customerWhere = $this->whereClause([$customerCondition]);
        $transactionWhere = $this->whereClause([$transactionCondition]);
        $completedWhere = $this->whereClause([
            "status_cucian IN ('Selesai', 'Diambil')",
            $transactionCondition,
        ]);

        return [
            'customers' => (int) $this->scalar('SELECT COUNT(*) FROM pelanggan' . $customerWhere, $customerParams),
            'orders' => (int) $this->scalar('SELECT COUNT(*) FROM transaksi' . $transactionWhere, $transactionParams),
            'revenue' => (float) $this->scalar('SELECT COALESCE(SUM(total_harga), 0) FROM transaksi' . $transactionWhere, $transactionParams),
            'completed' => (int) $this->scalar('SELECT COUNT(*) FROM transaksi' . $completedWhere, $transactionParams),
        ];
    }

    public function revenueSummary(?array $range = null): array
    {
        [$dateCondition, $params] = $this->dateRangeCondition('tanggal_masuk', $range, 'revenue');
        $where = $this->whereClause([$dateCondition]);
        $total = (float) $this->scalar('SELECT COALESCE(SUM(total_harga), 0) FROM transaksi' . $where, $params);

        $statement = $this->pdo()->prepare(
            'SELECT DATE(tanggal_masuk) AS revenue_date, COALESCE(SUM(total_harga), 0) AS total
             FROM transaksi
             ' . $where . '
             GROUP BY DATE(tanggal_masuk)
             ORDER BY revenue_date'
        );
        $this->bindStatementParams($statement, $params);
        $statement->execute();

        $daily = [];
        $peak = 0.0;
        $peakDate = null;

        foreach ($statement->fetchAll() as $row) {
            $value = (float) $row['total'];
            $daily[] = [
                'date' => $row['revenue_date'],
                'value' => $value,
            ];

            if ($value > $peak) {
                $peak = $value;
                $peakDate = $row['revenue_date'];
            }
        }

        $days = $this->daysInRange($range);

        return [
            'total' => $total,
            'average' => $days > 0 ? $total / $days : 0,
            'peak' => $peak,
            'peak_date' => $peakDate,
            'max' => max($peak, 1),
            'daily' => $daily,
        ];
    }

    public function customerStats(): array
    {
        $pdo = $this->pdo();

        return [
            'total' => (int) $pdo->query('SELECT COUNT(*) FROM pelanggan')->fetchColumn(),
            'month' => (int) $pdo->query('SELECT COUNT(*) FROM pelanggan WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())')->fetchColumn(),
            'new' => (int) $pdo->query('SELECT COUNT(*) FROM pelanggan WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)')->fetchColumn(),
            'active' => (int) $pdo->query("SELECT COUNT(DISTINCT id_pelanggan) FROM transaksi WHERE status_cucian NOT IN ('Selesai', 'Diambil')")->fetchColumn(),
        ];
    }

    public function transactionStats(): array
    {
        $pdo = $this->pdo();

        return [
            'total' => (int) $pdo->query('SELECT COUNT(*) FROM transaksi')->fetchColumn(),
            'today' => (int) $pdo->query('SELECT COUNT(*) FROM transaksi WHERE DATE(tanggal_masuk) = CURDATE()')->fetchColumn(),
            'open' => (int) $pdo->query("SELECT COUNT(*) FROM transaksi WHERE status_cucian NOT IN ('Selesai', 'Diambil')")->fetchColumn(),
            'completed' => (int) $pdo->query("SELECT COUNT(*) FROM transaksi WHERE status_cucian IN ('Selesai', 'Diambil')")->fetchColumn(),
            'revenue' => (float) $pdo->query('SELECT COALESCE(SUM(total_harga), 0) FROM transaksi')->fetchColumn(),
        ];
    }

    public function transactionSummary(?array $range = null, array $filters = []): array
    {
        [$dateCondition, $params] = $this->dateRangeCondition('t.tanggal_masuk', $range, 'transaction_summary');
        [$filterConditions, $filterParams] = $this->orderFilterConditions($filters, 'transaction_summary');
        $where = $this->whereClause(array_merge([$dateCondition], $filterConditions));
        $params = array_merge($params, $filterParams);

        $statement = $this->pdo()->prepare(
            'SELECT
                COUNT(DISTINCT t.no_nota) AS total,
                COALESCE(SUM(t.total_harga), 0) AS revenue
             FROM transaksi t
             JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             ' . $where
        );
        $this->bindStatementParams($statement, $params);
        $statement->execute();

        $summary = $statement->fetch();

        return [
            'total' => is_array($summary) ? (int) $summary['total'] : 0,
            'revenue' => is_array($summary) ? (float) $summary['revenue'] : 0.0,
        ];
    }

    public function orderRows(int $limit = 10, ?array $range = null, array $filters = [], int $offset = 0): array
    {
        $limit = max(1, $limit);
        $offset = max(0, $offset);
        [$dateCondition, $params] = $this->dateRangeCondition('t.tanggal_masuk', $range, 'orders');
        [$filterConditions, $filterParams] = $this->orderFilterConditions($filters, 'orders');
        $where = $this->whereClause(array_merge([$dateCondition], $filterConditions));
        $params = array_merge($params, $filterParams);

        $statement = $this->pdo()->prepare(
            'SELECT
                t.no_nota,
                t.tanggal_masuk,
                t.estimasi_selesai,
                t.tanggal_selesai,
                t.status_cucian,
                t.total_harga,
                t.catatan,
                p.nama_pelanggan,
                p.no_telp,
                p.alamat,
                MIN(d.id_paket) AS id_paket,
                GROUP_CONCAT(pl.nama_paket ORDER BY d.id_detail SEPARATOR ", ") AS layanan,
                SUM(d.berat) AS total_berat,
                MIN(d.satuan) AS satuan
             FROM transaksi t
             JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             LEFT JOIN detail_transaksi d ON d.no_nota = t.no_nota
             LEFT JOIN paket_laundry pl ON pl.id_paket = d.id_paket
             ' . $where . '
             GROUP BY
                t.no_nota, t.tanggal_masuk, t.estimasi_selesai, t.tanggal_selesai,
                t.status_cucian, t.total_harga, t.catatan,
                p.nama_pelanggan, p.no_telp, p.alamat
             ORDER BY t.tanggal_masuk DESC, t.no_nota DESC
             LIMIT :limit OFFSET :offset'
        );
        $this->bindStatementParams($statement, $params);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $rows = [];

        foreach ($statement->fetchAll() as $index => $row) {
            $rows[] = $this->presentOrderRow($row, $offset + $index + 1);
        }

        return $rows;
    }

    public function customerRows(int $limit = 10, ?array $range = null, array $filters = [], int $offset = 0): array
    {
        $limit = max(1, $limit);
        $offset = max(0, $offset);
        [$dateCondition, $params] = $this->dateRangeCondition('p.created_at', $range, 'customers');
        [$filterConditions, $havingConditions, $filterParams] = $this->customerFilterConditions($filters, 'customers');
        $where = $this->whereClause(array_merge([$dateCondition], $filterConditions));
        $having = $this->havingClause($havingConditions);
        $params = array_merge($params, $filterParams);

        $statement = $this->pdo()->prepare(
            'SELECT
                p.id_pelanggan,
                p.nama_pelanggan,
                p.no_telp,
                p.alamat,
                p.created_at,
                COUNT(t.no_nota) AS transaksi_count,
                SUM(CASE WHEN t.status_cucian NOT IN ("Selesai", "Diambil") THEN 1 ELSE 0 END) AS active_count,
                MAX(t.tanggal_masuk) AS last_transaction
             FROM pelanggan p
             LEFT JOIN transaksi t ON t.id_pelanggan = p.id_pelanggan
             ' . $where . '
             GROUP BY p.id_pelanggan, p.nama_pelanggan, p.no_telp, p.alamat, p.created_at
             ' . $having . '
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset'
        );
        $this->bindStatementParams($statement, $params);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $customers = [];

        foreach ($statement->fetchAll() as $index => $row) {
            $address = (string) ($row['alamat'] ?? '');

            $customers[] = [
                'no' => $offset + $index + 1,
                'database_id' => (int) $row['id_pelanggan'],
                'id' => $this->formatCustomerCode((int) $row['id_pelanggan']),
                'name' => $row['nama_pelanggan'],
                'phone' => $row['no_telp'],
                'address' => $address !== '' ? $address : '-',
                'address_value' => $address,
                'transactions' => (int) $row['transaksi_count'],
                'active' => (int) $row['active_count'],
                'last' => $row['last_transaction'] ? $this->formatDate($row['last_transaction']) : '-',
                'created' => !empty($row['created_at']) ? $this->formatDate((string) $row['created_at']) : '-',
            ];
        }

        return $customers;
    }

    public function nextCustomerCode(): string
    {
        $nextId = (int) $this->pdo()->query('SELECT COALESCE(MAX(id_pelanggan), 0) + 1 FROM pelanggan')->fetchColumn();

        return $this->formatCustomerCode($nextId);
    }

    public function statusSummary(?array $range = null, array $filters = []): array
    {
        [$dateCondition, $params] = $this->dateRangeCondition('t.tanggal_masuk', $range, 'status');
        [$filterConditions, $filterParams] = $this->orderFilterConditions($filters, 'status');
        $where = $this->whereClause(array_merge([$dateCondition], $filterConditions));
        $params = array_merge($params, $filterParams);
        $statement = $this->pdo()->prepare(
            'SELECT t.status_cucian, COUNT(*) AS total
             FROM transaksi t
             JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             ' . $where . '
             GROUP BY t.status_cucian'
        );
        $this->bindStatementParams($statement, $params);
        $statement->execute();

        $counts = array_fill_keys(self::STATUSES, 0);
        $total = 0;

        foreach ($statement->fetchAll() as $row) {
            $counts[$row['status_cucian']] = (int) $row['total'];
            $total += (int) $row['total'];
        }

        $summary = [];

        foreach ($counts as $status => $count) {
            $summary[] = [
                'label' => $status,
                'value' => $count,
                'percent' => $total > 0 ? number_format(($count / $total) * 100, 1, ',', '.') . '%' : '0%',
                'tone' => self::STATUS_TONES[$status] ?? 'blue',
            ];
        }

        return $summary;
    }

    public function serviceSummary(?array $range = null): array
    {
        [$dateCondition, $params] = $this->dateRangeCondition('t.tanggal_masuk', $range, 'service');
        $joinFilter = $dateCondition !== '' ? ' AND ' . $dateCondition : '';

        $statement = $this->pdo()->prepare(
            'SELECT pl.nama_paket, COUNT(t.no_nota) AS total
             FROM paket_laundry pl
             LEFT JOIN detail_transaksi d ON d.id_paket = pl.id_paket
             LEFT JOIN transaksi t ON t.no_nota = d.no_nota' . $joinFilter . '
             GROUP BY pl.id_paket, pl.nama_paket
             ORDER BY total DESC, pl.id_paket'
        );
        $this->bindStatementParams($statement, $params);
        $statement->execute();

        $rows = $statement->fetchAll();
        $grandTotal = array_sum(array_map(static fn (array $row): int => (int) $row['total'], $rows));
        $max = max(1, ...array_map(static fn (array $row): int => (int) $row['total'], $rows));

        return array_map(static function (array $row) use ($grandTotal, $max): array {
            $count = (int) $row['total'];

            return [
                'name' => $row['nama_paket'],
                'count' => $count,
                'percent' => $grandTotal > 0 ? number_format(($count / $grandTotal) * 100, 1, ',', '.') . '%' : '0%',
                'width' => number_format(($count / $max) * 100, 1, '.', '') . '%',
            ];
        }, $rows);
    }

    public function statusOrders(int $limit = 10, ?array $range = null, array $filters = []): array
    {
        $orders = [];
        $history = $this->historyByNota();

        foreach ($this->orderRows($limit, $range, $filters) as $row) {
            $steps = [];
            $orderHistory = $history[$row['nota']] ?? [];

            foreach (array_reverse($orderHistory) as $item) {
                $steps[$item['status']] = [
                    'date' => $this->formatDate($item['created_at']),
                    'time' => $this->formatTime($item['created_at']),
                ];
            }

            $orders[] = [
                'key' => $row['nota'],
                'nota' => $row['nota'],
                'customer' => $row['name'],
                'service' => $row['service'],
                'currentStatus' => $row['status'],
                'tone' => $row['tone'],
                'in' => $row['in_long'],
                'eta' => $row['eta_long'],
                'steps' => $steps,
                'history' => array_map(function (array $item): array {
                    return [
                        'status' => $item['status'],
                        'tone' => self::STATUS_TONES[$item['status']] ?? 'blue',
                        'detail' => $item['detail'],
                        'staff' => $item['staff'],
                        'time' => $this->formatDateTime($item['created_at']),
                    ];
                }, $orderHistory),
            ];
        }

        return $orders;
    }

    public function createLaundry(array $payload, int $adminId): string
    {
        $customerName = trim((string) ($payload['customer_name'] ?? ''));
        $phone = trim((string) ($payload['phone'] ?? ''));
        $package = $this->findPackage((string) ($payload['service'] ?? ''));
        $weight = max(0, (float) ($payload['weight'] ?? 0));
        $unit = trim((string) ($payload['unit'] ?? 'kg')) ?: 'kg';
        $status = $this->normalizeStatus((string) ($payload['initial_status'] ?? 'Antrean'));
        $total = max(0, (float) ($payload['total'] ?? 0));
        $notes = trim((string) ($payload['notes'] ?? ''));
        $dateIn = $this->normalizeDate((string) ($payload['date_in'] ?? ''), true);
        $eta = $this->normalizeDate((string) ($payload['eta'] ?? ''), false);

        if ($customerName === '' || $phone === '' || $package === null || $weight <= 0 || $total <= 0) {
            throw new \InvalidArgumentException('Data cucian belum lengkap.');
        }

        $nota = $this->generateNota();

        $this->pdo()->beginTransaction();

        try {
            $customerId = $this->findOrCreateCustomer($customerName, $phone);

            $transaction = $this->pdo()->prepare(
                'INSERT INTO transaksi
                    (no_nota, id_pelanggan, id_admin, tanggal_masuk, estimasi_selesai, status_cucian, total_harga, catatan)
                 VALUES
                    (:no_nota, :id_pelanggan, :id_admin, :tanggal_masuk, :estimasi_selesai, :status_cucian, :total_harga, :catatan)'
            );
            $transaction->execute([
                'no_nota' => $nota,
                'id_pelanggan' => $customerId,
                'id_admin' => $adminId,
                'tanggal_masuk' => $dateIn,
                'estimasi_selesai' => $eta,
                'status_cucian' => $status,
                'total_harga' => $total,
                'catatan' => $notes !== '' ? $notes : null,
            ]);

            $detail = $this->pdo()->prepare(
                'INSERT INTO detail_transaksi (no_nota, id_paket, berat, subtotal, satuan)
                 VALUES (:no_nota, :id_paket, :berat, :subtotal, :satuan)'
            );
            $detail->execute([
                'no_nota' => $nota,
                'id_paket' => $package['id_paket'],
                'berat' => $weight,
                'subtotal' => $total,
                'satuan' => $unit,
            ]);

            $this->insertHistory($nota, $adminId, $status, 'Cucian baru diterima dan masuk sistem.');

            $this->pdo()->commit();
        } catch (Throwable $error) {
            $this->pdo()->rollBack();
            throw $error;
        }

        $this->recordActivity($adminId, 'cucian', 'Data cucian baru ditambahkan', $nota . ' - ' . $customerName, $nota);

        return $nota;
    }

    public function updateLaundry(array $payload, int $adminId): string
    {
        $nota = trim((string) ($payload['nota'] ?? ''));
        $customerName = trim((string) ($payload['customer_name'] ?? ''));
        $phone = trim((string) ($payload['phone'] ?? ''));
        $package = $this->findPackage((string) ($payload['service'] ?? ''));
        $weight = max(0, (float) ($payload['weight'] ?? 0));
        $unit = trim((string) ($payload['unit'] ?? 'kg')) ?: 'kg';
        $status = $this->normalizeStatus((string) ($payload['initial_status'] ?? 'Antrean'));
        $total = max(0, (float) ($payload['total'] ?? 0));
        $notes = trim((string) ($payload['notes'] ?? ''));
        $dateIn = $this->normalizeDate((string) ($payload['date_in'] ?? ''), true);
        $eta = $this->normalizeDate((string) ($payload['eta'] ?? ''), false);

        if ($nota === '' || $customerName === '' || $phone === '' || $package === null || $weight <= 0 || $total <= 0) {
            throw new \InvalidArgumentException('Data cucian belum lengkap.');
        }

        $current = $this->pdo()->prepare('SELECT status_cucian FROM transaksi WHERE no_nota = :nota LIMIT 1');
        $current->execute(['nota' => $nota]);
        $currentStatus = $current->fetchColumn();

        if (!$currentStatus) {
            throw new \InvalidArgumentException('Data cucian tidak ditemukan.');
        }

        $this->pdo()->beginTransaction();

        try {
            $customerId = $this->findOrCreateCustomer($customerName, $phone);
            $transaction = $this->pdo()->prepare(
                'UPDATE transaksi
                 SET id_pelanggan = :id_pelanggan,
                     id_admin = :id_admin,
                     tanggal_masuk = :tanggal_masuk,
                     estimasi_selesai = :estimasi_selesai,
                     status_cucian = :status_cucian,
                     tanggal_selesai = CASE
                         WHEN :status_done = 1 THEN COALESCE(tanggal_selesai, NOW())
                         ELSE tanggal_selesai
                     END,
                     total_harga = :total_harga,
                     catatan = :catatan
                 WHERE no_nota = :no_nota'
            );
            $transaction->execute([
                'id_pelanggan' => $customerId,
                'id_admin' => $adminId,
                'tanggal_masuk' => $dateIn,
                'estimasi_selesai' => $eta,
                'status_cucian' => $status,
                'status_done' => in_array($status, ['Selesai', 'Diambil'], true) ? 1 : 0,
                'total_harga' => $total,
                'catatan' => $notes !== '' ? $notes : null,
                'no_nota' => $nota,
            ]);

            $this->pdo()->prepare('DELETE FROM detail_transaksi WHERE no_nota = :nota')->execute(['nota' => $nota]);

            $detail = $this->pdo()->prepare(
                'INSERT INTO detail_transaksi (no_nota, id_paket, berat, subtotal, satuan)
                 VALUES (:no_nota, :id_paket, :berat, :subtotal, :satuan)'
            );
            $detail->execute([
                'no_nota' => $nota,
                'id_paket' => $package['id_paket'],
                'berat' => $weight,
                'subtotal' => $total,
                'satuan' => $unit,
            ]);

            if ($currentStatus !== $status) {
                $this->insertHistory($nota, $adminId, $status, 'Status cucian diperbarui melalui edit data cucian.');
            }

            $this->pdo()->commit();
        } catch (Throwable $error) {
            $this->pdo()->rollBack();
            throw $error;
        }

        $this->recordActivity($adminId, 'cucian', 'Data cucian diperbarui', $nota . ' - ' . $customerName, $nota);

        return $nota;
    }

    public function deleteLaundry(string $nota, int $adminId): string
    {
        $nota = trim($nota);

        if ($nota === '') {
            throw new \InvalidArgumentException('Nomor nota tidak valid.');
        }

        $statement = $this->pdo()->prepare(
            'SELECT t.no_nota, p.nama_pelanggan
             FROM transaksi t
             JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             WHERE t.no_nota = :nota
             LIMIT 1'
        );
        $statement->execute(['nota' => $nota]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            throw new \InvalidArgumentException('Data cucian tidak ditemukan.');
        }

        $this->pdo()->beginTransaction();

        try {
            $this->pdo()->prepare('DELETE FROM detail_transaksi WHERE no_nota = :nota')->execute(['nota' => $nota]);
            $this->pdo()->prepare('DELETE FROM riwayat_status WHERE no_nota = :nota')->execute(['nota' => $nota]);
            $delete = $this->pdo()->prepare('DELETE FROM transaksi WHERE no_nota = :nota');
            $delete->execute(['nota' => $nota]);

            if ($delete->rowCount() === 0) {
                throw new \InvalidArgumentException('Data cucian tidak ditemukan.');
            }

            $this->pdo()->commit();
        } catch (Throwable $error) {
            $this->pdo()->rollBack();
            throw $error;
        }

        $this->recordActivity($adminId, 'cucian', 'Data cucian dihapus', $nota . ' - ' . $row['nama_pelanggan'], $nota);

        return $nota;
    }

    public function createCustomer(array $payload, int $adminId): string
    {
        $customerName = trim((string) ($payload['customer_name'] ?? ''));
        $phone = trim((string) ($payload['phone'] ?? ''));
        $address = trim((string) ($payload['address'] ?? ''));

        if ($customerName === '' || $phone === '') {
            throw new \InvalidArgumentException('Data pelanggan belum lengkap.');
        }

        $existing = $this->pdo()->prepare('SELECT id_pelanggan FROM pelanggan WHERE no_telp = :phone LIMIT 1');
        $existing->execute(['phone' => $phone]);

        if ($existing->fetchColumn()) {
            throw new \InvalidArgumentException('No. telepon sudah terdaftar sebagai pelanggan.');
        }

        $insert = $this->pdo()->prepare(
            'INSERT INTO pelanggan (nama_pelanggan, no_telp, alamat, role)
             VALUES (:name, :phone, :address, "pelanggan")'
        );
        $insert->execute([
            'name' => $customerName,
            'phone' => $phone,
            'address' => $address !== '' ? $address : null,
        ]);

        $customerCode = $this->formatCustomerCode((int) $this->pdo()->lastInsertId());
        $this->recordActivity($adminId, 'pelanggan', 'Pelanggan baru ditambahkan', $customerCode . ' - ' . $customerName, $customerCode);

        return $customerCode;
    }

    public function updateCustomer(array $payload, int $adminId): string
    {
        $customerId = max(0, (int) ($payload['customer_id'] ?? 0));
        $customerName = trim((string) ($payload['customer_name'] ?? ''));
        $phone = trim((string) ($payload['phone'] ?? ''));
        $address = trim((string) ($payload['address'] ?? ''));

        if ($customerId <= 0) {
            throw new \InvalidArgumentException('Pelanggan tidak valid.');
        }

        if ($customerName === '' || $phone === '') {
            throw new \InvalidArgumentException('Data pelanggan belum lengkap.');
        }

        $current = $this->findCustomer($customerId);

        if ($current === null) {
            throw new \InvalidArgumentException('Data pelanggan tidak ditemukan.');
        }

        $existing = $this->pdo()->prepare(
            'SELECT id_pelanggan
             FROM pelanggan
             WHERE no_telp = :phone
                AND id_pelanggan <> :id
             LIMIT 1'
        );
        $existing->execute([
            'id' => $customerId,
            'phone' => $phone,
        ]);

        if ($existing->fetchColumn()) {
            throw new \InvalidArgumentException('No. telepon sudah terdaftar sebagai pelanggan lain.');
        }

        $update = $this->pdo()->prepare(
            'UPDATE pelanggan
             SET nama_pelanggan = :name,
                 no_telp = :phone,
                 alamat = :address
             WHERE id_pelanggan = :id'
        );
        $update->execute([
            'id' => $customerId,
            'name' => $customerName,
            'phone' => $phone,
            'address' => $address !== '' ? $address : null,
        ]);

        $customerCode = $this->formatCustomerCode($customerId);
        $this->recordActivity($adminId, 'pelanggan', 'Pelanggan diperbarui', $customerCode . ' - ' . $customerName, $customerCode);

        return $customerCode;
    }

    public function deleteCustomer(int $customerId, int $adminId): string
    {
        if ($customerId <= 0) {
            throw new \InvalidArgumentException('Pelanggan tidak valid.');
        }

        $customer = $this->findCustomer($customerId);

        if ($customer === null) {
            throw new \InvalidArgumentException('Data pelanggan tidak ditemukan.');
        }

        $transactionCount = (int) $this->scalar(
            'SELECT COUNT(*) FROM transaksi WHERE id_pelanggan = :id',
            ['id' => $customerId]
        );

        if ($transactionCount > 0) {
            throw new \InvalidArgumentException('Pelanggan sudah memiliki transaksi, sehingga tidak bisa dihapus agar riwayat laundry tetap tersimpan.');
        }

        $delete = $this->pdo()->prepare('DELETE FROM pelanggan WHERE id_pelanggan = :id');
        $delete->execute(['id' => $customerId]);

        if ($delete->rowCount() === 0) {
            throw new \InvalidArgumentException('Data pelanggan tidak ditemukan.');
        }

        $customerCode = $this->formatCustomerCode($customerId);
        $this->recordActivity($adminId, 'pelanggan', 'Pelanggan dihapus', $customerCode . ' - ' . $customer['nama_pelanggan'], $customerCode);

        return $customerCode;
    }

    public function createPackage(array $payload, int $adminId): string
    {
        $package = $this->validatedPackagePayload($payload);

        $existing = $this->pdo()->prepare('SELECT id_paket FROM paket_laundry WHERE nama_paket = :name LIMIT 1');
        $existing->execute(['name' => $package['name']]);

        if ($existing->fetchColumn()) {
            throw new \InvalidArgumentException('Nama paket sudah terdaftar.');
        }

        $insert = $this->pdo()->prepare(
            'INSERT INTO paket_laundry
                (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
             VALUES
                (:name, :price, :duration, :status, :description, :category, :tone, :icon, :unit_label)'
        );
        $insert->execute([
            'name' => $package['name'],
            'price' => $package['price'],
            'duration' => $package['duration'],
            'status' => $package['status'],
            'description' => $package['description'],
            'category' => $package['category'],
            'tone' => $this->packageTone($package['category'], $package['unit_label']),
            'icon' => $this->packageIcon($package['category'], $package['unit_label']),
            'unit_label' => $package['unit_label'],
        ]);

        $packageCode = $this->formatPackageCode((int) $this->pdo()->lastInsertId());
        $this->recordActivity($adminId, 'paket', 'Paket laundry baru ditambahkan', $packageCode . ' - ' . $package['name'], $packageCode);

        return $packageCode;
    }

    public function updatePackage(array $payload, int $adminId): string
    {
        $packageId = max(0, (int) ($payload['package_id'] ?? 0));

        if ($packageId <= 0) {
            throw new \InvalidArgumentException('Paket laundry tidak valid.');
        }

        $current = $this->findPackage((string) $packageId);

        if ($current === null) {
            throw new \InvalidArgumentException('Paket laundry tidak ditemukan.');
        }

        $package = $this->validatedPackagePayload($payload);
        $existing = $this->pdo()->prepare(
            'SELECT id_paket
             FROM paket_laundry
             WHERE nama_paket = :name
                AND id_paket <> :id
             LIMIT 1'
        );
        $existing->execute([
            'name' => $package['name'],
            'id' => $packageId,
        ]);

        if ($existing->fetchColumn()) {
            throw new \InvalidArgumentException('Nama paket sudah terdaftar.');
        }

        $update = $this->pdo()->prepare(
            'UPDATE paket_laundry
             SET nama_paket = :name,
                 harga_per_kg = :price,
                 estimasi_hari = :duration,
                 status_aktif = :status,
                 deskripsi = :description,
                 kategori = :category,
                 tone = :tone,
                 ikon = :icon,
                 unit_label = :unit_label
             WHERE id_paket = :id'
        );
        $update->execute([
            'id' => $packageId,
            'name' => $package['name'],
            'price' => $package['price'],
            'duration' => $package['duration'],
            'status' => $package['status'],
            'description' => $package['description'],
            'category' => $package['category'],
            'tone' => $this->packageTone($package['category'], $package['unit_label']),
            'icon' => $this->packageIcon($package['category'], $package['unit_label']),
            'unit_label' => $package['unit_label'],
        ]);

        $packageCode = $this->formatPackageCode($packageId);
        $this->recordActivity($adminId, 'paket', 'Paket laundry diperbarui', $packageCode . ' - ' . $package['name'], $packageCode);

        return $packageCode;
    }

    public function deletePackage(int $packageId, int $adminId): string
    {
        if ($packageId <= 0) {
            throw new \InvalidArgumentException('Paket laundry tidak valid.');
        }

        $package = $this->findPackage((string) $packageId);

        if ($package === null) {
            throw new \InvalidArgumentException('Paket laundry tidak ditemukan.');
        }

        $usedCount = (int) $this->scalar(
            'SELECT COUNT(*) FROM detail_transaksi WHERE id_paket = :id',
            ['id' => $packageId]
        );

        if ($usedCount > 0) {
            throw new \InvalidArgumentException('Paket sudah digunakan pada transaksi, sehingga tidak bisa dihapus. Ubah status menjadi Nonaktif jika layanan tidak ingin dipakai lagi.');
        }

        $delete = $this->pdo()->prepare('DELETE FROM paket_laundry WHERE id_paket = :id');
        $delete->execute(['id' => $packageId]);

        if ($delete->rowCount() === 0) {
            throw new \InvalidArgumentException('Paket laundry tidak ditemukan.');
        }

        $packageCode = $this->formatPackageCode($packageId);
        $this->recordActivity($adminId, 'paket', 'Paket laundry dihapus', $packageCode . ' - ' . $package['nama_paket'], $packageCode);

        return $packageCode;
    }

    public function updateStatus(string $nota, string $status, string $note, int $adminId): void
    {
        $status = $this->normalizeStatus($status);
        $nota = trim($nota);
        $note = trim($note);

        $this->pdo()->beginTransaction();

        try {
            $statement = $this->pdo()->prepare(
                'UPDATE transaksi
                 SET status_cucian = :status,
                     tanggal_selesai = CASE
                         WHEN :status_done = 1 THEN COALESCE(tanggal_selesai, NOW())
                         ELSE tanggal_selesai
                     END
                 WHERE no_nota = :nota'
            );
            $statement->execute([
                'status' => $status,
                'status_done' => in_array($status, ['Selesai', 'Diambil'], true) ? 1 : 0,
                'nota' => $nota,
            ]);

            if ($statement->rowCount() === 0) {
                throw new \InvalidArgumentException('Nomor nota tidak ditemukan.');
            }

            $this->insertHistory($nota, $adminId, $status, $note !== '' ? $note : 'Status cucian diperbarui menjadi ' . $status . '.');

            $this->pdo()->commit();
        } catch (Throwable $error) {
            $this->pdo()->rollBack();
            throw $error;
        }

        $this->recordActivity($adminId, 'status', 'Status cucian diperbarui', $nota . ' menjadi ' . $status, $nota);
    }

    public function trackingResult(string $nota): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT
                t.no_nota,
                t.tanggal_masuk,
                t.estimasi_selesai,
                t.status_cucian,
                p.nama_pelanggan,
                GROUP_CONCAT(pl.nama_paket ORDER BY d.id_detail SEPARATOR ", ") AS layanan
             FROM transaksi t
             JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             LEFT JOIN detail_transaksi d ON d.no_nota = t.no_nota
             LEFT JOIN paket_laundry pl ON pl.id_paket = d.id_paket
             WHERE t.no_nota = :nota
             GROUP BY t.no_nota, t.tanggal_masuk, t.estimasi_selesai, t.status_cucian, p.nama_pelanggan
             LIMIT 1'
        );
        $statement->execute(['nota' => $nota]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        $history = $this->historyByNota($nota)[$nota] ?? [];
        $steps = [];
        $currentIndex = array_search($row['status_cucian'], self::STATUSES, true);

        foreach (self::STATUSES as $index => $status) {
            $historyItem = $this->firstHistoryForStatus($history, $status);
            $state = '';

            if ($historyItem !== null || ($currentIndex !== false && $index < $currentIndex)) {
                $state = 'done';
            }

            if ($status === $row['status_cucian']) {
                $state = 'current';
            }

            $steps[] = [
                'label' => $status,
                'time' => $historyItem ? $this->formatDateTime($historyItem['created_at']) : 'Menunggu proses',
                'note' => $historyItem['detail'] ?? 'berikutnya.',
                'state' => $state,
            ];
        }

        return [
            'nota' => $row['no_nota'],
            'tanggal_masuk' => $this->formatDateTime($row['tanggal_masuk']),
            'nama_pelanggan' => $row['nama_pelanggan'],
            'estimasi_selesai' => $row['estimasi_selesai'] ? $this->formatDateTime($row['estimasi_selesai']) : '-',
            'layanan' => $row['layanan'] ?: '-',
            'status' => $row['status_cucian'],
            'steps' => $steps,
        ];
    }

    public function recordActivity(?int $adminId, string $type, string $title, ?string $detail = null, ?string $reference = null): void
    {
        try {
            $statement = $this->pdo()->prepare(
                'INSERT INTO aktivitas (id_admin, tipe, judul, detail, referensi, ip_address, user_agent)
                 VALUES (:id_admin, :tipe, :judul, :detail, :referensi, :ip_address, :user_agent)'
            );
            $statement->execute([
                'id_admin' => $adminId,
                'tipe' => $type,
                'judul' => $title,
                'detail' => $detail,
                'referensi' => $reference,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? substr((string) $_SERVER['HTTP_USER_AGENT'], 0, 255) : null,
            ]);
        } catch (Throwable) {
            // Activity logging must not block the main laundry workflow.
        }
    }

    public function activities(int $limit = 5, ?array $types = null): array
    {
        $where = '';
        $params = [];

        if ($types !== null && $types !== []) {
            $placeholders = [];
            foreach ($types as $index => $type) {
                $key = 'type_' . $index;
                $placeholders[] = ':' . $key;
                $params[$key] = $type;
            }
            $where = 'WHERE a.tipe IN (' . implode(',', $placeholders) . ')';
        }

        $statement = $this->pdo()->prepare(
            'SELECT a.*, COALESCE(ad.nama_lengkap, "Sistem") AS admin_name
             FROM aktivitas a
             LEFT JOIN admin ad ON ad.id_admin = a.id_admin
             ' . $where . '
             ORDER BY a.created_at DESC
             LIMIT :limit'
        );

        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return array_map(function (array $row): array {
            return [
                'icon' => $this->activityIcon($row['tipe']),
                'tone' => $this->activityTone($row['tipe']),
                'title' => $row['judul'],
                'detail' => $row['detail'] ?: ($row['admin_name'] ?? 'Sistem'),
                'time' => $this->timeAgo($row['created_at']),
                'name' => $row['admin_name'] ?? 'Sistem',
            ];
        }, $statement->fetchAll());
    }

    public function topbarNotifications(?int $adminId, int $limit = 5): array
    {
        $readMarker = $this->topbarReadMarker('notifications', $adminId);

        return [
            'items' => $this->topbarActivityItems(self::TOPBAR_NOTIFICATION_TYPES, $readMarker, $limit),
            'unread_count' => $this->topbarUnreadCount(self::TOPBAR_NOTIFICATION_TYPES, $readMarker),
        ];
    }

    public function topbarMessages(?int $adminId, int $limit = 5): array
    {
        $readMarker = $this->topbarReadMarker('messages', $adminId);

        return [
            'items' => $this->topbarMessageItems($readMarker, $limit),
            'unread_count' => $this->topbarUnreadCount(self::TOPBAR_MESSAGE_TYPES, $readMarker),
        ];
    }

    public function markTopbarRead(string $type, ?int $adminId): void
    {
        $types = match ($type) {
            'notifications' => self::TOPBAR_NOTIFICATION_TYPES,
            'messages' => self::TOPBAR_MESSAGE_TYPES,
            default => throw new \InvalidArgumentException('Jenis notifikasi tidak valid.'),
        };

        [$condition, $params] = $this->activityTypeCondition($types, 'mark_topbar');
        $lastId = (int) $this->scalar(
            'SELECT COALESCE(MAX(a.id_aktivitas), 0)
             FROM aktivitas a
             WHERE ' . $condition,
            $params
        );

        $this->saveSettingValue($this->topbarReadMarkerKey($type, $adminId), (string) $lastId);
    }

    public function countOrders(?array $range = null, array $filters = []): int
    {
        [$dateCondition, $params] = $this->dateRangeCondition('t.tanggal_masuk', $range, 'count_orders');
        [$filterConditions, $filterParams] = $this->orderFilterConditions($filters, 'count_orders');
        $where = $this->whereClause(array_merge([$dateCondition], $filterConditions));
        $params = array_merge($params, $filterParams);

        return (int) $this->scalar(
            'SELECT COUNT(DISTINCT t.no_nota)
             FROM transaksi t
             JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             ' . $where,
            $params
        );
    }

    public function countCustomers(?array $range = null, array $filters = []): int
    {
        [$dateCondition, $params] = $this->dateRangeCondition('p.created_at', $range, 'count_customers');
        [$filterConditions, $havingConditions, $filterParams] = $this->customerFilterConditions($filters, 'count_customers');
        $where = $this->whereClause(array_merge([$dateCondition], $filterConditions));
        $having = $this->havingClause($havingConditions);
        $params = array_merge($params, $filterParams);

        return (int) $this->scalar(
            'SELECT COUNT(*)
             FROM (
                SELECT
                    p.id_pelanggan,
                    SUM(CASE WHEN t.status_cucian NOT IN ("Selesai", "Diambil") THEN 1 ELSE 0 END) AS active_count
                FROM pelanggan p
                LEFT JOIN transaksi t ON t.id_pelanggan = p.id_pelanggan
                ' . $where . '
                GROUP BY p.id_pelanggan, p.created_at
                ' . $having . '
             ) customer_filter',
            $params
        );
    }

    private function topbarActivityItems(array $types, int $readMarker, int $limit): array
    {
        [$condition, $params] = $this->activityTypeCondition($types, 'topbar_activity');
        $statement = $this->pdo()->prepare(
            'SELECT a.*, COALESCE(ad.nama_lengkap, "Sistem") AS admin_name
             FROM aktivitas a
             LEFT JOIN admin ad ON ad.id_admin = a.id_admin
             WHERE ' . $condition . '
             ORDER BY a.created_at DESC, a.id_aktivitas DESC
             LIMIT :limit'
        );

        $this->bindStatementParams($statement, $params);
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();

        return array_map(function (array $row) use ($readMarker): array {
            return [
                'id' => (int) $row['id_aktivitas'],
                'icon' => $this->activityIcon($row['tipe']),
                'tone' => $this->activityTone($row['tipe']),
                'title' => $row['judul'],
                'detail' => $row['detail'] ?: ($row['admin_name'] ?? 'Sistem'),
                'time' => $this->timeAgo($row['created_at']),
                'is_unread' => (int) $row['id_aktivitas'] > $readMarker,
            ];
        }, $statement->fetchAll());
    }

    private function topbarMessageItems(int $readMarker, int $limit): array
    {
        [$condition, $params] = $this->activityTypeCondition(self::TOPBAR_MESSAGE_TYPES, 'topbar_message');
        $statement = $this->pdo()->prepare(
            'SELECT
                a.*,
                p.nama_pelanggan,
                p.no_telp
             FROM aktivitas a
             LEFT JOIN transaksi t ON t.no_nota = a.referensi
             LEFT JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
             WHERE ' . $condition . '
             ORDER BY a.created_at DESC, a.id_aktivitas DESC
             LIMIT :limit'
        );

        $this->bindStatementParams($statement, $params);
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();

        return array_map(function (array $row) use ($readMarker): array {
            $nota = trim((string) ($row['referensi'] ?? ''));
            $name = trim((string) ($row['nama_pelanggan'] ?? ''));
            $phone = $this->normalizeWhatsappNumber((string) ($row['no_telp'] ?? ''));

            return [
                'id' => (int) $row['id_aktivitas'],
                'title' => 'Pesan dari ' . ($name !== '' ? $name : 'pelanggan'),
                'detail' => $nota !== ''
                    ? 'Pelanggan menanyakan status cucian dengan nomor nota ' . $nota . '.'
                    : ((string) ($row['detail'] ?? '') ?: 'Pelanggan menghubungi admin melalui WhatsApp.'),
                'time' => $this->timeAgo($row['created_at']),
                'url' => $phone !== '' ? 'https://wa.me/' . $phone : 'https://web.whatsapp.com/',
                'is_unread' => (int) $row['id_aktivitas'] > $readMarker,
            ];
        }, $statement->fetchAll());
    }

    private function topbarUnreadCount(array $types, int $readMarker): int
    {
        [$condition, $params] = $this->activityTypeCondition($types, 'topbar_unread');
        $params['read_marker'] = $readMarker;

        return (int) $this->scalar(
            'SELECT COUNT(*)
             FROM aktivitas a
             WHERE a.id_aktivitas > :read_marker
               AND ' . $condition,
            $params
        );
    }

    private function topbarReadMarker(string $type, ?int $adminId): int
    {
        return max(0, (int) ($this->settings()[$this->topbarReadMarkerKey($type, $adminId)] ?? 0));
    }

    private function topbarReadMarkerKey(string $type, ?int $adminId): string
    {
        return 'topbar_' . $type . '_read_id_admin_' . max(0, (int) $adminId);
    }

    private function activityTypeCondition(array $types, string $prefix): array
    {
        if ($types === []) {
            return ['1 = 0', []];
        }

        $params = [];
        $placeholders = [];

        foreach (array_values($types) as $index => $type) {
            $key = $prefix . '_type_' . $index;
            $placeholders[] = ':' . $key;
            $params[$key] = $type;
        }

        return ['a.tipe IN (' . implode(',', $placeholders) . ')', $params];
    }

    private function saveSettingValue(string $key, string $value): void
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO pengaturan (setting_key, setting_value)
             VALUES (:setting_key, :setting_value)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $statement->execute([
            'setting_key' => $key,
            'setting_value' => $value,
        ]);

        $this->settingsCache = null;
    }

    private function normalizeWhatsappNumber(string $value): string
    {
        $phone = preg_replace('/\D+/', '', $value) ?: '';

        if ($phone === '') {
            return '';
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        return $phone;
    }

    private function validatedSettingsPayload(array $payload): array
    {
        $settings = self::DEFAULT_SETTINGS;
        $email = trim((string) ($payload['email'] ?? ''));
        $laundryName = trim((string) ($payload['laundry_name'] ?? ''));
        $whatsapp = preg_replace('/[^\d+]/', '', trim((string) ($payload['whatsapp'] ?? ''))) ?: '';
        $address = trim((string) ($payload['address'] ?? ''));
        $message = trim((string) ($payload['message'] ?? ''));
        $dateFormat = trim((string) ($payload['date_format'] ?? $settings['date_format']));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email admin belum valid.');
        }

        if ($laundryName === '') {
            throw new \InvalidArgumentException('Nama laundry wajib diisi.');
        }

        if ($whatsapp === '' || strlen(ltrim($whatsapp, '+')) < 9) {
            throw new \InvalidArgumentException('Nomor WhatsApp belum valid.');
        }

        if ($address === '') {
            throw new \InvalidArgumentException('Alamat laundry wajib diisi.');
        }

        if ($message === '') {
            throw new \InvalidArgumentException('Pesan otomatis WhatsApp wajib diisi.');
        }

        if (!in_array($dateFormat, self::DATE_FORMATS, true)) {
            $dateFormat = $settings['date_format'];
        }

        return [
            'admin_email' => $email,
            'laundry_name' => $laundryName,
            'whatsapp' => $whatsapp,
            'address' => $address,
            'open_time' => $this->normalizeSettingTime((string) ($payload['open_time'] ?? $settings['open_time']), $settings['open_time']),
            'close_time' => $this->normalizeSettingTime((string) ($payload['close_time'] ?? $settings['close_time']), $settings['close_time']),
            'message' => substr($message, 0, 300),
            'browser_notification' => isset($payload['browser_notification']) ? '1' : '0',
            'message_notification' => isset($payload['message_notification']) ? '1' : '0',
            'date_format' => $dateFormat,
            'logout_confirmation' => isset($payload['logout_confirmation']) ? '1' : '0',
        ];
    }

    private function normalizeSettingTime(string $time, string $fallback): string
    {
        $time = trim($time);

        if (preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $time)) {
            return $time;
        }

        return $fallback;
    }

    private function scalar(string $sql, array $params = [])
    {
        $statement = $this->pdo()->prepare($sql);
        $this->bindStatementParams($statement, $params);
        $statement->execute();

        return $statement->fetchColumn();
    }

    private function bindStatementParams(\PDOStatement $statement, array $params): void
    {
        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
    }

    private function dateRangeCondition(string $column, ?array $range, string $prefix): array
    {
        $start = $range['start'] ?? null;
        $end = $range['end'] ?? null;

        if (!$start || !$end) {
            return ['', []];
        }

        return [
            $column . ' >= :' . $prefix . '_start AND ' . $column . ' < :' . $prefix . '_end',
            [
                $prefix . '_start' => $start,
                $prefix . '_end' => $end,
            ],
        ];
    }

    private function orderFilterConditions(array $filters, string $prefix): array
    {
        $conditions = [];
        $params = [];

        $search = trim((string) ($filters['search'] ?? ''));
        $status = trim((string) ($filters['status'] ?? ''));
        $service = trim((string) ($filters['service'] ?? ''));

        if ($search !== '') {
            $conditions[] = '(t.no_nota LIKE :' . $prefix . '_search
                OR p.nama_pelanggan LIKE :' . $prefix . '_search
                OR p.no_telp LIKE :' . $prefix . '_search
                OR EXISTS (
                    SELECT 1
                    FROM detail_transaksi sd
                    JOIN paket_laundry sp ON sp.id_paket = sd.id_paket
                    WHERE sd.no_nota = t.no_nota
                        AND sp.nama_paket LIKE :' . $prefix . '_search
                ))';
            $params[$prefix . '_search'] = '%' . $search . '%';
        }

        if ($status !== '') {
            $conditions[] = 't.status_cucian = :' . $prefix . '_status';
            $params[$prefix . '_status'] = $status;
        }

        if ($service !== '') {
            $conditions[] = 'EXISTS (
                SELECT 1
                FROM detail_transaksi fd
                JOIN paket_laundry fp ON fp.id_paket = fd.id_paket
                WHERE fd.no_nota = t.no_nota
                    AND fp.nama_paket = :' . $prefix . '_service
            )';
            $params[$prefix . '_service'] = $service;
        }

        return [$conditions, $params];
    }

    private function customerFilterConditions(array $filters, string $prefix): array
    {
        $conditions = [];
        $havingConditions = [];
        $params = [];

        $search = trim((string) ($filters['search'] ?? ''));
        $status = trim((string) ($filters['status'] ?? ''));

        if ($search !== '') {
            $conditions[] = '(p.nama_pelanggan LIKE :' . $prefix . '_search
                OR p.no_telp LIKE :' . $prefix . '_search
                OR p.alamat LIKE :' . $prefix . '_search
                OR CONCAT("PLG-", LPAD(p.id_pelanggan, 4, "0")) LIKE :' . $prefix . '_search)';
            $params[$prefix . '_search'] = '%' . $search . '%';
        }

        if ($status === 'active') {
            $havingConditions[] = 'active_count > 0';
        } elseif ($status === 'new') {
            $conditions[] = 'p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
        } elseif ($status === 'inactive') {
            $havingConditions[] = 'active_count = 0';
        }

        return [$conditions, $havingConditions, $params];
    }

    private function whereClause(array $conditions): string
    {
        $conditions = array_values(array_filter($conditions, static fn ($condition): bool => trim((string) $condition) !== ''));

        return $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);
    }

    private function havingClause(array $conditions): string
    {
        $conditions = array_values(array_filter($conditions, static fn ($condition): bool => trim((string) $condition) !== ''));

        return $conditions === [] ? '' : ' HAVING ' . implode(' AND ', $conditions);
    }

    private function daysInRange(?array $range): int
    {
        if (empty($range['start']) || empty($range['end'])) {
            return 1;
        }

        try {
            $start = new DateTimeImmutable((string) $range['start']);
            $end = new DateTimeImmutable((string) $range['end']);

            return max(1, (int) $start->diff($end)->days);
        } catch (Throwable) {
            return 1;
        }
    }

    private function presentOrderRow(array $row, int $number): array
    {
        $weight = (float) ($row['total_berat'] ?? 0);
        $unit = $row['satuan'] ?: 'kg';

        return [
            'no' => $number,
            'nota' => $row['no_nota'],
            'name' => $row['nama_pelanggan'],
            'phone' => $row['no_telp'],
            'service' => $row['layanan'] ?: '-',
            'service_id' => (string) ($row['id_paket'] ?? ''),
            'weight' => str_replace('.', ',', rtrim(rtrim(number_format($weight, 2, '.', ''), '0'), '.')) . ' ' . $unit,
            'weight_value' => $weight,
            'unit' => $unit,
            'in' => $this->formatDate($row['tanggal_masuk']),
            'in_long' => $this->formatDateTime($row['tanggal_masuk']),
            'date_in_value' => substr((string) $row['tanggal_masuk'], 0, 10),
            'eta' => $row['estimasi_selesai'] ? $this->formatDate($row['estimasi_selesai']) : '-',
            'eta_long' => $row['estimasi_selesai'] ? $this->formatDateTime($row['estimasi_selesai']) : '-',
            'eta_value' => $row['estimasi_selesai'] ? substr((string) $row['estimasi_selesai'], 0, 10) : '',
            'total' => $this->formatCurrency((float) $row['total_harga']),
            'total_value' => (float) $row['total_harga'],
            'status' => $row['status_cucian'],
            'tone' => self::STATUS_TONES[$row['status_cucian']] ?? 'blue',
            'notes' => (string) ($row['catatan'] ?? ''),
        ];
    }

    private function formatPackageCode(int $packageId): string
    {
        return 'PKT-' . str_pad((string) $packageId, 3, '0', STR_PAD_LEFT);
    }

    private function formatCustomerCode(int $customerId): string
    {
        return 'PLG-' . str_pad((string) $customerId, 4, '0', STR_PAD_LEFT);
    }

    private function validatedPackagePayload(array $payload): array
    {
        $packageName = trim((string) ($payload['package_name'] ?? ''));
        $category = trim((string) ($payload['category'] ?? ''));
        $price = max(0, (float) ($payload['price'] ?? 0));
        $duration = max(0, (int) ($payload['duration'] ?? 0));
        $unitLabel = trim((string) ($payload['unit_label'] ?? ''));
        $status = trim((string) ($payload['status'] ?? 'Aktif'));
        $description = trim((string) ($payload['description'] ?? ''));

        if ($packageName === '' || $category === '' || $price <= 0 || $duration <= 0 || $unitLabel === '') {
            throw new \InvalidArgumentException('Data paket laundry belum lengkap.');
        }

        return [
            'name' => $packageName,
            'price' => $price,
            'duration' => $duration,
            'status' => $status === 'Nonaktif' ? 'Nonaktif' : 'Aktif',
            'description' => $description !== '' ? substr($description, 0, 200) : null,
            'category' => in_array($category, ['Kiloan', 'Khusus'], true) ? $category : 'Kiloan',
            'unit_label' => $unitLabel,
        ];
    }

    private function packageTone(string $category, string $unitLabel): string
    {
        if ($category === 'Kiloan') {
            return 'blue';
        }

        return str_contains(strtolower($unitLabel), 'cepat') ? 'amber' : 'teal';
    }

    private function packageIcon(string $category, string $unitLabel): string
    {
        if ($category === 'Kiloan') {
            return '&#128085;';
        }

        if (str_contains(strtolower($unitLabel), 'cepat')) {
            return '&#9889;';
        }

        if (str_contains(strtolower($unitLabel), 'rawatan')) {
            return '&#128737;';
        }

        return '&#128717;';
    }

    private function findOrCreateCustomer(string $name, string $phone): int
    {
        $statement = $this->pdo()->prepare('SELECT id_pelanggan FROM pelanggan WHERE no_telp = :phone LIMIT 1');
        $statement->execute(['phone' => $phone]);
        $customerId = $statement->fetchColumn();

        if ($customerId) {
            $update = $this->pdo()->prepare('UPDATE pelanggan SET nama_pelanggan = :name WHERE id_pelanggan = :id');
            $update->execute(['id' => $customerId, 'name' => $name]);

            return (int) $customerId;
        }

        $insert = $this->pdo()->prepare(
            'INSERT INTO pelanggan (nama_pelanggan, no_telp, alamat, role)
             VALUES (:name, :phone, NULL, "pelanggan")'
        );
        $insert->execute([
            'name' => $name,
            'phone' => $phone,
        ]);

        return (int) $this->pdo()->lastInsertId();
    }

    private function findPackage(string $value): ?array
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            $statement = $this->pdo()->prepare('SELECT * FROM paket_laundry WHERE id_paket = :value LIMIT 1');
        } else {
            $statement = $this->pdo()->prepare('SELECT * FROM paket_laundry WHERE nama_paket = :value LIMIT 1');
        }

        $statement->execute(['value' => $value]);
        $package = $statement->fetch();

        return is_array($package) ? $package : null;
    }

    private function findCustomer(int $customerId): ?array
    {
        if ($customerId <= 0) {
            return null;
        }

        $statement = $this->pdo()->prepare('SELECT * FROM pelanggan WHERE id_pelanggan = :id LIMIT 1');
        $statement->execute(['id' => $customerId]);
        $customer = $statement->fetch();

        return is_array($customer) ? $customer : null;
    }

    private function generateNota(): string
    {
        $prefix = 'GL' . date('Ymd') . '-';
        $statement = $this->pdo()->prepare('SELECT no_nota FROM transaksi WHERE no_nota LIKE :prefix ORDER BY no_nota DESC LIMIT 1');
        $statement->execute(['prefix' => $prefix . '%']);
        $latest = (string) ($statement->fetchColumn() ?: '');
        $nextNumber = 1;

        if ($latest !== '') {
            $nextNumber = ((int) substr($latest, -3)) + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    private function insertHistory(string $nota, int $adminId, string $status, string $detail): void
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO riwayat_status (no_nota, id_admin, status_cucian, keterangan)
             VALUES (:nota, :admin, :status, :detail)'
        );
        $statement->execute([
            'nota' => $nota,
            'admin' => $adminId,
            'status' => $status,
            'detail' => $detail,
        ]);
    }

    private function historyByNota(?string $nota = null): array
    {
        $where = '';
        $params = [];

        if ($nota !== null) {
            $where = 'WHERE r.no_nota = :nota';
            $params['nota'] = $nota;
        }

        $statement = $this->pdo()->prepare(
            'SELECT
                r.no_nota,
                r.status_cucian AS status,
                r.keterangan AS detail,
                r.waktu_update AS created_at,
                COALESCE(a.nama_lengkap, "Admin Laundry") AS staff
             FROM riwayat_status r
             LEFT JOIN admin a ON a.id_admin = r.id_admin
             ' . $where . '
             ORDER BY r.waktu_update DESC, r.id_riwayat DESC'
        );
        $statement->execute($params);

        $history = [];

        foreach ($statement->fetchAll() as $row) {
            $history[$row['no_nota']][] = $row;
        }

        return $history;
    }

    private function firstHistoryForStatus(array $history, string $status): ?array
    {
        foreach (array_reverse($history) as $item) {
            if ($item['status'] === $status) {
                return $item;
            }
        }

        return null;
    }

    private function normalizeStatus(string $status): string
    {
        return in_array($status, self::STATUSES, true) ? $status : 'Antrean';
    }

    private function normalizeDate(string $date, bool $startOfDay): string
    {
        if ($date === '') {
            return date('Y-m-d H:i:s');
        }

        $time = $startOfDay ? ' 08:00:00' : ' 17:00:00';

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date . $time : date('Y-m-d H:i:s', strtotime($date));
    }

    private function formatCurrency(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    private function formatDate(string $date): string
    {
        return $this->formatDateTimeParts($date, false);
    }

    private function formatDateTime(string $date): string
    {
        return $this->formatDateTimeParts($date, true);
    }

    private function formatTime(string $date): string
    {
        return (new DateTimeImmutable($date, new DateTimeZone('Asia/Makassar')))->format('H:i');
    }

    private function formatDateTimeParts(string $date, bool $withTime): string
    {
        $dt = new DateTimeImmutable($date, new DateTimeZone('Asia/Makassar'));
        $format = $this->settings()['date_format'] ?? self::DEFAULT_SETTINGS['date_format'];

        if ($format === 'DD/MM/YYYY') {
            $value = $dt->format('d/m/Y');
        } elseif ($format === 'YYYY-MM-DD') {
            $value = $dt->format('Y-m-d');
        } else {
            $months = [
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
            ];
            $value = $dt->format('j') . ' ' . $months[(int) $dt->format('n')] . ' ' . $dt->format('Y');
        }

        return $withTime ? $value . ', ' . $dt->format('H:i') : $value;
    }

    private function timeAgo(string $date): string
    {
        $created = new DateTimeImmutable($date, new DateTimeZone('Asia/Makassar'));
        $now = new DateTimeImmutable('now', new DateTimeZone('Asia/Makassar'));
        $seconds = max(0, $now->getTimestamp() - $created->getTimestamp());

        if ($seconds < 60) {
            return 'Baru saja';
        }

        if ($seconds < 3600) {
            return floor($seconds / 60) . ' mnt lalu';
        }

        if ($seconds < 86400) {
            return floor($seconds / 3600) . ' jam lalu';
        }

        return floor($seconds / 86400) . ' hari lalu';
    }

    private function activityIcon(string $type): string
    {
        return [
            'cucian' => '+',
            'pelanggan' => '&#128101;',
            'paket' => '&#9672;',
            'status' => '&#10003;',
            'login' => '&#128274;',
            'logout' => '&#8617;',
            'pengaturan' => '&#9881;',
            'tracking' => '&#128269;',
            'sistem' => '&#8505;',
        ][$type] ?? '&#9672;';
    }

    private function activityTone(string $type): string
    {
        return [
            'cucian' => 'blue',
            'pelanggan' => 'purple',
            'paket' => 'orange',
            'status' => 'green',
            'login' => 'purple',
            'logout' => 'orange',
            'pengaturan' => 'blue',
            'tracking' => 'teal',
            'sistem' => 'blue',
        ][$type] ?? 'blue';
    }
}
