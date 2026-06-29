<?php

use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/admin', [HomeController::class, 'adminDashboard']);
$router->get('/admin/cucian', [HomeController::class, 'adminCucian']);
$router->post('/admin/cucian', [HomeController::class, 'adminStoreCucian']);
$router->post('/admin/cucian/update', [HomeController::class, 'adminUpdateCucian']);
$router->post('/admin/cucian/delete', [HomeController::class, 'adminDeleteCucian']);
$router->get('/admin/transaksi', [HomeController::class, 'adminTransaksi']);
$router->get('/admin/update-status', [HomeController::class, 'adminUpdateStatus']);
$router->post('/admin/update-status', [HomeController::class, 'adminStoreStatus']);
$router->get('/admin/pelanggan', [HomeController::class, 'adminPelanggan']);
$router->post('/admin/pelanggan', [HomeController::class, 'adminStorePelanggan']);
$router->post('/admin/pelanggan/update', [HomeController::class, 'adminUpdatePelanggan']);
$router->post('/admin/pelanggan/delete', [HomeController::class, 'adminDeletePelanggan']);
$router->get('/admin/paket-laundry', [HomeController::class, 'adminPaketLaundry']);
$router->post('/admin/paket-laundry', [HomeController::class, 'adminStorePaketLaundry']);
$router->post('/admin/paket-laundry/update', [HomeController::class, 'adminUpdatePaketLaundry']);
$router->post('/admin/paket-laundry/delete', [HomeController::class, 'adminDeletePaketLaundry']);
$router->get('/admin/pengaturan', [HomeController::class, 'adminPengaturan']);
$router->post('/admin/pengaturan', [HomeController::class, 'adminStorePengaturan']);
$router->post('/admin/topbar/read', [HomeController::class, 'adminMarkTopbarRead']);
$router->get('/admin/login', [HomeController::class, 'adminLogin']);
$router->post('/admin/login', [HomeController::class, 'adminAuthenticate']);
$router->get('/admin/logout', [HomeController::class, 'adminLogout']);
