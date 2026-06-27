USE gl_acha;

ALTER TABLE admin
    MODIFY role VARCHAR(50) NOT NULL DEFAULT 'admin';

ALTER TABLE pelanggan
    MODIFY role VARCHAR(50) NOT NULL DEFAULT 'pelanggan';

UPDATE admin
SET role = 'admin'
WHERE role = '';

UPDATE pelanggan
SET role = 'pelanggan'
WHERE role = '';

UPDATE admin
SET password = '$2y$10$VpY5s7nREpQnnrTepNJnHOWTI0e92Couttyi5hljEOzUBIkx9Fah2'
WHERE username = 'admin';

ALTER TABLE detail_transaksi
    ADD COLUMN IF NOT EXISTS satuan VARCHAR(10) NOT NULL DEFAULT 'kg';

ALTER TABLE transaksi
    ADD COLUMN IF NOT EXISTS estimasi_selesai DATETIME NULL AFTER tanggal_masuk;

UPDATE transaksi
SET estimasi_selesai = COALESCE(estimasi_selesai, DATE_ADD(tanggal_masuk, INTERVAL 2 DAY));

ALTER TABLE paket_laundry
    ADD COLUMN IF NOT EXISTS deskripsi VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS kategori VARCHAR(50) NOT NULL DEFAULT 'Kiloan',
    ADD COLUMN IF NOT EXISTS tone VARCHAR(30) NOT NULL DEFAULT 'blue',
    ADD COLUMN IF NOT EXISTS ikon VARCHAR(40) NOT NULL DEFAULT '&#128085;',
    ADD COLUMN IF NOT EXISTS unit_label VARCHAR(80) NOT NULL DEFAULT 'Minimal 3 kg';

CREATE TABLE IF NOT EXISTS pengaturan (
    setting_key VARCHAR(80) NOT NULL PRIMARY KEY,
    setting_value TEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS aktivitas (
    id_aktivitas INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    id_admin INT(10) UNSIGNED NULL,
    tipe VARCHAR(50) NOT NULL,
    judul VARCHAR(120) NOT NULL,
    detail VARCHAR(255) NULL,
    referensi VARCHAR(80) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_aktivitas),
    KEY idx_aktivitas_tipe (tipe),
    KEY idx_aktivitas_created_at (created_at),
    KEY fk_aktivitas_admin (id_admin),
    CONSTRAINT fk_aktivitas_admin FOREIGN KEY (id_admin)
        REFERENCES admin (id_admin)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pengaturan (setting_key, setting_value) VALUES
    ('admin_email', 'admin@ghavalaundry.com'),
    ('laundry_name', 'Ghava Laundry'),
    ('whatsapp', '081242910340'),
    ('address', 'Jl. Poros Hartaco Indah, Kelurahan Sudiang Raya, Kecamatan Biringkanaya, Kota Makassar, Sulawesi Selatan 90242'),
    ('open_time', '07:00'),
    ('close_time', '21:00'),
    ('message', 'Halo {nama_pelanggan}, cucian Anda dengan nomor pesanan {kode_pesanan} sudah selesai. Silakan datang kapan saja. Terima kasih telah mempercayakan cucian Anda kepada Ghava Laundry!'),
    ('browser_notification', '1'),
    ('message_notification', '1'),
    ('date_format', 'DD MMMM YYYY'),
    ('logout_confirmation', '1')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

UPDATE paket_laundry
SET nama_paket = 'Cuci Setrika Lipat'
WHERE nama_paket = 'Cuci Setrika';

INSERT INTO paket_laundry (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
SELECT 'Cuci Lipat', 8000, 1, 'Aktif', 'Pakaian dicuci dan dilipat rapi', 'Kiloan', 'green', '&#9636;', 'Minimal 3 kg'
WHERE NOT EXISTS (SELECT 1 FROM paket_laundry WHERE nama_paket = 'Cuci Lipat');

INSERT INTO paket_laundry (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
SELECT 'Pengering & Lipat', 8000, 1, 'Aktif', 'Pakaian dikeringkan dan dilipat', 'Kiloan', 'purple', '&#128260;', 'Minimal 3 kg'
WHERE NOT EXISTS (SELECT 1 FROM paket_laundry WHERE nama_paket = 'Pengering & Lipat');

INSERT INTO paket_laundry (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
SELECT 'Baju Bayi', 15000, 2, 'Aktif', 'Pencucian khusus pakaian bayi', 'Kiloan', 'pink', '&#128087;', 'Minimal 3 kg'
WHERE NOT EXISTS (SELECT 1 FROM paket_laundry WHERE nama_paket = 'Baju Bayi');

INSERT INTO paket_laundry (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
SELECT 'Satuan', 5000, 1, 'Aktif', 'Cucian berdasarkan jumlah item', 'Khusus', 'teal', '&#128717;', 'Per item'
WHERE NOT EXISTS (SELECT 1 FROM paket_laundry WHERE nama_paket = 'Satuan');

INSERT INTO paket_laundry (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
SELECT 'Express', 25000, 1, 'Aktif', 'Layanan cepat selesai di hari yang sama', 'Khusus', 'amber', '&#9889;', 'Layanan cepat'
WHERE NOT EXISTS (SELECT 1 FROM paket_laundry WHERE nama_paket = 'Express');

INSERT INTO paket_laundry (nama_paket, harga_per_kg, estimasi_hari, status_aktif, deskripsi, kategori, tone, ikon, unit_label)
SELECT 'Treatment', 20000, 2, 'Aktif', 'Perawatan khusus noda dan bahan', 'Khusus', 'blue', '&#128737;', 'Perawatan khusus'
WHERE NOT EXISTS (SELECT 1 FROM paket_laundry WHERE nama_paket = 'Treatment');

UPDATE paket_laundry SET
    deskripsi = 'Pakaian dicuci dan dikeringkan',
    kategori = 'Kiloan',
    tone = 'blue',
    ikon = '&#128085;',
    unit_label = 'Minimal 3 kg'
WHERE nama_paket = 'Cuci Kering';

UPDATE paket_laundry SET
    deskripsi = 'Pakaian dicuci, disetrika, dan dilipat',
    kategori = 'Kiloan',
    tone = 'purple',
    ikon = '&#128239;',
    unit_label = 'Minimal 3 kg'
WHERE nama_paket = 'Cuci Setrika Lipat';

UPDATE paket_laundry SET
    deskripsi = 'Pakaian hanya disetrika',
    kategori = 'Kiloan',
    tone = 'orange',
    ikon = '&#128239;',
    unit_label = 'Minimal 3 kg'
WHERE nama_paket = 'Setrika Saja';

UPDATE paket_laundry SET
    deskripsi = COALESCE(deskripsi, 'Pakaian dicuci dan dilipat rapi'),
    kategori = COALESCE(NULLIF(kategori, ''), 'Kiloan'),
    tone = COALESCE(NULLIF(tone, ''), 'green'),
    ikon = COALESCE(NULLIF(ikon, ''), '&#9636;'),
    unit_label = COALESCE(NULLIF(unit_label, ''), 'Minimal 3 kg')
WHERE nama_paket = 'Cuci Lipat';

INSERT INTO aktivitas (id_admin, tipe, judul, detail, referensi)
SELECT id_admin, 'sistem', 'Database aktivitas disiapkan', 'Tabel pengaturan dan aktivitas sudah tersedia.', 'migration'
FROM admin
WHERE username = 'admin'
  AND NOT EXISTS (
      SELECT 1 FROM aktivitas
      WHERE tipe = 'sistem' AND referensi = 'migration'
  );
