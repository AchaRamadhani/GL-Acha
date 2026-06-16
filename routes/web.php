<?php

use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/admin', [HomeController::class, 'adminDashboard']);
$router->get('/admin/cucian', [HomeController::class, 'adminCucian']);
$router->get('/admin/login', [HomeController::class, 'adminLogin']);
