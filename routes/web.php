<?php

use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/admin', [HomeController::class, 'adminDashboard']);
$router->get('/admin/cucian', [HomeController::class, 'adminCucian']);
$router->post('/admin/cucian', [HomeController::class, 'adminStoreCucian']);
$router->get('/admin/transaksi', [HomeController::class, 'adminTransaksi']);
$router->get('/admin/update-status', [HomeController::class, 'adminUpdateStatus']);
$router->post('/admin/update-status', [HomeController::class, 'adminStoreStatus']);
$router->get('/admin/pelanggan', [HomeController::class, 'adminPelanggan']);
$router->post('/admin/pelanggan', [HomeController::class, 'adminStorePelanggan']);
$router->get('/admin/paket-laundry', [HomeController::class, 'adminPaketLaundry']);
$router->post('/admin/paket-laundry', [HomeController::class, 'adminStorePaketLaundry']);
$router->get('/admin/pengaturan', [HomeController::class, 'adminPengaturan']);
$router->post('/admin/pengaturan', [HomeController::class, 'adminStorePengaturan']);
$router->get('/admin/login', [HomeController::class, 'adminLogin']);
$router->post('/admin/login', [HomeController::class, 'adminAuthenticate']);
$router->get('/admin/logout', [HomeController::class, 'adminLogout']);
