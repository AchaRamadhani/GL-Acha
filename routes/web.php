<?php

use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/admin', [HomeController::class, 'adminDashboard']);
$router->get('/admin/cucian', [HomeController::class, 'adminCucian']);
$router->get('/admin/transaksi', [HomeController::class, 'adminTransaksi']);
$router->get('/admin/update-status', [HomeController::class, 'adminUpdateStatus']);
$router->get('/admin/pelanggan', [HomeController::class, 'adminPelanggan']);
$router->get('/admin/paket-laundry', [HomeController::class, 'adminPaketLaundry']);
$router->get('/admin/pengaturan', [HomeController::class, 'adminPengaturan']);
$router->get('/admin/login', [HomeController::class, 'adminLogin']);
