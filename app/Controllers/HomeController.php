<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => 'Ghava Laundry',
        ]);
    }

    public function adminLogin(): void
    {
        $this->view('admin/login', [
            'title' => 'Login Admin - Ghava Laundry',
        ]);
    }

    public function adminDashboard(): void
    {
        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin - Ghava Laundry',
        ]);
    }

    public function adminCucian(): void
    {
        $this->view('admin/cucian', [
            'title' => 'Data Cucian - Ghava Laundry',
        ]);
    }
}
